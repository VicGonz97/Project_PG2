<?php
if ($_SESSION['nombre'] != "" && $_SESSION['tipo'] == "admin") {
    if (isset($_POST['id_del'])) {
        $id_user = MysqlQuery::RequestPost('id_del');
        if (MysqlQuery::Eliminar("usuario", "id_usuario='$id_user'")) {
            echo '
                <div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">USUARIO ELIMINADO</h4>
                    <p class="text-center">
                        El usuario fue eliminado del sistema con éxito.
                    </p>
                </div>
            ';
        } else {
            echo '
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                        No hemos podido eliminar el usuario.
                    </p>
                </div>
            ';
        }
    }

    $idA = $_SESSION['id'];
    /* Todos los admins*/
    $num_admin = Mysql::consulta("SELECT * FROM administrador WHERE id_admin!='1' AND id_admin!='$idA'");
    $num_total_admin = mysqli_num_rows($num_admin);

    /* Todos los users*/
    $num_user = Mysql::consulta("SELECT * FROM usuario");
    $num_total_user = mysqli_num_rows($num_user);

?>
    <div class="container">
        <div class="row">
            <div class="col-sm-2">
                <img src="./img/Guatemala.png" alt="Image" class="img-responsive animated flipInY">
            </div>
            <div class="col-sm-10">
                <p class="lead text-info">Bienvenido Administrador! Por politica del COCODE solo deberia de existir un usuario, si hay mas de un usuario porfavor verificar con el miembro del COCODE y puede eliminar el ususario.</p>
            </div>
        </div>
    </div>

    <br><br>

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <ul class="nav nav-pills nav-justified">
                    <li><a href="./admin.php?view=users"><i class="fa fa-users"></i>&nbsp;&nbsp;Usuarios&nbsp;&nbsp;<span class="badge"><?php echo $num_total_user; ?></span></a></li>
                    <li><a href="./admin.php?view=admin"><i class="fa fa-male"></i>&nbsp;&nbsp;Administradores&nbsp;&nbsp;<span class="badge"><?php echo $num_total_admin; ?></span></a></li>
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

                    $selusers = mysqli_query($mysqli, "SELECT SQL_CALC_FOUND_ROWS * FROM usuario LIMIT $inicio, $regpagina");

                    $totalregistros = mysqli_query($mysqli, "SELECT FOUND_ROWS()");
                    $totalregistros = mysqli_fetch_array($totalregistros, MYSQLI_ASSOC);

                    $numeropaginas = ceil($totalregistros["FOUND_ROWS()"] / $regpagina);
                    if (mysqli_num_rows($selusers) > 0) :
                    ?>
                        <table class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nombre completo</th>
                                    <th class="text-center">Nombre de usuario</th>
                                    <th class="text-center">Contraseña</th>
                                    <th class="text-center">Correo electronico</th>
                                    <th class="text-center">Telefono</th>
                                    <th class="text-center">DPI</th>
                                    <th class="text-center">Cargo</th>
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
                                        <td class="text-center"><?php echo $row['nombre_completo']; ?></td>
                                        <td class="text-center"><?php echo $row['nombre_usuario']; ?></td>
                                        <td class="text-center"><?php echo '********'; // Replace with a placeholder or remove this line if you want to hide the password 
                                                                ?></td>
                                        <td class="text-center"><?php echo $row['email_usuario']; ?></td>
                                        <td class="text-center"><?php echo $row['telefono']; ?></td>
                                        <td class="text-center"><?php echo $row['dpi']; ?></td>
                                        <td class="text-center"><?php echo $row['rol']; ?></td>

                                        <td class="text-center">
                                            <form action="" method="POST" style="display: inline-block;">
                                                <input type="hidden" name="id_del" value="<?php echo $row['id_usuario']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                            </form>
                                            <a href="admin.php?view=update&id=<?php echo $row['id_usuario']; ?>" class="btn btn-sm btn-warning"><i class="fa fa-edit" aria-hidden="true"></i> Modificar</a>
                                        </td>
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
                </div>
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
                                    <a href="./admin.php?view=users&pagina=<?php echo $pagina - 1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>


                            <?php
                            for ($i = 1; $i <= $numeropaginas; $i++) {
                                if ($pagina == $i) {
                                    echo '<li class="active"><a href="./admin.php?view=users&pagina=' . $i . '">' . $i . '</a></li>';
                                } else {
                                    echo '<li><a href="./admin.php?view=users&pagina=' . $i . '">' . $i . '</a></li>';
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
                                    <a href="./admin.php?view=users&pagina=<?php echo $pagina + 1; ?>" aria-label="Previous">
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
?>

    </div>
    <div class="col-sm-7 animated flip">
        <h1 class="text-danger">Lo sentimos esta página es solamente para administradores del COCODE</h1>
        <h3 class="text-info text-center">Inicia sesión como administrador para poder acceder</h3>
    </div>
    <div class="col-sm-1">&nbsp;</div>
    </div>
    </div>
<?php
}
?>