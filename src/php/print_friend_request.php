<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");
    
    
    $username1 = $_GET['ID'];
    $username2 = $_SESSION['ID'];

    $query = $link->prepare("SELECT * FROM user WHERE ID = ?");
    $query->bind_param("i", $username1);
    $query->execute();

    $result = $query->get_result();
    if($result->num_rows === 0){
        echo 'Error';
        $error = true;
        //redirect ?
    }
    $row = $result->fetch_assoc();

    $avatar_path = $row['AVATAR'];
    $username = $row['USERNAME'];
    $bio = $row ['BIO'];
    $walk = $row ['WALK'];

    echo '<img class="avatar avatar--top" src="'.$avatar_path.'?>"/>';
    echo '<p class="">'.$username.'</p>';
    echo '<p class="">'.$bio.'</p>';
    echo '<p class="">'.$walk.'</p>';

    echo "<button data-user='$username1' id='accept'>yes</button>";
    echo "<button data-user='$username1' id='refuse'>no</button>";

    echo '<p class="result"></p>';
?>