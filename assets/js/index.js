import $     from 'jquery';
import 'bootstrap/dist/js/bootstrap.js';

$(document).ready(function() {
    "use strict";

    $('.drawernav-toggle').each(function (i, el) {
        $(this).on('click', function (e) {
            $(this).toggleClass('active');
        });
    });

    $(window).on('scroll mousemove focus', function(e) {
        let $scr = $(window).scrollTop();

        if ($scr > 0) {
            $('body').addClass('page-scroll');
        } else {
            $('body').removeClass('page-scroll');
        }

        if ($scr >= 100) {
            $('body').addClass('page-scroll-100');
        } else {
            $('body').removeClass('page-scroll-100');
        }
    });

    $('body').removeClass(window.nowpt.splashscreenClass ?? 'splashscreen');
});
