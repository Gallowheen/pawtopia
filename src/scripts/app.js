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