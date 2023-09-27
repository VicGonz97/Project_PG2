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
$pdf->SetFillColor(0, 255, 255);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFont("Arial", "b", 9);
$pdf->Image('../img/Guatemala.png', 15, 15, 40);
$pdf->Ln(10); // Inserta 10 unidades de espacio en blanco
//$pdf->Image('../img/Guatemala.png', 40, 10, -300);
$pdf->Cell(0, 5, utf8_decode('COCODE Panimache4 del municipio de Chichicastenango, departamento Quiche'), 0, 1, 'C');
$pdf->Cell(0, 5, utf8_decode('Guatemala'), 0, 1, 'C');


$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

$pdf->Cell(0, 5, utf8_decode('Numero de comprobante #' . utf8_decode($reg['id_contabilidad'])), 0, 1, 'C');

$pdf->Cell(35, 10, 'Nombre', 1, 0, 'C', true);
$pdf->Cell(0, 10, utf8_decode($reg['nombre']), 1, 1, 'L');
$pdf->Cell(35, 10, 'Apellido', 1, 0, 'C', true);
$pdf->Cell(0, 10, utf8_decode($reg['apellido']), 1, 1, 'L');
$pdf->Cell(35, 10, 'Cantidad', 1, 0, 'C', true);
$pdf->Cell(0, 10, utf8_decode($reg['cantidad']), 1, 1, 'L');
$pdf->Cell(35, 10, 'dpi', 1, 0, 'C', true);
$pdf->Cell(0, 10, utf8_decode($reg['dpi']), 1, 1, 'L');
$pdf->Cell(35, 10, 'Fecha', 1, 0, 'C', true);
$pdf->Cell(0, 10, utf8_decode($reg['fecha_registro']), 1, 1, 'L');


$pdf->Ln();

$pdf->cell(0, 5, "COCODE siempre trabajando para ti!", 0, 0, 'C');
$pdf->Ln();
$pdf->cell(0, 5, "GRACIAS POR TU APORTE", 0, 0, 'C');

$pdf->output();
