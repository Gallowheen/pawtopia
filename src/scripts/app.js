$(document).ready(function(){ 

    $(".handle_friend").click(function(e){
        e.preventDefault();

        $(document).mouseup(function (e){
            if (!$('.friends__handler').find("*").is(e.target) && !$('.friends__handler').is(e.target)){
                $(".friends__handler__container").css('background','transparent'); 

                setTimeout(function(){
                    $(".friends__handler__container").css('transform','translateY(100%)'); 
                    $("body").css('overflow','auto');
                }, 1000);

                setTimeout(function(){
                    $(".friends__handler__container").css('display','none');
                }, 1500);
                $(document).off();
            }
        });

            var user = $(this).data("user");
        
			$.ajax({
				method: "GET",
				data:{ID:user},
				url:"src/php/print_friend_request.php",
			})
			.done(function(result){ 
                $("body").css('overflow','hidden');
                $(".friends__handler__container").css('display','block');

                setTimeout(function(){
                    $(".friends__handler__container").css('transform','translateY(0%)'); 
                }, 500);

                setTimeout(function(){
                    $(".friends__handler__container").css('background','#00000024'); 
                }, 1500);
                           
                $(".friends__handler .container .row .col").html(result);


                $('.friends__handler .avatar').click(function(){

                    let user = $(this).data('id');

                    window.location = "profile.php?ID=" + user;
                });
                // CREATE YES BUTTON
                $("#accept").click(function(e){
                    e.preventDefault();
                    var user = $(this).data("user");
                
                    $.ajax({
                        method: "GET",
                        data:{ID:user},
                        url:"src/php/accept_friend.php",
                    })
                    .done(function(result){ 

                        let data = JSON.parse(result);

                        //On vérifie que la demande soit acceptée
                        if (data[0] === "success"){
                            $('.result').append('Vous êtes maintenant amis');

                            setTimeout(function(){
                                $(".friends__handler__container").css('background','transparent'); 
                            }, 500);

                            setTimeout(function(){
                                $(".friends__handler__container").css('transform','translateY(100%)'); 
                                $("body").css('overflow','auto');
                            }, 2000);

                            setTimeout(function(){
                                $(".friends__handler__container").css('display','none');
                                $('.handle_friend[data-user="'+user+'"]').remove()
                            }, 2500);
  
                        }else{
                            $('.result').append('Une erreur est survenue, veuillez réessayer plus tard');
                        }

                        //On crée 'facticement' l'entrée de l'ami dans la liste
                        $('.friend_widget_container').append('<div class="friend_widget"><img class="avatar avatar--small" src="'+data[1].AVATAR+'"/><p>'+data[1].USERNAME+'</p></div>');
                        //On ajoute +1 au nombre d'ami
                        $('.information').html('Mes amis (' + $('.friend_widget_container div').length + ')')   
                        //On ajoute le message : pas de demande en attente si nécéssaire
                        if ($('.friend_pending div p').length === 0){
                            setTimeout(function(){
                                $('.friend_pending .col-12').append('<p>Aucune demande en attente</p>');
                            }, 2500);
                        }
                    });
                });
                // CREAT NO BUTTON
                $("#refuse").click(function(e){
                    e.preventDefault();
            
                    var user = $(this).data("user");
                
                    $.ajax({
                        method: "GET",
                        data:{ID:user},
                        url:"src/php/refuse_friend.php",
                    })
                    .done(function(result){ 
                        setTimeout(function(){
                            $(".friends__handler__container").css('background','transparent'); 
                        }, 500);

                        setTimeout(function(){
                            $(".friends__handler__container").css('transform','translateY(100%)'); 
                            $("body").css('overflow','auto');
                        }, 2000);

                        setTimeout(function(){
                            $(".friends__handler__container").css('display','none');
                            $('.handle_friend[data-user="'+user+'"]').remove();
                            $('.friend_pending .col-12').append('<p>Aucune demande en attente</p>');
                        }, 2500);
                    });
                });
			});
    });

    $(".view").click(function(e){
        e.preventDefault();
        e.stopPropagation();

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
            console.log(data);
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
                    member = '<div data-id="'+data[i].ID+'" class="view friend_widget"><img class="avatar avatar -friendlist" src="'+data[i].AVATAR+'"><span class="friend_name -member">'+data[i].USERNAME+'</span><span class="friend_name -km">A '+data[i].km+' km de vous</span></div>'; 
                    $(".member__filtred .container .row .col .member_widget_container").append(member);
                }

                $(".view").click(function(e){
                    e.preventDefault();
                    e.stopPropagation();
            
                    var user = $(this).data("id");
            
                    window.location = "profile.php?ID=" + user;
                });
            }

            $(".members__handler__container").css('background','transparent'); 

            setTimeout(function(){
                $(".members__handler__container").css('transform','translateY(100%)'); 
            }, 750);

            setTimeout(function(){
                $(".members__handler__container").css('display','none'); 
                $("body").css('overflow','auto');
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
        });
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
            console.log(result);
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
            console.log('lol');
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

        console.log(dogSelected);

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
            console.log(result);
            console.log(JSON.parse(result));
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

            if($('.walk__container'))
                    $('.walk__container').remove();

            if($('.tag_container'))
                $('.tag_container').remove();

            if($('.walk__noresult'))
                $('.walk__noresult').remove();
            
            if (data.length >= 1){

                if ($('form input[type=radio]:checked').val() != undefined)
                    $('.content_container .container .row .col').append('<div class="tag_container"><div class="tags"><span>'+$('form input[type=radio]:checked').val()+'</span></div><div class="tags"><span>'+$('#slider').val()+'km</span></div></div>');
                else
                $('.content_container .container .row .col').append('<div class="tag_container"><div class="tags"><span>'+$('#slider').val()+'km</span></div></div>');

                console.log(data.length);

                $('.content_container .container .row .col').append('<div class="walk__container"></div>');
            
                for( let i = 0; i < data.length; i++){
                    let date = new Date(data[i]['DATE_START']);
                    let hourSplit = data[i]['DATE_START'].split(' ')[1].split(':');
                    let hour = hourSplit[0] + ":" + hourSplit[1];
                    let day = jour[date.getDay()];
                    let month = mois[date.getMonth()];
                    let dayNumber = date.getDate();

                    let walk = '<div class="name__container"><span class="">'+data[i]['NAME']+'</span>'+'<span class="walk__name">'+data[i]['WALK']+'</span></div><div class="date__container"><span class="">'+ day +" "+dayNumber +" "+ month+" "+ hour+'</span>'+'<span class="walk__name">Durée : '+data[i]['LENGTH']+' heures</span></div><div class="town__container"><span class="">'+data[i]['town_name']+' <small>('+data[i]['km']+' km de vous)</small></span></div><div class="location__container"><span class="">'+data[i]['LOCATION']+'</span></div><div class="button__container"><button class="button -color -blue -round get_to_walk" data-id='+data[i]['ID']+'>Voir plus</button></div>';
                    $('.walk__container').append('<div class="walk__card">'+walk+'</div>');
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
            }, 1250);

            $('.get_to_walk').click(function(){
                console.log('lol');
                window.location = "walk_detail?ID="+$(this).data('id');
            });
        });
    });

    $('#validate__sign').click(function(e){
        console.log('lol');

        let dogSelected = [];
        let id_event = $('#validate__sign').data('id'); 
        console.log(id_event);
        let x = 0;

        $('.dog_card').each(function(){
            console.log('bite');
            if($(this).hasClass('-active')){
                dogSelected.push($(this).data('id'));
            }
        });

        console.log(dogSelected);

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
                console.log(result);
                if (result == "success")  
                    document.location.reload(true);
            });
        }
    });
});

$('body').on('click', '[data-editable]', function(){
  
    var $el = $(this);
                
    var $input = $('<input type="number"/>').val( $el.text() );
    $el.replaceWith( $input );
    
    var save = function(){
      var $p = $('<p data-editable />').text( $input.val() );
      $input.replaceWith( $p );
    };

    $input.one('blur', save).focus();
    
});