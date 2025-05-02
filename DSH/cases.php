<?php
session_start();
require_once 'conexion.php';

// Verificar conexion
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

// Verificar que el admin existe en la base de datos
try {
    $stmt = $conn->prepare("SELECT id FROM admins WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $_SESSION['admin_id']);
    $stmt->execute();
    
    if ($stmt->rowCount() !== 1) {
        session_destroy();
        header('Location: dashboard.php');
        exit;
    }
} catch(PDOException $e) {
    session_destroy();
    header('Location: dashboard.php');
    exit;
}

// Manejar acciones (eliminar, cambiar estado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_case'])) {
        $caseId = filter_input(INPUT_POST, 'case_id', FILTER_VALIDATE_INT);
        if ($caseId) {
            $stmt = $conn->prepare("DELETE FROM contact_cases WHERE id = :id");
            $stmt->bindParam(':id', $caseId);
            $stmt->execute();
        }
    } elseif (isset($_POST['update_status'])) {
        $caseId = filter_input(INPUT_POST, 'case_id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
        
        if ($caseId && in_array($status, ['nuevo', 'revisado', 'en_proceso', 'resuelto'])) {
            $stmt = $conn->prepare("UPDATE contact_cases SET status = :status WHERE id = :id");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $caseId);
            $stmt->execute();
        }
    }
}

// Obtener casos
$cases = [];
try {
    $stmt = $conn->query("SELECT * FROM contact_cases ORDER BY created_at DESC");
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
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        .logout-btn {
            background: var(--accent-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .case-card {
            background: var(--secondary-color);
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .case-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .case-status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .status-nuevo { background: #ffcccc; color: #cc0000; }
        .status-revisado { background: #fff3cd; color: #856404; }
        .status-en_proceso { background: #cce5ff; color: #004085; }
        .status-resuelto { background: #d4edda; color: #155724; }
        .case-actions {
            margin-top: 1rem;
            display: flex;
            gap: 1rem;
        }
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .btn-edit {
            background: var(--primary-color);
            color: white;
        }
        .btn-delete {
            background: var(--accent-color);
            color: white;
        }
        select {
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .error-message {
            color: var(--accent-color);
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
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