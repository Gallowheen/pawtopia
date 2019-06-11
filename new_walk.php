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

    if(!isset($_SESSION['ID'])){
        header('Location:index.php');
        exit;
    }

    // Récupérer l'utilisateur
    $user;
    $user = $_SESSION['ID'];

    if($user) {
        $query = $link->prepare("SELECT * FROM user WHERE ID = ?");
        $query->bind_param("i", $user);
        $query->execute();

        $result = $query->get_result();
        if($result->num_rows === 0){
            echo 'Error';
            $error = true;
            //redirect ?
        }
        $row = $result->fetch_assoc();
    }  

    $pagename = 'Nouvelle balade';
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Pawtopia | New walk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,500,700|Fira+Sans:300,400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="src/styles/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="src/styles/app.css">
    <link rel="stylesheet" type="text/css" href="src/styles/sanitize.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/projets/tfe/beta/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/projets/tfe/beta/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/projets/tfe/beta/favicon-16x16.png">
    <link rel="manifest" href="/projets/tfe/beta/site.webmanifest">
    <link rel="mask-icon" href="/projets/tfe/beta/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/projets/tfe/beta/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="/projets/tfe/beta/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!-- Méta Google -->
    <meta name="title" content="Pawtopia" />
    <meta name="description" content="Pawtopia permet aux chiens et à leur maître de trouver des partenaires pour leurs balades." />

    <!-- Métas Facebook Opengraph -->
    <meta property="og:title" content="Pawtopia" />
    <meta property="og:description" content="Pawtopia permet aux chiens et à leur maître de trouver des partenaires pour leurs balades." />
    <meta property="og:url" content="https://dylanernoud.be/projets/tfe/beta/" />
    <meta property="og:image" content="https://dylanernoud.be/projets/tfe/beta/favicon.ico" />
    <meta property="og:type" content="website"/>

    <!-- Métas Twitter Card -->
    <meta name="twitter:title" content="Pawtopia" />
    <meta name="twitter:description" content="Pawtopia permet aux chiens et à leur maître de trouver des partenaires pour leurs balades." />
    <meta name="twitter:url" content="https://dylanernoud.be/projets/tfe/beta/" />
    <meta name="twitter:image" content="https://dylanernoud.be/projets/tfe/beta/favicon.ico" />
    </head>
    <?php 
        include ('src/php/header.php');
    ?>
    <body class="new_walk">
         <div class="content_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <form id="event_information" data-id="<?php echo $_SESSION['ID'] ?>">
                            <h3 class="h3 information">Informations de la balade</h3>
                            <div class="input__container -first">
                                <span class="input__name">Nom de la balade</span>
                                <input placeholder="Nom de votre balade" class="input -walk" value="Balade de <?php echo $row['USERNAME'] ?>"type="text" name="walk_name" id="walk_name">
                            </div>
                            <div class="input__container">
                                <span class="input__name">Type de la balade</span>
                                <select required placeholder="Type de balade" class="select -walk" name="walk_type" id="walk_type">
                                    <option value="" disabled selected hidden>Type de la balade</option>
                                    <option value="Récréative">Récréative</option>
                                    <option value="Sportive">Sportive</option>
                                    <option value="Découverte">Découverte</option>
                                </select>
                            </div>
                            <?php
                                $link = mysqli_connect(HOST, USER, PWD, BASE);
                                mysqli_query($link, "SET NAMES UTF8");
                                
                                $sql = "SELECT * FROM towns";
                                $query = mysqli_query($link,$sql);
                                while ( $results[] = mysqli_fetch_object ( $query ) );
                                array_pop ( $results );
                            ?>
                            <div class="input__container">
                                <span class="input__name">Localité</span>
                                <select required placeholder="Votre ville" class="select -walk" name="town" id="town">
                                    <option value="" disabled selected hidden>Choisissez votre ville</option>
                                    <?php foreach ( $results as $option ) : ?>
                                        <option value="<?php echo $option->ID; ?>"><?php echo $option->NAME; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="input__container">
                                <span class="input__name">Lieu de la balade</span>
                                <input placeholder="Lieu de la balade" class="input input -walk" type="text" name="info" id="info">
                            </div>
                            <div class="input__container">
                                <span class="input__name"> Date de la balade</span>
                                <input class="input -walk -time" type="time" id="time" name="time" required>
                                <input placeholder="Lieu de la balade" class="input -walk" type="date" name="date" id="date">
                            </div>
                            <div class="input__container">
                                <span class="input__name"> Durée approximative</span>
                                <select required placeholder="Type de balade" class="select -walk" name="length" id="length">
                                    <option value="" disabled selected hidden>Durée approximative</option>
                                    <option value="1">1 heure</option>
                                    <option value="1">2 heures</option>
                                    <option value="1">3 heures</option>
                                    <option value="1">4 heures</option>
                                    <option value="1">5 heures</option>
                                    <option value="1">6 heures</option>
                                    <option value="1">7 heures</option>
                                    <option value="1">8 heures</option>
                                    <option value="1">9 heures</option>
                                    <option value="1">10 heures</option>      
                                </select>
                            </div>
                            <div class="input__container -center">
                                <button class="input button -color -blue -nomargin" id="next">Suivant</button>
                            </div>
                        </form>
                        <?php 

                        // On récupère les chiens
                        $query = $link->prepare("SELECT * FROM dog WHERE OWNER_ID = ? and ACTIVE = 1");
                        $query->bind_param("i", $row['ID']);
                        $query->execute();

                        $result = $query->get_result();
                        $rows = resultToArray($result);  
                        ?>
                        <div class="walk__dog">
                            <h3 class="h3 information">Votre partenaire de balade</h3>
                            <div class="walk__dog__container">
                                <?php
                                foreach ( $rows as $dog ) :?>
                                <div class="dog_card -walk" data-id="<?php echo $dog['ID']?>">
                                    <?php
                                    echo "<h4 class='h4 dog_name'>".$dog['NAME']."</h4>";
                                    ?>
                                    <div class="dog_button -walk">
                                    <?php
                                    echo '<img class="dog_img avatar -small -noMargin" src="'.$dog['PICTURE'].'">';?>
                                    
                                    </div> 
                                </div> 
                                <?php    
                                endforeach;
                                ?>      
                            </div>
                            <div class="input__container -center">
                                <button class="input button -color -blue -nomargin" id="validate">Créer</button>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </body>      
    <?php
        include ('src/php/footer.php');
    ?>
    <script src="src/scripts/jquery-3.4.0.min.js"></script>
    <script src="src/scripts/bootstrap.min.js"></script>
    <script src="src/scripts/jquery.touchSwipe.min.js"></script>
    <script src="https://cdn.pubnub.com/sdk/javascript/pubnub.4.24.1.js"></script>
    <script src="src/scripts/app.js"></script>
</html>
