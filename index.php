<?php
/**
 * Template Name: Homepage
 */

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;

Theme::add_body_classes('navbar-dynamic');

while (have_posts()): the_post();

get_header();
?>
<div class="content-frame">
    <header class="content-frame-header">
        <?php get_template_part('tpl/parts/navbar'); ?>
    </header>

    <main class="content-frame-body">
<?php
get_template_part('tpl/parts/hero-banner');
get_template_part('tpl/parts/intro');
get_template_part('tpl/parts/features');
get_template_part('tpl/parts/overview');
get_template_part('tpl/parts/faq');
get_template_part('tpl/parts/cta');
?>
    </main>

    <footer class="content-frame-footer">
        <?php get_template_part('tpl/parts/footer'); ?>
    </footer>
</div>
<?php

get_footer();

endwhile;
