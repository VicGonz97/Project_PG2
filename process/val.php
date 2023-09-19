<?php
// Incluir archivos necesarios
include '../lib/class_mysql.php';  // Incluye la clase de MySQL
include '../lib/config.php';       // Incluye la configuración (si es necesario)

// Consultar la base de datos para verificar si el usuario ya existe
$sql = Mysql::consulta("SELECT nombre_usuario FROM usuario WHERE nombre_usuario='" . MysqlQuery::RequestGet('id') . "'");

// Verificar si se encontró algún registro en la consulta
if (mysqli_num_rows($sql) > 0) {
    // Si se encontraron registros, mostrar una marca de error y un mensaje
    echo '<span class="glyphicon glyphicon-remove form-control-feedback"></span>';
    echo '<label class="control-label" for="inputSuccess2" style="margin-top:10px;">El usuario ya existe, por favor elige otro nombre de usuario</label>';
} else {
    // Si no se encontraron registros, mostrar una marca de éxito
    echo '<span class="glyphicon glyphicon-ok form-control-feedback"></span>';
}
?>
