<?php
    require_once("src/php/bdd.php");
    session_start();
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    $pagename = 'Balades';

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
?>

<!DOCTYPE html>
<html>
    <?php
        include ('src/php/head.php');
    ?>
    <body class="walk">
        <?php
            include ('src/php/header.php');
        ?>
        <div class="content_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="input__container -first -right">
                            <button class="button -color -blue -add" id="new_walk">Créer une balade</button>
                        </div>
                        <!-- <h3 class="h3 -title">Vos balades à venir</h3>
                        <div class="user_walk">

                        </div> -->
                        <h3 class="h3 -title">Trouvez votre balade idéale</h3>
                        <div class="map__container">
                            <div class="map__legend">
                                <div class="map__legend__element">
                                    <img class="map__legend__img" src="src/assets/img/ressources/bluetopia.png"/>
                                    <span class="map__legend__title">Récréative</span>
                                </div>
                                <div class="map__legend__element">
                                    <img class="map__legend__img" src="src/assets/img/ressources/redtopia.png"/>
                                    <span class="map__legend__title">Sportive</span>
                                </div>
                                <div class="map__legend__element">
                                    <img class="map__legend__img" src="src/assets/img/ressources/greentopia.png"/>
                                    <span class="map__legend__title">Découverte</span>
                                </div>
                            </div>
                            <div class="map" class="map" id="map"></div>
                        </div>
                        <div class="find__more">
                            <button class="button -color" id="filter">Filtrer les balades</button>
                        </div>
                        <div class="map__container">
                            <div class="map__legend">
                                <div class="map__legend__element">
                                    <img class="map__legend__img" src="src/assets/img/ressources/bluetopia.png"/>
                                    <span class="map__legend__title">Récréative</span>
                                </div>
                                <div class="map__legend__element">
                                    <img class="map__legend__img" src="src/assets/img/ressources/redtopia.png"/>
                                    <span class="map__legend__title">Sportive</span>
                                </div>
                                <div class="map__legend__element">
                                    <img class="map__legend__img" src="src/assets/img/ressources/greentopia.png"/>
                                    <span class="map__legend__title">Découverte</span>
                                </div>
                            </div>
                            <div class="map" class="map" id="mapid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="walk__handler__container">
            <div class="walk__handler">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <form>
                                <!-- <h3 class="h4 filter__title">Proximité</h3>
                                <div class="slider__value" id="slider_value">125 km</div>
                                <div class="slider__range">
                                    <span class="range__number">0</span>
                                    <input class="slider" type="range" id="slider" name="km" min="0" max="250">
                                    <span class="range__number">250</span>
                                </div> -->
                                <h3 class="h4 filter__title">Type de balade préféré</h3>
                                <div class="button_choice">
                                    <label class="label selected" for="Sportive"><div class="button_container">
                                        <input class="hidden" type="radio" name="walk" id="Sportive" value="Sportive" checked>
                                        <i class="icon icon-ic_directions_run_48px icon_walk"></i>
                                        <span class="walk__type">Sportive</span>
                                    </div></label>
                                    <label class="label" for="Découverte"><div class="button_container">
                                        <input class="hidden" type="radio" name="walk" id="Découverte" value="Découverte">
                                        <i class="icon icon-ic_map_48px icon_walk"></i>
                                        <span class="walk__type">Découverte</span>
                                    </div></label>
                                    <label class="label" for="Récréative"><div class="button_container">
                                        <input class="hidden" type="radio" name="walk" id="Récréative" value="Récréative">
                                        <i class="icon icon-ic_pets_48px icon_walk"></i>
                                        <span class="walk__type">Récréative</span>
                                    </div></label>
                                </div>
                                <h3 class="h4 filter__title">Quelle date ?</h3>
                                <div class="date">
                                    <input class="input -walk" placeholder="Quelle date ?" id="date" type="date"></div>
                                </div>
                                <div class="submit__button">
                                    <button class="button -color -blue" id="submit__walks" type="submit">C'est parti !</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?php
        include('src/php/footer.php');
    ?>
    <script src="src/scripts/jquery-3.4.0.min.js"></script>
    <script src="src/scripts/bootstrap.min.js"></script>
    <script src="src/scripts/jquery.touchSwipe.min.js"></script>
    <script src="https://cdn.pubnub.com/sdk/javascript/pubnub.4.24.1.js"></script>
    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
    integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
    crossorigin=""></script>
    <script src="https://unpkg.com/esri-leaflet@2.2.4/dist/esri-leaflet.js"
    integrity="sha512-tyPum7h2h36X52O2gz+Pe8z/3l+Y9S1yEUscbVs5r5aEY5dFmP1WWRY/WLLElnFHa+k1JBQZSCDGwEAnm2IxAQ=="
    crossorigin=""></script>
    <script src="https://unpkg.com/esri-leaflet-geocoder@2.2.14/dist/esri-leaflet-geocoder.js"
    integrity="sha512-uK5jVwR81KVTGe8KpJa1QIN4n60TsSV8+DPbL5wWlYQvb0/nYNgSOg9dZG6ViQhwx/gaMszuWllTemL+K+IXjg=="
    crossorigin=""></script>
    <script src="src/scripts/app.js"></script>
</html>
