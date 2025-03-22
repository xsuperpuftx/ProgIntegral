<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tabla = $_POST['tabla'];

    switch ($tabla) {
        case "Cabinas":
            $nombre_cabina = $_POST['nombre_cabina'];
            $ubicacion = $_POST['ubicacion'];
            $capacidad = $_POST['capacidad'];
            $sql = "INSERT INTO Cabinas (nombre_cabina, ubicacion, capacidad)
                    VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssi", $nombre_cabina, $ubicacion, $capacidad);

            if ($stmt->execute()) {
                echo "Cabina agregada correctamente.";
            } else {
                echo "Error al agregar la cabina: " . $stmt->error;
            }

            $stmt->close();
            break;

        case "Generos":
            $id_genero = $_POST['id_genero']; // ID proporcionado manualmente
            $nombre_genero = $_POST['nombre'];

            // Verificar si el ID del género ya existe
            $sql_check_genero = "SELECT id FROM Generos WHERE id = ?";
            $stmt_check = $conexion->prepare($sql_check_genero);
            $stmt_check->bind_param("i", $id_genero);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows == 0) {
                // Insertar el género
                $sql = "INSERT INTO Generos (id, nombre)
                        VALUES (?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("is", $id_genero, $nombre_genero);

                if ($stmt->execute()) {
                    echo "Género agregado correctamente.";
                } else {
                    echo "Error al agregar el género: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Error: El ID del género ya existe.";
            }

            $stmt_check->close();
            break;

        case "Frecuencias":
            $id_frecuencia = $_POST['id_frecuencia'];
            $valor = $_POST['valor'];
            $id_cabina = $_POST['id_cabina'];

            // Verificar si el ID de la cabina existe
            $sql_check_cabina = "SELECT id_cabina FROM Cabinas WHERE id_cabina = ?";
            $stmt_check = $conexion->prepare($sql_check_cabina);
            $stmt_check->bind_param("i", $id_cabina);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                // Insertar la frecuencia
                $sql = "INSERT INTO Frecuencias (id_frecuencia, valor, id_cabina)
                        VALUES (?, ?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("ssi", $id_frecuencia, $valor, $id_cabina);

                if ($stmt->execute()) {
                    echo "Frecuencia agregada correctamente.";
                } else {
                    echo "Error al agregar la frecuencia: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Error: El ID de la cabina no existe.";
            }

            $stmt_check->close();
            break;

        case "Locutores":
            $nombre = $_POST['nombre'];
            $id_cabina = $_POST['id_cabina'];
            $id_genero = $_POST['id_genero'];

            // Verificar si el ID de la cabina y el ID del género existen
            $sql_check_cabina = "SELECT id_cabina FROM Cabinas WHERE id_cabina = ?";
            $stmt_check_cabina = $conexion->prepare($sql_check_cabina);
            $stmt_check_cabina->bind_param("i", $id_cabina);
            $stmt_check_cabina->execute();
            $stmt_check_cabina->store_result();

            $sql_check_genero = "SELECT id FROM Generos WHERE id = ?";
            $stmt_check_genero = $conexion->prepare($sql_check_genero);
            $stmt_check_genero->bind_param("i", $id_genero);
            $stmt_check_genero->execute();
            $stmt_check_genero->store_result();

            if ($stmt_check_cabina->num_rows > 0 && $stmt_check_genero->num_rows > 0) {
                // Insertar el locutor
                $sql = "INSERT INTO Locutores (nombre, id_cabina, id_genero)
                        VALUES (?, ?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("sii", $nombre, $id_cabina, $id_genero);

                if ($stmt->execute()) {
                    echo "Locutor agregado correctamente.";
                } else {
                    echo "Error al agregar el locutor: " . $stmt->error;
                }

                $stmt->close();
            } else {
                if ($stmt_check_cabina->num_rows == 0) {
                    echo "Error: El ID de la cabina no existe.";
                }
                if ($stmt_check_genero->num_rows == 0) {
                    echo "Error: El ID del género no existe.";
                }
            }

            $stmt_check_cabina->close();
            $stmt_check_genero->close();
            break;

        case "Canciones":
            $nombre = $_POST['nombre'];
            $artista = $_POST['artista'];
            $duracion = $_POST['duracion'];
            $id_genero = $_POST['id_genero'];

            // Verificar si el ID del género existe
            $sql_check_genero = "SELECT id FROM Generos WHERE id = ?";
            $stmt_check = $conexion->prepare($sql_check_genero);
            $stmt_check->bind_param("i", $id_genero);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                // Insertar la canción
                $sql = "INSERT INTO Canciones (nombre, artista, duracion, id_genero)
                        VALUES (?, ?, ?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("sssi", $nombre, $artista, $duracion, $id_genero);

                if ($stmt->execute()) {
                    echo "Canción agregada correctamente.";
                } else {
                    echo "Error al agregar la canción: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Error: El ID del género no existe.";
            }

            $stmt_check->close();
            break;

        default:
            die("Tabla no válida");
    }
}

$conexion->close();
?>