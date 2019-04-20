<?php 
    require_once("src/php/bdd.php");
    session_start();
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    function resultToArray($result) {
        $rows = array();
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
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
            margin-top : 64px;
        }

        .avatar--top{
            margin-top : 16px;
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

        .information__container{
            margin-top : 32px;
        }
        .information{
            margin-bottom: 25px;
            font-weight: bold;
        }
        .information_space{
            margin-left : 16px;
        }
        .information_title{
            font-weight : bold;
        }
        .my_pet__container{
            overflow : visible;
        }

        .dog_card_container{
            display: flex;
            max-width: 500%;
            overflow-y: scroll;
            margin-bottom: 32px;
        }

        .dog_card{
            text-align: center;
            min-width: 150px;
            min-height: 200px;
            padding: 24px;
            background: white;
            border-radius: 10px;
            margin-right : 16px;
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
            margin-top: 16px;
        }

        .dog_button_container{
            position : relative;
            margin-top : 0px;
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
    </style>

    </head>
    <body>
        <header class="header">
            <div class="container">
                <div class="row">
                    <div class="col-6 relative">
                        <div class="title">
                            <i class="left"></i>

                            <h1>
                                <?php if (empty($_GET)) {
                                    if ( isset($_SESSION['user']) ){
                                        echo $_SESSION["user"];
                                    }else{
                                        echo 'error';
                                    }
                                }
                                ?>
                            </h1>
                        </div>
                    </div>
                    <div class="col-4 offset-2 relative">
                        <div class="menu-toggle">
                            <div class="one"></div>
                            <div class="two"></div>
                            <div class="three"></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="avatar__container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <?php 
                            //if it's user's profile
                            if (empty($_GET)) {
                                if ( isset($_SESSION['user']) ){
                                    $user = $_SESSION['user'];
                                }
                            //if it's another user's profile
                            }else{
                                if ( isset($_GET['user']) ){
                                    $user = $_GET['user'];
                                }else{
                                    //redirect 404 ?
                                }
                            }

                            $query = $link->prepare("SELECT * FROM user WHERE USERNAME = ?");
                            $query->bind_param("s", $user);
                            $query->execute();

                            $result = $query->get_result();
                            if($result->num_rows === 0) exit('No rows');
                            $row = $result->fetch_assoc();

                            if ($row['PRIVATE'] == 1)
                                echo "<p class='private'>Ce profil est privé</p>";

                        ?>
                        <?php
                            $avatar_path = $row['AVATAR'];

                            ?>
                            
                            <?php
                                if ($row['PRIVATE'] == 1){
                            ?>
                                    <img class="avatar avatar--top" src="<?php echo $avatar_path ?>"/>
                                <?php }
                                else{
                                ?>
                                    <img class="avatar" src="<?php echo $avatar_path ?>"/>
                            <?php }
                            ?>
                            <h3 class="username"><?php echo $row['USERNAME'] ?></h3>
                            <?php

                            //viewing our own profile
                            if ( isset($_SESSION['user'])){
                                if ( $row['FIRST_NAME'] ){
                                    echo" <span>".$row['FIRST_NAME']."</span>";
                                }
                                if ( $row['NAME'] ){
                                    echo" <span>".$row['NAME']."</span>";
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
                                $town_check_query = $link->prepare("SELECT NAME FROM TOWNS WHERE ID = ? ");

                                $town_check_query->bind_param("i", $row['TOWN_ID'] );
                                $town_check_query->execute();
                            
                                $result_town = $town_check_query->get_result(); 
                                $row_city = $result_town->fetch_assoc();

                                $townToInsert = $row_city['NAME']; 

                                echo" <p class='information_title'>Ville</p><p class='information_space'>".$townToInsert."</p>";

                                if ( $row['BIO'])
                                    echo"<p class='information_title'>Biographie</p><p class='information_space'>".$row['BIO']."</p>";
                                else
                                    echo"<p class='information_title'>Biographie</p><p class='information_space'>Rien à afficher pour le moment</p>";
                                if ( $row['WALK'])
                                    echo"<p class='information_title'>Type de balade</p><p class='information_space'>".$row['WALK']."</p>";
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
                            $query = $link->prepare("SELECT * FROM DOG WHERE OWNER_ID = ?");
                            $query->bind_param("i", $row['ID']);
                            $query->execute();

                            $result = $query->get_result();
                            if($result->num_rows === 0){
                                ?>
                                <h3 class="information">Mes animaux</h3>
                                <?php
                            }else{
                                //$row_dog = $result->fetch_assoc();
                                $rows = resultToArray($result);                     
                                ?>
                                <h3 class="information">Mes animaux <?php echo '('.count($rows).')' ?></h3>
                                <div class='dog_card_container'>
                                <div class="dog_card">
                                    <h4 class='dog_name'> Ajouter </h4>
                                    <button class="dog_button">
                                        <div class="dog_button_container">
                                            <div class="plus"></div>
                                        </div>
                                    </button>
                                </div>
                                <?php
                                foreach ( $rows as $dog ) :
                                ?>
                                    <div class="dog_card">
                                        <?php
                                        echo "<h4 class='dog_name'>".$dog['NAME']."</h4>";
                                        echo '<img class="dog_img" src="'.$dog['PICTURE'].'">';
                                        ?>
                                    </div> 
                                <?php
                                endforeach;
                            }
                        ?>
                        </div>
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
