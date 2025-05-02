<?php
session_start();
require_once 'conexion.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (empty($username) || empty($password)) {
        $response['message'] = "Por favor ingrese usuario y contraseña.";
        echo json_encode($response);
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() === 1) {
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_id'] = $admin['id'];
                $response['success'] = true;
                echo json_encode($response);
                exit;
            }
        }
        
        $response['message'] = "Credenciales incorrectas";
        echo json_encode($response);
        exit;
        
    } catch(PDOException $e) {
        $response['message'] = "Error en el sistema";
        echo json_encode($response);
        exit;
    }
}

$response['message'] = "Método no permitido";
echo json_encode($response);
?>