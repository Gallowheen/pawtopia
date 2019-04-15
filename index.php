<?php 
  session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <title>DWMA project</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="src/styles/app.css">
  <link rel="stylesheet" type="text/css" href="src/styles/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="src/styles/sanitize.css">
</head>
<body>
  <img src="src/assets/img/logo/logo-heaj.png">
  <h1 class="h1">Hello world</h1>


  <?php

    if ( isset($_SESSION['user']) ){
      echo "vous êtes connecté " . $_SESSION['user'];
      echo '<a href="src/php/logout.php">Log Out Btn</a>';
      echo '<img src="'.$_SESSION['avatar'].'">';
    }
    else{ ?>

      <div id="resultat"></div>
      <h1>Connect</h1>

      <form>
        <p>
          Nom utilisateur : <input type="text" id="username" />
          Mot de passe : <input type="password" id="password" />
          <input type="submit" id="submit" value="Se connecter !" />
        </p>
      </form>

      <h1>Sign Up</h1>

      <form method="post" action="src/php/register.php">
        <div class="input-group">
          <label>Username</label>
          <input type="text" name="username">
        </div>
        <div class="input-group">
          <label>Email</label>
          <input type="email" name="email">
        </div>
        <div class="input-group">
          <label>Ville</label>
          <input type="ville" name="town">
        </div>
        <div class="input-group">
          <label>Password</label>
          <input type="password" name="password_1">
        </div>
        <div class="input-group">
          <label>Confirm password</label>
          <input type="password" name="password_2">
        </div>
        <div class="input-group">
          <button type="submit" class="btn" name="reg_user">Register</button>
        </div>
      </form>
    <?php }
  ?>
<!-- <button id="logout" href="src/php/logout.php">Log Out Btn</button> -->
 
</body>
</html>

<script src="src/scripts/jquery-3.4.0.min.js"></script>
<script src="src/scripts/bootstrap.min.js"></script>
<script src="src/scripts/app.js"></script>

</body>
</html>
