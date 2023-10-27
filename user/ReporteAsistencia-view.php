<?php
require_once './lib/config.php'; // Incluye tu archivo de configuración de la base de datos

$exitoMessage = ''; // Inicializa la variable de éxito
$errorMessage = ''; // Inicializa la variable de error

if (isset($_SESSION['exitoMessage'])) {
    echo '<div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="text-center"> ELIMINADO</h4>
<p class="text-center">
¡Todos los registros de asistencia han sido eliminados!.
</p>' . $_SESSION['exitoMessage'] . '</div>';

    unset($_SESSION['exitoMessage']); // Limpia el mensaje de éxito
}

if (isset($_SESSION['errorMessage'])) {
    echo '<div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                    Ocurrió un error al eliminar los registros..
                    </p>
                    ' . $_SESSION['errorMessage'] . '</div>';
    unset($_SESSION['errorMessage']); // Limpia el mensaje de error
}


// Función para escapar y formatear las fechas
function formatDate($date)
{
    return date('Y-m-d', strtotime($date));
}

$num_user = Mysql::consulta("SELECT * FROM asistencia");
$num_total_user = mysqli_num_rows($num_user);

// Consulta SQL para recuperar los registros
$mysqli = mysqli_connect(SERVER, USER, PASS, BD);
mysqli_set_charset($mysqli, "utf8");

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$regpagina = 15;
$inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

$selusers = mysqli_query($mysqli, "SELECT SQL_CALC_FOUND_ROWS * FROM asistencia LIMIT $inicio, $regpagina");

// Inicializa las fechas de inicio y fin
$fecha_inicio = isset($_POST['fecha_inicio']) ? formatDate($_POST['fecha_inicio']) . ' 00:00:00' : '';
$fecha_fin = isset($_POST['fecha_fin']) ? formatDate($_POST['fecha_fin']) . ' 23:59:59' : '';

// Consulta SQL modificada con filtros de fecha
if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM asistencia WHERE fecha_registro BETWEEN '$fecha_inicio' AND '$fecha_fin' LIMIT $inicio, $regpagina";
    $totalregistros = mysqli_query($mysqli, "SELECT FOUND_ROWS()");
} else {
    $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM asistencia LIMIT $inicio, $regpagina";
    $totalregistros = mysqli_query($mysqli, "SELECT FOUND_ROWS()");
}

$selusers = mysqli_query($mysqli, $consulta);

// Obtener el número total de registros para paginación
$totalregistros = mysqli_query($mysqli, "SELECT FOUND_ROWS()");
$totalregistros = mysqli_fetch_array($totalregistros, MYSQLI_ASSOC);

$numeropaginas = ceil($totalregistros["FOUND_ROWS()"] / $regpagina);
?>
<div class="container">
    <a href="./index.php?view=AddReporte" style="color: gray;">
        <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <ul class="nav nav-pills nav-justified">
                <li><a><i class="fa fa-users"></i>&nbsp;&nbsp;Asistentes&nbsp;&nbsp;<span class="badge"><?php echo $num_total_user; ?></span></a></li>
            </ul>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12 text-center">
            <form class="form-horizontal" method="POST" action="">
                <fieldset>
                    <legend>"Visualiza y descarga la asistencia."</legend>
                    <!-- Agregar tus campos de formulario aquí -->
                    <div class="form-group">
                        <label for="fecha_inicio" class="col-lg-2 control-label">Fecha de Inicio:</label>
                        <div class="col-lg-4">
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
                        </div>
                        <label for="fecha_fin" class="col-lg-2 control-label">Fecha de Fin:</label>
                        <div class="col-lg-4">
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $fecha_fin; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary">Filtrar por fecha</button>
                            <!-- Agregamos un botón para generar el PDF -->
                            <a href="./lib/pdf_AsistenciaFiltro.php" class="btn btn-sm btn-success" target="_blank"><i class="fa fa-print" aria-hidden="true"></i> Generar PDF</a>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

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
                                </tr>
                            <?php
                                $ct++;
                            endwhile;
                            ?>
                        </tbody>
                    </table>
                    <button id="eliminar_asistencia" class="btn btn-sm btn-danger"><i class="fa fa-trash" aria-hidden="true"></i> Eliminar Todo</button>
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
                                <a href="./index.php?view=ReporteAsistencia&pagina=<?php echo $pagina - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php
                        for ($i = 1; $i <= $numeropaginas; $i++) {
                            if ($pagina == $i) {
                                echo '<li class="active"><a href="./index.php?view=ReporteAsistencia&pagina=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="./index.php?view=ReporteAsistencia&pagina=' . $i . '">' . $i . '</a></li>';
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
                                <a href="./index.php?view=ReporteAsistencia&pagina=<?php echo $pagina + 1; ?>" aria-label="Previous">
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

<script>
    document.getElementById('eliminar_asistencia').addEventListener('click', function() {
        if (confirm("ALERTA !. Estás intentando eliminar el historial. Esto también eliminará todas las asistencias registradas en el módulo de asistencia. ¿Deseas continuar?")) {
            // Si el usuario confirma la eliminación, redirige a un archivo PHP para realizar la eliminación.
            window.location.href = 'eliminar_asistencia.php';
        }
    });
</script>

<br><br>