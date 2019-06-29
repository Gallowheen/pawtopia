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
    $unmutual = 0;
    $errors = array();
    $information = array();

    // chefk if user2 already invited user1
    $query_user2 = $link->prepare("SELECT * from friends WHERE ID_USER2 = ? AND ID_USER1 = ? LIMIT 1");
    $query_user2->bind_param("ii", $username2,$username1);
    $query_user2->execute();

    if($query_user2)
    {
        $result_user2 = $query_user2->get_result();

        //if user2 did not already invited user1
        if($result_user2->num_rows === 0){
            $query_user2_accept = $link->prepare("INSERT INTO `friends`(`ID_USER1`, `ID_USER2`, `MUTUAL`) VALUES (?,?,?)");
            $query_user2_accept->bind_param("iii", $username2,$username1,$unmutual);
            $query_user2_accept->execute();
        }
        // if user2 already invited user1
        else{
            // user 1 accept
            // ex : 44 45 1
            $query_user1_accept = $link->prepare("INSERT INTO `friends`(`ID_USER1`, `ID_USER2`, `MUTUAL`) VALUES (?,?,?)");
            $query_user1_accept->bind_param("iii", $username2,$username1,$mutual);
            $query_user1_accept->execute();

            // update user 2 request
            // ex : 45 44 1 ==> 45 44 0
            $query_user2_update = $link->prepare("UPDATE `friends` SET `MUTUAL`= ? WHERE ID_USER1 = ? AND ID_USER2 = ?");
            $query_user2_update->bind_param("iii", $mutual,$username1,$username2);
            $query_user2_update->execute();
        }
    }
?>