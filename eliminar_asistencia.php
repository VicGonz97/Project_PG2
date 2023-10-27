<?php
session_start();
require_once './lib/config.php'; // Incluye tu archivo de configuración de la base de datos

$mysqli = new mysqli(SERVER, USER, PASS, BD);

if ($mysqli->connect_errno) {
    $_SESSION['errorMessage'] = "Error al conectar a la base de datos.";
} else {
    // Ejecuta una consulta SQL para truncar la tabla "asistencia" y eliminar todos los registros.
    $query = "TRUNCATE TABLE asistencia";

    if ($mysqli->query($query)) {
        $_SESSION['exitoMessage'] = " ";
    } else {
        $_SESSION['errorMessage'] = " ";
    }

    // Cierra la conexión a la base de datos.
    $mysqli->close();
}

// Redirige a la página principal
header("Location: index.php?view=ReporteAsistencia");
