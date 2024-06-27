<?php
/**
 * Template Name: Recover password
 */

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;

// var_dump(
//     retrieve_password(wp_get_current_user()->user_login)
// );

// exit;

// Theme::allow_access(['administrator']);

$rdir_url = home_url();

if (is_user_logged_in()) {
    if (count(array_intersect(wp_get_current_user()->roles, [
        'administrator',
        'instructor',
    ])) >= 1) {
        $rdir_url = get_admin_url();
    }

    if (count(array_intersect(wp_get_current_user()->roles, [
        'student',
    ])) === 1) {
        $rdir_url = get_page_link(Theme::page('dashboard'));
    }

    wp_redirect($rdir_url, 302, bloginfo('title'));
    exit;
}

while (have_posts()): the_post();

get_header();

?><div class="content-frame">
    <header class="content-frame-header">
        <?php get_template_part('tpl/parts/navbar'); ?>
    </header>

    <main class="content-frame-body my-5 py-5">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">
                    <div class="card border border-0 px-4">
                        <div class="card-body pt-4">
                            <h3 class="card-title text-center mb-4">Recover password</h3>

                            <div class="text-center mt-3 mb-4">
                                <p class="text-muted"><a href="<?= get_page_link(Theme::page('login')) ?>">Login instead</a></p>
                            </div>

                            <form method="POST" action="<?= Theme::urlNow() ?>">
                                <div class="mb-3">
                                    <label for="user-email" class="form-label">Email address</label>
                                    <input type="email"
                                        class="form-control"
                                        id="user-email"
                                        name="email"
                                        placeholder=""
                                    />
                                    <div id="user-password-help" class="form-text">Enter the email address associated with your account and we will send you a password reset link.</div>
                                </div>

                                <?php wp_nonce_field('recover_password_page_' . get_the_ID()); ?>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Get link</button>
                                </div>

                                <div class="mt-4">
                                    <p><a href="<?= get_page_link(Theme::page('contact')) ?>">I need more help</a></p>
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
</div>
<?php

get_footer();

endwhile;
