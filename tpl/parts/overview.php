<?php

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;

$cnt = [
    'img' => get_theme_mod('ovv_img', ''),
    'h'   => get_theme_mod('ovv_h', ''),
    'sum' => get_theme_mod('ovv_sum', ''),
];


if ((! empty($cnt['h'])) && (! empty($cnt['sum']))):
?>
<section class="section-overview">
    <div class="container-fluid g-0">
        <div class="row g-0 align-items-center">
            <div class="col-md-6 col-lg-6 align-self-stretch section-overview-img" style="background-image: url('<?= $cnt['img'] ?>');">
                <div class="section-overview-img-placeholder"></div>
            </div>
            <div class="col-md-6 col-lg-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="py-5 px-md-5">
                                <h2 class="pt-3"><?= $cnt['h'] ?></h2>

                                <div class="h5 mt-4 pb-3"><?= $cnt['sum'] ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-1"></div>
        </div>
    </div>
</section>
<?php
endif;
