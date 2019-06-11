<?php 
    header('Content-Type: text/html; charset=utf-8');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    //Function to return table of result
        function resultToArray($result) {
        $rows = array();
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }


    $user = $_GET['ID'];

    echo $_SESSION['ID'];
    echo $_GET['ID'];
  
    //UPDATE `message` SET `ID`=[value-1],`ID_USER1`=[value-2],`ID_USER2`=[value-3],`CONTENT`=[value-4],`DATE`=[value-5],`STATUT`=[value-6] WHERE 1
    $query = $link->prepare("UPDATE `message` SET `STATUT`='Read' WHERE (ID_USER2 = ? AND ID_USER1 = ?)");
    $query->bind_param("ii", $_SESSION['ID'], $_GET['ID']);
    $query->execute();
?>
                                    