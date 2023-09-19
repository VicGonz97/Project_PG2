<?php
if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
    // Incluye tu archivo de conexión a la base de datos (config.php u otro)
    require_once './lib/config.php';

    // Obtiene la cantidad total de registros de contribuyentes
    $num_user = Mysql::consulta("SELECT * FROM contribuyente");
    $num_total_user = mysqli_num_rows($num_user);
?>

    <div class="container">
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
                    // Conecta a la base de datos
                    $mysqli = mysqli_connect(SERVER, USER, PASS, BD);
                    mysqli_set_charset($mysqli, "utf8");

                    // Obtiene la página actual y calcula el inicio de la paginación
                    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                    $regpagina = 15;
                    $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

                    // Consulta para obtener los registros limitados según la paginación
                    $selusers = mysqli_query($mysqli, "SELECT SQL_CALC_FOUND_ROWS * FROM contribuyente LIMIT $inicio, $regpagina");

                    // Obtiene el total de registros encontrados sin límite
                    $totalregistros = mysqli_query($mysqli, "SELECT FOUND_ROWS()");
                    $totalregistros = mysqli_fetch_array($totalregistros, MYSQLI_ASSOC);

                    // Calcula el número de páginas
                    $numeropaginas = ceil($totalregistros["FOUND_ROWS()"] / $regpagina);

                    if (mysqli_num_rows($selusers) > 0) :
                    ?>
                        <form action="./index.php?view=SaveConta" method="POST">
                            <table class="table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">Apellidos</th>
                                        <th class="text-center">DPI</th>
                                        <th class="text-center">Cantidad</th> <!-- Nueva columna para la Cantidad -->
                                        <th class="text-center">Fecha y Hora</th> <!-- Nueva columna para la Fecha y Hora -->
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
                                                <label for="cantidad_<?php echo $row['id_contr']; ?>">Cantidad</label>
                                                <input type="text" name="cantidad_[<?php echo $row['id_contr']; ?>]" id="cantidad_<?php echo $row['id_contr']; ?>" placeholder="Cantidad">
                                            </td>
                                            <td class="text-center"><?php echo date('Y-m-d H:i:s'); ?></td>
                                            <td>
                                                <button type="submit" class="btn btn-primary">Guardar y generar comprobante</button>
                                                <button type="submit" class="btn btn-primary">PDF</button>
                                            </td>
                                        </tr>
                                    <?php
                                        $ct++;
                                    endwhile;
                                    ?>
                                </tbody>
                            </table>

                            <a href="./index.php?view=ViewAsistencia" class="btn btn-info">Ver caja</a>
                            <a href="./index.php" class="btn btn-danger">Cancelar</a>
                        </form>
                    <?php else : ?>
                        <h2 class="text-center">No hay registros</h2>
                    <?php endif; ?>
                </div>
                <?php if ($numeropaginas >= 1) : ?>
                    <nav aria-label="Page navigation" class="text-center">
                        <!-- Aquí debes agregar tu código para generar los enlaces de paginación -->
                    </nav>
                <?php endif; ?>
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
                <h1 class="text-danger">Lo sentimos, esta página es solamente para el COCODE a Cargo</h1>
                <h3 class="text-info text-center">Inicia sesión como COCODE para poder acceder</h3>
            </div>
            <div class="col-sm-1">&nbsp;</div>
        </div>
    </div>
<?php
}
?>