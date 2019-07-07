$(document).ready(function() {
    $('.get_to_walk').click(function(){
        $.ajax({
            method: "GET",
            url:"walk_detail.php",
            data:{ID:$(this).data('id')}
        })
        .done(function(result){
            container.html(result);
            $('.h1').text('Balades');
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