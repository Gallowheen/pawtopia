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
});