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
                    console.log($(this).data("user"));
            
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
                    console.log($(this).data("user"));
            
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
        console.log('lol');

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
            swipe:function(event, direction, distance, duration, fingerCount){

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
            localStorage.setItem('km', $('#slider').val());
            localStorage.setItem('type', $('form input[type=radio]:checked').val());

            console.log(result);

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
                    member = '<div data-id="'+data[i].ID+'" class="view friend_widget"><img class="avatar avatar -friendlist" src="'+data[i].AVATAR+'"><span class="friend_name -member">'+data[i].USERNAME+'</span><span class="friend_name -km">'+data[i].km+' km</span></div>'; 
                    $(".member__filtred .container .row .col .member_widget_container").append(member);
                }

                console.log(result);

                $(".view").click(function(e){
                    e.preventDefault();
                    e.stopPropagation();
            
                    var user = $(this).data("id");
                    console.log('lol');
            
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

    if (localStorage.km !== undefined && localStorage.type !== undefined){

        $.ajax({
            method: "GET",
            data:{
                walk:localStorage.getItem('type'),
                km : localStorage.getItem('km')
            },
            url:"src/php/managefriend.php",
        })
        .done(function(result){ 

            var scroll = localStorage.getItem("scroll");

            // if($('body').is('.members'))
            //     setTimeout(function(){
            //         if (scroll >= 1)
            //             $('html, body').animate({scrollTop: '+='+scroll}, 800);
            //     },1000);

            let data = JSON.parse(result);
            console.log(data);
            let member;

            if(data.length == 0){

            }else{
                
                if($('.member_widget_container'))
                $('.member_widget_container').remove();

                if($('.tag_container'))
                    $('.tag_container').remove();

                if (localStorage.getItem('type') !== 'undefined')
                    $('.member__filtred .container .row .col').append('<div class="tag_container"><div class="tags"><span>'+localStorage.getItem('type')+'</span></div><div class="tags"><span>'+localStorage.getItem('km')+'km</span></div></div>');
                else
                    $('.member__filtred .container .row .col').append('<div class="tag_container"><div class="tags"><span>'+localStorage.getItem('km')+'km</span></div></div>');
            
                
                $('.member__filtred .container .row .col').append('<div class="member_widget_container"></div>');

                for( let i = 0; i < data.length; i++){
                    member = '<div data-id="'+data[i].ID+'" class="view friend_widget"><img class="avatar avatar -friendlist" src="'+data[i].AVATAR+'"><span class="friend_name -member">'+data[i].USERNAME+'</span><span class="friend_name -km">A '+data[i].km+' km de vous</span></div>'; 
                    $(".member__filtred .container .row .col .member_widget_container").append(member);
                }

                console.log(result);

                $(".view").click(function(e){
                    e.preventDefault();
                    e.stopPropagation();
            
                    // localStorage.setItem('scroll', $(window).scrollTop());
                    var user = $(this).data("id");
                    console.log('lol');
            
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
    }

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
        $(document.body).append('<div class="dog_handler"><div class="dog_handler_container -small"><div class="container"><div class="row"><div class="col"><p>Êtes-vous sûr de vouloir supprimer <b class="capitalize">'+ name +'</b> ?</p><div class="dog_handler_button_container"><button id="dog_accepted" class="button -color -blue -small">Oui</button><button id="dog_denied" class="button -color -blue -small">Non</button><div></div></div></div></div></div>')
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
        $(document.body).append('<div class="friend_handler"><div class="friend_handler_container"><div class="container"><div class="row"><div class="col"><p>Êtes-vous sûr de vouloir supprimer <b class="capitalize">'+ name +'</b> ?</p><div class="friend_handler_button_container"><button id="friend_deleted" class="button -color -blue -small">Oui</button><button id="friend_saved" class="button -color -blue -small">Non</button><div></div></div></div></div></div>')
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
                console.log(result);
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
        document.getElementById("time").setAttribute("min", hour);
    }

    $('#new_walk').click(function(e){
        window.location = "new_walk.php";
    });

    $("#validate").click(function(e){
        e.preventDefault();

        var date = $("#date").val() + " " + $("#time").val();

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
            },

            function(data){
                if(data === 'success'){
                    window.location = "walk.php"; 
                }
            },
            'text'
        );
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