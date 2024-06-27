<?php
declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;
use NatOkpe\Wp\Theme\Impact\Utils\Request;

Theme::pageAccess(['student']);

$screen          = 'initial';
$screen_errors   = [];
$course_list     = new WP_Query;
$cnt             = [
    'user' => wp_get_current_user(),
    'page_title' => 'All Courses',
];

if (in_array(Request::get('view'), [
    'enrolled',
    'history'
], true)) {
    $screen = Request::get('view');
} else {
    $course_list = new WP_Query([
        'has_password' => false,
        'post_type'    => 'course',
        'post_status'  => 'publish',
        'nopaging'     => true,
        'order'        => 'ASC',
        'order_by'     => 'title',
    ]);
}

if ($screen === 'enrolled') {
    $course_list_id = [];
    $cnt['page_title'] = 'Enrolled Courses';

    foreach ((new WP_Query([
        'post_type'    => 'enrollment',
        'nopaging'     => true,
        'meta_key'     => 'user_id',
        'meta_value'   => $cnt['user']->ID,
    ]))->posts as $_erm) {
        $course_list_id[] = get_post_meta($_erm->ID, 'course_id', true);
    }

    if (! empty($course_list_id)) {
        $course_list = new WP_Query([
            'has_password' => false,
            'post_type'    => 'course',
            'post_status'  => 'publish',
            'nopaging'     => true,
            'order'        => 'DESC',
            'order_by'     => 'date', // ID | date | title
            'post__in'      => $course_list_id,
        ]);
    }
}

if ($screen === 'history') {
    $cnt['page_title'] = 'Enrollment History';
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
                                <li class="breadcrumb-item active" aria-current="page">Courses</li>
                            </ol>
                        </nav>

                    </div>

                    <div class="col-12">
                        <h1 class="display-6"><?= $cnt['page_title'] ?></h1>
                    </div>

                    <div class="col-12">

                        <div class="card border-0 border-bottom mt-3 mb-3">
                            <div class="card-body">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link<?= (in_array($screen, ['enrolled', 'history']) ? '' : ' active') ?>" aria-current="page" href="<?= get_page_link(Theme::page('list-courses')) ?>">All</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link<?= ($screen === 'enrolled' ? ' active' : '') ?>" href="<?= add_query_arg(['view' => 'enrolled'], get_page_link(Theme::page('list-courses'))) ?>">Enrolled</a>
                                    </li>
                                    <li class="nav-item flex-end">
                                        <a class="nav-link<?= ($screen === 'history' ? ' active' : '') ?>" href="<?= add_query_arg(['view' => 'history'], get_page_link(Theme::page('list-courses'))) ?>">Enrollment History</a>
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

                    switch ($screen) {
                        case 'enrolled':
                            if ($course_list->have_posts()):
                                while ($course_list->have_posts()):
                                    $course_list->the_post();

                                    // Check if user is already enrolled
                                    $user_enrolled = ! empty((new WP_Query([
                                        'post_type' => 'enrollment',
                                        'nopaging'  => true,
                                        'meta_query'      => [
                                            'relation' => 'AND',
                                            [
                                                'key'   => 'user_id',
                                                'value' => $cnt['user']->ID,
                                                'compare' => '=',
                                            ], [
                                                'key'   => 'course_id',
                                                'value' => get_the_ID(),
                                                'compare' => '=',
                                            ],
                                        ],
                                    ]))->posts);

                                    // 
                                    ?>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="card card-course border-0 color-bg-white mb-3">
                                    <div class="card-body">
                                        <div class="card-course-img">
                                            <img src="<?= get_the_post_thumbnail_url() ?>" alt="<?= get_the_title() ?>">
                                        </div>

                                        <div>Starts April 4, 2024 ----- <i class="icon feather icon-clock"></i> 14h 20m</div>

                                        <a class="card-course-title" href="<?= get_the_permalink() ?>"><?= get_the_title() ?></a>

                                        <div>N<?= (int) get_post_meta(get_the_ID(), 'enrollment_fee', true) ?></div>

                                        <?php
                                        if ($user_enrolled):
                                        ?>
                                        You are enrolled
                                        <?php
                                        else:
                                        ?>
                                        <a href="<?= add_query_arg(['item_id' => get_the_ID()], get_page_link(Theme::page('payment'))) ?>">Enroll</a>
                                        <?php
                                        endif;
                                        ?>

                                        <div class="row">
                                            <div class="col-6">Instructors</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <?php
                                endwhile;
                            else:
                                esc_html_e('Sorry, no posts matched your criteria.');
                            endif;

                            wp_reset_postdata();

                            break;

                        case 'history':
                            ?>

                            <div class="col-12">

                                <div class="card border-0">
                                    <div class="card-body">

                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Date Enrolled</th>
                                                        <th scope="col">Course Title</th>
                                                        <th scope="col">Course ID</th>
                                                        <th scope="col">Payment Method</th>
                                                        <th scope="col">Payment Amount</th>
                                                        <th scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- <tr>
                                                        <td>Jan 6, 2024</td>
                                                        <td>Conflict Resolution</td>
                                                        <td>536721</td>
                                                        <td>Bank Tranfer</td>
                                                        <td>N20,000</td>
                                                        <td>
                                                            <a href="#">Print Receipt</a>
                                                        </td>
                                                    </tr> -->
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <?php
                            break;
                        
                        default:
                            // $clist = new WP_Query([
                            //     'has_password' => false,
                            //     'post_type'    => 'course',
                            //     'post_status'  => 'publish',
                            //     'nopaging'     => true,
                            //     'order'        => 'ASC',
                            //     'order_by'     => 'title',
                            // ]);

                            if ($course_list->have_posts()):
                                while ($course_list->have_posts()):
                                    $course_list->the_post();

                                    // Check if user is already enrolled
                                    $user_enrolled = ! empty((new WP_Query([
                                        'post_type' => 'enrollment',
                                        'nopaging'  => true,
                                        'meta_query'      => [
                                            'relation' => 'AND',
                                            [
                                                'key'   => 'user_id',
                                                'value' => $cnt['user']->ID,
                                                'compare' => '=',
                                            ], [
                                                'key'   => 'course_id',
                                                'value' => get_the_ID(),
                                                'compare' => '=',
                                            ],
                                        ],
                                    ]))->posts);

                                    // 
                                    ?>
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="card card-course border-0 color-bg-white mb-3">
                                    <div class="card-body">
                                        <div class="card-course-img">
                                            <img src="<?= get_the_post_thumbnail_url() ?>" alt="<?= get_the_title() ?>">
                                        </div>

                                        <div>Starts April 4, 2024 ----- <i class="icon feather icon-clock"></i> 14h 20m</div>

                                        <a class="card-course-title" href="<?= get_the_permalink() ?>"><?= get_the_title() ?></a>

                                        <div>N<?= (int) get_post_meta(get_the_ID(), 'enrollment_fee', true) ?></div>

                                        <?php
                                        if ($user_enrolled):
                                        ?>
                                        You are enrolled
                                        <?php
                                        else:
                                        ?>
                                        <a href="<?= add_query_arg(['item_id' => get_the_ID()], get_page_link(Theme::page('payment'))) ?>">Enroll</a>
                                        <?php
                                        endif;
                                        ?>

                                        <div class="row">
                                            <div class="col-6">Instructors</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <?php
                                endwhile;
                            else:
                                esc_html_e('Sorry, no posts matched your criteria.');
                            endif;

                            wp_reset_postdata();

                            break;
                    }

                    ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="content-frame-footer">
    </footer>
</div>
<?php

get_footer();
endwhile;
