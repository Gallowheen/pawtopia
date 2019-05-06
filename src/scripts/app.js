$(document).ready(function(){
    $("#submit").click(function(e){
        e.preventDefault();

        $.post(
            'src/php/connect.php',
            {
            username : $("#login_username").val(),
            password : $("#login_password").val()
            },

            function(data){

                if(data == 'Success'){
                    location.reload();
                }else{
                    $("#log_error").append("<p>La connexion a echouée</p>"); 
                }
            },
            'text'
        );
    });
    
    $('#logout').click(function(){

        $.ajax({
        type: 'GET',
        url: 'src/php/logout.php',
        success: function(msg) {
            console.log(msg);
        }
        });
    });

    $("#register").click(function(e){
        e.preventDefault();

        $.post(
            'src/php/register.php',
            {
            username : $("#username").val(),
            email : $("#email").val(),
            town : $("#town").val(),
            password_1 : $("#password_1").val(),
            password_2 : $("#password_2").val()
            },

            function(data){


                $("#resultat").html(" ");
                $("#username_error").html(" ");
                $("#email_error").html(" ");
                $("#town_error").html(" ");
                $("#password_error").html(" ");

                if(data == 'Success'){
                    $("#resultat").append("<p>Votre inscription est validée</p>");
                }else{
                    if(data.indexOf('username_needed') > -1){
                        $("#username_error").append("<p>Veuillez entrer votre pseudonyme</p>");
                    } 
                    if(data.indexOf('email_needed') > -1){
                        $("#email_error").append("<p>Veuillez entrer votre adresse mail</p>");
                    } 
                    if(data.indexOf('password_needed') > -1){
                        $("#password_error").append("<p>Veuille entrer votre mot de passe</p>");
                    }  
                    if(data.indexOf('town_needed') > -1){
                        $("#town_error").append("<p>Veuillez entrer votre ville</p>");
                    } 
                    if(data.indexOf('password_no_match') > -1){
                        $("#resultat").append("<p>Les deux mots de passe ne sont pas identiques</p>");
                    }   
                    if(data.indexOf('user_taken') > -1){
                        $("#resultat").append("<p>Le pseudonyme est déjà utilisé</p>");
                    } 
                    if(data.indexOf('mail_taken') > -1){
                        $("#resultat").append("<p>L'adresse mail est déjà utilisée</p>");
                    } 
                    if(data.indexOf('town_unknown') > -1){
                        $("#town_error").append("<p>Vérifiez votre ville</p>");
                    }      
                }   
            },
            'text'
        );
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
});

$(".view").click(function(e){

    var user = $(this).data("id");

    window.location = "profile.php?ID=" + user;
});