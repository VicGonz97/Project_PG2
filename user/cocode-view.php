<?php
// Verifica si existe una sesión con nombre y tipo de usuario
if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
    // Verifica si se han recibido datos del formulario
    if (isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['telefono']) && isset($_POST['dpi']) && isset($_POST['cargo'])) {
        // Recopila los datos del formulario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $telefono = $_POST['telefono'];
        $dpi = $_POST['dpi'];
        $cargo = $_POST['cargo'];
        $estado = $_POST['estado'];

        // Realiza una consulta para verificar si ya existe un registro con el mismo DPI
        $mysqli = mysqli_connect(SERVER, USER, PASS, BD);
        $consulta = mysqli_query($mysqli, "SELECT * FROM cocode WHERE dpi = '$dpi'");

        if (mysqli_num_rows($consulta) == 0) {
            // No se encontró un registro con el mismo DPI, por lo que es seguro insertar el nuevo registro.
            if (MysqlQuery::Guardar("cocode", "nombre, apellido, telefono, dpi, cargo, estado", "'$nombre', '$apellido', '$telefono', '$dpi', '$cargo', '$estado'")) {
                // Mostrar un mensaje de éxito si el registro fue exitoso
                echo '
                    <div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="text-center">REGISTRO EXITOSO</h4>
                        <p class="text-center">
                            Datos ingresados exitosamente.
                        </p>
                    </div>
                ';
            } else {
                // Mostrar un mensaje de error si el registro falla
                echo '
                    <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                        <p class="text-center">
                            ERROR AL REGISTRARSE: Por favor, inténtelo nuevamente.
                        </p>
                    </div>
                ';
            }
        } else {
            // Ya existe un registro con el mismo DPI, muestra un mensaje de error.
            echo '
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">DPI DUPLICADO</h4>
                    <p class="text-center">
                        El número de DPI ya existe en la base de datos.
                    </p>
                </div>
            ';
        }
        // Cierra la conexión a la base de datos
        mysqli_close($mysqli);
    }
?>
    <div class="container">
        <a href="./index.php?view=vcocode" style="color: gray;">
            <span class="glyphicon glyphicon-arrow-left"></span> Regresar
        </a>
    </div>
    <br>
    <!-- Formulario de registro -->
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center"><strong><i class="fa fa-ticket"></i>&nbsp;&nbsp;&nbsp;Registro</strong></h3>
                    </div>
                    <br>
                    <div class="container">
                        <div class "row">
                            <div class="col-sm-2">
                                <img src="./img/Guatemala.png" alt="Image" class="img-responsive animated flipInY">
                            </div>
                            <div class="col-sm-8">
                                <form class="form-horizontal" role="form" action="" method="POST">
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Nombre</label>
                                            <div class="col-sm-10">
                                                <div class='input-group'>
                                                    <input type="text" class="form-control" placeholder="Nombre" required="" pattern="[a-zA-Z ]{1,30}" name="nombre">
                                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Apellidos</label>
                                            <div class="col-sm-10">
                                                <div class='input-group'>
                                                    <input type="text" class="form-control" placeholder="Apellidos" required="" pattern="[a-zA-Z ]{1,30}" name="apellido">
                                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Telefono</label>
                                            <div class="col-sm-10">
                                                <div class='input-group'>
                                                    <input type="text" class="form-control" placeholder="00000000" required="" name="telefono" pattern="[0-9]{1,8}" maxlength="">
                                                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">DPI</label>
                                            <div class="col-sm-10">
                                                <div class='input-group'>
                                                    <input type="text" class="form-control" placeholder="0000000000000" required="" name="dpi" pattern="[0-9]{1,13}" maxlength="13">
                                                    <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Cargo</label>
                                            <div class="col-sm-10">
                                                <div class='input-group'>
                                                    <select class="form-control" name="cargo" required>
                                                        <option value="">Selecciona un cargo</option>
                                                        <option value="Presidente">Presidente</option>
                                                        <option value="VicePresidente">VicePresidente</option>
                                                        <option value="Secretario">Secretario</option>
                                                        <option value="Tesorero">Tesorero</option>
                                                        <option value="Vocal 1">Vocal 1</option>
                                                        <option value="Vocal 2">Vocal 2</option>
                                                        <option value="Vocal 3">Vocal 3</option>
                                                        <option value="Vocal 4">Vocal 4</option>
                                                        <option value="Vocal 5">Vocal 5</option>
                                                        <option value="Vocal 6">Vocal 6</option>
                                                        <option value="Vocal 7">Vocal 7</option>
                                                        <option value="Vocal 8">Vocal 8</option>

                                                    </select>
                                                    <span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Estado</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <select class="form-control" name="estado">
                                                        <option value="activo">Activo <i class="fa fa-check"></i></option>
                                                        <option value="retirado">Retirado <i class="fa fa-times"></i></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10 text-center">
                                                <button type="submit" class="btn btn-success">Guardar</button>
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
?>

<?php
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#fechainput").datepicker();
    });
</script>