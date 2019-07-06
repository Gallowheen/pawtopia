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
})