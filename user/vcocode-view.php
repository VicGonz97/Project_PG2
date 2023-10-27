<?php
// Verifica si existe una sesión con nombre y tipo de usuario
if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
?>
    <?php
    // Incluye el archivo de configuración de la base de datos
    require_once './lib/config.php';

    // Si se ha enviado un formulario para eliminar un usuario COCODE
    if (isset($_POST['id_del'])) {
        // Obtiene el ID del usuario a eliminar desde el formulario
        $id_user = MysqlQuery::RequestPost('id_del');

        // Intenta eliminar al usuario de la base de datos
        if (MysqlQuery::Eliminar("cocode", "id_cocode='$id_user'")) {
            echo '
                <div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">COCODE ELIMINADO</h4>
                    <p class="text-center">
                        Realizado con exito!.
                    </p>
                </div>
            ';
        } else {
            echo '
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                        No hemos podido eliminar, intente de nuevo!.
                    </p>
                </div>
            ';
        }
    }

    // Obtiene el número total de usuarios COCODE en la base de datos
    $num_user = Mysql::consulta("SELECT * FROM cocode");
    $num_total_user = mysqli_num_rows($num_user);

    // Obtiene el número total de usuarios en la tabla "contribuyente" (posiblemente un error duplicado)
    $num_user = Mysql::consulta("SELECT * FROM contribuyente");
    $num_total_user = mysqli_num_rows($num_user);
    ?>

    <!-- Aquí comienza la sección HTML visible para usuarios con sesión iniciada -->
    <div class="container">
        <a href="./index.php" style="color: gray;">
            <span class="glyphicon glyphicon-arrow-left"></span> Volver a la página principal
        </a>
        <div class="row">
            <div class="col-md-12 text-center">
                <ul class="nav nav-pills nav-justified">
                    <li><a><i class="fa fa-users"></i>&nbsp;&nbsp;COCODES&nbsp;&nbsp;<span class="badge"><?php echo $num_total_user; ?></span></a></li>
                </ul>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <?php
                    // Conexión a la base de datos y configuración de caracteres
                    $mysqli = mysqli_connect(SERVER, USER, PASS, BD);
                    mysqli_set_charset($mysqli, "utf8");

                    // Configuración de paginación
                    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                    $regpagina = 10;
                    $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

                    // Consulta para seleccionar usuarios COCODE con paginación
                    $selusers = mysqli_query($mysqli, "SELECT SQL_CALC_FOUND_ROWS * FROM cocode LIMIT $inicio, $regpagina");

                    // Total de registros encontrados
                    $totalregistros = mysqli_query($mysqli, "SELECT FOUND_ROWS()");
                    $totalregistros = mysqli_fetch_array($totalregistros, MYSQLI_ASSOC);

                    $numeropaginas = ceil($totalregistros["FOUND_ROWS()"] / $regpagina);

                    if (mysqli_num_rows($selusers) > 0) :
                    ?>

                        <!-- Tabla que muestra la lista de usuarios COCODE -->
                        <table class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Apellidos</th>
                                    <th class="text-center">Telefono</th>
                                    <th class="text-center">DPI</th>
                                    <th class="text-center">Cargo</th>
                                    <?php if ($_SESSION['tipo'] == "admin") : ?>
                                        <th class="text-center">Opciones</th>
                                    <?php endif; ?>
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
                                        <td class="text-center"><?php echo $row['telefono']; ?></td>
                                        <td class="text-center"><?php echo $row['dpi']; ?></td>
                                        <td class="text-center"><?php echo $row['cargo']; ?></td>
                                        <?php if ($_SESSION['tipo'] == "admin") : ?>
                                            <!-- Opciones de edición y eliminación (solo para usuarios de tipo "admin") -->
                                            <td class="text-center">
                                                <form action="" method="POST" style="display: inline-block;">
                                                    <input type="hidden" name="id_del" value="<?php echo $row['id_cocode']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                                </form>
                                                <a href="./index.php?view=cupdate&id=<?php echo $row['id_cocode']; ?>" class="btn btn-sm btn-warning"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                            </td>
                                        <?php endif; ?>

                                    </tr>
                                <?php
                                    $ct++;
                                endwhile;
                                ?>
                            </tbody>
                        </table>

                    <?php else : ?>
                        <h2 class="text-center">No hay registros</h2>
                    <?php endif; ?>

                    <!-- Botón para agregar un nuevo usuario COCODE (solo para usuarios de tipo "admin") -->
                    <?php if ($_SESSION['tipo'] == "admin") : ?>
                        <a href="./index.php?view=cocode" class="btn btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a>
                    <?php endif; ?>
                    <br><br>
                </div>

                <!-- Aquí se incluye el código de paginación -->
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
                                    <a href="./index.php?view=vcocode&pagina=<?php echo $pagina - 1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php
                            for ($i = 1; $i <= $numeropaginas; $i++) {
                                if ($pagina == $i) {
                                    echo '<li class="active"><a href="./index.php?view=vcocode&pagina=' . $i . '">' . $i . '</a></li>';
                                } else {
                                    echo '<li><a href="./index.php?view=vcocode&pagina=' . $i . '">' . $i . '</a></li>';
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
                                    <a href="./index.php?view=vcocode&pagina=<?php echo $pagina + 1; ?>" aria-label="Previous">
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
<?php
} else {
    // Aquí se muestra el contenido para usuarios no autenticados
?>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <img src="./img/Guatemala.png" alt="Image" class="img-responsive" /><br>
            </div>
            <div class="col-sm-7 text-center">
                <h1 class="text-danger">Lo sentimos, esta página es solamente para usuarios registrados en el COCODE</h1>
                <li>
                    <a href="" data-toggle="modal" data-target="#modalLog"><span class=" "></span>&nbsp;&nbsp;Inicia sesión aquí para poder acceder</a>
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