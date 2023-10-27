<?php
// Verifica si existe una sesión con nombre y tipo de usuario
if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
    require_once './lib/config.php'; // Incluye tu archivo de conexión a la base de datos

    // Si se envió el formulario de actualización
    if (isset($_POST['update'])) {
        // Recopila los datos del formulario
        $id_retiro = $_POST['id_retiro'];
        $cantidad = $_POST['cantidad'];
        $descripcion = $_POST['descripcion'];

        // Asegúrate de definir la función MysqlQuery::Actualizar para realizar la actualización en la base de datos.
        if (MysqlQuery::Actualizar("retiro", "cantidad='$cantidad', descripcion='$descripcion'", "id_retiro='$id_retiro'")) {
            // Mostrar un mensaje de éxito si la actualización fue exitosa
            echo '
                <div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">ACTUALIZACIÓN EXITOSA</h4>
                    <p class="text-center">
                        Datos actualizados exitosamente.
                    </p>
                </div>
            ';
        } else {
            // Mostrar un mensaje de error si la actualización falla
            echo '
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type of="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                        ERROR AL ACTUALIZAR: Por favor, inténtelo nuevamente.
                    </p>
                </div>
            ';
        }
    }

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        // Obtener el ID del registro a actualizar desde la URL
        $id_retiro = $_GET['id'];

        // Realiza una consulta para obtener los datos del registro COCODE
        $consulta = Mysql::consulta("SELECT * FROM retiro WHERE id_retiro = '$id_retiro'");

        if ($row = mysqli_fetch_assoc($consulta)) {
            // Aquí se crea un formulario con los campos prellenados con los datos actuales del registro COCODE.
            // El usuario podrá modificar los campos y enviar el formulario para actualizarlos.
?>
            <div class="container">
                <a href="./index.php?view=ViewTran" style="color: gray;">
                    <span class="glyphicon glyphicon-arrow-left"></span> Regresar
                </a>
            </div>
            <br>
            <!-- Formulario de actualización -->
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title text-center"><strong><i class="fa fa-pencil"></i>&nbsp;&nbsp;&nbsp;Actualizar Datos</strong></h3>
                            </div>
                            <br>
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <img src="./img/Guatemala.png" alt="Image" class="img-responsive animated flipInY">
                                    </div>
                                    <div class="col-sm-8">
                                        <form class="form-horizontal" role="form" action="" method="POST">
                                            <input type="hidden" name="id_retiro" value="<?php echo $row['id_retiro']; ?>">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Cantidad</label>
                                                    <div class="col-sm-10">
                                                        <div class='input-group'>
                                                            <input type="text" class="form-control" placeholder="" required="" name="cantidad" value="<?php echo $row['cantidad']; ?>">
                                                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Descripcion</label>
                                                    <div class="col-sm-10">
                                                        <div class='input-group'>
                                                            <input type="text" class="form-control" placeholder="Descripcion" required="" pattern=".{1,50}" name="descripcion" value="<?php echo $row['descripcion']; ?>">
                                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Fecha Retiro </label>
                                                    <div class="col-sm-10">
                                                        <div class='input-group'>
                                                            <?php
                                                            // Formatear la fecha y hora desde el valor de la base de datos
                                                            $fecha_retiro = $row['fecha_retiro'];
                                                            $formatted_date = date('d/m/Y', strtotime($fecha_retiro));
                                                            $formatted_time = date('h:i a', strtotime($fecha_retiro));
                                                            ?>
                                                            <input type="text" class="form-control" placeholder="fecha actual" required="" name="fecha_retiro" value="<?php echo $formatted_date . ' ' . $formatted_time; ?>" readonly>
                                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" class="btn btn-success" name="update">Actualizar</button>
                                                        <a href="./index.php?view=UpdateTran&id=<?php echo $row['id_retiro']; ?>" class="btn btn-danger">Cancelar</a>
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
            
<?php
        } else {
            // Si el registro no se encuentra, muestra un mensaje de error o redirige al usuario a la página de la lista de COCODES.
            echo "Registro no encontrado.";
        }
    } else {
        // Si no se proporciona un ID válido en the URL, muestra un mensaje de error o redirige al usuario.
        echo "ID no válido.";
    }
} else {
    // Si no se ha iniciado sesión, muestra un mensaje o redirige al usuario a la página de inicio de sesión.
    echo "Inicia sesión como COCODE para acceder a esta página.";
}
?>
<br> <br> <br> <br> <br>