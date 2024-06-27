<?php

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;

?>
<section class="my-5 pt-4 pb-5">
    <div class="accordion" id="faqList">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h2 class="text-center mb-5">Frequently Asked Questions</h2>
                </div>
            </div>
            <div class="row justify-content-center">
<?php
$query = new WP_Query([
    'post_type'           => 'faq',
    'post_status'         => 'publish',
    'has_password'        => false,
    'ignore_sticky_posts' => false,
    'order'               => 'DESC',
    'orderby'             => 'date',
    'nopaging'            => true,
    'posts_per_page'      => 6,
    'paged'               => 1,
]);

while ($query->have_posts()) {
    $query->the_post();
?>
                <!-- <div class="col-md-10 col-lg-8 col-xl-7"> -->
                <div class="col-lg-8">
                    <div class="accordion-item mt-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-item-<?php echo get_the_ID(); ?>" aria-expanded="false" aria-controls="faq-item-<?php echo get_the_ID(); ?>"><span class="h5"><?php echo get_the_title(); ?></span></button>
                        </h2>
                        <div id="faq-item-<?php echo get_the_ID(); ?>" class="accordion-collapse collapse" data-bs-parent="#faqList">
                            <div class="accordion-body"><?php echo get_the_content() ?></div>
                        </div>
                    </div>
                </div>
<?php
}
wp_reset_postdata();

?>
            </div>
        </div>
    </div>
</section>
