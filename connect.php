<?php 

    $dsn = 'mysql:host=localhost;dbname=shop';
    $name = 'root';
    $pass = '';
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    );

    try{
        $con = new PDO($dsn,$name,$pass,$option);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo 'YOU ARE CONNECTED, WELCOME';
    }catch(PDOException $e){
        echo 'Failed To Connect' . $e->getMessage();
    }
