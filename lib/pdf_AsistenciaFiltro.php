<?php
require "./fpdf/fpdf.php";
include './config.php';
include './class_mysql.php';

// Consulta SQL para recuperar los registros de asistencia
$mysqli = mysqli_connect(SERVER, USER, PASS, BD);
mysqli_set_charset($mysqli, "utf8");

class PDF extends FPDF
{
}

// Crear una instancia de la clase FPDF
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(15, 20);
$pdf->AliasNbPages();
$pdf->AddPage();

// Resto del código de configuración del PDF (sin cambios)

// Obtén las fechas de inicio y fin desde el formulario
$fecha_inicio = isset($_POST['fecha_inicio']) ? formatDate($_POST['fecha_inicio']) . ' 00:00:00' : '';
$fecha_fin = isset($_POST['fecha_fin']) ? formatDate($_POST['fecha_fin']) . ' 23:59:59' : '';

// Consulta SQL modificada con filtros de fecha
if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $query = "SELECT * FROM asistencia WHERE fecha_registro BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    $selusers = mysqli_query($mysqli, $query);
} else {
    // Si no se proporcionaron fechas, obtén todos los registros
    $selusers = mysqli_query($mysqli, "SELECT * FROM asistencia");
}




// Configura el estilo de fuente y tamaño
$pdf->SetTextColor(0, 0, 128);
$pdf->SetFillColor(0, 255, 255);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFont("Times", "b", 11);;
$pdf->Image('../img/Guatemala.png', 15, 15, 40);
$pdf->Ln(10); // Inserta 10 unidades de espacio en blanco


// Título del PDF
$pdf->Cell(0, 5, 'COCODE. Canton Panimache4 del municipio de Chichicastenango, departamento de Quiche', 0, 1, 'C');
$pdf->Cell(0, 5, 'Guatemala', 0, 1, 'C');

$pdf->Ln(10);

$pdf->Cell(0, 8, 'LISTADO DE ASISTENCIA', 0, 1, 'C');
$pdf->Ln(7);

// Encabezados de la tabla
$pdf->SetTextColor(0, 0, 0); // Color negro
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(10, 10, '#', 0, 0, 'C'); // Centra el texto en las celdas
$pdf->Cell(40, 10, 'Nombre', 0, 0, 'C');
$pdf->Cell(40, 10, 'Apellidos', 0, 0, 'C');
$pdf->Cell(40, 10, 'Asistencia', 0, 0, 'C');
$pdf->Cell(45, 10, 'Fecha de registro', 0, 0, 'C');
$pdf->Ln();

// Agregar línea debajo de los encabezados
$pdf->Cell(170, 0, '', 'T');
$pdf->Ln(); // Nueva línea para separar

// Contenido de la tabla
$pdf->SetFont('Times', '', 11); // Fuente normal (sin negritas)
$pdf->SetTextColor(0, 0, 0); // Color negro
$ct = 1;
while ($row = mysqli_fetch_array($selusers, MYSQLI_ASSOC)) {
    $pdf->Cell(10, 10, $ct, 0, 0, 'C');
    $pdf->Cell(40, 10, $row['nombre'], 0, 0, 'C');
    $pdf->Cell(40, 10, $row['apellido'], 0, 0, 'C');
    if ($row['asistio'] == '1') {
        $pdf->SetTextColor(0, 0, 0); // Establece el color del texto a negro
    } else {
        $pdf->SetTextColor(255, 0, 0); // Establece el color del texto a rojo
    }
    $pdf->Cell(40, 10, ($row['asistio'] == '1') ? 'Si' : 'No', 0, 0, 'C');
    $pdf->SetTextColor(0, 0, 0); // Restablece el color del texto a negro
    $pdf->Cell(45, 10, date('d/m/Y h:i a', strtotime($row['fecha_registro'])), 0, 0, 'C'); // Formatea la fecha
    $pdf->Ln();
    $ct++;
}


$pdf->Ln();
$pdf->SetTextColor(0, 0, 128);
$pdf->SetFont('Times', 'B', 10); // Fuente en negritas a blanco
$pdf->cell(0, 5, "GRACIAS POR TU ASISTENCIA", 0, 0, 'C');


// Salida del PDF
$pdf->Output();
