<?php
/**
 * Template Name: Contact
 */

declare(strict_types = 1);

use NatOkpe\Wp\Theme\Impact\Theme;

while (have_posts()): the_post();

get_header();

?><div class="content-frame">
    <header class="content-frame-header">
        <?php get_template_part('tpl/parts/navbar'); ?>
        <?php get_template_part('tpl/parts/breadcrumb'); ?>

    </header>

    <main class="content-frame-body my-5 pb-5">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-sm-9 col-md-7 col-lg-5 col-xl-5">
                    <div class="card border border-0 px-4 pb-3">
                        <div class="card-body pt-4">
                            <h3 class="card-title text-center mb-4">Leave us a message</h3>

                            <form method="POST" action="<?= Theme::urlNow() ?>">
                                <div class="mb-3">
                                    <label for="user-name" class="form-label">Name</label>
                                    <input type="text"
                                        class="form-control"
                                        id="user-name"
                                        name="name"
                                        placeholder="Your name" />
                                </div>

                                <div class="mb-3">
                                    <label for="user-email" class="form-label">Email address</label>
                                    <input type="email"
                                        class="form-control"
                                        id="user-email"
                                        name="email"
                                        placeholder="Your email"
                                    />
                                </div>

                                <div class="mb-3">
                                    <label for="user-subject" class="form-label">Subject</label>
                                    <input type="text"
                                        class="form-control"
                                        id="user-subject"
                                        name="subject"
                                        placeholder="Message subject" />
                                </div>

                                <div class="mb-3">
                                    <label for="user-message" class="form-label">Message</label>
                                    <textarea type="text"
                                        class="form-control"
                                        id="user-message"
                                        name="message"
                                        rows="5" 
                                        placeholder="Your message"></textarea>
                                </div>

                                <?php wp_nonce_field('contact_page_' . get_the_ID()); ?>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Send</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <footer class="content-frame-footer">
        <?php get_template_part('tpl/parts/footer'); ?>
    </footer>
</div>
<?php

get_footer();

endwhile;
