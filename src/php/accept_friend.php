<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    //echo $_GET['ID'];

    $username1 = $_GET['ID'];
    $username2 = $_SESSION['ID'];
    $mutual = 1;
    $errors = array(); 

    //User2 devient ami avec User1
    $query_user2_accept = $link->prepare("INSERT INTO `friends`(`ID_USER1`, `ID_USER2`, `MUTUAL`) VALUES (?,?,?)");
    $query_user2_accept->bind_param("iii", $username2,$username1,$mutual);
    $query_user2_accept->execute();

    if($query_user2_accept)
    {
        //echo query_user2_accept;
    }
    else{
        array_push($errors, "something went wrong with user2");
    }

    //On update la demande
    $query_user1_accept = $link->prepare("UPDATE `friends` SET `MUTUAL`= ? WHERE ID_USER1 = ? AND ID_USER2 = ?");
    $query_user1_accept->bind_param("iii", $mutual,$username1,$username2);
    $query_user1_accept->execute();

    if($query_user1_accept)
    {
        //echo query_user1_accept;
    }
    else{
        array_push($errors, "something went wrong with user1");
    }

    if (count($errors) == 0) {
        echo "success";
    }else{
        echo "failed";
    }
?>