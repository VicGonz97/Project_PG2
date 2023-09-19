<?php
// Iniciar la sesión (si aún no está iniciada)
session_start();

// Desactivar y destruir la sesión actual
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión actual

// Redirigir al usuario a la página de inicio (index.php)
header("Location: ../index.php");
?>