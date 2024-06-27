<?php
declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;

while (have_posts()): the_post();

get_header();

?>
<div class="content-frame">
    <header class="content-frame-header">
        <?php get_template_part('tpl/parts/navbar'); ?>
        <?php get_template_part('tpl/parts/breadcrumb'); ?>
    </header>

    <main class="content-frame-body my-5 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-lg-8">
                </div>
                <div class="col-md-5 col-lg-4">
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
