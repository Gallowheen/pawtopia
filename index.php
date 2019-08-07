<?php
    require_once("./app/src/php/bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);

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
    <?php
        include ('./app/src/php/head_landing.php');
    ?>
    <body>
        <header>
            <div class="dotting"></div>
            <svg class="logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 244.74 170.42"><defs><style>.cls-1{fill:none;stroke:#fff;stroke-miterlimit:10;stroke-width:5px;}</style></defs><title>logo</title><path class="cls-1" d="M118.19,97.6c0,23.21-2.8,41-5.63,50.09a44.35,44.35,0,0,1-10.27,17.78c-.26.27-4,3.08-2.7,3.73,0,0,2.91.25,3.29.22,6-.36,13.08,0,28.54-14.08,6.07-5.51,2.74-4.67,5-4.7l104.86-.43c2.5-.19,4.06-1.11,4.93-2.59a7.7,7.7,0,0,0,.64-3.21,30.48,30.48,0,0,0,0-4.08c-.06-2-.09-3-.12-3.43-.18-3.55-.4-77.85,0-91.13a5.81,5.81,0,0,0-1.16-4.07A6.41,6.41,0,0,0,241.87,40a14.17,14.17,0,0,0-4.93-.08l-112.15.3c-.81-.05-3.77-.42-5.36,1.28a6.23,6.23,0,0,0-1.29,2.79,6.77,6.77,0,0,0,0,2.81V97.6" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M117.62,114.92l-104.86-.43c-2.5-.19-4.06-1.11-4.93-2.58a7.8,7.8,0,0,1-.64-3.22,30.35,30.35,0,0,1,0-4.07c.06-2,.09-3,.12-3.44.18-3.55.4-77.85,0-91.13A5.81,5.81,0,0,1,8.47,6a6.38,6.38,0,0,1,3.65-1.72,14.17,14.17,0,0,1,4.93-.08l112.15.3c.81-.05,3.77-.42,5.36,1.28a5.86,5.86,0,0,1,1.29,2.79c.12,1.9,0,4.11,0,4.11v27.5" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M161.3,133.48" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M158.6,129.75" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M153,132.86a2.33,2.33,0,0,1-.4-1.27c.13-1.85,4-1.5,5.54-4,1.35-2.25,0-4.8,1.06-8.59,1.81-6.4,4.24-4.85,4.53-11.54.08-1.89-1.2-2.85-2.56-6.39a26,26,0,0,1-1.23-14.73,34.69,34.69,0,0,0,1-6.63c.2-2.88.29-6-2.49-8.59-3.67-3.37-6.25-2.09-7.86-5.4-1-2.09-2.34-4.6-.74-5.78,2.45-1.8,6.27.38,9.61-1.59,1.74-1,1.3-1.68,2.92-3.19,3.15-2.94,7.75-2,11.48-.73,6.53,2.24,7.23,10.46,8.16,22.58.54,7.16,5,13.75,10.31,18.66,6.62,6.17,7.58,7.49,10.56,11.54,2.81,3.83,4.58,8.41,8.1,9.58,8,2.63,13.5-5.65,16.32-7.86,1.18-.93.92.22-.79,4.06-2.29,5.14-5.3,8.54-10.38,12.67-1.34,1.09-3.28,1.11-3.19,3.33a24.64,24.64,0,0,0,.74,5.91c.35,1.17-.47,4.65-2.68,4.81-5,.37-1.5-.19-9.84.15-5.6.23-7.56.51-9-.77a5,5,0,0,1-1.76-4.49c.43-1.68,2.55-3.43,5.52-4.14.47-.1-4.36-.75-8.25-2.13a33.06,33.06,0,0,1-4.83-2.18,24.22,24.22,0,0,1-7.28-5.38c-.78-.9-1.9,3.17-2,3.49-2.36,6.27-5.21,8.5-5.21,11.18,0,1.91-1,3-3.16,3.2-2.31.26-1.36.31-9.49,1.06a5.33,5.33,0,0,1-2-.57,1.34,1.34,0,0,1-.48-1.76h0s.72-.64,2.93-2.47A4.4,4.4,0,0,1,153,132.86Z" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M156.14,134.85c3.27-1.74,3.9-1.92,4.54-2.3a13.22,13.22,0,0,0,2.34-1.8c1.44-1.43.91-3.3,2-8.86,0,0,1.06-5,2.72-5.6" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M160.7,75.51c4.12,3.17,7.16,3.21,10.65,3.17,2.38,0,10.38-3.33,10.37-3.33" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M161,80.13a15.74,15.74,0,0,0,11,3.43c2.37,0,10.65-3.44,10.65-3.43" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M196.64,114.21a6,6,0,0,0-5.4,2.45c-1.52,2.18-.59,5.35.62,8.42,1,2.59,2.16,4.53,4.54,5.58,3.69,1.62,8.32.61,11.78-2.46" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M163.61,54.38c-2,2.14-.88,5.84,1.38,13.08,1.56,5,2.86,6.21,3.93,6.88a7.52,7.52,0,0,0,6.87.49c3.8-1.73,4.37-6.42,4.55-7.9.07-.62.72-6.83-4-10.87C172.6,52.81,166.19,51.67,163.61,54.38Z" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M209,115.57c1.87,1.92,1.73,2.44,2.76,4.85a16.85,16.85,0,0,1,1.23,8.52" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M173.09,80.24A42.64,42.64,0,0,0,194,78.6c14.65-5,21.72-16.68,23.57-20" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M104.82,98.32a2.25,2.25,0,0,0,.4-1.27c-.13-1.86-4-1.5-5.54-4-1.34-2.25,0-4.81-1.06-8.6-1.81-6.39-4.24-4.84-4.53-11.53C94,71,95.29,70,96.66,66.48a26,26,0,0,0,1.22-14.73,35.5,35.5,0,0,1-1-6.63c-.19-2.88-.29-6,2.5-8.59,3.67-3.37,6.24-2.09,7.85-5.4,1-2.09,2.34-4.61.74-5.79-2.44-1.8-6.26.39-9.6-1.58-1.75-1-1.3-1.68-2.92-3.19-3.16-2.94-7.75-2-11.48-.73-6.54,2.24-7.24,10.46-8.16,22.58-.55,7.16-5,13.74-10.31,18.66-6.62,6.17-7.59,7.49-10.56,11.54-2.82,3.83-4.58,8.41-8.1,9.57-8,2.64-13.51-5.64-16.32-7.85-1.18-.93-.92.21.79,4.06,2.28,5.14,5.3,8.54,10.37,12.66,1.34,1.1,3.28,1.12,3.2,3.34a25.15,25.15,0,0,1-.74,5.91c-.35,1.17.47,4.64,2.68,4.81,5,.37,1.5-.19,9.84.15,5.59.23,7.55.51,9-.77A5,5,0,0,0,67.41,100c-.42-1.68-2.55-3.43-5.52-4.14-.46-.11,4.37-.75,8.25-2.14A32.64,32.64,0,0,0,75,91.56a24.19,24.19,0,0,0,7.27-5.38c.79-.9,1.9,3.17,2,3.49,2.37,6.27,5.21,8.5,5.21,11.17,0,1.92,1,3,3.17,3.21,2.31.26,1.35.31,9.49,1.06a5.33,5.33,0,0,0,2-.57,1.33,1.33,0,0,0,.48-1.76h0s-.72-.64-2.93-2.47A4.43,4.43,0,0,0,104.82,98.32Z" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M101.65,100.31c-3.27-1.74-3.9-1.92-4.53-2.3a13.5,13.5,0,0,1-2.35-1.8c-1.43-1.44-.9-3.3-2-8.86,0,0-1.06-5-2.72-5.6" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M97.09,41c-4.12,3.17-7.15,3.21-10.65,3.17-2.37,0-10.37-3.33-10.37-3.33" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M96.79,45.59A15.74,15.74,0,0,1,85.8,49c-2.37,0-10.66-3.44-10.65-3.43" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M61.15,79.66a6,6,0,0,1,5.4,2.46c1.52,2.18.6,5.34-.61,8.42-1,2.59-2.17,4.53-4.54,5.57-3.7,1.63-8.33.62-11.79-2.45" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M94.18,19.84c2,2.14.88,5.84-1.38,13.08-1.56,5-2.85,6.21-3.93,6.88a7.52,7.52,0,0,1-6.87.49c-3.8-1.74-4.36-6.42-4.54-7.9-.08-.62-.72-6.83,4-10.87C85.19,18.26,91.61,17.13,94.18,19.84Z" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M48.85,81C47,83,47.11,83.46,46.08,85.88a16.82,16.82,0,0,0-1.22,8.52" transform="translate(-4.62 -1.5)"/><path class="cls-1" d="M84.71,45.69a42.48,42.48,0,0,1-20.95-1.63C49.11,39,42,27.37,40.19,24.09" transform="translate(-4.62 -1.5)"/></svg>
            <h1 class="h1 -landing">PAWTOPIA</h1>
            <h2 class="h2 -landing">Let's walk together !</h2>
            <p class="introduction">L'application mobile pour les amoureux de balades</p>
            <i class="icon icon__down"></i>
            <video id="video" autoplay muted loop class="video-responsive">
                <source id="source" src="" type="video/mp4">
            </video>
        </header>
        <section class="information">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="texte">
                            <h2 class="h2">Rejoignez la communauté !</h2>
                            <p>Pawtopia est un service gratuit qui a pour but de rassembler tout les amoureux de balades en compagnie de leur chien.</p>
                            <p>Il propose un outil qui permet de mettre en relation rapidement et simplement les maîtres et leur chiens afin d'organiser des balades communautaires.</p>
                        </div>
                        <div class="numbers">
                            <div class="numbers__element">
                                <div class="element__flex">
                                    <i class="icon icon__footer icon-multiple-11"></i>
                                    <?php 

                                        $query = $link->prepare("SELECT count(*) c FROM user");
                                        $query->execute();

                                        $result = $query->get_result();
                                        $row = $result->fetch_assoc();

                                        //var_dump($row);
                                    ?>
                                    <span class="members__title">Membres</span>
                                </div>
                                <span clas="member__number"><?php echo $row['c'] ?></span>
                            </div>
                            <div class="numbers__element">
                                <div class="element__flex">
                                    <i class="icon icon__footer icon-calendar-60"></i>
                                    <span class="walks__title">Balades</span>
                                </div>
                                <?php 

                                    $query = $link->prepare("SELECT count(*) c FROM event");
                                    $query->execute();

                                    $result = $query->get_result();
                                    $row = $result->fetch_assoc();

                                    //var_dump($row);
                                ?>
                                <span clas="walks__number"><?php echo $row['c'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="walks">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="walks__container">
                            <div class="walks__element">
                                <h2 class="h2">Trouver une balade ? Rien de plus simple !</h2>
                                <h3 class="h3">D'un simple coup d'oeil vous trouverez les balades qui vous correspondent</h3>
                                <p>Que vous aimiez courir avec votre compagnon à 4 pattes, vous amuser dans un parc ou tout simplement découvrir de nouveaux endroits insolites dans votre ville, tout est directement visible via une carte détaillée des balades créées par nos membres.</p>

                            </div>
                            <div class="walks__element">
                                <div class="layers">
                                    <img class="shadow__layer" src="/app/src/assets/img/ressources/shadowlayer.png"/>
                                    <img class="phone__layer" src="/app/src/assets/img/ressources/phonelayer.png"/>
                                    <img class="map__layer" src="/app/src/assets/img/ressources/layermap.png"/>
                                </div>                         
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="specs">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h2 class="h2">Vous, votre chien et plus encore ! </h2>
                        <div class="card__container">
                            <div class="card">
                                <img class="card__img" src="/app/src/assets/img/ressources/review.png"/>
                                <div class="card__text">
                                    <h3 class="h3">Votre avis compte !</h3>
                                    <p>Une belle rencontre ? Une mauvaise surprise ?</p>
                                    <p>Utilisez le système de review pour aider nos membres à trouver leur partenaire de balade idéale !</p>
                                </div>
                            </div>
                            <div class="card">
                                <img class="card__img -extra" src="/app/src/assets/img/ressources/localisation.png"/>
                                <div class="card__text">
                                    <h3 class="h3">Vos données, sécurisées</h3>
                                    <p>Le service utilise un système de géolocalisation non intrusive qui vous permet en un clin d'œil de trouver des balades autour de vous.</p>
                                </div>
                            </div>
                            <div class="card">
                                <img class="card__img" src="/app/src/assets/img/ressources/messages.png"/>
                                <div class="card__text">
                                    <h3 class="h3">Restez en contact !</h3>
                                    <p>Vous avez des questions à propos d'une balade ? Vous voulez garder contact avec un maître ?</p>
                                    <p>Discuter en direct ou en différé grâce à notre messagerie instantané intégrée !</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h2 class="h2">Rejoignez notre communauté dès maintenant !</h2>
                        <div class="stores">
                            <img class="store" src="/app/src/assets/img/ressources/android.png"/>
                            <img class="store" src="/app/src/assets/img/ressources/apple.png"/>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </body>
    <script src="./app/src/scripts/lib/bootstrap.min.js"></script>
    <script src="https://cdn.pubnub.com/sdk/javascript/pubnub.4.24.1.js"></script>
    <script src="./app/src/scripts/landing.js"></script>

    <script>

        var url ="./app/src/assets/video/pawtopia_";
        var urlEnd = ".mp4"

        var choice = Math.floor((Math.random() * 3) + 1);
        var finalURL = url + choice + urlEnd;
        var source = document.getElementById('source');

        source.setAttribute('src',finalURL);
        video.load();
        video.play();
    </script>
</html>
