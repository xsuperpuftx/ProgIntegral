<?php

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validacion de datos
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $service = filter_input(INPUT_POST, 'service', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    if (empty($name) || empty($email) || empty($phone) || empty($service) || empty($message)) {
        die("Por favor complete todos los campos del formulario.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Por favor ingrese un correo electrónico válido.");
    }

    // Insertar en la base de datos
    try {
        $stmt = $connection->prepare("INSERT INTO contact_cases (name, email, phone, service_type, description) 
                               VALUES (:name, :email, :phone, :service, :message)");
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':service', $service);
        $stmt->bindParam(':message', $message);
        
        $stmt->execute();
        
        // Redirigir con mensaje de exito
        header('Location: index.html?success=1#contacto');
        exit();
    } catch(PDOException $e) {
        die("Error al guardar el caso: " . $e->getMessage());
    }
} else {
    header('Location: index.html');
    exit();
}
?>