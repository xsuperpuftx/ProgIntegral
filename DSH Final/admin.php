<?php
include('conexion.php');

$username = 'admin';
$password_plano = 'admin123';

// Generar el hash
$password_hash = password_hash($password_plano, PASSWORD_DEFAULT);

// Insertar en la base de datos
try {
    $stmt = $connection->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password_hash);
    $stmt->execute();
    echo "Usuario admin creado con éxito.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>