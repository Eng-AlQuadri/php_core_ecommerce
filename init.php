<?php

    include 'connect.php';

    $session_user = '';

    if(isset($_SESSION['user'])) {
        $session_user = $_SESSION['user'];
    }

    //Routes

    $tpl = 'includes/templates/';   //Template Directory
    $css = 'layout/css/';           // Css Directory
    $js = 'layout/js/';             // JS Directory
    $lang = 'includes/languages/';  // Language Directory
    $func = 'includes/functions/';  // Function Directory

    // Include The Important Files

    include $func . 'function.php';
    include $lang . 'en.php';
    include $tpl . 'header.php';
    include $tpl . 'navbar.php';