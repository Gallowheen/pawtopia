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

    $(".more__friend button").click(function(e){

        var user = $(this).data("id");
        $.ajax({
            method: "GET",
            url:"friends.php",
            data: {ID:user}
        })
        .done(function(result) {
            container.html(result);
        });
    });

    $('#add_friend').click(function(){
        let user = $(this).data("id");
        $.ajax({
            method: "GET",
            data:{ID:user},
            url:"src/php/add_friend.php",
        })
        .done(function(result){
            $('.avatar__container #friend__button').remove();
            let button = '<div id="friend__button"><a class="friend__link" href="getMessage.php?ID='+user+'"><i class="icon friend__message -friend icon__friend icon-ic_sms_48px"></i></a><button class="friend__button button -friend"><i class="icon icon__friend icon-ic_check_48px"></i>Envoyé</button></div>';
            $(button).insertBefore($('.avatar__container .container .row .col .avatars'));
            //$('.avatar__container .container .row .col').append('<div id="friend__button"><a class="friend__link" href="getMessage.php?ID='+user+'"><i class="icon friend__message -friend icon__friend icon-ic_sms_48px"></i></a><button class="friend__button button -friend"><i class="icon icon__friend icon-ic_check_48px"></i>Envoyé</button></div>');
        });
    });

    $('#add_dog').click(function(){
        let position = $(window).scrollTop();
        let size = $(window).height();

        $(document).mouseup(function (e){
            if (!$('.dog_handler_container .row').find("*").is(e.target) && !$('.dog_handler_container .row').is(e.target)){
                $('.dog_handler').remove();
                $(document).off();
                $(document.body).css('overflow','scroll');
            }
        });

        $.ajax({
            method: "GET",
            url:"src/php/addDog.php",
        })
        .done(function(result){
            $(document.body).css('overflow','hidden');
            $(document.body).append('<div class="dog_handler"><div class="dog_handler_container -noBg"><div class="container"><div class="row"><div class="col"></div></div></div></div></div>');
            $('.dog_handler').css('top',position);

            $('.dog_handler_container .container .row .col').append(result);

            let position_popup = (size / 2) - (($('.dog_handler_container').height() + 40) / 2);
            $('.dog_handler_container').css('top',position_popup);
            $('.dog_handler_container').animate({"opacity": 1}, "normal");

            $('#addDog').click(function(){
                if ($('.dog_name').last().text() == "Nom" || $('#breed').val() == null){
                    if($('.dog_name').last().text() == "Nom")
                        $('.dog_name').last().addClass('error');
                    if($('#breed').val() == null)
                        $('#breed').addClass("-error");
                }else{
                    var uploadfiles = document.querySelector('#uploadfiles');

                    let name = $('.dog_name').last().text();
                    let breed = $('#breed').val();
                    let age = $('#age').val();
                    let sexe = $('#sexe').val();
                    let image;

                    if (uploadfiles.files.length > 0){
                        image = uploadfiles.files[0].name;
                    }else{
                        image = "none";
                    }

                    $.ajax({
                        method: "GET",
                        url:"src/php/addDog.php",
                        data:{
                            name:name,
                            breed:breed,
                            age:age,
                            sexe:sexe,
                            image:image
                        },
                    })
                    .done(function(result){

                        setTimeout(function(){
                            $('.dog_handler').animate({"opacity": 0}, "normal");
                        }, 750);

                        setTimeout(function(){
                            $('.dog_handler').remove();
                            $(document).off();
                            $(document.body).css('overflow','scroll');
                        }, 1250);

                        document.location.reload(true);

                    });
                }
            });

            $('#uploadfiles').on("change", function(){

                // var uploadfiles = $('#uploadfiles');
                // var files = uploadfiles.files;
                // for(var i=0; i<files.length; i++){
                //     uploadFile(uploadfiles.files[i]);
                // }

                if ($('.dog_name').last().text() != "Nom"){
                    $("#addDog").prop('disabled', true);
                    if (gallery.children.length == 0){
                        var files = this.files;
                        for(var i=0; i<files.length; i++){
                            previewImage(this.files[i]);
                        }

                        var files = uploadfiles.files;
                        var page = "dog";
                        var name = $('.dog_name').last().text();
                        for(var i=0; i<files.length; i++){
                            uploadFile(uploadfiles.files[i],name,page);
                        }
                    }

                    $('.label-file').hide();
                    let position_popup = (size / 2) - (($('.dog_handler_container').height() + 40) / 2);
                    $('.dog_handler_container').css('top',position_popup);
                }else{
                    $('.dog_name').last().addClass('error');
                }
            });
        });
    });

    $('.dog_delete').click(function(){
        //On récupère l'ID du chien
        let dog = $(this).data('dog');
        let position = $(window).scrollTop();
        let size = $(window).height();
        let name = $(this).parent().parent().children().first().html();

        $(document).mouseup(function (e){
            if (!$('.dog_handler_container').find("*").is(e.target) && !$('.dog_handler_container').is(e.target)){
                $('.dog_handler').remove();
                $(document).off();
                $(document.body).css('overflow','scroll');
            }
        });

        $(document.body).css('overflow','hidden');
        $(document.body).append('<div class="dog_handler"><div class="dog_handler_container -small"><div class="container"><div class="row"><div class="col"><p>Êtes-vous sûr de vouloir supprimer <b class="capitalize">'+ name +'</b> ?</p><div class="dog_handler_button_container"><button id="dog_accepted" class="button -color -blue -right">Oui</button><button id="dog_denied" class="button -color -blue">Non</button><div></div></div></div></div></div>')
        $('.dog_handler').css('top',position);

        let position_popup = (size / 2) - (($('.dog_handler_container').height() + 40) / 2);
        $('.dog_handler_container').css('top',position_popup);
        $('.dog_handler_container').animate({"opacity": 1}, "normal");

        $('#dog_denied').click(function(){
            $(this).parent().parent().parent().parent().parent().parent().remove();
            $(document.body).css('overflow','scroll');
        });

        $('#dog_accepted').click(function(){

            $.ajax({
                method: "GET",
                data:{dog:dog},
                url:"src/php/deleteDog.php",
            })
            .done(function(result){
                let divToDelete;
                $('.dog_card').each(function(){
                    if($(this).children().eq(1).children().eq(0).data('dog') == dog){
                    divToDelete = $(this);
                    }
                });

                $('#dog_accepted').parent().parent().parent().parent().parent().parent().animate({"opacity": 0}, "slow");
                setTimeout(function(){
                    $('#dog_accepted').parent().parent().parent().parent().parent().parent().remove();
                },1000)
                $(document.body).css('overflow','scroll');

                divToDelete.remove();
                initSwipe();

                //update animal number
                $('.my_pet__container').children().children().children().children().eq(0).html('Mes animaux ('+($('.my_pet__container').children().children().children().children().eq(0).html().split('(')[1].split(')')[0] - 1)+')');

            });
        });
    });

});
