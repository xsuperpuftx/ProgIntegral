<?php
    $conexion= new mysqli("localhost", "root", " ", "aeromexico");
    if($conexion){
        echo "la gestion fue exitosa!";

    }else{
        echo "algo salio mal";
    }




?>