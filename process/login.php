<?php 
    // Obtener el nombre de usuario y la contraseña del formulario
    $nombre = MysqlQuery::RequestPost("nombre_login");
    $clave = md5(MysqlQuery::RequestPost("contrasena_login"));
    $radio = MysqlQuery::RequestPost('optionsRadios');

    // Verificar si se proporcionaron datos válidos
    if ($nombre != "" && $clave != "" && $radio != "") {
        // Verificar el tipo de usuario (admin o usuario)
        if ($radio == "admin") {
            // Consultar la base de datos para el administrador
            $sql = Mysql::consulta("SELECT * FROM administrador WHERE nombre_admin= '$nombre' AND clave='$clave'");
            if (mysqli_num_rows($sql) >= 1) {
                // Si se encontró un administrador, establecer variables de sesión
                $reg = mysqli_fetch_array($sql, MYSQLI_ASSOC);
                $_SESSION['nombre'] = $reg['nombre_admin'];
                $_SESSION['id'] = $reg['id_admin'];
                $_SESSION['nombre_completo'] = $reg['nombre_completo'];
                $_SESSION['email'] = $reg['email_admin'];
                $_SESSION['clave'] = $clave;
                $_SESSION['tipo'] = "admin";
            } else {
                // Mostrar un mensaje de error si el inicio de sesión falla
                echo '
                    <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                        <p class="text-center">
                            Nombre de usuario o contraseña incorrectos
                        </p>
                    </div>
                '; 
            }
        } elseif ($radio == "user") {
            // Consultar la base de datos para el usuario
            $sql = Mysql::consulta("SELECT * FROM usuario WHERE nombre_usuario= '$nombre' AND clave='$clave'");
            if (mysqli_num_rows($sql) >= 1) {
                // Si se encontró un usuario, establecer variables de sesión
                $reg = mysqli_fetch_array($sql, MYSQLI_ASSOC);
                $_SESSION['nombre'] = $reg['nombre_usuario'];
                $_SESSION['nombre_completo'] = $reg['nombre_completo'];
                $_SESSION['email'] = $reg['email_usuario'];
                $_SESSION['telefono'] = $reg['telefono'];
                $_SESSION['dpi'] = $reg['dpi'];
                $_SESSION['rol'] = $reg['rol'];
                $_SESSION['clave'] = $clave;
                $_SESSION['tipo'] = "user";
            } else {
                // Mostrar un mensaje de error si el inicio de sesión falla
                echo '
                    <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                        <p class="text-center">
                            Nombre de usuario o contraseña incorrectos
                        </p>
                    </div>
                '; 
            }
        } else {
            // Mostrar un mensaje de error si no se seleccionó un usuario válido
            echo '
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                    <p class="text-center">
                        No has seleccionado un usuario valido
                    </p>
                </div>
            ';
        }
    } else {
        // Mostrar un mensaje de error si se dejan campos vacíos en el formulario
        echo '
            <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="text-center">OCURRIÓ UN ERROR</h4>
                <p class="text-center">
                    No puedes dejar ningún campo vacío
                </p>
            </div>
        ';
    }
?>