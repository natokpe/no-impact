<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

declare(strict_types = 1);

namespace NatOkpe\Wp\Theme\Impact;

use NatOkpe\Wp\Theme\Impact\Loaders\AssetLoader;
use NatOkpe\Wp\Theme\Impact\Loaders\WidgetLoader;

use function \add_action;
use function \add_theme_support;
use function \get_stylesheet_directory;
use function \get_stylesheet_directory_uri;
use function \load_theme_textdomain;
use function \cmb2_get_option;

/**
 * The short description
 *
 * As many lines of extendend description as you want {@link element} links to an element
 * {@link http://www.example.com Example hyperlink inline link} links to a website
 * Below this goes the tags to further describe element you are documenting
 *
 * @param type $varname description
 * @param string $month 3-letter Month abbreviation
 * @param integer $day day of the month
 * @param integer $year year
 * @return type description
 * @access public
 * @author Nat Okpe <natokpe@gmail.com>
 * @copyright name date
 * @version 1.0.0
 * @see name of another element that can be documented, produces a link to it in the documentation
 * @link a url
 * @since a version or a date
 * @deprec alias for deprecated
 * @magic phpdoc.de compatibility
 * @todo phpdoc.de compatibility
 * @exception Javadoc-compatible, use as needed
 * @throws Javadoc-compatible, use as needed
 * @var type a data type for a class variable
 * @package package name
 * @subpackage sub package name, groupings inside of a project
 */
class Theme
{
    /**
     * @var \NatOkpe\Ecjp\Theme\Config
     */
    private
    $_config = null;

    /**
     * 
     */
    public
    function __construct(Config $config)
    {
        $this->_config = $config->close();
    }

    /**
     * 
     */
    public static
    function url(...$parts): string
    {
        $a = [];
        $p = [];
        $d = get_stylesheet_directory_uri();

        foreach ($parts as $part) {
            if (is_array($part)) {
                $a = array_merge($a, $part);
            } else {
                if (is_scalar($part)) {
                    $a[] = $part;
                }
            }
        }

        foreach ($a as $part) {
            $p[] = ltrim((string) $part, '/');
        }

        $d = ! empty($p) ? rtrim($d, '/') : $d;

        return implode('/', array_merge([$d], $p));
    }

    /**
     * 
     */
    public static
    function urlNow(bool $includeQuery = true): string
    {
        global $wp;
        $u = home_url($wp->request);
        return $includeQuery ? add_query_arg($wp->query_vars, $u) : $u;
    }

    /**
     * 
     */
    public static
    function dir(...$parts): string
    {
        $a = [];
        $p = [];
        $d = get_stylesheet_directory();

        foreach ($parts as $part) {
            if (is_array($part)) {
                $a = array_merge($a, $part);
            } else {
                if (is_scalar($part)) {
                    $a[] = $part;
                }
            }
        }

        foreach ($a as $part) {
            $p[] = ltrim((string) $part, '\\/');
        }

        $d = ! empty($p) ? rtrim($d, '\\/') : $d;

        return implode(DIRECTORY_SEPARATOR, array_merge([$d], $p));
    }

    /**
     * 
     */
    public static
    function dirAssets(...$parts): string
    {
        return self::dir('assets', ...$parts);
    }

    /**
     * 
     */
    public static
    function dirConfig(...$parts): string
    {
        return self::dir('config', ...$parts);
    }

    /**
     * 
     */
    public static
    function path(...$parts): string
    {
        $a = [];
        $p = [];

        foreach ($parts as $part) {
            if (is_array($part)) {
                $a = array_merge($a, $part);
            } else {
                if (is_scalar($part)) {
                    $a[] = $part;
                }
            }
        }

        foreach ($a as $part) {
            if (is_scalar($part)) {
                $p[] = $part;
            }
        }

        return implode(DIRECTORY_SEPARATOR, $p);
    }

    /**
     * 
     */
    public static
    function env(?string $key = null, $alt = null): mixed
    {
        return is_null($key) ? ($_ENV ?? []) : ($_ENV[$key] ?? $alt);
    }

    /**
     * 
     */
    public static
    function get_option(string $key = '', $default = false, string $store = 'no_opts')
    {
        if (function_exists( 'cmb2_get_option' ) ) {
            // Use cmb2_get_option as it passes through some key filters.
            return cmb2_get_option($store, $key, $default );
        }

        // Fallback to get_option if CMB2 is not loaded yet.
        $opts = get_option( $store, $default );

        $val = $default;

        if ( 'all' == $key ) {
            $val = $opts;
        } elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
            $val = $opts[ $key ];
        }

        return $val;
    }

    /**
     * 
     */
    public static
    function base64_url_encode(string $data): string|bool
    {
        $res = base64_encode($data);
        return $res === false ? false : rtrim(strtr($res, '+/', '-_'), '=');
    }

    /**
     * 
     */
    public static
    function base64_url_decode(string $data, bool $strict = false): string|bool
    {
        $res = base64_decode(strtr($data, '-_', '+/'), $strict);
        return $res === false ? false : $res;
    }

    /**
     * 
     */
    public static
    function encodeResetToken(int $user_id, string $key): string
    {
        return self::base64_url_encode((string) $user_id . '||' . $key);
    }

    /**
     * 
     */
    public static
    function decodeResetToken(string $token): array|bool
    {
        $res = self::base64_url_decode($token);

        if ($res === false) {
            return false;
        }

        $res = explode('||', $res);

        if (count($res) !== 2) {
            return false;
        }

        $res = [
            'user' => $res[0],
            'key'  => $res[1],
        ];

        return ctype_digit($res['user']) ? $res : false;
    }

    /**
     * 
     */
    public static
    function getResetUrl(string $token): string
    {
        return add_query_arg(
            [
                (new Config([]))->password_reset_url_token_key => urlencode($token),
            ],
            get_page_link(self::page('password-reset'))
        );
    }

    /**
     * 
     */
    public static
    function page(string $name): null|int
    {
        $pg = (get_option('page_loc') ?? [])[$name] ?? null;

        return is_string($pg) && ctype_digit($pg) ? (int) $pg : null;
    }

    /**
     * 
     */
    public static
    function requireLogin(?string $location = null): void
    {
        if (! is_user_logged_in()) {
            wp_redirect($location ?? home_url());
            exit;
        }
    }

    /**
     * 
     */
    public static
    function requireLogout(?string $location = null): void
    {
        if (is_user_logged_in()) {
            wp_redirect($location ?? home_url());
            exit;
        }
    }

    /**
     * 
     */
    public static
    function pageAccess(array $allowedRoles = [], array $excludeRoles = [], ?string $location = null): void
    {
        if (! is_user_logged_in()) {
            return;
        }

        $user_allowed = false;
        $user_roles   = wp_get_current_user()->roles;

        if (count(array_intersect($user_roles, $allowedRoles)) > 0) {
            $user_allowed = true;
        }

        if (count(array_intersect($user_roles, $excludeRoles)) > 0) {
            $user_allowed = false;
        }

        if (! $user_allowed) {
            $rdir_url = home_url();

            if (count(array_intersect($user_roles, [
                'administrator',
                'instructor',
            ])) > 0) {
                $rdir_url = get_admin_url();
            };

            if (count(array_intersect($user_roles, [
                'student'
            ])) > 0) {
                $rdir_url = get_page_link(Theme::page('dashboard'));
            };

            wp_redirect($location ?? $rdir_url);
            exit;
        }
    }

    public static
    function add_body_classes(string ...$body_classes): void
    {
        add_filter('body_class', function($classes) use ($body_classes)
        {
            // TODO: check uniqueness
            // TODO: remove empty strings
            return array_merge($classes, $body_classes);
        });
    }

    /**
     * 
     */
    public
    function start(): self
    {
        global $content_width;

        load_theme_textdomain('natokpe', self::dir('lang'));

        add_filter('locale', function ($locale) {
            return $this->_config->locale;
        });

        add_filter('retrieve_password_message', function ($msg, $key, $user_login, $user_data) {
            return 'Click ' . self::getResetUrl(self::encodeResetToken($user_data->ID, $key));
        }, 20, 4);

        add_action('after_setup_theme', function () {
            foreach ($this->_config->theme_support as $_ => $__) {
                add_theme_support($_, $__);
            }

            // add_editor_style();
        });

        add_action('customize_register', function ($wp_customize) {
            $panels   = [];
            $sections = [];
            $controls = [];
            $settings = [];

            foreach ($this->_config->customizer as $_p => $__p) {
                $is_panel = isset($__p['sections']);

                $sections_raw = $is_panel ? $__p['sections'] : [$_p => $__p];

                if ($is_panel) {
                    $panels[$_p] = [
                        'title'    => $__p['title'] ?? '',
                        'priority' => $__p['priority'] ?? 10,
                    ];
                }

                foreach ($sections_raw as $_s => $__s) {
                    $sections[$_s] = [
                        'title'    => $__s['title'] ?? '',
                        'priority' => $__s['priority'] ?? 10,
                    ];

                    if ($is_panel) {
                        $sections[$_s]['panel'] = $_p;
                    }

                    foreach ($__s['controls'] ?? [] as $_c => $__c) {
                        if (! isset($__c['settings'])) {
                            continue;
                        }

                        if ((! is_string($__c['settings'])) && (! is_array($__c['settings']))) {
                            continue;
                        }

                        if (is_string($__c['settings'])) {
                            $setting_id = $__c['settings'];

                            $settings[$__c['settings']] = [
                                'capability' => 'edit_theme_options',
                                'type'       => 'theme_mod',
                            ];
                        }

                        if (is_array($__c['settings'])) {
                            if (! isset($__c['settings']['setting'])) {
                                continue;
                            }

                            $setting_id = $__c['settings']['setting'];

                            $settings[$__c['settings']['setting']] = array_merge([
                                'capability' => 'edit_theme_options',
                                'type'       => 'theme_mod',
                            ], $__c['settings']);

                            unset($settings[$__c['settings']['setting']]['setting']);
                        }

                        $controls[$_c] = $__c;
                        $controls[$_c]['section'] = $_s;
                        $controls[$_c]['settings'] = $setting_id;
                    }
                }
            }

            foreach ($settings as $_k => $_p) {
                $wp_customize->add_setting($_k, $_p);
            }

            foreach ($panels as $_k => $_p) {
                $wp_customize->add_panel($_k, $_p);
            }

            foreach ($sections as $_k => $_p) {
                $wp_customize->add_section($_k, $_p);
            }
            // $wp_customize->add_section('hello', ['title' => 'Hello']);
            // $wp_customize->add_control('hhhh', [ 
            //     'type'     => 'text',
            //     'default'  => 'Welcome',
            //     'label'    => __('Call-In Text', 'natokpe'),
            //     'settings' => 'hero_call',
            //     'section' => 'hello',
            // ]);

            foreach ($controls as $_k => $_p) {
                if ((! is_string($_p['type'] ?? null)) && (! is_callable($_p['type'] ?? null))) {
                    continue;
                }

                if (is_callable($_p['type'])) {
                    $params = $_p;

                    unset($params['type']);

                    $wp_customize->add_control($_p['type']($wp_customize, $_k, $params));

                    continue;
                }

                $wp_customize->add_control($_k, $_p);
            }
        });

        $loader = (new AssetLoader([
            'styles'  => $this->_config->assets['styles'] ?? [],
            'scripts' => $this->_config->assets['scripts'] ?? []
        ]))->loadAll();

        $loader = (new WidgetLoader([
            'widgets'          => $this->_config->widgets ?? [],
            'widget_locations' => $this->_config->widget_locations ?? []
        ]))->loadAll();

        add_action('init', function () {
            register_nav_menus([
                'navbar'                 => __('Navbar', 'natokpe'),
                'navbar_guest'           => __('Navbar - Guest', 'natokpe'),
                'footer_primary'         => __('Footer (Primary)', 'natokpe'),
                'footer_primary_guest'   => __('Footer (Primary) - Guest', 'natokpe'),
                'footer_secondary'       => __('Footer (Secondary)', 'natokpe'),
                'footer_secondary_guest' => __('Footer (Secondary) - Guest', 'natokpe'),
            ]);
        });

        $content_width = 800;

        return $this;
    }
}
/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
