$(document).ready(function() {
    $('.get_to_walk').click(function(){
        $.ajax({
            method: "GET",
            url:"walk_detail.php",
            data:{ID:$(this).data('id')}
        })
        .done(function(result){
            container.html(result);
        });
    });

    $('.icon__friend').click(function(){
        $.ajax({
            url:"friends.php",
        })
        .done(function(result){
            container.html(result);
        });
    });
})