<?php
  require_once("src/php/bdd.php");
  session_start();

  $pagename = 'Messages';

  if(!isset($_SESSION)){
    header('index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
  <?php
      include ('src/php/head.php');
  ?>
  <body data-id='<?php echo $_SESSION['ID'] ?>' class="message">
    <?php
      include ('src/php/header.php');
    ?>
    <div class=content_container>
      <div class="container">
        <div class="row">
          <div class="col">
            <div class="message__container">

            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
    include ('src/php/footer.php');
    ?>
  </body>
  <script src="src/scripts/jquery-3.4.0.min.js"></script>
  <script src="src/scripts/bootstrap.min.js"></script>
  <script src="src/scripts/jquery.touchSwipe.min.js"></script>
  <script src="https://cdn.pubnub.com/sdk/javascript/pubnub.4.24.1.min.js"></script>
  <script src="src/scripts/app.js"></script>
</html>
