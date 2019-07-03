<?php
    header('Content-Type: text/html; charset=utf-8');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    
    $query = $link->prepare("SELECT * FROM towns WHERE ID = ?");
    $query->bind_param("i", $_SESSION['TOWN_ID']);
    $query->execute();

    $result = $query->get_result();
    if($result->num_rows === 0){
        //impossible
    }else{
        $row = $result->fetch_assoc();
        
        echo json_encode($row);
    }
?>