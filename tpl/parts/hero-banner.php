<?php

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;

?>
<div class="hero-banner" style="background-image: url('<?= get_theme_mod('hero_img', '') ?>');">
    <div class="hero-banner-overlay">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="hero-banner-content">
                        <?php
                            if (! empty(get_theme_mod('hero_call', ''))):
                        ?>
                        <h1 class="hero-banner-call-in fs-4"><?= get_theme_mod('hero_call', '') ?></h1>
                        <?php
                            endif;

                            if (! empty(get_theme_mod('hero_title', ''))):
                        ?>
                        <h2 class="hero-banner-title fs-1 mt-4"><?= get_theme_mod('hero_title', '') ?></h2>

                        <?php
                            endif;

                            if (! empty(get_theme_mod('hero_cta_text', ''))):
                        ?>
                        <div class="mt-4 pt-3">
                            <a class="btn btn-primary px-4" href="<?= get_theme_mod('hero_cta_url', '') ?>"><?= get_theme_mod('hero_cta_text', '') ?></a>
                        </div>
                        <?php
                            endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
