<?php 
    require_once("bdd.php");
    $link = mysqli_connect(HOST, USER, PWD, BASE);
    mysqli_query($link, "SET NAMES UTF8");
?>


<header class="header">
    <div class="container">
        <div class="row">
            <div class="col-10 relative">
                <div class="title">
                    <button onclick="history.go(-1);"><i class="left"></i></button>

                    <h1>
                    <?php 
                        $user;
                        if (empty($_GET))
                            $user = $_SESSION['ID'];
                        else
                            $user = $_GET['ID'];

                        if($user) {
                            $query = $link->prepare("SELECT * FROM user WHERE ID = ?");
                            $query->bind_param("i", $user);
                            $query->execute();

                            $result = $query->get_result();
                            if($result->num_rows === 0){
                                echo 'Error';
                                $error = true;
                                //redirect ?
                            }
                            $row = $result->fetch_assoc();

                            echo $pagename;
                        }       
                    ?>
                    </h1>
                </div>
            </div>
            <div class="col-2 relative">
               
            </div>
        </div>
    </div>
</header>