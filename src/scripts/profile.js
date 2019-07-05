$(document).ready(function() {

    initSwipe();

    $(".edit-profile").click(function(e){
        $.ajax({
            method: "POST",
            url:"src/php/edit_profile_view"
        })
        .done(function(result) {
            $(".information_editable").html(result);

            $('#uploadfiles').on("change", function(){
                $("#update").prop('disabled', true);
                // var uploadfiles = $('#uploadfiles');
                // var files = uploadfiles.files;
                // for(var i=0; i<files.length; i++){
                //     uploadFile(uploadfiles.files[i]);
                // }

                // if(this.files[0].size > 2000000){
                //     $('.error').html("Image trop grande");
                // }else{
                    //console.log(gallery);
                    if (gallery.children.length <= 1){
                        var files = this.files;
                        for(var i=0; i<files.length; i++){
                            previewImage(this.files[i]);
                        }
                    }

                    var uploadfiles = document.querySelector('#uploadfiles');
                    var page = "user";
                    var files = uploadfiles.files;

                    for(var i=0; i<files.length; i++){
                        uploadFile(uploadfiles.files[i],name,page);
                    }

                    $('.label-file').hide();
                    $('.error').html('');
                // }
            });

            $("#update").click(function(e){

                var uploadfiles = document.querySelector('#uploadfiles');

                let bio = $('#bio').val();
                let walk = $('#walk').val();
                let town = $('#town').val();
                let image;

                if (uploadfiles.files.length > 0){
                    image = uploadfiles.files[0].name;
                }else{
                    image = "none";
                }

                $.ajax({
                    method: "GET",
                    url:"src/php/updateProfile.php",
                    data:{
                        bio:bio,
                        town:town,
                        walk:walk,
                        image:image
                    },
                })
                .done(function(result){

                    //console.log(result);

                    // setTimeout(function(){
                    document.location.reload(true);
                    // },2000);
                });
            });
        });
    });

    $(".view").click(function(e){
        e.preventDefault();
        e.stopPropagation();

        var user = $(this).data("id");

        $.ajax({
            method: "GET",
            url:"profile.php",
            data: {ID:user}
        })
        .done(function(result) {
            container.html(result);
        });
    });

});

function initSwipe(){
    //var x = nombre de swipe
    var x = 0;
    //var maxX = max swap
    var maxX = $(".dog_card_container").children().length;

    //remove bubble if exist as we can call back the function
    if ($('.bubble').length){
        $(".dog_card_bubble_container").remove();
    }

    if ($(".dog_card_container").children().length > 1){
        if($('body').is('.members'))
            $(".my_pet__container .container--full .row .col").append('<div class="dog_card_bubble_container"></div>');
        else
            $(".my_pet__container .container .row .col").append('<div class="dog_card_bubble_container"></div>');

        $(".dog_card_container").children().each(function(){
            $('.dog_card_bubble_container').append('<div class="bubble"></div>');
        });

        $(".bubble").first().addClass('-active');
    }


    $(".dog_card_container").swipe({
        swipe:function(event, direction, distance, duration, allowPageScroll){

            $(".bubble").each(function(){
            if($(this).hasClass('-active')){
                $(this).removeClass('-active');
            }
            });

            if (direction == "left" && x < maxX - 1){
                x += 1;
            }
            if (direction == "right"  && x > 0){
                x -= 1;
            }
            if (direction == "up" || direction == "down"){
                $(this).swipe({allowPageScroll:"auto"});
            }

            $(this).children().each(function(){
                $(this).animate({"right": ($('.dog_card_container').width() * x) + (16*x) +'px'}, "normal");
            });

            $(".bubble:eq("+x+")").addClass('-active');
        },
        threshold:100
    });
}