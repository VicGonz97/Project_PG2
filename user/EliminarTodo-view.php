<?php
require_once './lib/config.php'; // Incluye tu archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mysqli = mysqli_connect(SERVER, USER, PASS, BD);
    mysqli_set_charset($mysqli, "utf8");

    // Consulta SQL para eliminar todos los registros de la tabla asistencia
    $sql = "DELETE FROM asistencia";

    if (mysqli_query($mysqli, $sql)) {
        echo '
            <div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="text-center"> ELIMINADO</h4>
                <p class="text-center">
                    Todos los registros de asistencia han sido eliminados.
                </p>
            </div>
        ';
    } else {
        echo '
            <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                <p class="text-center">
                    No hemos podido eliminar todos los registros de asistencia.
                </p>
            </div>
        ';
    }
}
?>