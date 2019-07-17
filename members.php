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

    $pagename = 'Membres';

    $error = false;

    if(!isset($_SESSION['ID'])){
        echo 0;
        exit;
    }
    $user = $_SESSION['ID'];
?>

<div class="member__content">
<div class="member__filtred">
    <div class="container">
        <div class="row">
            <div class="col">
                <h3 class="h3 -title -first">Trouvez votre partenaire de balade idéale</h3>
                <div class="showcase__member"><?php include ("src/php/showcase_member.php"); ?></div>
                <div class="find__more -member">
                    <button class="button -color -blue" id="filter">Découvrez nos membres</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="members__handler__container">
<div class="members__handler">
    <div class="container">
        <div class="row">
            <div class="col">
                <form>
                    <h3 class="h3 filter__title">Proximité</h3>
                    <div class="slider__value" id="slider_value">15 km</div>
                    <div class="slider__range">
                        <span class="range__number">5</span>
                        <input class="slider" type="range" id="slider" name="km" min="5" max="25">
                        <span class="range__number">25</span>
                    </div>
                    <h3 class="h3 filter__title">Type de balade choisi</h3>
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
                    <div class="submit__button">
                        <button class="button -color -blue -nomargin" id="submit__members" type="submit">C'est parti !</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script src="src/scripts/members.js"></script>