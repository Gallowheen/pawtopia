<?php
    header('Content-Type: text/html; charset=utf-8');
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

    //$distanceMax = (int)$_GET['km'];

    if (isset($_GET['walk']))
        if ($_GET['walk'] !== 'undefined')
            $walk = $_GET['walk'];
    if (isset($_GET['date']))
        if ($_GET['date'] !== 'undefined')
            $date = $_GET['date'];

    // echo $date;

    if (isset($walk)){

        if(sizeof($walk) == 1){
            //echo ('lol');

            $firstwalk = $walk[0];

            $query = $link->prepare("SELECT * FROM event WHERE WALK = ? AND DATE_START > ? ORDER BY NAME");
            $query->bind_param("ss", $firstwalk, $date);
        }else{

            $firstwalk = $walk[0];
            $secondwalk = $walk[1];

            if(sizeof($walk) == 2){
                $query = $link->prepare("SELECT * FROM event WHERE WALK IN (?,?) AND DATE_START > ? ORDER BY NAME");
                $query->bind_param("sss", $firstwalk, $secondwalk, $date);
            }
            if(sizeof($walk) == 3){
                $firstwalk = $walk[0];
                $secondwalk = $walk[1];
                $thirdwalk = $walk[2];

                $query = $link->prepare("SELECT * FROM event WHERE WALK IN (?,?,?) AND DATE_START > ? ORDER BY NAME");
                $query->bind_param("ssss", $firstwalk, $secondwalk, $thirdwalk, $date);
            }
        }
        
        //$query->bind_param("ss", $walk, $date);
    }else{
        $query = $link->prepare("SELECT * FROM event WHERE DATE_START > ? ORDER BY NAME");
        $query->bind_param("s", $date);
    }
    $query->execute();

    $result = $query->get_result();
    if($result->num_rows === 0){

    }
    $rows = array();

    while($e = $result->fetch_assoc() ){
        $rows[] = $e;
    }

    $membresProches = [];
    $town1 = $_SESSION['TOWN_ID'];

    $lat1 = $_GET['LAT'];
    $lon1 = $_GET['LON'];

    $lat2;
    $lon2;

    $town2;

    //var_dump($rows);

    foreach($rows as $cle => $membre){

        $lat2 = $membre['LAT'];
        $lon2 = $membre['LON'];

        // echo "lat1 et lon1\n";
        // echo $lat1 +"\n";
        // echo $lon1 +"\n";
        // echo "lat2 et lon2\n";
        // echo $lat2 +"\n";
        // echo $lon2 +"\n";

        $distance = round(distance($lat1, $lon1, $lat2, $lon2, $unit));

        //echo $distance;

        $membre['km'] = $distance;
        $membresProches[] = $membre;
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
    //echo json_encode($rows);
?>