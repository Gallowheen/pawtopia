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

    $user = array();
    $userID = $_SESSION['ID'];

    $USER_AVATAR;
    $USER_NAME;
    $USER_PID;

    $query = $link->prepare("SELECT * FROM message WHERE ID_USER1 = ? || ID_USER2 = ?  ORDER BY DATE DESC");
    $query->bind_param("ii", $_SESSION['ID'], $_SESSION['ID']);
    $query->execute();

    $result = $query->get_result();
    if($result->num_rows === 0){
        echo 'no msg';
    }else{
        $row = resultToArray($result); 
    }
    for ($i = 0; $i < count($row); $i++){
        if (count($user) > 0){
            $error = false;
            $userToAdd;
            for ($k = 0; $k < count($user); $k++){            
                if ($row[$i]['ID_USER1'] == $userID){
                    if($row[$i]['ID_USER2'] == $user[$k]){
                        $error = true;
                    }else{
                        $userToAdd = "USER1";
                    }
                }else{
                    if($row[$i]['ID_USER1'] == $user[$k]){
                        $error = true;
                    }else{
                        $userToAdd = "USER2";
                    }
                }
            }
            if(!$error){
                if($userToAdd == "USER1"){
                    array_push($user,$row[$i]['ID_USER2']);
                }else{
                    array_push($user,$row[$i]['ID_USER1']);
                }
            }
        }else{
            if ($row[$i]['ID_USER1'] != $userID) 
                array_push($user,$row[$i]['ID_USER1']);
            else
                array_push($user,$row[$i]['ID_USER2']);
        }
    }

    foreach ($user as $entry){

        $query__user = $link->prepare("SELECT * FROM user WHERE ID = ?");
        $query__user->bind_param("i", $entry);
        $query__user->execute();

        $result__query = $query__user->get_result();
        $row__query = resultToArray($result__query); 

        //var_dump($row__query);

        foreach ($row__query as $test){
            $USER_AVATAR;
            $USER_NAME;
            $USER_ID;

            // echo $entry.'--';
            // echo $test['ID'];
   
            $USER_AVATAR = $test['AVATAR'];
            $USER_NAME = $test['USERNAME'];
            $USER_PID = $test['ID'];
        }

        //var_dump($row);

        foreach ($row as $cle => $user){
            //echo $row[$cle]['ID_USER2'];
            // echo($row[$cle]['ID']."------");
            // echo $USER_ID."||";
            if ($row[$cle]['ID_USER1'] != $USER_PID ){
                
            }else{
                $row[$cle]['USERNAME'] = $USER_NAME;
                $row[$cle]['AVATAR'] = $USER_AVATAR;
            }

            if ($row[$cle]['ID_USER2'] != $USER_PID ){
                
            }else{
                $row[$cle]['USERNAME'] = $USER_NAME;
                $row[$cle]['AVATAR'] = $USER_AVATAR;
            }
        }   
    }

    echo json_encode($row)
    //echo json_encode($row)
?>