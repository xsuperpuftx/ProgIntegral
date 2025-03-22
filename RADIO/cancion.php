<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Canción</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Agregar Cancion</h1>
        <form action="procesar.php" method="post">
            <input type="hidden" name="tabla" value="Canciones">
            <div class="form-group">
                <label for="nombre">Nombre de la Canción:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="artista">Artista:</label>
                <input type="text" id="artista" name="artista" required>
            </div>
            <div class="form-group">
                <label for="duracion">Duración (MM:SS):</label>
                <input type="text" id="duracion" name="duracion" required>
            </div>
            <div class="form-group">
                <label for="id_genero">ID del Género:</label>
                <input type="number" id="id_genero" name="id_genero" required>
            </div>
            <button type="submit">Agregar Canción</button>
        </form>
    </div>
</body>
</html>