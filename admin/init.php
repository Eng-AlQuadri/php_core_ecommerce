<?php

    include 'connect.php';

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
    
    // Include Navbar In All Pages Except The One With No $no_navbar Variable
    
    if(!isset($no_navbar)){

        include $tpl . 'navbar.php';

    }

    