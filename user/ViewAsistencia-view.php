<?php
// Verifica si existe una sesión con nombre y tipo de usuario
if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
?>
    <?php
    require_once './lib/config.php'; // Incluye tu archivo de configuración de la base de datos

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['id_del'])) {
            $id_user = MysqlQuery::RequestPost('id_del');
            if (MysqlQuery::Eliminar("asistencia", "id_asistencia='$id_user'")) {
                echo '
                    <div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="text-center"> ELIMINADO</h4>
                        <p class="text-center">
                            Contribuyente eliminado en la asistencia!.
                        </p>
                    </div>
                ';
            } else {
                echo '
                    <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                        <p class="text-center">
                            No hemos podido eliminar el contribuyente.
                        </p>
                    </div>
                ';
            }
        }
    }

    // Consulta SQL para recuperar los registros
    $mysqli = mysqli_connect(SERVER, USER, PASS, BD);
    mysqli_set_charset($mysqli, "utf8");

    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $regpagina = 15;
    $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

    // Modifica la consulta SQL para aplicar el filtro
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

    if ($filtro === 'marcados') {
        $selusers = mysqli_query($mysqli, "SELECT SQL_CALC_FOUND_ROWS * FROM asistencia WHERE asistio = '1' LIMIT $inicio, $regpagina");
    } elseif ($filtro === 'vacios') {
        $selusers = mysqli_query($mysqli, "SELECT SQL_CALC_FOUND_ROWS * FROM asistencia WHERE asistio = '0' LIMIT $inicio, $regpagina");
    } else {
        $selusers = mysqli_query($mysqli, "SELECT SQL_CALC_FOUND_ROWS * FROM asistencia LIMIT $inicio, $regpagina");
    }

    // Obtener el número total de registros para paginación
    $totalregistros = mysqli_query($mysqli, "SELECT FOUND_ROWS()");
    $totalregistros = mysqli_fetch_array($totalregistros, MYSQLI_ASSOC);

    $numeropaginas = ceil($totalregistros["FOUND_ROWS()"] / $regpagina);
    ?>

    <div class="container">
        <a href="./index.php?view=RegAsistencia" style="color: gray;">
            <span class="glyphicon glyphicon-arrow-left"></span> Regresar
        </a>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <ul class="nav nav-pills nav-justified">
                    <li><a href="./index.php?view=ViewAsistencia"><i class="fa fa-users"></i>&nbsp;&nbsp;Asistencia&nbsp;&nbsp;<span class="badge"><?php echo $totalregistros["FOUND_ROWS()"]; ?></span></a></li>
                    <li><a href="./index.php?view=ViewAsistencia&filtro=marcados"><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Presente</a></li>
                    <li><a href="./index.php?view=ViewAsistencia&filtro=vacios"><i class="fa fa-square-o"></i>&nbsp;&nbsp;Ausente</a></li>
                </ul>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <?php if (mysqli_num_rows($selusers) > 0) : ?>
                        <table class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Apellidos</th>
                                    <th class="text-center">DPI</th>
                                    <th class="text-center">Asistencia</th>
                                    <th class="text-center">Fecha de registro</th>
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
                                        <td class="text-center">
                                            <input type="checkbox" name="asistencia_<?php echo $row['id_asistencia']; ?>" <?php echo ($row['asistio'] == '1') ? 'checked' : ''; ?> disabled>
                                        </td>
                                        <td class="text-center"><?php echo date('d/m/Y h:i a', strtotime($row['fecha_registro'])); ?></td>
                                        <td class="text-center">
                                            <form action="" method="POST" style="display: inline-block;">
                                                <input type="hidden" name="id_del" value="<?php echo $row['id_asistencia']; ?>">
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
                        <!-- Agregamos un botón para generar el PDF -->
                        <a href="./lib/pdf_asistencia.php" class="btn btn-sm btn-primary" target="_blank"><i class="fa fa-print" aria-hidden="true"></i> Generar PDF</a>
                    <?php else : ?>
                        <h2 class="text-center">No hay registros</h2>
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
                                    <a href="./index.php?view=ViewAsistencia&pagina=<?php echo $pagina - 1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php
                            for ($i = 1; $i <= $numeropaginas; $i++) {
                                if ($pagina == $i) {
                                    echo '<li class="active"><a href="./index.php?view=ViewAsistencia&pagina=' . $i . '">' . $i . '</a></li>';
                                } else {
                                    echo '<li><a href="./index.php?view=ViewAsistencia&pagina=' . $i . '">' . $i . '</a></li>';
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
                                    <a href="./index.php?view=ViewAsistencia&pagina=<?php echo $pagina + 1; ?>" aria-label="Previous">
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

    <br>
<?php
} else {
    // Aquí se muestra el contenido para usuarios no autenticados
?>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <img src="./img/Guatemala.png" alt="Image" class="img-responsive" /><br>
            </div>
            <div class="col-sm-7 text-center">
                <h1 class="text-danger">Lo sentimos, esta página es solamente para usuarios registrados en el COCODE</h1>
                <li>
                    <a href="" data-toggle="modal" data-target="#modalLog"><span class=" "></span>&nbsp;&nbsp;Inicia sesión aquí para poder acceder</a>
                </li>
            </div>
            <div class="col-sm-1">&nbsp;</div>
        </div>
    </div>
<?php
}
?>