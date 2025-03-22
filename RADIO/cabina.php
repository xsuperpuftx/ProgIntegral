<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Cabina</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Agregar Cabina</h1>
    <form action="procesar.php" method="post">
        <input type="hidden" name="tabla" value="Cabinas">
        <label for="nombre_cabina">Nombre de la Cabina:</label>
        <input type="text" id="nombre_cabina" name="nombre_cabina" required><br><br>
        <label for="ubicacion">Ubicación:</label>
        <input type="text" id="ubicacion" name="ubicacion" required><br><br>
        <label for="capacidad">Capacidad:</label>
        <input type="number" id="capacidad" name="capacidad" required><br><br>
        <input type="submit" value="Agregar Cabina">
    </form>
</body>
</html>