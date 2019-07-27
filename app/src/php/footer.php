<?php
    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    // //Function to return table of result
    // function resultToArray($result) {
    //     $rows = array();
    //     while($row = $result->fetch_assoc()) {
    //         $rows[] = $row;
    //     }
    //     return $rows;
    // }
?>


<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="nav_container">
                <div class="nav_button_group home" data-url='home'>
                    <span class="link_footer -active"><i class="icon icon__footer icon-ic_home_48px"></i></span>
                    <span class="icon__name -active">Accueil</span>
                </div>
                <div class="nav_button_group profile" data-url='profile'>
                    <span class="link_footer"><i class="icon icon__footer icon-single-01"></i></span>
                    <span class="icon__name">Profil</span>
                </div>
                <div class="nav_button_group members" data-url='members'>
                    <span class="link_footer"><i class="icon icon__footer icon-multiple-11"></i></span>
                    <span class="icon__name">Membres</span>
                </div>
                <div class="nav_button_group walk" data-url='walk'>
                    <span class="link_footer"><i class="icon icon__footer icon-calendar-60"></i></span>
                    <span class="icon__name">Balades</span>
                </div>
                <div class="nav_button_group message" data-url='message'>
                <?php
                    $unread = 0;
                    $banList = array();

                    $query = $link->prepare("SELECT * FROM message WHERE ID_USER2 = ? OR ID_USER1 = ? ORDER BY DATE DESC");
                    $query->bind_param("ii", $_SESSION['ID'], $_SESSION['ID']);
                    $query->execute();
            
                    $result = $query->get_result();
                    if($result->num_rows === 0){
            
                    }else{
                      $row = resultToArray($result);
                      $error = false;
            
                      foreach ($row as $message){

                        //var_dump($message);
            
                        //echo $message['ID_USER1'];
                        if ($message['ID_USER1'] != $_SESSION['ID'] && $message['ID_USER2'] == $_SESSION['ID'] ){
            
                          //echo $message['CONTENT'].'<br>';
            
                          //echo $message['ID_USER1'];
            
                          $query = $link->prepare("SELECT * FROM message WHERE (ID_USER2 = ? && ID_USER1 = ?) OR (ID_USER2 = ? && ID_USER1 = ?) ORDER BY DATE DESC");
                          $query->bind_param("iiii", $_SESSION['ID'], $message['ID_USER1'], $message['ID_USER1'], $_SESSION['ID']);
                          $query->execute();
            
                          $result = $query->get_result();
                          if($result->num_rows === 0){
            
                          }else{
                            $row_check = resultToArray($result);
                          }
            
                          //var_dump($row_check);
            
                          if ($row_check[0]['ID_USER2'] != $message['ID_USER1']){
            
                            //echo $row_check[0]['ID_USER2'].' = '.$message['ID_USER1'].'<br>';
                            //var_dump($banList);
                            $error = false;
                            //echo count($banList);
                            if(count($banList) > 0){
                              //echo 'lol';
            
                              // foreach ($banList as $ban)
                              //   echo $ban;
            
                              for ($i = 0; $i < count($banList); $i++){
                                if($message['ID_USER1'] != $banList[$i]){
            
                                }else{
                                  //echo('erreur');
                                  $error = true;
                                }
                              }
            
                              if (!$error){
                                //echo $message['STATUT'];
            
                                if ($message['STATUT'] == "Unread"){
                                  $unread += 1;
                                }
                                //echo $unread;
                                array_push($banList,$message['ID_USER1']);
            
                                // foreach ($banList as $ban)
                                //   echo $ban;
                              }
                            }else{
            
                              //echo $message['ID_USER1'];
                              if ($message['STATUT'] == "Unread"){
                                $unread += 1;
                              }
                              //echo $unread;
                              array_push($banList,$message['ID_USER1']);
            
                              // foreach ($banList as $ban)
                              //   echo $ban;
                            }
                          }
                        }
            
                        // foreach ($banList as $ban)
                        //   echo $ban;
                      }
            
                      // foreach ($banList as $ban)
                      //   echo $ban;
                      //echo $unread;
                      if ($unread != 0)
                        echo '<span class="ping">'.$unread.'</span>';
                    }
                    ?>
                    <span class="link_footer"><i class="icon icon__footer icon__friend icon-ic_sms_48px"></i></span>
                    <span class="icon__name">Messages</span>
                </div>
            </div>
        </div>
    </div>
</footer>