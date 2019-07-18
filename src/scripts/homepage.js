$(document).ready(function(){

    let height = $( window ).height();
    let resized = false;

    let login = true;

    $("#submit").click(function(e){
        e.preventDefault();

        $.post(
            'src/php/connect.php',
            {
            username : $("#login_username").val(),
            password : $("#login_password").val()
            },

            function(data){

                if(data == 'Success'){
                    return window.location.href = "app.php";
                    localStorage.setItem('page', 0);
                }else{
                    $("#log_error").html("<p>La connexion a echouée</p>");
                }
            },
            'text'
        );
    });

    $( window ).resize(function(){
        if($('body').is('.landing')){

            console.log($( window ).height());
            console.log(height + 50);

            if ( $( window ).height() + 50 < height && resized == false ){
             
                if ( $('.logo').css('display') == 'block')
                    $('.logo').css('display','none');
                else
                    $('.logo').css('display','block');

                if ($('.container__action').css('position') == 'absolute'){
                    $('.container__action').css('position','relative');
                    $('.container__action').css('padding-top','20px');
                    $('body').css('overflow','auto');
                } else {
                    $('.container__action').css('position','absolute');
                    $('body').css('overflow','hidden');
                }
                resized = true;
            }else{
     
                resized = false;
                $('.logo').css('display','block');
                $('.container__action').css('position','absolute');
                $('body').css('overflow','hidden');
            }
        }
    });

    function showPassword(input, icon){
        if (input.type === "password") {
            input.type = "text";
            $(icon).removeClass('icon-ic_visibility_48px');
            $(icon).addClass('icon-ic_visibility_off_48px');
        } else {
            input.type = "password";
            $(icon).removeClass('icon-ic_visibility_off_48px');
            $(icon).addClass('icon-ic_visibility_48px');
        }
    }

    $('body').css('overflow','hidden');

    $('i').click(function(){
        showPassword($(this).parent().children()[1],$(this).parent().children()[0]);
    });

    setTimeout(function(){
        $('.container__introduction').animate({"opacity": "0"}, "slow");
    },2000)

    setTimeout(function(){
        $('.container__introduction p').html("Let's walk together !");
    },2500)

    setTimeout(function(){
        $('.container__introduction').animate({"opacity": "1"}, "fast");
    },4000)

    setTimeout(function(){
        $('.container__introduction').animate({"opacity": "0"}, "slow");
    },6000)

    setTimeout(function(){
        $('.container__introduction').css('display','none');
    },6500)

    setTimeout(function(){
        $('.login').animate({"opacity": "1"}, "slow");
    },7000)

    $('.choice').click(function(){

        if(login){
            $('.signup').css('display','block');
            $('.login').css('display','none');
        }else{
            $('.signup').css('display','none');
            $('.login').css('display','block');
        }
        login = !login;
    });

    $("#register").click(function(e){
        e.preventDefault();

        let town__name = $("#town").val();
        let town;

        if (town__name != "")
            town = $('#towns [value="' + town__name + '"]').data('value');
        else
            town = null;

        console.log(town);

        $.post(
            'src/php/register.php',
            {
            username : $("#username").val(),
            email : $("#email").val(),
            town : town,
            password_1 : $("#password_1").val(),
            password_2 : $("#password_2").val()
            },

            function(data){

            	console.log(data);

                $("#resultat").html(" ");
                $("#username_error").html(" ");
                $("#email_error").html(" ");
                $("#town_error").html(" ");
                $("#password_error").html(" ");

                if(data == 'Success'){
                    $("#resultat").append("<p>Votre inscription est validée</p>");

                    setTimeout(function(){
                        $('.signup').animate({"opacity": "0"}, "fast");
                        setTimeout(function(){
                            $('.signup').css('display','none');
                        },500)
                        setTimeout(function(){
                            $('.login').css('display','block');
                        },1000)
                        setTimeout(function(){
                            $('.login').animate({"opacity": "1"}, "fast");
                        },1500)
                    },2000)
                }else{
                    if(data.indexOf('username_needed') > -1){
                        $(".register__name").addClass("-error");
                        // $("#username_error").append("<p>Veuillez entrer votre pseudonyme</p>");
                    }else{
                        if ($('.register__name').hasClass('-error'))
                            $(".register__name").removeClass("-error");
                    }
                    if(data.indexOf('email_needed') > -1){
                        $(".register__mail").addClass("-error");
                    }else{
                        if ($('.register__mail').hasClass('-error'))
                            $(".register__mail").removeClass("-error");
                    }
                    if(data.indexOf('password_needed') > -1){
                        $(".register__password1").addClass("-error");
                        $(".register__password2").addClass("-error");
                        // $("#password_error").append("<p>Veuille entrer votre mot de passe</p>");
                    }else{
                        if ($('.register__password1').hasClass('-error'))
                            $(".register__password1").removeClass("-error");
                        if ($('.register__password2').hasClass('-error'))
                            $(".register__password2").removeClass("-error");
                    }

                    if(data.indexOf('town_needed') > -1){
                        // $("#resultat").append("<p>Entrez votre ville svp</p>");
                        // $("#town_error").append("<p>Veuillez entrer votre ville</p>");
                        $("#town").addClass("-error");
                    }else{
                        if ($('#town').hasClass('-error'))
                            $("#town").removeClass("-error");
                    }

                    if(data.indexOf('password_no_match') > -1){
                        $(".register__password1").addClass("-error");
                        $(".register__password2").addClass("-error");
                        $("#resultat").append("<p>Les deux mots de passe ne sont pas identiques</p>");
                    }

                    if(data.indexOf('user_taken') > -1){
                        $("#resultat").append("<p>Le pseudonyme est déjà utilisé</p>");
                        $(".register__name").addClass("-error");
                    }
                    if(data.indexOf('mail_taken') > -1){
                        $("#resultat").append("<p>L'adresse mail est déjà utilisée</p>");
                        $(".register__mail").addClass("-error");
                    }
                    if(data.indexOf('town_unknown') > -1){
                        $("#town_error").append("<p>Vérifiez votre ville</p>");
                        $(".register__town").addClass("-error");
                    }
                }
            },
            'text'
        );
    });
});