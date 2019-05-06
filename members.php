<?php 
    require_once("src/php/bdd.php");
    session_start();
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
?>

<!DOCTYPE html>
<html>
  <head>
    <title>DWMA project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,500,700|Fira+Sans:300,400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="src/styles/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="src/styles/app.css">
    <link rel="stylesheet" type="text/css" href="src/styles/sanitize.css">
    <style>
        
    </style>

    </head>
    <body>
        <?php 
        include ('src/php/header.php');
        ?>
        <div class="member__content">
            <div class="member__filtred">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <h4>Trouvez votre partenaire idéale !</h4>
                            <button class="button" id="filter">Filtrer</button>
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
                            <h4>Aidez nous à trouver votre partenaire idéale de balade</h4>
                            <form>
                                <p id="slider_value">50 km</p>
                                <span>0</span>
                                <input class="slider" type="range" id="slider" name="km" min="0" max="100">
                                <span>100</span>
                                <input type="radio" name="walk" value="Sportive" checked> Sportive<br>
                                <input type="radio" name="walk" value="Découverte"> Découverte<br>
                                <input type="radio" name="walk" value="Récréative">Récréative<br><br>
                                <input id="submit" type="submit">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            include('src/php/footer.php');
        ?>
    </body>
    <script src="src/scripts/jquery-3.4.0.min.js"></script>
    <script src="src/scripts/bootstrap.min.js"></script>
    <script src="src/scripts/app.js"></script>
    <script>
        $(document).on('input', '#slider', function() {
            $('#slider_value').html( $(this).val() + " km" );
        });

        $('#submit').click(function(){

            $.ajax({
				method: "GET",
                data:{
                    walk:$('form input[type=radio]:checked').val(),
                    km : $('#slider').val()
                },
				url:"src/php/managefriend.php",
            })
            .done(function(result){ 
                console.log(result);

                $(".members__handler__container").css('background','transparent'); 

                setTimeout(function(){
                    $(".members__handler__container").css('transform','translateY(100%)'); 
                    $("body").css('overflow','auto');
                }, 750);
            });
        });

        $('#filter').click(function(){
            $("body").css('overflow','hidden');
            $(".members__handler__container").css('display','block');

            setTimeout(function(){
                $(".members__handler__container").css('transform','translateY(0%)'); 
            }, 500);

            setTimeout(function(){
                $(".members__handler__container").css('background','#00000024'); 
            }, 1500);
        });

    </script>
</html>
