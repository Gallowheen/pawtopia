<?php
  @session_start();
  require_once("src/php/bdd.php");
  $link = mysqli_connect(HOST, USER, PWD, BASE);

  //Function to return table of result
  function resultToArray($result) {
    $rows = array();
    while($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
    return $rows;
  }

  if (empty($_GET))
    $user = $_SESSION['ID'];
  else
      $user = $_GET['ID'];

  $pagename = 'Messages';

  if(!isset($_SESSION['ID'])){
    echo 0;
    exit;
}
?>

<div class="container">
  <div class="row">
    <div class="col">
      <div class="">
        
      </div>
    </div>
  </div>
</div>
    <?php
    echo '<input placeholder="Recherchez dans vos amis" class="input -walk -nomargin message__input" list="friends" id="friends" name="friends" />';
    echo '<datalist id="friens">';
    foreach ( $friends_global as $option ) :
        echo $option;
        echo'<option data-value="'.$option->ID.'>'.$option->USERNAME.'" value="'.$option->USERNAME.'"</option>';
    endforeach;
    echo'</datalist>';
        ?>
<?php   
    $friends = [];
    $query_friend_mutual = $link->prepare("SELECT distinct(id_user1) FROM friends WHERE mutual = 1 AND (ID_USER2 = ? OR ID_USER1 = ?)");
    $query_friend_mutual->bind_param("ii", $user, $user);
    $query_friend_mutual->execute();

    $result_friend_mutual = $query_friend_mutual->get_result();
    $row_friends_mutual = resultToArray($result_friend_mutual);

    foreach ($row_friends_mutual as $friend) :
        if(!in_array($friend['id_user1'],$friends)){
            if($friend['id_user1'] != $user)
                array_push($friends, $friend['id_user1']);
        }
    endforeach;

    ?>
   
    <?php

  
   //get user's friendlist ( we get ID )

       //own profile
       if (empty($_GET)){
           if($result_friend_mutual->num_rows <= 1){
               echo "<p style='margin-left: 16px;margin-top: 16px;'>Aucun ami pour l'instant.</p><div class='discover'><button id='discover' class='button -color -blue'>Découvrez nos membres</button></div>";
           }else{
               echo '<div class="friend_widget_container">';
               $friends_global = [];
               foreach ($friends as $friend) :

                   $query_friend_info = $link->prepare("SELECT * FROM user WHERE ID = ? ORDER BY USERNAME ASC");
                   $query_friend_info->bind_param("i", $friend);
                   $query_friend_info->execute();

                   $result_friend_info = $query_friend_info->get_result();
                   if($result_friend_info->num_rows === 0){
                       //impossible
                   }
                   $row_friend_info = $result_friend_info->fetch_assoc();
                   array_push($friends_global, $row_friend_info);
               endforeach;

               $newArray = [];
               foreach($friends_global as $user)
               {
                   $newArray[$user['USERNAME']] = $user;
               }

               ksort($newArray);

               foreach ($newArray as $friend) :
                   echo '<div data-id="'.$friend['ID'].'" class="chat friend_widget -nomargin -newMessage">';
                   ?>
                   <img class="avatar -friendlist" src="<?php echo $friend['AVATAR']?>"/>
                   <?php
                   echo "<span class='friend_name -newMessage'>".$friend['USERNAME']."</span>";
                   echo "<p class='friend_bio'>".$friend['BIO']."</p>";?>
                   <?php
                   echo '</div>';
               endforeach;
               echo '</div>';
           }
       }
?>
<div class="new__message"><i class="icon icon-ic_sms_48px"></i></div>
<script>
  userID = <?php echo $_SESSION['ID'] ?>;
</script>
<script src="src/scripts/message.js"></script>
