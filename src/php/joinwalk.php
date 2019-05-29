<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    $user = $_SESSION['ID'];
    $dogs = $_GET['DOG'];
    $id_event = $_GET['ID'];

    //ADD THE CREATOR
    $query_add_event_attendee = $link->prepare("INSERT INTO event_attendee (`ID_EVENT`, `ID_ATTENDEE`) VALUES (?,?)");
    $query_add_event_attendee->bind_param("ii", $id_event, $user);
    $query_add_event_attendee->execute();
    
    //ADD THE DOG(S) OF THE CREATOR 
    foreach ($dogs as $dog){
        $query_add_event_dog = $link->prepare("INSERT INTO event_dog (`ID_EVENT`, `ID_DOG`) VALUES (?,?)");
        $query_add_event_dog->bind_param("ii", $id_event, $dog);
        $query_add_event_dog->execute();
    }

    if($query_add_event_attendee && $query_add_event_dog){
        echo "success";
    }
?>