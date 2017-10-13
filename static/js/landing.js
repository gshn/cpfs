$(window).scroll(function(e) {
    var st = $(this).scrollTop();
    var h = $(this).height();
    var opacity = 1 - (st / h) * 2;
    if (opacity >= 0) {
        $('#slogan').css({ 'opacity': opacity });
    }
    if (st > h / 2) {
        $('#slogan').hide();
        $('#wrap').removeClass('top');
    } else if (st < h / 2) {
        $('#slogan').show();
        $('#wrap').addClass('top');
    }
});
$('.lets-start').click(function() {
    move_scroll('#container');
});

var move_scroll = function (id) {
    $(window).on("mousewheel.disableScroll DOMMouseScroll.disableScroll touchmove.disableScroll", function (e) {
        e.preventDefault();
        return;
    });

    var top = $(id).offset().top + 'px';
    $('html, body').stop(true).animate({ scrollTop: top }, {
        duration: 1500, easing: 'easeInOutExpo', complete: function () {
            $(window).off(".disableScroll");
        }
    });
}
$('.owl-carousel').owlCarousel({
    loop:true,
    margin:20,
    responsiveClass:true,
    responsive:{
        0:{
            items:2,
            nav:true
        },
        600:{
            items:3,
            nav:false
        },
        1000:{
            items:5,
            nav:true,
            loop:false
        }
    }
});
$('.gnb-trigger').click(function() {
    $('#wrap').toggleClass('gnb-on');
});
$('#gnb .gnb-list>li>a').click(function() {
    $('#wrap').removeClass('gnb-on');
});
