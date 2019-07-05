<?php
  require_once("src/php/bdd.php");
  session_start();

  $pagename = 'Messages';

  if(!isset($_SESSION)){
    header('index.php');
    exit;
}
?>

<div class="container">
  <div class="row">
    <div class="col">
      <div class="message__container">
      </div>
    </div>
  </div>
</div>

<script src="src/scripts/message.js"></script>
