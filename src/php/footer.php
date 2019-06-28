<?php 
    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");
?>


<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="nav_container">
                <div class="nav_button_group home">
                    <a class="link_footer" href="home.php"><i class="icon icon__footer icon-ic_home_48px"></i></a>
                    <span class="icon__name">Accueil</span>
                </div>
                <div class="nav_button_group profile">
                    <a class="link_footer" href="profile.php"><i class="icon icon__footer icon-single-01"></i></a>
                    <span class="icon__name">Profil</span>
                </div>
                <div class="nav_button_group members">
                    <a class="link_footer" href="members.php"><i class="icon icon__footer icon-multiple-11"></i></a>
                    <span class="icon__name">Membres</span>
                </div>
                <div class="nav_button_group walk">
                    <a class="link_footer" href="walk.php"><i class="icon icon__footer icon-calendar-60"></i></a>
                    <span class="icon__name">Balades</span>
                </div>
                <div class="nav_button_group message">
                    <a class="link_footer" href="message.php"><i class="icon icon__footer icon__friend icon-ic_sms_48px"></i></a>
                    <span class="icon__name">Messages</span>
                </div>
            </div>
        </div>
    </div>
</footer>