<?php

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;
use NatOkpe\Wp\Theme\Impact\Utils\Clock;

$user_logged = is_user_logged_in();

$menu_data = [
    'menu_primary'        => wp_nav_menu([
        'theme_location'       => ($user_logged ? 'footer_primary' : 'footer_primary_guest'),
        'menu_class'           => 'page-footer-menu',
        'menu_id'              => false,
        'container'            => 'nav',
        'container_class'      => 'page-footer-nav',
        'container_id'         => false,
        'container_aria_label' => '',
        'fallback_cb'          => false,
        'before'               => '',
        'after'                => '',
        'link_before'          => '',
        'link_after'           => '',
        'echo'                 => false,
        'depth'                => 1,
        // 'items_wrap'        => '',
        'item_spacing'         => 'preserve',
    ]),

    'menu_secondary'      => wp_nav_menu([
        'theme_location'       => ($user_logged ? 'footer_secondary' : 'footer_secondary_guest'),
        'menu_class'           => 'page-footer-menu',
        'menu_id'              => false,
        'container'            => 'nav',
        'container_class'      => 'page-footer-nav',
        'container_id'         => false,
        'container_aria_label' => '',
        'fallback_cb'          => false,
        'before'               => '',
        'after'                => '',
        'link_before'          => '',
        'link_after'           => '',
        'echo'                 => false,
        'depth'                => 1,
        // 'items_wrap'        => '',
        'item_spacing'         => 'preserve',
    ]),

    'menu_primary_name'   => wp_get_nav_menu_name(($user_logged ? 'footer_primary' : 'footer_primary_guest')),
    'menu_secondary_name' => wp_get_nav_menu_name(($user_logged ? 'footer_secondary' : 'footer_secondary_guest')),
];

?>
<div class="page-footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <h2 class="page-footer-nav-title"><?= $menu_data['menu_primary_name'] ?></h2>
                <?= $menu_data['menu_primary'] ?>
            </div>
            <div class="col-sm-6 col-md-4">
                <h2 class="page-footer-nav-title"><?= $menu_data['menu_secondary_name'] ?></h2>
                <?= $menu_data['menu_secondary'] ?>
            </div>
            <div class="col-md-4 d-flex flex-column justify-content-end">
                <div class="page-footer-sponsor">
                    <a class="page-footer-sponsor-img" data-sponsor-text="Powered By" href="https://ecjpnigeria.org" target="_blank" rel="noreferrer noopener">
                        <img src="<?= Theme::url('assets/img/ecjp-brand-white.svg') ?>" />
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-footer-footer">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="copyright text-center">Copyright &copy; <?= Clock::nowYear() ?> <a href="<?= home_url() ?>"><?= bloginfo('name'); ?></a> | All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</div>
