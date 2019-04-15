<?php   
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");

    $errors = array(); 

    if (isset($_POST['reg_user'])) {

        $username = mysqli_real_escape_string($link , $_POST['username']);
        $email = mysqli_real_escape_string($link , $_POST['email']);
        $town =  mysqli_real_escape_string($link , $_POST['town']);
        $password_1 = mysqli_real_escape_string($link , $_POST['password_1']);
        $password_2 = mysqli_real_escape_string($link , $_POST['password_2']);
      
        if (empty($username)) { array_push($errors, "Username is required"); }
        if (empty($email)) { array_push($errors, "Email is required"); }
        if (empty($town)) { array_push($errors, "Town is required"); }
        if (empty($password_1)) { array_push($errors, "Password is required"); }
        if ($password_1 != $password_2) { 
          array_push($errors, "The two passwords do not match");
        }  

        $user_check_query = $link->prepare("SELECT * FROM USER WHERE USERNAME = ? OR EMAIL = ? LIMIT 1");
        $user_check_query->bind_param("ss", $username, $email);
        $user_check_query->execute();
    
        $result = $user_check_query->get_result();
        if($result->num_rows === 0) exit('No rows');
        $row = $result->fetch_assoc();

        $user = $row['USERNAME'];

        echo $user;

        $town_check_query = "SELECT ID FROM TOWNS WHERE NAME='$town'";
        $result_town = mysqli_query($link , $town_check_query);
        $town_array = mysqli_fetch_assoc($result_town);
        $townToInsert = $town_array['ID'];

        echo $user;
        
        if ($user) {
            if ($row['USERNAME'] === $username) {
                array_push($errors, "Username already exists");
            }
        
            if ($row['EMAIL'] === $email) {
                array_push($errors, "email already exists");
            }
        }
      
        if (count($errors) == 0) {
            // Set up avatar
            $firstLetter = strtolower($username[0]);

            echo $username;
            echo $firstLetter;
            $avatarPath = 'src/assets/img/avatar/default_'.$firstLetter.'.png';

            $password = md5($password_1);
            $query = "INSERT INTO USER (USERNAME, EMAIL, TOWN_ID, PASSWORD, AVATAR) 
                      VALUES('$username', '$email', '$townToInsert', '$password', '$avatarPath')";
            mysqli_query($link, $query);
            $id = mysqli_insert_id($link);
            // $_SESSION['username'] = $username;
            // $_SESSION['success'] = "You are now logged in";

            // create user directory
            $newDirectory = "../../users/" . $id . "_" . $username;
            mkdir($newDirectory, 0777);

            //header("location:/pawtopia");
        }
      }
      
?>