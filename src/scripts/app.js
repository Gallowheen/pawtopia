var latUser;
var lonUser;
var container;

$(document).ready(function(){
    container = $(".content_container");
    let city;
    let road;
    let postcode;

    //GET GEOLOC
    var options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    };

    function success(pos) {
        var crd = pos.coords;

        latUser = crd.latitude;
        lonUser = crd.longitude;
    }

    function error(err) {
        console.warn(`ERREUR (${err.code}): ${err.message}`);
    }

    navigator.geolocation.getCurrentPosition(success, error, options);

    let lat;
    let lon;

    $(".nav_button_group").click(function(e) {
        $(".-active").removeClass('-active');
        $(this).children().eq(0).addClass('-active');
        $(this).children().eq(1).addClass('-active');
        $.ajax({
            method: "GET",
            url:$(this).data('url')+".php",
        })
        .done(function(result){
            container.html(result);
        });
    });

    $("#info").on('keyup', function(){
        console.log('//nominatim.openstreetmap.org/search?format=json&q='+$(this).val());
        $.get(location.protocol + '//nominatim.openstreetmap.org/search?format=json&q='+$(this).val(), 		function(data){

            for (i = 2; i < $("#info").parent().children().length; i++){
                $("#info").parent().children().eq(i).remove();
            }

            if(data.length) {
                console.log(data);

                if($("#info").hasClass('-error'))
                    $("#info").removeClass('-error');

                for ( i = 0; i < data.length; i++){
                    $("#info").parent().append("<div class='pickadress__container'><div class='pickaddress' style='width: 90%;margin-left: 24px;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;padding: 8px;border: 1px solid #8080804a;background:white;'>"+data[i].display_name+"</div></div>")
                }

                $(".pickaddress").click(function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    //console.log(e);

                    console.log($(this).text());
                    var ville = $(this).text();


                    $('.pickadress__container').remove();
                    $("#info").val(ville);
                });
            }
            else {
                $("#info").addClass('-error');
            }
        });
    });

    var pubnub = new PubNub({
	    subscribeKey: 'sub-c-1ef083d0-dc62-11e8-911d-e217929ad048', // always required
	    publishKey: 'pub-c-07c00e21-e522-43ef-965d-f81e60f7a47f' // only required if publishing
	});

    // CREATE YES BUTTON
    $("#accept").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        var user = $(this).data("user");

        $.ajax({
            method: "GET",
            data:{ID:user},
            url:"src/php/accept_friend.php",
        })
        .done(function(result){
            document.location.reload(true);
        });
    });
    // CREAT NO BUTTON
    $("#refuse").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        var user = $(this).data("user");

        $.ajax({
            method: "GET",
            data:{ID:user},
            url:"src/php/refuse_friend.php",
        })
        .done(function(result){
            document.location.reload(true);
        });
    });

    if($('body').is('.members')){
        $.ajax({
            method: "GET",
            url:"src/php/showcase_member.php",
        })
        .done(function(result){
            //console.log(result);
            let data = JSON.parse(result);
            let member;

            if(data.length == 0){

            }else{
                var x = 0;

                for( let i = 0; i < data.length && x <= 5; i++){
                    //member = '<div data-id="'+data[i].ID+'" class="view friend_widget"><img class="avatar avatar -friendlist" src="'+data[i].AVATAR+'"><span class="friend_name -member">'+data[i].USERNAME+'</span><span class="friend_name -km">A '+data[i].km+' km de vous</span></div>';
                    member = '<button class="button view" data-id="'+data[i].ID+'"><div class="friend_widget -small"><img class="avatar -topFriend" src="'+data[i].AVATAR+'"/><p class="friend__username">'+data[i].USERNAME+'</p></div>';
                    $(".member__filtred .container .row .col .showcase__member").append(member);
                    x++;
                }

                $(".view").click(function(e){
                    e.preventDefault();
                    e.stopPropagation();

                    var user = $(this).data("id");

                    window.location = "profile.php?ID=" + user;
                });
            }
        });
    }

    $(document).on('input', '#slider', function() {
        $('#slider_value').html( $(this).val() + " km" );
    });

    $('#filter').click(function(){
        $(".members__handler").find("input:radio").prop("checked", false);
        $(".label").each(function(){
            if($(this).hasClass('selected'))
                $(this).removeClass('selected')
        });

        $("body").css('overflow','hidden');
        $(".members__handler__container").css('display','block');

        setTimeout(function(){
            $(".members__handler__container").css('transform','translateY(0%)');
        }, 500);

        setTimeout(function(){
            $(".members__handler__container").css('background','#00000024');
        }, 1500);
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
    })

    if($('body').is('.friends')){
        $('.chat').click(function(e){
            e.preventDefault();
            e.stopPropagation();

            //console.log($(this).data('id'));

            window.location = "getMessage.php?ID=" + $(this).data('id');
        });
    }

    $('.friend_delete').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        //On récupère l'ID de l'ami
        let friend = $(this).data('friend');
        let position = $(window).scrollTop();
        let size = $(window).height();
        let name = $(this).parent().children().eq(1).html();

        $(document.body).css('overflow','hidden');
        $(document.body).append('<div class="friend_handler"><div class="friend_handler_container"><div class="container"><div class="row"><div class="col"><p>Êtes-vous sûr de vouloir supprimer <b class="capitalize">'+ name +'</b> ?</p><div class="friend_handler_button_container"><button id="friend_deleted" class="button -color -blue -right">Oui</button><button id="friend_saved" class="button -color -blue">Non</button><div></div></div></div></div></div>')
        $('.friend_handler').css('top',position);

        let position_popup = (size / 2) - (($('.friend_handler_container').height() + 40) / 2);
        $('.friend_handler_container').css('top',position_popup);
        $('.friend_handler_container').animate({"opacity": 1}, "normal");

        $('#friend_saved').click(function(){
            $(this).parent().parent().parent().parent().parent().parent().remove();
            $(document.body).css('overflow','scroll');
        });

        $('#friend_deleted').click(function(){

            $.ajax({
				method: "GET",
				data:{friend:friend},
				url:"src/php/deleteFriend.php",
			})
			.done(function(result){
                let divToDelete;
                $('.friend_widget').each(function(){
                    if($(this).children().eq(2).data('friend') === friend){
                        divToDelete = $(this);
                    }
                });

                $('#friend_deleted').parent().parent().parent().parent().parent().parent().animate({"opacity": 0}, "slow");
                setTimeout(function(){
                    $('#friend_deleted').parent().parent().parent().parent().parent().parent().remove();
                },1000)
                $(document.body).css('overflow','scroll');

                divToDelete.remove();

                //update friend number
                $('.friend_list').children().children().children().children().eq(0).html('Mes amis ('+($('.friend_list').children().children().children().children().eq(0).html().split('(')[1].split(')')[0] - 1)+')');

            });
        });
    })

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

    $("#discover").click(function(e){
        e.preventDefault();
        return window.location.href = "members.php";
    });

    $('.label').click(function(){

        $('.label').each(function(){
            $(this).removeClass('selected');
        })

        if (!$(this).hasClass('selected'))
            $(this).addClass('selected');
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

    // event
    $('.clickme').click(function(){
        $.ajax({
            method: "GET",
            url:"src/php/add_event.php",
        })
        .done(function(result){

        });
    });

    $("#validate").click(function(e){
        e.preventDefault();

        var date = $("#date").val() + " " + $("#time").val();

        let dogSelected = [];
        let x = 0;

        $('.dog_card').each(function(){
            if($(this).hasClass('-active')){
                dogSelected.push($(this).data('id'));
            }
        });

        $.get(location.protocol + '//nominatim.openstreetmap.org/search?format=json&q='+$('#info').val()+'&addressdetails=1', 		function(data){
            console.log(data[0]);
            lat = data[0].lat;
            lon = data[0].lon;

            if (data[0].address.city != undefined){
                city = data[0].address.city;
            }else{
                city = data[0].address.village;
            }
            road = data[0].address.road;
            postcode = data[0].address.postcode;
        });

        setTimeout(function(){
            console.log(lat);
            console.log(lon);
            console.log(city);
            console.log(road);
            console.log(postcode);


            $.get(
                'src/php/add_event.php',
                {
                    ID_OWNER : $("form").data('id'),
                    NAME : $("#walk_name").val(),
                    LOCATION : $("#info").val(),
                    TYPE : $("#walk_type").val(),
                    DATE : date,
                    LENGTH : $("#length").val(),
                    DOG : dogSelected,
                    LAT : lat,
                    LON : lon,
                    CITY : city,
                    ROAD : road,
                    POSTCODE : postcode
                },

                function(data){
                    console.log(data);
                    if(data === 'success'){
                        window.location = "walk.php";
                    }
                },
                'text'
            );
        },500);

    });

    $('#submit__walks').click(function(e){

        e.preventDefault();

        //Si la géoloc ne fonctionne pas ou n'est pas activ
        if (latUser == undefined || lonUser == undefined){
            $.ajax({
                method: "GET",
                url:"src/php/get_lat_long_city.php",
            })
            .done(function(result){

                data = JSON.parse(result);

                latUser  = data.LAT;
                lonUser  = data.LON;
            });
        }

        setTimeout(function(){

            $.ajax({
                method: "GET",
                data:{
                    walk:$('form input[type=radio]:checked').val(),
                    //km : $('#slider').val(),
                    date : $('#date').val(),
                    LAT : latUser,
                    LON : lonUser
                },
                url:"src/php/managewalk.php",
            })
            .done(function(result){

                console.log(result);
                data = JSON.parse(result);
                //console.log(result);

                //map.removeLayer();

                //savoir le jour
                var jour = new Array(7);
                jour[0] = "Lundi";
                jour[1] = "Mardi";
                jour[2] = "Mercredi";
                jour[3] = "Jeudi";
                jour[4] = "Vendredi";
                jour[5] = "Samedi";
                jour[6] = "Dimanche";

                //savoir le mois
                var mois = new Array(12);
                mois[0] = "Janvier";
                mois[1] = "Février";
                mois[2] = "Mars";
                mois[3] = "Avril";
                mois[4] = "Mai";
                mois[5] = "Juin";
                mois[6] = "Juillet";
                mois[7] = "Août";
                mois[8] = "Septembre";
                mois[9] = "Octobre";
                mois[10] = "Novembre";
                mois[11] = "Décembre";

                if($('.walk__container__result'))
                    $('.walk__container__result').remove();

                if($('.tag_container'))
                    $('.tag_container').remove();

                if($('.walk__noresult'))
                    $('.walk__noresult').remove();

                if (data.length >= 1){

                    setTimeout(() => {
                        mymap.invalidateSize();
                    }, 0);
                    layerGroup.clearLayers();

                    $('.content_container .container .row .col').append('<div class="walk__container__result"></div>');

                    $('.walk__container__result').append('<p class="search__result"><b>'+data.length+'</b> résultats correspondant à votre recherche</p>');

                    for( let i = 0; i < data.length; i++){

                        let adress = data[i]['LOCATION'];

                        setTimeout(function(){
                            if (data[i]['WALK'] == "Récréative"){
                                var marker = L.marker([data[i]['LAT'], data[i]['LON']],{icon: bluetopia}).addTo(mymap).on('click', function(){

                                    setTimeout(function(){
                                        $('.get_to_walk').click(function(){
                                            window.location = "walk_detail?ID="+data[i]['ID'];
                                        });
                                    },100);
                                });
                                marker.bindPopup("<b>"+data[i]['NAME']+"</b></br><b>"+data[i]['ROAD']+" "+data[i]['CITY']+"</b></br><button class='button -color -blue -round -top -walk get_to_walk' data-id='10'>En savoir plus</button>");
                                marker.addTo(layerGroup);
                            }
                            if (data[i]['WALK'] == "Sportive"){
                                var marker = L.marker([data[i]['LAT'], data[i]['LON']],{icon: redtopia}).addTo(mymap).on('click', function(){

                                    setTimeout(function(){
                                        $('.get_to_walk').click(function(){
                                            window.location = "walk_detail?ID="+data[i]['ID'];
                                        });
                                    },100);
                                });
                                marker.bindPopup("<b>"+data[i]['NAME']+"</b></br><b>"+data[i]['ROAD']+" "+data[i]['CITY']+"</b></br><button class='button -color -blue -round -top -walk get_to_walk' data-id='10'>En savoir plus</button>");
                                marker.addTo(layerGroup);

                            }
                            if (data[i]['WALK'] == "Découverte"){
                                var marker = L.marker([data[i]['LAT'], data[i]['LON']],{icon: greentopia}).addTo(mymap).on('click', function(){

                                    setTimeout(function(){
                                        $('.get_to_walk').click(function(){
                                            window.location = "walk_detail?ID="+data[i]['ID'];
                                        });
                                    },100);
                                });
                                marker.bindPopup("<b>"+data[i]['NAME']+"</b></br><b>"+data[i]['ROAD']+" "+data[i]['CITY']+"</b></br><button class='button -color -blue -round -top -walk get_to_walk' data-id='10'>En savoir plus</button>");
                                marker.addTo(layerGroup);
                            }
                        },250);

                        let date = new Date(data[i]['DATE_START']);
                        let hourSplit = data[i]['DATE_START'].split(' ')[1].split(':');
                        let hour = hourSplit[0] + ":" + hourSplit[1];
                        let day = jour[date.getDay()];
                        let month = mois[date.getMonth()];
                        let dayNumber = date.getDate();

                        let walk = '<div class="name__container"><span class="">'+data[i]['NAME']+'</span></div><div class="date__container -flex"><span class="">'+ day +" "+dayNumber +" "+ month+" "+ hour+'</span>'+'<span class="walk__name">'+data[i]['LENGTH']+' heures</span><span>'+data[i]['WALK']+'</span></div><div class="town__container"><i class="icon home icon-ic_home_48px"></i><span class="">'+data[i]['ROAD']+" "+data[i]['CITY']+' <small class="friend_name -km -walk">('+data[i]['km']+' km de vous)</small></span></div><div class="button__container"><button class="button -color -blue -walk get_to_walk" data-id='+data[i]['ID']+'>En savoir plus</button></div>';
                        $('.walk__container__result').append('<div class="walk__card test">'+walk+'</div>');
                    }
                }else{
                    $('.content_container .container .row .col').append('<div class="walk__noresult">Aucun resultat pour la recherche</div>');
                }

                $(".walk__handler__container").css('background','transparent');

                setTimeout(function(){
                    $(".walk__handler__container").css('transform','translateY(100%)');
                }, 750);

                setTimeout(function(){
                    $(".walk__handler__container").css('display','none');
                    $("body").css('overflow','auto');

                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#mapid").offset().top - 150
                    }, 500);
                }, 1250);

                //$(".map__container").show();

                $('.map__container').eq(0).hide();
                $(".map__container").eq(1).show();
                $('.get_to_walk').click(function(){
                    window.location = "walk_detail?ID="+$(this).data('id');
                });
            });

        },500);
    });

    $('#validate__sign').click(function(e){

        let dogSelected = [];
        let id_event = $('#validate__sign').data('id');
        let x = 0;

        $('.dog_card').each(function(){
            if($(this).hasClass('-active')){
                dogSelected.push($(this).data('id'));
            }
        });

        if(dogSelected.length > 0){
            $.ajax({
                method: "GET",
                data:{
                   DOG : dogSelected,
                   ID : id_event
                },
                url:"src/php/joinwalk.php",
            })
            .done(function(result){
                if (result == "success")
                    document.location.reload(true);
            });
        }
    });

    if($('body').is('.home')){
        $.ajax({
            method: "GET",
            url:"src/php/get_user_walk.php",
        })
        .done(function(result){

            console.log(result);

            if(result){
                data = JSON.parse(result);

                //savoir le jour
                var jour = new Array(7);
                jour[0] = "Lundi";
                jour[1] = "Mardi";
                jour[2] = "Mercredi";
                jour[3] = "Jeudi";
                jour[4] = "Vendredi";
                jour[5] = "Samedi";
                jour[6] = "Dimanche";

                //savoir le mois
                var mois = new Array(12);
                mois[0] = "Janvier";
                mois[1] = "Février";
                mois[2] = "Mars";
                mois[3] = "Avril";
                mois[4] = "Mai";
                mois[5] = "Juin";
                mois[6] = "Juillet";
                mois[7] = "Août";
                mois[8] = "Septembre";
                mois[9] = "Octobre";
                mois[10] = "Novembre";
                mois[11] = "Décembre";

                if (data.length > 0){

                    $('.content_container .container .row .col .user_walk').append('<div class="walk__container"></div>');

                    for( let i = 0; i < data.length; i++){
                        let date = new Date(data[i]['DATE_START']);
                        let hourSplit = data[i]['DATE_START'].split(' ')[1].split(':');
                        let hour = hourSplit[0] + ":" + hourSplit[1];
                        let day = jour[date.getDay()];
                        let monthNumber = date.getMonth()+1;
                        let month = mois[date.getMonth()];
                        let dayNumber = date.getDate();
                        let year = date.getFullYear();

                        let walk = '<div class="name__container -home"><span>'+data[i]['NAME']+'</span></div><div class="address__container -home">'+data[i]['ROAD']+' '+data[i]['CITY']+'</div><div class="date__container -home"><span class="">'+ day +" "+dayNumber +" "+ month+" "+ hour+'</span></div><div class="button__container -preview"><i class="icon icon__up -right get_to_walk" data-id='+data[i]['ID']+'></i><span class="learn__more">En savoir plus</span></div></div>';
                        // A rajouter à la ligne du dessus pour repasser à l'ancienne version
                        //+'<span class="walk__name">'+data[i]['LENGTH']+' heures</span><span>'+data[i]['WALK']+'</span></div><div class="town__container"><i class="icon home icon-ic_home_48px"></i><span class="">'+data[i]['LOCATION']+'</span></div><div class="align-right"><div class="button__container"><button class="button -color -blue -round -walk get_to_walk" data-id='+data[i]['ID']+'>En savoir plus</button></div></div>'
                        $('.walk__container').append('<div class="walk__card -home test">'+walk+'</div>');
                    }

                    $('.get_to_walk').click(function(){
                        window.location = "walk_detail?ID="+$(this).data('id');
                    });

                    if (data.length > 1 && $('body').is('.walk') || $('body').is('.home')){
                        $('.content_container .container .row .col .user_walk').css({
                            "max-height": "120px",
                            "overflow" : "scroll"
                        })
                    }


                }
            }else{
                if($('body').is('.home'))
                    $('.content_container .container .row .col .user_walk').html("<p class='information'>Vous n'êtes inscrit à aucune balades pour le moment !</p><div class='center'><a href='walk.php'><button class='button -color'>Découvrez les balades</button></a></div>");
                else
                    $('.content_container .container .row .col .user_walk').html("<img class='map__img' src='src/assets/img/ressources/no_walk.png'/>");
            }
        });
    }

    if($('body').is('.getMessage')){

        $('#message').keypress(function (e) {
            var key = e.which;
            if(key == 13)  // the enter key code
            {
                let user2 = $('body').data('id');
                if($('#message').val() != ""){
                    $.ajax({
                        method: "GET",
                        data: {ID:user2,MESSAGE:$('#message').val()},
                        url:"src/php/insertMessage.php",
                    })
                    .done(function(result){

                        $.ajax({
                            method: "GET",
                            data: {ID:user2},
                            url:"src/php/getLastMessage.php",
                        })
                        .done(function(result){

                            setTimeout(function(){
                                $('.messages').append(result);
                            },500);

                            $('.messages').animate({
                                scrollTop: realHeight
                            }, 500);

                            if($('#message').hasClass('-error')){
                                $('#message').removeClass('-error');
                            }

                            $('#message').val('');
                        })
                    })
                }else{
                    $('#message').addClass('-error');
                }
            }
        });

        var pubnub = new PubNub({
		    subscribeKey: 'sub-c-1ef083d0-dc62-11e8-911d-e217929ad048', // always required
		    publishKey: 'pub-c-07c00e21-e522-43ef-965d-f81e60f7a47f' // only required if publishing
		});

		pubnub.subscribe({
			channels : ['message'],
		});

		pubnub.addListener({
		    message: function(message) {


                $('.messages').append(message.message);
                $('.messages').children().last().css('opacity','0');

                setTimeout(function(){
                    if ($('.messages').children().last().data('id') == $('body').data('me')){

                        $('.messages').children().last().remove();
                        $('.messages').children().last().css('opacity','1');
                    }else{
                         $('.messages').children().last().css('opacity','1');
                    }
                },10);

                //$('.messages').children().last().css('opacity','1');


                let realHeight  = $('.messages').scrollTop() + ($('.message__body').height() * 2);

                setTimeout(function(){
                    $('.messages').animate({
                        scrollTop: realHeight
                    }, 500);
                },100);

			}
		});

        $('body').css('overflow','hidden');

        $('.message__viewer__container').css('height',$(window).height()  - 220+'px');
        $('.messages').css('height',$(window).height()  - 220+'px');
        $('.message__viewer__container').css('position','relative');

        let realHeight  = $('.messages')[0].scrollHeight;

        setTimeout(function(){
            $('.messages').animate({
                scrollTop: realHeight
            }, 500);
        },500);

        $('.sendMessage').click(function(){

            let user2 = $('body').data('id');

            if($('#message').val() != ""){
                $.ajax({
                    method: "GET",
                    data: {ID:user2,MESSAGE:$('#message').val()},
                    url:"src/php/insertMessage.php",
                })
                .done(function(result){

                    $.ajax({
                        method: "GET",
                        data: {ID:user2},
                        url:"src/php/getLastMessage.php",
                    })
                    .done(function(result){

                        setTimeout(function(){
                            $('.messages').append(result);
                        },500);

                        $('.messages').animate({
                            scrollTop: realHeight
                        }, 500);

                        if($('#message').hasClass('-error')){
                            $('#message').removeClass('-error');
                        }

                        $('#message').val('');
                    })
                })
            }else{
                $('#message').addClass('-error');
            }
        });
    }
});

$('body').on('click', '[data-editable]', function(){

    var $el = $(this);
    let value = $(this).html();

    var $input = $('<input />').val( $el.text() );
    $el.replaceWith( $input );

    var save = function(){
        let value = $p
        var $p = $('<p data-editable class="dog_name -nolimit"'+ value +'><i class="icon edit icon-ic_edit_48px"></i></p>').text( $input.val() );
        if ($(this).is(':empty')){
            $input.replaceWith( $p );
        }
    };

    $input.one('blur', save).focus();

});

function previewImage(file) {
    var gallery = $('#gallery');
    var imageType = /image.*/;

    if (!file.type.match(imageType)) {
        throw "File Type must be an image";
    }

    var thumb = document.createElement("div");
    thumb.classList.add('thumbnail');

    var img = document.createElement("img");
    img.classList.add('dog_img');
    img.file = file;
    thumb.appendChild(img);
    gallery.html(thumb);

    var reader = new FileReader();
    reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
    reader.readAsDataURL(file.slice(0,10 * 4096 * 4096));
    //console.log(reader);

    galleryButton = document.getElementById("gallery__button");
    var button = document.createElement("button");
    button.id = "deleteImage";
    button.setAttribute('type', 'button');
    button.classList.add('icon');
    button.classList.add('close-icon');
    if($('.edit__profile').length != 0){
        button.classList.add('-imgAdd');
    }
    else{
        button.classList.add('-dogAdd');
    }

    galleryButton.appendChild(button);

    var deleteImg = document.getElementById("deleteImage");
    deleteImage.addEventListener("click", function(){
        var gallery = document.getElementById("gallery");
        var galleryButton = document.getElementById("gallery__button");
        if (gallery.children.length != 0){
            gallery.removeChild(gallery.childNodes[0]);
            galleryButton.removeChild(galleryButton.childNodes[0]);
        }

        $('.label-file').show();
        let size = $(window).height();
        let position_popup = (size / 2) - (($('.dog_handler_container').height() + 40) / 2);
        $('.dog_handler_container').css('top',position_popup);


    });
}

function uploadFile(file, name, page){

    //console.log(name);

    if (name != "")
        var url = 'src/php/uploadFile.php?name='+name+'&'+'page='+page;
    else
        var url = 'src/php/uploadFile.php?page='+page;
    var name = name;
    var xhr = new XMLHttpRequest();
    var fd = new FormData();
    xhr.open("POST", url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            //console.log(xhr.responseText);
            if (name != "")
                $("#addDog").prop('disabled', false);
            else
                $("#update").prop('disabled', false);
        }
    };
    fd.append("upload_file", file);
    xhr.send(fd);
}