<?php if (!$_SESSION['nombre'] == "" && !$_SESSION['tipo'] == "") {

  /*Script para eliminar cuenta*/
  if (isset($_POST['usuario_delete']) && isset($_POST['clave_delete'])) {
    $usuario_delete = MysqlQuery::RequestPost('usuario_delete');
    $clave_delete = md5(MysqlQuery::RequestPost('clave_delete'));

    $sql = Mysql::consulta("SELECT * FROM usuario WHERE nombre_usuario= '$usuario_delete' AND clave='$clave_delete'");

    if (mysqli_num_rows($sql) >= 1) {
      MysqlQuery::Eliminar("usuario", "nombre_usuario='$usuario_delete' and clave='$clave_delete'");
      echo '<script type="text/javascript"> window.location="eliminar.php"; </script>';
    } else {
      echo '
                <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="text-center">ERROR AL ELIMINAR CUENTA</h4>
                    <p class="text-center">
                        Porfavor verificar usuario y contraseña!
                    </p>
                </div>
            ';
    }
  }


  /*Script para actualizar datos de cuenta*/

  // Verifica si se enviaron los datos del formulario de actualización de cuenta
  if (isset($_POST['name_complete_update']) && isset($_POST['old_user_update']) && isset($_POST['new_user_update']) && isset($_POST['old_pass_update']) && isset($_POST['new_pass_update']) && isset($_POST['email_update']) && isset($_POST['telefono1_update']) && isset($_POST['dpi1_update']) && isset($_POST['rol1_update'])) {
    $nombreCompleto = MysqlQuery::RequestPost('name_complete_update');
    $oldUsuario = MysqlQuery::RequestPost('old_user_update');
    $newUsuario = MysqlQuery::RequestPost('new_user_update');
    $oldClave = md5(MysqlQuery::RequestPost('old_pass_update'));
    $newClave = md5(MysqlQuery::RequestPost('new_pass_update'));
    $email = MysqlQuery::RequestPost('email_update');
    $telefono = MysqlQuery::RequestPost('telefono1_update');
    $dpi = MysqlQuery::RequestPost('dpi1_update');
    $rol = MysqlQuery::RequestPost('rol1_update');

    // Consulta si existe el usuario antiguo con la contraseña antigua
    $sql = Mysql::consulta("SELECT * FROM usuario WHERE nombre_usuario = '$oldUsuario' AND clave = '$oldClave'");


    if (mysqli_num_rows($sql) >= 1) {
      // Actualiza los datos del usuario
      MysqlQuery::Actualizar("usuario", "nombre_completo = '$nombreCompleto', nombre_usuario = '$newUsuario', email_usuario = '$email', telefono = '$telefono', dpi = '$dpi', rol = '$rol', clave = '$newClave'", "nombre_usuario = '$oldUsuario' AND clave = '$oldClave'");

      // Actualiza las sesiones
      $_SESSION['nombre'] = $newUsuario;
      $_SESSION['clave'] = $newClave;

      // Muestra un mensaje de éxito
      echo '
          <div class="alert alert-info alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="text-center">CUENTA ACTUALIZADA</h4>
              <p class="text-center">
                  ¡Tus datos han sido actualizados correctamente!
              </p>
          </div>
      ';
    } else {
      // Muestra un mensaje de error si los datos no son válidos
      echo '
          <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="text-center">OCURRIO UN ERROR</h4>
              <p class="text-center">
                  Asegúrese de que los datos ingresados son válidos. Por favor intente nuevamente.
              </p>
          </div>
      ';
    }
  }
?>
  <div class="container">
    <div class="row well">
      <div class="col-sm-3">
        <img src="img/Guatemala.png" alt="Image" class="img-responsive">
      </div>
      <div class="col-sm-9 lead">
        <h2 class="text-info">Bienvenido a la configuración de cuenta </h2>
        <p>Puedes <strong>actualizar los datos</strong> de tu cuenta ó puedes <strong>eliminar tu cuenta</strong> permanentemente si lo desea!</p>
      </div>
    </div><!--Fin row well-->

    <div class="row">
      <div class="col-sm-8">
        <div class="panel panel-info">
          <div class="panel-heading text-center"><i class="fa fa-retweet"></i>&nbsp;&nbsp;<strong>Actualizar datos de cuenta</strong></div>
          <div class="panel-body">
            <form action="" method="post" role="form">
              <div class="form-group">
                <label class="text-primary"><i class="fa fa-male"></i>&nbsp;&nbsp;Nombre completo</label>
                <input type="text" class="form-control" placeholder="Nombre completo" name="name_complete_update" required="" pattern="[a-zA-Z ]{1,40}" title="Nombre Apellido" maxlength="40">
              </div>
              <div class="form-group">
                <label class="text-danger"><i class="fa fa-user"></i>&nbsp;&nbsp;Nombre de usuario actual</label>
                <input type="text" class="form-control" placeholder="Nombre de usuario actual" name="old_user_update" required="" pattern="[a-zA-Z0-9 ]{1,30}" title="Ejemplo7" maxlength="20">
              </div>
              <div class="form-group  has-success has-feedback">
                <label class="text-primary"><i class="fa fa-user"></i>&nbsp;&nbsp;Nombre de usuario nuevo</label>
                <input type="text" class="form-control" id="input_user" placeholder="Nombre de usuario nuevo" name="new_user_update" required="" pattern="[a-zA-Z0-9 ]{1,30}" title="Ejemplo7" maxlength="20">
                <div id="com_form"></div>
              </div>
              <div class="form-group">
                <label class="text-danger"><i class="fa fa-key"></i>&nbsp;&nbsp;Contraseña actual</label>
                <input type="password" class="form-control" placeholder="Contraseña actual" name="old_pass_update" required="">
              </div>
              <div class="form-group">
                <label class="text-primary"><i class="fa fa-unlock-alt"></i>&nbsp;&nbsp;Contraseña nueva</label>
                <input type="password" class="form-control" placeholder="Nueva Contraseña" name="new_pass_update" required="">
              </div>
              <div class="form-group">
                <label class="text-primary"><i class="fa fa-envelope-o"></i>&nbsp;&nbsp;Correo Electronico</label>
                <input type="email" class="form-control" placeholder="Escriba su email" name="email_update" required="">
              </div>
              <div class="form-group">
                <label class="text-primary"><i class="fa fa-phone"></i>&nbsp;&nbsp;Telefono</label>
                <input type="text" class="form-control" placeholder="8 Digitos" name="telefono1_update" required="">
              </div>
              <div class="form-group">
                <label class="text-primary"><i class="fa fa-id-card"></i>&nbsp;&nbsp;DPI</label>
                <input type="text" class="form-control" placeholder="13 Digitos" name="dpi1_update" required="">
              </div>
              <div class="form-group">
                <label class="text-primary"><i class="fa fa-briefcase"></i>&nbsp;&nbsp;Cargo</label>
                <input type="text" class="form-control" placeholder="Rol" name="rol1_update" required="">
              </div>
              <div class="text-center well">
                <button type="submit" class="btn btn-info">Actualizar datos</button>
            </form>
          </div>
        </div>
      </div>
    </div><!--Fin col 8-->
    <div class="col-sm-4 text-center well">
      <br><br><br><br><br><br><br><br>
      <button class="btn btn-danger" data-toggle="modal" data-target=".bs-example-modal-sm">Eliminar cuenta</button>
      <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title text-center text-danger" id="myModalLabel">¿Deseas eliminar tu cuenta?</h4>
            </div>
            <form action="" method="post" role="form" style="padding:20px;">
              <p class="text-warning">Si estas seguro que deseas eliminar tu cuenta por favor introduce tu nombre de usuario y contraseña</p>
              <div class="input-group input-group-sm">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                <input type="text" class="form-control" name="usuario_delete" placeholder="Nombre de usuario" required="">
              </div><br>
              <div class="input-group input-group-sm">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                <input type="password" class="form-control" name="clave_delete" placeholder="Contraseña" required="">
              </div><br>

              <div class="modal-footer">
                <button type="submit" class="btn btn-danger btn-sm">Eliminar cuenta</button>
                <button type="button" class="btn btn-success btn-sm" data-dismiss="modal">Cancelar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <br><br><br><br><br><br><br>
    </div>
  </div>
  </div>
<?php
} else {
?>
  <div class="container">
    <div class="row">
      <div class="col-sm-4">
        <img src="img/Stop.png" alt="Image" class="img-responsive animated slideInDown" /><br>
        <img src="img/SadTux.png" alt="Image" class="img-responsive" />

      </div>
      <div class="col-sm-7 animated flip">
        <h1 class="text-danger">Lo sentimos esta página es solamente para usuarios registrados en COCODE</h1>
        <h3 class="text-info text-center">Inicia sesión para poder acceder</h3>
      </div>
      <div class="col-sm-1">&nbsp;</div>
    </div>
  </div>
<?php
}
?>
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