<?php
    require_once("bdd.php");
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
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
    if($result->num_rows === 0){

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

        echo json_encode($valid_event);
    }
?>