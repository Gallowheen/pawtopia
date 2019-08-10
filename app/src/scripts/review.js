$(document).ready(function() {

    $('body').css('overflow-y','scroll');

    $("#update").click(function(e){
        e.preventDefault();
        e.stopPropagation();

        var user = $(this).data("id");
        var name = $(this).data("name");
        var note = 5; // Changer ça pour prendre en compte les étoiles selectionnées
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
