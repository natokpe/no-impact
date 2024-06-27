<?php
/**
 * Template Name: Certificates
 */

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;
use NatOkpe\Wp\Theme\Impact\Utils\Request;

Theme::requireLogin();

$screen          = 'initial';
$screen_errors   = [];
$cnt             = [
];

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
                                <li class="breadcrumb-item"><a href="<?= Theme::page('profile') ?>">Account</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Certificates</li>
                            </ol>
                        </nav>

                    </div>

                    <div class="col-12">
                        <h1 class="display-6">Certificates</h1>
                    </div>

                </div>
            </div>
        </div>

    </header>

    <main class="content-frame-body">
        <div class="px-3">
            <div class="container-float">
                <div class="row justify-content-start">
                    <div class="col-12">
                        <p class="h1 text-center text-muted mt-5 pt-2 mb-3">Coming Soon</p>
                        <p class="text-center text-muted">This feature is not yet available.</p>
<!-- 
                        <div class="card border-0">
                            <div class="card-body">
                            
                            </div>
                        </div>
                         -->
                    </div>
                </div>
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
