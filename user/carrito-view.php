<?php
$carrito = $_SESSION['carrito'] ?? [];
$con     = Mysql::Conectar();
$items   = [];
$total   = 0;

if (!empty($carrito)) {
    $stmt = mysqli_prepare($con, "SELECT id_producto, nombre, precio, stock FROM producto WHERE id_producto = ? AND activo = 1");
    foreach ($carrito as $id => $cantidad) {
        $id = (int)$id;
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $p = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if ($p && $p['stock'] > 0) {
            $p['cantidad'] = min((int)$cantidad, (int)$p['stock']);
            $p['subtotal'] = $p['cantidad'] * $p['precio'];
            $total += $p['subtotal'];
            $items[] = $p;
        }
    }
}
?>
<div class="container">
    <h2><i class="fa fa-shopping-cart"></i>&nbsp;<?php echo t('carrito'); ?></h2>
    <br>

    <?php if (empty($items)) : ?>
        <p class="text-center text-muted"><?php echo t('carrito_vacio'); ?></p>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><?php echo t('producto'); ?></th>
                        <th><?php echo t('precio'); ?></th>
                        <th><?php echo t('cantidad'); ?></th>
                        <th><?php echo t('subtotal'); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $it) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($it['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>Q <?php echo number_format($it['precio'], 2); ?></td>
                            <td>
                                <form action="./process/carrito.php" method="POST" class="form-inline">
                                    <input type="hidden" name="accion" value="actualizar">
                                    <input type="hidden" name="id_producto" value="<?php echo (int)$it['id_producto']; ?>">
                                    <input type="number" name="cantidad" class="form-control input-sm" style="width:70px"
                                           min="1" max="<?php echo (int)$it['stock']; ?>" value="<?php echo (int)$it['cantidad']; ?>">
                                    <button type="submit" class="btn btn-default btn-sm"><?php echo t('actualizar'); ?></button>
                                </form>
                            </td>
                            <td>Q <?php echo number_format($it['subtotal'], 2); ?></td>
                            <td>
                                <form action="./process/carrito.php" method="POST">
                                    <input type="hidden" name="accion" value="quitar">
                                    <input type="hidden" name="id_producto" value="<?php echo (int)$it['id_producto']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right"><?php echo t('total'); ?></th>
                        <th colspan="2">Q <?php echo number_format($total, 2); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <a href="./index.php?view=pedido" class="btn btn-primary">
    <i class="fa fa-check"></i>&nbsp;<?php echo t('hacer_pedido'); ?>
</a>
    <?php endif; ?>

    <a href="./index.php?view=marketplace"><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;<?php echo t('seguir_comprando'); ?></a>
</div>