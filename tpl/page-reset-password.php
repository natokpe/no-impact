<?php
/**
 * Template Name: Reset password
 */

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;
use NatOkpe\Wp\Theme\Impact\Config;
use NatOkpe\Wp\Theme\Impact\Utils\Request;

global $wp;

Theme::requireLogout();

$user                 = null;
$do_reset             = false;
$screen               = 'initial';
$screen_errors        = [];
$preset_form_nonce_id = 'password_reset_form_' . get_the_ID();
$form                 = [
    'password'  => Request::post('pwd', ''),
    'vpassword' => Request::post('vpwd', ''),
    'nonce'     => Request::post('vtkn', ''),
];


$url_token_key = (new Config)->password_reset_url_token_key;
$reset_token   = array_key_exists($url_token_key, $wp->query_vars)
? $wp->query_vars[$url_token_key]
: null;

if (isset($reset_token)) {
    $reset_token = Theme::decodeResetToken(urldecode($reset_token));

    if (is_array($reset_token)) {
        $user = new WP_USER((int) $reset_token['user']);

        if ($user->exists()) {
            $user     = check_password_reset_key($reset_token['key'], $user->user_login);
            $do_reset = $user instanceOf WP_USER;
        }
    }
}

if ($do_reset) {
    $screen = 'reset';
}

if ($do_reset && Request::is_post()) {
    if (wp_verify_nonce($form['nonce'], $preset_form_nonce_id)) {

        if (empty($form['password'])) {
            $do_reset = false;
            $form_errors['password'] = 'Choose a password.';
        }

        if ($form['vpassword'] !== $form['password']) {
            $do_reset = false;
            $form_errors['vpassword'] = 'Passwords do not match.';
        }

        if ($do_reset) {
            reset_password($user, $form['password']);
            // TODO: invalidate activation key
            $screen = 'complete';
        }
    } else {
        $do_reset = false;
        $screen_errors['general'] = 'An error occurred. Please refresh the page and try again.';
    }
}

get_header();

while (have_posts()): the_post();

?><div class="content-frame">
    <header class="content-frame-header">
        <?php get_template_part('tpl/parts/navbar'); ?>
    </header>

    <main class="content-frame-body my-5 py-5">
        <div class="container">
            <div class="row justify-content-center">
<?php

    switch ($screen) {
        case 'reset':
?>
            <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">
                <div class="card my-5 px-4">
                    <div class="card-body pt-4">
                        <h3 class="card-title text-center mb-4">Reset password</h3>

                        <div class="text-center mt-3 mb-4">
                            <p class="text-muted"><a href="<?= get_page_link(Theme::page('login')) ?>">Go to login</a></p>
                        </div>

                        <form method="POST" action="<?= Theme::urlNow() ?>">
                            <div class="mb-3">
                                <label for="user-password" class="form-label">New password</label>
                                <input type="password"
                                    id="user-password"
                                    class="form-control"
                                    aria-describedby="user-password-help"
                                    placeholder="Choose a password"
                                    name="pwd"
                                    value="<?= $form['password'] ?>" 
                                />
                                <div id="user-password-help" class="form-text">Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.</div>
                            </div>

                            <div class="mb-3">
                                <label for="user-vpassword" class="form-label">Verify password</label>
                                <input type="password"
                                    class="form-control"
                                    id="user-vpassword"
                                    name="vpwd"
                                    placeholder="Retype password"
                                    value="<?= $form['vpassword'] ?>"
                                />
                            </div>

                            <input type="hidden"
                                hidden="hidden"
                                name="vtkn"
                                value="<?= wp_create_nonce($preset_form_nonce_id); ?>" 
                            />

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>

                            <div class="mt-4">
                                <p><a href="<?= get_page_link(Theme::page('contact')) ?>">I need help</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div><?php
            break;

        case 'complete':
        ?>
            <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">
                <p>Complete. Go to login.</p>
            </div>
        <?php
            break;

        case 'initial':
        ?>
            <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4 text-center">
                <h2 class="mb-3">Sorry!</h2>
                <p>This link may have expired and can not be used.</p>
            </div>
        <?php
        default:
            break;
    }

?>
            </div>
        </div>
    </main>

    <footer class="content-frame-footer">
        <?php get_template_part('tpl/parts/footer'); ?>
    </footer>
</div><?php

endwhile;

get_footer();
