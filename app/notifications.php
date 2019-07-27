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

  $pagename = 'Notifications';

  if(!isset($_SESSION['ID'])){
    echo 0;
    exit;
  }
  $user = $_SESSION['ID'];
?>

<div class="container">
  <div class="row">
    <div class="col">
        <h1>hELLO</h1>
    </div>
  </div>
</div>

<script src="src/scripts/notifications.js"></script>
