<?php
require "./fpdf/fpdf.php";
include './config.php';
include './class_mysql.php';


$mysqli = mysqli_connect(SERVER, USER, PASS, BD);
mysqli_set_charset($mysqli, "utf8");

$selusers = mysqli_query($mysqli, "SELECT * FROM cocode");

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

$pdf->Cell(0, 8, 'LISTADO DEL COCODE', 0, 1, 'C');
$pdf->Ln(7);

// Encabezados de la tabla
$pdf->SetTextColor(0, 0, 0); // Color negro
$pdf->SetFont('Times', 'B', 10); // Fuente en negritas
$pdf->Cell(15, 10, '#', 0, 0, 'C'); // Sin borde
$pdf->Cell(30, 10, 'Nombre', 0, 0, 'C'); // Cambiado el encabezado
$pdf->Cell(40, 10, 'Apellido', 0, 0, 'C');
$pdf->Cell(30, 10, 'Telefono', 0, 0, 'C');
$pdf->Cell(30, 10, 'DPI', 0, 0, 'C');
$pdf->Cell(30, 10, 'Cargo', 0, 0, 'C');
$pdf->Ln();

// Agregar línea debajo de los encabezados
$pdf->Cell(170, 0, '', 'T');
$pdf->Ln(); // Nueva línea para separar


// Contenido de la tabla (sin negritas)
$pdf->SetFont('Times', '', 11); // Fuente normal (sin negritas)
$pdf->SetTextColor(0, 0, 0); // Color negro
$ct = 1;
while ($row = mysqli_fetch_array($selusers, MYSQLI_ASSOC)) {
    $pdf->Cell(15, 10, $ct, 0, 0, 'C'); // Contenido centrado

    $pdf->Cell(30, 10, $row['nombre'], 0, 0, 'C');
    $pdf->Cell(40, 10, $row['apellido'], 0, 0, 'C');
    $pdf->Cell(30, 10, $row['telefono'], 0, 0, 'C');
    $pdf->Cell(30, 10, $row['dpi'], 0, 0, 'C');
    $pdf->Cell(30, 10, $row['cargo'], 0, 0, 'C');

    $pdf->Ln();
    $ct++;
}

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

// Salida del PDF
$pdf->Output();
