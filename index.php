<?php
session_start();
include './lib/class_mysql.php';
include './lib/config.php';
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>

<head>
    <title>COCODE</title>
    <?php include "./inc/links.php"; ?>
</head>

<body>
    <?php include "./inc/navbar.php"; ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="animated lightSpeedIn">COCODE<small>Panimache 4</small></h1>
                <p class="pull-right text-primary">
                    <strong>
                        <?php include "./inc/timezone.php"; ?>
                    </strong>
                </p>
            </div>
        </div>
    </div>
    </div>

    <?php
    if (isset($_GET['view'])) {
        $content = $_GET['view'];
        $WhiteList = [
            "index", "cocode", "vcocode", "cupdate", "contribuyente", "asistencia", "contabilidad", "reportes", "registro", "configuracion", "AddContr", "ViewContr", "UpdateContr", "RegAsistencia", "SaveAsistencia",
            "ViewAsistencia", "AddConta", "SaveConta", "ViewConta", "EliminarTodo", "UpdateConta", "AddReporte", "ReporteCoco", "ReporteContr", "ReporteAsistencia", "ReporteContabilidad", "RetirarFondos", "HistorialRetiros",
        ];
        if (in_array($content, $WhiteList) && is_file("./user/" . $content . "-view.php")) {
            include "./user/" . $content . "-view.php";
        } else {
    ?>

            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <img src="./img/Guatemala.png" alt="Image" class="img-responsive" /><br>

                    </div>
                    <div class="col-sm-7 text-center">
                        <h1 class="text-danger">Lo sentimos, la opción que ha seleccionado no se encuentra disponible</h1>
                        <h3 class="text-info">Por favor intente nuevamente</h3>
                    </div>
                    <div class="col-sm-1">&nbsp;</div>
                </div>
            </div>
    <?php
        }
    } else {
        include "./user/index-view.php";
    }
    ?>


    <?php include './inc/footer.php'; ?>
</body>

</html>