<?php 
  require_once("src/php/bdd.php");
  session_start();

  //Function to return table of result
  function resultToArray($result) {
    $rows = array();
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
  }

  $pagename = 'Accueil';

  if(!isset($_SESSION)){
    header('index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Pawtopia | Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,500,700|Fira+Sans:300,400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="src/styles/app.css">
    <link rel="stylesheet" type="text/css" href="src/styles/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="src/styles/sanitize.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/projets/tfe/beta/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/projets/tfe/beta/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/projets/tfe/beta/favicon-16x16.png">
    <link rel="manifest" href="/projets/tfe/beta/site.webmanifest">
    <link rel="mask-icon" href="/projets/tfe/beta/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/projets/tfe/beta/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="/projets/tfe/beta/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!-- Méta Google -->
    <meta name="title" content="Pawtopia" />
    <meta name="description" content="Pawtopia permet aux chiens et à leur maître de trouver des partenaires pour leurs balades." />

    <!-- Métas Facebook Opengraph -->
    <meta property="og:title" content="Pawtopia" />
    <meta property="og:description" content="Pawtopia permet aux chiens et à leur maître de trouver des partenaires pour leurs balades." />
    <meta property="og:url" content="https://dylanernoud.be/projets/tfe/beta/" />
    <meta property="og:image" content="https://dylanernoud.be/projets/tfe/beta/favicon.ico" />
    <meta property="og:type" content="website"/>

    <!-- Métas Twitter Card -->
    <meta name="twitter:title" content="Pawtopia" />
    <meta name="twitter:description" content="Pawtopia permet aux chiens et à leur maître de trouver des partenaires pour leurs balades." />
    <meta name="twitter:url" content="https://dylanernoud.be/projets/tfe/beta/" />
    <meta name="twitter:image" content="https://dylanernoud.be/projets/tfe/beta/favicon.ico" />
  </head>
  <body class="home"> 
    <?php 
      include ('src/php/header.php');
    ?>
    <div class=content_container>
      <div class="container">
        <div class="row">
          <div class="col">
            <div class="statuts">
              <div class="status__icon">
              <?php
                $banList = array();
                
                $query_friend_invite = $link->prepare("SELECT distinct(id_user1) FROM friends WHERE mutual = 0 AND ID_USER2 = ?");
                $query_friend_invite->bind_param("i", $user);
                $query_friend_invite->execute();

                $result_friend_invite = $query_friend_invite->get_result();
                if($result_friend_invite->num_rows === 0){
                  
                }else{
                  echo '<span class="ping">'.$result_friend_invite->num_rows.'</span>';
                }
              ?>
                <a href="friends.php"><i class="icon icon__friend icon-ic_people_48px"></i></a>
              </div>
              <div class="status__icon">
              <?php 

              $unread = 0;

              $query = $link->prepare("SELECT * FROM message WHERE ID_USER2 = ? OR ID_USER1 = ? ORDER BY DATE DESC");
              $query->bind_param("ii", $_SESSION['ID'], $_SESSION['ID']);
              $query->execute();
          
              $result = $query->get_result();
              if($result->num_rows === 0){

              }else{
                $row = resultToArray($result);
                $error = false;
            
                foreach ($row as $message){

                  //echo $message['ID_USER1'];
                  if ($message['ID_USER1'] != $_SESSION['ID'] && $message['ID_USER2'] == $_SESSION['ID'] ){

                    //echo $message['CONTENT'].'<br>';

                    //echo $message['ID_USER1'];
                    
                    $query = $link->prepare("SELECT * FROM message WHERE (ID_USER2 = ? && ID_USER1 = ?) OR (ID_USER2 = ? && ID_USER1 = ?) ORDER BY DATE DESC");
                    $query->bind_param("iiii", $_SESSION['ID'], $message['ID_USER1'], $message['ID_USER1'], $_SESSION['ID']);
                    $query->execute();
                
                    $result = $query->get_result();
                    if($result->num_rows === 0){

                    }else{
                      $row_check = resultToArray($result);
                    }

                    //var_dump($row_check);

                    if ($row_check[0]['ID_USER2'] != $message['ID_USER1']){

                      //echo $row_check[0]['ID_USER2'].' = '.$message['ID_USER1'].'<br>';

                      $error = false;
                      //echo count($banList);
                      if(count($banList) > 0){
                        //echo 'lol';

                        // foreach ($banList as $ban)
                        //   echo $ban; 

                        for ($i = 0; $i < count($banList); $i++){ 
                          if($message['ID_USER1'] != $banList[$i]){
                            
                          }else{
                          
                            $error = true;
                          }
                        }

                        if (!$error){
                          //echo $message['STATUT'];

                          if ($message['STATUT'] == "Unread"){
                            $unread += 1;
                          }
                          //echo $unread;
                          array_push($banList,$message['ID_USER1']);
  
                          // foreach ($banList as $ban)
                          //   echo $ban; 
                        }
                      }else{

                        //echo $message['ID_USER1'];
                        if ($message['STATUT'] == "Unread"){
                          $unread += 1;
                        }
                        //echo $unread;
                        array_push($banList,$message['ID_USER1']);
                        
                        // foreach ($banList as $ban)
                        //   echo $ban; 
                      }
                    } 
                  } 

                  // foreach ($banList as $ban)
                  //   echo $ban; 
                }

                // foreach ($banList as $ban)
                //   echo $ban; 

                echo '<span class="ping">'.$unread.'</span>';
              }
              ?>
                <a href="message.php"><i class="icon icon__friend icon-ic_sms_48px"></i></a>
              </div>
            </div>
            <h3 class="h3 -title -space">Vos balades à venir</h3>
              <div class="user_walk">
                  
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
  <script src="src/scripts/app.js"></script>
</html>
