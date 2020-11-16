$(document).ready(function() {

    var note = 0;

    $('body').css('overflow-y','scroll');

    $(".review__icon").click(function(){
        //hard reset so the user can still change his note

        note = $(this).index() + 1;

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
        var message = $("#review_message").val();

        if (note != 0){
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
                if (result == "failed"){
    
                    let span = "<span class='error -review'>Vous avez déjà review cette personne</span>";
    
                    if($('.edit__profile').children().eq(0).hasClass('error')){
                        $('.edit__profile').children().eq(0).remove();
                    }
                    
                    $(span).insertBefore( $( ".information_group" )[0] );
                    
                    setTimeout(function(){
    
                    },2000);
                }else{
                    slidePage(result, 'left');
                    var title = $(".header__title").html();
                    setReturnButton("profile", {}, title);
                    setTitle('Profil  de '+name);

                    $([document.documentElement, document.body]).animate({
                        scrollTop: $(".review__container").offset().top - 150
                    }, 250);
                }
            });
        }else{
            let span = "<span class='error -review'>N'oubliez pas votre note !</span>";

            if($('.edit__profile').children().eq(0).hasClass('error')){
                $('.edit__profile').children().eq(0).remove();
            }

            $(span).insertBefore( $( ".information_group" )[0] );    
        }

       
    });

});

function selectStar(nombre) {
    $('.review__icon').eq(nombre).removeClass('-empty');
    $('.review__icon').eq(nombre).addClass('-selected');
}
