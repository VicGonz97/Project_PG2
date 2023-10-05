<?php
if (isset($_POST['nombre_login']) && isset($_POST['contrasena_login'])) {
    include "./process/login.php";

}
?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><i class=" "></i>&nbsp;&nbsp;COCODE</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <?php if (isset($_SESSION['tipo']) && isset($_SESSION['nombre'])) : ?>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-user"></span> &nbsp; <?php echo $_SESSION['nombre']; ?><b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- COCODE -->
                            <?php if ($_SESSION['tipo'] == "user") : ?>

                                <li>
                                    <a href="./index.php?view=configuracion"><i class="fa fa-cogs"></i>&nbsp;&nbsp;Configuracion</a>
                                </li>
                            <?php endif; ?>

                            <!-- admins -->
                            <?php if ($_SESSION['tipo'] == "admin") : ?>

                                <li>
                                    <a href="admin.php?view=admin"><span class="glyphicon glyphicon-user"></span> &nbsp;Administrar Usuarios</a>
                                </li>
                                <li>
                                    <a href="admin.php?view=config"><i class="fa fa-cogs"></i> &nbsp; Configuracion</a>
                                </li>
                                <li>
                                    <a href="./index.php?view=registro"><i class="fa fa-users"></i>&nbsp;&nbsp;Registro</a>
                                </li>
                            <?php endif; ?>
                            <li class="divider"></li>
                            <li><a href="./process/logout.php"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Cerrar sesión</a></li>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="./index.php?view=vcocode"><span class=""></span>&nbsp;&nbsp;Miembros del COCODE</a>
                </li>
                <li>
                    <a href="./index.php?view=ViewContr"><span class=""></span>&nbsp;&nbsp;Contribuyentes</a>
                </li>
                <li>
                    <a href="./index.php?view=RegAsistencia"><span class=""></span>&nbsp;&nbsp;Asistencia</a>
                </li>
                <li>
                    <a href="./index.php?view=AddConta"><span class=""></span>&nbsp;&nbsp;Contabilidad</a>
                </li>
                <li>
                    <a href="./index.php?view=AddReporte"><span class=""></span>&nbsp;&nbsp;Reportes</a>
                    
                </li>
                <?php if (!isset($_SESSION['tipo']) && !isset($_SESSION['nombre'])) : ?>

                    <li>
                        <a href="#" data-toggle="modal" data-target="#modalLog"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Iniciar sesión</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Modal de inicio de sesión -->

<div class="modal fade" tabindex="-1" role="dialog" id="modalLog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <div class="col-sm-10">
                    <img src="./img/Guatemala.png" alt="Image" class="img-responsive ">
                </div>
                <h4 class="modal-title text-center text-primary" id="myModalLabel">Bienvenido COCODE</h4>

            </div>

            <form action="" method="POST" style="margin: 20px;">
                <div class="form-group">
                    <label><span class="glyphicon glyphicon-user"></span>&nbsp;Nombre Usuario</label>
                    <input type="text" class="form-control" name="nombre_login" placeholder="Escribe tu nombre" required="" />
                </div>
                <div class="form-group">
                    <label><span class="glyphicon glyphicon-lock"></span>&nbsp;Contraseña</label>
                    <input type="password" class="form-control" name="contrasena_login" placeholder="Escribe tu contraseña" required="" />
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Iniciar sesión</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>