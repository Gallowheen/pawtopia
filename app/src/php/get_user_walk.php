<?php
    require_once("bdd.php");
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    @session_start();
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

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
        if($pagename == "Accueil") {
            $html .= "<img class='nowalk' src='/app/src/assets/img/ressources/no_walk.png'/><div class='center'><button id='walk' class='button -color -blue'>Découvrez les balades</button></div>";
        }
        else {
            $html .= "<img class='map__img' src='/app/src/assets/img/ressources/no_walk.png'/>";
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
        $jourLibelles = [
            1 => "Lundi",
            2 => "Mardi",
            3 => "Mercredi",
            4 => "Jeudi",
            5 => "Vendredi",
            6 => "Samedi",
            0 => "Dimanche",
        ];

        //savoir le mois
        $moisLibelles = [
            1 => "Janvier",
            2 => "Février",
            3 => "Mars",
            4 => "Avril",
            5 => "Mai",
            6 => "Juin",
            7 => "Juillet",
            8 => "Août",
            9 => "Septembre",
            10 => "Octobre",
            11 => "Novembre",
            12 => "Décembre",
        ];

        if (count($data)){

            $html .= '<div class="walk__container">';
            for($i = 0; $i < count($data); $i++){
                $date = date(strtotime($data[$i]['DATE_START']));
                $mois = $moisLibelles[date("n", $date)];
                $jour = $jourLibelles[date("w", $date)];
                $date = $jour . " ". date('j', $date) . " " . $mois . " " . date('H:i', $date);

                $walk = '<div class="walk__background"></div>
                <div class="walk__contrast">
                    <div class="name__container -home">
                        <span>' . $data[$i]['NAME'] . '</span>
                    </div>
                    <div class="walk__bottom">
                        <div class="address__container -home">' . $data[$i]['ROAD'] . ' ' . $data[$i]['CITY'] . '</div>
                        <div class="date__container -home">
                            <span class="">' . $date . '</span>
                        </div>
                    </div>
                </div>';
                // A rajouter à la ligne du dessus pour repasser à l'ancienne version
                //+'<span class="walk__name">'+data[i]['LENGTH']+' heures</span><span>'+data[i]['WALK']+'</span></div><div class="town__container"><i class="icon home icon-ic_home_48px"></i><span class="">'+data[i]['LOCATION']+'</span></div><div class="align-right"><div class="button__container"><button class="button -color -blue -round -walk get_to_walk" data-id='+data[i]['ID']+'>En savoir plus</button></div></div>'
                $html .= '<div class="walk__card -home get_to_walk" data-id=' . $data[$i]['ID'] . '">' . $walk . '</div>';
            }
            $html .= '</div>';
        }
        else
        {
            $html .= "<img class='map__img' src='app/src/assets/img/ressources/no_walk.png'/>";
        }
    }
    echo $html;
?>