<?php
// Function to calculate age based on date of birth
function calcularEdad($fechaNacimiento) {
    $today = new DateTime();
    $birthdate = new DateTime($fechaNacimiento);
    $age = $birthdate->diff($today)->y;
    return $age;
}

// Verifica si existe una sesión con nombre y tipo de usuario
if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
    // Verifica si se han recibido datos del formulario
    if (isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['fecha_nacimiento']) && isset($_POST['telefono']) && isset($_POST['dpi'])) {
        // Recopila los datos del formulario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $telefono = $_POST['telefono'];
        $dpi = $_POST['dpi'];

        // Calcula la edad a partir de la fecha de nacimiento
        $edad = calcularEdad($fecha_nacimiento);

        // Asegúrate de definir la función MysqlQuery::Guardar para realizar la inserción en la base de datos.
        if (MysqlQuery::Guardar("contribuyente", "nombre, apellido, fecha_nacimiento, edad, telefono, dpi", "'$nombre', '$apellido', '$fecha_nacimiento', '$edad', '$telefono', '$dpi'")) {
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
    <!-- Formulario de registro -->
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center"><strong><i class="fa fa-ticket"></i>&nbsp;&nbsp;&nbsp;Registro</strong></h3>
                    </div>
                    <div class="panel-body">
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
                                        <label class="col-sm-2 control-label">Fecha de nacimiento</label>
                                        <div class="col-sm-10">
                                            <div class='input-group'>
                                                <input type="date" class="form-control" required="" name="fecha_nacimiento" id="fechaNacimiento">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Edad</label>
                                        <div class="col-sm-10">
                                            <div class='input-group'>
                                                <input type="number" class="form-control" placeholder="00" readonly id="edad">
                                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Telefono</label>
                                        <div class="col-sm-10">
                                            <div class='input-group'>
                                                <input type="text" class="form-control" placeholder="00000000" required="" name="telefono" pattern="[0-9]{1,8}" maxlength="8">
                                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">DPI</label>
                                        <div class="col-sm-10">
                                            <div class='input-group'>
                                                <input type="text" class="form-control" placeholder="0000000000000" required="" name="dpi" pattern="[0-9]{1,13}" maxlength="13">
                                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-info">Guardar</button>
                                            <a href="./index.php?view=ViewContr" class="btn btn-danger">Regresar</a>
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

<script>
    // Captura el campo de fecha de nacimiento
    const fechaNacimiento = document.getElementById("fechaNacimiento");

    // Captura el campo de edad
    const edad = document.getElementById("edad");

    // Agrega un evento para calcular la edad al cambiar la fecha de nacimiento
    fechaNacimiento.addEventListener("change", calcularEdad);

    // Función para calcular la edad
    function calcularEdad() {
        const fechaNacimientoValue = new Date(fechaNacimiento.value);
        const fechaActual = new Date();
        const edadCalculada = fechaActual.getFullYear() - fechaNacimientoValue.getFullYear();

        // Actualiza el campo de edad con la edad calculada
        edad.value = edadCalculada;
    }
</script>

