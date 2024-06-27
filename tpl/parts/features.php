<?php

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;

?>
<section class="py-5" style="background-color: #fff;">
    <div class="container py-3 pt-5">
        <div class="row justify-content-center">
            <div class="col-10 col-md-9 col-lg-7">
                <h2 class="text-center mb-4 pb-3">Discover the Features That Make us Stand Out</h2>
            </div>
        </div>
        <div class="row">
<?php
foreach ([
    [
        'icon' => 'zap',
        'text' => 'Accelerated Learning',
    ], [
        'icon' => 'umbrella',
        'text' => 'Expert Guidance',
    ], [
        'icon' => 'video',
        'text' => 'Live Classes',
    ], [
        'icon' => 'users',
        'text' => 'Networking Opportunities',
    ],
] as $_f) {
?>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 text-center my-4">
                    <div class="card-body">
                        <i class="icon feather icon-<?php echo $_f['icon']; ?> text-primary" style="font-size: 38px;"></i>

                        <div class="h5 mt-4"><?php echo $_f['text']; ?></div>
                    </div>
                </div>
            </div>
<?php
}
?>
        </div>
    </div>
</section>
