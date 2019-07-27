<?php
  @session_start();
  require_once("src/php/bdd.php");

  $pagename = 'Messages';

  if(!isset($_SESSION['ID'])){
    echo 0;
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
<script>
  userID = <?php echo $_SESSION['ID'] ?>;
</script>
<script src="src/scripts/message.js"></script>
