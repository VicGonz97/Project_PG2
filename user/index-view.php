<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-image: url(<?php echo obtenerURLFondo(); ?>);
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center center;
            /* Centra la imagen en el viewport */
        }

        .contenido {
            padding: 10px;
            border-radius: 10px;
            margin: 200px auto;
            /* Centra el contenido horizontalmente */
            color: #fff;
            /* Cambiado a texto blanco para que sea legible en fondo oscuro */
            width: fit-content;
            background-color: rgba(4, 13, 18, 0.8);
            /* Fondo semitransparente */
        }
    </style>
</head>

<body>
    <div class="contenido">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="animated lightSpeedIn">Bienvenido! <small>COCODE siempre trabajando para el pueblo.</small></h1>
                <p class="pull-right text-primary"> </p>
            </div>
        </div>
    </div>

</body>

</html>


<?php
function obtenerURLFondo()
{
    // Aquí puedes implementar la lógica para obtener la URL de la imagen de fondo.
    // Puede ser a través de una base de datos, una API, o simplemente un archivo estático.
    // En este ejemplo, se retorna una URL de imagen estática.
    return "img/fondo1.jpg";
}
?>
<br><br>