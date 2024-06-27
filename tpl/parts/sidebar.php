<?php

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;
use NatOkpe\Wp\Theme\Impact\Utils\Request;


$nav = [
    'list-courses' => [
        'tpl'     => 'page-courses',
        'icon'    => 'book',
        'display' => 'Courses',
        'pt'      => 'course',
    ],

    'list-classes' => [
        'tpl'     => 'page-classes',
        'icon'    => 'book-open',
        'display' => 'Classes',
        'pt'      => 'class',
    ],

    // 'assessments' => [
    //     'tpl'     => 'page-assessments',
    //     'icon'    => 'box',
    //     'display' => 'Assessments',
    //     'pt'      => 'assessment',
    // ],

    // 'badges' => [
    //     'tpl'     => 'page-badges',
    //     'icon'    => 'award',
    //     'display' => 'Badges',
    //     'pt'      => 'badge',
    // ],

    // 'certificates' => [
    //     'tpl'     => 'page-certificates',
    //     'icon'    => 'star',
    //     'display' => 'Certificates',
    //     'pt'      => 'certificate',
    // ],
];

?>
<aside class="drawernav offcanvas offcanvas-start border-end"
    tabindex="-1"
    id="offcanvasDrawernav"
    data-bs-scroll="true"
    data-bs-backdrop="static">

    <div class="offcanvas-body">
        <nav>
            <ul class="nav flex-column">
                <?php
                    foreach ($nav as $_ => $__) {
                        $active = false;

                        if (is_page()) {
                            $active = basename(get_page_template()) === $__['tpl'] . '.php';
                        }

                        if (is_single()) {
                            $active = get_post_type(get_the_ID()) === $__['pt'] ?? false;
                        }
                ?>
                <li class="nav-item">
                    <a class="nav-link<?= $active ? ' active' : '' ?>" href="<?= get_page_link(Theme::page($_)) ?>"><i class="icon feather icon-<?= $__['icon'] ?>"></i> <?= $__['display'] ?></a>
                </li>
                <?php
                    }
                ?>
            </ul>
        </nav>
    </div>
</aside>
