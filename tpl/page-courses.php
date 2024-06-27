<?php
/**
 * Template Name: Courses
 */

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;
use NatOkpe\Wp\Theme\Impact\Utils\Request;

$user_roles = wp_get_current_user()->roles;

if (is_user_logged_in() && (count(array_intersect($user_roles, ['student'])) > 0)) {
    get_template_part('tpl/parts/courses');
} else {
    get_template_part('tpl/parts/courses-guest');
}
