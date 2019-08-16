$(document).ready(function(){
    $('.notification__friend__icon').click(function(){
        $.ajax({
            method: "GET",
            url:"friends.php"
        })
        .done(function(result){
            var newtitle = "Mes amis";
            slidePage(result,'right');
            $(window).scrollTop(0);
            setTitle(newtitle);
            setReturnButton("notifications", {}, title);
        });
    });
});