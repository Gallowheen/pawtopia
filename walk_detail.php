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

    $walk = $_GET['ID'];

    if($walk) {
        $query = $link->prepare("SELECT * FROM event WHERE ID = ?");
        $query->bind_param("i", $walk);
        $query->execute();

        $result = $query->get_result();
        if($result->num_rows === 0){

        }
        $row = $result->fetch_assoc();
    }

    $pagename = $row['NAME'];
?>

            <div class="container">
                <div class="row">
                    <div class="col">
                        <h3 class="information -first h3">Informations</h3>
                        <div class="informations">
                            <?php
                                $query = $link->prepare("SELECT * FROM event WHERE ID = ?");
                                $query->bind_param("i", $walk);
                                $query->execute();

                                $result = $query->get_result();
                                $row = $result->fetch_assoc();

                                $id_event = $row['ID'];

                                $town_check_query = $link->prepare("SELECT NAME FROM towns WHERE ID = ? ");

                                $town_check_query->bind_param("i", $row['TOWN_ID'] );
                                $town_check_query->execute();

                                $result_town = $town_check_query->get_result();
                                $row_city = $result_town->fetch_assoc();

                                $townToInsert = $row_city['NAME'];

                                $jour = [];
                                $jour[0] = "Lundi";
                                $jour[1] = "Mardi";
                                $jour[2] = "Mercredi";
                                $jour[3] = "Jeudi";
                                $jour[4] = "Vendredi";
                                $jour[5] = "Samedi";
                                $jour[6] = "Dimanche";

                                $mois = [];
                                $mois[1] = "Janvier";
                                $mois[2] = "Février";
                                $mois[3] = "Mars";
                                $mois[4] = "Avril";
                                $mois[5] = "Mai";
                                $mois[6] = "Juin";
                                $mois[7] = "Juillet";
                                $mois[8] = "Août";
                                $mois[9] = "Septembre";
                                $mois[10] = "Octobre";
                                $mois[11] = "Novembre";
                                $mois[12] = "Décembre";

                                $date = $row['DATE_START'];
                                $dateExplod = explode("-", $date);

                                $year = $dateExplod[0];
                                $month = intval($dateExplod[1]);
                                $month = $mois[$month];
                                $day_number = explode(" ", $dateExplod[2])[0];
                                $day = date('D', strtotime($date));

                                switch($day){
                                    case 'Mon' :
                                    $day = $jour[0];
                                    break;
                                    case 'Tue' :
                                    $day = $jour[1];
                                    break;
                                    case 'Wed' :
                                    $day = $jour[2];
                                    break;
                                    case 'Thu' :
                                    $day = $jour[3];
                                    break;
                                    case 'Fri' :
                                    $day = $jour[4];
                                    break;
                                    case 'Sat' :
                                    $day = $jour[5];
                                    break;
                                    case 'Sun' :
                                    $day = $jour[6];
                                    break;
                                }

                                echo" <div class='information_group -walk'><p class='information_title'>Nom de la balade</p><p class='information_space'>".$row['NAME']."</p></div>";
                                echo" <div class='information_group -walk'><p class='information_title'>Type de balade</p><p class='information_space'>".$row['WALK']."</p></div>";
                                echo" <div class='information_group -walk'><p class='information_title'>Date</p><p class='information_space'>".$day." ".$day_number." ".$month." ".$year."</p></div>";
                                echo" <div class='information_group -walk'><p class='information_title'>Ville</p><p class='information_space'>".$townToInsert."</p></div>";
                                echo" <div class='information_group -walk'><p class='information_title'>Adresse</p><p class='information_space'>".$row['LOCATION']."</p></div>";
                            ?>
                        </div>
                        <h3 class="information -first h3">Participants</h3>
                        <div class="participants">
                            <div class='information_group -walk'>
                                <h4 class="h4 information_title">Les maîtres</h4>
                            </div>
                            <div class="master">
                                <div class="master__container">
                                    <?php
                                        $query = $link->prepare("SELECT * FROM event_attendee WHERE ID_EVENT = ?");
                                        $query->bind_param("i", $id_event);
                                        $query->execute();

                                        $result = $query->get_result();
                                        $rows_attendee = resultToArray($result);

                                        foreach($rows_attendee as $attendee){
                                            $query = $link->prepare("SELECT * FROM user WHERE ID = ?");
                                            $query->bind_param("i", $attendee['ID_ATTENDEE']);
                                            $query->execute();

                                            $result = $query->get_result();
                                            $rows = resultToArray($result);

                                            foreach ( $rows as $master ) :?>
                                                    <div class="center">
                                                        <?php
                                                        echo "<h4 class='h4 dog_name'>".$master['USERNAME']."</h4>";
                                                        ?>
                                                        <?php

                                                        $filename = $master['AVATAR'];

                                                        if (file_exists($filename)){
                                                            echo '<img data-id="'.$master["ID"].'"class="dog_img view avatar -small -noMargin" src="'.$master['AVATAR'].'">';
                                                        }else{
                                                            echo '<img data-id="'.$master["ID"].'"class="dog_img view avatar -small -noMargin" src="src/assets/img/avatar/default.jpg">';

                                                        }?>
                                                    </div>
                                                <?php
                                            endforeach;
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class='information_group -walk'>
                                <h4 class="h4 information_title -top">Les chiens</h4>
                            </div>
                            <div class="chiens">
                                <div class="chien__container">
                            <?php
                                    $query = $link->prepare("SELECT * FROM event_dog WHERE ID_EVENT = ?");
                                    $query->bind_param("i", $id_event);
                                    $query->execute();

                                    $result = $query->get_result();
                                    $rows_attendee = resultToArray($result);
                                    foreach($rows_attendee as $attendee){
                                        $query = $link->prepare("SELECT * FROM dog WHERE ID = ? and ACTIVE = 1");
                                        $query->bind_param("i", $attendee['ID_DOG']);
                                        $query->execute();

                                        $result = $query->get_result();
                                        $rows = resultToArray($result);

                                        foreach ( $rows as $dog ) :?>
                                                <div class="center">
                                                    <?php
                                                    echo "<h4 class='h4 dog_name'>".$dog['NAME']."</h4>";
                                                    ?>
                                                    <?php
                                                    echo '<img class="dog_img avatar -small -noMargin" src="'.$dog['PICTURE'].'">';?>
                                                </div>
                                            <?php
                                        endforeach;
                                    }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                    $query = $link->prepare("SELECT * FROM event_attendee WHERE ID_ATTENDEE = ? AND ID_EVENT = ?");
                    $query->bind_param("ii", $_SESSION['ID'], $id_event);
                    $query->execute();

                    $result = $query->get_result();
                    if($result->num_rows === 0){
                        $row_user = $result->fetch_assoc();

                        $query = $link->prepare("SELECT * FROM dog WHERE OWNER_ID = ? and ACTIVE = 1");
                        $query->bind_param("i", $_SESSION['ID']);
                        $query->execute();

                        $result = $query->get_result();
                        $rows = resultToArray($result);
                        if($result->num_rows === 0){
                            echo '<p class="error -center">Vous devez avoir au moins un compagnon pour participer à une balade</p>';
                        }else{
                        ?>
                        <div class="submit__button">
                            <button class="button -color -blue" id="next" type="submit">Je participe</button>
                        </div>
                        <?php } ?>
                        <div class="walk__add__dog">
                        <?php
                            // On récupère les chiens
                            $query = $link->prepare("SELECT * FROM dog WHERE OWNER_ID = ? and ACTIVE = 1");
                            $query->bind_param("i", $_SESSION['ID']);
                            $query->execute();

                            $result = $query->get_result();
                            $rows = resultToArray($result);
                            ?>
                            <div class="walk__dog">
                                <h3 class="h3 information -top">Votre partenaire de balade</h3>
                                <p class="information -blue">Selectionner au moins un compagnon pour vous inscrire</p>
                                <div class="walk__dog__container">
                                    <?php
                                    foreach ( $rows as $dog ) :?>
                                    <div class="dog_card -walk" data-id="<?php echo $dog['ID']?>">
                                        <?php
                                        echo "<h4 class='h4 dog_name -walk'>".$dog['NAME']."</h4>";
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
                                    <button class="input button -color -blue -nomargin" data-id="<?php echo $id_event ?>" id="validate__sign">Je valide</button>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
</html>
