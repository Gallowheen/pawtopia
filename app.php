<?php
  session_start();
  require_once("src/php/bdd.php");

  $pagename = 'Accueil';

  if(!isset($_SESSION['ID'])){
      header('Location:index.php');
      exit;
  }
?>

<!DOCTYPE html>
<html>
  <?php
      include ('src/php/head.php');
  ?>
  <body class="home">
    <script>
        var userID = <?php echo $_SESSION['ID']; ?>;
    </script>
    <?php
      include ('src/php/header.php');
    ?>
    <div class="content_container">
        <!-- Contenu des pages ici -->
        <?php
            include ('home.php');
        ?>
    </div>
    <?php
    include ('src/php/footer.php');
    ?>
  </body>
  <script src="src/scripts/lib/bootstrap.min.js"></script>
  <script src="src/scripts/lib/jquery.touchSwipe.min.js"></script>
  <script src="https://cdn.pubnub.com/sdk/javascript/pubnub.4.24.1.js"></script>
  <script src="src/scripts/app.js"></script>
  <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
  integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
  crossorigin=""></script>
  <script src="https://unpkg.com/esri-leaflet@2.2.4/dist/esri-leaflet.js"
  integrity="sha512-tyPum7h2h36X52O2gz+Pe8z/3l+Y9S1yEUscbVs5r5aEY5dFmP1WWRY/WLLElnFHa+k1JBQZSCDGwEAnm2IxAQ=="
  crossorigin=""></script>
  <script src="https://unpkg.com/esri-leaflet-geocoder@2.2.14/dist/esri-leaflet-geocoder.js"
  integrity="sha512-uK5jVwR81KVTGe8KpJa1QIN4n60TsSV8+DPbL5wWlYQvb0/nYNgSOg9dZG6ViQhwx/gaMszuWllTemL+K+IXjg=="
  crossorigin=""></script>
</html>
