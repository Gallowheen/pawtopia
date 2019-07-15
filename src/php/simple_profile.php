<?php
    require_once("bdd.php");
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
        <div class="content_container -small">
            <div class="avatar__container">
                <div>
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
                                        echo '<div><button class="friend__button button -friend"><i class="icon icon__friend icon-ic_check_48px"></i>Ami</button></div>';
                                    }else{
                                            echo '<div class="add__friend "><button id="add_friend" data-id="'.$_GET['ID'].'" class="friend__button add__friend button -friend"><i class="icon icon__friend icon icon-ic_person_add_48px"></i>Ajouter</button></div>';
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
                                <h3 class="username h3 -small"><?php echo $row['USERNAME'] ?></h3>
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
            <div class="information__container -nospace">
                <div>
                    <div class="row">
                        <div class="col">
                                <?php
                                    $town_check_query = $link->prepare("SELECT NAME FROM towns WHERE ID = ? ");

                                    $town_check_query->bind_param("i", $row['TOWN_ID'] );
                                    $town_check_query->execute();

                                    $result_town = $town_check_query->get_result();
                                    $row_city = $result_town->fetch_assoc();

                                    $townToInsert = $row_city['NAME'];

                                    echo" <div class='information__flex'><div class='information_group -nopadding'><i class='icon information__city icon-home-52'></i><span class='information_space'>".$townToInsert."</span></div>";

                                    $query = $link->prepare("SELECT * FROM dog WHERE OWNER_ID = ? and ACTIVE = 1");
                                    $query->bind_param("i", $row['ID']);
                                    $query->execute();
                                    $result = $query->get_result();
                                    $rows = resultToArray($result);

                                    echo" <div class='information_group -nopadding'><i class='icon information__city icon-ic_pets_48px'></i><span class='information_space'>".count($rows)."</span></div>";

                                    $friends = [];

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

                                    if ((count($row_friends_mutual)-1) <= 0)
                                        echo" <div class='information_group -nopadding'><i class='icon information__city icon-ic_people_48px'></i><span class='information_space'>0</span></div></div>";
                                    else
                                        echo" <div class='information_group -nopadding'><i class='icon information__city icon-ic_people_48px'></i><span class='information_space'>".(count($row_friends_mutual)-1)."</span></div></div>";

                                    // if ( $row['BIO'])
                                    //     echo"<div class='information_group'><p class='information_title'>Biographie</p><p class='information_space'>".$row['BIO']."</p></div>";
                                    // else
                                    //     echo"<div class='information_group'><p class='information_title'>Biographie</p><p class='information_space'>L'utilisateur n'a pas encore de biographie</p></div>";
                                    if ( $row['WALK'])
                                        echo"<div class='information_group'><p class='information_title'>Type de balade</p><p class='information_space'>".$row['WALK']."</p></div>";

                                    echo "<div class='discover'><button data-id='".$_GET['ID']."' class='view button -color -blue -add'>Profil Complet</button></div>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tapToClose">
                <i class="up"></i>
            </div>


