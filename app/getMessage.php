<?php
    header('Content-Type: text/html; charset=utf-8');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    require_once("src/php/bdd.php");
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


    $user2 = $_GET['ID'];

    $query = $link->prepare("SELECT * FROM user WHERE ID = ?");
    $query->bind_param("i", $_GET['ID']);
    $query->execute();

    $result = $query->get_result();
    $row = $result->fetch_assoc();

    $pagename = $row['USERNAME'] ;
?>

            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="message__viewer__container">
                            <div class="messages">
                                <?php
                                    $user = array();
                                    $userID = $_SESSION['ID'];

                                    $query = $link->prepare("SELECT * FROM message WHERE (ID_USER2 = ? AND ID_USER1 = ?) OR (ID_USER1 = ? AND ID_USER2 = ?)ORDER BY DATE DESC");
                                    $query->bind_param("iiii", $_SESSION['ID'], $_GET['ID'], $_SESSION['ID'], $_GET['ID']);
                                    $query->execute();

                                    $result = $query->get_result();
                                    $row = resultToArray($result);

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

                                    krsort($row);
                                    //var_dump($row);

                                    foreach ($row as $message) :
                                        $now = new DateTime();
                                        $now->setTimezone(new DateTimeZone('Europe/Paris'));
                                        $dStart = $now->format('Y-m-d H:i:s');
                                        $dStart = new DateTime($message['DATE']);

                                        $now = new DateTime();
                                        $now->setTimezone(new DateTimeZone('Europe/Paris'));
                                        $dEnd = $now->format('Y-m-d H:i:s');
                                        $dEnd = new DateTime($dEnd);

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
                                                        $time = ('< 1h');
                                                    }
                                                }
                                            }
                                        }
                                        //$dDiff = explode("-", $dDiff->format('%r%a'))[1];
                                        //echo $dDiff->format('%r%a'); // use for point out relation: smaller/greater
                                        //echo $dDiff->format('%a days');

                                        if ($message['ID_USER1'] != $_SESSION['ID']){
                                            echo '<div data-id="'.$message['ID_USER1'].'" class="message__body">';
                                            echo '<div class="message__info"><img class="avatar -message" src="'.$message['AVATAR'].'"/><div class="message__name">'.$message['USERNAME'].'</div></div>';
                                            echo '<div class="message__content">'.$message['CONTENT'].'</div>';
                                            echo '<div class="message__date">'.$time.'</div>';

                                            echo '</div>';
                                        }else{
                                            echo '<div data-id="'.$message['ID_USER1'].'" class="message__body -other">';
                                            echo '<div class="message__content -other">'.$message['CONTENT'].'</div>';
                                            echo '<div class="message__date">'.$time.'</div>';
                                            echo '</div>';
                                        }
                                    endforeach;
                                ?>
                            </div>
                        </div>
                        <input class="input -walk -message" placeholder="Envoyer votre message ..." type="text" id="message" name="message" required maxlength="256">
                        <i class="icon icon__up -right sendMessage"></i>
                    </div>
                </div>
            </div>
            <script>
                var user2 = <?= $user2; ?>;
                var userID = <?php echo $_SESSION['ID'] ?>;
            </script>
            <script src="src/scripts/getMessage.js"></script>
