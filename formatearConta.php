<?php
require_once './lib/config.php'; // Incluye tu archivo de conexión a la base de datos

$mysqli = mysqli_connect(SERVER, USER, PASS, BD);
mysqli_set_charset($mysqli, "utf8");

// Eliminar todos los datos de la tabla "contabilidad"
$eliminarContabilidad = mysqli_query($mysqli, "DELETE FROM contabilidad");

// Eliminar todos los datos de la tabla "retiro"
$eliminarRetiro = mysqli_query($mysqli, "DELETE FROM retiro");

if ($eliminarContabilidad && $eliminarRetiro) {
    echo "¡Todos los datos han sido eliminados correctamente!";
} else {
    echo "Error al eliminar los datos: " . mysqli_error($mysqli);
}

mysqli_close($mysqli);

// Redirige a la página principal
header("Location: index.php?view=AddConta");
