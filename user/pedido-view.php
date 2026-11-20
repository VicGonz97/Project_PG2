<?php
$error = $_SESSION['pedido_error'] ?? '';
unset($_SESSION['pedido_error']);
?>
<div class="container">
    <h2><i class="fa fa-check-square-o"></i>&nbsp;<?php echo t('datos_pedido'); ?></h2>
    <br>

    <?php if (empty($_SESSION['carrito'])) : ?>
        <p class="text-center text-muted"><?php echo t('carrito_vacio'); ?></p>
        <a href="./index.php?view=marketplace"><?php echo t('seguir_comprando'); ?></a>
    <?php else : ?>

        <?php if ($error) : ?>
            <div class="alert alert-danger">
                <?php echo t('pedido_error_' . $error); ?>
                <a href="./index.php?view=carrito"><?php echo t('ir_al_carrito'); ?></a>
            </div>
        <?php endif; ?>

        <div class="alert alert-info"><?php echo t('pago_info'); ?></div>

        <form action="./process/pedido.php" method="POST" class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo t('nombre_completo'); ?></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="nombre" required maxlength="100">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo t('telefono'); ?></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="telefono" required
                           pattern="[0-9]{8}" maxlength="8" inputmode="numeric" placeholder="00000000">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo t('direccion'); ?></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="direccion" maxlength="255">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-6">
                    <button type="submit" class="btn btn-success"><?php echo t('confirmar_pedido'); ?></button>
                    <a href="./index.php?view=carrito" class="btn btn-default"><?php echo t('ir_al_carrito'); ?></a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>