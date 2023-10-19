<?php
require "./fpdf/fpdf.php";
include './config.php';
include './class_mysql.php';


$mysqli = mysqli_connect(SERVER, USER, PASS, BD);
mysqli_set_charset($mysqli, "utf8");

$selusers = mysqli_query($mysqli, "SELECT * FROM retiro");

class PDF extends FPDF
{
}

// Crear una instancia de la clase FPDF
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(15, 20);
$pdf->AliasNbPages();
$pdf->AddPage();

// Configura el estilo de fuente y tamaño
$pdf->SetTextColor(0, 0, 128);
$pdf->SetFillColor(0, 255, 255);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFont("Times", "b", 11);
$pdf->Image('../img/Guatemala.png', 15, 15, 40);
$pdf->Ln(10); // Inserta 10 unidades de espacio en blanco

// Título del PDF
$pdf->Cell(0, 5, 'COCODE. Canton Panimache4 del municipio de Chichicastenango, departamento de Quiche', 0, 1, 'C');
$pdf->Cell(0, 5, 'Guatemala', 0, 1, 'C');

$pdf->Ln(10);

$pdf->Cell(0, 8, 'HISTORIAL DE RETIROS', 0, 1, 'C');
$pdf->Ln(7);

// Encabezados de la tabla
$pdf->SetTextColor(0, 0, 0); // Color negro
$pdf->SetFont('Times', 'B', 10); // Fuente en negritas
$pdf->Cell(20, 10, '#', 0, 0, 'C'); // Sin borde
$pdf->Cell(25, 10, 'Cantidad', 0, 0, 'C'); // Cambiado el encabezado
$pdf->Cell(85, 10, 'Descripcion', 0, 0, 'C');
$pdf->Cell(40, 10, 'Fecha de Retiro', 0, 0, 'C');
$pdf->Ln();

// Agregar línea debajo de los encabezados
$pdf->Cell(170, 0, '', 'T');
$pdf->Ln(); // Nueva línea para separar

$total_cantidad = 0; // Variable para almacenar el total de la cantidad

// Definir las anchuras iniciales de las columnas
$ancho_numero = 20;
$ancho_cantidad = 25;
$ancho_descripcion = 85; // Ancho inicial para la descripción
$ancho_fecha = 40;

// Contenido de la tabla (sin negritas)
$pdf->SetFont('Times', '', 11); // Fuente normal (sin negritas)
$pdf->SetTextColor(0, 0, 0); // Color negro
$ct = 1;
while ($row = mysqli_fetch_array($selusers, MYSQLI_ASSOC)) {
    $pdf->Cell($ancho_numero, 10, $ct, 0, 0, 'C'); // Contenido centrado

    $cantidad_quetzales = 'Q' . number_format($row['cantidad'], 2, '.', ',');
    $pdf->Cell($ancho_cantidad, 10, $cantidad_quetzales, 0, 0, 'C');
    $descripcion = $row['descripcion'];
    if ($pdf->GetStringWidth($descripcion) > $ancho_descripcion) {
        $ancho_descripcion = $pdf->GetStringWidth($descripcion) + 6; // Añade un pequeño espacio
    }

    $pdf->Cell($ancho_descripcion, 10, $descripcion, 0, 0, 'C');

    $fecha_formateada = date('d/m/Y', strtotime($row['fecha_retiro']));
    $hora_formateada = date('h:i a', strtotime($row['fecha_retiro']));
    $pdf->Cell($ancho_fecha, 10, $fecha_formateada . ' ' . $hora_formateada, 0, 0, 'C');
    $pdf->Ln();
    $ct++;

    // Suma la cantidad para calcular el total
    $total_cantidad += $row['cantidad'];
}


$pdf->Cell(170, 0, '', 'T');
$pdf->Ln(); // Nueva línea para separar

// Formatea el total con dos decimales y muestra en Quetzalez (Q)
$total_cantidad_formateado = ' Q' . number_format($total_cantidad, 2, '.', ',');
$pdf->SetFont('Times', 'B', 11); // Fuente en negritas
$pdf->Cell(17, 10, 'Total:', 0, 0, 'R');
$pdf->Cell(34, 10, $total_cantidad_formateado, 0, 1, 'C'); // Contenido centrado y nueva línea

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->SetTextColor(0, 0, 128);
$pdf->Cell(0, 5, "COCODE siempre trabajando para ti!", 0, 0, 'C');

// Salida del PDF
$pdf->Output();
