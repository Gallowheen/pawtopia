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

$owner_name = $row['USERNAME'];
$name = $_GET['name'];

if (isset($_FILES['upload_file'])) {
    if(move_uploaded_file($_FILES['upload_file']['tmp_name'], "../../users/".$_SESSION['ID']."_".$owner_name.'/dogs/'.$name.'/'. $_FILES['upload_file']['name'])){
      
    } else {
 
    }
    exit;
} else {
    echo "No files uploaded ...";
}

?>