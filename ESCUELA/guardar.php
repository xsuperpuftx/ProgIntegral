<?php
include("abre.php");

$codigo    =$_POST['codigo'];
$nombre    =$_POST['nombre'];
$apellidos     =$_POST['apellidos'];
$edad    =$_POST['edad'];
$telefono    =$_POST['telefono'];

$consulta = "INSERT INTO boletos(nombre, boleto, destino) VALUES('$nombre', '$boleto', '$destino')";

if($conexion->query($consulta) === TRUE){
    header("Location: index.php");
}else{
    echo "Error" .$consulta. "br" .$conexion->error;
}

$conexion->close();

?>