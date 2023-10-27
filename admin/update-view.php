<?php
// Verifica si existe una sesión con nombre y tipo de usuario
if (isset($_SESSION['nombre']) && isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'admin') {
    require_once './lib/config.php'; // Incluye tu archivo de conexión a la base de datos

    if (isset($_POST['update'])) {
        $id_usuario = $_POST['id_usuario'];
        $nombre_completo = $_POST['nombre_completo'];
        $nombre_usuario = $_POST['nombre_usuario'];
        $email_usuario = $_POST['email_usuario'];
        $telefono = $_POST['telefono'];
        $dpi = $_POST['dpi'];
        $rol = $_POST['rol'];

        // Verifica si se proporcionó una nueva contraseña
        if (!empty($_POST['clave'])) {
            $clave = $_POST['clave'];
            // Hashea la nueva contraseña utilizando MD5
            $clave_md5 = md5($clave);
            $update_clave = "clave='$clave_md5', ";
        } else {
            // No se proporcionó una nueva contraseña, así que no la actualizamos
            $update_clave = "";
        }

        // Actualiza los datos en la base de datos
        if (MysqlQuery::Actualizar(
            "usuario",
            "nombre_completo='$nombre_completo', nombre_usuario='$nombre_usuario', $update_clave email_usuario='$email_usuario', telefono='$telefono', dpi='$dpi', rol='$rol'",
            "id_usuario='$id_usuario'"
        )) {
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
                    <button type="button" class close data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                        ERROR AL ACTUALIZAR: Por favor, inténtelo nuevamente.
                    </p>
                </div>
            ';
        }
    }


    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        // Obtener el ID del usuario a actualizar desde la URL
        $id_usuario = $_GET['id'];

        // Realiza una consulta para obtener los datos del usuario
        $consulta = Mysql::consulta("SELECT * FROM usuario WHERE id_usuario = '$id_usuario'");

        if ($row = mysqli_fetch_assoc($consulta)) {
            // Muestra un formulario con los campos prellenados con los datos actuales del usuario.
            echo '
                <div class="container">
                    <a href="admin.php?view=users" style="color: gray;">
                        <span class="glyphicon glyphicon-arrow-left"></span> Regresar
                    </a>
                </div>
                <br>

                <div class="container">
        <div class="row">
            <div class="col-sm-10">
                <p class="lead text-info">Alerta! Cualquier actulizacion de datos es obligatorio cambiar la contraseña para mayor seguridad del ususario.</p>
            </div>
        </div>
    </div>

    <br>

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
                                                <input type="hidden" name="id_usuario" value="' . $row['id_usuario'] . '">
                                                <fieldset>
                                                <div class="form-group">
                                                <label class="col-sm-2 control-label">Nombre Completo</label>
                                                <div class="col-sm-10">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" placeholder="" required name="nombre_completo" value="' . $row['nombre_completo'] . '">
                                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                            <label class="col-sm-2 control-label">Nombre Usuario</label>
                                            <div class="col-sm-10">
                                                <div class="input-group"> <!-- Corrección: Agregado "class" sin espacio -->
                                                    <input type="text" class="form-control" placeholder="" required name="nombre_usuario" value="' . $row['nombre_usuario'] . '">
                                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                            
                                            <div class="form-group">
                                            <label class="col-sm-2 control-label">Contraseña</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <input type="password" class="form-control" placeholder="" required name="clave" value="' . $row['clave'] . '" id="password-field">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-lock"></i>
                                                        
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        <label class="col-sm-2 control-label">Correo electrónico</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="" required name="email_usuario" value="' . $row['email_usuario'] . '">
                                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                    <label class="col-sm-2 control-label">Teléfono</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="" required name="telefono" value="' . $row['telefono'] . '" maxlength="8">
                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
    <label class="col-sm-2 control-label">DPI</label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="" required name="dpi" value="' . $row['dpi'] . '" maxlength="14">
            <span class="input-group-addon"><i class="fa fa-address-card"></i></span>
        </div>
    </div>
</div>
                            
                            <div class="form-group">
                            <label class="col-sm-2 control-label">Cargo</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="" required name="rol" value="' . $row['rol'] . '">
                                    <span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
                                </div>
                            </div>
                        </div>
                        
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-success" name="update">Actualizar</button>
                                        <a href="admin.php?view=update&id=' . $row['id_usuario'] . '" class="btn btn-danger">Cancelar</a>
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
';
        } else {
            // Si el usuario no se encuentra, muestra un mensaje de error
            echo "Usuario no encontrado.";
        }
    } else {
        // Si no se ha iniciado sesión como administrador, muestra un mensaje
        echo "Inicia sesión como administrador para acceder a esta página.";
    }
}
