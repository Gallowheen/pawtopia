<?php
  @session_start();
  require_once("src/php/bdd.php");

  $pagename = 'Reviews';

  if(!isset($_SESSION['ID'])){
    echo 0;
    exit;
  }

  // $query = $link->prepare("SELECT * FROM user WHERE ID = ?");
  // $query->bind_param("i", $user);
  // $query->execute();

  // $result = $query->get_result();
  // if($result->num_rows === 0){
  //     echo 'Error';
  //     $error = true;
  //     //redirect ?
  // }
  // $row = $result->fetch_assoc();
?>

<div class="container">
  <div class="row">
    <div class="col">
      <div class="edit__profile">
        <!-- Le CSS écrit en dur ici est à placer dans des classes / selecteurs des fichiers CSS -->
        <div class='information_group'><p class='information_title'>Votre note</p>
          <div class='note_wrapper' style='display: flex; justify-content: center; align-items: center;'>
            <?php
            for($i=0; $i<5; $i++) {
            ?>
            <i class="icon friend__review -friend icon__friend icon-ic_favorite_48px" style='font-size:15vw; width:20%;-webkit-text-stroke: 2px;-webkit-text-stroke-color: #3c6382;color:transparent;'></i>
            <?php
            }
            ?>
          </div>
        </div>
        <div class='information_group'><p class='information_title'>Commentaire facultatif</p><textarea id="review_message" class='select -walk -nomargin' placeholder='Vous pouvez entrer un commentaire...'></textarea></div>
        <input class="button -color -blue" type='button' id="update" data-id="<?= $_GET['ID'] ?>" value='Enregistrer' />
      </div>
    </div>
  </div>
</div>
<script>
  userID = <?php echo $_SESSION['ID'] ?>;
</script>
<script src="src/scripts/review.js"></script>
