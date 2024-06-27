<?php
/**
 * Template Name: Login
 */

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;
use NatOkpe\Wp\Theme\Impact\Utils\Request;

Theme::requireLogout();

$screen = 'initial';
$screen_errors = [];

$form = [
    'email' => Request::post('log', ''),
    'password' => Request::post('pwd', ''),
    'rem' => Request::post('rememberme', null) === 'on',
    'nonce' => Request::post('vtkn', ''),
];

$login_form_nonce_id = 'login_form_' . get_the_ID();

if (Request::is_post()) {
    if (wp_verify_nonce($form['nonce'], $login_form_nonce_id)) {
        $signon = wp_signon([
            'user_login' => $form['email'],
            'user_password' => $form['password'],
            'remember' => $form['rem'],
        ], is_ssl());

        if ($signon instanceOf WP_USER) {
            wp_set_current_user($signon->ID, $signon->user_login);

            if (in_array('student', $signon->roles)) {
                wp_redirect(get_page_link(Theme::page('dashboard')));
                exit;
            }

            if (count(array_intersect($signon->roles, [
                'administrator',
                'instructor',
                'editor',
                'author',
                'contributor',
            ])) > 0) {
                wp_redirect(get_admin_url());
                exit;
            }

            wp_redirect(home_url());
            exit;
        }

        if (is_wp_error($signon)) {
            $screen_errors[] = $signon->get_error_message();
        }
    }
}

while (have_posts()): the_post();

get_header();

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

                <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">
                    <?php if (! empty($screen_errors)): ?>
                    <div class="alert alert-danger mb-3 text-center"><?= __('Incorrect email or password. Please try again.', 'natokpe') ?></div>
                    <?php endif; ?>

                    <div class="card border border-0 px-4 color-bg-white">
                        <div class="card-body pt-4">
                            <h3 class="card-title text-center mb-4">Login</h3>

                            <div class="text-center mt-3 mb-4">
                                <p class="text-muted">Don't have an account? <a href="<?= get_page_link(Theme::page('register')) ?>">Register</a></p>
                            </div>

                            <form method="POST" action="<?= Theme::urlNow() ?>">
                                <div class="mb-3">
                                    <label for="user-email" class="form-label">Email address</label>
                                    <input type="email"
                                        class="form-control"
                                        id="user-email"
                                        name="log"
                                        placeholder=""
                                        value="<?= $form['email'] ?>" 
                                    />
                                </div>

                                <div class="mb-3">
                                    <div class="form-label mb-0">
                                        <div class="row justify-content-between">
                                            <div class="col-6 text-start">
                                                <label for="user-password" class="form-label">Password</label>
                                            </div>
                                            <div class="col-6 text-end">
                                                <a href="<?= get_page_link(Theme::page('password-recover')) ?>">Forgot password</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-container form-container-password">
                                        <input type="password"
                                            id="user-password"
                                            class="form-control"
                                            aria-describedby="user-password-help"
                                            placeholder=""
                                            name="pwd"
                                            value="<?= $form['password'] ?>" 
                                        />
                                        <div class="form-container-password-toggle">
                                            <i class="icon feather icon-eye"></i>
                                        </div>
                                    </div>

                                    <!-- <div id="user-password-help" class="form-textxxx mt-1"></div> -->
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox"
                                            class="form-check-input"
                                            id="user-rem"
                                            name="rememberme"
                                            value="on"<?php echo ($form['rem'] ? ' checked="checked"' : ''); ?> 
                                        />
                                        <label class="form-check-label" for="user-rem">Remember me</label>
                                    </div>
                                </div>

                                <input type="hidden"
                                    hidden="hidden"
                                    name="vtkn"
                                    value="<?= wp_create_nonce($login_form_nonce_id); ?>" 
                                />

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </div>

                                <div class="mt-4">
                                    <p>Need help accessing your account? <a href="<?= get_page_link(Theme::page('contact')) ?>">Contact Support.</a></p>
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

get_footer();

endwhile;
