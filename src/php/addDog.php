<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    if (empty($_GET)){
        echo '<div class="dog_card">';
            echo "<h4 data-editable class='dog_name'>Nom</h4>";
                echo'<div class="dog_button -add">';
            echo '<div class="dog_img -add"><i class="icon dog_add_icon icon-ic_photo_camera_48px"></i></div></div>';
            
            echo '<div class="dog_information">';
                    echo '<div class="information_group -dog"><p data-editable class="information_title">Sexe</p>';
                    echo '<div class="information_group -dog"><p data-editable class="information_title">Age</p>';

                    $sql = "SELECT * FROM breeds";
                    $query = mysqli_query($link,$sql);
                    while ( $results[] = mysqli_fetch_object ( $query ) );
                    array_pop ( $results );

                    echo '<select required placeholder="Race" class="select -transparent -small" name="breed" id="breed">';
                            echo '<option value="" disabled selected hidden>Race</option>';
                            foreach ( $results as $option ) : 
                                echo'<option value="'.$option->ID.'">'.$option->NAME.'</option>';
                            endforeach;
                        echo'</select>';
            echo'</div>';
        echo'</div>';
        echo'<button class="button -color -blue -margintop">Ajouter</button>';
    }
    else{

    }
?>