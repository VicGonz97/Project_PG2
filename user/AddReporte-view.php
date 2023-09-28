<?php
// Verifica si existe una sesión con nombre y tipo de usuario
if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
?>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" method="POST" action="procesar_registro.php">
                    <fieldset>
                        <legend>Ver y descargar informes, descargue su informe detallado en formato PDF</legend>
                        <!-- Agregar tus campos de formulario aquí -->
                        <div class="form-group">
                            <label for="opcion" class="col-sm-2 control-label">Opciones:</label>
                            <div class="col-sm-10">
                                <select id="opcion" name="opcion" class="form-control" onchange="redirectToPage(this)">
                                    <option value="registrar_nuevo">Selecione una opcion</option>
                                    <option value="miembros_cocode">Miembros del COCODE</option>
                                    <option value="contribuyentes">Contribuyentes</option>
                                    <option value="asistencia">Asistencia</option>
                                    <option value="contabilidad">Contabilidad</option>
                                    <!-- Agrega más opciones según tus necesidades -->
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </form>
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
    function redirectToPage(selectElement) {
        var selectedOption = selectElement.value;
        if (selectedOption === "miembros_cocode") {
            window.location.href = "./index.php?view=ReporteCoco";
        } else if (selectedOption === "contribuyentes") {
            window.location.href = "./index.php?view=ReporteContr";
        } else if (selectedOption === "asistencia") {
            window.location.href = "./index.php?view=ReporteAsistencia";
        } else if (selectedOption === "contabilidad") {
            window.location.href = "./index.php?view=ReporteContabilidad";
        }
        // Agrega más condiciones para otras opciones si es necesario
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#fechainput").datepicker();
    });
</script>