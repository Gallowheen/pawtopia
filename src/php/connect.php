<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    $query = $link->prepare("SELECT * FROM user WHERE USERNAME = ?");
    $query->bind_param("s", $_POST['username']);
    $query->execute();

    $result = $query->get_result();
    if($result->num_rows === 0) exit('No rows');
    $row = $result->fetch_assoc();

    $username = $row['USERNAME'];
    $password = $row['PASSWORD'];
    $avatar = $row['AVATAR'];
 
    if( isset($_POST['username']) && isset($_POST['password']) ){
 
        if($_POST['username'] == $username && md5($_POST['password']) == $password){
            session_start();
            $_SESSION['user'] = $username;
            $_SESSION['avatar'] = $avatar;
            echo "Success";    
        }
        else{
            echo "Failed";
        }
    }
?>