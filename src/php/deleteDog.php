<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    $dog_id = $_GET['dog'];

    $query = $link->prepare("UPDATE `dog` SET `ACTIVE`= 0 WHERE ID = ?");
    $query->bind_param("i", $dog_id);
    $query->execute();
?>