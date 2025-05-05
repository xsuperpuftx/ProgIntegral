<?php
include('conexion.php');
session_start();

// Redirigir si yase inicio la sesion
if(isset($_SESSION['admin_logged_in'])) {
    header('Location: cases.php');
    exit;
}

$error = '';
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!empty($username) && !empty($password)) {
        try {
            $query = $connection->prepare("SELECT * FROM admins WHERE username=:username LIMIT 1");
            $query->bindParam(":username", $username, PDO::PARAM_STR);
            $query->execute();

            if($query->rowCount() > 0) {
                $admin = $query->fetch(PDO::FETCH_ASSOC);
                
                if(password_verify($password, $admin['password'])) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    header('Location: cases.php');
                    exit;
                } else {
                    $error = "Credenciales incorrectas";
                }
            } else {
                $error = "Usuario no encontrado";
            }
        } catch(PDOException $e) {
            $error = "Error en el sistema: " . $e->getMessage();
        }
    } else {
        $error = "Por favor complete todos los campos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo - DSH Consultores</title>
    <link rel="stylesheet" href="estilo.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2 class="login-title">Acceso Administrativo</h2>
        
        <?php if(!empty($error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form class="login-form" method="POST">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" name="login">Ingresar</button>
        </form>
    </div>
</body>
</html>