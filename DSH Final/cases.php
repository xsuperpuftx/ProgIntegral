<?php
session_start();
require_once 'conexion.php';

// Verificar el login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Verificar que el admin existe en la base de datos
try {
    $stmt = $connection->prepare("SELECT id FROM admins WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $_SESSION['admin_id']);
    $stmt->execute();
    
    if ($stmt->rowCount() !== 1) {
        session_destroy();
        header('Location: login.php');
        exit;
    }
} catch(PDOException $e) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// acciones (eliminar, cambiar estado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_case'])) {
        $caseId = filter_input(INPUT_POST, 'case_id', FILTER_VALIDATE_INT);
        if ($caseId) {
            $stmt = $connection->prepare("DELETE FROM contact_cases WHERE id = :id");
            $stmt->bindParam(':id', $caseId);
            $stmt->execute();
        }
    } elseif (isset($_POST['update_status'])) {
        $caseId = filter_input(INPUT_POST, 'case_id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
        
        if ($caseId && in_array($status, ['nuevo', 'revisado', 'en_proceso', 'resuelto'])) {
            $stmt = $connection->prepare("UPDATE contact_cases SET status = :status WHERE id = :id");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $caseId);
            $stmt->execute();
        }
    }
}

// jalar casos
$cases = [];
try {
    $stmt = $connection->query("SELECT * FROM contact_cases ORDER BY created_at DESC");
    $cases = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al cargar los casos";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Casos | DSH Consultores</title>
    <link rel="stylesheet" href="estilo.css">
    <link rel="stylesheet" href="cases.css">
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h2>Administrar Casos</h2>
            <div>
                <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
            </div>
        </div>
        
        <?php if (isset($error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php foreach ($cases as $case): ?>
        <div class="case-card">
            <div class="case-header">
                <h3><?php echo htmlspecialchars($case['name']); ?> - <?php echo htmlspecialchars($case['service_type']); ?></h3>
                <span class="case-status status-<?php echo str_replace(' ', '_', $case['status']); ?>">
                    <?php 
                    $statusText = [
                        'nuevo' => 'Nuevo',
                        'revisado' => 'Revisado', 
                        'en_proceso' => 'En Proceso',
                        'resuelto' => 'Resuelto'
                    ];
                    echo $statusText[$case['status']]; 
                    ?>
                </span>
            </div>
            
            <p><strong>Email:</strong> <?php echo htmlspecialchars($case['email']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($case['phone']); ?></p>
            <p><strong>Mensaje:</strong> <?php echo nl2br(htmlspecialchars($case['description'])); ?></p>
            <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($case['created_at'])); ?></p>
            
            <div class="case-actions">
                <form method="POST">
                    <input type="hidden" name="case_id" value="<?php echo $case['id']; ?>">
                    <select name="status" onchange="this.form.submit()">
                        <option value="nuevo" <?php echo $case['status'] === 'nuevo' ? 'selected' : ''; ?>>Nuevo</option>
                        <option value="revisado" <?php echo $case['status'] === 'revisado' ? 'selected' : ''; ?>>Revisado</option>
                        <option value="en_proceso" <?php echo $case['status'] === 'en_proceso' ? 'selected' : ''; ?>>En Proceso</option>
                        <option value="resuelto" <?php echo $case['status'] === 'resuelto' ? 'selected' : ''; ?>>Resuelto</option>
                    </select>
                    <input type="hidden" name="update_status" value="1">
                </form>
                
                <form method="POST">
                    <input type="hidden" name="case_id" value="<?php echo $case['id']; ?>">
                    <button type="submit" name="delete_case" class="btn btn-delete" 
                            onclick="return confirm('¿Está seguro de eliminar este caso?')">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>