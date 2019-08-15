$('.icon__down').click(function(){
    //$(window).scrollTop(0);
    $('html, body').animate({
        scrollTop: $(".information").offset().top
    }, 1000);
});

$(window).on('scroll' , function(){
    scroll_pos = $(window).scrollTop() + $(window).height();
    element_pos = $('.layers').offset().top + ($('.layers').height() / 2);
    if (scroll_pos > element_pos) {
        $('.shadow__layer').addClass('shadow__float');
        $('.map__layer').addClass('map__float');
    }else{
        $('.shadow__layer').removeClass('shadow__float');
        $('.map__layer').removeClass('map__float');
    }
})

$('.store').click(function(){
    window.location.href = "app/index.php";
});