<?php

// Inicializar la sesión si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de configuración y la conexión a la base de datos
require_once './lib/config.php';

$exitoMessage = ''; // Inicializar el mensaje de éxito
$errorMessage = ''; // Inicializar el mensaje de error

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se ha iniciado sesión y el usuario tiene permiso para registrar asistencia
    if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {

        // Verificar si se han seleccionado contribuyentes para marcar asistencia
        if (isset($_POST['asistencia']) && is_array($_POST['asistencia']) && !empty($_POST['asistencia'])) {
            // Crear una conexión a la base de datos
            $mysqli = mysqli_connect(SERVER, USER, PASS, BD);
            if (!$mysqli) {
                die("Error en la conexión a la base de datos: " . mysqli_connect_error());
            }

            // Obtener la fecha actual en el formato deseado (Y-m-d H:i:s)
            $fecha_actual = date('Y-m-d H:i:s');

            // Obtener todos los contribuyentes de la base de datos
            $query_contribuyentes = "SELECT id_contr, nombre, apellido, dpi FROM contribuyente";
            $result_contribuyentes = mysqli_query($mysqli, $query_contribuyentes);

            if (!$result_contribuyentes) {
                $errorMessage = "Error al obtener la lista de contribuyentes: " . mysqli_error($mysqli);
            } else {
                while ($row_contribuyente = mysqli_fetch_assoc($result_contribuyentes)) {
                    $contribuyenteId = $row_contribuyente['id_contr'];

                    // Verificar si este contribuyente se seleccionó en el formulario
                    $asistio = in_array($contribuyenteId, $_POST['asistencia']) ? 1 : 0;

                    // Obtener los datos del contribuyente desde la base de datos
                    $nombre = $row_contribuyente['nombre'];
                    $apellido = $row_contribuyente['apellido'];
                    $dpi = $row_contribuyente['dpi'];

                    // Preparar la consulta SQL para insertar asistencia con nombre, apellido, dpi y asistencia
                    $query_asistencia = "INSERT INTO asistencia (nombre, apellido, dpi, asistio, fecha_registro) VALUES (?, ?, ?, ?, ?)";

                    // Preparar una sentencia SQL usando consultas preparadas
                    if ($stmt_asistencia = mysqli_prepare($mysqli, $query_asistencia)) {
                        mysqli_stmt_bind_param($stmt_asistencia, "sssis", $nombre, $apellido, $dpi, $asistio, $fecha_actual);
                        mysqli_stmt_execute($stmt_asistencia);

                        // Cerrar la sentencia de asistencia
                        mysqli_stmt_close($stmt_asistencia);
                    } else {
                        $errorMessage = "Error en la consulta preparada de asistencia: " . mysqli_error($mysqli);
                    }
                }

                // Liberar el resultado de la consulta de contribuyentes
                mysqli_free_result($result_contribuyentes);
            }

            // Cerrar la conexión a la base de datos
            mysqli_close($mysqli);

            if (empty($errorMessage)) {
                $exitoMessage = 'Registrada Exitosamente!';
            }
        } else {
            $errorMessage = 'NO SE HA SELECCIONADO NINGÚN CONTRIBUYENTE: Por favor, seleccione al menos uno.';
        }
    } else {
        $errorMessage = 'No tienes permiso para registrar asistencia.';
    }
}
?>

<!-- Mostrar mensajes de éxito o error -->
<?php if (!empty($exitoMessage)) : ?>
    <div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="text-center">ASISTENCIA</h4>
        <p class="text-center">
            <?php echo $exitoMessage; ?>
        </p>
    </div>
<?php endif; ?>

<?php if (!empty($errorMessage)) : ?>
    <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="text-center">OCURRIÓ UN ERROR</h4>
        <p class="text-center">
            <?php echo $errorMessage; ?>
        </p>
    </div>
<?php endif; ?>