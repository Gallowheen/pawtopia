<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    if (empty($_GET)){
        echo '<div class="dog_card">';
            echo "<h4 data-editable class='dog_name -nolimit'>Nom<i class='icon edit icon-ic_edit_48px'></i></h4>";
                echo'<label for="uploadfiles" class="label-file">Choisir une image</label><input class="input-file" type="file" id="uploadfiles" accept="image/*"></input>';
                echo'<div class="dog_button -add">';
            //echo '<div class="dog_img -add"><i class="icon dog_add_icon icon-ic_photo_camera_48px"></i></div></div>';
            echo '<div>';
            echo '<div id="gallery"></div>';
            echo '<div id="gallery__button"></div></div>';
            echo '<div class="dog_information">';
                    echo '<div class="information_group -dog">
                    <select class="select -addDog" id="sexe">
                        <option value="M">Mâle</option>
                        <option value="F">Femelle</option>
                    </select>';
                    echo '<div class="information_group -dog">
                    <select class="select -addDog" id="age">
                        <option value="1">1 ans</option>
                        <option value="2">2 ans</option>
                        <option value="3">3 ans</option>
                        <option value="4">4 ans</option>
                        <option value="5">5 ans</option>
                        <option value="6">6 ans</option>
                        <option value="7">7 ans</option>
                        <option value="8">8 ans</option>
                        <option value="9">9 ans</option>
                        <option value="10">10 ans</option>
                        <option value="11">11 ans</option>
                        <option value="12">12 ans</option>
                        <option value="13">13 ans</option>
                        <option value="14">14 ans</option>
                        <option value="15">15 ans</option>
                    </select>';

                    $sql = "SELECT * FROM breeds";
                    $query = mysqli_query($link,$sql);
                    while ( $results[] = mysqli_fetch_object ( $query ) );
                    array_pop ( $results );

                    echo '<select required placeholder="Race" class="select -addDog" name="breed" id="breed">';
                            echo '<option value="" disabled selected hidden>Race</option>';
                            foreach ( $results as $option ) :
                                echo'<option value="'.$option->ID.'">'.$option->NAME.'</option>';
                            endforeach;
                        echo'</select>';
            echo'</div>';
        echo'</div>';
        echo'<button id="addDog" class="button -color -blue -margintop">Ajouter</button>';
    }
    else{
        $query = $link->prepare("SELECT USERNAME FROM user WHERE ID = ?");
        $query->bind_param("i", $_SESSION['ID']);
        $query->execute();

        $result = $query->get_result();
        $row = $result->fetch_assoc();

        $owner_name = $row['USERNAME'];
        $breed = $_GET['breed'];
        $gender = $_GET['sexe'];
        $age = $_GET['age'];
        $picture = $_GET['image'];
        $owner = $_SESSION['ID'];
        $name = $_GET['name'];
        $active = 1;
        if ($picture === "none"){
            $picture = 'src/assets/img/avatar/dog_avatar.png';
        }else{
            $picture = 'users/'.$_SESSION['ID'].'_'.$owner_name.'/dogs/'.$name.'/'.$picture;
        }

        $query = $link->prepare("INSERT INTO `dog`(`BREED_ID`, `GENDER`, `AGE`, `PICTURE`, `OWNER_ID`, `NAME`, `ACTIVE`)  VALUES (?,?,?,?,?,?,?)");
        $query->bind_param("isissss", $breed, $gender, $age, $picture, $owner, $name, $active);
        $query->execute();
    }
?>