$(function () {
    Application.init ();
});

var Application = function () {

    return { init: init };

    function init () {
        enableBackToTop ();
    }

    function enableBackToTop () {
        var backToTop = $('<a>', { id: 'back-to-top', href: '#top' });
        var icon = $('<i>', { class: 'icon-chevron-up' });

        backToTop.appendTo('body');
        icon.appendTo(backToTop);
        backToTop.hide();

        $(window).scroll(function() {
            if ($(this).scrollTop() > 150) {
                backToTop.show();
            } else {
                backToTop.hide();
            }
        });

        backToTop.click (function(e) {
            e.preventDefault ();

            $('body, html').animate({
                scrollTop: 0
            }, 600);
        });
    }
}();

var offset = 60, navCloned = false;
$(window).scroll(function () {
    if ($(window).scrollTop() > offset && !navCloned) {
        navCloned = true;
        $('ul.mainnav').clone().prependTo('.navbar .container .nav-collapse').attr('id', 'clonedNav').show();
    } else if ( $(window).scrollTop() > offset ) {
        $('#clonedNav').show();
    } else if ( $(window).scrollTop() < offset ) {
        $('#clonedNav').hide();
    }
});

