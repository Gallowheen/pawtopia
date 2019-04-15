//console.log("Hello");

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

                if(data == 'Success'){
                    $("#resultat").html("<p>Votre inscription est validée</p>");
                }  
                if(data == 'username_needed'){
                    $("#username_error").html("<p>Veuillez entrer votre pseudonyme</p>");
                } 
                if(data == 'email_needed'){
                    $("#email_error").html("<p>Veuillez entrer votre adresse mail</p>");
                } 
                if(data == 'town_needed'){
                    $("#town_error").html("<p>Veuillez entrer votre ville</p>");
                } 
                if(data == 'password_needed'){
                    $("#password_error").html("<p>Veuille entrer votre mot de passe</p>");
                }  
                if(data == 'password_needed'){
                    $("#resultat").html("<p>Les deux mots de passent ne sont pas identiques</p>");
                }               
                if(data == 'Failed'){
                    $("#resultat").html("<p>Erreur lors de l'inscription</p>");
                }
            },
            'text'
        );
    });
});