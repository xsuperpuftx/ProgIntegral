<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Frecuencia</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Agregar Frecuencia</h1>
        <form action="procesar.php" method="post">
            <input type="hidden" name="tabla" value="Frecuencias">
            <div class="form-group">
                <label for="id_frecuencia">ID de la Frecuencia:</label>
                <input type="text" id="id_frecuencia" name="id_frecuencia" required maxlength="3">
            </div>
            <div class="form-group">
                <label for="valor">Valor de la Frecuencia:</label>
                <input type="text" id="valor" name="valor" required maxlength="5">
            </div>
            <div class="form-group">
                <label for="id_cabina">ID de la Cabina:</label>
                <input type="number" id="id_cabina" name="id_cabina" required>
            </div>
            <button type="submit">Agregar Frecuencia</button>
        </form>
    </div>
</body>
</html>