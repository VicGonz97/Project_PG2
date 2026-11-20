<?php
$con = Mysql::Conectar();
$resultado = mysqli_query($con, "SELECT id_producto, nombre, descripcion, precio, stock, imagen
                                 FROM producto
                                 WHERE activo = 1 AND stock > 0
                                 ORDER BY nombre");
?>
<div class="container">
    <h2><i class="fa fa-shopping-bag"></i>&nbsp;<?php echo t('marketplace'); ?></h2>
    <br>
    <div class="row">
        <?php if (mysqli_num_rows($resultado) == 0) : ?>
            <p class="text-center text-muted"><?php echo t('sin_productos'); ?></p>
        <?php endif; ?>

        <?php while ($p = mysqli_fetch_assoc($resultado)) : ?>
            <div class="col-sm-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    </div>
                    <div class="panel-body">
                        <?php if (!empty($p['imagen'])) : ?>
                            <img src="./img/productos/<?php echo htmlspecialchars($p['imagen'], ENT_QUOTES, 'UTF-8'); ?>" class="img-responsive" alt="">
                            <br>
                        <?php endif; ?>
                        <p><?php echo htmlspecialchars($p['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Q <?php echo number_format($p['precio'], 2); ?></strong></p>
                        <p class="text-muted"><?php echo t('disponibles'); ?>: <?php echo (int)$p['stock']; ?></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>