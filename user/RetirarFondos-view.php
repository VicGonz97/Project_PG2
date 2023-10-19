<?php
require_once './lib/config.php'; // Include your database connection file

$mysqli = new mysqli(SERVER, USER, PASS, BD);
mysqli_set_charset($mysqli, "utf8");

// Verifica si existe una sesión con nombre y tipo de usuario
if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
    // Verifica si se han recibido datos del formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['cantidad']) && isset($_POST['descripcion'])) {
            // Recopila los datos del formulario
            $cantidad = $_POST['cantidad'];
            $descripcion = $_POST['descripcion'];
            $fecha_retiro = $_POST['fecha_retiro'];

            // Asegúrate de definir la función MysqlQuery::Guardar para realizar la inserción en la base de datos.
            // Utiliza prepared statements to prevent SQL injection
            $stmt = $mysqli->prepare("INSERT INTO retiro (cantidad, descripcion, fecha_retiro) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $cantidad, $descripcion);

            $mysqli->query("START TRANSACTION"); // Iniciar transacción

            // Realiza una consulta para calcular la diferencia entre las tablas
            $query = " 
                SELECT
                (SELECT SUM(cantidad_total) FROM totalingreso) -
                (SELECT SUM(retiro_total) FROM totalretiro) AS total_diferencia;
            ";

            $result = $mysqli->query($query);
            $row = $result->fetch_assoc();
            $totalDiferencia = $row['total_diferencia'];

            // Verifica si los fondos son suficientes
            if ($totalDiferencia >= $cantidad) {
                // Fondos suficientes, procede con la inserción del retiro
                if ($stmt->execute()) {
                    $mysqli->query("COMMIT"); // Confirmar transacción
                    echo '
                        <div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <p class="text-center">
                                Transaccion realizada correctamente!.
                            </p>
                        </div>
                    ';
                } else {
                    $mysqli->query("ROLLBACK"); // Revertir transacción en caso de error
                    echo '
                        <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                            <button type="button" class close data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                            <p class="text-center">
                                ERROR AL RETIRAR FONDO: Por favor, inténtelo nuevamente.
                            </p>
                        </div>
                    ';
                }
            } else {
                $mysqli->query("ROLLBACK"); // Revertir transacción ya que no hay fondos suficientes
                echo '
                    <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="text-center">FONDOS INSUFICIENTES</h4>
                        <p class="text-center">
                            No hay fondos suficientes para realizar esta transacción.
                        </p>
                    </div>
                ';
            }
        }
    }
}

$mysqli = new mysqli(SERVER, USER, PASS, BD);
mysqli_set_charset($mysqli, "utf8");

// Query to get the total withdrawal
$result = mysqli_query($mysqli, "SELECT SUM(cantidad) AS retiro_total FROM retiro");
$retiro_total = mysqli_fetch_assoc($result);

if ($retiro_total['retiro_total'] === null) {
    $retiro_total['retiro_total'] = 0;
}

// Actualizar el monto total en la tabla "saldo"
$update_query = mysqli_query($mysqli, "UPDATE totalretiro SET retiro_total = " . $retiro_total['retiro_total']);

// Realiza una consulta para calcular la diferencia entre las tablas
$query = " 
   SELECT
      (SELECT SUM(cantidad_total) FROM totalingreso) -
      (SELECT SUM(retiro_total) FROM totalretiro) AS total_diferencia;
";

$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$totalDiferencia = $row['total_diferencia'];

?>

<!-- Formulario de registro -->
<div class="container">
    <a href="./index.php?view=AddConta" style="color: gray;">
        <span class="glyphicon glyphicon-arrow-left"></span> Regresar
    </a>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title text-center"><strong>
                            <p id="total_diferencia">Saldo disponible: Q<?php echo number_format($totalDiferencia, 2); ?></p>
                        </strong></h3>
                    <h3 class="panel-title text-center"><strong>
                            <p id="retiro_total">Total retirada: - Q<?php echo number_format($retiro_total['retiro_total'], 2); ?></p>
                        </strong></h3>
                </div>

                <div class="container">
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="./img/Guatemala.png" alt="Image" class="img-responsive animated flipInY">
                        </div>
                        <div class="col-sm-8">
                            <form class="form-horizontal" role="form" method="POST">
                                <fieldset>
                                    <div class="form-group">
                                        <br>
                                        <label class="col-sm-2 control-label">Cantidad</label>
                                        <div class="col-sm-10">
                                            <div class='input-group'>
                                                <input type="text" class="form-control" placeholder="Ingrese cantidad a retirar" required name="cantidad" pattern="^\d*\.?\d*$">
                                                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Descripción</label>
                                        <div class="col-sm-10">
                                            <div class='input-group'>
                                                <input type="text" class="form-control" placeholder="Máximo 50 caracteres" required name="descripcion" pattern=".*" maxlength="50">
                                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Fecha de retiro</label>
                                        <div class="col-sm-10">
                                            <div class='input-group'>
                                                <input type="datetime-local" class="form-control" required name="fecha_retiro" id="fecha_retiro" readonly>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <div class="col-sm-12 text-center">
                                                <button type="submit" class="btn btn-info">Realizar transacción</button>
                                            </div>
                                        </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Obten la fecha y hora actual
        let fechaActual = new Date();

        // Ajusta el huso horario a Guatemala (GMT-6)
        fechaActual.setHours(fechaActual.getHours() - 6);

        // Formatea la fecha y hora en el formato "YYYY-MM-DDTHH:MM"
        let fechaFormateada = fechaActual.toISOString().slice(0, 16);

        // Asigna la fecha y hora formateada al campo de fecha
        document.getElementById("fecha_retiro").value = fechaFormateada;
    });
</script>