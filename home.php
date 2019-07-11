<?php
  @session_start();
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

  $pagename = 'Accueil';

  if(!isset($_SESSION)){
    header('index.php');
    exit;
  }
  $user = $_SESSION['ID'];
?>

<div class="container">
  <div class="row">
    <div class="col">
      <div class="statuts">
        <div class="status__icon">
        <?php
          $banList = array();

          $query_friend_invite = $link->prepare("SELECT distinct(id_user1) FROM friends WHERE mutual = 0 AND ID_USER2 = ?");
          $query_friend_invite->bind_param("i", $user);
          $query_friend_invite->execute();

          $result_friend_invite = $query_friend_invite->get_result();
          if($result_friend_invite->num_rows === 0){

          }else{
            echo '<span class="ping">'.$result_friend_invite->num_rows.'</span>';
          }
        ?>
          <div><i class="icon icon__friend icon-ic_people_48px"></i></div>
        </div>
        <div class="status__icon">
        <?php

        $unread = 0;

        $query = $link->prepare("SELECT * FROM message WHERE ID_USER2 = ? OR ID_USER1 = ? ORDER BY DATE DESC");
        $query->bind_param("ii", $_SESSION['ID'], $_SESSION['ID']);
        $query->execute();

        $result = $query->get_result();
        if($result->num_rows === 0){

        }else{
          $row = resultToArray($result);
          $error = false;

          foreach ($row as $message){

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

                $error = false;
                //echo count($banList);
                if(count($banList) > 0){
                  //echo 'lol';

                  // foreach ($banList as $ban)
                  //   echo $ban;

                  for ($i = 0; $i < count($banList); $i++){
                    if($message['ID_USER1'] != $banList[$i]){

                    }else{

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

          if ($unread != 0)
            echo '<span class="ping">'.$unread.'</span>';
        }
        ?>
          <div><i class="icon icon__message icon-ic_sms_48px"></i></div>
        </div>
      </div>
      <?php

      $query = $link->prepare("SELECT * FROM user WHERE ID = ?");
      $query->bind_param("i", $_SESSION['ID']);
      $query->execute();

      $result = $query->get_result();
      $row = $result->fetch_assoc();

      if($row['BIO'] == null || $row['WALK'] == null ){ ?>
      <h3 class="h3 -title -space">Attention : information</h3>
      <div class="reminder">
        <?php
          echo "<p class='information' >Vos informations ne sont pas complètes.</p><p class='information'>Certaines informations sont importantes pour votre visibilité auprès des autres utilisateurs.</p><p class='information -red'> Nous vous recommandons de compléter votre profil.</p>";
        ?>
      </div>
      <?php } ?>
      <h3 class="h3 -title -space">Vos balades à venir</h3>
      <div class="user_walk -home">
          <?php include("./src/php/get_user_walk.php"); ?>
      </div>
    </div>
  </div>
  <button class='logout'>Déconnexion</button>
</div>

<script src="src/scripts/home.js"></script>
