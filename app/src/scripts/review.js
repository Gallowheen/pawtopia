$(document).ready(function() {


    $('body').css('overflow-y','scroll');

    $(".review__icon").click(function(){
        //hard reset so the user can still change his note
        $('.review__icon').addClass('-empty');
        $('.review__icon').removeClass('-selected');

        var delay = 250;
        for (i = 0; i <= $(this).index(); i++){
            setTimeout(selectStar,delay*i, i);
        }

    });

    $("#update").click(function(e){
        e.preventDefault();
        e.stopPropagation();

        var user = $(this).data("id");
        var name = $(this).data("name");
        var note = 0;
        var message = $("#review_message").val();

        $.ajax({
            method: "GET",
            url:"./src/php/add_review.php",
            data: {
                ID:user,
                NOTE:note,
                MESSAGE:message
            }
        })
        .done(function(result) {
            slidePage(result, 'left');
            var title = $(".header__title").html();
            setReturnButton("profile", {}, title);
            setTitle('Profil  de '+name);
        });
    });

});

function selectStar(nombre) {
    $('.review__icon').eq(nombre).removeClass('-empty');
    $('.review__icon').eq(nombre).addClass('-selected');
}
