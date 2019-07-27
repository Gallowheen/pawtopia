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

    require_once("../lib/autoloader.php");

    use PubNub\PNConfiguration;
    use PubNub\Pubnub;

    $pnConfiguration = new PNConfiguration();

    $publish_key = "pub-c-07c00e21-e522-43ef-965d-f81e60f7a47f";
    $subscribe_key = "sub-c-1ef083d0-dc62-11e8-911d-e217929ad048";
    $secret_key = "sec-c-ZGRmN2ZkYjMtOTdhMi00ZWM5LTk0MzctZjg5MTg1MzVmZGMy";
    $pubnub = new Pubnub($publish_key, $subscribe_key, $secret_key);

    $user = $_GET['ID'];
    $message = $_GET['MESSAGE'];
    $statut = "Unread";

    //INSERT INTO `message`(`ID_USER1`, `ID_USER2`, `CONTENT`, `STATUT`) VALUES (?,?,?,?,?,?)
    $query = $link->prepare("INSERT INTO `message` (`ID_USER1`, `ID_USER2`, `CONTENT`, `STATUT`) VALUES (?,?,?,?)");
    $query->bind_param("iiss", $_SESSION['ID'], $_GET['ID'],$message,$statut);
    $query->execute();
    $lastid = mysqli_insert_id($link);

    // $query = $link->prepare("SELECT * FROM `message` WHERE ID = ?");
    // $query->bind_param("i", $lastid);
    // $query->execute();
    // $result = $query->get_result();
    // $row = $result->fetch_assoc();

    $query = $link->prepare("SELECT * FROM `message` WHERE ID_USER1 = ? AND ID_USER2 = ? ORDER BY DATE DESC LIMIT 1");
    $query->bind_param("ii", $_SESSION['ID'], $_GET['ID']);
    $query->execute();
    $result = $query->get_result();
    $row = resultToArray($result);

    foreach ($row as $message) :

        //var_dump($message);

        $now = new DateTime();
        $now->setTimezone(new DateTimeZone('Europe/Paris'));
        $dStart = $now->format('Y-m-d H:i:s');
        $dStart = new DateTime($message['DATE']);

        $now = new DateTime();
        $now->setTimezone(new DateTimeZone('Europe/Paris'));
        $dEnd = $now->format('Y-m-d H:i:s');
        $dEnd = new DateTime($dEnd);

        //var_dump($dEnd);

        $dDiff = $dEnd->diff($dStart);
        $time;
        //var_dump($dDiff);

        //var_dump($dDiff);
        if (($dDiff->format('%y')) > 0){
            $time = $dDiff->format('%y y');
        }else{
            if (($dDiff->format('%m')) > 0){
                $time = $dDiff->format('%m m');
            }else{
                if (($dDiff->format('%d')) > 0){
                    $time = $dDiff->format('%d d');
                }else{
                    if (($dDiff->format('%h')) > 0){
                        $time = $dDiff->format('%h h');
                    }else{
                        $time = ('Now');
                    }
                }
            }
        }

        $query = $link->prepare("SELECT * FROM user WHERE ID = ?");
        $query->bind_param("i", $_SESSION['ID']);
        $query->execute();
        $result = $query->get_result();
        $row_user = resultToArray($result);

        foreach ($row_user as $test){
            $USER_AVATAR;
            $USER_NAME;

            // echo $entry.'--';
            // echo $test['ID'];

            $USER_AVATAR = $test['AVATAR'];
            $USER_NAME = $test['USERNAME'];
        }

        //$dDiff = explode("-", $dDiff->format('%r%a'))[1];
        //echo $dDiff->format('%r%a'); // use for point out relation: smaller/greater
        //echo $dDiff->format('%a days');

    endforeach;

    foreach ($row as $cle => $user){
        $row[$cle]['USERNAME'] = $USER_NAME;
        $row[$cle]['AVATAR'] = $USER_AVATAR;
    }


    foreach ($row as $message){
        //$pubnub->publish('message' array('from' => 'test', 'message' => '<div data-id="'.$_SESSION['ID'].'" class="message__body"><div class="message__info"><img class="avatar -message" src="'.$message['AVATAR'].'"/><div class="message__name">'.$message['USERNAME'].'</div></div><div class="message__content">'.$message['CONTENT'].'</div><div class="message__date">'.$time.'</div></div>'));
        //$pubnub->publish({"from":"hello", "message": '<div data-id="'.$_SESSION['ID'].'" class="message__body"><div class="message__info"><img class="avatar -message" src="'.$message['AVATAR'].'"/><div class="message__name">'.$message['USERNAME'].'</div></div><div class="message__content">'.$message['CONTENT'].'</div><div class="message__date">'.$time.'</div></div>'});
        $pubnub->publish("message", '<div data-id="'.$_SESSION['ID'].'" class="message__body -hidden"><div class="message__info"><img class="avatar -message" src="'.$message['AVATAR'].'"/><div class="message__name">'.$message['USERNAME'].'</div></div><div class="message__content">'.$message['CONTENT'].'</div><div class="message__date">'.$time.'</div></div>');
    }


    //var_dump($row);

    //$pubnub->publish("message", $row);


?>
