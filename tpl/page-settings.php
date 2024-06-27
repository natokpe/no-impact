<?php
/**
 * Template Name: Settings
 */
declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;
use NatOkpe\Wp\Theme\Impact\Utils\Request;

Theme::requireLogin();

$screen          = 'profile';
$screen_errors   = [];
$cnt             = [
    'user' => wp_get_current_user(),
    'user_avatar'  => get_avatar_url(wp_get_current_user()->ID, [
        'size'          => 200,
        'default'       => 'mystery_man',
        'force_default' => false,
        'rating'        => 'G',
        // 'scheme'        => ''
    ]),
];

if (in_array(Request::get('view'), [
    'profile',
    'notifications',
    'privacy',
], true)) {
    $screen = Request::get('view');
}

// switch (Request::get('view')) {
//     case 'notifications':
//     case 'privacy':
//         $screen = Request::get('view');
//         break;

//     default:
//         $screen = 'profile';
//         break;
// }

/* ******* Profile ******* */
if ($screen === 'profile') {
    $form = [
        'nonce_action'          => 'edit_profile' . get_the_ID(),
        'first_name'     => $cnt['user']->first_name,
        'last_name'      => $cnt['user']->last_name,
        'gender'         => get_user_meta($cnt['user']->ID, 'gender', true),
        'marital_status' => get_user_meta($cnt['user']->ID, 'marital_status', true),
    ];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $upd      = true;
        $form_fmr = $form;
        $form     = array_merge($form, [
            'nonce'          => Request::post('vtkn', ''),
            'first_name'     => Request::post('first_name', ''),
            'last_name'      => Request::post('last_name', ''),
            'gender'         => Request::post('gender', ''),
            'marital_status' => Request::post('marital_status', ''),
        ]);

        if (! wp_verify_nonce($form['nonce'], $form['nonce_action'])) {
            $upd = false;
            $screen_errors['nonce'] = 'Page timeout. Refresh the page and try again.';
        }

        if (empty($form['first_name'])) {
            $upd = false;
            $screen_errors['first_name'] = 'First name is required.';
        }

        if (empty($form['last_name'])) {
            $upd                        = false;
            $screen_errors['last_name'] = 'Last name is required.';
        }

        if (empty($form['gender'])) {
            $upd                     = false;
            $screen_errors['gender'] = 'Gender is required.';
        }

        if (empty($form['marital_status'])) {
            $upd                             = false;
            $screen_errors['marital_status'] = 'Marital status is required.';
        }

        if ($upd) {
            $user             = $cnt['user'];
            $user->first_name = $form['first_name'];
            $user->last_name  = $form['last_name'];

            if (! is_int(wp_update_user($user))) {
                $screen_errors['general'] = 'Failed to update profile. Contact support.';
            }

            $user_m = update_user_meta(
                $cnt['user']->ID,
                'gender',
                $form['gender'],
                $form_fmr['gender']
            );

            if (($form['gender'] === $form_fmr['gender'])
                && (! is_int($user_m)) && ($user_m !== true)) {
                $screen_errors['general'] = 'Failed to update profile. Contact support.';
            }

            $user_m = update_user_meta(
                $cnt['user']->ID,
                'marital_status',
                $form['marital_status'],
                $form_fmr['marital_status']
            );

            // var_dump($user_m, $form, $form_fmr);exit;

            if (($form['marital_status'] === $form_fmr['marital_status'])
                && (! is_int($user_m)) && ($user_m !== true)) {
                $screen_errors['general'] = 'Failed to update profile. Contact support.';
            }
        }
    }
}

/* ******* Notifications ******* */
// if ($screen === 'profile') {
//     $form = [
//         'nonce_action'          => 'edit_profile' . get_the_ID(),
//         'first_name'     => $cnt['user']->first_name,
//         'last_name'      => $cnt['user']->last_name,
//         'gender'         => get_user_meta($cnt['user']->ID, 'gender', true),
//         'marital_status' => get_user_meta($cnt['user']->ID, 'marital_status', true),
//     ];

//     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     }
// }

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
                                <li class="breadcrumb-item"><a href="<?= Theme::page('dashboard') ?>">Account</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Settings</li>
                            </ol>
                        </nav>

                    </div>

                    <div class="col-12">
                        <h1 class="display-6">Settings</h1>
                    </div>

                    <div class="col-12">

                        <div class="card border-0 border-bottom mt-3 mb-3">
                            <div class="card-body">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link<?= ($screen === 'profile' ? ' active' : '') ?>" aria-current="page" href="<?= get_page_link(Theme::page('settings')) ?>">Edit Profile</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link<?= ($screen === 'notifications' ? ' active' : '') ?>" href="<?= add_query_arg(['view' => 'notifications'], get_page_link(Theme::page('settings'))) ?>">Notifications</a>
                                    </li>
                                    <li class="nav-item flex-end">
                                        <a class="nav-link<?= ($screen === 'privacy' ? ' active' : '') ?>" href="<?= add_query_arg(['view' => 'privacy'], get_page_link(Theme::page('settings'))) ?>">Privacy & Security</a>
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
                        case 'profile':
                            ?>
                            <div class="col-md-10 col-xl-9">
                                <form class="mb-4" method="POST" action="<?= Theme::urlNow() ?>">

                                    <input type="hidden" hidden="hidden" name="settings" value="profile" />

                                    <input type="hidden"
                                        hidden="hidden"
                                        name="vtkn"
                                        value="<?= wp_create_nonce($form['nonce_action']); ?>" 
                                    />

                                    <div class="card border-0 mb-4">

                                        <div class="card-body">
                                            <h3 class="card-title">Personal Info</h3>


                                            <div class="row justify-content-start justify-content-md-start">

                                                <div class="col-6 col-sm-5 col-md-4 col-lg-5 col-xl-4">
                                                    <div class="mt-4">
                                                        <div class="mb-1">
                                                            <div class="form-label">Profile Picture</div>
                                                            <img class="img-thumbnail rounded-0" src="<?= $cnt['user_avatar'] ?>" rel="noreferrer noopener" target="_blank" alt="" />
                                                        </div>
                                                        <a href="https://en.gravatar.com/">You can change your profile picture on Gravatar.</a>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">

                                                <div class="col-12">
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mt-4">
                                                        <label for="user-first_name" class="form-label">First Name</label>
                                                        <input type="text"
                                                            class="form-control"
                                                            id="user-first_name"
                                                            name="first_name"
                                                            placeholder=""
                                                            value="<?= $form['first_name'] ?>" 
                                                        />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mt-4">
                                                        <label for="user-last_name" class="form-label">Last Name</label>
                                                        <input type="text"
                                                            class="form-control"
                                                            id="user-last_name"
                                                            name="last_name"
                                                            placeholder=""
                                                            value="<?= $form['last_name'] ?>" 
                                                        />
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mt-4">
                                                        <label class="form-label" for="user-gender">I am...</label>
                                                        <select class="form-select" name="gender" id="user-gender">
                                                            <option>--SELECT--</option>
                                                            <option value="male"<?= $form['gender'] === 'male' ? ' selected' : '' ?>>Male</option>
                                                            <option value="female"<?= $form['gender'] === 'female' ? ' selected' : '' ?>>Female</option>
                                                            <option value="non-binary"<?= $form['gender'] === 'non-binary' ? ' selected' : '' ?>>Non-binary</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                </div>
                                                
                                                <div class="col-12">
                                                    <div class="mt-4">
                                                        <div class="form-label">Marital Status</div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio"
                                                                class="form-check-input"
                                                                id="user-marital_status_single"
                                                                name="marital_status"
                                                                value="single"
                                                                <?= $form['marital_status'] === 'single' ? ' checked="checked"' : '' ?>
                                                            />
                                                            <label for="user-marital_status_single" class="form-check-label">Single</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio"
                                                                class="form-check-input"
                                                                id="user-marital_status_married"
                                                                name="marital_status"
                                                                value="married"
                                                                <?= $form['marital_status'] === 'married' ? ' checked="checked"' : '' ?>
                                                            />
                                                            <label for="user-marital_status_married" class="form-check-label">Married</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio"
                                                                class="form-check-input"
                                                                id="user-marital_status_divorced"
                                                                name="marital_status"
                                                                value="divorced"
                                                                <?= $form['marital_status'] === 'divorced' ? ' checked="checked"' : '' ?>
                                                            />
                                                            <label for="user-marital_status_divorced" class="form-check-label">Divorced</label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="">
                                        <button type="submit" class="btn btn-primary px-5">Save</button>
                                    </div>

                                </form>

                            </div>
                            <?php
                            break;

                        case 'notifications':
                            ?>
                            <div class="col-12">
                                <div class="card border-0">
                                    <div class="card-body">
                                    </div>
                                </div>
                            </div>
                            <?php
                            break;
                        
                        case 'profile':
                        default:
                        ?>
                            <div class="col-12">
                                <div class="card card-course border-0 mb-3">
                                    <div class="card-body">
                                    </div>
                                </div>
                            </div>
                        <?php
                            break;
                    }

                    ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="content-frame-footer pt-4 mt-5">
        <?php  // get_template_part('tpl/parts/footer'); ?>
    </footer>
</div>
<?php

endwhile;

get_footer();
