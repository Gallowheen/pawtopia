<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    $query = $link->prepare("SELECT USERNAME FROM user WHERE ID = ?");
    $query->bind_param("i", $_SESSION['ID']);
    $query->execute();

    $result = $query->get_result();
    $row = $result->fetch_assoc();

    $username = $row['USERNAME'];
    $bio = $_GET['bio'];
    $picture = $_GET['image'];
    $walk = $_GET['walk'];
    $town = $_GET['town'];

    echo $bio;
    echo $picture;
    echo $walk;
    echo $town;

    if ($picture === "none"){
        $query = $link->prepare("UPDATE `user` SET `TOWN_ID` = ?, `BIO` = ?, `WALK` = ? WHERE ID = ?");
        $query->bind_param("issi", $town, $bio, $walk, $_SESSION['ID']);
        $query->execute();
    }else{
        $picture = 'users/'.$_SESSION['ID'].'_'.$username.'/avatar/'.$picture;
        $filename = "../../users/" . $_SESSION['ID'] . "_" . $username.'/avatar/';

        //CREATE NEW DIR
        if (!file_exists($filename)) {
            $newDirectory = "../../users/" . $_SESSION['ID'] . "_" . $username.'/avatar/';
            mkdir($newDirectory, 0777);
        }

        $query = $link->prepare("UPDATE `user` SET `TOWN_ID`= ? , `BIO` = ?, `AVATAR` = ? ,`WALK` = ? WHERE ID = ?");
        $query->bind_param("isssi", $town, $bio, $picture, $walk, $_SESSION['ID']);
        $query->execute();
    }

    $_SESSION['TOWN_ID'] = $town;

?>