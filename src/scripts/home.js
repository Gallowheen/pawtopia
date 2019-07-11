$(document).ready(function() {
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
})