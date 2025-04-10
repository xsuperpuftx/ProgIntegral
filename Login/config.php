<?php

    define('USER',  'ROOT');
    define ('PASSWORD', '');
    define ('HOST', 'localhost');
    define ('DATABASE', 'test');

    try{
            $connection = new PDO("mysql:host=" .HOST.";dbname=".DATABASE, USER, PASSWORD);

    }catch(PDOException $e){
        exit("ERROR:".$e->getMessage());
    }


?>