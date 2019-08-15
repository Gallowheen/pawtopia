$(document).ready(function() {


    $('body').css('overflow-y','scroll');

    $(".review__icon").click(function(){
        console.log($(this).index());

        //hard reset so the user can still change his note
        for (i = 0; i < 5; i++){
            $('.review__icon').eq(i).addClass('-empty');
            $('.review__icon').eq(i).removeClass('-selected');
        }

        for (i = 0; i <= $(this).index(); i++){
          
                console.log(250 * i);
                console.log(i);
                $('.review__icon').eq(i).delay(250 * i).removeClass('-empty');
                $('.review__icon').eq(i).delay(250 * i).addClass('-selected');

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
