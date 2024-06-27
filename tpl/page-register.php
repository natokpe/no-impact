<?php
/**
 * Template Name: Sign Up
 */
 
declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;
use NatOkpe\Wp\Theme\Impact\Utils\Request;
use NatOkpe\Wp\Theme\Impact\Utils\Clock;

Theme::requireLogout();

$screen = 'initial';
$screen_errors = [];

$form = [
    'firstname' => Request::post('firstname', ''),
    'lastname'  => Request::post('lastname', ''),
    'email'     => Request::post('email', ''),
    'password'  => Request::post('password', ''),
    'vpassword' => Request::post('vpassword', ''),
    'terms'     => Request::post('terms', null) === 'on',
    'nonce'     => Request::post('vtkn', ''),
];

$signup_nonce = 'signup_form_' . get_the_ID();
$do_signup    = 0;

if (Request::is_post()) {
    if (wp_verify_nonce($form['nonce'], $signup_nonce)) {
        if (empty($form['firstname'])) {
            $do_signup++;
            $screen_errors['firstname'] = 'Enter a first name.';
        }

        if (empty($form['lastname'])) {
            $do_signup++;
            $screen_errors['lastname'] = 'Enter a last name.';
        }

        // if (getmxrr($form['email'])) {}

        if (! filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
            $do_signup++;
            $screen_errors['email'] = 'Enter a valid email.';
        }

        if (empty($form['password'])) {
            $do_signup++;
            $screen_errors['password'] = 'Choose a password.';
        }

        if ($form['vpassword'] !== $form['password']) {
            $do_signup++;
            $screen_errors['vpassword'] = 'Passwords do not match.';
        }

        if ($form['terms'] !== true) {
            $do_signup++;
            $screen_errors['terms'] = 'You must accept the terms.';
        }

        if ($do_signup === 0) {
            $user_username = base_convert(((string) random_int(1, 9))
            . ((string) Clock::now())
            . ((string) random_int(0, 999))
            . ((string) random_int(0, 999)), 10, 36);

            $signon = wp_insert_user([
                'user_pass' => $form['password'],
                'user_login' => $user_username,
                'user_email' => $form['email'],
                'nickname' => $user_username,
                'first_name' => $form['firstname'],
                'last_name' => $form['lastname'],
                'show_admin_bar_front' => false,
                'role' => 'student',
            ]);

            if (is_int($signon)) {
                wp_redirect(get_page_link(Theme::page('login')));
                exit;
            }

            $do_signup++;
            $screen_errors['general'] = 'An error occurred. Please try again later.';
        }
    } else {
        $do_signup++;
        $screen_errors['general'] = 'An error occurred. Please refresh the page and try again.';
    }
}

get_header();

while (have_posts()): the_post();

    switch ($screen) {
        // case 'screen':
        //     break;

        case 'initial':
        default:
?><div class="content-frame">
    <header class="content-frame-header">
        <?php get_template_part('tpl/parts/navbar'); ?>
    </header>

    <main class="content-frame-body my-5 py-5">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-sm-9 col-md-7 col-lg-5 col-xl-5">
                    <?php if (! empty($screen_errors['general'])): ?>
                    <div class="alert alert-danger mb-3 text-center"><?= $screen_errors['general'] ?></div>
                    <?php endif; ?>

                    <div class="card border border-0 px-4 color-bg-white">
                        <div class="card-body pt-4">
                            <h3 class="card-title text-center mb-4">Create an account</h3>

                            <div class="mt-3 mb-4 text-center">
                                <p class="text-muted">Already have an account? <a href="<?= get_page_link(Theme::page('login')) ?>">Log in</a></p>
                            </div>

                            <form method="POST" action="<?= Theme::urlNow() ?>">
                                <div class="mb-3">
                                    <label for="user-firstname" class="form-label">First name</label>
                                    <input type="text"
                                        class="form-control"
                                        id="user-firstname"
                                        name="firstname"
                                        aria-describedby="user-firstname-help"
                                        value="<?= $form['firstname'] ?>" 
                                        placeholder="Your first name"
                                    />
                                    <div id="user-firstname-help" class="form-text">
                                        <?php if (! empty($screen_errors['firstname'] ?? '')): ?>
                                        <div class="text-danger mb-1"><?= $screen_errors['firstname'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="user-lastname" class="form-label">Last name</label>
                                    <input type="text"
                                        class="form-control"
                                        id="user-lastname"
                                        name="lastname"
                                        aria-describedby="user-lastname-help"
                                        value="<?= $form['lastname'] ?>" 
                                        placeholder="Your last name"
                                    />
                                    <div id="user-lastname-help" class="form-text">
                                        <?php if (! empty($screen_errors['lastname'] ?? '')): ?>
                                        <div class="text-danger mb-1"><?= $screen_errors['lastname'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="user-email" class="form-label">Email address</label>
                                    <input type="email"
                                        class="form-control"
                                        id="user-email"
                                        name="email"
                                        aria-describedby="user-email-help"
                                        value="<?= $form['email'] ?>" 
                                        placeholder="Your email"
                                    />
                                    <div id="user-email-help" class="form-text">
                                        <?php if (! empty($screen_errors['email'] ?? '')): ?>
                                        <div class="text-danger mb-1"><?= $screen_errors['email'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="user-password" class="form-label">Password</label>
                                    <div class="form-control-password">
                                        <input type="password"
                                            id="user-password"
                                            class="form-control"
                                            aria-describedby="user-password-help"
                                            placeholder="Choose a password"
                                            name="password"
                                            value="<?= $form['password'] ?>"
                                        />
                                        <div class="form-control-password-toggle">
                                            <i class="icon feather icon-eye-off"></i>
                                        </div>
                                    </div>

                                    <div id="user-password-help" class="form-text">
                                        <?php if (! empty($screen_errors['password'] ?? '')): ?>
                                        <div class="text-danger mb-1"><?= $screen_errors['password'] ?></div>
                                        <?php endif; ?>

                                        <div>Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="user-vpassword" class="form-label">Verify password</label>
                                    <input type="password"
                                        class="form-control"
                                        id="user-vpassword"
                                        name="vpassword"
                                        aria-describedby="user-vpassword-help"
                                        value="<?= $form['vpassword'] ?>" 
                                        placeholder="Retype password"
                                    />
                                    <div id="user-vpassword-help" class="form-text">
                                        <?php if (! empty($screen_errors['vpassword'] ?? '')): ?>
                                        <div class="text-danger mb-1"><?= $screen_errors['vpassword'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox"
                                            class="form-check-input"
                                            id="user-terms"
                                            name="terms"
                                            aria-describedby="user-terms-help"
                                            value="on"<?php echo ($form['terms'] ? ' checked="checked"' : ''); ?> 
                                        />
                                        <label class="form-check-label" for="user-terms">I agree to the <a href="<?= get_page_link(Theme::page('terms')) ?>">Terms and Conditions</a> and <a href="<?= get_page_link(Theme::page('policy')) ?>">Privacy Policy</a></label>
                                    </div>
                                    <div id="user-terms-help" class="form-text">
                                        <?php if (! empty($screen_errors['terms'] ?? '')): ?>
                                        <div class="text-danger"><?= $screen_errors['terms'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <input type="hidden"
                                    hidden="hidden"
                                    name="vtkn"
                                    value="<?= wp_create_nonce($signup_nonce); ?>" 
                                />

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Continue</button>
                                </div>

                                <div class="mt-4">
                                    <p>Having issues? <a href="<?= get_page_link(Theme::page('contact')) ?>">Get support.</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <footer class="content-frame-footer">
        <?php get_template_part('tpl/parts/footer'); ?>
    </footer>
</div><?php
            break;
    }

endwhile;

get_footer();
