<?php

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;
use NatOkpe\Wp\Theme\Impact\Utils\Request;

$cnt             = [
    'current_user' => wp_get_current_user(),
    'user_avatar'  => get_avatar_url(wp_get_current_user()->ID, [
        'size'          => 40,
        'default'       => 'mystery_man',
        'force_default' => false,
        'rating'        => 'G',
        // 'scheme'        => ''
    ]),
];

$cnt['user_avatar'] = is_string($cnt['user_avatar']) ? $cnt['user_avatar'] : '';

?>
<div class="toolbar border-bottom">
    <div class="toolbar-left">

        <button class="drawernav-toggle"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasDrawernav"
            aria-controls="offcanvasDrawernav">

            <span class="drawernav-toggle-box">
                <span class="drawernav-toggle-inner"></span>
            </span>
        </button>

        <a class="toolbar-brand" href="<?= get_page_link(Theme::page('dashboard')) ?>">ECJP Peace Institute</a>
    </div>

    <div class="toolbar-right">
        <div class="toolbar-actions">
            <!-- <button class="toolbar-action"><i class="icon feather icon-bell"></i></button> -->

            <div class="dropdown toolbar-action toolbar-action-user">
                <button class="dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    style="background-image: url(<?= $cnt['user_avatar'] ?>);">
                </button>

                <ul class="dropdown-menu dropdown-menu-end border-1 shadow-sm">
                    <li>
                        <a class="dropdown-item" href="<?= get_page_link(Theme::page('profile')) ?>"><i class="icon feather icon-user"></i> My Profile</a>
                    </li>
<!-- 
                    <li>
                        <a class="dropdown-item" href="<?= get_page_link(Theme::page('notifications')) ?>"><i class="icon feather icon-bell"></i> Notifications</a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="<?= get_page_link(Theme::page('chat')) ?>"><i class="icon feather icon-message-square"></i> Chat</a>
                    </li>
 -->
                    <li>
                        <a class="dropdown-item" href="<?= get_page_link(Theme::page('settings')) ?>"><i class="icon feather icon-settings"></i> Settings</a>
                    </li>
<!-- 
                    <li>
                        <a class="dropdown-item" href="<?= get_page_link(Theme::page('faq')) ?>"><i class="icon feather icon-help-circle"></i> Help</a>
                    </li>
 -->
                    <li>
                        <hr class="dropdown-divider" />
                    </li>

                    <li>
                        <a class="dropdown-item" href="<?= add_query_arg(['n' => ''], get_page_link(Theme::page('logout'))) ?>"><i class="icon feather icon-log-out"></i> Log out</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
