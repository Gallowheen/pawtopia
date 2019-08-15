<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    @session_start();

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    //if user already made a review about the user
    $query_check_review = $link->prepare("SELECT * FROM reviews WHERE ID_USER = ? AND ID_REVIEWER = ?");
    $id_user = $_GET['ID'];
    $id_reviewer = $_SESSION['ID'];
    $query_check_review->bind_param("ii", $id_user, $id_reviewer);
    $query_check_review->execute();
    $result = $query_check_review->get_result();
    $row_cnt = $result->num_rows;

    if ($row_cnt != 0){
        echo 'failed';
    }else{
        $query_add_review = $link->prepare("INSERT INTO reviews (`ID_USER`, `ID_REVIEWER`, `NOTE`, `MESSAGE`, `DATE`) VALUES (?,?,?,?,?)");
        $id_user = $_GET['ID'];
        $id_reviewer = $_SESSION['ID'];
        $note = $_GET['NOTE'];
        $message = $_GET['MESSAGE'];
        $date = date("Y-m-d");

        $query_add_review->bind_param("iiiss", $id_user, $id_reviewer, $note, $message, $date);
        $query_add_review->execute();

        include('../../profile.php');
    }
?>