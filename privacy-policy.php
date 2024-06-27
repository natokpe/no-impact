<?php
/**
 * Template Name: Legal
 */

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;

while (have_posts()): the_post();

get_header();

?><div class="content-frame">
    <header class="content-frame-header">
        <?php get_template_part('tpl/parts/navbar'); ?>
        <?php get_template_part('tpl/parts/breadcrumb'); ?>
    </header>

    <main class="content-frame-body bg-white mt-5 py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <?php the_content(); ?>
                </div>
                <div class="col-lg-4">
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
