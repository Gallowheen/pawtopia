<?php 
    require_once("src/php/bdd.php");
    session_start();
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");
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
            background : white;
        }

        .header{
            height : 75px;
            width : 100vw;
            position : absolute;
            top : 0px;
            background-color : #0077C0;
        }
        .menu-toggle {
            width: 50px;
            height: 30px;
            top: 20px;
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
            padding: 15px;
            position: absolute;
            top: 20px;
        }

        .title h1{
            top: 0;
            margin-left : 50px;
            color : white;
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

        .avatar{
            margin-top : 75px;
        }
    </style>

    </head>
    <body>
        <header class="header">
            <div class="container">
                <div class="row">
                    <div class="col-6">
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
                    <div class="col-2 offset-4">
                        <div class="menu-toggle">
                            <div class="one"></div>
                            <div class="two"></div>
                            <div class="three"></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="avatar">
            <?php if (empty($_GET)) {
                if ( isset($_SESSION['user']) ){
                    $user = $_SESSION['user'];

                    $query = $link->prepare("SELECT * FROM user WHERE USERNAME = ?");
                    $query->bind_param("s", $user);
                    $query->execute();

                    $result = $query->get_result();
                    if($result->num_rows === 0) exit('No rows');
                    $row = $result->fetch_assoc();

                    $avatar_path = $row['AVATAR'];

                    ?>

                    <img src="<?php echo $avatar_path ?>"/>
                    <?php

                } 
            }
            ?>
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
