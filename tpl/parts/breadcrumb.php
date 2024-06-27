<?php

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;


$cnt = [
    'current' => in_the_loop() ? get_the_title() : '',
    'items' => [
        '<i class="icon feather icon-home"></i> Home' => home_url(),
    ],
];
?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <nav class="mt-2" aria-label="breadcrumb">
                <ol class="breadcrumb">
                <?php
                    foreach ($cnt['items'] as $title => $url):
                ?>
                    <li class="breadcrumb-item"><a href="<?= $url ?>"><?= $title ?></a></li>
                <?php
                    endforeach;
                ?>
                    <li class="breadcrumb-item active" aria-current="page"><?= $cnt['current'] ?></li>
                </ol>
            </nav>
        </div>

        <div class="col-12">
            <h1 class="display-6"><?= $cnt['current'] ?></h1>
        </div>
    </div>
</div>
