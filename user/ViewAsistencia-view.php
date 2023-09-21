<?php
require_once './lib/config.php'; // Incluye tu archivo de conexión a la base de datos

$exitoMessage = ''; // Inicializa la variable de éxito
$errorMessage = ''; // Inicializa la variable de error
$filtrado = false; // Bandera para verificar si se ha enviado el formulario de filtro
$whereClause = ""; // Inicializa la variable $whereClause fuera del bloque de filtro

if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
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

    // Procesar el filtro de fecha si se envió el formulario
    if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_fin'])) {
        $fecha_inicio = $_GET['fecha_inicio'];
        $fecha_fin = $_GET['fecha_fin'];

        if (!empty($fecha_inicio) && !empty($fecha_fin)) {
            $filtrado = true; // Establecer la bandera en true
            // Validar y formatear las fechas (asegúrate de que estén en el formato correcto para tu base de datos)
            $fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($fecha_fin));

            // Construir la cláusula WHERE para filtrar por fecha
            $whereClause = "WHERE fecha_registro BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        }
    }

    // Consulta SQL para recuperar los registros
    $mysqli = mysqli_connect(SERVER, USER, PASS, BD);
    mysqli_set_charset($mysqli, "utf8");

    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $regpagina = 15;
    $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

    // Modificar la consulta SQL para incluir la variable $whereClause
    if ($filtrado) {
        // Si se filtraron los resultados, utiliza los resultados almacenados en la variable
        $selusers = $resultadosFiltrados; // Cambia "resultadosFiltrados" al nombre real de la variable donde almacenaste los resultados
    } else {
        // Si no se filtraron los resultados, ejecuta la consulta normal
        $selusers = mysqli_query($mysqli, "SELECT SQL_CALC_FOUND_ROWS * FROM asistencia LIMIT $inicio, $regpagina");
    }

    // Obtener el número total de registros para paginación
    $totalregistros = mysqli_query($mysqli, "SELECT FOUND_ROWS()");
    $totalregistros = mysqli_fetch_array($totalregistros, MYSQLI_ASSOC);

    $numeropaginas = ceil($totalregistros["FOUND_ROWS()"] / $regpagina);
    ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Mostrar el formulario de filtro solo si no se ha filtrado aún -->
                <?php if (!$filtrado) : ?>
                    <form method="GET">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de Inicio:</label>
                            <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio">
                        </div>
                        <div class="form-group">
                            <label for="fecha_fin">Fecha de Fin:</label>
                            <input type="date" class="form-control" name="fecha_fin" id="fecha_fin">
                        </div>
                        <button type="submit" class="btn btn-primary">Filtrar por Fecha</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <ul class="nav nav-pills nav-justified">
                    <li><a><i class="fa fa-users"></i>&nbsp;&nbsp;Contribuyentes&nbsp;&nbsp;<span class="badge"><?php echo $totalregistros["FOUND_ROWS()"]; ?></span></a></li>
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
                                        <td class="text-center"><?php echo $row['fecha_registro']; ?></td>
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
                        <a href="./index.php?view=RegAsistencia" class="btn btn-primary">Regresar</a>
                        <a href="" class="btn btn-primary">Generar PDF</a>
                        <a href="./index.php?view=EliminarTodo" class="btn btn-danger">Eliminar Todo</a>
                    <?php else : ?>
                        <h2 class="text-center">No hay registros</h2>
                    <?php endif; ?>
                </div>
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
