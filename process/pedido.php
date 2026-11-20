<?php
session_start();
include '../lib/config.php';
include '../lib/class_mysql.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?view=marketplace');
    exit;
}

$carrito   = $_SESSION['carrito'] ?? [];
$nombre    = trim($_POST['nombre'] ?? '');
$telefono  = trim($_POST['telefono'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');

// 1. Validar en el servidor (el HTML se puede saltar, esto no)
if (empty($carrito) || $nombre === '' || mb_strlen($nombre) > 100
    || !preg_match('/^[0-9]{8}$/', $telefono) || mb_strlen($direccion) > 255) {
    $_SESSION['pedido_error'] = 'datos';
    header('Location: ../index.php?view=pedido');
    exit;
}

$con = Mysql::Conectar();
mysqli_begin_transaction($con);

try {
    // 2. Leer cada producto BLOQUEANDO su fila, y calcular el total con los precios de la base
    $stmtProd = mysqli_prepare($con,
        "SELECT precio, stock FROM producto WHERE id_producto = ? AND activo = 1 FOR UPDATE");
    $total  = 0;
    $lineas = [];

    foreach ($carrito as $id => $cantidad) {
        $id = (int)$id;
        $cantidad = (int)$cantidad;
        mysqli_stmt_bind_param($stmtProd, "i", $id);
        mysqli_stmt_execute($stmtProd);
        $p = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtProd));

        if (!$p || $cantidad < 1 || (int)$p['stock'] < $cantidad) {
            throw new Exception('stock');
        }
        $total   += $p['precio'] * $cantidad;
        $lineas[] = [$id, $cantidad, $p['precio']];
    }
    $total = round($total, 2);

    // 3. Guardar el pedido
    $stmt = mysqli_prepare($con,
        "INSERT INTO pedido (nombre_cliente, telefono, direccion, total) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssd", $nombre, $telefono, $direccion, $total);
    mysqli_stmt_execute($stmt);
    $idPedido = mysqli_insert_id($con);

    // 4. Guardar el detalle y descontar el stock
    $stmtDet = mysqli_prepare($con,
        "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
    $stmtStock = mysqli_prepare($con,
        "UPDATE producto SET stock = stock - ? WHERE id_producto = ? AND stock >= ?");

    foreach ($lineas as [$idProd, $cant, $precio]) {
        mysqli_stmt_bind_param($stmtDet, "iiid", $idPedido, $idProd, $cant, $precio);
        mysqli_stmt_execute($stmtDet);

        mysqli_stmt_bind_param($stmtStock, "iii", $cant, $idProd, $cant);
        mysqli_stmt_execute($stmtStock);
        if (mysqli_stmt_affected_rows($stmtStock) !== 1) {
            throw new Exception('stock');
        }
    }

    mysqli_commit($con);

} catch (Exception $e) {
    mysqli_rollback($con);
    $_SESSION['pedido_error'] = ($e->getMessage() === 'stock') ? 'stock' : 'general';
    header('Location: ../index.php?view=pedido');
    exit;
}

// 5. Todo salió bien: vaciar el carrito y mostrar la confirmación
unset($_SESSION['carrito']);
$_SESSION['ultimo_pedido'] = $idPedido;
header('Location: ../index.php?view=pedido_ok');
exit;