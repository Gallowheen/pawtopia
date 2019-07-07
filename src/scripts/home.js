$(document).ready(function() {
    $('.get_to_walk').click(function(){
        console.log( $(this).parent().parent().children().eq(0));
        var name = $(this).parent().parent().children().eq(0).children().eq(0).text();
        $.ajax({
            method: "GET",
            url:"walk_detail.php",
            data:{ID:$(this).data('id')}
        })
        .done(function(result){
            container.html(result);
            $('.h1').text(name);
        });
    });

    $('.statuts .icon__friend').click(function(){
        $.ajax({
            url:"friends.php",
        })
        .done(function(result){
            container.html(result);
            $('.h1').text('Amis');
        });
    });

    $('.statuts .icon__message').click(function(){
        $.ajax({
            url:"message.php",
        })
        .done(function(result){
            container.html(result);
            $('.h1').text('Messages');
        });
    });
})