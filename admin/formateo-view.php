<?php
require_once './lib/config.php'; // Incluye tu archivo de conexión a la base de datos

$num_user = Mysql::consulta("SELECT * FROM totalingreso");
$num_total_user = mysqli_num_rows($num_user);

$mysqli = mysqli_connect(SERVER, USER, PASS, BD);
mysqli_set_charset($mysqli, "utf8");

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$regpagina = 15;
$inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

$selusers = mysqli_query($mysqli, "SELECT SQL_CALC_FOUND_ROWS * FROM totalingreso LIMIT $inicio, $regpagina");

$totalregistros = mysqli_query($mysqli, "SELECT FOUND_ROWS()");
$totalregistros = mysqli_fetch_array($totalregistros, MYSQLI_ASSOC);

$numeropaginas = ceil($totalregistros["FOUND_ROWS()"] / $regpagina);

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

<div class="container">
    <a href="./index.php" style="color: gray;">
        <span class="glyphicon glyphicon-arrow-left"></span> Volver a la página principal
    </a>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <ul class="nav nav-pills nav-justified">
                    <legend class="text-center" style="color: gray;">"Aqui puede eliminar todos los datos de contabilidad."</legend>
                    <button id="formatearConta" class="btn btn-sm btn-danger"><i class="fa fa-cog" aria-hidden="true"></i> Eliminar Todo</button>

                </ul>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Total Cantidad Ingresada</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ct = $inicio + 1;
                            while ($row = mysqli_fetch_array($selusers, MYSQLI_ASSOC)) :
                            ?>
                                <tr>
                                    <td class="text-center">Q<?php echo $row['cantidad_total']; ?></td>
                                </tr>
                            <?php
                                $ct++;
                            endwhile;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Comienza la segunda tabla "totalretiro" -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>

                                <th class="text-center">Total Cantidad Retirada</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ct = $inicio + 1;
                            // Cambia la consulta SQL para obtener datos de la tabla "totalretiro"
                            $selretiros = mysqli_query($mysqli, "SELECT SQL_CALC_FOUND_ROWS * FROM totalretiro LIMIT $inicio, $regpagina");
                            while ($row = mysqli_fetch_array($selretiros, MYSQLI_ASSOC)) :
                            ?>
                                <tr>

                                    <td class="text-center">Q<?php echo $row['retiro_total']; ?></td>
                                </tr>
                            <?php
                                $ct++;
                            endwhile;
                            ?>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <p style="color: black; font-size: large; font-weight: bold;" id="total_diferencia">Saldo Disponible: Q <?php echo $totalDiferencia; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br><br><br><br>
<script>
    document.getElementById('formatearConta').addEventListener('click', function() {
        if (confirm("ALERTA !. Estás intentando eliminar datos en contabilidad. Esto también eliminará cantidades ingresadas, retiradas y saldo disponible y todo el historial. ¿Deseas continuar?")) {
            // Si el usuario confirma la eliminación, redirige a un archivo PHP para realizar la eliminación.
            window.location.href = 'formatearConta.php';
        }
    });
</script>