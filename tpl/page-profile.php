<?php
/**
 * Template Name: Profile
 */

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;

if (is_user_logged_in()) {
    $rdir_url = home_url();

    $user_has_access = count(array_intersect(wp_get_current_user()->roles, [
        'student'
    ])) > 0;

    if (! $user_has_access) {
        $rdir_url = (count(array_intersect(wp_get_current_user()->roles, [
            'administrator',
            'instructor',
        ])) > 0) ? get_admin_url() : $rdir_url;

        wp_redirect($rdir_url, 302, bloginfo('title'));
        exit;
    }
} else {
    wp_redirect(get_page_link(Theme::page('login')), 302, bloginfo('title'));
    exit;
}

get_header();



get_template_part('tpl/parts/navbar');
// https://www.davidangulo.xyz/posts/how-to-verify-email-address-in-php/

$userdata = array(
    'ID'                    => 0,   //(int) User ID. If supplied, the user will be updated.
    'user_pass'             => '',  //(string) The plain-text user password.
    'user_login'            => '',  //(string) The user's login username.
    'user_nicename'         => '',  //(string) The URL-friendly user name.
    'user_url'              => '',  //(string) The user URL.
    'user_email'            => '',  //(string) The user email address.
    'display_name'          => '',  //(string) The user's display name. Default is the user's username.
    'nickname'              => '',  //(string) The user's nickname. Default is the user's username.
    'first_name'            => '',  //(string) The user's first name. For new users, will be used to build the first part of the user's display name if $display_name is not specified.
    'last_name'             => '',  //(string) The user's last name. For new users, will be used to build the second part of the user's display name if $display_name is not specified.
    'description'           => '',  //(string) The user's biographical description.
    'rich_editing'          => '',  //(string|bool) Whether to enable the rich-editor for the user. False if not empty.
    'syntax_highlighting'   => '',  //(string|bool) Whether to enable the rich code editor for the user. False if not empty.
    'comment_shortcuts'     => '',  //(string|bool) Whether to enable comment moderation keyboard shortcuts for the user. Default false.
    'admin_color'           => '',  //(string) Admin color scheme for the user. Default 'fresh'.
    'use_ssl'               => '',  //(bool) Whether the user should always access the admin over https. Default false.
    'user_registered'       => '',  //(string) Date the user registered. Format is 'Y-m-d H:i:s'.
    'show_admin_bar_front'  => '',  //(string|bool) Whether to display the Admin Bar for the user on the site's front end. Default true.
    'role'                  => '',  //(string) User's role.
    'locale'                => '',  //(string) User's locale. Default empty.

);
