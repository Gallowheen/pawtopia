$(document).ready(function() {
    $('.get_to_walk').click(function(){
        window.location = "walk_detail?ID="+$(this).data('id');
    });

    $('.content_container .container .row .col .user_walk').css({
        "max-height": "120px",
        "overflow" : "scroll"
    });
})