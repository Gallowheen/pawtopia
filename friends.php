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

    //Check if friends ?


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

        echo 'Profil';
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
        body{
            font-family: 'Cabin', sans-serif;
            font-size : 18px;
            line-height : 1.6;
        }
        .relative{
            position : relative;
        }

        body{
            background : #f6f8fa;
        }

        .header{
            height : 75px;
            width : 100vw;
            position : absolute;
            top : 0px;
            background-color : #0077C0;
        }
        .menu-toggle {
            width: 40px;
            height: 30px;
            top: 23px;
            right: 25px;
            cursor: pointer;
            position : absolute;
        }

        .on .one{
            transform : rotate(45deg) translate(7px, 7px);
        }

        .on .two{
            opacity: 0;
        }

        .on .three{
            transform : rotate(-45deg) translate(8px, -10px);
        }

        /*SCSS*/
        .one{
            margin-top: 2px !important;
        }

        .one,
        .two,
        .three{
            width: 100%;
            height: 5px;
            background: white;
            margin: 6px auto;
            backface-visibility: hidden;
            transition-duration : .3s;
        }

        i {
            border: solid white;
            border-width: 0 3px 3px 0;
            display: inline-block;
            padding: 10px;
            position: absolute;
            top: 28px;
            left : 25px;
        }

        .title h1{
            top: 0;
            margin-left : 50px;
            color : white;
            font-family: 'Fira Sans', sans-serif;
            margin-top: 20px;
        }

        .right {
            transform: rotate(-45deg);
            -webkit-transform: rotate(-45deg);
        }

        .left {
            transform: rotate(135deg);
            -webkit-transform: rotate(135deg);
        }

        .up {
            transform: rotate(-135deg);
            -webkit-transform: rotate(-135deg);
        }

        .down {
            transform: rotate(45deg);
            -webkit-transform: rotate(45deg);
        }

        .avatar__container{
            margin-top : 75px;
            text-align : center;
        }
        .avatar{
            border-radius : 50%;
            margin-top : 32px;
        }

        .avatar--top{
            margin-top : 16px;
        }

        .avatar--small{
            height : 50px;
            width : 50px;
        }

        .username{
            margin-top : 16px;
            font-family: 'Fira Sans', sans-serif;
        }
        .private{
            margin-top : 32px;
            margin-bottom : 16px;
            font-weight : bold;
            color : #C72C1C;
        }
        .friend__container{
            margin-top : 32px;
        }
        .information__container{
            margin-top : 32px;
        }
        .information{
            margin-bottom: 32px;
            font-weight: bold;
        }
        .information_space{
            margin-left : 16px;
            margin-bottom : 0px;
        }

        .information_group{
            /* background: #0077C0;
            color : white; */
            padding: 8px 16px;
            background: white;
            color: black;
            border: 1px dashed #0077C0;
            /* box-shadow: 4px 4px 5px 0px #00000036; */
            margin-bottom: 16px;
            border-radius : 10px;
        }

        .information_group:last-child{
            margin-bottom : 0px;
        }

        .information_title{
            font-weight : bold;
            font-size: 20px;
            color : #0077C0;
        }
        .my_pet__container{
            overflow : visible;
            margin-top : 32px;
        }

        .dog_card_container{
            display: flex;
            overflow-x: scroll;
            margin-top : 32px;
        }

        .dog_card{
            text-align: center;
            width: 150px;
            min-height: 200px;
            padding: 24px;
            background: white;
            border-radius: 10px;
            margin-right : 16px;
            position : relative;
            border: 1px dashed #0077C0;
        }
        .dog_name{
            text-align: center;
            text-transform: capitalize;
        }
        .dog_img,
        .dog_button_container{
            border-radius: 50%;
            height: 100px;
            width : 100px;
            object-fit: cover;
        }

        .dog_button_container{
            position : relative;
        }
        .dog_button{
            background: transparent;
            padding: 0px;
            margin: 0px;
            border: none;
            margin-top : 16px;
            position : relative;
            outline : none;
        }
        .plus {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%,-50%);
            width: 60px;
            height: 60px;
            background: linear-gradient(#fff,#fff), linear-gradient(#fff,#fff), #0077C0;
            background-position: center;
            background-size: 50% 2px,2px 50%;
            background-repeat: no-repeat;
            border-radius: 50%;
        }
        .close-icon
        {
            display:block;
            box-sizing:border-box;
            width:35px;
            height:35px;
            border-width:5px;
            border-style: solid;
            border-color:#C72C1C;
            border-radius:100%;
            background: -webkit-linear-gradient(-45deg, transparent 0%, transparent 46%, white 46%,  white 56%,transparent 56%, transparent 100%), -webkit-linear-gradient(45deg, transparent 0%, transparent 46%, white 46%,  white 56%,transparent 56%, transparent 100%);
            background-color:#C72C1C;
            transition: all 0.3s ease;
            position : absolute;
            bottom : 10px;
            right : 50%;
            z-index : 5;

            transform : translateX(50%);
        }
        .reviews__container{
            margin-top : 32px;
        }
        .last_event__container{
            margin-top : 32px;
        }
        .error__container{
            text-align : center;
            height : 100vh;
        }
        .error__container h2{
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }
        button{
            outline: none !important;
        }

        .friend_widget_container{
            display : flex;
            flex-wrap : wrap;
            justify-content : space-around;
        }
        .friend_widget{
            height : 100px;
            width : 100px;
            position : relative;
            background: white;
            padding: 8px;
            border-radius: 15px;
            margin-bottom: 32px;
        }
    </style>

    </head>
    <body>
        <header class="header">
            <div class="container">
                <div class="row">
                    <div class="col-10 relative">
                        <div class="title">
                            <i class="left"></i>

                            <h1>
                            <?php 
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

                                    echo 'Amis';
                                }       
                            ?>
                            </h1>
                        </div>
                    </div>
                    <div class="col-2 relative">
                        <div class="menu-toggle">
                            <div class="one"></div>
                            <div class="two"></div>
                            <div class="three"></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
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
        }else{?>
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
                                            
                                            $query_friend_info = $link->prepare("SELECT * FROM user WHERE ID = ?");
                                            $query_friend_info->bind_param("i", $friend);
                                            $query_friend_info->execute();

                                            $result_friend_info = $query_friend_info->get_result();
                                            if($result_friend_info->num_rows === 0){
                                                //impossible
                                            }
                                            $row_friend_info = $result_friend_info->fetch_assoc();
                                            

                                            echo '<div class="friend_widget">';?>
                                            <img class="avatar avatar--small" src="<?php echo $row_friend_info['AVATAR']?>"/>
                                            <?php
                                            echo $row_friend_info['USERNAME'];
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
                                            <img class="avatar avatar--small" src="<?php echo $row_friend_info['AVATAR']?>"/>
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
