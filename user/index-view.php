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
        }
        
        .contenido {
            padding: 20px;
            border-radius: 10px;
            margin: 20px; 
        }
    </style>
</head>
<body>
    <div class="contenido">
        <h1>Bienvenido </h1>
        <p>COCODE siempre trabajando para el pueblo.</p>
    </div>
</body>
</html>

<?php
function obtenerURLFondo() {
    // Aquí puedes implementar la lógica para obtener la URL de la imagen de fondo.
    // Puede ser a través de una base de datos, una API, o simplemente un archivo estático.
    // En este ejemplo, se retorna una URL de imagen estática.
    return "img/fondo1.jpg";
    
}
?>