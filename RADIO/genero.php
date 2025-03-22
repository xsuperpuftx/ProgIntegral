<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Género</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Agregar Género</h1>
        <form action="procesar.php" method="post">
            <input type="hidden" name="tabla" value="Generos">
            <div class="form-group">
                <label for="id_genero">ID del Género:</label>
                <input type="number" id="id_genero" name="id_genero" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre del Género:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <button type="submit">Agregar Género</button>
        </form>
    </div>
</body>
</html>