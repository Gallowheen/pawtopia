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

    if(!isset($_SESSION['ID'])){
        echo 0;
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
    <div class="avatar__container">
        <div class="container">
            <div class="row">
                <div class="col">
                    <?php
                        if ($row['PRIVATE'] == 1 )
                            echo "<p class='private'>Ce profil est privé</p>";
                    ?>
                    <?php
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
                            <?php
                            $filename = $_SERVER['DOCUMENT_ROOT']."/app/".$avatar_path;

                            if (file_exists($filename)){ ?>
                                <img class="avatar" src="<?php echo $avatar_path ?>"/>
                            <?php }else{ ?>
                                <img class="avatar" src="src/assets/img/avatar/default.jpg"/>
                            <?php
                            }?>
                        <?php
                        }else{ ?>

                        <div class="avatars">
                            <img class="avatar avatar -dog" src="<?php echo $avatar_dog ?>"/>

                            <?php
                            $filename = $_SERVER['DOCUMENT_ROOT']."/app/".$avatar_path;

                            if (file_exists($filename)){ ?>
                                <img class="avatar -small -master" src="<?php echo $avatar_path ?>"/>
                            <?php }else{ ?>
                                <img class="avatar -small -master" src="src/assets/img/avatar/default.jpg"/>
                            <?php
                            }?>
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
                        }else
                        ?>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col">
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

                        ?>
                        <div class="action__container">
                            <?php

                            if ($row_friend['MUTUAL']){
                                echo '<div class="action__element"><i class="icon icon__friend icon-single-01"></i><span>Ami</span></div>';
                            }else{
                                if($row_friend_requested){
                                    echo '<div class="action__element"><i class="icon icon__friend icon-sent"></i><span>Envoyé</span></div>';
                                }else{
                                    echo '<div class="action__element" id="add_friend" data-id="'.$_GET['ID'].'"><i class="icon icon__friend icon icon-ic_person_add_48px"></i><span>Ajouter</span></div>';
                                }
                            }
                            echo '<div class="action__element" id="review_profile" data-id="'.$_GET["ID"].'"><i class="icon friend__review -friend icon__friend icon-ic_favorite_48px"></i><span>Évaluer</span></div>';
                            echo '<div class="action__element friend__link" data-id="'.$_GET["ID"].'"><i class="icon friend__message -friend icon__friend icon-ic_sms_48px"></i><span>Message</span></div>';
                        ?>
                        </div>
                        <?php
                    }
                ?>
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
                            $editBouton = "<button class='button -color -blue edit-profile edit'><i class='icon edit edit__user icon-ic_edit_48px'></i>Éditer</button>";
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

                            echo" <div class='information_group'><i class='icon information__icon icon-home-52'></i><span class='information_space'>".$townToInsert."</span></div>";

                            if ( $row['BIO'])
                                echo"<div class='information_group'><i class='icon information__icon icon-ic_import_contacts_48px'></i><span class='information_space'>".$row['BIO']."</span></div>";
                            else
                                echo"<div class='information_group'><i class='icon information__icon icon-ic_import_contacts_48px'></i><span class='information_space'>L'utilisateur n'a pas encore de biographie</span></div>";
                            if ( $row['WALK'])
                                echo"<div class='information_group'><i class='icon information__icon icon-ic_favorite_48px'></i><span class='information_space'>Balade <span style='text-transform : lowercase;'>".$row['WALK']."</span></span></div>";
                            else
                                echo"<div class='information_group'><i class='icon information__icon icon-ic_favorite_48px'></i><span class='information_space'>Pas de type de balade favori</span></div>";
                        ?>
                    </div>
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
                                <h3 class="information h3">Ses compagnons</h3>
                            <?php
                            }else{ ?>
                                <h3 class="information h3">Mes compagnons <button id="add_dog" class="button -color -blue edit-profile"><i class="icon edit__user icon-add_dog"></i>Ajouter</button></h3>
                            <?php
                            }
                            if(!empty($_GET))
                                //echo "<p class='information_space'>Cet utilisateur n'a aucun chien pour le moment.</p>";
                                echo "<img class='dog__img' src='src/assets/img/ressources/no_dog.png'/>";
                            else{
                                echo "<img class='dog__img' src='src/assets/img/ressources/no_dog.png'/>";
                                //echo "<p class='information_space -empty'>Vous n'avez aucun chien pour le moment.</p>";
                            }
                        }else{
                            $rows = resultToArray($result);
                            if ($row['PRIVATE'] == 1 && !empty($_GET)){ ?>
                                <h3 class="information h3">Ses compagnons <?php echo '('.count($rows).')' ?></h3>
                                <p class="information_space private"> Ce profil est privé</p>
                                <?php
                            }else{
                                ?>
                                <h3 class="information h3">Mes compagnons <?php echo '('.count($rows).') <button id="add_dog" class="button -color -blue edit-profile"><i class="icon edit__user icon-add_dog"></i>Ajouter</button>' ?></h3>
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
                                                <button data-dog="<?php echo $dog["ID"] ?>" class="icon close-icon dog_delete"></button>
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
    <!-- Le CSS écrit en dur ici est à placer dans des classes / selecteurs des fichiers CSS -->
    <div class="review__container" style='margin-top: 32px;'>
        <div class="container">
            <div class="row">
                <div class="col">
                    <!-- DOG VIEWER FUNCTION -->
                    <?php
                        $query = $link->prepare("SELECT r.*, u.USERNAME, u.AVATAR FROM reviews r, user u WHERE r.ID_USER = ? AND r.ID_REVIEWER = u.ID");
                        $query->bind_param("i", $row['ID']);
                        $query->execute();

                        $result = $query->get_result();
                        $reviews = array();
                        $note = 0;
                        if($result->num_rows === 0){
                            // Pas d'évaluations
                        }else{
                            $reviews = resultToArray($result);
                            foreach ($reviews as $k => $v) {
                                $note += $v['NOTE'];
                            }
                            $note = $note / count($reviews);
                        }
                        if (!$reviews){ ?>
                            <h3 class="information h3">Évaluations</h3>
                            <p class="information_space private">Aucune review pour le moment.</p>
                        <?php
                        }else{
                            $note = number_format($note, 1);
                            ?>
    
                            <h3 class="information h3">Évaluations <?php echo '('.$note.' / 5)'; ?> </h3>
                            <?php
                            foreach ( $reviews as $review ) :
                            ?>
                                <!-- Le CSS écrit en dur ici est à placer dans des classes / selecteurs des fichiers CSS -->
                                <div class="review_card" style='clear:both; padding:4px; margin:4px;'>
                                    <?php
                                    // Partie avatar
                                    echo "<div><img class='avatar' style='float:left; width:62px; height:62px; margin-top:0px; margin-right:12px;' src=\"".$review['AVATAR']."\" /></div>";
    
                                    // Partie nom
                                    echo "<div>".$review['USERNAME']."</div>";
    
                                    // Partie étoiles
                                    for($i=0; $i<$review['NOTE']; $i++)
                                    {
                                        echo "<i class='icon information__icon icon-ic_favorite_48px'></i>";
                                    }
    
                                    // Partie commentaire
                                    echo "<div>".$review['MESSAGE']."</div>"; ?>
                                </div>
                            <?php
                            endforeach;
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
                                <h3 class="information h3">Ses amis</h3><?php
                            }else{ ?>
                                <h3 class="information h3">Mes amis</h3>
                            <?php
                            }
                            if(!empty($_GET))
                                echo "<p class='information_space'>Cet utilisateur n'a aucun ami pour le moment.</p>";
                            else{
                                echo "<p class='information_space'>Vous n'avez aucun ami pour le moment.</p>";
                            }
                        }else{
                            if(!empty($_GET)){?>
                            <h3 class="information h3 -first">Ses amis <?php echo '('.(count($row_friends_mutual)-1).") <button class='button -color -blue edit-profile more__friend' data-id='$user'><i class='icon edit__user icon-ic_people_48px'></i>Plus d'amis</button>" ?></h3>
                            <?php
                            }else{ ?>
                            <h3 class="information h3 -first">Mes amis <?php echo '('.(count($row_friends_mutual)-1).") <button class='button -color -blue edit-profile more__friend'><i class='icon edit__user icon-ic_people_48px'></i>Gérer</button>" ?></h3>
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
                                <div class="discover"><button id="discover" class="button -color -blue">Découvrez nos membres</button></div>
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

                                    <?php
                                    $filename = $row_friend_info['AVATAR'];

                                    if (file_exists($filename)){ ?>
                                        <img class="avatar -topFriend" src="<?php echo $row_friend_info['AVATAR']?>"/>
                                    <?php }else{ ?>
                                        <img class="avatar -topFriend" src="src/assets/img/avatar/default.jpg"/>
                                    <?php
                                    }?>

                                    <?php
                                    echo '<p class="friend__username">'.$row_friend_info['USERNAME'].'</p>';
                                    echo '</div>';
                                endforeach;
                                echo '</div></button>';
                                ?>
                                <!-- <div class="more__friend">
                                    <button class="button -color -blue">Plus d'amis</button>
                                </div> -->
                                <?php

                            }
                        //other one profile
                        }else{
                            $query_friend_mutual = $link->prepare("SELECT distinct(id_user1) FROM friends WHERE mutual = 1 AND (ID_USER2 = ? OR ID_USER1 = ?) LIMIT 6");
                            $query_friend_mutual->bind_param("ii", $user, $user);
                            $query_friend_mutual->execute();

                            $result_friend_mutual = $query_friend_mutual->get_result();
                            if($result_friend_mutual->num_rows <= 1){

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


                                    echo '<button class="button view" data-id="'.$row_friend_info['ID'].'"><div class="friend_widget -small">';
                                    $filename = $_SERVER['DOCUMENT_ROOT']."/app/".$row_friend_info['AVATAR'];

                                    if (file_exists($filename)){ ?>
                                        <img class="avatar -topFriend" src="<?php echo $row_friend_info['AVATAR']?>"/>
                                    <?php }else{ ?>
                                        <img class="avatar -topFriend" src="src/assets/img/avatar/default.jpg"/>
                                    <?php
                                    }?>

                                    <?php
                                    echo '<p class="friend__username">'.$row_friend_info['USERNAME'].'</p>';
                                    echo '</div>';
                                endforeach;
                                echo '</div>';
                                ?>
                                <!-- <div class="more__friend">
                                    <span><button class="button -color -blue" data-id="<?= $user ?>">Plus d'amis</button></span>
                                </div> -->
                                <?php
                            }
                        }
                    }
                    ?>
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
    <?php
        }
    ?>
    <script src="src/scripts/profile.js"></script>
