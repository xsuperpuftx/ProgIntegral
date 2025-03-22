<?php
include "conexion.php";

// Crear (INSERT)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["crear"])) {
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $color = $_POST["color"];
    $imagen = $_FILES["imagen"]["name"];
    $ruta = "uploads/" . basename($imagen);

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta)) {
        $sql = "INSERT INTO productos (nombre, precio, color, imagen) VALUES ('$nombre', '$precio', '$color', '$ruta')";
        $conn->query($sql);
    }
}

// Leer (SELECT)
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Productos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<div class="container">
    <h1>Gestión de Productos</h1>

    <form action="index.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="nombre" placeholder="Nombre del producto" required>
        <input type="number" step="0.01" name="precio" placeholder="Precio" required>
        <input type="text" name="color" placeholder="Color" required>
        <input type="file" name="imagen" accept="image/*" required>
        <button type="submit" name="crear">Agregar Producto</button>
    </form>

    <div class="productos">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="producto">
                <img src="<?= $row['imagen'] ?>" alt="<?= $row['nombre'] ?>">
                <h3><?= $row['nombre'] ?></h3>
                <p>Precio: $<?= $row['precio'] ?></p>
                <p>Color: <?= $row['color'] ?></p>
                <a href="editar.php?id=<?= $row['id'] ?>">Editar</a>
                <a href="eliminar.php?id=<?= $row['id'] ?>">Eliminar</a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
