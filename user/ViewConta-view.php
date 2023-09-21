<?php if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) { ?>
    <?php
    require_once './lib/config.php'; // Incluye tu archivo de conexión a la base de datos

    if (isset($_POST['id_del'])) {
        $id_user = MysqlQuery::RequestPost('id_del');
        if (MysqlQuery::Eliminar("contabilidad", "id_contabilidad='$id_user'")) {
            echo '
                <div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">COCODE ELIMINADO</h4>
                    <p class="text-center">
                        El usuario fue eliminado del sistema con éxito.
                    </p>
                </div>
            ';
        } else {
            echo '
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                        No hemos podido eliminar el usuario.
                    </p>
                </div>
            ';
        }
    }

    $num_user = Mysql::consulta("SELECT * FROM contabilidad");
    $num_total_user = mysqli_num_rows($num_user);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <ul class="nav nav-pills nav-justified">
                    <li><a><i class="fa fa-users"></i>&nbsp;&nbsp;Contribuyentes&nbsp;&nbsp;<span class="badge"><?php echo $num_total_user; ?></span></a></li>
                </ul>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <?php
                    $mysqli = mysqli_connect(SERVER, USER, PASS, BD);
                    mysqli_set_charset($mysqli, "utf8");

                    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                    $regpagina = 15;
                    $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

                    $selusers = mysqli_query($mysqli, "SELECT SQL_CALC_FOUND_ROWS * FROM contabilidad LIMIT $inicio, $regpagina");

                    $totalregistros = mysqli_query($mysqli, "SELECT FOUND_ROWS()");
                    $totalregistros = mysqli_fetch_array($totalregistros, MYSQLI_ASSOC);

                    $numeropaginas = ceil($totalregistros["FOUND_ROWS()"] / $regpagina);
                    if (mysqli_num_rows($selusers) > 0) :
                    ?>
                        <table class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Apellidos</th>
                                    <th class="text-center">DPI</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ct = $inicio + 1;
                                while ($row = mysqli_fetch_array($selusers, MYSQLI_ASSOC)) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $ct; ?></td>
                                        <td class="text-center"><?php echo $row['nombre']; ?></td>
                                        <td class="text-center"><?php echo $row['apellido']; ?></td>
                                        <td class="text-center"><?php echo $row['dpi']; ?></td>
                                        <td class="text-center"><?php echo $row['cantidad']; ?></td>
                                        <td class="text-center">
                                            <form action="" method="POST" style="display: inline-block;">
                                                <input type="hidden" name="id_del" value="<?php echo $row['id_contabilidad']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                            </form>

                                        </td>

                                    </tr>
                                <?php

                                    $ct++;
                                endwhile;
                                ?>
                            </tbody>
                        </table>
                        <a href="./index.php?view=AddConta" class="btn btn-primary">Regresar</a>
                    <?php else : ?>
                        <h2 class="text-center">No hay registros</h2>
                    <?php endif; ?>
                </div>
                <?php if ($numeropaginas >= 1) : ?>
                    <nav aria-label="Page navigation" class="text-center">
                        <ul class="pagination">
                            <?php if ($pagina == 1) : ?>
                                <li class="disabled">
                                    <a aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php else : ?>
                                <li>
                                    <a href="./admin.php?view=users&pagina=<?php echo $pagina - 1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>

                                </li>
                            <?php endif; ?>

                            <?php
                            for ($i = 1; $i <= $numeropaginas; $i++) {
                                if ($pagina == $i) {
                                    echo '<li class="active"><a href="./admin.php?view=users&pagina=' . $i . '">' . $i . '</a></li>';
                                } else {
                                    echo '<li><a href="./admin.php?view=users&pagina=' . $i . '">' . $i . '</a></li>';
                                }
                            }
                            ?>

                            <?php if ($pagina == $numeropaginas) : ?>
                                <li class="disabled">
                                    <a aria-label="Previous">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php else : ?>
                                <li>
                                    <a href="./admin.php?view=users&pagina=<?php echo $pagina + 1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
} else {
?>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <img src="./img/Guatemala.png" alt="Image" class="img-responsive animated slideInDown" /><br>
            </div>
            <div class="col-sm-7 animated flip">
                <h1 class="text-danger">Lo sentimos esta página es solamente para el COCODE a Cargo</h1>
                <h3 class="text-info text-center">Inicia sesión como COCODE para poder acceder</h3>
            </div>
            <div class="col-sm-1">&nbsp;</div>
        </div>
    </div>
<?php
}
?>