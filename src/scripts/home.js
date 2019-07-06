$(document).ready(function() {
    $('.get_to_walk').click(function(){
        window.location = "walk_detail?ID="+$(this).data('id');
    });
})