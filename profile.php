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

    $pagename = 'Mon Profil';
    
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
        }else{

            ?>
        <div class="content_container">
            <div class="avatar__container">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <?php 
                                if ($row['PRIVATE'] == 1 )
                                    echo "<p class='private'>Ce profil est privé</p>";
                            ?>
                            <?php              
                                $avatar_path = $row['AVATAR'];

                                ?>
                                
                                <?php
                                    if ($row['PRIVATE'] == 1){
                                ?>
                                        <img class="avatar avatar -top" src="<?php echo $avatar_path ?>"/>
                                    <?php }
                                    else{
                                    ?>
                                        <img class="avatar" src="<?php echo $avatar_path ?>"/>
                                <?php }
                                ?>
                                <h3 class="username"><?php echo $row['USERNAME'] ?></h3>
                                <?php

                                //viewing our own profile
                                if ($row['PRIVATE'] == 0 || empty($_GET) ){
                                    if ( isset($_SESSION['user'])){
                                        if ( $row['FIRST_NAME'] ){
                                            echo" <span>".$row['FIRST_NAME']."</span>";
                                        }
                                        if ( $row['NAME'] ){
                                            echo" <span>".$row['NAME']."</span>";
                                        }
                                    }
                                }
                                ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="my_pet__container">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <!-- DOG VIEWER FUNCTION -->   
                            <?php
                                $query = $link->prepare("SELECT * FROM dog WHERE OWNER_ID = ?");
                                $query->bind_param("i", $row['ID']);
                                $query->execute();

                                $result = $query->get_result();
                                if($result->num_rows === 0){
                                    ?>
                                    <h3 class="information">Mes animaux</h3>
                                    <!-- <div class="dog_card">
                                        <h4 class='dog_name'> Ajouter </h4>
                                        <button class="dog_button">
                                            <div class="dog_button_container">
                                                <div class="plus"></div>
                                            </div>
                                        </button>
                                    </div> -->
                                    <?php
                                }else{
                                    $rows = resultToArray($result);   
                                    if ($row['PRIVATE'] == 1 && !empty($_GET)){ ?>
                                        <h3 class="information">Mes animaux <?php echo '('.count($rows).')' ?></h3>
                                        <p class="information_space private"> Ce profil est privé</p>
                                        <?php
                                    }else{              
                                        ?>
                                        <h3 class="information">Mes animaux <?php echo '('.count($rows).')' ?></h3>
                                        <div class='dog_card_container'>
                                        <?php 

                                            if(empty($_GET)){
                                            ?>

                                            <!-- <div class="dog_card">
                                                <h4 class='dog_name'> Ajouter </h4>
                                                <button class="dog_button">
                                                    <div class="dog_button_container">
                                                        <div class="plus"></div>
                                                    </div>
                                                </button>
                                            </div> -->

                                        <?php
                                        }
                                        foreach ( $rows as $dog ) :
                                        ?>
                                            <div class="dog_card">
                                                <?php
                                                echo "<h4 class='dog_name'>".$dog['NAME']."</h4>";
                                                ?>
                                                <div class="dog_button">
                                                <?php
                                                    if (empty($_GET)){ ?>
                                                        <button data-dog=<?php echo '"'.$dog["ID"].'"' ?> class="close-icon"></button>
                                                <?php
                                                    }
                                                echo '<img class="dog_img" src="'.$dog['PICTURE'].'">';?>
                                                
                                                </div>
                                                <div class='dog_information'>
                                                    <?php
                                                        echo '<div class="information_group -dog"><p class="information_title">Sexe</p>';
                                                        echo '<p class="information_space">'.$dog["GENDER"].'</p></div>';
                                                        echo '<div class="information_group -dog"><p class="information_title">Age</p>';
                                                        echo '<p class="information_space">'.$dog["AGE"].' ans</p></div>';
                                                        echo '<div class="information_group -dog"><p class="information_title">Race</p>';

                                                        $query_breeds = $link->prepare("SELECT * FROM breeds WHERE ID = ?");
                                                        $query_breeds->bind_param("i", $dog['BREED_ID']);
                                                        $query_breeds->execute();

                                                        $result_breeds = $query_breeds->get_result();
                                                        $row_breeds = $result_breeds->fetch_assoc();

                                                        echo '<p class="information_space">'.$row_breeds['NAME'].'</p></div>';
                                                    ?>
                                                </div>
                                            </div> 
                                        <?php
                                        endforeach;
                                    }
                                }
                                ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="friend__container">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <?php
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
        
                            ?>
                            <?php
                                if ((count($row_friends_mutual)-1) < 0){ ?>
                                    <h3 class="information -space">Mes amis</h3><?php
                                }else{?>
                                    <h3 class="information -space">Mes amis <?php echo '('.(count($row_friends_mutual)-1).')' ?></h3><?php
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
                                        ?>
                                        <a href="friends.php"><button class="button -color">Plus d'amis</button></a>
                                    <?php
                                    }else{   
                                        echo '<div class="friend_widget_container -notEmpty">';
                                        foreach ($friends as $friend) :           
                                            
                                            $query_friend_info = $link->prepare("SELECT * FROM user WHERE ID = ?");
                                            $query_friend_info->bind_param("i", $friend);
                                            $query_friend_info->execute();

                                            $result_friend_info = $query_friend_info->get_result();
                                            if($result_friend_info->num_rows === 0){
                                                //impossible
                                            }
                                            $row_friend_info = $result_friend_info->fetch_assoc();
                                            

                                            echo '<button class="button view" data-id="'.$row_friend_info['ID'].'"><div class="friend_widget -small">';?>
                                            <img class="avatar avatar -small -negative" src="<?php echo $row_friend_info['AVATAR']?>"/>
                                            <?php
                                            echo '<p class="friend__username">'.$row_friend_info['USERNAME'].'</p>';
                                            echo '</div>';
                                        endforeach;
                                        echo '</div></button>';
                                        ?>
                                        <div class="more__friend">
                                            <a href="friends.php"><button class="button -color -blue">Plus d'amis</button></a>
                                        </div>
                                        <?php

                                    }  
                                    
                                    // echo "<h4>Envoyée</h4>";
                                    // $query_friend_pending = $link->prepare("SELECT distinct(id_user2) FROM friends WHERE mutual = 0 AND ID_USER1 = ?");
                                    // $query_friend_pending->bind_param("i", $user);
                                    // $query_friend_pending->execute();

                                    // $result_friend_pending = $query_friend_pending->get_result();
                                    // if($result_friend_pending->num_rows === 0){
                                    //     echo 'Aucune demande en attente';
                                    // }else{
                                    //     $row_friends_pending = resultToArray($result_friend_pending);  
                                        
                                    //     foreach ($row_friends_pending as $friend) :       
                                    //         if(!in_array($friend['id_user2'],$friends_pending)){
                                    //             if($friend['id_user2'] != $user)
                                    //                 array_push($friends_pending, $friend['id_user2']);    
                                    //         }   
                                    //     endforeach;

                                    //     foreach ($friends_pending as $friend) :           
                                    //         echo $friend;
                                    //     endforeach;

                                    // }  
                                    // echo "<h4>Reçu</h4>";
                                    // $query_friend_invite = $link->prepare("SELECT distinct(id_user1) FROM friends WHERE mutual = 0 AND ID_USER2 = ?");
                                    // $query_friend_invite->bind_param("i", $user);
                                    // $query_friend_invite->execute();

                                    // $result_friend_invite = $query_friend_invite->get_result();
                                    // if($result_friend_invite->num_rows === 0){
                                    //     echo 'Aucune demande en attente';
                                    // }else{
                                    //     $row_friends_pending = resultToArray($result_friend_invite);  
                                        
                                    //     foreach ($row_friends_pending as $friend) :       
                                    //         if(!in_array($friend['id_user1'],$friends_pending)){
                                    //             if($friend['id_user1'] != $user)
                                    //                 array_push($friends_invite, $friend['id_user1']);    
                                    //         }   
                                    //     endforeach;

                                    //     foreach ($friends_invite as $friend) :           
                                    //         echo $friend;
                                    //     endforeach;
                                    // }
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

                                        echo '<div class="friend_widget_container -notEmpty">';
                                        foreach ($friends as $friend) :           
                                            $query_friend_info = $link->prepare("SELECT * FROM user WHERE ID = ?");
                                            $query_friend_info->bind_param("i", $friend);
                                            $query_friend_info->execute();

                                            $result_friend_info = $query_friend_info->get_result();
                                            if($result_friend_info->num_rows === 0){
                                                //impossible
                                            }
                                            $row_friend_info = $result_friend_info->fetch_assoc();
                                            

                                            echo '<div class="friend_widget -small">';?>
                                            <img class="avatar avatar -small -negative" src="<?php echo $row_friend_info['AVATAR']?>"/>
                                            <?php
                                            echo $row_friend_info['USERNAME'];
                                            echo '</div>';
                                        endforeach;
                                        echo '</div>';
                                        ?>
                                        <div class="more__friend">
                                            <a href="<?php echo "friends.php?ID=".$user ?>"><button class="button -color -green">Plus d'amis</button></a>
                                        </div>
                                        <?php
                                    } 
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="information__container">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <h3 class="information">Informations</h3>
                                <?php
                                    $town_check_query = $link->prepare("SELECT NAME FROM towns WHERE ID = ? ");

                                    $town_check_query->bind_param("i", $row['TOWN_ID'] );
                                    $town_check_query->execute();
                                
                                    $result_town = $town_check_query->get_result(); 
                                    $row_city = $result_town->fetch_assoc();

                                    $townToInsert = $row_city['NAME']; 

                                    echo" <div class='information_group'><p class='information_title'>Ville</p><p class='information_space'>".$townToInsert."</p></div>";

                                    if ( $row['BIO'])
                                        echo"<div class='information_group'><p class='information_title'>Biographie</p><p class='information_space'>".$row['BIO']."</p></div>";
                                    else
                                        echo"<div class='information_group'><p class='information_title'>Biographie</p><p class='information_space'>Rien à afficher pour le moment</p></div>";
                                    if ( $row['WALK'])
                                        echo"<div class='information_group'><p class='information_title'>Type de balade</p><p class='information_space'>".$row['WALK']."</p></div>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="reviews__container">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <h3 class="information">Mes reviews</h3>
                            <!-- REVIEW FUNCTION -->   
                        </div>
                    </div>
                </div>
            </div>
            <div class="last_event__container">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <h3 class="information">Mes derniers événements</h3>
                            <!-- LAST_EVENT FUNCTION -->   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        }
    ?>
    <?php 
        include('src/php/footer.php');
    ?>
    </body>
    <script src="src/scripts/jquery-3.4.0.min.js"></script>
    <script src="src/scripts/bootstrap.min.js"></script>
    <script src="src/scripts/app.js"></script>
    <script>
        $(".menu-toggle").on('click', function() {
            $(this).toggleClass("on");
            $('.menu-section').toggleClass("on");
            $("nav ul").toggleClass('hidden');
        });
    </script>
</html>
