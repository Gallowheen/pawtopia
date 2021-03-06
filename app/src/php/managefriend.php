<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    $unit = "K";

    function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
            return ($miles * 1.609344);
            } else if ($unit == "N") {
            return ($miles * 0.8684);
            } else {
            return $miles;
            }
        }
    }

    $distanceMax = (int)$_GET['km'];

    if (isset($_GET['walk']))
        if ($_GET['walk'] !== 'undefined')
            $walk = $_GET['walk'];

    //var_dump($walk);

    if (!isset($walk)){
        $query = $link->prepare("SELECT AVATAR, USERNAME, TOWN_ID, ID FROM user WHERE ID != ? ORDER BY USERNAME");
        $query->bind_param("i", $_SESSION['ID']);
    }else{
        if(sizeof($walk) == 1){
            //echo ('lol');

            $firstwalk = $walk[0];

            //echo $firstwalk;
            $query = $link->prepare("SELECT AVATAR, USERNAME, TOWN_ID, ID FROM user WHERE WALK = ? AND ID != ? ORDER BY USERNAME");
            $query->bind_param("si", $firstwalk, $_SESSION['ID']);
        }else{

            $firstwalk = $walk[0];
            $secondwalk = $walk[1];

            if(sizeof($walk) == 2){
                $query = $link->prepare("SELECT AVATAR, USERNAME, TOWN_ID, ID FROM user WHERE WALK IN (?,?) AND ID != ? ORDER BY USERNAME");
                $query->bind_param("ssi", $firstwalk, $secondwalk, $_SESSION['ID']);
            }
            if(sizeof($walk) == 3){
                $firstwalk = $walk[0];
                $secondwalk = $walk[1];
                $thirdwalk = $walk[2];

                $query = $link->prepare("SELECT AVATAR, USERNAME, TOWN_ID, ID FROM user WHERE WALK IN (?,?,?) AND ID != ? ORDER BY USERNAME");
                $query->bind_param("sssi", $firstwalk, $secondwalk, $thirdwalk, $_SESSION['ID']);
            }
        }
        
        //$query->bind_param("ss", $walk, $date);
        
        // $query = $link->prepare("SELECT AVATAR, USERNAME, TOWN_ID, ID FROM user WHERE WALK = ? AND ID != ? ORDER BY USERNAME");
        // $query->bind_param("si", $walk, $_SESSION['ID']);
    }
    $query->execute();

    $result = $query->get_result();
    if($result->num_rows === 0){

    }
    $rows = array();

    while($e = $result->fetch_assoc() ){
        $rows[] = $e;
    }

    //var_dump($rows);

    $membresProches = [];
    $town1 = $_SESSION['TOWN_ID'];
    $lat1;
    $lon1;
    $lat2;
    $lon2;
    $town2;


    foreach($rows as $cle => $membre){

        //var_dump($membre);

        $query_town_1 = $link->prepare("SELECT LAT, LON FROM towns WHERE ID = ?");
        $query_town_1->bind_param("i", $town1);
        $query_town_1->execute();

        $result_town_1 = $query_town_1->get_result();
        $row_town_1 = $result_town_1->fetch_assoc();

        $lat1 = $row_town_1['LAT'];
        $lon1 = $row_town_1['LON'];

        $town2 = $membre['TOWN_ID'];

        $query_town_2 = $link->prepare("SELECT LAT, LON FROM towns WHERE ID = ?");
        $query_town_2->bind_param("i", $town2);
        $query_town_2->execute();

        $result_town_2 = $query_town_2->get_result();
        $row_town_2 = $result_town_2->fetch_assoc();

        $lat2 = $row_town_2['LAT'];
        $lon2 = $row_town_2['LON'];

        $distance = round(distance($lat1, $lon1, $lat2, $lon2, $unit));

        if ($distance <= $distanceMax){
            $membre['km'] = $distance;
            $membresProches[] = $membre;
        }
    }

    //var_dump($membresProches);

    $x = 1;
    $newArray = [];
    foreach($membresProches as $user)
    {
        if(isset($newArray[$user['km']])){
            $newArray[$user['km']. ".00".$x] = $user;
            $x = $x + 1;
        }else
            $newArray[$user['km']] = $user;
    }

    ksort($newArray);

    $newNewArray = [];
    foreach($newArray as $v)
    {
    $newNewArray[] = $v;
    }

    echo json_encode($newNewArray);
?>