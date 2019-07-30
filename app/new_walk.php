<?php
    @session_start();
    require_once("src/php/bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    $retour = true;

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

            <div class="container">
                <div class="row centered_form">
                    <div class="col">
                        <form id="event_information" data-id="<?php echo $_SESSION['ID'] ?>">
                            <h3 class="h3 information">Informations de la balade</h3>
                            <div class="input__container -first" data-step=1>
                                <span class="input__name">Nom de la balade</span>
                                <input placeholder="Nom de votre balade" class="input -walk" value="Balade de <?php echo $row['USERNAME'] ?>"type="text" name="walk_name" id="walk_name">
                            </div>
                            <div class="input__container walk_type_wrapper hidden_form" data-step=2>
                                <span class="input__name">Type de la balade</span>
                                <select required placeholder="Type de balade" class="select -walk" name="walk_type" id="walk_type">
                                    <option value="" disabled selected hidden>Type de la balade</option>
                                    <option value="Récréative">Récréative</option>
                                    <option value="Sportive">Sportive</option>
                                    <option value="Découverte">Découverte</option>
                                </select>
                            </div>
                            <?php
                                $sql = "SELECT * FROM towns";
                                $query = mysqli_query($link,$sql);
                                while ( $results[] = mysqli_fetch_object ( $query ) );
                                array_pop ( $results );
                            ?>
                            <!-- <div class="input__container">
                                <span class="input__name">Localité</span>
                                <select required placeholder="Votre ville" class="select -walk" name="town" id="town">
                                    <option value="" disabled selected hidden>Choisissez votre ville</option>
                                    <?php foreach ( $results as $option ) : ?>
                                        <option value="<?php echo $option->ID; ?>"><?php echo $option->NAME; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div> -->
                            <div class="input__container walk_info_wrapper hidden_form" data-step=3>
                                <span class="input__name">Lieu de la balade</span>
                                <input placeholder="Lieu de la balade" class="input input -walk" type="text" name="info" id="info">
                            </div>
                            <div class="input__container walk_date_wrapper hidden_form" data-step=4>
                                <span class="input__name">Date de la balade</span>
                                <input class="input -walk" type="date" name="date" id="date">
                            </div>
                            <div class="input__container walk_date_wrapper hidden_form" data-step=5>
                                <span class="input__name">Heure de la balade</span>
                                <input class="input -walk -time" type="time" id="time" name="time" required>
                            </div>
                            <div class="input__container walk_length_wrapper hidden_form" data-step=6>
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
    <script src="src/scripts/new_walk.js"></script>