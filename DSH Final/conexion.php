<?php
$host = 'fdb1030.awardspace.net';
$dbname = '4550941_aeromexico';
$username = '4550941_aeromexico';
$password = 'ProperDose1024!';

try {
    $connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
