<?php
include "conexion.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT imagen FROM productos WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if (file_exists($row["imagen"])) {
        unlink($row["imagen"]);
    }

    $sql = "DELETE FROM productos WHERE id=$id";
    $conn->query($sql);
}

header("Location: index.php");
?>
