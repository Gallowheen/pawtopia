<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");


    //reminder
    //INSERT INTO `event`(`ID_OWNER`, `NAME`, `TOWN`, `LOCATION`, `TYPE`, `DATE`, `LENGTH`)


    //ID_OWNER
    $id_owner = $_GET['ID_OWNER'];
    //$id_owner = 44;

    //EVENT NAME
    $name = $_GET['NAME'];
    //$name = 'Balade de Gallow';

    //TOWN
    $town_id = $_GET['TOWN_ID'];
    //$town_id = 136;

    //LOCATION
    $location = $_GET['LOCATION'];
    //$location = 'Parc de la boverie';

    //Type de balade
    $type = $_GET['TYPE'];
    //$type = 'Découverte';

    //Type de balade
    $date = $_GET['DATE'];
    //$date = '2019-05-16 18:00:00';

    //Longeur de la balade
    $length = $_GET['LENGTH'];
    //$length = "3 heures";


    //INSERT
    $query_add_event = $link->prepare("INSERT INTO event (`ID_OWNER`, `NAME`, `TOWN_ID`, `LOCATION`, `TYPE`, `DATE`, `LENGTH`) VALUES (?,?,?,?,?,?,?)");
    $query_add_event->bind_param("isissss", $id_owner, $name, $town_id, $location, $type, $date, $length);
    $query_add_event->execute();

    if($query_add_event){
        echo 'success';
    }
?>