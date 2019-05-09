$(document).ready(function(){ 
    $('#logout').click(function(){

        $.ajax({
        type: 'GET',
        url: 'src/php/logout.php',
        success: function(msg) {
            console.log(msg);
        }
        });
    });

    $(".handle_friend").click(function(e){
        e.preventDefault();
        console.log($(this).data("user"));
        console.log('lol');

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
                           
                $(".friends__handler").append(result);

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
                        $('.result').append('Vous avez refusé la demande');
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

    //var x = nombre de swipe
    var x = 0;
    //var maxX = max swap
    var maxX = $(".dog_card_container").children().length;

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

    
});
