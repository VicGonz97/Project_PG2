<?php
require_once './lib/config.php'; // Incluye tu archivo de conexión a la base de datos

$exitoMessage = ''; // Inicializa la variable de éxito
$errorMessage = ''; // Inicializa la variable de error

if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $mysqli = mysqli_connect(SERVER, USER, PASS, BD);
        mysqli_set_charset($mysqli, "utf8");

        $atLeastOneEntry = false; // Flag to check if at least one entry was made

        foreach ($_POST as $key => $value) {
            if (strpos($key, 'cantidad_') === 0) {
                $id_contr = substr($key, 9); // Obtén el ID del contribuyente desde el nombre del campo
                $cantidad = (float)$value; // Convierte la cantidad a un número decimal

                if ($cantidad > 0) { // Check if a valid quantity is entered
                    // Inserta los datos en la base de datos
                    $query = "INSERT INTO contabilidad (nombre, apellido, dpi, cantidad) SELECT nombre, apellido, dpi, $cantidad FROM contribuyente WHERE id_contr = $id_contr";
                    if (mysqli_query($mysqli, $query)) {
                        // Registro exitoso
                        $atLeastOneEntry = true; // Set the flag to true if at least one entry was made
                    } else {
                        // Error en el registro
                        $errorMessage = 'Error en el registro: ' . mysqli_error($mysqli);
                    }
                }
            }
        }

        // Cierra la conexión a la base de datos
        mysqli_close($mysqli);

        if ($atLeastOneEntry) {
            $exitoMessage = 'Registrada Exitosamente!';
        } else {
            $errorMessage = 'No se ingresó ninguna cantidad válida.';
        }
    } else {
        $errorMessage = 'NO SE HA SELECCIONADO NINGÚN CONTRIBUYENTE: Por favor, seleccione al menos uno.';
    }
} else {
    $errorMessage = 'No tienes permiso para registrar asistencia.';
}
?>

<!-- Mostrar mensajes de éxito o error -->
<?php if (!empty($exitoMessage)) : ?>
<div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="text-center">Registrado</h4>
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
