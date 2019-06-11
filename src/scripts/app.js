$(document).ready(function(){ 

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

    $(".view").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        console.log(e);

        var user = $(this).data("id");

        window.location = "profile.php?ID=" + user;
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

        if($('body').is('.members'))
            $(".my_pet__container .container--full .row .col").append('<div class="dog_card_bubble_container"></div>');
        else    
            $(".my_pet__container .container .row .col").append('<div class="dog_card_bubble_container"></div>');

        $(".dog_card_container").children().each(function(){
            $('.dog_card_bubble_container').append('<div class="bubble"></div>');
        });

        $(".bubble").first().addClass('-active');


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

    initSwipe();

    if($('body').is('.members')){
        $.ajax({
            method: "GET",
            url:"src/php/showcase_member.php",
        })
        .done(function(result){ 
            let data = JSON.parse(result);
            console.log(data);
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

    $('#submit__members').click(function(e){

        e.preventDefault();

        $.ajax({
            method: "GET",
            data:{
                walk:$('form input[type=radio]:checked').val(),
                km : $('#slider').val()
            },
            url:"src/php/managefriend.php",
        })
        .done(function(result){ 
        
            let data = JSON.parse(result);
            //console.log(data);
            let member;

            if(data.length == 0){

            }else{

                if($('.member_widget_container'))
                    $('.member_widget_container').remove();

                if($('.tag_container'))
                    $('.tag_container').remove();

                if ($('form input[type=radio]:checked').val() != undefined)
                    $('.member__filtred .container .row .col').append('<div class="tag_container"><div class="tags"><span>'+$('form input[type=radio]:checked').val()+'</span></div><div class="tags"><span>'+$('#slider').val()+'km</span></div></div>');
                else
                $('.member__filtred .container .row .col').append('<div class="tag_container"><div class="tags"><span>'+$('#slider').val()+'km</span></div></div>');

                $('.member__filtred .container .row .col').append('<div class="member_widget_container"></div>');
                
                for( let i = 0; i < data.length; i++){
                    member = '<div data-id="'+data[i].ID+'" class="test friend_widget -hidden"><div class="friend__info"><img class="avatar -friendlist" src="'+data[i].AVATAR+'"><div class="container__info"><span class="friend_name -member">'+data[i].USERNAME+'</span><span class="friend_name -km">A '+data[i].km+' km de vous</span></div></div></div>'; 
                    $(".member__filtred .container .row .col .member_widget_container").append(member);
                }
 

                $(".test").click(function(e){
                    e.preventDefault();
                    e.stopPropagation();
            
                    var user = $(this).data("id");
                    var container = $(this);

                    $.ajax({
                        method: "GET",
                        url:"src/php/simple_profile.php?ID="+user,
                    })
                    .done(function(result){ 

                        if ( container.hasClass('expanded')){
                            
                        }else{
                            container.append(result);
                            container.addClass('expanded');
                            let tap = container.children().eq(1).children().eq(2);

                            let height = container.children().eq(1).height();
                            container.animate({ height: height + 75}, "fast");
                            setTimeout(function(){
                                tap.animate({opacity : 1},"slow");
                            },250);
                            container.children().eq(0).hide();
                            initSwipe();

                            $(".view").click(function(e){
                                e.preventDefault();
                                e.stopPropagation();
                        
                                var user = $(this).data("id");
                        
                                window.location = "profile.php?ID=" + user;
                            });
                        }   
                        
                        $('.tapToClose').click(function(e){  
                            e.stopPropagation();

                            var container = $(this).parent().parent();
                            var child = $(this).parent().parent().eq(0).children().eq(1);
                            $(this).parent().parent().removeClass('expanded');
                            $(this).parent().parent().children().eq(0).css("display","block"); 
                            $(this).parent().parent().css("height", "75px");
                            $([document.documentElement, document.body]).animate({
                                scrollTop: container.offset().top - 100
                            }, 1000);
                            console.log(child);
                            child.animate({opacity : 0},"slow");

                            setTimeout(function(){
                                child.remove();     
                            },250);   
                        });
                    });
                });
            }

            $(".members__handler__container").css('background','transparent'); 

            setTimeout(function(){
                $(".members__handler__container").css('transform','translateY(100%)'); 
            }, 750);

            setTimeout(function(){
                $(".members__handler__container").css('display','none'); 
                $("body").css('overflow','auto');
                $(".showcase__member").css("height","0px");
            }, 1250);
        });
    });

    $('#filter').click(function(){

        $("html, body").animate({ scrollTop: 0 }, "slow");

        if($('body').is('.walk')){
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1;
            var yyyy = today.getFullYear();
            var hour = today.getHours();
            if(dd<10){
                    dd='0'+dd
                } 
                if(mm<10){
                    mm='0'+mm
                } 
    
            today = yyyy+'-'+mm+'-'+dd;
            document.getElementById("date").setAttribute("min", today);
            document.getElementById("date").setAttribute("value", today);
        }

        if($('body').is('.walk')){
            $(".walk__handler").find("input:radio").prop("checked", false);
            $(".label").each(function(){
                if($(this).hasClass('selected'))
                    $(this).removeClass('selected')
            });

            $("body").css('overflow','hidden');
            $(".walk__handler__container").css('display','block');

            setTimeout(function(){
                $(".walk__handler__container").css('transform','translateY(0%)'); 
            }, 500);

            setTimeout(function(){
                $(".walk__handler__container").css('background','#00000024'); 
            }, 1500);
        }else{
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
        }
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

                    //console.log(image);

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
                        //console.log(result);
                        var files = uploadfiles.files;
                        for(var i=0; i<files.length; i++){
                            uploadFile(uploadfiles.files[i],name);
                        }
        
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
                if (gallery.children.length == 0){
                    var files = this.files;
                    for(var i=0; i<files.length; i++){
                        previewImage(this.files[i]);
                    }
                }

                $('.label-file').hide();
                let position_popup = (size / 2) - (($('.dog_handler_container').height() + 40) / 2);
                $('.dog_handler_container').css('top',position_popup);
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
            $('.avatar__container .container .row .col').append('<div id="friend__button"><button class="friend__button button -friend"><i class="icon icon__friend icon-ic_check_48px"></i>Envoyé</button></div>');
        });
    });

    $('.clickme').click(function(){
        $.ajax({
            method: "GET",
            url:"src/php/add_event.php",
        })
        .done(function(result){ 
            //console.log(result);
        });
    });

    //WALK 
    if($('body').is('.new_walk')){
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1;
        var yyyy = today.getFullYear();
        var hour = today.getHours();
        if(dd<10){
                dd='0'+dd
            } 
            if(mm<10){
                mm='0'+mm
            } 

        today = yyyy+'-'+mm+'-'+dd;
        document.getElementById("date").setAttribute("min", today);
        document.getElementById("date").setAttribute("value", today);
        document.getElementById("time").setAttribute("min", hour);
    }

    $('#new_walk').click(function(e){
        window.location = "new_walk.php";
    });

    $("#next").click(function(e){

        e.preventDefault();

        $('#event_information').css('display','none');
        $('.walk__dog').css('display','block');

        if($('body').is('.new_walk') || $('body').is('.walk_detail')){
            $('.dog_card').click(function(){
                if(!$(this).hasClass('-active')){
                    $(this).addClass('-active');
                }else{
                    $(this).removeClass('-active');
                }
            })
        }

        if($('body').is('.walk_detail')){
            //console.log('lol');
            $('.submit__button').remove();
            $(window).scrollTop($('.walk__add__dog').offset().top);
        }
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

        //console.log(dogSelected);

        $.get(
            'src/php/add_event.php',
            {
                ID_OWNER : $("form").data('id'),
                NAME : $("#walk_name").val(),
                TOWN_ID : $("#town").val(),
                LOCATION : $("#info").val(),
                TYPE : $("#walk_type").val(),
                DATE : date,
                LENGTH : $("#length").val(),
                DOG : dogSelected
            },

            function(data){
                if(data === 'success'){
                    window.location = "walk.php"; 
                }
            },
            'text'
        );
    });

    $('#submit__walks').click(function(e){
        e.preventDefault();

        $.ajax({
            method: "GET",
            data:{
                walk:$('form input[type=radio]:checked').val(),
                km : $('#slider').val(),
                date : $('#date').val()
            },
            url:"src/php/managewalk.php",
        })
        .done(function(result){ 
            //console.log(result);
            //console.log(JSON.parse(result));
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

            if($('.walk__container__result'))
                $('.walk__container__result').remove();

            if($('.tag_container'))
                $('.tag_container').remove();

            if($('.walk__noresult'))
                $('.walk__noresult').remove();
            
            if (data.length >= 1){

                if ($('form input[type=radio]:checked').val() != undefined)
                    $('.content_container .container .row .col').append('<div class="tag_container"><div class="tags"><span>'+$('form input[type=radio]:checked').val()+'</span></div><div class="tags"><span>'+$('#slider').val()+'km</span></div></div>');
                else
                $('.content_container .container .row .col').append('<div class="tag_container"><div class="tags"><span>'+$('#slider').val()+'km</span></div></div>');

                //console.log(data.length);

                $('.content_container .container .row .col').append('<div class="walk__container__result"></div>');
            
                for( let i = 0; i < data.length; i++){
                    let date = new Date(data[i]['DATE_START']);
                    let hourSplit = data[i]['DATE_START'].split(' ')[1].split(':');
                    let hour = hourSplit[0] + ":" + hourSplit[1];
                    let day = jour[date.getDay()];
                    let month = mois[date.getMonth()];
                    let dayNumber = date.getDate();

                    let walk = '<div class="name__container"><span class="">'+data[i]['NAME']+'</span></div><div class="date__container"><span class="">'+ day +" "+dayNumber +" "+ month+" "+ hour+'</span>'+'<span class="walk__name">'+data[i]['LENGTH']+' heures</span><span>'+data[i]['WALK']+'</span></div><div class="town__container"><i class="icon home icon-ic_home_48px"></i><span class="">'+data[i]['town_name']+' <small>('+data[i]['km']+' km de vous)</small></span></div><div class="button__container"><button class="button -color -blue -round -walk get_to_walk" data-id='+data[i]['ID']+'>En savoir plus</button></div>';
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
                    scrollTop: $(".walk__container__result").offset().top
                }, 500);
            }, 1250);

            $('.get_to_walk').click(function(){
                //console.log('lol');
                window.location = "walk_detail?ID="+$(this).data('id');
            });
        });
    });

    $('#validate__sign').click(function(e){
        //console.log('lol');

        let dogSelected = [];
        let id_event = $('#validate__sign').data('id'); 
        //console.log(id_event);
        let x = 0;

        $('.dog_card').each(function(){
            //console.log('bite');
            if($(this).hasClass('-active')){
                dogSelected.push($(this).data('id'));
            }
        });

        //console.log(dogSelected);

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
                //console.log(result);
                if (result == "success")  
                    document.location.reload(true);
            });
        }
    });

    if($('body').is('.walk') || $('body').is('.home')){
        $.ajax({
            method: "GET",
            url:"src/php/get_user_walk.php",
        })
        .done(function(result){ 

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
            
            if (result.length >= 1){

                data = JSON.parse(result);

                //console.log(data.length);

                $('.content_container .container .row .col .user_walk').append('<div class="walk__container"></div>');
            
                for( let i = 0; i < data.length; i++){
                    let date = new Date(data[i]['DATE_START']);
                    let hourSplit = data[i]['DATE_START'].split(' ')[1].split(':');
                    let hour = hourSplit[0] + ":" + hourSplit[1];
                    let day = jour[date.getDay()];
                    let month = mois[date.getMonth()];
                    let dayNumber = date.getDate();

                    let walk = '<div class="name__container"><span class="">'+data[i]['NAME']+'</span></div><div class="date__container"><span class="">'+ day +" "+dayNumber +" "+ month+" "+ hour+'</span>'+'<span class="walk__name">'+data[i]['LENGTH']+' heures</span><span>'+data[i]['WALK']+'</span></div><div class="town__container"><i class="icon home icon-ic_home_48px"></i><span class="">'+data[i]['town_name']+'</span></div><div class="button__container"><button class="button -color -blue -round -walk get_to_walk" data-id='+data[i]['ID']+'>En savoir plus</button></div>';
                    $('.walk__container').append('<div class="walk__card test">'+walk+'</div>');
                }

                $('.get_to_walk').click(function(){
                    //console.log('lol');
                    window.location = "walk_detail?ID="+$(this).data('id');
                });

                if (data.length > 1 && $('body').is('.walk') || $('body').is('.home')){
                    $('.content_container .container .row .col .user_walk').css({
                        "max-height": "220px",
                        "overflow" : "scroll"
                    })
                }


            }else{
                $('.content_container .container .row .col .user_walk').html("<p>Vous n'êtes inscrit à aucune balades</p>");
            }
        });
    }

    /// MESSAGE

    if($('body').is('.message')){

        $.ajax({
            method: "GET",
            url:"src/php/showcase_message.php",
        })
        .done(function(result){ 
            //console.log(result);
            if(result != "noMsg")
                data = JSON.parse(result);
            //console.log(data);
            let userID = $('body').data('id');
            let user = [];
            let userList = [];
            let banlist = [];
            let found = false;

            if (typeof data !== 'undefined' && data.length > 0){
                for (i = 0; i < data.length; i++){
                    if (user.length > 0){
                        let error = false;
                        let userToAdd;
                        for (k = 0; k < user.length; k++){            
                            if (data[i].ID_USER1 == userID){
                                if(data[i].ID_USER2 == user[k]){
                                    error = true;
                                }else{
                                    userToAdd = "USER1";
                                }
                            }else{
                                if(data[i].ID_USER1 == user[k]){
                                    error = true;
                                }else{
                                    userToAdd = "USER2";
                                }
                            }
                        }
                        if(!error){
                            if(userToAdd == "USER1"){
                                user.push(data[i].ID_USER2);
                            }else{
                                user.push(data[i].ID_USER1);
                            }
                        }
                    }else{
                        if (data[i].ID_USER1 != userID) 
                            user.push(data[i].ID_USER1);
                        else
                            user.push(data[i].ID_USER2);
                    }
                }

                console.log(user);

                for (i = 0; i < data.length; i++){   
                    for(k = 0; k < user.length; k++){ 
                        found = false;             
                        if (banlist.length > 0){
                            if (!banlist.includes(user[k])){
                                if (data[i].ID_USER1 == user[k] ){
                                    userList.push(data[i]);
                                    found = true;
                                }
                                if (data[i].ID_USER2 == user[k] ){
                                    userList.push(data[i]);
                                    found = true;
                                }
                            }   
                        }else{
                            if (data[i].ID_USER1 == user[k])
                                userList.push(data[i]);   
                            if (data[i].ID_USER2 == user[k])
                                userList.push(data[i]);
                            banlist.push(user[k]);
                        }
                        if (found)
                            banlist.push(user[k]); 
                    }
                }

                console.log(userList);

                userList.sort((a, b) => (a.STATUT > b.STATUT) ? -1 : 1);
                console.log(userList);

                userList.forEach(element => {

                    let ID;

                    if(element.ID_USER1 != $('body').data('id')){
                        ID = element.ID_USER1;
                    }
                    else{
                        ID = element.ID_USER2;
                    }
                    if(element.STATUT == "Unread" && element.ID_USER1 != $('body').data('id'))
                        member = '<div data-id="'+ID+'" class="toMessage statut-0 friend_widget -message -hidden -unread"><div class="friend__info"><img class="avatar -friendlist" src="'+element.AVATAR+'"><div class="container__info"><span class="friend_name -member">'+element.USERNAME+'</span><span class="friend_message">'+element.CONTENT+'</span></div></div></div>'; 
                    else
                        member = '<div data-id="'+ID+'" class="toMessage statut-1 friend_widget -message -hidden"><div class="friend__info"><img class="avatar -friendlist" src="'+element.AVATAR+'"><div class="container__info"><span class="friend_name -member">'+element.USERNAME+'</span><span class="friend_message">'+element.CONTENT+'</span></div></div></div>'; 
                    $(".content_container .container .row .col .message__container").append(member);
                });

                var divElement = $('.content_container .container .row .col .message__container').find('.toMessage');
                divElement.sort(sortMe);

                function sortMe(a, b) { 
                    return a.className.match(/statut-(\d)/)[1] - b.className.match(/statut-(\d)/)[1];
                }

                $('content_container .container .row .col .message__container').html(' ');
                $('.content_container .container .row .col .message__container').append(divElement);

                $('.toMessage').click(function(){

                    let IDuser = $(this).data('id');
                    $.ajax({
                        method: "GET",
                        data: {ID:IDuser},
                        url:"src/php/updateMessageStatus.php",
                    })
                    .done(function(result){ 
                        console.log(result);
                    })

                    setTimeout(function(){
                        window.location = "getMessage.php?ID=" + IDuser; 
                    },50);           
                });
            }
        })
    }
        
    if($('body').is('.getMessage')){

        var pubnub = new PubNub({
		    subscribeKey: 'sub-c-1ef083d0-dc62-11e8-911d-e217929ad048', // always required
		    publishKey: 'pub-c-07c00e21-e522-43ef-965d-f81e60f7a47f' // only required if publishing
		});

		pubnub.subscribe({
			channels : ['message'],
		});

		pubnub.addListener({
		    message: function(message) {
                //console.log(message.message);
                
                $('.messages').append(message.message);  
                            
                setTimeout(function(){
                    if ($('.messages').children().last().data('id') == $('body').data('me')){
                        console.log("APRES");
                        console.log($('.messages').children().last().prev(".message__body"));
                        $('.messages').children().last().remove();
                    }
                },10);

                
                let realHeight  = $('.messages')[0].scrollHeight;

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
            //console.log(user2);
            if($('#message').val() != ""){
                $.ajax({
                    method: "GET",
                    data: {ID:user2,MESSAGE:$('#message').val()},
                    url:"src/php/insertMessage.php",
                })
                .done(function(result){ 
                    //console.log(result);
                    $.ajax({
                        method: "GET",
                        data: {ID:user2},
                        url:"src/php/getLastMessage.php",
                    })
                    .done(function(result){ 
                        //console.log(result);
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
    //console.log(value);
    $el.replaceWith( $input );
    
    var save = function(){
        let value = $p
        var $p = $('<p data-editable class="dog_name"'+ value +'><i class="icon edit icon-ic_edit_48px"></i></p>').text( $input.val() );
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
    img.classList.add('-add');
    img.file = file;
    thumb.appendChild(img);
    gallery.html(thumb);

    var reader = new FileReader();
    reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
    reader.readAsDataURL(file);

    galleryButton = document.getElementById("gallery__button");
    var button = document.createElement("button");
    button.id = "deleteImage";
    button.setAttribute('type', 'button');
    button.classList.add('icon');
    button.classList.add('close-icon');
    button.classList.add('-dogAdd');
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

function uploadFile(file, name){
    var url = 'src/php/uploadFile.php?name='+name;
    var name = name;
    var xhr = new XMLHttpRequest();
    var fd = new FormData();
    xhr.open("POST", url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            //console.log(xhr.responseText);
        }
    };
    fd.append("upload_file", file);
    xhr.send(fd);
}