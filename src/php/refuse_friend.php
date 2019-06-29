<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    $username1 = $_GET['ID'];
    $username2 = $_SESSION['ID'];

    //On supprime la demande d'amis
    $query_user2_delete = $link->prepare("DELETE FROM `friends` WHERE ID_USER1 = ? AND ID_USER2 = ?");
    $query_user2_delete->bind_param("ii", $username1,$username2);
    $query_user2_delete->execute();
?>