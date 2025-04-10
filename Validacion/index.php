<?php 
$host = "fdb1030.awardspace.net";  
$user = "4550941_aeromexico";  
$pass = "ProperDose1024!"; 
$dbname = "4550941_aeromexico"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if($conn -> connect_error){
    die("conexion fallida: ". $conn->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $fnombre = mysqli_real_escape_string($conn, $_POST["fnombre"]);
    $fapellidos = mysqli_real_escape_string($conn, $_POST["fapellidos"]);

    $encryption_key = "clave secreta";
    $sql = "INSERT INTO usuarios (nombre, apellidos) VALUES (
        AES_ENCRYPT('nombre', 'encryption_key'),
        AES_ENCRYPT('apellidos', 'encryption_key'))";
    if($conn->query($sql) === TRUE){
        echo "Los datos se guardaron correctamente";
    }else{
        echo "ERROR: ". $sql . "<br>" . $conn -> error;
    }

$conn->close();
}
?>

<form action="index.php" method="POST">
Nombre: <input type="text" name="fnombre" require><br>
Apellidos: <input type="text" name="fapellidos" require><br>
<input type="submit" value="Guardar datos"><br>
</form>

