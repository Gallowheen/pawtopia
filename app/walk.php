<?php
    @session_start();
    require_once("src/php/bdd.php");
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
        echo 0;
        exit;
    }
    ob_start();
?>
    <div class="container">
        <div class="row">
            <div class="">
                <!-- <div class="input__container -first -right">
                    <button class="button -color -blue -add" id="new_walk">Créer une balade</button>
                </div> -->
                <!-- <h3 class="h3 -title">Vos balades à venir</h3>
                <div class="user_walk">

                </div> -->
                <!-- <h3 class="h3 -title">Trouvez votre balade idéale</h3> -->
                <div class="find__more">
                    <!-- <button class="button -color -blue -inverted" id="filter">Filtrer les balades</button> -->
                    <div id="filter" class="option">
                        <i class="icon icon-zoom-2"></i><span>Filtrer</span>
                    </div>
                    <div id="new_walk" class="option">
                        <i class="icon icon-add_event" ></i><span>Créer</span></div>
                    </div>
                <div class="map__container">
                    <div class="whereami">
                        <i class="icon icon-ic_location_searching_48px" ></i>
                    </div>
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
                    <div class="map" id="map"></div>
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
                            <h3 class="h4 filter__title">Type de balade choisi</h3>
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

    <script src="src/scripts/walk.js"></script>
<?php
    $html = ob_get_contents();
    ob_end_clean();
    echo $html;

    ob_start();
    $_GET['date'] = date("Y-m-d");
    $_GET['LAT'] = $_SESSION['LAT'];
    $_GET['LON'] = $_SESSION['LON'];
    include ('src/php/managewalk.php');
    $walks = ob_get_contents();
    ob_end_clean();
?>
    <script>
        var walks = <?php echo $walks; ?>;
    </script>