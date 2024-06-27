<?php
/**
 * Template Name: Logout
 */

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;
use NatOkpe\Wp\Theme\Impact\Utils\Request;

wp_logout();
wp_redirect(get_page_link(Theme::page('login')), 302, bloginfo('title'));
exit;
