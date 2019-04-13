<?php 
  session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <title>DWMA project</title>
  <link rel="stylesheet" type="text/css" href="src/styles/app.css">
  <link rel="stylesheet" type="text/css" href="src/styles/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="src/styles/sanitize.css">
</head>
<body>
  <img src="src/assets/images/logo-heaj.png">
  <h1 class="h1">Hello world</h1>


  <?php

    if ( isset($_SESSION['user']) ){
      echo "vous êtes connecté " . $_SESSION['user'];
      echo '<a href="src/php/logout.php">Log Out Btn</a>';
    }
    else{

      echo '<div id="resultat"></div>
      <h1>Un formulaire de connexion en AJAX</h1>

      <form>
        <p>
          Nom utilisateur : <input type="text" id="username" />
          Mot de passe : <input type="password" id="password" />
          <input type="submit" id="submit" value="Se connecter !" />
        </p>
      </form>';

    }
  ?>
<!-- <button id="logout" href="src/php/logout.php">Log Out Btn</button> -->
 
</body>
</html>

<script src="src/scripts/jquery-3.4.0.min.js"></script>
<script src="src/scripts/bootstrap.min.js"></script>
<script src="src/scripts/app.js"></script>

<script>
$(document).ready(function(){
 
$("#submit").click(function(e){
  e.preventDefault();

  $.post(
      'src/php/connexion.php',
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

</script>

</body>
</html>
