$(document).ready(function () {
    $('.nav-tabs > li a[title]');

    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        var $target = $(e.target);
        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function (e) {
        var $active = $('.nav-tabs li.active');
        $active.next().removeClass('disabled');
        nextTab($active);

    });
    $(".prev-step").click(function (e) {
        var $active = $('.nav-tabs li.active');
        prevTab($active);
    });
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}

// This Function Will display the Welcome Message to new Members and then will set a cookie
// if ($.cookie('welcome') != 'seen') {
//     var date = new Date();
//     date.
//     setTime(date.getTime() + (300 * 1000));
//     $.cookie('welcome', 'seen', {expires: date, path: '/'});
//     $(function () {
//         $(document
//         ).ready(function () {
//             $("[data-popup-open]").trigger('click');
//         });
//
//         //----- OPEN
//         $('[data-popup-open]').on('click', function (e) {
//             var targeted_popup_class = jQuery(this).attr('data-popup-open');
//             $('[data-popup="' + targeted_popup_class + '"]').delay(2000).fadeIn(400);
//             e.preventDefault();
//         });
//
//         //----- CLOSE
//         $('[data-popup-close]').on('click', function (e) {
//             var targeted_popup_class = jQuery(this).attr('data-popup-close');
//             $('[data-popup="' + targeted_popup_class + '"]').fadeOut(50);
//             e.preventDefault();
//         });
//     });
// };


// This function is used for the display on the events page, to switch between grid & List view
$(document).ready(function () {
    $('#list').click(function (event) {
        event.preventDefault();
        $('#products .item').addClass('list-group-item');
        $('.event-body').css( "min-height", "170px" );
    });

    $('#grid').click(function (event) {
        event.preventDefault();
        $('#products .item').removeClass('list-group-item');
        $('#products .item').addClass('grid-group-item');
        $('.event-body').css( "min-height", "275px" );
    });

    //
    //$('#grid').click(function () {
    //
    //});
    //
    //$('#list').click(function () {
    //
    //});





    $('#list').addClass('active');
    $('#list').click(function () {
        $('#grid').removeClass('active');
        $('#list').addClass('active');
    });

    $('#grid').click(function () {
        $('#grid').addClass('active');
        $('#list').removeClass('active');
    });


    $(document).ready(function () {
        $("[data-popup-open-event]").trigger('click');
    });

    //----- OPEN
    $('[data-popup-open-event]').on('click', function (e) {
        var targeted_popup_class = jQuery(this).attr('data-popup-open-event');
        $('[data-popup-event="' + targeted_popup_class + '"]').delay(1000).fadeIn(400);
        e.preventDefault();
    });

    //----- CLOSE
    $('[data-popup-close-event]').on('click', function (e) {
        var targeted_popup_class = jQuery(this).attr('data-popup-close-event');
        $('[data-popup-event="' + targeted_popup_class + '"]').fadeOut(50);
        e.preventDefault();
    });
})