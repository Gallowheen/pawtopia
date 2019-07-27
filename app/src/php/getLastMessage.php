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

    //SELECT `ID`, `ID_USER1`, `ID_USER2`, `CONTENT`, `DATE`, `STATUT` FROM `message` WHERE 1
    $query = $link->prepare("SELECT * FROM `message` WHERE ID_USER1 = ? AND ID_USER2 = ? ORDER BY DATE DESC LIMIT 1");
    $query->bind_param("ii", $_SESSION['ID'], $_GET['ID']);
    $query->execute();
    $result = $query->get_result();
    $row = resultToArray($result);

    foreach ($row as $message) :
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
        //$dDiff = explode("-", $dDiff->format('%r%a'))[1];
        //echo $dDiff->format('%r%a'); // use for point out relation: smaller/greater
        //echo $dDiff->format('%a days');

        if ($message['ID_USER1'] != $_SESSION['ID']){
            echo '<div data-id="'.$message['ID_USER2'].'" class="message__body -bounce">';
            echo '<div class="message__info"><img class="avatar -message" src="'.$message['AVATAR'].'"/><div class="message__name">'.$message['USERNAME'].'</div></div>';
            echo '<div class="message__content">'.$message['CONTENT'].'</div>';
            echo '<div class="message__date">'.$time.'</div>';

            echo '</div>';
        }else{
            echo '<div data-id="'.$message['ID_USER1'].'" class="message__body -bounce -other">';
            echo '<div class="message__content -other">'.$message['CONTENT'].'</div>';
            echo '<div class="message__date">'.$time.'</div>';
            echo '</div>';
        }
    endforeach;
?>
