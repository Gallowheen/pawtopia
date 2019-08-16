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
<div class="notification__container">
  <div class="container">
    <div class="row">
      <div class="col">
        <?php
        //check if friend invite
        $friends = [];
        $friends_pending = [];
        $friends_invite= [];

        if (!empty($_GET)){
            //other profile, we don't have to see what's going on on their channel
        }
        else{
            $query_friend_invite = $link->prepare("SELECT distinct(id_user1) FROM friends WHERE mutual = 0 AND ID_USER2 = ?");
            $query_friend_invite->bind_param("i", $user);
            $query_friend_invite->execute();

            $result_friend_invite = $query_friend_invite->get_result();
            if($result_friend_invite->num_rows === 0){
              
            }else{
              $row_friends_pending = resultToArray($result_friend_invite);
              $friend__invite__number = count($row_friends_pending);

              echo '<div class="notification__wrapper -flex notification__friend__icon">';
                echo "<div><i class='icon icon-ic_people_48px'></i><span class='notification__title'><span class='notification__number'>".$friend__invite__number."</span> demande d'amis en attente</span></div>"; 
                echo "<i class='icon notification__icon'></i>";
              echo '</div>';  
            }
        }
        ?>
        <div class="notification__wrapper -flex">
          <div>
            <i class='icon icon-calendar-60'></i>
            <span class='notification__title'>Un utilisateur a rejoint votre balade !</span>
          </div>
          <i class='icon notification__icon'></i>
        </div>
        <div class="notification__wrapper -flex">
          <div>
          <i class='icon icon-review'></i>
          <span class='notification__title'>Un utilisateur a laissé votre avis sur vous !</span>
          </div>
            <i class='icon notification__icon'></i>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="src/scripts/notifications.js"></script>
