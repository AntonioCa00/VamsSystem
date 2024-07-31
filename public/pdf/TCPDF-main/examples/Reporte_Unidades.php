<?php
require_once('tcpdf_include.php');
// create new PDF document
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Javier Chavez');
$pdf->setTitle('Reporte general ');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' reporte de unidades ', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// Agregar una página
$pdf->AddPage();

// Obtener los datos de títulos, subtitulos y fechas
// $tituloReporte = "Reporte general de gastos anual "; //Agregar el nombre del reporte segun el lapso de tiempo

// $fechaReporte = "Enero - Diciembre 2023";

// Establecer la configuración regional a Español
setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish');

// Obtener el mes actual en español
$currentMonth = ucfirst(strftime('%B'));

// Obtener el año actual
$currentYear = date('Y');

// Definir la fuente y el tamaño de la fuente titulo
$pdf->SetFont('helvetica', 'B', 19);
// Imprimir el título del reporte

$pdf->Cell(0, 10, 'Reporte de Unidades', 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);

$pdf->Ln(2); // Salto de línea antes de la tabla
// Definir la fuente y el tamaño de la fuente
$pdf->SetFont('helvetica', 'A', 10);

$pdf->Ln(4); // Salto de línea antes de la tabla

$pdf->SetFont('helvetica', 'B', 12);
// Imprimir el subtutitulo
$pdf->Cell(0, 10, "Registro actual de unidades activas ", 0, 1, 'A');
//  ID requisicion	Encargado:	Fecha solicitud:	Estado:	Unidad:	Requisicion	Orden Compra:
// Crear la tabla de gastos
$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(30, 7, 'Placas', 1, 0, 'C', 1);
$pdf->Cell(30, 7, 'N° Permiso', 1, 0, 'C', 1);
$pdf->Cell(55, 7, 'Numero de serie', 1, 0, 'C', 1);
$pdf->Cell(30, 7, 'Tipo', 1, 0, 'C', 1);
$pdf->Cell(15, 7, 'Año', 1, 0, 'C', 1);
$pdf->Cell(50, 7, 'Marca / Modelo', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Caracteristica', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Kilometraje', 1, 1, 'C',1);

// Iterar sobre los datos de gastos y agregar filas a la tabla
foreach ($unidades as $unidad) {
    $pdf->Cell(30, 7, $unidad['id_unidad'], 1, 0, 'C',0);
    $pdf->Cell(30, 7, $unidad['n_de_permiso'], 1, 0, 'C',0);
	$pdf->Cell(55, 7, $unidad['n_de_serie'], 1, 0, 'C',0);
    $pdf->Cell(30, 7, $unidad['tipo'], 1, 0, 'C',0);
    $pdf->Cell(15, 7, $unidad['anio_unidad'], 1, 0, 'C',0);
    $pdf->Cell(50, 7, $unidad['marca'].' '.$unidad['modelo'], 1, 0, 'C',0);
	$pdf->Cell(25, 7, $unidad['caracteristicas'], 1, 0, 'C',0);
    $pdf->Cell(25, 7, $unidad['kilometraje'], 1, 1, 'C',0);
}
// Generar el PDF

$pdf->Output('reporte_mensual.pdf', 'I');
