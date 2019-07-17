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
  <!-- <button class='logout'>Déconnexion</button> -->
</div>

<script src="src/scripts/home.js"></script>
