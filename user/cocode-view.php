<?php
// Verifica si existe una sesión con nombre y tipo de usuario
if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
    // Verifica si se han recibido datos del formulario
    if (isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['telefono']) && isset($_POST['dpi']) &&  isset($_POST['cargo'])) {
        // Recopila los datos del formulario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $telefono = $_POST['telefono'];
        $dpi = $_POST['dpi'];
        $cargo = $_POST['cargo'];

        // Asegúrate de definir la función MysqlQuery::Guardar para realizar la inserción en la base de datos.
        if (MysqlQuery::Guardar("cocode", "nombre, apellido, telefono, dpi, cargo", "'$nombre', '$apellido', '$telefono', '$dpi', '$cargo'")) {
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
                                                    <input type="text" class="form-control" placeholder="00000000" required="" name="telefono" pattern="[0-9]{1,8}" maxlength="8">
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
                                                    <input type="text" class="form-control" placeholder="Cargo" required="" pattern="[a-zA-Z ]{1,30}" name="cargo">
                                                    <span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
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