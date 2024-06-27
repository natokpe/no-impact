<?php
/**
 * Template Name: FAQ
 */

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;
use NatOkpe\Wp\Theme\Impact\Utils\Request;

$query_arg = [
    'post_type'           => 'faq',
    'post_status'         => 'publish',
    'has_password'        => false,
    'ignore_sticky_posts' => false,
    'order'               => 'DESC',
    'orderby'             => 'date',
    'nopaging'            => false,
    'posts_per_page'      => 20,
    'paged'               => Request::get('pg', 1),
];

$query_arg['paged'] = ctype_digit((string) $query_arg['paged']) ? (int) $query_arg['paged'] : 1;
$query              = new WP_Query($query_arg);

$pg = [
    'items' => [],
    'prev'  => [
        'url'    => '#!',
        'active' => false,
    ],
    'next'  => [
        'url'    => '#!',
        'active' => false,
    ],
];

for ($i = 1; $i <= (float) $query->max_num_pages; $i++) {
    $pg['items'][] = [
        'url' => add_query_arg(['pg' => $i], get_page_link(Theme::page('faq'))),
        'index' => $i,
        'active' => ($query_arg['paged'] === $i),
    ];
}

if (empty($pg['items'])) {
    $pg['items'][] = [
        'url' => add_query_arg(['pg' => 1], get_page_link(Theme::page('faq'))),
        'index' => 1,
        'active' => true,
    ];
}

if (($query_arg['paged'] > 1) && ($query->max_num_pages > 1)) {
    $pg['prev']['url'] = add_query_arg(
        [
            'pg' => ($query_arg['paged'] - 1)
        ],
        get_page_link(Theme::page('faq'))
    );

    $pg['prev']['active'] = true;
}

if (($query_arg['paged'] < $query->max_num_pages) && ($query->max_num_pages > 1)) {
    $pg['next']['url'] = add_query_arg(
        [
            'pg' => ($query_arg['paged'] + 1)
        ],
        get_page_link(Theme::page('faq'))
    );

    $pg['next']['active'] = true;
}

while (have_posts()): the_post();

get_header();

?><div class="content-frame">
    <header class="content-frame-header">
        <?php get_template_part('tpl/parts/navbar'); ?>
        <?php get_template_part('tpl/parts/breadcrumb'); ?>
    </header>

    <main class="content-frame-body my-5 pb-5">
        <div class="container">
            <div class="row">

                <div class="col-md-7 col-lg-8">
                    <!-- TODO: add topic filter -->

                    <div class="accordion mb-5" id="faqList">
<?php
while ($query->have_posts()) {
    $query->the_post();
?>
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-item-<?php echo get_the_ID(); ?>" aria-expanded="false" aria-controls="faq-item-<?php echo get_the_ID(); ?>"><span class="h5"><?php echo get_the_title(); ?></span></button>
                            </h2>
                            <div id="faq-item-<?php echo get_the_ID(); ?>" class="accordion-collapse collapse" data-bs-parent="#faqList">
                                <div class="accordion-body"><?php echo get_the_content() ?></div>
                            </div>
                        </div>
<?php
}
wp_reset_postdata();

?>
                    </div>
                    <nav aria-label="FAQ pagination">
                        <ul class="pagination">
                            <li class="page-item<?php echo ($pg['prev']['active'] ? '' : ' disabled'); ?>">
                                <a class="page-link" href="<?php echo $pg['prev']['url']; ?>" aria-label="Previous">
                                    <span aria-hidden="true"><i class="icon feather icon-arrow-left"></i> Prev</span>
                                </a>
                            </li>
<?php
    foreach ($pg['items'] as $_pg) {
        if ($_pg['active']):
?>
                            <li class="page-item active" aria-current="page">
                                <div class="page-link">
                                    <?php echo $_pg['index']; ?>
                                </div>
                            </li>
<?php
        else:
?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo $_pg['url']; ?>">
                                    <?php echo $_pg['index']; ?>
                                </a>
                            </li>
<?php
        endif;
    }
?>
                            <li class="page-item<?php echo ($pg['next']['active'] ? '' : ' disabled'); ?>">
                                <a class="page-link" href="<?php echo $pg['next']['url']; ?>" aria-label="Next">
                                    <span aria-hidden="true">Next <i class="icon feather icon-arrow-right"></i></span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <div class="col-md-5 col-lg-4">
                    <div class="card border border-0">
                        <div class="card-body py-4">
                            <h5 class="card-title mb-3"><?= __('Need more help?', 'natokpe') ?></h5>
                            <div class="d-grid">
                                <a class="btn btn-primary btn-block" href="<?= get_page_link(Theme::page('contact')) ?>"><?= __('Ask a Question', 'natokpe') ?></a>
                            </div>
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
