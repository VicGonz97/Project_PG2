<?php

require_once './lib/config.php'; // Incluye tu archivo de conexión a la base de datos

$mysqli = new mysqli(SERVER, USER, PASS, BD);
mysqli_set_charset($mysqli, "utf8");

// Verifica si existe una sesión con nombre y tipo de usuario
if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {

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

    // Add a new SQL query to calculate the sum of "cantidad"
    $sum_query = mysqli_query($mysqli, "SELECT SUM(cantidad) AS total_cantidad FROM contabilidad");
    $total_cantidad = mysqli_fetch_assoc($sum_query);

    if ($total_cantidad['total_cantidad'] === null) {
        $total_cantidad['total_cantidad'] = 0;
    }

    // Realiza una consulta para obtener el total de contribuyentes
    $contribuyentes_query = mysqli_query($mysqli, "SELECT COUNT(*) AS num_contribuyentes FROM contribuyente");
    $num_contribuyentes = mysqli_fetch_assoc($contribuyentes_query);

    if ($num_contribuyentes['num_contribuyentes'] === null) {
        $num_contribuyentes['num_contribuyentes'] = 0;
    }

    // Realiza una consulta para obtener el total de miembros COCODE
    $cocode_query = mysqli_query($mysqli, "SELECT COUNT(*) AS num_cocode FROM cocode");
    $num_cocode = mysqli_fetch_assoc($cocode_query);

    if ($num_cocode['num_cocode'] === null) {
        $num_cocode['num_cocode'] = 0;
    }

?>

    <div class="container">
        <a href="./index.php" style="color: gray;">
            <span class="glyphicon glyphicon-arrow-left"></span> Volver a la página principal
        </a>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <form class "form-horizontal" method="POST" action="procesar_registro.php">
                    <fieldset>
                        <legend class="text-center" style="color: gray;">Ver y descargar informes, descargue su informe detallado en formato PDF</legend>
                        <!-- Agregar tus campos de formulario aquí -->
                        <div class="form-group">
                            <label for="opcion" class="col-sm-3 control-label"></label>
                            <div class="col-sm-7">
                                <select id="opcion" name="opcion" class="form-control" onchange="redirectToPage(this)">
                                    <option value="registrar_nuevo">Seleccione una opción</option>
                                    <option value="asistencia">Asistencia</option>
                                    <option value="contabilidad">Historial Ingresos</option>
                                    <option value="retiro">Historial Retiros</option>
                                    <!-- Agrega más opciones según tus necesidades -->
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <br><br>
                <div class="container">
                    <div class="col-sm-4">
                        <div class="panel panel-info" style="border-color: #3498db;"> <!-- Estilo azul suave para "Ingresada" -->
                            <div class="panel-heading" style="background-color: #3498db; color: white;"> <!-- Fondo azul suave y texto blanco -->
                                <h3 class="panel-title text-center" style="font-size: 15px;"><strong>Total Cantidad Ingresada</strong></h3>
                            </div>
                            <div class="panel-body text-center" style="font-size: 24px; font-weight: bold;"> <!-- Tamaño y negritas en el texto -->
                                <p id_ingreso="total_cantidad">Q<?php echo number_format($total_cantidad['total_cantidad'], 2); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="panel panel-info" style="border-color: #e74c3c;">
                            <div class="panel-heading" style="background-color: #e74c3c; color: white;">
                                <h3 class="panel-title text-center" style="font-size: 15px;"><strong>Total Cantidad retirada</strong></h3>
                            </div>
                            <div class="panel-body text-center" style="font-size: 24px; font-weight: bold;">
                                <p id="retiro_total">Q<?php echo number_format($retiro_total['retiro_total'], 2); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="panel panel-info" style="border-color: green;"> <!-- Estilo verde suave para "Disponible" -->
                            <div class="panel-heading" style="background-color: green; color: white;"> <!-- Fondo verde suave y texto blanco -->
                                <h3 class="panel-title text-center" style="font-size: 15px;"><strong>Saldo Disponible</strong></h3>
                            </div>
                            <div class="panel-body text-center" style="font-size: 24px; font-weight: bold;"> <!-- Tamaño y negritas en el texto -->
                                <p id="total_diferencia" class="text-center">Q<?php echo number_format($totalDiferencia, 2); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        <div class="row text-center">
                            <div class="col-sm-3">
                                <a href="./index.php?view=ReporteCoco">
                                    <div class="panel panel-info" style="border-color: black;">
                                        <div class="panel-heading" style="background-color: rgba(0, 0, 0, 0.5); color: white;">
                                            <h3 class="panel-title" style="font-size: 15px;"><strong>Cocode</strong></h3>
                                        </div>
                                        <div class="panel-body" style="font-size: 24px; font-weight: bold;">
                                            <p id_ingreso="num_total_user"><?php echo number_format($num_cocode['num_cocode']); ?></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-3">
                                <a href="./index.php?view=ReporteContr">
                                    <div class="panel panel-info" style="border-color: black;">
                                        <div class="panel-heading" style="background-color: rgba(0, 0, 0, 0.5); color: white;">
                                            <h3 class="panel-title" style="font-size: 15px;"><strong>Contribuyentes</strong></h3>
                                        </div>
                                        <div class="panel-body" style="font-size: 24px; font-weight: bold;">
                                            <p id_ingreso="num_total_contribuyentes"><?php echo number_format($num_contribuyentes['num_contribuyentes']); ?></p>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
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
                <img src="./img/Guatemala.png" alt="Image" class="img-responsive" /><br>
            </div>
            <div class="col-sm-7 text-center">
                <h1 class="text-danger">Lo sentimos, esta página es solamente para usuarios registrados en el COCODE</h1>
                <li>
                    <a href="" data-toggle="modal" data-target="#modalLog"><span class=" "></span>&nbsp;&nbsp;Inicia sesión aquí. Para poder acceder</a>
                </li>
            </div>
            <div class="col-sm-1">&nbsp;</div>
        </div>
    </div>
<?php
}
?>

<script type="text/javascript">
    function redirectToPage(selectElement) {
        var selectedOption = selectElement.value;
        if (selectedOption === "asistencia") {
            window.location.href = "./index.php?view=ReporteAsistencia";
        } else if (selectedOption === "contabilidad") {
            window.location.href = "./index.php?view=ReporteContabilidad";
        }else if (selectedOption === "retiro") {
            window.location.href = "./index.php?view=HistorialRetiros";
        }
        // Agrega más condiciones para otras opciones si es necesario
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#fechainput").datepicker();
    });
</script>