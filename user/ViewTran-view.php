<?php
require_once './lib/config.php'; // Include your database connection file

$mysqli = mysqli_connect(SERVER, USER, PASS, BD);
mysqli_set_charset($mysqli, "utf8");

if (isset($_POST['id_del'])) {
    $id_user = MysqlQuery::RequestPost('id_del');
    if (MysqlQuery::Eliminar("retiro", "id_retiro='$id_user'")) {
        echo '
            <div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <p class="text-center">
                    Eliminado exitosamente!.
                </p>
            </div>
        ';
    } else {
        echo '
            <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                <p class="text-center">
                    No hemos podido eliminar, intente de nuevo.
                </p>
            </div>
        ';
    }
}

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$regpagina = 15;
$inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

// Define $selusers here by executing the appropriate query
$selusers = mysqli_query($mysqli, "SELECT * FROM retiro");

// Calculate the number of pages
$num_total_user = mysqli_num_rows($selusers);
$numeropaginas = ceil($num_total_user / $regpagina);

// Initialize the $total_amount variable
$total_amount = 0;

?>
<div class="container">
    <a href="./index.php?view=RetirarFondos" style="color: gray;">
        <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <ul class="nav nav-pills nav-justified">
                <li><a><i class="fa fa-users"></i>&nbsp;&nbsp;Retiros&nbsp;&nbsp;<span class="badge"><?php echo $num_total_user; ?></span></a></li>
            </ul>
        </div>
    </div>
    <br>
    <!-- Check if there are records in the "retiro" table before displaying the table -->
    <?php if ($num_total_user > 0) : ?>
        <!-- Muestra la tabla con los registros -->
        <table class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Descripcion</th>
                    <th class="text-center">Fecha de retiro</th>
                    <th class="text-center">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $ct = $inicio + 1;
                while ($row = mysqli_fetch_array($selusers, MYSQLI_ASSOC)) :
                    // Calculate the total amount
                    $total_amount += $row['cantidad'];
                ?>
                    <tr>
                        <td class="text-center"><?php echo $ct; ?></td>
                        <td class="text-center"> Q <?php echo $row['cantidad']; ?></td>
                        <td class="text-center"><?php echo $row['descripcion']; ?></td>
                        <td class="text-center">
                            <?php
                            $fecha_retiro = date('d/m/Y h:i a', strtotime($row['fecha_retiro']));
                            echo $fecha_retiro;
                            ?>
                        </td>

                        <td class="text-center">
                            <form action="" method="POST" style="display: inline-block;">
                                <input type="hidden" name="id_del" value="<?php echo $row['id_retiro']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            </form>
                            <a href="./index.php?view=UpdateTran&id=<?php echo $row['id_retiro']; ?>" class="btn btn-sm btn-warning"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                <?php
                    $ct++;
                endwhile;
                ?>
            </tbody>
        </table>
        <!-- Muestra el total de cantidad debajo de la tabla -->
        <div class="text-center">
            <p style="color: black; font-size: large; font-weight: bold;" id_ingreso="total_cantidad">Total Cantidad Retirada: Q <?php echo $total_amount; ?></p>
        </div>
    <?php else : ?>
        <!-- Display a message if there are no records in the "retiro" table -->
        <h2 class="text-center">No hay registros en la tabla "retiro".</h2>
    <?php endif; ?>
</div>
<!-- Aquí colocas el código de paginación -->
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
                    <a href="./index.php?view=ViewTran&pagina=<?php echo $pagina - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php
            for ($i = 1; $i <= $numeropaginas; $i++) {
                if ($pagina == $i) {
                    echo '<li class="active"><a href="./index.php?view=ViewTran&pagina=' . $i . '">' . $i . '</a></li>';
                } else {
                    echo '<li><a href="./index.php?view=ViewTran&pagina=' . $i . '">' . $i . '</a></li>';
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
                    <a href="./index.php?view=ViewTran&pagina=<?php echo $pagina + 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
</div>

<br> <br> <br> <br> <br>