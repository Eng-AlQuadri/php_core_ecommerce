$(function () {

    'use strict';

    // Dashboard

    $('.toggle-info').click(function () {

        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(500);

        if ($(this).hasClass('selected')) {

            $(this).html('<i class = "fa fa-plus fa-lg"></i>');

        } else {

            $(this).html('<i class = "fa fa-minus fa-lg"></i>');

        }
    })

    // Hide Placeholder On Form Focus

    $('[placeholder]').focus(function () {

        $(this).attr('data-text', $(this).attr('placeholder'));

        $(this).attr('placeholder', '');

    }).blur(function () {

        $(this).attr('placeholder', $(this).attr('data-text'));
    });


    // Convert Password Field To Text Field On Hover

    var passField = $('.password');

    $('.show-pass').hover(function () {

        passField.attr('type', 'text');

    }, function () {

        passField.attr('type', 'password');

    });

    // Confirmation Message On Button

    $('.confirm').click(function () {

        return confirm("Are You Sure?");
    });


});




