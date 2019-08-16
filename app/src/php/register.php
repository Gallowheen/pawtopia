<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    $errors = array();

    $username = mysqli_real_escape_string($link , $_POST['username']);
    $email = mysqli_real_escape_string($link , $_POST['email']);
    $town =  mysqli_real_escape_string($link , $_POST['town']);
    $password_1 = mysqli_real_escape_string($link , $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($link , $_POST['password_2']);

    if (empty($username))
    {
        array_push($errors, "Username is required");
        echo 'username_needed';
    }
    if (empty($email))
    {
        array_push($errors, "Email is required");
        echo 'email_needed';
    }
    if (empty($town)) {
        array_push($errors, "Town is required");
        echo 'town_needed';
    }
    if (empty($password_1) || empty($password_2)  ) {
        array_push($errors, "Password is required");
        echo 'password_needed' ;
    }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
        echo 'password_no_match';
    }

    $user_check_query = $link->prepare("SELECT * FROM user WHERE USERNAME = ? OR EMAIL = ? LIMIT 1");
    $user_check_query->bind_param("ss", $username, $email);
    $user_check_query->execute();

    $result = $user_check_query->get_result();

    $town_check_query = $link->prepare("SELECT ID FROM towns WHERE ID = ? ");
    $town_check_query->bind_param("s", $town);
    $town_check_query->execute();

    $result_town = $town_check_query->get_result();

    //check if exist
    if($result_town->num_rows > 0){
        $row = $result_town->fetch_assoc();
        $townToInsert = $row['ID'];
    }else{
        array_push($errors, "town is unknown");
        // if ($town != null)
        echo "town_unknown";
    }

    //check if exist
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();

        if (isset($row['USERNAME']) && $row['USERNAME'] == $username ){
            echo 'user_taken';
        }

        if (isset($row['EMAIL']) && $row['EMAIL'] == $email){
            echo 'mail_taken';
        }

    }else{

        //if no error
        if (count($errors) == 0) {

            // Set up avatar
            $firstLetter = strtolower($username[0]);
            $avatarPath = 'src/assets/img/avatar/default_'.$firstLetter.'.png';

            // create user in DB
            $password = md5($password_1);
            $query = "INSERT INTO user (USERNAME, EMAIL, TOWN_ID, PASSWORD, AVATAR)
                        VALUES('$username', '$email', '$townToInsert', '$password', '$avatarPath')";
            mysqli_query($link, $query);
            $id = mysqli_insert_id($link);

            // create user directory
            $newDirectory = "../../users/" . $id . "_" . $username;
            mkdir($newDirectory, 0777);

            // create dogs directory
            $newDirectory = "../../users/" . $id . "_" . $username.'/dogs';
            mkdir($newDirectory, 0777);

            // create dogs directory
            $newDirectory = "../../users/" . $id . "_" . $username.'/avatar';
            mkdir($newDirectory, 0777);

            //relocate
            //header("location:/pawtopia?register=ok");
            echo "Success";
        }
    }
?>