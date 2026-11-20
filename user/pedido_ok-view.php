<?php
$whatsapp  = '50250424169';   // código de Guatemala (502) + número del COCODE
$idPedido  = (int)($_SESSION['ultimo_pedido'] ?? 0);
$pedido    = null;
$detalle   = [];

if ($idPedido > 0) {
    $con  = Mysql::Conectar();

    $stmt = mysqli_prepare($con,
        "SELECT nombre_cliente, telefono, direccion, total FROM pedido WHERE id_pedido = ?");
    mysqli_stmt_bind_param($stmt, "i", $idPedido);
    mysqli_stmt_execute($stmt);
    $pedido = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    $stmt = mysqli_prepare($con,
        "SELECT p.nombre, d.cantidad, d.precio_unitario
         FROM detalle_pedido d
         JOIN producto p ON p.id_producto = d.id_producto
         WHERE d.id_pedido = ?");
    mysqli_stmt_bind_param($stmt, "i", $idPedido);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($res)) {
        $detalle[] = $fila;
    }
}
?>
<div class="container">
    <?php if (!$pedido) : ?>
        <p class="text-center text-muted"><?php echo t('sin_pedido'); ?></p>
        <a href="./index.php?view=marketplace"><?php echo t('seguir_comprando'); ?></a>
    <?php else : ?>
        <?php
        // El mensaje que le llega al COCODE siempre va en español
        $mensaje  = "Hola, quiero confirmar mi pedido #$idPedido del Marketplace COCODE:\n";
        foreach ($detalle as $d) {
            $mensaje .= "- {$d['cantidad']} x {$d['nombre']} (Q " . number_format($d['precio_unitario'], 2) . ")\n";
        }
        $mensaje .= "Total: Q " . number_format($pedido['total'], 2) . "\n";
        $mensaje .= "Nombre: {$pedido['nombre_cliente']}\n";
        $mensaje .= "Teléfono: {$pedido['telefono']}";
        $urlWhatsapp = 'https://wa.me/' . $whatsapp . '?text=' . rawurlencode($mensaje);
        ?>
        <div class="alert alert-success">
            <h3><i class="fa fa-check-circle"></i>&nbsp;<?php echo t('pedido_recibido'); ?></h3>
            <p><?php echo t('pedido_numero'); ?> <strong>#<?php echo $idPedido; ?></strong></p>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><?php echo t('producto'); ?></th>
                        <th><?php echo t('cantidad'); ?></th>
                        <th><?php echo t('precio'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalle as $d) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($d['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo (int)$d['cantidad']; ?></td>
                            <td>Q <?php echo number_format($d['precio_unitario'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-right"><?php echo t('total'); ?></th>
                        <th>Q <?php echo number_format($pedido['total'], 2); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <p><?php echo t('pago_info'); ?></p>

        <a href="<?php echo htmlspecialchars($urlWhatsapp, ENT_QUOTES, 'UTF-8'); ?>"
           target="_blank" rel="noopener" class="btn btn-success btn-lg">
            <i class="fa fa-whatsapp"></i>&nbsp;<?php echo t('confirmar_whatsapp'); ?>
        </a>
    <?php endif; ?>
</div>