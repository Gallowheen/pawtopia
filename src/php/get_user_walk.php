<?php
    require_once("bdd.php");
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    @session_start();
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");
    $page = (isset($_GET['page']) ? $_GET['page'] : "home");

    //$town1 = $_SESSION['TOWN_ID'];

    //$query_town_3 = $link->prepare("SELECT NAME FROM towns WHERE ID = ?");
    //$query_town_3->bind_param("i", $town1);
    //$query_town_3->execute();
    //$result_town_3 = $query_town_3->get_result();
    //$row_town_3 = $result_town_3->fetch_assoc();
    //$town_name = $row_town_3['NAME'];

    $town = array();
    $town_name = array();

    $query = $link->prepare("SELECT * FROM event_attendee WHERE ID_ATTENDEE = ?");
    $query->bind_param("i", $_SESSION['ID']);
    $query->execute();

    $result = $query->get_result();
    $html = "";
    if($result->num_rows === 0){
        if($page == "home") {
            $html .= "<p class='information'>Vous n'êtes inscrit à aucune balades pour le moment !</p><div class='center'><a href='walk.php'><button class='button -color'>Découvrez les balades</button></a></div>";
        }
        else {
            $html .= "<img class='map__img' src='src/assets/img/ressources/no_walk.png'/>";
        }
    }else{
        $rows = resultToArray($result);

        //echo json_encode($rows);

        $get_event = array();
        $valid_event = array();

        foreach ( $rows as $test ){

            $query = $link->prepare("SELECT * FROM event WHERE ID = ?");
            $query->bind_param("i", $test['ID_EVENT']);
            $query->execute();

            $result = $query->get_result();
            if($result->num_rows === 0){

            }else{
                $rows_event = resultToArray($result);

                foreach ($rows_event as $event){
                    array_push($get_event,$event);
                }
            }
            //var_dump($rows_event);
        }

        // foreach ($town as $city){
        //     $query_town = $link->prepare("SELECT * FROM towns WHERE ID = ?");
        //     $query_town->bind_param("i", $city);
        //     $query_town->execute();
        //     $result_town = $query_town->get_result();
        //     $row_town = $result_town->fetch_assoc();

        //     array_push($town_name,$row_town['NAME']);
        // }

        foreach ($get_event  as $event){
            //echo $event['ID'];
            if ($event['DATE_START'] > date("Y-m-d")){
                array_push($valid_event,$event);
                // $valid_event[] = $event;
            }
        }

        // foreach ($valid_event as $cle => $event){
        //     $valid_event[$cle]['town_name'] = $town_name[$cle];
        // }

        // echo json_encode($valid_event);
        $data = $valid_event;

        //savoir le jour
        $jour = [
            0 => "Lundi",
            1 => "Mardi",
            2 => "Mercredi",
            3 => "Jeudi",
            4 => "Vendredi",
            5 => "Samedi",
            6 => "Dimanche",
        ];

        //savoir le mois
        $mois = [
            0 => "Janvier",
            1 => "Février",
            2 => "Mars",
            3 => "Avril",
            4 => "Mai",
            5 => "Juin",
            6 => "Juillet",
            7 => "Août",
            8 => "Septembre",
            9 => "Octobre",
            10 => "Novembre",
            11 => "Décembre",
        ];

        if (count($data)){

            $html .= '<div class="walk__container">';

            for($i = 0; $i < count($data); $i++){
                // Ceci ne marchera pas en php, les dates sont gérées autrement, fix dans le prochain commit

                /*$date = new Date($data[$i]['DATE_START']);
                $hourSplit = explode(':', explode(' ', $data[$i]['DATE_START'][1]));
                $hour = $hourSplit[0] . ":" . $hourSplit[1];
                $day = $jour[$date.getDay()];
                $monthNumber = $date.getMonth()+1;
                $month = $mois[$date.getMonth()];
                $dayNumber = $date.getDate();
                $year = $date.getFullYear();
                //$day . " " . $dayNumber . " " . $month . " " . $hour
                */
                $date = '';

                $walk = '<div class="name__container">
                            <span>' . $data[$i]['NAME'] . '</span>
                        </div>
                        <div class="address__container">' . $data[$i]['ROAD'] . ' ' . $data[$i]['CITY'] . '</div>
                        <div class="date__container">
                            <span class="">' . $date . '</span>
                        </div>
                        <div class="button__container -preview">
                            <i class="icon icon__up -right get_to_walk" data-id=' . $data[$i]['ID'] . '></i>
                            <span class="learn__more">En savoir plus</span>
                        </div>';
                // A rajouter à la ligne du dessus pour repasser à l'ancienne version
                //+'<span class="walk__name">'+data[i]['LENGTH']+' heures</span><span>'+data[i]['WALK']+'</span></div><div class="town__container"><i class="icon home icon-ic_home_48px"></i><span class="">'+data[i]['LOCATION']+'</span></div><div class="align-right"><div class="button__container"><button class="button -color -blue -round -walk get_to_walk" data-id='+data[i]['ID']+'>En savoir plus</button></div></div>'
                $html .= '<div class="walk__card -home test">' . $walk . '</div>';
            }
            $html .= '</div>';
        }
    }
    echo $html;
?>