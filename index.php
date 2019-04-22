<?php 
  session_start();
?>

<!DOCTYPE html>
<html>
  <head>
    <title>DWMA project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,500,700|Fira+Sans:300,400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="src/styles/app.css">
    <link rel="stylesheet" type="text/css" href="src/styles/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="src/styles/sanitize.css">
    <style>

    .video-responsive 
    {
        position: fixed;
        width: 400vw;
        height: 100vh;
        left: -170%;
        filter: blur(2px);
    }

    .logo{
        position : absolute;
        z-index: 100;
        width: 80%;
        left: 10%;
        top: 7.5%;
    }

    button{
        background : transparent;
        border : 2px solid white;
        position : absolute;
        width : 60%;
        left: 20%;
        padding : 10px;
        bottom : 7.5%;
        color : white;
        font-family: 'Fira Sans', sans-serif;
        background: #ffffff36;
        z-index: 100;
    }

    .dotting{
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 50;
        background: linear-gradient( 45deg, transparent, transparent 50%, #00000050, #00000050 );
        background-size: 2px 2px;
    }

    /* @media screen and (min-width: 320px) and (max-width: 812px) and (orientation: landscape)  {

        .logo{
            position : absolute;
            z-index: 100;
            width: 40%;
            left: 30%;
            top: 7.5%;
        }
        html {
            transform: rotate(-90deg);
            transform-origin: left top;
            width: 100vh;
            overflow-x: hidden;
            position: absolute;
            top: 100%;
            left: 0;
        }
    } */

    @media only screen and (orientation:landscape){
        body {
            height: 100vw;
            transform: rotate(90deg);
        }

        .video-responsive {
            width: 400vw;
            height: 250vh;
            left: -180%;
        }

        .logo{
            width: 30%;
            left: 7.5%;
        }

        button{
            width: 30%;
            left: 7.5%;
        }
    }

    </style>
    </head>
    <body>
        <div class="dotting"></div>

        <!-- <svg class="logo" enable-background="new 0 0 227 54" version="1.1" viewBox="0 0 227 54" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
            <style type="text/css">
                .st0{fill:none;stroke:#FFFFFF;stroke-width:1.5;stroke-miterlimit:10;}
                .st1{fill:#FFFFFF;}
            </style>
            <path class="st0" d="m36.44 30.58c-0.01 7.38-0.89 13.04-1.79 15.93-0.81 2.62-2.15 4.52-3.27 5.65-0.08 0.09-1.28 0.98-0.86 1.19 0 0 0.92 0.08 1.05 0.07 1.9-0.12 4.16-0.01 9.07-4.48 1.93-1.75 0.87-1.49 1.58-1.5l33.34-0.14c0.79-0.06 1.29-0.35 1.57-0.82 0.13-0.22 0.16-0.49 0.2-1.02 0.03-0.31 0.03-0.54 0-1.3-0.02-0.63-0.03-0.94-0.04-1.09-0.06-1.13-0.13-24.76 0-28.98 0.01-0.23 0.03-0.85-0.37-1.3-0.32-0.37-0.8-0.47-1.16-0.55-0.82-0.17-1.5-0.03-1.57-0.03l-35.66 0.09c-0.26-0.01-1.2-0.13-1.7 0.41-0.3 0.32-0.4 0.81-0.41 0.89-0.09 0.46 0 0.83 0.01 0.89v16.09"/>
            <path class="st0" d="m36.26 36.09l-33.34-0.14c-0.79-0.06-1.29-0.35-1.57-0.82-0.13-0.22-0.16-0.49-0.2-1.02-0.03-0.31-0.03-0.54 0-1.3 0.02-0.63 0.03-0.94 0.04-1.09 0.06-1.13 0.13-24.76 0-28.98-0.01-0.22-0.04-0.84 0.36-1.29 0.32-0.37 0.8-0.47 1.16-0.55 0.82-0.17 1.5-0.03 1.57-0.03l35.66 0.09c0.26-0.01 1.2-0.13 1.7 0.41 0.3 0.32 0.41 0.81 0.41 0.89 0.04 0.6 0.01 1.31 0.01 1.31v8.75"/>
            <path class="st0" d="m50.15 42"/>
            <path class="st0" d="m49.29 40.81"/>
            <path class="st0" d="m47.5 41.8c-0.06-0.12-0.14-0.25-0.13-0.4 0.04-0.59 1.28-0.48 1.76-1.29 0.43-0.72 0-1.53 0.34-2.73 0.58-2.03 1.35-1.54 1.44-3.67 0.03-0.6-0.38-0.91-0.82-2.03-0.61-1.56-0.7-3.2-0.39-4.68 0.15-0.73 0.25-1.02 0.32-2.11 0.06-0.92 0.09-1.92-0.79-2.73-1.17-1.07-1.99-0.66-2.5-1.72-0.32-0.66-0.74-1.46-0.23-1.84 0.78-0.57 1.99 0.12 3.05-0.5 0.56-0.33 0.41-0.54 0.93-1.01 1-0.94 2.46-0.64 3.65-0.23 2.08 0.71 2.3 3.33 2.59 7.18 0.17 2.28 1.6 4.37 3.28 5.93 2.1 1.96 2.41 2.38 3.36 3.67 0.89 1.22 1.46 2.67 2.58 3.04 2.53 0.84 4.29-1.8 5.19-2.5 0.37-0.29 0.29 0.07-0.25 1.29-0.73 1.63-1.69 2.72-3.3 4.03-0.43 0.35-1.04 0.35-1.02 1.06 0.02 0.54 0.06 1.28 0.24 1.88 0.11 0.37-0.15 1.48-0.85 1.53-1.6 0.12-0.48-0.06-3.13 0.05-1.78 0.07-2.4 0.16-2.87-0.25-0.4-0.35-0.68-0.97-0.56-1.43 0.14-0.53 0.81-1.09 1.76-1.31 0.15-0.03-1.39-0.24-2.62-0.68-0.58-0.21-1.09-0.44-1.54-0.69-0.81-0.44-1.59-0.88-2.31-1.71-0.25-0.29-0.6 1.01-0.64 1.11-0.75 1.99-1.66 2.7-1.66 3.55 0 0.61-0.33 0.94-1.01 1.02-0.73 0.08-0.43 0.1-3.02 0.34-0.17 0.02-0.53-0.13-0.62-0.18 0 0-0.3-0.15-0.15-0.56 0-0.01 0.23-0.21 0.93-0.79-0.1-0.02-0.72-0.12-1.01-0.64z"/>
            <path class="st0" d="m48.51 42.43c1.04-0.55 1.24-0.61 1.44-0.73 0 0 0.41-0.24 0.74-0.57 0.46-0.46 0.29-1.05 0.65-2.82 0 0 0.34-1.58 0.87-1.78"/>
            <path class="st0" d="m49.96 23.56c1.31 1.01 2.28 1.02 3.39 1.01 0.75-0.01 3.3-1.06 3.3-1.06"/>
            <path class="st0" d="m50.06 25.03c1.31 1.01 2.38 1.11 3.49 1.09 0.75-0.01 3.39-1.1 3.39-1.09"/>
            <path class="st0" d="m61.39 35.86c-0.38-0.02-1.2 0.03-1.72 0.78-0.48 0.69-0.19 1.7 0.2 2.68 0.32 0.82 0.69 1.44 1.44 1.77 1.18 0.52 2.65 0.2 3.75-0.78"/>
            <path class="st0" d="m50.89 16.84c-0.65 0.68-0.28 1.86 0.44 4.16 0.5 1.59 0.91 1.98 1.25 2.19 0.62 0.38 1.47 0.48 2.19 0.16 1.21-0.55 1.39-2.04 1.44-2.51 0.02-0.2 0.23-2.17-1.26-3.46-1.21-1.04-3.24-1.4-4.06-0.54z"/>
            <path class="st0" d="m65.3 36.3c0.59 0.61 0.55 0.77 0.88 1.54 0.49 1.15 0.45 2.2 0.39 2.71"/>
            <path class="st0" d="m53.9 25.06c1.31 0.22 3.82 0.46 6.66-0.52 4.66-1.6 6.91-5.31 7.5-6.35"/>
            <path class="st0" d="m32.19 30.81c0.06-0.12 0.14-0.25 0.13-0.4-0.04-0.59-1.28-0.48-1.76-1.29-0.43-0.72 0-1.53-0.34-2.73-0.58-2.03-1.35-1.54-1.44-3.67-0.03-0.6 0.38-0.91 0.82-2.03 0.61-1.56 0.7-3.2 0.39-4.68-0.15-0.73-0.25-1.02-0.32-2.11-0.06-0.92-0.09-1.92 0.79-2.73 1.17-1.07 1.99-0.66 2.5-1.72 0.32-0.66 0.74-1.46 0.23-1.84-0.78-0.57-1.99 0.12-3.05-0.5-0.56-0.33-0.41-0.54-0.93-1.01-1-0.94-2.46-0.64-3.65-0.23-2.08 0.71-2.3 3.33-2.59 7.18-0.17 2.28-1.6 4.37-3.28 5.93-2.1 1.96-2.41 2.38-3.36 3.67-0.89 1.22-1.46 2.67-2.58 3.04-2.53 0.84-4.29-1.8-5.19-2.5-0.37-0.29-0.29 0.07 0.25 1.29 0.73 1.63 1.69 2.72 3.3 4.03 0.43 0.35 1.04 0.35 1.02 1.06-0.02 0.54-0.06 1.28-0.24 1.88-0.11 0.37 0.15 1.48 0.85 1.53 1.6 0.12 0.48-0.06 3.13 0.05 1.78 0.07 2.4 0.16 2.87-0.25 0.4-0.35 0.68-0.97 0.56-1.43-0.14-0.53-0.81-1.09-1.76-1.31-0.15-0.03 1.39-0.24 2.62-0.68 0.58-0.21 1.09-0.44 1.54-0.69 0.81-0.44 1.59-0.88 2.31-1.71 0.25-0.29 0.6 1.01 0.64 1.11 0.75 1.99 1.66 2.7 1.66 3.55 0 0.61 0.33 0.94 1.01 1.02 0.73 0.08 0.43 0.1 3.02 0.34 0.17 0.02 0.53-0.13 0.62-0.18 0 0 0.3-0.15 0.15-0.56 0-0.01-0.23-0.21-0.93-0.79 0.1-0.02 0.72-0.11 1.01-0.64z"/>
            <path class="st0" d="m31.18 31.45c-1.04-0.55-1.24-0.61-1.44-0.73 0 0-0.41-0.24-0.74-0.57-0.46-0.46-0.29-1.05-0.65-2.82 0 0-0.34-1.58-0.87-1.78"/>
            <path class="st0" d="m29.73 12.58c-1.31 1.01-2.28 1.02-3.39 1.01-0.75-0.01-3.3-1.06-3.3-1.06"/>
            <path class="st0" d="m29.64 14.04c-1.31 1.01-2.38 1.11-3.49 1.09-0.75-0.01-3.39-1.1-3.39-1.09"/>
            <path class="st0" d="m18.3 24.88c0.38-0.02 1.2 0.03 1.72 0.78 0.48 0.69 0.19 1.7-0.2 2.68-0.32 0.82-0.69 1.44-1.44 1.77-1.18 0.52-2.65 0.2-3.75-0.78"/>
            <path class="st0" d="m28.81 5.86c0.65 0.68 0.28 1.86-0.44 4.16-0.5 1.59-0.91 1.98-1.25 2.19-0.62 0.38-1.47 0.48-2.19 0.16-1.21-0.55-1.39-2.04-1.44-2.51-0.02-0.2-0.23-2.17 1.26-3.46 1.2-1.04 3.24-1.4 4.06-0.54z"/>
            <path class="st0" d="m14.39 25.31c-0.59 0.61-0.55 0.77-0.88 1.54-0.49 1.15-0.45 2.2-0.39 2.71"/>
            <path class="st0" d="m25.79 14.08c-1.31 0.22-3.82 0.46-6.66-0.52-4.66-1.6-6.91-5.31-7.5-6.35"/>
            <path class="st1" d="m101.75 14.49c1.43 1.1 2.15 2.73 2.15 4.9 0 2.32-0.75 4.04-2.24 5.16s-3.48 1.69-5.96 1.69h-2.67v7.78h-2.93v-21.18h5.56c2.62 0 4.65 0.55 6.09 1.65zm-2.29 8.46c0.89-0.66 1.34-1.83 1.34-3.54 0-1.52-0.45-2.61-1.34-3.29s-2.17-1.01-3.83-1.01h-2.61v8.82h2.55c1.7 0.01 3-0.32 3.89-0.98z"/>
            <path class="st1" d="m117.39 34.02l-1.6-5.32h-8.02l-1.6 5.32h-2.95l6.79-21.17h3.69l6.76 21.17h-3.07zm-8.91-7.69h6.61l-3.29-11.06-3.32 11.06z"/>
            <path class="st1" d="m145.26 12.84l-4.3 21.17h-3.75l-3.9-17.73-3.93 17.73h-3.66l-4.3-21.17h2.86l3.41 18.62 4.09-18.62h3.1l4.18 18.62 3.53-18.62h2.67z"/>
            <path class="st1" d="m161.61 12.84l-0.31 2.49h-5.87v18.69h-2.92v-18.69h-6.02v-2.49h15.12z"/>
            <path class="st1" d="m176.17 13.75c1.34 0.85 2.39 2.09 3.13 3.73s1.12 3.63 1.12 5.96c0 2.29-0.37 4.26-1.12 5.9s-1.79 2.89-3.13 3.75-2.9 1.29-4.69 1.29-3.35-0.42-4.69-1.26-2.39-2.08-3.13-3.72c-0.75-1.64-1.12-3.62-1.12-5.93 0-2.27 0.37-4.24 1.12-5.9s1.8-2.92 3.15-3.79 2.91-1.31 4.67-1.31c1.78 0 3.34 0.43 4.69 1.28zm-8.99 3.21c-1.02 1.39-1.54 3.56-1.54 6.52 0 2.93 0.52 5.08 1.55 6.45s2.46 2.06 4.29 2.06c3.89 0 5.84-2.85 5.84-8.54 0-5.72-1.95-8.57-5.84-8.57-1.84-0.01-3.28 0.69-4.3 2.08z"/>
            <path class="st1" d="m196.83 14.49c1.43 1.1 2.15 2.73 2.15 4.9 0 2.32-0.75 4.04-2.24 5.16-1.5 1.13-3.48 1.69-5.96 1.69h-2.67v7.78h-2.92v-21.18h5.56c2.62 0 4.65 0.55 6.08 1.65zm-2.29 8.46c0.89-0.66 1.34-1.83 1.34-3.54 0-1.52-0.45-2.61-1.34-3.29s-2.17-1.01-3.83-1.01h-2.61v8.82h2.55c1.71 0.01 3-0.32 3.89-0.98z"/>
            <path class="st1" d="m205.96 12.84v21.17h-2.92v-21.17h2.92z"/>
            <path class="st1" d="m223.39 34.02l-1.6-5.32h-8.02l-1.6 5.32h-2.95l6.79-21.17h3.69l6.76 21.17h-3.07zm-8.92-7.69h6.61l-3.29-11.06-3.32 11.06z"/>
        </svg> -->

        <svg class="logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 235 54"><g fill="none" stroke="#fff" stroke-width="1.5" stroke-miterlimit="10"><path d="M36.44 30.58c-.01 7.38-.9 13.04-1.8 15.93-.8 2.62-2.15 4.52-3.27 5.65-.08.1-1.28.98-.86 1.2 0 0 .92.08 1.05.07 1.9-.12 4.16-.01 9.07-4.48 1.93-1.75.87-1.5 1.58-1.5l33.34-.14c.8-.06 1.3-.35 1.57-.82.13-.22.16-.5.2-1.02.03-.3.03-.54 0-1.3l-.04-1.1c-.06-1.13-.13-24.76 0-28.98.01-.23.03-.85-.37-1.3-.32-.37-.8-.47-1.16-.55-.82-.17-1.5-.03-1.57-.03l-35.66.1c-.26-.01-1.2-.13-1.7.4-.3.32-.4.8-.4.9-.1.46 0 .83.01.9v16.1m-.17 5.5l-33.34-.14c-.8-.06-1.3-.35-1.57-.82-.13-.22-.16-.5-.2-1.02-.03-.3-.03-.54 0-1.3l.04-1.1c.06-1.13.13-24.76 0-28.98-.01-.22-.04-.84.36-1.3.32-.37.8-.47 1.16-.55C3.53.73 4.2.87 4.28.87l35.66.1c.26-.01 1.2-.13 1.7.4a1.77 1.77 0 0 1 .41.89c.04.6.01 1.3.01 1.3v8.75"/><path d="M47.5 41.8c-.06-.12-.14-.25-.13-.4.04-.6 1.28-.48 1.76-1.3.43-.72 0-1.53.34-2.73.58-2.03 1.35-1.54 1.44-3.67.03-.6-.38-.9-.82-2.03-.6-1.56-.7-3.2-.4-4.68.15-.73.25-1.02.32-2.1.06-.92.1-1.92-.8-2.73-1.17-1.07-2-.66-2.5-1.72-.32-.66-.74-1.46-.23-1.84.78-.57 2 .12 3.05-.5.56-.33.4-.54.93-1 1-.94 2.46-.64 3.65-.23 2.08.7 2.3 3.33 2.6 7.18.17 2.28 1.6 4.37 3.28 5.93 2.1 1.96 2.4 2.38 3.36 3.67.9 1.22 1.46 2.67 2.58 3.04 2.53.84 4.3-1.8 5.2-2.5.37-.3.3.07-.25 1.3-.73 1.63-1.7 2.72-3.3 4.03-.43.35-1.04.35-1.02 1.06.02.54.06 1.28.24 1.88.1.37-.15 1.48-.85 1.53-1.6.12-.48-.06-3.13.05-1.78.07-2.4.16-2.87-.25-.4-.35-.68-.97-.56-1.43.14-.53.8-1.1 1.76-1.3.15-.03-1.4-.24-2.62-.68-.58-.2-1.1-.44-1.54-.7-.8-.44-1.6-.88-2.3-1.7-.25-.3-.6 1-.64 1.1-.75 2-1.66 2.7-1.66 3.55 0 .6-.33.94-1 1.02-.73.08-.43.1-3.02.34-.17.02-.53-.13-.62-.18 0 0-.3-.15-.15-.56 0-.01.23-.2.93-.8-.1-.02-.72-.12-1-.64z"/><path d="M48.5 42.43l1.44-.73s.4-.24.74-.57c.46-.46.3-1.05.65-2.82 0 0 .34-1.58.87-1.78m-2.24-12.97c1.3 1 2.28 1.02 3.4 1 .75-.01 3.3-1.06 3.3-1.06m-6.6 1.53c1.3 1 2.38 1.1 3.5 1.1.75-.01 3.4-1.1 3.4-1.1m4.44 10.83c-.38-.02-1.2.03-1.72.78-.48.7-.2 1.7.2 2.68.32.82.7 1.44 1.44 1.77 1.18.52 2.65.2 3.75-.78"/><path d="M50.9 16.84c-.65.68-.28 1.86.44 4.16.5 1.6.9 1.98 1.25 2.2a2.43 2.43 0 0 0 2.2.16c1.2-.55 1.4-2.04 1.44-2.5.02-.2.23-2.17-1.26-3.46-1.2-1.04-3.24-1.4-4.06-.54zM65.3 36.3c.6.6.55.77.88 1.54.5 1.15.45 2.2.4 2.7M53.9 25.06c1.3.22 3.82.46 6.66-.52 4.66-1.6 6.9-5.3 7.5-6.35M32.2 30.8c.06-.12.14-.25.13-.4-.04-.6-1.28-.48-1.76-1.3-.43-.72 0-1.53-.34-2.73-.58-2.03-1.35-1.54-1.44-3.67-.03-.6.38-.9.82-2.03.6-1.56.7-3.2.4-4.68-.15-.73-.25-1.02-.32-2.1-.06-.92-.1-1.92.8-2.73 1.17-1.07 2-.66 2.5-1.72.32-.66.74-1.46.23-1.84-.78-.57-2 .12-3.05-.5-.56-.33-.4-.54-.93-1-1-.94-2.46-.64-3.65-.23-2.08.7-2.3 3.33-2.6 7.18-.17 2.28-1.6 4.37-3.28 5.93-2.1 1.96-2.4 2.38-3.36 3.67-.9 1.22-1.46 2.67-2.58 3.04-2.53.84-4.3-1.8-5.2-2.5-.37-.3-.3.07.25 1.3.73 1.63 1.7 2.72 3.3 4.03.43.35 1.04.35 1.02 1.06-.02.54-.06 1.28-.24 1.88-.1.37.15 1.48.85 1.53 1.6.12.48-.06 3.13.05 1.78.07 2.4.16 2.87-.25.4-.35.68-.97.56-1.43-.14-.53-.8-1.1-1.76-1.3-.15-.03 1.4-.24 2.62-.68.58-.2 1.1-.44 1.54-.7.8-.44 1.6-.88 2.3-1.7.25-.3.6 1 .64 1.1.75 2 1.66 2.7 1.66 3.55 0 .6.33.94 1 1.02.73.08.43.1 3.02.34.17.02.53-.13.62-.18 0 0 .3-.15.15-.56 0-.01-.23-.2-.93-.8.1-.02.72-.1 1-.64z"/><path d="M31.18 31.45l-1.44-.73s-.4-.24-.74-.57c-.46-.46-.3-1.05-.65-2.82 0 0-.34-1.58-.87-1.78m2.25-12.97c-1.3 1-2.28 1.02-3.4 1-.75-.01-3.3-1.06-3.3-1.06m6.6 1.52c-1.3 1-2.38 1.1-3.5 1.1-.75-.01-3.4-1.1-3.4-1.1M18.3 24.88c.38-.02 1.2.03 1.72.78.48.7.2 1.7-.2 2.68-.32.82-.7 1.44-1.44 1.77-1.18.52-2.65.2-3.75-.78"/><path d="M28.8 5.86c.65.68.28 1.86-.44 4.16-.5 1.6-.9 1.98-1.25 2.2a2.43 2.43 0 0 1-2.2.16c-1.2-.55-1.4-2.04-1.44-2.5-.02-.2-.23-2.17 1.26-3.46C25.95 5.36 28 5 28.8 5.86zM14.4 25.3c-.6.6-.55.77-.88 1.54-.5 1.15-.45 2.2-.4 2.7M25.8 14.08c-1.3.22-3.82.46-6.66-.52-4.66-1.6-6.9-5.3-7.5-6.35"/></g><path d="M109.75 14.5c1.43 1.1 2.15 2.73 2.15 4.9 0 2.32-.75 4.04-2.24 5.16s-3.48 1.7-5.96 1.7h-2.67v7.78H98.1v-21.2h5.56c2.62 0 4.65.55 6.1 1.65zm-2.3 8.46c.9-.66 1.34-1.83 1.34-3.54 0-1.52-.45-2.6-1.34-3.3s-2.17-1-3.83-1h-2.6v8.82h2.55c1.7.01 3-.32 3.9-.98zm17.95 11.06l-1.6-5.32h-8.02l-1.6 5.32h-2.95l6.8-21.17h3.7l6.76 21.17h-3.07zm-8.9-7.7h6.6l-3.3-11.06-3.32 11.06zm36.76-13.48L148.96 34h-3.75l-3.9-17.73L137.38 34h-3.66l-4.3-21.17h2.86l3.4 18.62 4.1-18.62h3.1l4.18 18.62 3.53-18.62h2.67zm16.34 0l-.3 2.5h-5.87v18.7h-2.92v-18.7h-6.02v-2.5h15.12zm14.57.9c1.34.85 2.4 2.1 3.13 3.73s1.12 3.63 1.12 5.96c0 2.3-.37 4.26-1.12 5.9s-1.8 2.9-3.13 3.75-2.9 1.3-4.7 1.3-3.35-.42-4.7-1.26-2.4-2.08-3.13-3.72c-.75-1.64-1.12-3.62-1.12-5.93 0-2.27.37-4.24 1.12-5.9s1.8-2.92 3.15-3.8 2.9-1.3 4.67-1.3c1.78 0 3.34.43 4.7 1.28zm-9 3.2c-1.02 1.4-1.54 3.56-1.54 6.52 0 2.93.52 5.08 1.55 6.45s2.46 2.06 4.3 2.06c3.9 0 5.84-2.85 5.84-8.54 0-5.72-1.95-8.57-5.84-8.57-1.84-.01-3.28.7-4.3 2.08zm29.66-2.45c1.43 1.1 2.15 2.73 2.15 4.9 0 2.32-.75 4.04-2.24 5.16-1.5 1.13-3.48 1.7-5.96 1.7h-2.67v7.78h-2.92v-21.2h5.56c2.62 0 4.65.55 6.08 1.65zm-2.3 8.46c.9-.66 1.34-1.83 1.34-3.54 0-1.52-.45-2.6-1.34-3.3s-2.17-1-3.83-1h-2.6v8.82h2.55c1.7.01 3-.32 3.9-.98zm11.43-10.12V34h-2.92V12.84h2.92zm17.44 21.18l-1.6-5.32h-8.02l-1.6 5.32h-2.95l6.8-21.17h3.7l6.76 21.17h-3.07zm-8.92-7.7h6.6l-3.3-11.06-3.32 11.06z" stroke="#fff" stroke-miterlimit="10" fill="#fff"/></svg>

        <video id="video" autoplay muted loop class="video-responsive">
            <source id="source" src="" type="video/mp4">
        </video>

        <form>
          <button formaction="/pawtopia/test.php">LET'S WALK TOGETHER</button>
        </form>
        
    </body>
    <script src="src/scripts/jquery-3.4.0.min.js"></script>
    <script src="src/scripts/bootstrap.min.js"></script>
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
