<?php 
    require_once("src/php/bdd.php");
    session_start();
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    $pagename = 'Balades';

    if(!isset($_SESSION['ID'])){
        header('Location:index.php');
        exit;
    }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>DWMA project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,500,700|Fira+Sans:300,400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="src/styles/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="src/styles/app.css">
    <link rel="stylesheet" type="text/css" href="src/styles/sanitize.css">
    </head>
    <body>
        <?php 
        include ('src/php/header.php');
        ?>
        <div class="content_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="input__container -first -center">
                            <button class="input button -color -blue -nomargin" id="new_walk">Créer une balade</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            include('src/php/footer.php');
        ?>
    </body>
    <script src="src/scripts/jquery-3.4.0.min.js"></script>
    <script src="src/scripts/bootstrap.min.js"></script>
    <script src="src/scripts/jquery.touchSwipe.min.js"></script>
    <script src="src/scripts/app.js"></script>
</html>
