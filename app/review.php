<?php
  @session_start();
  require_once("src/php/bdd.php");
  $link = mysqli_connect(HOST, USER, PWD, BASE);

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
  <div class="row centered_form -review">
    <div class="col">
      <div class="edit__profile">
        <div class='information_group'><p class='information_title'>Votre note</p>
          <div class='note_wrapper'>
            <?php
            for($i=0; $i<5; $i++) {
            ?>
            <i class="icon -review icon-review review__icon review__icon<?php echo $i ?>"></i>
            <?php
            }
            ?>
          </div>
        </div>
        <?php 

          $query = $link->prepare("SELECT USERNAME FROM user WHERE ID = ?");
          $query->bind_param("i", $_GET['ID']);
          $query->execute();

          $result = $query->get_result();
          $row = $result->fetch_assoc();

        ?>
        <div class='information_group'><p class='information_title'>Commentaire facultatif</p><textarea id="review_message" class='select -walk -nomargin' placeholder='Vous pouvez entrer un commentaire...'></textarea></div>
        <input class="button -color -blue" type='button' id="update" data-id="<?= $_GET['ID'] ?>" data-name="<?= $row['USERNAME'] ?>" value='Enregistrer' />
      </div>
    </div>
  </div>
</div>
<script>
  userID = <?php echo $_SESSION['ID'] ?>;
</script>
<script src="src/scripts/review.js"></script>
