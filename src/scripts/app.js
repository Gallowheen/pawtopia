//console.log("Hello");

$(document).ready(function(){
    $("#submit").click(function(e){
        e.preventDefault();

        $.post(
            'src/php/connect.php',
            {
            username : $("#username").val(),
            password : $("#password").val()
            },

            function(data){

                if(data == 'Success'){
                $("#resultat").html("<p>Vous avez été connecté avec succès !</p>");
                location.reload();
                }
                else{
                $("#resultat").html("<p>Erreur lors de la connexion...</p>");
                }
            },
            'text'
        );
    });
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