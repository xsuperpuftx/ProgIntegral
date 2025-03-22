<?php
include "conexion.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM productos WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

// Actualizar (UPDATE)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $color = $_POST["color"];
    $nuevaImagen = $_FILES["imagen"]["name"];
    $ruta = "uploads/" . basename($nuevaImagen);

    if (!empty($nuevaImagen)) {
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);
        $sql = "UPDATE productos SET nombre='$nombre', precio='$precio', color='$color', imagen='$ruta' WHERE id=$id";
    } else {
        $sql = "UPDATE productos SET nombre='$nombre', precio='$precio', color='$color' WHERE id=$id";
    }

    $conn->query($sql);
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<div class="container">
    <h1>Editar Producto</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="nombre" value="<?= $row['nombre'] ?>" required>
        <input type="number" step="0.01" name="precio" value="<?= $row['precio'] ?>" required>
        <input type="text" name="color" value="<?= $row['color'] ?>" required>
        <input type="file" name="imagen">
        <button type="submit">Actualizar</button>
    </form>

    <a href="index.php">Volver</a>
</div>

</body>
</html>
