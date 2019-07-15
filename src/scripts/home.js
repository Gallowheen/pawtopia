$(document).ready(function() {

    $('body').css('overflow-y','scroll');

    if (localStorage.getItem("layout") !== null) {
        if(localStorage.getItem("layout") == "carousel"){
            changeToCarousel();
        }else{
            changeToList();
        }

        $('.icon__action').each(function(){

            if($(this).hasClass(localStorage.getItem("layout")))
                $(this).addClass('-selected');
            else{
                $(this).removeClass('-selected');
            }
        })
    }

    $('.get_to_walk').click(function(){
        var title = $(".header__title").html();
        var newtitle = $(this).parent().parent().find('.name__container span').html();
        $.ajax({
            method: "GET",
            url:"walk_detail.php",
            data:{ID:$(this).data('id')}
        })
        .done(function(result){
            container.html(result);
            $(window).scrollTop(0);
            $('.h1').text(newtitle);
            setReturnButton("home", {}, title);
        });
    });

    $('.statuts .icon__friend').click(function(){
        var title = $(".header__title").html();
        $.ajax({
            url:"friends.php",
        })
        .done(function(result){
            container.html(result);
            $('.h1').text('Amis');
            setReturnButton("home", {}, title);
        });
    });

    $('.statuts .icon__message').click(function(){
        var title = $(".header__title").html();
        $.ajax({
            url:"message.php",
        })
        .done(function(result){
            container.html(result);
            $('.h1').text('Messages');
            setReturnButton("home", {}, title);
        });
    });

    $(".logout").click(function(){
        if(confirm('Voulez-vous vraiment vous déconnecter ?'))
        {
            $.ajax({
                url:"src/php/logout.php",
            })
            .done(function(result){
                if(result == 1)
                    window.location.href = "index.php";
            });
        }
    });

    //update image

    imgBank = ['src/assets/img/ressources/walk_1.jpg','src/assets/img/ressources/walk_2.jpg','src/assets/img/ressources/walk_3.jpg','src/assets/img/ressources/walk_4.jpg','src/assets/img/ressources/walk_5.jpg','src/assets/img/ressources/walk_6.jpg'];

    $('.walk__background').each(function(){
        let random = Math.floor(Math.random() * imgBank.length);
        console.log(imgBank[random]);
        $(this).css('background','url("'+imgBank[random]+'")');
        $(this).css('background-size','100% 100%');
    });

    //change layout

    $('.icon__action').click(function(){

        $('.icon__action').each(function(){
            $(this).removeClass('-selected');
        })
        $(this).addClass('-selected');

        if( $(this).hasClass('carousel')){
            changeToCarousel();
            localStorage.setItem('layout', 'carousel');
        }else{
            changeToList();
            localStorage.setItem('layout', 'list');
        }
    });

    function changeToList(){

        $('.user_walk.-home').addClass('-list');
        $('.walk__container').addClass('-list');
        $('.walk__card').addClass('-list');
        $('.walk__background').addClass('-list');
        $('.walk__contrast').addClass('-list');
        $('.name__container').addClass('-list');
        $('.address__container').addClass('-list');
        $('.date__container').addClass('-list');
    }

    function changeToCarousel(){

        if ($('.walk__card').length == 1){
            $('.walk__card').addClass('-alone');
            $('.walk__background').addClass('-alone');
        } 
        $('.user_walk.-home').removeClass('-list');
        $('.walk__container').removeClass('-list');
        $('.walk__card').removeClass('-list');
        $('.walk__background').removeClass('-list');
        $('.walk__contrast').removeClass('-list');
        $('.name__container').removeClass('-list');
        $('.address__container').removeClass('-list');
        $('.date__container').removeClass('-list');
    }
})