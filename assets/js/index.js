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

    $(".form-container-password").each(function (i, e)
    {
        var ctrl    = $(this).find('.form-control');
        var tgl     = $(this).find('.form-container-password-toggle');
        var tglIcon = tgl.find('.icon');

        tgl.on('click', function (e)
        {
            if (ctrl.attr('type') == 'password') {
                ctrl.attr('type', 'text');
                tglIcon.removeClass('icon-eye').addClass('icon-eye-off');
            } else {
                ctrl.attr('type', 'password');
                tglIcon.removeClass('icon-eye-off').addClass('icon-eye');
            }
        });
    });

    $('body').removeClass(window.nowpt.splashscreenClass ?? 'splashscreen');
});
