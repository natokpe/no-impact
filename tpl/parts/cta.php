<?php

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;

$cnt = [
    'sum'     => get_theme_mod('no_impact_home_cta_sum', ''),
    'btn_txt' => get_theme_mod('no_impact_home_cta_btn_txt', ''),
    'btn_url' => get_theme_mod('no_impact_home_cta_btn_url', ''),
];

if ((! empty($cnt['sum'])) && (! empty($cnt['btn_txt']))):
?>
<section class="py-5" style="background-color: #fff;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="text-center text-md-start"><?= $cnt['sum'] ?></h2>
            </div>
            <div class="col-md-4">
                <div class="d-grid">
                    <a class="btn btn-primary my-3" href="<?= $cnt['btn_url'] ?>"><?= $cnt['btn_txt'] ?></a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
endif;

