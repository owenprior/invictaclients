// JavaScript Document

jQuery(function($) {
    $(window).scroll(function() {
        var yPos = ($(window).scrollTop());
        //if (yPos > 0) { // show sticky menu after screen has scrolled down 200px from the top
            $(".nav-primary").fadeIn(0); // change value to display sticky menu more or less faster
        //} else {
            $(".nav-primary").fadeOut(0);
        }
    });
});