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
if (isset($_GET['name']))
    $name = $_GET['name'];

$page = $_GET['page'];
echo $_FILES['upload_file']['tmp_name'];

if($page == "dog"){
    if (isset($_FILES['upload_file'])) {

        //CREATE NEW DIR
        $filename = "../../users/" . $_SESSION['ID'] . "_" . $owner_name.'/dogs/'.$name;
        if (!file_exists($filename)) {
            $newDirectory = "../../users/" . $_SESSION['ID'] . "_" . $owner_name.'/dogs/'.$name;
            mkdir($newDirectory, 0777);
        }

        if(move_uploaded_file($_FILES['upload_file']['tmp_name'], "../../users/".$_SESSION['ID']."_".$owner_name.'/dogs/'.$name.'/'. $_FILES['upload_file']['name'])){
        
        } else {
    
        }
        exit;
    } else {
        echo "No files uploaded ...";
    }
}else{
    echo "lol";
    echo $_FILES['upload_file']['tmp_name'];
    if (isset($_FILES['upload_file'])) {
        if(move_uploaded_file($_FILES['upload_file']['tmp_name'], "../../users/".$_SESSION['ID']."_".$owner_name.'/avatar/'. $_FILES['upload_file']['name'])){
            
        } else {
    
        }
        exit;
    } else {
        echo "No files uploaded ...";
    }
}

?>