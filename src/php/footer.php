<?php
    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");
?>


<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="nav_container">
                <div class="nav_button_group home" data-url='home'>
                    <span class="link_footer -active"><i class="icon icon__footer icon-ic_home_48px"></i></span>
                    <span class="icon__name -active">Accueil</span>
                </div>
                <div class="nav_button_group profile" data-url='profile'>
                    <span class="link_footer"><i class="icon icon__footer icon-single-01"></i></span>
                    <span class="icon__name">Profil</span>
                </div>
                <div class="nav_button_group members" data-url='members'>
                    <span class="link_footer"><i class="icon icon__footer icon-multiple-11"></i></span>
                    <span class="icon__name">Membres</span>
                </div>
                <div class="nav_button_group walk" data-url='walk'>
                    <span class="link_footer"><i class="icon icon__footer icon-calendar-60"></i></span>
                    <span class="icon__name">Balades</span>
                </div>
                <div class="nav_button_group message" data-url="notification">
                    <span class="link_footer"><i class="icon icon__footer icon-ic_notifications_48px"></i></span>
                    <span class="icon__name">Notifications</span>
                </div>
                <div class="nav_button_group message" data-url='message'>
                    <span class="link_footer"><i class="icon icon__footer icon__friend icon-ic_sms_48px"></i></span>
                    <span class="icon__name">Messages</span>
                </div>
            </div>
        </div>
    </div>
</footer>