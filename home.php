<?php 
  require_once("src/php/bdd.php");
  session_start();
?>

<!DOCTYPE html>
<html>
  <head>
    <title>DWMA project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,500,700|Fira+Sans:300,400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="src/styles/app.css">
    <link rel="stylesheet" type="text/css" href="src/styles/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="src/styles/sanitize.css">
  </head>
  <body>
      <?php
        if ( isset($_SESSION['user']) ){
            echo "vous êtes connecté " . $_SESSION['user'];
            echo '<a href="src/php/logout.php">Log Out Btn</a>';
            ?>
            <a href="profile.php">Profile</a>
            <?php
        }
        ?>
  </body>
  <script src="src/scripts/jquery-3.4.0.min.js"></script>
  <script src="src/scripts/bootstrap.min.js"></script>
  <script src="src/scripts/app.js"></script>
</html>
