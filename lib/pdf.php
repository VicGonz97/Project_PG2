<?php
require "./fpdf/fpdf.php";
include './class_mysql.php';
include './config.php';

$id = MysqlQuery::RequestGet('id');
$sql = Mysql::consulta("SELECT * FROM contabilidad WHERE id_contabilidad = '$id'");
$reg = mysqli_fetch_array($sql, MYSQLI_ASSOC);

class PDF extends FPDF
{
}

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(15, 20);
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTextColor(0, 0, 128);
$pdf->SetFillColor(200, 200, 200);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFont("Times", "b", 11);
$pdf->Image('../img/Guatemala.png', 15, 15, 40);
$pdf->Ln(10); // Inserta 10 unidades de espacio en blanco
// Título del PDF
$pdf->Cell(0, 5, 'COCODE. Canton Panimache4 del municipio de Chichicastenango, departamento de Quiche', 0, 1, 'C');
$pdf->Cell(0, 5, 'Guatemala', 0, 1, 'C');

$pdf->Ln();
$pdf->Ln();

$pdf->SetTextColor(0, 0, 12);
$pdf->Cell(170, 5, 'No. Comprobante #' . $reg['id_contabilidad'], 0, 1, 'R');

$pdf->SetFont('Times', '', 10);
$pdf->Cell(35, 10, 'Nombre', '0', 0, 'C', true);
$pdf->Cell(135, 10, $reg['nombre'], 'TB', 1, 'L');
$pdf->Cell(35, 10, 'Apellido', '0', 0, 'C', true);
$pdf->Cell(135, 10, $reg['apellido'], 'B', 1, 'L');
$pdf->Cell(35, 10, 'Cantidad', '0', 0, 'C', true);
$pdf->Cell(135, 10, $reg['cantidad'], 'B', 1, 'L');
$pdf->Cell(35, 10, 'dpi', '0', 0, 'C', true);
$pdf->Cell(135, 10, $reg['dpi'], 'B', 1, 'L');
$pdf->Cell(35, 10, 'Fecha', 'B', 0, 'C', true);
$pdf->Cell(135, 10, $reg['fecha_registro'], 'B', 1, 'L');




$pdf->Ln();
$pdf->SetTextColor(0, 0, 128);
$pdf->cell(0, 5, "COCODE siempre trabajando para ti!", 0, 0, 'C');
$pdf->Ln();
$pdf->cell(0, 5, "GRACIAS POR TU APORTE", 0, 0, 'C');

$pdf->output();
