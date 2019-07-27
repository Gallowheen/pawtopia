<?php
    @session_start();

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");


    $query = $link->prepare("SELECT * FROM towns WHERE ID = ?");
    $query->bind_param("i", $_SESSION['TOWN_ID']);
    $query->execute();

    $result = $query->get_result();
    if($result->num_rows){
        $row = $result->fetch_assoc();
        $_SESSION['LAT'] = $row['LAT'];
        $_SESSION['LON'] = $row['LON'];
        echo json_encode($row);
    }
?>