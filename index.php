<?php 
    require_once("src/php/bdd.php");
    session_start();
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Pawtopia | Let's walk together</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,500,700|Fira+Sans:300,400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="src/styles/app.css">
    <link rel="stylesheet" type="text/css" href="src/styles/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="src/styles/sanitize.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/projets/tfe/beta/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/projets/tfe/beta/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/projets/tfe/beta/favicon-16x16.png">
    <link rel="manifest" href="/projets/tfe/beta/site.webmanifest">
    <link rel="mask-icon" href="/projets/tfe/beta/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/projets/tfe/beta/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="/projets/tfe/beta/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!-- Méta Google -->
    <meta name="title" content="Pawtopia" />
    <meta name="description" content="Pawtopia permet aux chiens et à leur maître de trouver des partenaires pour leurs balades." />

    <!-- Métas Facebook Opengraph -->
    <meta property="og:title" content="Pawtopia" />
    <meta property="og:description" content="Pawtopia permet aux chiens et à leur maître de trouver des partenaires pour leurs balades." />
    <meta property="og:url" content="https://dylanernoud.be/projets/tfe/beta/" />
    <meta property="og:image" content="https://dylanernoud.be/projets/tfe/beta/favicon.ico" />
    <meta property="og:type" content="website"/>

    <!-- Métas Twitter Card -->
    <meta name="twitter:title" content="Pawtopia" />
    <meta name="twitter:description" content="Pawtopia permet aux chiens et à leur maître de trouver des partenaires pour leurs balades." />
    <meta name="twitter:url" content="https://dylanernoud.be/projets/tfe/beta/" />
    <meta name="twitter:image" content="https://dylanernoud.be/projets/tfe/beta/favicon.ico" />
    </head>
    <body class="landing">
        <div class="mobile">
            <div class="dotting"></div>

            <svg class="logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 235 54"><g fill="none" stroke="#fff" stroke-width="1.5" stroke-miterlimit="10"><path d="M36.44 30.58c-.01 7.38-.9 13.04-1.8 15.93-.8 2.62-2.15 4.52-3.27 5.65-.08.1-1.28.98-.86 1.2 0 0 .92.08 1.05.07 1.9-.12 4.16-.01 9.07-4.48 1.93-1.75.87-1.5 1.58-1.5l33.34-.14c.8-.06 1.3-.35 1.57-.82.13-.22.16-.5.2-1.02.03-.3.03-.54 0-1.3l-.04-1.1c-.06-1.13-.13-24.76 0-28.98.01-.23.03-.85-.37-1.3-.32-.37-.8-.47-1.16-.55-.82-.17-1.5-.03-1.57-.03l-35.66.1c-.26-.01-1.2-.13-1.7.4-.3.32-.4.8-.4.9-.1.46 0 .83.01.9v16.1m-.17 5.5l-33.34-.14c-.8-.06-1.3-.35-1.57-.82-.13-.22-.16-.5-.2-1.02-.03-.3-.03-.54 0-1.3l.04-1.1c.06-1.13.13-24.76 0-28.98-.01-.22-.04-.84.36-1.3.32-.37.8-.47 1.16-.55C3.53.73 4.2.87 4.28.87l35.66.1c.26-.01 1.2-.13 1.7.4a1.77 1.77 0 0 1 .41.89c.04.6.01 1.3.01 1.3v8.75"/><path d="M47.5 41.8c-.06-.12-.14-.25-.13-.4.04-.6 1.28-.48 1.76-1.3.43-.72 0-1.53.34-2.73.58-2.03 1.35-1.54 1.44-3.67.03-.6-.38-.9-.82-2.03-.6-1.56-.7-3.2-.4-4.68.15-.73.25-1.02.32-2.1.06-.92.1-1.92-.8-2.73-1.17-1.07-2-.66-2.5-1.72-.32-.66-.74-1.46-.23-1.84.78-.57 2 .12 3.05-.5.56-.33.4-.54.93-1 1-.94 2.46-.64 3.65-.23 2.08.7 2.3 3.33 2.6 7.18.17 2.28 1.6 4.37 3.28 5.93 2.1 1.96 2.4 2.38 3.36 3.67.9 1.22 1.46 2.67 2.58 3.04 2.53.84 4.3-1.8 5.2-2.5.37-.3.3.07-.25 1.3-.73 1.63-1.7 2.72-3.3 4.03-.43.35-1.04.35-1.02 1.06.02.54.06 1.28.24 1.88.1.37-.15 1.48-.85 1.53-1.6.12-.48-.06-3.13.05-1.78.07-2.4.16-2.87-.25-.4-.35-.68-.97-.56-1.43.14-.53.8-1.1 1.76-1.3.15-.03-1.4-.24-2.62-.68-.58-.2-1.1-.44-1.54-.7-.8-.44-1.6-.88-2.3-1.7-.25-.3-.6 1-.64 1.1-.75 2-1.66 2.7-1.66 3.55 0 .6-.33.94-1 1.02-.73.08-.43.1-3.02.34-.17.02-.53-.13-.62-.18 0 0-.3-.15-.15-.56 0-.01.23-.2.93-.8-.1-.02-.72-.12-1-.64z"/><path d="M48.5 42.43l1.44-.73s.4-.24.74-.57c.46-.46.3-1.05.65-2.82 0 0 .34-1.58.87-1.78m-2.24-12.97c1.3 1 2.28 1.02 3.4 1 .75-.01 3.3-1.06 3.3-1.06m-6.6 1.53c1.3 1 2.38 1.1 3.5 1.1.75-.01 3.4-1.1 3.4-1.1m4.44 10.83c-.38-.02-1.2.03-1.72.78-.48.7-.2 1.7.2 2.68.32.82.7 1.44 1.44 1.77 1.18.52 2.65.2 3.75-.78"/><path d="M50.9 16.84c-.65.68-.28 1.86.44 4.16.5 1.6.9 1.98 1.25 2.2a2.43 2.43 0 0 0 2.2.16c1.2-.55 1.4-2.04 1.44-2.5.02-.2.23-2.17-1.26-3.46-1.2-1.04-3.24-1.4-4.06-.54zM65.3 36.3c.6.6.55.77.88 1.54.5 1.15.45 2.2.4 2.7M53.9 25.06c1.3.22 3.82.46 6.66-.52 4.66-1.6 6.9-5.3 7.5-6.35M32.2 30.8c.06-.12.14-.25.13-.4-.04-.6-1.28-.48-1.76-1.3-.43-.72 0-1.53-.34-2.73-.58-2.03-1.35-1.54-1.44-3.67-.03-.6.38-.9.82-2.03.6-1.56.7-3.2.4-4.68-.15-.73-.25-1.02-.32-2.1-.06-.92-.1-1.92.8-2.73 1.17-1.07 2-.66 2.5-1.72.32-.66.74-1.46.23-1.84-.78-.57-2 .12-3.05-.5-.56-.33-.4-.54-.93-1-1-.94-2.46-.64-3.65-.23-2.08.7-2.3 3.33-2.6 7.18-.17 2.28-1.6 4.37-3.28 5.93-2.1 1.96-2.4 2.38-3.36 3.67-.9 1.22-1.46 2.67-2.58 3.04-2.53.84-4.3-1.8-5.2-2.5-.37-.3-.3.07.25 1.3.73 1.63 1.7 2.72 3.3 4.03.43.35 1.04.35 1.02 1.06-.02.54-.06 1.28-.24 1.88-.1.37.15 1.48.85 1.53 1.6.12.48-.06 3.13.05 1.78.07 2.4.16 2.87-.25.4-.35.68-.97.56-1.43-.14-.53-.8-1.1-1.76-1.3-.15-.03 1.4-.24 2.62-.68.58-.2 1.1-.44 1.54-.7.8-.44 1.6-.88 2.3-1.7.25-.3.6 1 .64 1.1.75 2 1.66 2.7 1.66 3.55 0 .6.33.94 1 1.02.73.08.43.1 3.02.34.17.02.53-.13.62-.18 0 0 .3-.15.15-.56 0-.01-.23-.2-.93-.8.1-.02.72-.1 1-.64z"/><path d="M31.18 31.45l-1.44-.73s-.4-.24-.74-.57c-.46-.46-.3-1.05-.65-2.82 0 0-.34-1.58-.87-1.78m2.25-12.97c-1.3 1-2.28 1.02-3.4 1-.75-.01-3.3-1.06-3.3-1.06m6.6 1.52c-1.3 1-2.38 1.1-3.5 1.1-.75-.01-3.4-1.1-3.4-1.1M18.3 24.88c.38-.02 1.2.03 1.72.78.48.7.2 1.7-.2 2.68-.32.82-.7 1.44-1.44 1.77-1.18.52-2.65.2-3.75-.78"/><path d="M28.8 5.86c.65.68.28 1.86-.44 4.16-.5 1.6-.9 1.98-1.25 2.2a2.43 2.43 0 0 1-2.2.16c-1.2-.55-1.4-2.04-1.44-2.5-.02-.2-.23-2.17 1.26-3.46C25.95 5.36 28 5 28.8 5.86zM14.4 25.3c-.6.6-.55.77-.88 1.54-.5 1.15-.45 2.2-.4 2.7M25.8 14.08c-1.3.22-3.82.46-6.66-.52-4.66-1.6-6.9-5.3-7.5-6.35"/></g><path d="M109.75 14.5c1.43 1.1 2.15 2.73 2.15 4.9 0 2.32-.75 4.04-2.24 5.16s-3.48 1.7-5.96 1.7h-2.67v7.78H98.1v-21.2h5.56c2.62 0 4.65.55 6.1 1.65zm-2.3 8.46c.9-.66 1.34-1.83 1.34-3.54 0-1.52-.45-2.6-1.34-3.3s-2.17-1-3.83-1h-2.6v8.82h2.55c1.7.01 3-.32 3.9-.98zm17.95 11.06l-1.6-5.32h-8.02l-1.6 5.32h-2.95l6.8-21.17h3.7l6.76 21.17h-3.07zm-8.9-7.7h6.6l-3.3-11.06-3.32 11.06zm36.76-13.48L148.96 34h-3.75l-3.9-17.73L137.38 34h-3.66l-4.3-21.17h2.86l3.4 18.62 4.1-18.62h3.1l4.18 18.62 3.53-18.62h2.67zm16.34 0l-.3 2.5h-5.87v18.7h-2.92v-18.7h-6.02v-2.5h15.12zm14.57.9c1.34.85 2.4 2.1 3.13 3.73s1.12 3.63 1.12 5.96c0 2.3-.37 4.26-1.12 5.9s-1.8 2.9-3.13 3.75-2.9 1.3-4.7 1.3-3.35-.42-4.7-1.26-2.4-2.08-3.13-3.72c-.75-1.64-1.12-3.62-1.12-5.93 0-2.27.37-4.24 1.12-5.9s1.8-2.92 3.15-3.8 2.9-1.3 4.67-1.3c1.78 0 3.34.43 4.7 1.28zm-9 3.2c-1.02 1.4-1.54 3.56-1.54 6.52 0 2.93.52 5.08 1.55 6.45s2.46 2.06 4.3 2.06c3.9 0 5.84-2.85 5.84-8.54 0-5.72-1.95-8.57-5.84-8.57-1.84-.01-3.28.7-4.3 2.08zm29.66-2.45c1.43 1.1 2.15 2.73 2.15 4.9 0 2.32-.75 4.04-2.24 5.16-1.5 1.13-3.48 1.7-5.96 1.7h-2.67v7.78h-2.92v-21.2h5.56c2.62 0 4.65.55 6.08 1.65zm-2.3 8.46c.9-.66 1.34-1.83 1.34-3.54 0-1.52-.45-2.6-1.34-3.3s-2.17-1-3.83-1h-2.6v8.82h2.55c1.7.01 3-.32 3.9-.98zm11.43-10.12V34h-2.92V12.84h2.92zm17.44 21.18l-1.6-5.32h-8.02l-1.6 5.32h-2.95l6.8-21.17h3.7l6.76 21.17h-3.07zm-8.92-7.7h6.6l-3.3-11.06-3.32 11.06z" stroke="#fff" stroke-miterlimit="10" fill="#fff"/></svg>

            <video id="video" autoplay muted loop class="video-responsive">
                <source id="source" src="" type="video/mp4">
            </video>
            <div class="container__action">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="login" style="opacity:0">
                            <div class="log_error" id="log_error"></div>
                                <form>
                                    <div class="input__container">
                                        <input placeholder="Nom d'utilisateur" class="input -transparent login__name" type="text" id="login_username" />
                                    </div>
                                    <div class="input__container">
                                        <i class='icon icon-ic_visibility_48px'></i>
                                        <input placeholder="Mot de passe" class="input -transparent login__pwd" type="password" id="login_password" />
                                    </div>
                                    <input class="input button login__submit" type="submit" id="submit" value="Se connecter" />
                                </form>
                                <span class="choice">S'inscrire</span>
                            </div>
                            <div class="signup" style="display:none;">
                                <div class="log_error" id="resultat"></div>
                                <form>
                                    <div class="input__container">
                                        <!-- <div class="log_error" id="username_error"></div> -->
                                        <input placeholder="Votre pseudo" class="input -transparent register__name" type="text" name="username" id="username">
                                    </div>
                                    <div class="input__container">
                                        <!-- <div class="log_error" id="email_error"></div> -->
                                        <input placeholder="Votre adresse mail" class="input -transparent register__mail" type="email" name="email" id="email">
                                    </div>

                                    <?php
                                        $link = mysqli_connect(HOST, USER, PWD, BASE);
                                        mysqli_query($link, "SET NAMES UTF8");
                                        
                                        $sql = "SELECT * FROM towns";
                                        $query = mysqli_query($link,$sql);
                                        while ( $results[] = mysqli_fetch_object ( $query ) );
                                        array_pop ( $results );
                                    ?>
                                    <div class="input__container">
                                        <!-- <div class="log_error" id="town_error"></div> -->
                                        <select required placeholder="Votre ville" class="select -transparent register__town" name="town" id="town">
                                            <option value="" disabled selected hidden>Choisissez votre ville</option>
                                            <?php foreach ( $results as $option ) : ?>
                                                <option value="<?php echo $option->ID; ?>"><?php echo $option->NAME; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="input__container">
                                        <!-- <div class="log_error" id="password_error"></div> -->
                                        <i class='icon icon-ic_visibility_48px'></i>
                                        <input placeholder="Entrez votre mot de passe" class="input -transparent register__password1" type="password" name="password_1" id="password_1">
                                    </div>
                                    <div class="input__container">
                                        <!-- <div class="log_error" id="password_error"></div> -->
                                        <i class='icon icon-ic_visibility_48px'></i>
                                        <input placeholder="Entrez votre mot de passe" class="input -transparent register__password2" type="password" name="password_2" id="password_2">
                                    </div>
                                    <input class="input button login__submit -register" type="submit" id="register" value="S'enregister" />
                                </form>
                                <span class="choice">Se connecter</span>
                            </div>
                        </div>
                    </div>       
                </div>      
            </div>
            <div class="container__introduction">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <p class="introduction">Meet people with the same passion</p>
                            <!-- <button class="button -transparent together">LET'S WALK TOGETHER</button> -->
                        </div>
                    </div>
                </div>       
            </div>      
        </div>
        <div class="desktop">
            <h1 class="wip">Ceci n'est pas une landing page</h1>
        </div>
    </body>
    <script src="src/scripts/jquery-3.4.0.min.js"></script>
    <script src="src/scripts/bootstrap.min.js"></script>
    <script src="src/scripts/jquery.touchSwipe.min.js"></script>
    <script src="src/scripts/homepage.js"></script>
    <script src="src/scripts/app.js"></script>

    <script>

        var url ="src/assets/video/pawtopia_";
        var urlEnd = ".mp4"

        var choice = Math.floor((Math.random() * 3) + 1);
        var finalURL = url + choice + urlEnd;
        var source = document.getElementById('source');

        source.setAttribute('src',finalURL);
        video.load();
        video.play();
    </script>
</html>
