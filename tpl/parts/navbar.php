<?php

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;

$user_roles   = wp_get_current_user()->roles;

/*
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</a>
    <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="#">Action</a></li>
    <li><a class="dropdown-item" href="#">Another action</a></li>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item" href="#">Something else here</a></li>
    </ul>
</li>
*/

$menu = wp_nav_menu([
    'theme_location' => (is_user_logged_in() ? 'navbar' : 'navbar_guest'),
    'menu_class' => 'navbar-nav ms-auto justify-content-end',
    'menu_id' => false,
    'container' => null,
    'container_class' => null,
    'container_id' => false,
    'container_aria_label' => '',
    'fallback_cb' => false,
    'before' => '',
    'after' => '',
    'link_before' => '',
    'link_after' => '',
    'echo' => false,
    'depth' => 3,
    // 'items_wrap' => '',
    'item_spacing' => 'preserve', // Accepts 'preserve' or 'discard'. Default 'preserve'.
]);

// var_dump($menu);exit;
?>
<div class="navbar-placeholder"></div>
<nav class="navbar fixed-top navbar-expand-lg">
    <!-- <div class="container-fluid">
        <div class="row">
            <div class="col-12"> -->
                <a class="navbar-brand" href="<?= home_url() ?>">
                    <img class="navbar-brand-img" src="<?= Theme::url('assets/img/ecjp-brand.svg') ?>" alt="<?= bloginfo('title') ?>">
                    <img class="navbar-brand-img navbar-brand-img-white" src="<?= Theme::url('assets/img/ecjp-brand-white.svg') ?>" alt="<?= bloginfo('title') ?>">
                </a>

                <button class="navbar-toggler"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggle-icon"></span>
                </button>

                <div class="offcanvas offcanvas-start"
                    tabindex="-1"
                    data-bs-scroll="true"
                    id="navbarSupportedContent"
                    aria-controls="offcanvasNavbar"
                    aria-label="Navbar menu">

                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel"></h5>

                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <?= $menu ?>
                    </div>
                    <div class="offcanvas-footer">
                    <?php
                        if (is_user_logged_in()) {

                            $cta_text = 'Contact Us';
                            $cta_url  = get_page_link(Theme::page('contact'));

                            if (count(array_intersect($user_roles, [
                                'student',
                            ])) > 0) {
                                $cta_text = 'My Account';
                                $cta_url  = get_page_link(Theme::page('dashboard'));
                            }

                            if (count(array_intersect($user_roles, [
                                'administrator',
                                'instructor',
                                'blogger',
                                'editor',
                            ])) > 0) {
                                $cta_text = 'Go to Dashboard';
                                $cta_url  = get_dashboard_url();
                            }

                    ?>
                        <a class="btn btn-primary cta px-4" href="<?= $cta_url ?>"><?= $cta_text ?></a>
                    <?php
                        } else {
                    ?>
                        <a class="btn cta cta-outline px-4" href="<?= get_page_link(Theme::page('login')) ?>">Login</a>
                        <a class="btn btn-primary cta px-4" href="<?= get_page_link(Theme::page('register')) ?>">Register</a>
                    <?php
                        }
                    ?>
                    </div>
                </div>
            <!-- </div>
        </div>
    </div> -->
</nav>
