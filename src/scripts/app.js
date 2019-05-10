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
            console.log(result);

            $(".members__handler__container").css('background','transparent'); 

            setTimeout(function(){
                $(".members__handler__container").css('transform','translateY(100%)'); 
                $("body").css('overflow','auto');
            }, 750);
        });
    });

    $('#filter').click(function(){
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
        $(document.body).append('<div class="dog_handler"><div class="dog_handler_container"><div class="container"><div class="row"><div class="col"><p>Êtes-vous sûr de vouloir supprimer <b class="capitalize">'+ name +'</b> ?</p><div class="dog_handler_button_container"><button id="dog_accepted" class="button -color -blue -small">Oui</button><button id="dog_denied" class="button -color -blue -small">Non</button><div></div></div></div></div></div>')
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

    $('.friend_delete').click(function(){
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