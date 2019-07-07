$(document).ready(function() {
    $('.chat').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        //console.log($(this).data('id'));

        $.ajax({
            method: "GET",
            url:"getMessage.php?ID=" + $(this).data('id'),
        })
        .done(function(result){
            console.log(result);
            container.html(result);
        });

    });

	$('.friend_delete').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        //On récupère l'ID de l'ami
        let friend = $(this).data('friend');
        let position = $(window).scrollTop();
        let size = $(window).height();
        let name = $(this).parent().children().eq(1).html();

        $(document.body).css('overflow','hidden');
        $(document.body).append('<div class="friend_handler"><div class="friend_handler_container"><div class="container"><div class="row"><div class="col"><p>Êtes-vous sûr de vouloir supprimer <b class="capitalize">'+ name +'</b> ?</p><div class="friend_handler_button_container"><button id="friend_deleted" class="button -color -blue -right">Oui</button><button id="friend_saved" class="button -color -blue">Non</button><div></div></div></div></div></div>')
        $('.friend_handler').css('top',position);

        let position_popup = (size / 2) - (($('.friend_handler_container').height() + 40) / 2);
        $('.friend_handler_container').css('top',position_popup);
        $('.friend_handler_container').animate({"opacity": 1}, "normal");

        $('#friend_saved').click(function(){
            $(this).parent().parent().parent().parent().parent().parent().remove();
            $(document.body).css('overflow','scroll');
        });

        $('#friend_deleted').click(function(){

            $.ajax({
				method: "GET",
				data:{friend:friend},
				url:"src/php/deleteFriend.php",
			})
			.done(function(result){
                let divToDelete;
                $('.friend_widget').each(function(){
                    if($(this).children().eq(2).data('friend') === friend){
                        divToDelete = $(this);
                    }
                });

                $('#friend_deleted').parent().parent().parent().parent().parent().parent().animate({"opacity": 0}, "slow");
                setTimeout(function(){
                    $('#friend_deleted').parent().parent().parent().parent().parent().parent().remove();
                },1000)
                $(document.body).css('overflow','scroll');

                divToDelete.remove();

                //update friend number
                $('.friend_list').children().children().children().children().eq(0).html('Mes amis ('+($('.friend_list').children().children().children().children().eq(0).html().split('(')[1].split(')')[0] - 1)+')');

            });
        });
    });

    // CREATE YES BUTTON
    $("#accept").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        var user = $(this).data("user");

        $.ajax({
            method: "GET",
            data:{ID:user},
            url:"src/php/accept_friend.php",
        })
        .done(function(result){
            document.location.reload(true);
        });
    });

    // CREAT NO BUTTON
    $("#refuse").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        var user = $(this).data("user");

        $.ajax({
            method: "GET",
            data:{ID:user},
            url:"src/php/refuse_friend.php",
        })
        .done(function(result){
            document.location.reload(true);
        });
    });
});