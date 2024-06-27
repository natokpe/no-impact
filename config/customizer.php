<?php

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact;

// Priorities
// Site Title & Tagline (title_tagline): 20
// Colors (colors): 40
// Header Image (header_image): 60
// Background Image (background_image): 80
// Menus (Panel) (nav_menus): 100
// Widgets (Panel) (widgets): 110
// Static Front Page (static_front_page): 120
// default: 160
// Additional CSS (custom_css): 200

$customizer = [
    'content_layout' => [
        'title'    => 'Content and Layout',
        'priority' => 300,
        'sections' => [
            'hero' => [
                'title'    => __('Hero Banner', 'natokpe'),
                'priority' => 10,
                'controls'   => [
                    'hero_img' => [
                        'label'    => __('Banner Image', 'natokpe'),
                        'settings' => 'hero_img',
                        'type'  => function ($wp_customize, $control_id, $params) {
                            return new WP_Customize_Image_Control(
                                $wp_customize,
                                $control_id,
                                $params
                            );
                        },
                    ],

                    'hero_call' => [ 
                        'type'     => 'text',
                        'default'  => 'Welcome',
                        'label'    => __('Call-In Text', 'natokpe'),
                        'settings' => [
                            'setting'    => 'hero_call',
                            'capability' => 'edit_theme_options',
                            'type'       => 'theme_mod',
                        ],
                    ],

                    'hero_title' => [
                        'type'     => 'textarea',
                        'label'    => __('Lead Text', 'natokpe'),
                        'settings' => 'hero_title',
                    ],

                    'hero_cta_text' => [
                        'type'     => 'text',
                        'default'  => 'Enroll Now',
                        'label'    => __('CTA Button Text', 'natokpe'),
                        'settings' => 'hero_cta_text',
                    ],

                    'hero_cta_url' => [
                        'type'     => 'url',
                        'label'    => __('CTA Button URL', 'natokpe'),
                        'settings' => 'hero_cta_url',
                    ],
                ],
            ],

            'intro' => [
                'title'    => __('Intro', 'natokpe'),
                'controls'   => [
                    'intro_img' => [
                        'label'    => __('Intro Image', 'natokpe'),
                        'settings' => 'intro_img',
                        'type'  => function ($wp_customize, $control_id, $params) {
                            return new WP_Customize_Image_Control(
                                $wp_customize,
                                $control_id,
                                $params
                            );
                        },
                    ],

                    'intro_h' => [
                        'type'     => 'textarea',
                        'label'    => __('Heading', 'natokpe'),
                        'settings' => 'intro_heading',
                    ],

                    'intro_sum' => [
                        'type'     => 'textarea',
                        'label'    => __('Summary', 'natokpe'),
                        'settings' => 'intro_sum',
                    ],

                    'intro_cta_text' => [
                        'type'     => 'text',
                        'default'  => 'Learn More',
                        'label'    => __('CTA Button Text', 'natokpe'),
                        'settings' => 'intro_cta_text',
                    ],

                    'intro_cta_url' => [
                        'type'     => 'url',
                        'default'  => '',
                        'label'    => __('CTA Button URL', 'natokpe'),
                        'settings' => 'intro_cta_url',
                    ],
                ],
            ],

            'ovv' => [
                'title'    => __('Overview', 'natokpe'),
                'controls'   => [
                    'ovv_img' => [
                        'label'    => __('Overview Image', 'natokpe'),
                        'settings' => 'ovv_img',
                        'type'  => function ($wp_customize, $control_id, $params) {
                            return new WP_Customize_Image_Control(
                                $wp_customize,
                                $control_id,
                                $params
                            );
                        },
                    ],

                    'ovv_h' => [
                        'type'     => 'textarea',
                        'label'    => __('Heading', 'natokpe'),
                        'settings' => 'ovv_h',
                    ],

                    'ovv_sum' => [
                        'type'     => 'textarea',
                        'label'    => __('Summary', 'natokpe'),
                        'settings' => 'ovv_sum',
                    ],
                ],
            ],

            'cta' => [
                'title'    => __('Call to Action', 'natokpe'),
                'controls' => [

                    'cta_sum' => [
                        'type'     => 'textarea',
                        'label'    => __('Summary', 'natokpe'),
                        'settings' => 'cta_sum',
                    ],

                    'cta_btn_text' => [
                        'type'     => 'text',
                        'default'  => 'Enroll Now',
                        'label'    => __('Button Text', 'natokpe'),
                        'settings' => 'cta_btn_txt',
                    ],

                    'cta_btn_url' => [
                        'type'     => 'url',
                        'default'  => '',
                        'label'    => __('Button URL', 'natokpe'),
                        'settings' => 'cta_btn_url',
                    ],
                ],
            ],
        ],
    ],




    'page_locations' => [
        'title'    => __('Page Locations', 'natokpe'),
        'priority' => 301,
        'controls'   => [
            'no_pg_register' => [
                'type'     => 'dropdown-pages',
                'label'    => __('Sign up / Registration', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[register]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_login' => [
                'type'     => 'dropdown-pages',
                'label'    => __('Login', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[login]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],


            'no_pg_password-recover' => [
                'type'     => 'dropdown-pages',
                'label'    => __('Recover Password', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[password-recover]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_password-reset' => [
                'type'     => 'dropdown-pages',
                'label'    => __('Reset Password', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[password-reset]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_dashboard' => [
                'type'     => 'dropdown-pages',
                'label'    => __('Dashboard', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[dashboard]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_profile' => [
                'type'     => 'dropdown-pages',
                'label'    => __('Profile', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[profile]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_notif' => [
                'type'     => 'dropdown-pages',
                'label'    => __('Notifications', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[notifications]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_chat' => [
                'type'     => 'dropdown-pages',
                'label'    => __('Chat', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[chat]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_settings' => [
                'type'     => 'dropdown-pages',
                'label'    => __('Settings', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[settings]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_list-courses' => [
                'type'     => 'dropdown-pages',
                'label'    => __('List Courses', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[list-courses]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_list-classes' => [
                'type'     => 'dropdown-pages',
                'label'    => __('List Classes', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[list-classes]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_payment' => [
                'type'     => 'dropdown-pages',
                'label'    => __('Payment', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[payment]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_contact' => [
                'type'     => 'dropdown-pages',
                'label'    => __('Contact', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[contact]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_about' => [
                'type'     => 'dropdown-pages',
                'label'    => __('About', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[about]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_terms' => [
                'type'     => 'dropdown-pages',
                'label'    => __('Terms and Conditions', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[terms]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_help' => [
                'type'     => 'dropdown-pages',
                'label'    => __('Help', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[help]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_faq' => [
                'type'     => 'dropdown-pages',
                'label'    => __('Frequently Asked Questions (FAQ)', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[faq]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

            'no_pg_logout' => [
                'type'     => 'dropdown-pages',
                'label'    => __('Log out', 'natokpe'),
                'settings' => [
                    'setting'    => 'page_loc[logout]',
                    'capability' => 'edit_theme_options',
                    'type'       => 'option',
                ],
            ],

        ],
    ],
];

return $customizer;


// //  =============================
// //  = Radio Input               =
// //  =============================
// $wp_customize->add_setting('themename_theme_options[color_scheme]', array(
//     'default'        => 'value2',
//     'capability'     => 'edit_theme_options',
//     'type'           => 'option',
// ));

// $wp_customize->add_control('themename_color_scheme',
//     array(
//         'label'      => __('Color Scheme', 'themename'),
//         'section'    => 'themename_color_scheme',
//         'settings'   => 'themename_theme_options[color_scheme]',
//         'type'       => 'radio',
//         'choices'    => array(
//         'value1' => 'Choice 1',
//         'value2' => 'Choice 2',
//         'value3' => 'Choice 3',
//     ),
// ));

// //  =============================
// //  = Checkbox                  =
// //  =============================
// $wp_customize->add_setting('themename_theme_options[checkbox_test]', array(
//     'capability' => 'edit_theme_options',
//     'type'       => 'option',
// ));

// $wp_customize->add_control('display_header_text', array(
//     'settings' => 'themename_theme_options[checkbox_test]',
//     'label'    => __('Display Header Text'),
//     'section'  => 'themename_color_scheme',
//     'type'     => 'checkbox',
// ));

// //  =============================
// //  = Select Box                =
// //  =============================
// $wp_customize->add_setting('themename_theme_options[header_select]', array(
// 'default'        => 'value2',
// 'capability'     => 'edit_theme_options',
// 'type'           => 'option',

// ));
// $wp_customize->add_control( 'example_select_box', array(
//     'settings' => 'themename_theme_options[header_select]',
//     'label'   => 'Select Something:',
//     'section' => 'themename_color_scheme',
//     'type'    => 'select',
//     'choices'    => array(
//     'value1' => 'Choice 1',
//     'value2' => 'Choice 2',
//     'value3' => 'Choice 3',
// ),
// ));

// //  =============================
// //  = File Upload               =
// //  =============================
// $wp_customize->add_setting('themename_theme_options[upload_test]', array(
// 'default'           => 'arse',
// 'capability'        => 'edit_theme_options',
// 'type'           => 'option',

// ));

// $wp_customize->add_control( new WP_Customize_Upload_Control($wp_customize, 'upload_test', array(
// 'label'    => __('Upload Test', 'themename'),
// 'section'  => 'themename_color_scheme',
// 'settings' => 'themename_theme_options[upload_test]',
// )));

// //  =============================
// //  = Color Picker              =
// //  =============================
// $wp_customize->add_setting('themename_theme_options[link_color]', array(
// 'default'           => '#000',
// 'sanitize_callback' => 'sanitize_hex_color',
// 'capability'        => 'edit_theme_options',
// 'type'           => 'option',

// ));

// $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'link_color', array(
// 'label'    => __('Link Color', 'themename'),
// 'section'  => 'themename_color_scheme',
// 'settings' => 'themename_theme_options[link_color]',
// )));

// //  =============================
// //  = Page Dropdown             =
// //  =============================
// $wp_customize->add_setting('themename_theme_options[page_test]', array(
// 'capability'     => 'edit_theme_options',
// 'type'           => 'option',

// ));

// $wp_customize->add_control('themename_page_test', array(
// 'label'      => __('Page Test', 'themename'),
// 'section'    => 'themename_color_scheme',
// 'type'    => 'dropdown-pages',
// 'settings'   => 'themename_theme_options[page_test]',
// ));

// =====================
//  = Category Dropdown =
//  =====================
// $categories = get_categories();
// $cats = array();
// $i = 0;
// foreach($categories as $category) {
// if($i==0){
// $default = $category->slug;
// $i++;
// }
// $cats[$category->slug] = $category->name;
// }

// $wp_customize->add_setting('_s_f_slide_cat', array(
// 'default'        => $default
// ));
// $wp_customize->add_control( 'cat_select_box', array(
// 'settings' => '_s_f_slide_cat',
// 'label'   => 'Select Category:',
// 'section'  => '_s_f_home_slider',
// 'type'    => 'select',
// 'choices' => $cats,
// ));
// }
// Do stuff with $wp_customize, the WP_Customize_Manager object.

/*

    'no_pl' => [
        'title'       => esc_html__('Page locations', 'natokpe'),
        'option_key'  => 'no_pl',
        'parent_slug' => 'options-general.php',
        // 'icon_url' => '',
        'menu_title'  => esc_html__('Impact', 'natokpe' ),
        'capability'  => 'manage_options',

        'tab_group'   => 'no_impact',
        'tab_title'   => 'Pages',

        // 'position'        => 20, // Menu position. Only applicable if 'parent_slug' is left empty.
        // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
        // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
        'save_button'     => esc_html__('Save', 'natokpe' ), // The text for the options-page save button. Defaults to 'Save'.
        // 'disable_settings_errors' => true, // On settings pages (not options-general.php sub-pages), allows disabling.
        // 'message_cb'      => 'yourprefix_options_page_message_callback',

        'page-sections' => [
            'no_pl_t' => [
                'name' => 'Page locations',
                'desc' => 'Set the locations',

                'fields' => [
                    'no_pg_reg' => [
                        'name'             => 'Sign up',
                        'type'             => 'select',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_login' => [
                        'name'             => 'Log in',
                        'type'             => 'select',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_prec' => [
                        'name'             => 'Recover password',
                        'type'             => 'select',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_pres' => [
                        'name'             => 'Reset Password',
                        'type'             => 'select',
                        'desc'             => 'Page that is displayed after user clicks on password reset link',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_dash' => [
                        'name'             => 'Dashboard',
                        'type'             => 'select',
                        'desc'             => '',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_prof' => [
                        'name'             => 'Profile',
                        'type'             => 'select',
                        'desc'             => '',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_notif' => [
                        'name'             => 'Notifications',
                        'type'             => 'select',
                        'desc'             => '',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_chat' => [
                        'name'             => 'Chat',
                        'type'             => 'select',
                        'desc'             => '',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_logout' => [
                        'name'             => 'Logout',
                        'type'             => 'select',
                        'desc'             => '',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_prefs' => [
                        'name'             => 'Settings',
                        'type'             => 'select',
                        'desc'             => '',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_lcourses' => [
                        'name'             => 'List Courses',
                        'type'             => 'select',
                        'desc'             => '',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_lclasses' => [
                        'name'             => 'List Classes',
                        'type'             => 'select',
                        'desc'             => '',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_asm' => [
                        'name'             => 'Assessments',
                        'type'             => 'select',
                        'desc'             => '',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_bdg' => [
                        'name'             => 'Badges',
                        'type'             => 'select',
                        'desc'             => '',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_certs' => [
                        'name'             => 'Certificates',
                        'type'             => 'select',
                        'desc'             => '',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_payment' => [
                        'name'             => 'Payment',
                        'type'             => 'select',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_contact' => [
                        'name'             => 'Contact',
                        'type'             => 'select',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_about' => [
                        'name'             => 'About',
                        'type'             => 'select',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_terms' => [
                        'name'             => 'Terms and Conditions',
                        'type'             => 'select',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],

                    'no_pg_faq' => [
                        'name'             => 'FAQ',
                        'type'             => 'select',
                        'show_option_none' => true,
                        'options'          => $opgs,
                    ],
                ],
            ],
        ],
    ],

*/
