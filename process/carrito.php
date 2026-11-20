<?php
session_start();
include '../lib/config.php';
include '../lib/class_mysql.php';

$accion = $_POST['accion'] ?? '';
$id     = (int)($_POST['id_producto'] ?? 0);

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if ($accion === 'quitar') {
    unset($_SESSION['carrito'][$id]);
} elseif ($id > 0 && in_array($accion, ['agregar', 'actualizar'])) {

    // Consultar el stock real en la base de datos (prepared statement)
    $con  = Mysql::Conectar();
    $stmt = mysqli_prepare($con, "SELECT stock FROM producto WHERE id_producto = ? AND activo = 1");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $producto = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($producto) {
        $stock = (int)$producto['stock'];

        if ($accion === 'agregar') {
            $nueva = ($_SESSION['carrito'][$id] ?? 0) + 1;
        } else {
            $nueva = (int)($_POST['cantidad'] ?? 1);
        }

        $nueva = min($nueva, $stock);   // nunca más de lo que hay

        if ($nueva > 0) {
            $_SESSION['carrito'][$id] = $nueva;
        } else {
            unset($_SESSION['carrito'][$id]);
        }
    }
}

header('Location: ../index.php?view=carrito');
exit;