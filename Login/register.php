<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo.css">
    <title>Document</title>
</head>
<body>
        <form action="registration.php" method="post">
            <div class="form-element">
                <label >Usuario</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-element">
                <label >Password </label>
                <input type="password" name="password" required>
            </div>

            <div class="form-element">
                <label >Correo</label>
                <input type="text" name="email" required>
            </div>

            <button type="submit" name="register">ENVIAR</button>
        </form>
</body>
</html>