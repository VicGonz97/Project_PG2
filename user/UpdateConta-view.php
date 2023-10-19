<?php
// Verifica si existe una sesión con nombre y tipo de usuario
if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
    require_once './lib/config.php'; // Incluye tu archivo de conexión a la base de datos

    if (isset($_POST['update'])) {
        // Recopila los datos del formulario
        $id_contabilidad = $_POST['id_contabilidad'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $dpi = $_POST['dpi'];
        $cantidad = $_POST['cantidad'];
        $fecha_registro = $_POST['fecha_registro'];

        // Asegúrate de definir la función MysqlQuery::Actualizar para realizar la actualización en la base de datos.
        if (MysqlQuery::Actualizar("contabilidad", "nombre='$nombre', apellido='$apellido', dpi='$dpi', cantidad='$cantidad', fecha_registro='$fecha_registro'", "id_contabilidad='$id_contabilidad'")) {
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
            echo '
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
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
        $id_contabilidad = $_GET['id'];

        // Realiza una consulta para obtener los datos del registro COCODE
        $consulta = Mysql::consulta("SELECT * FROM contabilidad WHERE id_contabilidad = '$id_contabilidad'");

        if ($row = mysqli_fetch_assoc($consulta)) {
            // Aquí deberías crear un formulario con los campos prellenados con los datos actuales del registro COCODE.
            // El usuario podrá modificar los campos y enviar el formulario para actualizarlos.
?>

            <!-- Formulario de actualización -->
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title text-center"><strong><i class="fa fa-pencil"></i>&nbsp;&nbsp;&nbsp;Actualizar Datos</strong></h3>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <img src="./img/Guatemala.png" alt="Image" class="img-responsive animated flipInY">
                                    </div>
                                    <div class="col-sm-8">
                                        <form class="form-horizontal" role="form" action="" method="POST">
                                            <input type="hidden" name="id_contabilidad" value="<?php echo $row['id_contabilidad']; ?>">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Nombre</label>
                                                    <div class="col-sm-10">
                                                        <div class='input-group'>
                                                            <input type="text" class="form-control" placeholder="Nombre" required="" pattern="[a-zA-Z ]{1,30}" name="nombre" value="<?php echo $row['nombre']; ?>" readonly>
                                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Apellidos</label>
                                                    <div class="col-sm-10">
                                                        <div class='input-group'>
                                                            <input type="text" class="form-control" placeholder="Apellidos" required="" pattern="[a-zA-Z ]{1,30}" name="apellido" value="<?php echo $row['apellido']; ?>" readonly>
                                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">DPI</label>
                                                    <div class="col-sm-10">
                                                        <div class='input-group'>
                                                            <input type="text" class="form-control" placeholder="0000000000000" required="" name="dpi" value="<?php echo $row['dpi']; ?>" pattern="[0-9]{1,13}" maxlength="13" readonly>
                                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Cantidad</label>
                                                    <div class="col-sm-10">
                                                        <div class='input-group'>
                                                            <input type="text" class="form-control" placeholder="" required="" name="cantidad" value="<?php echo $row['cantidad']; ?>">
                                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Fecha</label>
                                                    <div class="col-sm-10">
                                                        <div class='input-group'>
                                                            <?php
                                                            // Formatear la fecha y hora desde el valor de la base de datos
                                                            $fecha_registro = $row['fecha_registro'];
                                                            $formatted_date = date('d/m/Y', strtotime($fecha_registro));
                                                            $formatted_time = date('h:i a', strtotime($fecha_registro));
                                                            ?>
                                                            <input type="text" class="form-control" placeholder="fecha actual" required="" name="fecha_registro" value="<?php echo $formatted_date . ' ' . $formatted_time; ?>" readonly>
                                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" class="btn btn-primary" name="update">Actualizar Datos</button>
                                                        <a href="./index.php?view=UpdateConta&id=<?php echo $row['id_contabilidad']; ?>" class="btn btn-danger">Cancelar</a>
                                                        <a href="./index.php?view=ViewConta" class="btn btn-primary">Regresar</a>
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
            // Si el registro no se encuentra, puedes mostrar un mensaje de error o redirigir al usuario a la página de la lista de COCODES.
            echo "Registro no encontrado.";
        }
    } else {
        // Si no se proporciona un ID válido en la URL, muestra un mensaje de error o redirige al usuario.
        echo "ID no válido.";
    }
} else {
    // Si no se ha iniciado sesión, muestra un mensaje o redirige al usuario a la página de inicio de sesión.
    echo "Inicia sesión como COCODE para acceder a esta página.";
}
?>