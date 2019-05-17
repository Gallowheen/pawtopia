<?php 
    require_once("src/php/bdd.php");
    session_start();
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    //Function to return table of result
    function resultToArray($result) {
        $rows = array();
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    $pagename = 'Membres';
    
    $error = false;

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
    <style>
        
    </style>

    </head>
    <body class="members">
        <?php 
        include ('src/php/header.php');
        ?>
        <div class="member__content">
            <div class="member__filtred">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <h3 class="h3">Aidez nous à trouver votre partenaire de balade idéale</h3>
                            <div class="find__more">
                                <button class="button -color -blue -nomargin" id="filter">Filtrer</button>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>  
        </div>
        <div class="members__handler__container">
            <div class="members__handler">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <form>
                                <h3 class="h3 filter__title">Proximité</h3>
                                <div class="slider__value" id="slider_value">125 km</div>
                                <div class="slider__range">
                                    <span class="range__number">0</span>
                                    <input class="slider" type="range" id="slider" name="km" min="0" max="250">
                                    <span class="range__number">250</span>
                                </div>
                                <h3 class="h3 filter__title">Type de balade préféré</h3>
                                <div class="button_choice">
                                    <label class="label selected" for="Sportive"><div class="button_container">
                                        <input class="hidden" type="radio" name="walk" id="Sportive" value="Sportive" checked>
                                        <i class="icon icon-ic_directions_run_48px icon_walk"></i>
                                        <span class="walk__type">Sportive</span>
                                    </div></label>
                                    <label class="label" for="Découverte"><div class="button_container">
                                        <input class="hidden" type="radio" name="walk" id="Découverte" value="Découverte">
                                        <i class="icon icon-ic_map_48px icon_walk"></i>
                                        <span class="walk__type">Découverte</span>
                                    </div></label>                
                                    <label class="label" for="Récréative"><div class="button_container">
                                        <input class="hidden" type="radio" name="walk" id="Récréative" value="Récréative">
                                        <i class="icon icon-ic_pets_48px icon_walk"></i>
                                        <span class="walk__type">Récréative</span>
                                    </div></label>
                                </div>
                                <div class="submit__button">
                                    <button class="button -color -blue -nomargin" id="submit__members" type="submit">C'est parti !</button>
                                </div>
                            </form>
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
