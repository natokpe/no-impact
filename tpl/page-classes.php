<?php
/**
 * Template Name: Classes
 */

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;
use NatOkpe\Wp\Theme\Impact\Utils\Request;

Theme::requireLogin();
Theme::pageAccess(['student']);

$screen = in_array(Request::get('view'), ['upcoming', 'completed'])
? Request::get('view') : 'all';

$screen_errors = [];
$class_list    = new WP_Query;
$course_list   = [];
$cnt           = [
    'user' => wp_get_current_user(),
];

foreach ((new WP_Query([
    'has_password' => false,
    'post_type'    => 'enrollment',
    'post_status'  => 'publish',
    'nopaging'     => true,
    'order'        => 'DESC',
    'order_by'     => 'date', // ID | date | title
    'meta_key'     => 'user_id',
    'meta_value'   => $cnt['user']->ID,
    // 'fields'       => 'ids',
]))->posts as $_erm) {
    $course_list[] = get_post_meta($_erm->ID, 'course_id', true);
}

if (! empty($course_list)) {
    $class_list = new WP_Query([
        'has_password' => false,
        'post_type'    => 'class',
        'post_status'  => 'publish',
        'nopaging'     => true,
        'order'        => 'DESC',
        'order_by'     => 'date', // ID | date | title
        'meta_query'   => [
            [
                'key'     => 'course_id',
                'value'   => $course_list,
                'compare' => 'IN',
            ],
        ],
    ]);
}

if ($screen === 'upcoming') {
}

if ($screen === 'completed') {
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
                                <li class="breadcrumb-item"><a href="<?= Theme::page('profile') ?>">Account</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Classes</li>
                            </ol>
                        </nav>

                    </div>

                    <div class="col-12">
                        <h1 class="display-6">Classes</h1>
                    </div>

                    <div class="col-12">

                        <div class="card border-0 border-bottom mt-3 mb-3">
                            <div class="card-body">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link<?= ($screen === 'all' ? ' active' : '') ?>" aria-current="page" href="<?= get_page_link(Theme::page('list-classes')) ?>">All</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link<?= ($screen === 'upcoming' ? ' active' : '') ?>" href="<?= add_query_arg(['view' => 'upcoming'], get_page_link(Theme::page('list-classes'))) ?>">Upcoming</a>
                                    </li>
                                    <li class="nav-item flex-end">
                                        <a class="nav-link<?= ($screen === 'completed' ? ' active' : '') ?>" href="<?= add_query_arg(['view' => 'completed'], get_page_link(Theme::page('list-classes'))) ?>">Completed</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </header>

    <main class="content-frame-body">
        <div class="px-3">
            <div class="container-float">
                <div class="row justify-content-start">
                <?php
                    if ($class_list->have_posts()):
                        while ($class_list->have_posts()):
                            $class_list->the_post();
                ?>
                    <div class="col-sm-6 col-md-6 col-lg-4">
                        <div class="card border-0 color-bg-white mb-3">
                            <div class="card-body">
                                <!-- <a class="card-title" href="#"><?= get_the_title() ?></a> -->
                                <div class="card-title"><?= get_the_title() ?></div>
                                <div class="row">
                                    <div class="col-6">Instructor</div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                        endwhile;
                    else:
                ?>
                    <div class="col-12">
                        <p><?= esc_html('You have no classes on schedule.') ?></p>
                    </div>
                <?php
                    endif;

                    wp_reset_postdata();
                ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="content-frame-footer mt-5 pt-4">
        <?php  // get_template_part('tpl/parts/footer'); ?>
    </footer>
</div>
<?php

endwhile;

get_footer();
