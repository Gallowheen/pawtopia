$('.icon__down').click(function(){
    //$(window).scrollTop(0);
    $('html, body').animate({
        scrollTop: $(".information").offset().top
    }, 1000);
});

$(window).on('scroll' , function(){
    scroll_pos = $(window).scrollTop() + $(window).height();
    element_pos = $('.layers').offset().top + $('.layers').height() ;
    if (scroll_pos > element_pos) {
        console.log('lol');
        $('.shadow__layer').addClass('shadow__float');
        $('.map__layer').addClass('map__float');
    };

})