<?php
/**
 * Template Name: Payment
 */

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;
use NatOkpe\Wp\Theme\Impact\Utils\Request;

if ( ! function_exists( 'wp_handle_upload' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
}

$screen          = 'error';
$screen_errors   = [];
$cnt             = [
    'user'       => wp_get_current_user(),
    'page_title' => 'Payment Error',
];

$checkpoint = is_int(Request::get('item_id')) ||
(is_string(Request::get('item_id')) && ctype_digit(Request::get('item_id')));
$checkpoint = $checkpoint &&
in_array(get_post((int) Request::get('item_id'))->post_type ?? null, [
    'course',
]);

if ($checkpoint) {
    $screen                      = 'summary';
    $cnt['page_title']           = 'Payment Summary';
    $cnt['item']                 = get_post((int) Request::get('item_id'));
    $cnt['item_price']           = (int) get_post_meta($cnt['item']->ID, 'enrollment_fee', true);
    $cnt['item_payment_pending'] = ! empty((new WP_Query([
        'post_type'    => 'payment',
        'nopaging'     => true,
        'meta_query'   => [
            'relation' => 'AND',
            [
                'key'     => 'sender',
                'value'   => $cnt['user']->ID,
                'compare' => '=',
            ], [
                'key'     => 'item',
                'value'   => $cnt['item']->ID,
                'compare' => '=',
            ], [
                'key'     => 'status',
                'value'   => 'pending',
                'compare' => '=',
            ],
        ],
    ]))->posts);

    $cnt['item_payment_paid']  = ! empty((new WP_Query([
        'post_type'    => 'payment',
        'nopaging'     => true,
        'meta_query'   => [
            'relation' => 'AND',
            [
                'key'     => 'sender',
                'value'   => $cnt['user']->ID,
                'compare' => '=',
            ], [
                'key'     => 'item',
                'value'   => $cnt['item']->ID,
                'compare' => '=',
            ], [
                'key'     => 'status',
                'value'   => 'success',
                'compare' => '=',
            ],
        ],
    ]))->posts);

    if ($cnt['item_payment_pending']) {
        $screen_errors['item_payment_pending'] = 'You have a pending payment for this course. You can cancel the payment or wait for it to be checked.';
    }

    if ($cnt['item_payment_paid']) {
        $screen_errors['item_payment_paid'] = 'You have already paid for this course. Contact support if you are not yet enrolled.';
    }

    if (! ($cnt['item_payment_pending'] || $cnt['item_payment_paid'])) {
        if (! is_null(Request::get('method'))) {
            if (in_array(Request::get('method'), [
                'bank_transfer',
            ])) {
                if ($cnt['item_price'] <= 0) { // auto enroll for free course
                    do_action('make_payment', $cnt['user']->ID, $cnt['item']->ID, [
                        'amount'  => 0,
                        'status'  => 'success',
                        'txn_ref' => '',
                    ]);

                    // verify payment and enrollment, switch screen
                    if (! empty((new WP_Query([
                        'post_type'    => 'payment',
                        'nopaging'     => true,
                        'meta_query'   => [
                            'relation' => 'AND',
                            [
                                'key'     => 'sender',
                                'value'   => $cnt['user']->ID,
                                'compare' => '=',
                            ], [
                                'key'     => 'item',
                                'value'   => $cnt['item']->ID,
                                'compare' => '=',
                            ], [
                                'key'     => 'status',
                                'value'   => 'success',
                                'compare' => '=',
                            ],
                        ],
                    ]))->posts)) {
                        $screen = 'success';
                        $screen_errors['success_success'] = 'You have been enrolled for the course ' . $cnt['item']->post_title;
                    } else {
                        $screen_errors['summary_error'] = 'Auto payment failed. Contact support if this issue persists.';
                    }
                } else {
                    switch (Request::get('method')) {
                        case 'bank_transfer':
                            $screen            = 'pay';
                            $cnt['page_title'] = 'Complete Payment';

                            if (Request::get('action') === 'complete') {
                                $screen            = 'proof';
                                $cnt['page_title'] = 'Proof of Payment';
                                $form              = [
                                    'nonce'         => Request::post('vtkn'),
                                    'nonce_id'      => 'payment_proof_form',
                                    'payment_proof' => Request::file('payment_proof'),
                                ];

                                if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
                                    if (wp_verify_nonce($form['nonce'], $form['nonce_id'])) {

                                        $proof_upload_check = ($form['payment_proof']['error'] ?? null) === UPLOAD_ERR_OK;
                                        $proof_upload_check = $proof_upload_check && (in_array($form['payment_proof']['type'] ?? null, [
                                            'image/jpeg',
                                            'image/jpg',
                                            'image/png',
                                            'image/webp',
                                            'application/pdf',
                                        ]));
                                        $proof_upload_check = $proof_upload_check && (($form['payment_proof']['size'] ?? 0) > 0);

                                        if ($proof_upload_check) {
                                            $proof_upload = wp_handle_upload(
                                                $form['payment_proof'],
                                                [
                                                    'test_form' => false,
                                                    'test_size' => true,
                                                    // 'test_type' => true,
                                                    // 'mimes'     => [
                                                    //     '[jpg|jpeg|jpe]' => 'image/jpeg',
                                                    //     '[png]'  => 'image/png',
                                                    //     '[webp]' => 'image/webp',
                                                    //     '[pdf]'  => 'application/pdf',
                                                    // ],
                                                ],
                                                // 'yyyy/mm'
                                            );

                                            if (isset($proof_upload['error'])) {
                                                $screen_errors['proof_error'] = 'Upload failed. Contact support if this issue persists';
                                            } else {
                                                do_action('make_payment', $cnt['user']->ID, $cnt['item']->ID, [
                                                    'amount'    => $cnt['item_price'],
                                                    'status'    => 'pending',
                                                    'txn_proof' => $proof_upload['url'],
                                                ]);

                                                $proof_upload_check = ! empty((new WP_Query([
                                                    'post_type'    => 'payment',
                                                    'nopaging'     => true,
                                                    'meta_query'   => [
                                                        'relation' => 'AND',
                                                        [
                                                            'key'     => 'sender',
                                                            'value'   => $cnt['user']->ID,
                                                            'compare' => '=',
                                                        ], [
                                                            'key'     => 'item',
                                                            'value'   => $cnt['item']->ID,
                                                            'compare' => '=',
                                                        ], [
                                                            'key'     => 'status',
                                                            'value'   => 'pending',
                                                            'compare' => '=',
                                                        ],
                                                    ],
                                                ]))->posts);

                                                // verify payment and switch screen
                                                if ($proof_upload_check) {
                                                    $screen = 'complete';
                                                    $cnt['page_title'] = 'Payment Complete';
                                                    $screen_errors['proof_success'] = 'Payment complete. Wait for your payment to be confirmed.';
                                                } else {
                                                    $screen_errors['proof_error'] = 'Failed to complete. Contact support if this issue persists.';
                                                }
                                            }

                                        } else {
                                            $screen_errors['proof_error'] = 'Select a valid file for upload.';
                                        }
                                    } else {
                                        $screen_errors['proof_error'] = 'An error occurred. Please refresh the page and try again.';
                                    }
                                }
                            }
                            break;

                        default:
                            // code...
                            break;
                    }
                }
            } else {
                $screen_errors['summary_error'] = 'Select a valid a payment method to continue';
            }
        }
    }
}

if ($screen === 'success') {
    $cnt['page_title'] = 'Payment Successful';
}

while (have_posts()): the_post();

    get_header();
?>
<div class="content-frame topbar-float sidebar-float">
    <header class="content-frame-header">
        <?php
        get_template_part('tpl/parts/toolbar');
        get_template_part('tpl/parts/sidebar');
        ?>

        <div class="px-3 pt-2">

            <div class="container-float">
                <div class="row">
                    <div class="col-12">

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= Theme::page('dashboard') ?>">Account</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Payment</li>
                            </ol>
                        </nav>

                    </div>

                    <div class="col-12">
                        <h1 class="display-6"><?= $cnt['page_title'] ?></h1>
                    </div>

                </div>
            </div>
        </div>

    </header>

    <main class="content-frame-body">
        <div class="px-3">

            <div class="container-float">
                <?php
                switch ($screen) {
                    case 'pay':
                    ?>
                <div class="row py-3">
                    <div class="col-md-8 col-lg-8 col-xl-8">
                        <!-- <?php if (isset($screen_errors['success_success'])): ?>
                        <div class="alert alert-success mb-3" role="alert">
                            <?= $screen_errors['success_success'] ?>
                        </div>
                        <?php endif; ?> -->

                        <div class="card border-0 mb-3 pt-2">
                            <div class="card-body text-left">
                                <h5 class="mb-3">Send Payment</h5>
                                <p>Transfer exactly <span><b>&#8358;<?= number_format($cnt['item_price'], 2, '.', ',') ?></b></span> to the following bank account:</p>
<!-- 
                                <ol>
                                    <li> -->
                                        <div class="mt-3 px-3 pb-1 pt-3 border">
                                            <div class="text-secondary">Bank Name</div>
                                            <p class="fs-5 mb-3">GTBank</p>

                                            <div class="text-secondary">Account Number</div>
                                            <p class="fs-5 mb-3">00123456789</p>

                                            <div class="text-secondary">Account Name</div>
                                            <p class="fs-5 mb-3">ECJP</p>
                                        </div><!-- 
                                    </li>
                                </ol> -->

                                <p class="mt-3 pb-1">Your payment will be cancelled if you send an amount not exactly equal to the stated amount.</p>

                                <div class="mt-4 d-flex justify-content-between">
                                    <a class="btn btn-light px-3" href="<?= add_query_arg(['item_id' => $cnt['item']->ID], get_page_link(Theme::page('payment'))) ?>" role="button">Go Back</a>

                                    <a class="btn btn-primary px-5"<?= ($cnt['item_payment_pending'] || $cnt['item_payment_paid']) ? ' disabled="disabled"' : '' ?> href="<?= add_query_arg(['method' => 'bank_transfer', 'item_id' => $cnt['item']->ID, 'action' => 'complete'], get_page_link(Theme::page('payment'))) ?>">I have paid</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                    <?php
                        break;

                    case 'complete':
                    ?>
                <div class="row py-3">
                    <div class="col-md-8 col-lg-8 col-xl-8">
                        <div class="card border-0 mb-3 pt-2">
                            <div class="card-body text-center">
                                <h5 class="mb-3">Payment pending confirmation</h5>
                                <p>Your proof of payment has been uploaded.<br />Your payment will be confirmed once reviewed.</p>

                            </div>
                        </div>

                    </div>
                </div>
                    <?php
                        break;

                    case 'proof':
                    ?>
                <div class="row py-3">
                    <div class="col-md-8 col-lg-8 col-xl-8">
                        <?php if (isset($screen_errors['proof_error'])): ?>
                        <div class="alert alert-danger mb-3" role="alert">
                            <?= $screen_errors['proof_error'] ?>
                        </div>
                        <?php endif; ?>

                        <div class="card border-0 mb-3 pt-2">
                            <div class="card-body">
                                <h5 class="mb-3">Upload a proof of your payment</h5>
                                <p>Proof of payment is required to confirm your payment.<br />Proof can be a photo of bank <b>deposit slip</b> or transaction <b>receipt</b>.</p>

                                <form method="POST" action="<?= add_query_arg([
                                    'method' => 'bank_transfer',
                                    'item_id' => $cnt['item']->ID,
                                    'action' => 'complete',
                                    ], get_page_link(Theme::page('payment'))) ?>" enctype="multipart/form-data">
                                    <!-- <input type="hidden"
                                        name="MAX_FILE_SIZE"
                                        value="30000"
                                    /> -->
                                    <!-- measured in bytes -->
                                    
                                    <div class="mb-3">
                                        <label for="payment-proof_file" class="form-label">Proof of Payment</label>
                                        <input class="form-control"
                                            type="file"
                                            id="payment-proof_file"
                                            name="payment_proof" 
                                            aria-describedby="payment-proof_file-help"
                                            accept=".jpeg,.jpg,.png,.webp,.pdf"
                                            required="required"
                                        />
                                        <div id="payment-proof_file-help" class="form-text">
                                            Allowed: JPEG, PNG and PDF.
                                        </div>
                                    </div>

                                    <div class="">
                                        <div class="form-label mb-2">Which of our bank accounts did you pay to?</div>

                                        <div class="form-check form-check">
                                            <input type="radio"
                                                class="form-check-input"
                                                id="pay-method_bank_transfer"
                                                name="method"
                                                aria-describedby="pay-method_bank_transfer-help"
                                                value="bank_transfer"
                                                checked="checked"
                                            />
                                            <label for="pay-method_bank_transfer" class="form-check-label">GTBank</label>
                                            <div id="pay-method_bank_transfer-help" class="form-text">
                                                <div class="mb-0">00123456789</div>
                                                <div class="mb-1">ECJP</div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden"
                                        hidden="hidden"
                                        name="vtkn"
                                        value="<?= wp_create_nonce($form['nonce_id']); ?>" 
                                    />

                                    <div class="mt-4 d-flex justify-content-between">
                                        <a class="btn btn-light px-3" href="<?= add_query_arg(['method' => 'bank_transfer', 'item_id' => $cnt['item']->ID], get_page_link(Theme::page('payment'))) ?>" role="button">Go Back</a>

                                        <button class="btn btn-primary px-5" type="submit" role="button">Complete</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
                    <?php
                        break;

                    case 'confirm':
                    ?>
                <div class="row py-3">
                </div>
                    <?php
                        break;

                    case 'success':
                    ?>
                <div class="row py-3">
                    <div class="col-md-8 col-lg-8 col-xl-8">
                        <?php if (isset($screen_errors['success_success'])): ?>
                        <div class="alert alert-success mb-3" role="alert">
                            <?= $screen_errors['success_success'] ?>
                        </div>
                        <?php endif; ?>

                        <div class="card border-0 mb-3 pt-2">
                            <div class="card-body text-center">
                                <h5 class="mb-3">Your transaction has been completed successfully.</h5>
                                <p>Thank you for purchase. Enjoy your course and happy learning!</p>
                                <div class="mt-4">
                                    <a href="<?= add_query_arg(['view' => 'enrolled'], get_page_link(Theme::page('list-courses'))) ?>">Go to Courses</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                        <?php 
                        break;
                    case 'summary':
                    ?>
                <form class="row py-3" method="GET" action="<?= add_query_arg(['item_id' => $cnt['item']->ID], get_page_link(Theme::page('payment'))) ?>">

                    <div class="col-md-8 col-lg-8 col-xl-8">
                        <?php if (isset($screen_errors['summary_error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $screen_errors['summary_error'] ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-8 col-lg-8 col-xl-8">
                        <div class="card border-0 mb-3 pt-2">

                            <div class="card-body">
                                <h5 class="" style="font-weight: 400; text-transform: uppercase;">Items (1)</h5>

                                <div class="mt-3 px-3 pb-1 pt-3 border">
                                    <p class="h6" style="text-transform: uppercase;"><?= $cnt['item']->post_type ?></p>

                                    <h5 style="font-weight: 400;"><?= $cnt['item']->post_title ?></h5>

                                    <div class="h6 mt-3">&#8358;<?= number_format($cnt['item_price'], 2, '.', ',') ?></div>
                                </div>

                            </div>
                        </div>

                        <div class="card border-0 mb-3 pt-2">

                            <div class="card-body">
                                <h5 class="mb-3" style="font-weight: 400;">How would you like to pay?</h5>

                                <div class="">
                                    <div class="form-label mb-2">Select Payment Method</div>

                                    <div class="form-check form-check">
                                        <input type="radio"
                                            class="form-check-input"
                                            id="pay-method_bank_transfer"
                                            name="method"
                                            aria-describedby="pay-method_bank_transfer-help"
                                            value="bank_transfer"
                                            checked="checked"
                                        />
                                        <label for="pay-method_bank_transfer" class="form-check-label">Bank Tranfer</label>
                                        <div id="pay-method_bank_transfer-help" class="form-text">
                                            <div class="mb-1">Requires submission of proof of payment.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="form-check form-check">
                                        <input type="radio"
                                            class="form-check-input"
                                            id="pay-method_paystack"
                                            name="method"
                                            aria-describedby="pay-method_paystack-help"
                                            value="paystack"
                                            disabled="disabled"
                                        />
                                        <label for="pay-method_paystack" class="form-check-label">Paystack</label>
                                        <div id="pay-method_paystack-help" class="form-text">
                                            <div class="mb-1">Instant</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-4">

                        <input type="hidden" hidden="hidden" name="item_id" value="<?= $cnt['item']->ID ?>">

                        <div class="card border-0 mb-3 pt-2">
                            <div class="card-body">
                                <div class="d-flex flex-column align-content-center align-items-start justify-content-start">
                                    <div class="text-mute h6">TOTAL</div>
                                    <div class="text-success h4">&#8358;<?= number_format($cnt['item_price'], 2, '.', ',') ?></div>
                                </div>

                                <div class="mt-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-5"<?= ($cnt['item_payment_pending'] || $cnt['item_payment_paid']) ? ' disabled="disabled"' : '' ?>>Next</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
                    <?php
                        break;

                case 'error':
                    default:

                    ?>
                <div class="row py-3">
                    <div class="col-md-8 col-lg-8 col-xl-8">
                        <div class="card border-0 mb-3 pt-2">
                            <div class="card-body text-center">
                                <h5 class="mb-3">An error occurred.</h5>
                                <p>Link may have expired or item might no longer be available for purchase.</p>

                                <div class="mt-4 d-flex justify-content-center">
                                    <a class="btn btn-primary px-5" href="<?= add_query_arg([], get_page_link(Theme::page('list-courses'))) ?>">Go to courses</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                    <?php
                        break;
                }
                ?>
            </div>

        </div>
    </main>

    <footer class="content-frame-footer">
        <?php  // get_template_part('tpl/parts/footer'); ?>
    </footer>
</div>
<?php

endwhile;

get_footer();
