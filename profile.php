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

    if(!isset($_SESSION['ID'])){
        header('Location:index.php');
        exit;
    }

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


    if (empty($_GET)) 
        $pagename = 'Mon Profil';
    else{
        $pagename = 'Profil de '. $row['USERNAME'];
    }
    
    $error = false;
?>

<!DOCTYPE html>
<html>
    <?php
        include ('src/php/head.php');
    ?>
    <body class="profile">
        <?php
            include ('src/php/header.php');
        ?>
        <?php
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

                                if (!empty($_GET) && $_GET['ID'] != $_SESSION['ID']){

                                    // CHECK IF FRIEND

                                    $query = $link->prepare("SELECT * FROM friends WHERE ID_USER1 = ? AND ID_USER2 = ? AND MUTUAL = 1 LIMIT 1");
                                    $query->bind_param("ii", $_SESSION['ID'], $_GET['ID']);
                                    $query->execute();

                                    $result_friend = $query->get_result();
                                    $row_friend = $result_friend->fetch_assoc();

                                    $query = $link->prepare("SELECT * FROM friends WHERE ID_USER1 = ? AND ID_USER2 = ? AND MUTUAL = 0 LIMIT 1");
                                    $query->bind_param("ii", $_SESSION['ID'], $_GET['ID']);
                                    $query->execute();

                                    $result_friend_requested = $query->get_result();
                                    $row_friend_requested = $result_friend_requested->fetch_assoc();

                                    if ($row_friend['MUTUAL']){
                                        echo '<div><a class="friend__link" href="getMessage.php?ID='.$_GET["ID"].'"><i class="icon friend__message icon__friend icon-ic_sms_48px"></i></a><button class="friend__button button -friend"><i class="icon icon__friend icon-ic_check_48px"></i>Ami</button></div>';
                                    }else{
                                        if($row_friend_requested){
                                            echo '<div id="friend__button"><a class="friend__link" href="getMessage.php?ID='.$_GET["ID"].'"><i class="icon icon__friend friend__message -friend icon-ic_sms_48px"></i></a><button class="friend__button button -friend"><i class="icon icon__friend icon-ic_check_48px"></i>Envoyé</button></div>';
                                        }else{
                                            echo '<div id="friend__button"><a class="friend__link" href="getMessage.php?ID='.$_GET["ID"].'"><i class="icon friend__message -friend icon__friend icon-ic_sms_48px"></i></a><button id="add_friend" data-id="'.$_GET['ID'].'" class="friend__button button -friend"><i class="icon icon__friend icon icon-ic_person_add_48px"></i>Ajouter</button></div>';
                                        }
                                    }
                                }

                                // SELECT AVATAR
                            
                                $query = $link->prepare("SELECT * FROM dog WHERE OWNER_ID = ? and ACTIVE = 1 LIMIT 1");
                                if (empty($_GET)){
                                    $query->bind_param("i", $_SESSION['ID']);
                                }else{
                                    $query->bind_param("i", $_GET['ID']); 
                                }  
                                $query->execute();

                                $result = $query->get_result();
                                $row_dog = $result->fetch_assoc();

                                $avatar_dog = $row_dog['PICTURE'];
                                $avatar_path = $row['AVATAR'];

                                if($result->num_rows === 0){ ?>
                                    <img class="avatar avatar" src="<?php echo $avatar_path ?>"/>
                                <?php
                                }else{ ?>

                                <div class="avatars">
                                    <img class="avatar avatar -dog" src="<?php echo $avatar_dog ?>"/>
                                    <img class="avatar avatar -small -master" src="<?php echo $avatar_path ?>"/>
                                </div>
                                   
                                <?php
                                }
                                ?>
                                <h3 class="username h3"><?php echo $row['USERNAME'] ?></h3>
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
                                $query = $link->prepare("SELECT * FROM dog WHERE OWNER_ID = ? and ACTIVE = 1");
                                $query->bind_param("i", $row['ID']);
                                $query->execute();

                                $result = $query->get_result();
                                if($result->num_rows === 0){
                                    if(!empty($_GET)){
                                    ?>
                                        <h3 class="information h3">Ses compagnons (0)</h3>                        
                                    <?php
                                    }else{ ?>
                                        <h3 class="information h3">Ses compagnons (0)</h3>
                                    <?php
                                    }
                                    if(!empty($_GET))
                                        echo "<p class='information_space'>Cet utilisateur n'a aucun chien pour le moment.</p>";
                                    else{
                                        echo "<p class='information_space -empty'>Vous n'avez aucun chien pour le moment.</p>";
                                        echo "<div class='dog_information_button'><button id='add_dog' class='button -color'>Ajouter un compagnon</button></div>";
                                    }
                                }else{
                                    $rows = resultToArray($result);   
                                    if ($row['PRIVATE'] == 1 && !empty($_GET)){ ?>
                                        <h3 class="information h3">Ses compagnons <?php echo '('.count($rows).')' ?></h3>
                                        <p class="information_space private"> Ce profil est privé</p>
                                        <?php
                                    }else{              
                                        ?>
                                        <h3 class="information h3">Mes compagnons <?php echo '('.count($rows).')' ?></h3>
                                        <?php
                                            if(empty($_GET)){
                                                ?>
                                                <div class="dog_information_button"><button id="add_dog" class="button -color">Ajouter un compagnon</button></div>
                                        <?php
                                            }
                                        ?>
                                        <div class='dog_card_container'>
                                        <?php
                                        foreach ( $rows as $dog ) :
                                        ?>
                                            <div class="dog_card">
                                                <?php
                                                echo "<h4 class='h4 dog_name -nolimit'>".$dog['NAME']."</h4>";
                                                ?>
                                                <div class="dog_button">
                                                <?php
                                                    if (empty($_GET)){ ?>
                                                        <button data-dog=<?php echo '"'.$dog["ID"].'"' ?> class="icon close-icon dog_delete"></button>
                                                <?php
                                                    }
                                                echo '<img class="dog_img" src="'.$dog['PICTURE'].'">';?>
                                                
                                                </div>
                                                <div class='dog_information'>
                                                    <?php
                                                        echo '<div class="information_group -dog"><p><span class="information_title">Sexe :</span><span class="information_space">'.$dog["GENDER"].'</span></p></div>';
                                                        echo '<div class="information_group -dog"><p><span class="information_title">Age :</span><span class="information_space">'.$dog["AGE"].' ans</span></p></div>';

                                                        $query_breeds = $link->prepare("SELECT * FROM breeds WHERE ID = ?");
                                                        $query_breeds->bind_param("i", $dog['BREED_ID']);
                                                        $query_breeds->execute();

                                                        $result_breeds = $query_breeds->get_result();
                                                        $row_breeds = $result_breeds->fetch_assoc();

                                                        echo '<div class="information_group -dog"><p><span class="information_title">Race :</span><span class="information_space">'.$row_breeds['NAME'].'</span></p></div>';
                                                    ?>
                                                </div>
                                            </div> 
                                        <?php
                                        endforeach;
                                        ?>
                                        </div>
                                        <?php
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
                                if ((count($row_friends_mutual)-1) <= 0){ 
                                    if(!empty($_GET)){
                                    ?>
                                        <h3 class="information h3">Ses amis (0)</h3><?php
                                    }else{ ?>
                                        <h3 class="information h3">Mes amis (0)</h3>
                                    <?php
                                    }
                                    if(!empty($_GET))
                                        echo "<p class='information_space'>Cet utilisateur n'a aucun ami pour le moment.</p>";
                                    else{
                                        echo "<p class='information_space'>Vous n'avez aucun ami pour le moment.</p>";
                                    }
                                }else{
                                    if(!empty($_GET)){?>
                                    <h3 class="information -nospace h3">Ses amis <?php echo '('.(count($row_friends_mutual)-1).')' ?></h3>
                                    <?php
                                    }else{ ?>
                                    <h3 class="information -nospace h3">Mes amis <?php echo '('.(count($row_friends_mutual)-1).')' ?></h3>
                                    <?php
                                    }
                                }
                            ?>      
                            <?php
                            if ($row['PRIVATE'] == 1 && !empty($_GET) && count($row_friends_mutual)-1 == 0){ ?>
                                <p class="information_space private"> Ce profil est privé</p>
                                <?php
                            }
                            else{//get user's friendlist ( we get ID )

                                //own profile
                                if (empty($_GET)){
                                    if($result_friend_mutual->num_rows <= 1){
                                        ?>
                                        <div class="more__friend">
                                            <a href="friends.php"><button class="button -color">Plus d'amis</button></a>
                                        </div>
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
                                            <img class="avatar avatar -topFriend" src="<?php echo $row_friend_info['AVATAR']?>"/>
                                            <?php
                                            echo '<p class="friend__username">'.$row_friend_info['USERNAME'].'</p>';
                                            echo '</div>';
                                        endforeach;
                                        echo '</div></button>';
                                        ?>
                                        <div class="more__friend">
                                            <a href="friends.php"><button class="button -color">Plus d'amis</button></a>
                                        </div>
                                        <?php

                                    }  
                                //other one profile
                                }else{
                                    $query_friend_mutual = $link->prepare("SELECT distinct(id_user1) FROM friends WHERE mutual = 1 AND (ID_USER2 = ? OR ID_USER1 = ?) LIMIT 6");
                                    $query_friend_mutual->bind_param("ii", $user, $user);
                                    $query_friend_mutual->execute();

                                    $result_friend_mutual = $query_friend_mutual->get_result();
                                    if($result_friend_mutual->num_rows === 1){
                                        
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
                                            

                                            echo '<button class="button view" data-id="'.$row_friend_info['ID'].'"><div class="friend_widget -small">';?>
                                            
                                            <img class="avatar avatar -topFriend" src="<?php echo $row_friend_info['AVATAR']?>"/>
                                            <?php
                                            echo '<p class="friend__username">'.$row_friend_info['USERNAME'].'</p>';
                                            echo '</div>';
                                        endforeach;
                                        echo '</div>';
                                        ?>
                                        <div class="more__friend">
                                            <a href="<?php echo "friends.php?ID=".$user ?>"><button class="button -color -blue">Plus d'amis</button></a>
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
                            <?php
                                $editBouton = "";
                                if(empty($_GET))
                                    $editBouton = "<button class='button -color -blue edit-profile'><i class='icon edit edit__user icon-ic_edit_48px'></i>Éditer</button>";
                            ?>
                            <h3 class="information h3">Informations <?= $editBouton ?> </h3>
                            <div class="information_editable">
                                <?php
                                    $town_check_query = $link->prepare("SELECT NAME FROM towns WHERE ID = ? ");

                                    $town_check_query->bind_param("i", $row['TOWN_ID'] );
                                    $town_check_query->execute();
                                
                                    $result_town = $town_check_query->get_result(); 
                                    $row_city = $result_town->fetch_assoc();

                                    $townToInsert = $row_city['NAME']; 

                                    echo" <div class='information_group'><i class='icon information__city icon-home-52'></i><span class='information_space'>".$townToInsert."</span></div>";

                                    if ( $row['BIO'])
                                        echo"<div class='information_group'><p class='information_title'>Biographie</p><p class='information_space'>".$row['BIO']."</p></div>";
                                    else
                                        echo"<div class='information_group'><p class='information_title'>Biographie</p><p class='information_space'>L'utilisateur n'a pas encore de biographie</p></div>";
                                    if ( $row['WALK'])
                                        echo"<div class='information_group'><p class='information_title'>Type de balade</p><p class='information_space'>".$row['WALK']."</p></div>";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="reviews__container">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <h3 class="information h3">Mes reviews</h3>
                            REVIEW FUNCTION   
                        </div>
                    </div>
                </div>
            </div>
            <div class="last_event__container">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <h3 class="information h3">Mes derniers événements</h3>
                            LAST_EVENT FUNCTION   
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
    <?php
        }
    ?>
    </body>
    <?php 
        include('src/php/footer.php');
    ?>
    <script src="src/scripts/jquery-3.4.0.min.js"></script>
    <script src="src/scripts/bootstrap.min.js"></script>
    <script src="src/scripts/jquery.touchSwipe.min.js"></script>
    <script src="https://cdn.pubnub.com/sdk/javascript/pubnub.4.24.1.js"></script>
    <script src="src/scripts/app.js"></script>

</html>
