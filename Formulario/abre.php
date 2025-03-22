<?php

$conexion = new mysqli("localhost", "root", "", "");

if($conexion){
    echo "la gestion fue exitosa!!!";
}else{
    echo "fallo la conexion!!!"
}
