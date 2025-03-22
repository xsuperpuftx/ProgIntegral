<?php
    $conexion= new mysqli("fdb1030.awardspace.net", "4550941_aeromexico", "ProperDose1024!", "4550941_aeromexico");
    if($conexion){
        echo "la gestion fue exitosa!";

    }else{
        echo "algo salio mal";
    }




?>