<?php
require "./fpdf/fpdf.php";
include './config.php';
include './class_mysql.php';

// Consulta SQL para recuperar los registros de asistencia
$mysqli = mysqli_connect(SERVER, USER, PASS, BD);
mysqli_set_charset($mysqli, "utf8");

$selusers = mysqli_query($mysqli, "SELECT * FROM asistencia");

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
$pdf->SetFont("Arial", "b", 9);
$pdf->Image('../img/Guatemala.png', 15, 15, 40);
$pdf->Ln(10); // Inserta 10 unidades de espacio en blanco


// Título del PDF
$pdf->Cell(0, 5, 'COCODE Panimache4 del municipio de Chichicastenango, departamento Quiche', 0, 1, 'C');
$pdf->Cell(0, 5, 'Guatemala', 0, 1, 'C');

$pdf->Ln(15);

$pdf->Cell(0, 8, 'Listado de asistencia', 0, 1, 'C');


// Encabezados de la tabla
$pdf->SetTextColor(0, 128, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 10, '#', 1);
$pdf->Cell(30, 10, 'Nombre', 1);
$pdf->Cell(30, 10, 'Apellidos', 1);
$pdf->Cell(40, 10, 'DPI', 1);
$pdf->Cell(30, 10, 'Asistencia', 1);
$pdf->Cell(45, 10, 'Fecha de registro', 1);
$pdf->Ln();

// Contenido de la tabla
$pdf->SetTextColor(0, 0, 0);
$ct = 1;
while ($row = mysqli_fetch_array($selusers, MYSQLI_ASSOC)) {
    $pdf->Cell(10, 10, $ct, 1);
    $pdf->Cell(30, 10, $row['nombre'], 1);
    $pdf->Cell(30, 10, $row['apellido'], 1);
    $pdf->Cell(40, 10, $row['dpi'], 1);
    if ($row['asistio'] == '1') {
        $pdf->SetTextColor(0, 0, 0); // Establece el color del texto a negro
    } else {
        $pdf->SetTextColor(255, 0, 0); // Establece el color del texto a rojo
    }

    $pdf->Cell(30, 10, ($row['asistio'] == '1') ? 'Si' : 'No', 1);

    // Restablece el color del texto a negro (o el color deseado) después de imprimir el texto si es necesario
    $pdf->SetTextColor(0, 0, 0); // Restablece el color del texto a negro
    $pdf->Cell(45, 10, $row['fecha_registro'], 1);
    $pdf->Ln();
    $ct++;
}

$pdf->Ln();
$pdf->SetTextColor(0, 0, 128);
$pdf->cell(0, 5, "COCODE siempre trabajando para ti!", 0, 0, 'C');
$pdf->Ln();
$pdf->cell(0, 5, "GRACIAS POR TU ASISTENCIA", 0, 0, 'C');


// Salida del PDF
$pdf->Output();
