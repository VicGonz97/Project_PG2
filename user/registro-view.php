<?php
if (isset($_POST['user_reg']) && isset($_POST['clave_reg']) && isset($_POST['nom_complete_reg'])) {
    $nombre_reg = MysqlQuery::RequestPost('nom_complete_reg');
    $user_reg = MysqlQuery::RequestPost('user_reg');
    $clave_reg = md5(MysqlQuery::RequestPost('clave_reg'));
    $clave_reg2 = MysqlQuery::RequestPost('clave_reg');
    $email_reg = MysqlQuery::RequestPost('email_reg');
    $telefono_reg = MysqlQuery::RequestPost('telefono_reg');
    $dpi_reg = MysqlQuery::RequestPost('dpi_reg');
    $rol_reg = MysqlQuery::RequestPost('rol_reg');


    $asunto = "Registro de cuenta en COCODE";
    $cabecera = "From: COCODE Guatemala<cocode2023@gmail.com>";
    $mensaje_mail = "Hola " . $nombre_reg . ", Gracias por registrarte en COCODE Panimache4. Los datos de cuenta son los siguientes:\nNombre Completo: " . $nombre_reg . "\nNombre de usuario: " . $user_reg . "\nClave: " . $clave_reg2 . "\nEmail: " . $email_reg . "\n Página principal: http://www.cocode2023.com/index.php";


    if (MysqlQuery::Guardar("usuario", "nombre_completo, nombre_usuario, clave, email_usuario, telefono, dpi, rol", "'$nombre_reg', '$user_reg', '$clave_reg', '$email_reg', '$telefono_reg', '$dpi_reg', '$rol_reg'")) {

        /*----------  Enviar correo con los datos de la cuenta 
                mail($email_reg, $asunto, $mensaje_mail, $cabecera);
            ----------*/

        echo '
                <div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">REGISTRO EXITOSO</h4>
                    <p class="text-center">
                        Cuenta creada exitosamente, ahora puedes iniciar sesión, ya eres usuario del COCODE.
                    </p>
                </div>
            ';
    } else {
        echo '
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                        ERROR AL REGISTRARSE: Por favor intente nuevamente.
                    </p>
                </div>
            ';
    }
}
?>
<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <img src="./img/Guatemala.png" alt="Image" class="img-responsive" /><br>
        </div>
        <div class="col-sm-4 text-center hidden-xs">
            <h2 class="text-primary">¡Gracias! Por ser parte del COCODE</h2>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-success">
                <div class="panel-heading text-center"><strong>Para poder registrarte debes llenar todos los campos de este formulario</strong></div>
                <div class="panel-body">
                    <form role="form" action="" method="POST">
                        <div class="form-group">
                            <label><i class="fa fa-male"></i>&nbsp;Nombre completo</label>
                            <input type="text" class="form-control" name="nom_complete_reg" placeholder="Nombre completo" required="" pattern="[a-zA-Z ]{1,40}" title="Nombre Apellido" maxlength="34">
                        </div>
                        <div class="form-group has-success has-feedback">
                            <label class="control-label"><i class="fa fa-user"></i>&nbsp;Nombre de usuario</label>
                            <input type="text" id="input_user" class="form-control" name="user_reg" placeholder="Nombre de usuario" required="" pattern="[a-zA-Z0-9]{6,15}" title="minimo 6 - máximo 15 caracteres" maxlength="34">
                            <!-- Note: I modified maxlength attribute to match the pattern -->
                            <div id="com_form"></div>
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-key"></i>&nbsp;Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="clave_reg" id="password" placeholder="Contraseña" required="" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$" title="La contraseña debe tener al menos 8 caracteres de longitud, incluir al menos una letra mayúscula, una letra minúscula y un número" maxlength="34">
                                <span class="input-group-addon" id="showPasswordToggle">
                                    <i class="fa fa-eye" id="eyeIcon"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-envelope"></i>&nbsp;Correo electronico</label>
                            <input type="email" class="form-control" name="email_reg" placeholder="Escriba su email" required="">
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-phone"></i>&nbsp;Teléfono</label>
                            <input type="tel" class="form-control" name="telefono_reg" placeholder="8 Digitos" required="" pattern="[0-9]{8}" maxlength="8">
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-id-card"></i>&nbsp;DPI</label>
                            <input type="text" class="form-control" name="dpi_reg" placeholder="13 Digitos" required="" pattern="[0-9]{13}" maxlength="13">
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-briefcase"></i>&nbsp;Cargo</label>
                            <input type="text" class="form-control" name="rol_reg" placeholder="Cargo" required="">
                        </div>
                        <button type="submit" class="btn btn-danger">Crear cuenta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var passwordField = document.getElementById("password");
    var eyeIcon = document.getElementById("eyeIcon");

    document.getElementById("showPasswordToggle").addEventListener("click", function() {
        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.classList.add("show-password");
        } else {
            passwordField.type = "password";
            eyeIcon.classList.remove("show-password");
        }
    });
</script>


<script>
    $(document).ready(function() {
        $("#input_user").keyup(function() {
            $.ajax({
                url: "./process/val.php?id=" + $(this).val(),
                success: function(data) {
                    $("#com_form").html(data);
                }
            });
        });
    });
</script>