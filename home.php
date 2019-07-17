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

  if(!isset($_SESSION['ID'])){
    echo 0;
    exit;
  }
  $user = $_SESSION['ID'];
?>

<div class="container">
  <div class="row">
    <div class="col">
      <div class="statuts">
        <div class="status__icon">
          <div><i class="icon icon-ic_notifications_48px"></i></div>
        </div>
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
      <div class="user__walk__action">
        <h3 class="h3 -title">Vos balades à venir</h3>
        <div class="icon__action__container">
          <i class="icon -selected icon__action carousel icon-ic_view_carousel_48px"></i>
          <i class="icon icon__action list icon-ic_view_list_48px"></i>
        </div>
      </div>
      <div class="user_walk -home">
        <?php include("./src/php/get_user_walk.php"); ?>
      </div>
    </div>
  </div>
  <button class='logout'>Déconnexion</button>
</div>

<script src="src/scripts/home.js"></script>
