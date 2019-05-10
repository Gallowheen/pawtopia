<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    $friend_id = $_GET['friend'];

    $query1 = $link->prepare("DELETE FROM `friends` WHERE ID_USER1 = ? AND ID_USER2 = ?");
    $query1->bind_param("ii", $friend_id, $_SESSION['ID']);
    $query1->execute();

    $query2 = $link->prepare("DELETE FROM `friends` WHERE ID_USER1 = ? AND ID_USER2 = ?");
    $query2->bind_param("ii", $_SESSION['ID'], $friend_id);
    $query2->execute();   
?>