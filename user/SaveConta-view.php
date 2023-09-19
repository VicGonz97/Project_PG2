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
    // Verificar si se ha iniciado sesión y el usuario tiene permiso para registrar 
    if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
        // Verificar si se han seleccionado contribuyentes para registrar
        if (isset($_POST['contabilidad']) && is_array($_POST['contabilidad']) && !empty($_POST['contabilidad'])) {
            // Crear una conexión a la base de datos
            $mysqli = mysqli_connect(SERVER, USER, PASS, BD);
            if (!$mysqli) {
                die("Error en la conexión a la base de datos: " . mysqli_connect_error());
            }

            // Obtener la fecha actual en el formato deseado (Y-m-d H:i:s)
            $fecha_actual = date('Y-m-d H:i:s');




            // Iterar a través de los contribuyentes seleccionados y registrar
            foreach ($_POST['contabilidad'] as $contribuyenteId) {
                // Obtener los datos del contribuyente (nombre, apellido, dpi)
                $query_contribuyente = "SELECT nombre, apellido, dpi FROM contribuyente WHERE id_contr = ?";
                if ($stmt_contribuyente = mysqli_prepare($mysqli, $query_contribuyente)) {
                    mysqli_stmt_bind_param($stmt_contribuyente, "i", $contribuyenteId);
                    mysqli_stmt_execute($stmt_contribuyente);
                    mysqli_stmt_store_result($stmt_contribuyente);

                    if (mysqli_stmt_num_rows($stmt_contribuyente) > 0) {
                        mysqli_stmt_bind_result($stmt_contribuyente, $nombre, $apellido, $dpi);
                        mysqli_stmt_fetch($stmt_contribuyente);

                        // Preparar la consulta SQL para insertar asistencia con nombre, apellido, dpi
                        $cantidad = $_POST['cantidad']; // Get quantity from the form
                        $query_contabilidad = "INSERT INTO contabilidad (nombre, apellido, dpi, cantidad, fecha_registro) VALUES ($nombre, $apellido, $dpi, $cantidad,  $fecha_actual)";

                        // Preparar una sentencia SQL usando consultas preparadas
                        if ($stmt_contabilidad = mysqli_prepare($mysqli, $query_contabilidad)) {
                            mysqli_stmt_bind_param($stmt_contabilidad, "sssss", $nombre, $apellido, $dpi, $cantidad,  $fecha_actual);
                            mysqli_stmt_execute($stmt_contabilidad);

                            // Cerrar la sentencia de asistencia
                            mysqli_stmt_close($stmt_contabilidad);
                        } else {
                            $errorMessage = "Error en la consulta preparada de asistencia: " . mysqli_error($mysqli);
                        }
                    } else {
                        $errorMessage = "No se encontraron datos para el contribuyente con ID: " . $contribuyenteId;
                    }

                    // Cerrar la sentencia de contribuyente
                    mysqli_stmt_close($stmt_contribuyente);
                } else {
                    $errorMessage = "Error en la consulta preparada de contribuyente: " . mysqli_error($mysqli);
                }
            }

            // Cerrar la conexión a la base de datos
            mysqli_close($mysqli);

            if (empty($errorMessage)) {
                $exitoMessage = 'Registrada Exitosamente!';
            }
        } else {
            $errorMessage = 'NO SE HA INGRESADO CANTIDAD: Por favor, verifique!.';
        }
    } else {
        $errorMessage = 'No tienes permiso para registrar.';
    }
}
?>

<!-- Mostrar mensajes de éxito o error -->
<?php if (!empty($exitoMessage)) : ?>
    <div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="text-center">INGRESO</h4>
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