<?php
    $host = "127.0.0.1";
    $user = "root";
    $mdp = "";
    $base = "pawtopia";

    $link = mysqli_connect($host,$user,$mdp,$base);
    mysqli_query($link, "SET NAMES UTF8");

    // Gallow / jjj
    $query = mysqli_query($link, "SELECT * FROM user WHERE USERNAME = '" . $_POST['username'] . "'");
    $row = mysqli_fetch_assoc($query);

    $username = $row['USERNAME'];
    $password = $row['PASSWORD'];
 
    if( isset($_POST['username']) && isset($_POST['password']) ){
 
        if($_POST['username'] == $username && $_POST['password'] == $password){
            session_start();
            $_SESSION['user'] = $username;
            echo "Success";    
        }
        else{
            echo "Failed";
        }
    }
?>