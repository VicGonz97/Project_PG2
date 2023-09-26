<?php
// Comprueba si el usuario está conectado y tiene el tipo de usuario 'admin'
if ($_SESSION['nombre'] != "" && $_SESSION['tipo'] == "admin") {
    // Comprueba si se ha enviado una solicitud de eliminación
    if (isset($_POST['id_del'])) {
        // Obtiene el ID del administrador a eliminar
        $id_admin = MysqlQuery::RequestPost('id_del');
        // Intenta eliminar el registro del administrador
        if (MysqlQuery::Eliminar("administrador", "id_admin='$id_admin'")) {
            echo '
                <div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">USUARIO ELIMINADO</h4>
                    <p class="text-center">
                        El Usuario fue eliminado del sistema con éxito
                    </p>
                </div>
            ';
        } else {
            echo '
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                        No hemos podido eliminar el usuario
                    </p>
                </div>
            ';
        }
    }

    $idA = $_SESSION['id'];
    /* Contar todos los administradores */
    $num_admin = Mysql::consulta("SELECT * FROM administrador WHERE id_admin!='1' AND id_admin!='$idA'");
    $num_total_admin = mysqli_num_rows($num_admin);

    /* Contar todos los usuarios */
    $num_user = Mysql::consulta("SELECT * FROM usuario");
    $num_total_user = mysqli_num_rows($num_user);
?>
<!-- Contenido HTML para la sección de administradores -->
<div class="container">
    <div class="row">
        <div class="col-sm-2">
            <img src="./img/Guatemala.png" alt="Imagen" class="img-responsive animated flipInY">
        </div>
        <div class="col-sm-10">
            <p class="lead text-info"> Bienvenido ! Por politica del COCODE solo deberia de existir un usuario, si hay mas de un usuario porfavor verificar con el miembro del COCODE y puede eliminar el ususario </p>
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

                // Conexión a la base de datos utilizando los parámetros SERVER, USER, PASS y BD.

                    $mysqli = mysqli_connect(SERVER, USER, PASS, BD);
                    mysqli_set_charset($mysqli, "utf8");

                    // Paginación: Determina la página actual a mostrar, el número de registros por página y el inicio de los resultados.
                    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                    $regpagina = 15;
                    $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

                // Consulta SQL para obtener los administradores en función de la página actual y la cantidad de registros por página.
                    $seladmin = mysqli_query($mysqli, "SELECT SQL_CALC_FOUND_ROWS * FROM administrador WHERE id_admin!='1' AND id_admin!='$idA' LIMIT $inicio, $regpagina");
                
                    // Consulta SQL para obtener el número total de registros sin límite de paginación.
                    $totalregistros = mysqli_query($mysqli, "SELECT FOUND_ROWS()");
                    $totalregistros = mysqli_fetch_array($totalregistros, MYSQLI_ASSOC);

                     // Cálculo del número total de páginas en función del número total de registros y registros por página.
                    $numeropaginas = ceil($totalregistros["FOUND_ROWS()"] / $regpagina);
                    
                    // Comprobar si existen registros en la consulta.
                    if (mysqli_num_rows($seladmin) > 0):
                ?>
                <!-- Tabla HTML para mostrar los administradores -->
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Nombre completo</th>
                            <th class="text-center">Nombre de usuario</th>
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
                            while ($row = mysqli_fetch_array($seladmin, MYSQLI_ASSOC)): 
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $ct; ?></td>
                            <td class="text-center"><?php echo $row['nombre_completo']; ?></td>
                            <td class="text-center"><?php echo $row['nombre_admin']; ?></td>
                            <td class="text-center"><?php echo $row['email_admin']; ?></td>
                            <td class="text-center"><?php echo $row['telefono']; ?></td>
                            <td class="text-center"><?php echo $row['dpi']; ?></td>
                            <td class="text-center"><?php echo $row['rol']; ?></td>
                            <td class="text-center">
                                <form action="" method="POST" style="display: inline-block;">
                                    <input type="hidden" name="id_del" value="<?php echo $row['id_admin']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php
                            $ct++;
                            endwhile; 
                        ?>
                    </tbody>
                </table>
                <?php else: ?>
               <!-- Mensaje que se muestra cuando no hay registros -->
                    <h2 class="text-center">No hay registros</h2>
                <?php endif; ?>
            </div>
            <?php if ($numeropaginas >= 1): ?>
            <nav aria-label="Navegación de páginas" class="text-center">
                <ul class="pagination">
                    <?php if ($pagina == 1): ?>
                        <!-- Desactivar el botón de "Anterior" en la primera página -->
                        <li class="disabled">
                            <a aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                           <!-- Enlace a la página anterior -->
                            <a href="./admin.php?view=admin&pagina=<?php echo $pagina-1; ?>" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    
                    <?php
                        for ($i = 1; $i <= $numeropaginas; $i++ ){
                            if ($pagina == $i){
                               // Resaltar la página actual
                                echo '<li class="active"><a href="./admin.php?view=admin&pagina='.$i.'">'.$i.'</a></li>';
                            } else {
                                echo '<li><a href="./admin.php?view=admin&pagina='.$i.'">'.$i.'</a></li>';
                            }
                        }
                    ?>
                    
                    <!-- Desactivar el botón de "Siguiente" en la última página -->
                    <?php if ($pagina == $numeropaginas): ?>
                        <li class="disabled">
                            <a aria-label="Siguiente">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="./admin.php?view=admin&pagina=<?php echo $pagina+1; ?>" aria-label="Siguiente">
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
<!-- Contenido HTML para usuarios no administradores -->
<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <img src="./img/Stop.png" alt="Imagen" class="img-responsive animated slideInDown"/><br>
            <img src="./img/SadTux.png" alt="Imagen" class="img-responsive"/>
            
        </div>
        <div class="col-sm-7 animated flip">
            <h1 class="text-danger">Lo sentimos, esta página es solo para administradores del COCODE</h1>
            <h3 class="text-info text-center">Inicia sesión como administrador para poder acceder</h3>
        </div>
        <div class="col-sm-1">&nbsp;</div>
    </div>
</div>
<?php
}
?>
