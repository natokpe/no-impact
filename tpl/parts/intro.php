<?php

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;

?>
<section class="py-5" style="background-color: #fff;">
    <div class="container pt-5 pb-3">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-7 col-lg-6">
                <div class="overview-img keep-aspect-ratio-60 my-3" style="background-color: #eee; background-image: url('<?= get_theme_mod('intro_img', '') ?>');"></div>
            </div>

            <div class="col-12 col-md-11 col-lg-6">
                <div class="py-3 text-center text-lg-start">
                    <h2 class=""><?= get_theme_mod('intro_heading', '') ?></h2>

                    <div class="h5 mt-4"><?= get_theme_mod('intro_sum', '') ?></div>

                    <div class="mt-4">
                        <a class="btn btn-primary px-4" href="<?= get_theme_mod('intro_cta_url', '') ?>"><?= get_theme_mod('intro_cta_text', '') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
