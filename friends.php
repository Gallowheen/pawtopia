<?php 
    require_once("src/php/bdd.php");
    session_start();
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
    $pagename = 'Mes amis';

    // Récupérer l'utilisateur
    $user;
    if (empty($_GET))
        $user = $_SESSION['ID'];
    else
        $user = $_GET['ID'];

    if($user) {
        $query = $link->prepare("SELECT * FROM user WHERE ID = ?");
        $query->bind_param("i", $user);
        $query->execute();

        $result = $query->get_result();
        if($result->num_rows === 0){
            echo 'Error';
            $error = true;
            //redirect ?
        }
        $row = $result->fetch_assoc();
    }  
    //

    //Récupérer ses amis
    $friends = [];
    $friends_pending = [];
    $friends_invite= [];

    $query_friend_mutual = $link->prepare("SELECT distinct(id_user1) FROM friends WHERE mutual = 1 AND (ID_USER2 = ? OR ID_USER1 = ?) LIMIT 6");
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
    //
    
    $error = false;
?>

<!DOCTYPE html>
<html>
  <head>
    <title>DWMA project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,500,700|Fira+Sans:300,400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="src/styles/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="src/styles/app.css">
    <link rel="stylesheet" type="text/css" href="src/styles/sanitize.css">
    <style>
        
    </style>

    </head>
    <body>
        

        <?php 
        include ('src/php/header.php');
        if ($error){ ?>
        <div class="error__container">
            <div class="container">
                <div class="row">
                    <h2>Impossible de trouver cet utilisateur</h2>
                </div>
            </div>
        </div>
        <?php    
        }else{?>
        <div class="content_container">
            <div class="friend_pending">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <?php

                            if (!empty($_GET)){
                                //other profile, we don't have to see what's going on on their channel
                            }
                            else{
                                echo "<h3 class='information -first'>Invitation reçue</h3>";
                                $query_friend_invite = $link->prepare("SELECT distinct(id_user1) FROM friends WHERE mutual = 0 AND ID_USER2 = ?");
                                $query_friend_invite->bind_param("i", $user);
                                $query_friend_invite->execute();

                                $result_friend_invite = $query_friend_invite->get_result();
                                if($result_friend_invite->num_rows === 0){
                                    echo '<p>Aucune demande en attente</p>';
                                }else{
                                    $row_friends_pending = resultToArray($result_friend_invite);  
                                    
                                    foreach ($row_friends_pending as $friend) :       
                                        if(!in_array($friend['id_user1'],$friends_pending)){
                                            if($friend['id_user1'] != $user)
                                                array_push($friends_invite, $friend['id_user1']);    
                                        }   
                                    endforeach;

                                    foreach ($friends_invite as $friend) :           
                                        echo "<button class='handle_friend' data-user='$friend'>$friend</button>";
                                    endforeach;
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="friend_list">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <?php
                                if ((count($row_friends_mutual)-1) < 0){ ?>
                                    <h3 class="information">Mes amis</h3><?php
                                }else{?>
                                        <h3 class="information">Mes amis <?php echo '('.(count($row_friends_mutual)-1).')' ?></h3><?php
                                }
                            ?>      
                            <?php

                                if ($row['PRIVATE'] == 1 && !empty($_GET)){ ?>
                                    <p class="information_space private"> Ce profil est privé</p>
                                    <?php
                                }
                                else{//get user's friendlist ( we get ID )

                                    //own profile
                                    if (empty($_GET)){
                                        if($result_friend_mutual->num_rows <= 1){
                                            echo "<h4>Vous n'avez actuellement aucun ami.</h4>";
                                        }else{   
                                            echo '<div class="friend_widget_container">';
                                            foreach ($friends as $friend) :           
                                                
                                                $query_friend_info = $link->prepare("SELECT * FROM user WHERE ID = ? ORDER BY USERNAME ASC");
                                                $query_friend_info->bind_param("i", $friend);
                                                $query_friend_info->execute();

                                                $result_friend_info = $query_friend_info->get_result();
                                                if($result_friend_info->num_rows === 0){
                                                    //impossible
                                                }
                                                $row_friend_info = $result_friend_info->fetch_assoc();
                                                

                                                echo '<div data-id="'.$row_friend_info['ID'].'" class="view friend_widget">';?>
                                                <img class="avatar avatar -friendlist" src="<?php echo $row_friend_info['AVATAR']?>"/>
                                                <?php
                                                echo '<span class="friend_name">'.$row_friend_info['USERNAME'].'</span>';?>
                                                <button data-friend=<?php echo '"'.$row_friend_info["ID"].'"' ?> class="friend_delete icon close-icon -friendlist"></button>
                                                <?php
                                                echo '</div>';
                                            endforeach;
                                            echo '</div>';  
                                        }          
                                    //other one profile
                                    }else{
                                        $query_friend_mutual = $link->prepare("SELECT distinct(id_user1) FROM friends WHERE mutual = 1 AND (ID_USER2 = ? OR ID_USER1 = ?) LIMIT 6");
                                        $query_friend_mutual->bind_param("ii", $user, $user);
                                        $query_friend_mutual->execute();

                                        $result_friend_mutual = $query_friend_mutual->get_result();
                                        if($result_friend_mutual->num_rows === 0){
                                            echo 'Vous n"avez pas d"amis :(';
                                        }else{
                                            $row_friends_mutual = resultToArray($result_friend_mutual);  
                                            
                                            foreach ($row_friends_mutual as $friend) :          
                                                if(!in_array($friend['id_user1'],$friends)){
                                                    if($friend['id_user1'] != $user)
                                                        array_push($friends, $friend['id_user1']);        
                                                }   
                                            endforeach;

                                            echo '<div class="friend_widget_container">';
                                            foreach ($friends as $friend) :           
                                                $query_friend_info = $link->prepare("SELECT * FROM user WHERE ID = ?");
                                                $query_friend_info->bind_param("i", $friend);
                                                $query_friend_info->execute();

                                                $result_friend_info = $query_friend_info->get_result();
                                                if($result_friend_info->num_rows === 0){
                                                    //impossible
                                                }
                                                $row_friend_info = $result_friend_info->fetch_assoc();
                                                

                                                echo '<div class="friend_widget">';?>
                                                <img class="avatar avatar -small" src="<?php echo $row_friend_info['AVATAR']?>"/>
                                                <?php
                                                echo $row_friend_info['USERNAME'];
                                                echo '</div>';
                                            endforeach;
                                            echo '</div>';
                                        } 
                                    }
                                }   
                            }
                            ?>   
                        </div>
                    </div>   
                </div>
            </div>
            <div class="friends__handler__container">
                <div class="friends__handler">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                            </div>
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
