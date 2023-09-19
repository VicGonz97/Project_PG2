<?php



// Function to calculate age based on date of birth
function calcularEdad($fechaNacimiento)
{
    $today = new DateTime();
    $birthdate = new DateTime($fechaNacimiento);
    $age = $birthdate->diff($today)->y;
    return $age;
}

// Verifica si existe una sesión con nombre y tipo de usuario
if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
    require_once './lib/config.php'; // Incluye tu archivo de conexión a la base de datos

    if (isset($_POST['update'])) {
        // Recopila los datos del formulario
        $id_contr = $_POST['id_contr'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $telefono = $_POST['telefono'];
        $dpi = $_POST['dpi'];

        // Calculate age using the provided function
        $edad = calcularEdad($fecha_nacimiento);

        // Asegúrate de definir la función MysqlQuery::Actualizar para realizar la actualización en la base de datos.
        if (MysqlQuery::Actualizar("contribuyente", "nombre='$nombre', apellido='$apellido', fecha_nacimiento='$fecha_nacimiento', edad='$edad', telefono='$telefono', dpi='$dpi'", "id_contr='$id_contr'")) {
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
        // Get the ID of the record to update from the URL
        $id_contr = $_GET['id'];

        // Query to retrieve data of the contribuyente
        $consulta = Mysql::consulta("SELECT * FROM contribuyente WHERE id_contr = '$id_contr'");

        if ($row = mysqli_fetch_assoc($consulta)) {
            // Calcular la edad y almacenarla en el arreglo $row
            $row['edad'] = calcularEdad($row['fecha_nacimiento']);
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
                                            <input type="hidden" name="id_contr" value="<?php echo $row['id_contr']; ?>">
                                            <fieldset>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Nombre</label>
                                                    <div class="col-sm-10">
                                                        <div class='input-group'>
                                                            <input type="text" class="form-control" placeholder="Nombre" required="" pattern="[a-zA-Z ]{1,30}" name="nombre" value="<?php echo $row['nombre']; ?>">
                                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Apellidos</label>
                                                    <div class="col-sm-10">
                                                        <div class='input-group'>
                                                            <input type="text" class="form-control" placeholder="Apellidos" required="" pattern="[a-zA-Z ]{1,30}" name="apellido" value="<?php echo $row['apellido']; ?>">
                                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Fecha de nacimiento</label>
                                                    <div class="col-sm-10">
                                                        <div class='input-group'>
                                                            <input type="date" class="form-control" required="" name="fecha_nacimiento" id="fechaNacimiento" value="<?php echo $row['fecha_nacimiento']; ?>">
                                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Edad</label>
                                                    <div class="col-sm-10">
                                                        <div class='input-group'>
                                                            <?php
                                                            $edad = calcularEdad($row['fecha_nacimiento']);
                                                            ?>
                                                            <input type="text" class="form-control" placeholder="00" readonly id="edad" value="<?php echo $edad; ?>">
                                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Telefono</label>
                                                    <div class="col-sm-10">
                                                        <div class='input-group'>
                                                            <input type="text" class="form-control" placeholder="00000000" required="" name="telefono" value="<?php echo $row['telefono']; ?>" pattern="[0-9]{1,8}" maxlength="8">
                                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">DPI</label>
                                                    <div class="col-sm-10">
                                                        <div class='input-group'>
                                                            <input type="text" class="form-control" placeholder="0000000000000" required="" name="dpi" value="<?php echo $row['dpi']; ?>" pattern="[0-9]{1,13}" maxlength="13">
                                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" class="btn btn-primary" name="update">Actualizar Datos</button>
                                                        <a href="./index.php?view=UpdateContr&id=<?php echo $row['id_contr']; ?>" class="btn btn-danger">Cancelar</a>
                                                        <a href="./index.php?view=ViewContr" class="btn btn-primary">Regresar</a>
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

<script>
    // Capture the date of birth field
    const fechaNacimiento = document.getElementById("fechaNacimiento");

    // Capture the age field
    const edad = document.getElementById("edad");

    // Add an event to calculate age when the date of birth changes
    fechaNacimiento.addEventListener("change", calcularEdad);

    // Function to calculate age
    function calcularEdad() {
        const fechaNacimientoValue = new Date(fechaNacimiento.value);
        const fechaActual = new Date();
        const edadCalculada = fechaActual.getFullYear() - fechaNacimientoValue.getFullYear();

        // Update the age field with the calculated age
        edad.value = edadCalculada;
    }
</script>
