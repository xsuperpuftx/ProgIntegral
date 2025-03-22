<?php
$servidor = "localhost"; // Servidor de la base de datos
$usuario = "root";       // Usuario de la base de datos
$contrasena = "";        // Contraseña de la base de datos
$basedatos = "Radios";   // Nombre de la base de datos

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $contrasena, $basedatos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>