<?php


if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) { ?>
    <?php
    require_once './lib/config.php'; // Incluye tu archivo de conexión a la base de datos

    $num_user = Mysql::consulta("SELECT * FROM contribuyente");
    $num_total_user = mysqli_num_rows($num_user);
    ?>
    <div class="container">
        <a href="./index.php" style="color: gray;">
            <span class="glyphicon glyphicon-arrow-left"></span> Volver a la página principal
        </a>

        <div class="row">
            <div class="col-md-12 text-center">
                <ul class="nav nav-pills nav-justified">
                    <li><a><i class="fa fa-users"></i>&nbsp;&nbsp;Contribuyentes&nbsp;&nbsp;<span class="badge"><?php echo $num_total_user; ?></span></a></li>
                </ul>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <?php
                    $mysqli = mysqli_connect(SERVER, USER, PASS, BD);
                    mysqli_set_charset($mysqli, "utf8");

                    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                    $regpagina = 15;
                    $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

                    $selusers = mysqli_query($mysqli, "SELECT SQL_CALC_FOUND_ROWS * FROM contribuyente LIMIT $inicio, $regpagina");

                    $totalregistros = mysqli_query($mysqli, "SELECT FOUND_ROWS()");
                    $totalregistros = mysqli_fetch_array($totalregistros, MYSQLI_ASSOC);

                    $numeropaginas = ceil($totalregistros["FOUND_ROWS()"] / $regpagina);
                    if (mysqli_num_rows($selusers) > 0) :

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

                        <div id="confirmacionDialog" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">ALERTA!</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Verificar si los datos se ingresaron correctamente?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Verificar</button>
                                        <button type="button" class="btn btn-success" id="confirmarGuardar">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <form action="./index.php?view=SaveConta" method="POST"> <!-- Agregamos un formulario para enviar la asistencia -->
                            <table class="table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">Apellidos</th>
                                        <th class="text-center">DPI</th>
                                        <th class="text-center">Cantidad</th> <!-- Cambiamos el nombre de la columna -->
                                        <th class="text-center">Fecha</th>
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
                                                <!-- Cambiamos el tipo de campo de entrada -->
                                                <input type="number" name="cantidad_<?php echo $row['id_contr']; ?>" />
                                            </td>
                                            <td class="text-center"><?php echo date('d/m/Y h:i a'); ?></td> <!-- Muestra la fecha y hora actual -->
                                        </tr>
                                    <?php
                                        $ct++;
                                    endwhile;
                                    ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-success">Guardar</button> <!-- Agregamos un botón para registrar la asistencia -->
                            <a href="./index.php?view=ViewConta" class="btn btn-info">Ver Contribuciones</a>
                            <a href="./index.php?view=RetirarFondos" class="btn btn-success">Realizar Retiro</a>
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#saldoModal">Ver Saldo</a>
                        </form>

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
                                    <a href="./index.php?view=AddConta&pagina=<?php echo $pagina - 1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php
                            for ($i = 1; $i <= $numeropaginas; $i++) {
                                if ($pagina == $i) {
                                    echo '<li class="active"><a href="./index.php?view=AddConta&pagina=' . $i . '">' . $i . '</a></li>';
                                } else {
                                    echo '<li><a href="./index.php?view=AddConta&pagina=' . $i . '">' . $i . '</a></li>';
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
                                    <a href="./index.php?view=AddConta&pagina=<?php echo $pagina + 1; ?>" aria-label="Previous">
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
    <!-- Modal para mostrar el saldo disponible -->
    <div id="saldoModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Saldo Disponible</h4>
                </div>
                <div class="modal-body">
                    <p>Saldo Disponible: Q<?php echo number_format($totalDiferencia, 2); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
                <h1 class="text-danger">Lo sentimos esta página es solamente para usuarios registrados en el COCODE</h1>
                <li>
                    <a href="" data-toggle="modal" data-target="#modalLog"><span class=" "></span>&nbsp;&nbsp;Inicia sesión aqui!. Para poder acceder</a>
                </li>
            </div>
            <div class="col-sm-1">&nbsp;</div>
        </div>
    </div>
<?php
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#fechainput").datepicker();
    });
</script>


<script type="text/javascript">
    $(document).ready(function() {
        $("#fechainput").datepicker();

        // Agregar un manejador de evento click al botón "Guardar"
        $("button[type='submit']").on("click", function(e) {
            e.preventDefault(); // Evitar la acción predeterminada del botón "Guardar"

            // Mostrar el cuadro de diálogo de confirmación
            $("#confirmacionDialog").modal("show");
        });

        // Agregar un manejador de evento al botón de confirmación
        $("#confirmarGuardar").on("click", function() {
            // Si el usuario confirma, envía el formulario
            $("form").submit();
        });
    });
</script>