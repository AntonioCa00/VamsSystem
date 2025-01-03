<?php
require_once('tcpdf_include.php');
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Javier Chavez');
$pdf->setTitle('Reporte general ');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' General de gastos anual ', PDF_HEADER_STRING);

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

$pdf->Cell(0, 10, 'Reporte de requisiciones', 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);

$pdf->Ln(6); // Salto de línea antes de la tabla

$pdf->Cell(0, 10, 'Periodo del reporte', 0, 1, 'A');
// Definir la fuente y el tamaño de la fuente
$pdf->SetFont('helvetica', 'A', 10);

// Encabezados de la tabla
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(87.5, 5, 'Fecha Inicial:', 1, 0, 'C', 1);
$pdf->Cell(87.5, 5, 'Fecha Final:', 1, 1, 'C', 1);

$pdf->Cell(87.5, 5, $fechas['fecha_inicio'], 1, 0, 'C',0);
$pdf->Cell(87.5, 5, $fechas['fecha_fin'], 1, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);

$pdf->Ln(4); // Salto de línea antes de la tabla

$pdf->SetFont('helvetica', 'B', 12);
// Imprimir el subtutitulo
$pdf->Cell(0, 10, "Registro de solicitudes creadas ", 0, 1, 'A');
//  ID requisicion	Encargado:	Fecha solicitud:	Estado:	Unidad:	Requisicion	Orden Compra:
// Crear la tabla de gastos
$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(15, 7, 'Folio', 1, 0, 'C', 1);
$pdf->Cell(50, 7, 'Solicitante', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Departamento', 1, 0, 'C', 1);
$pdf->Cell(35, 7, 'Fecha solicitud', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Estado', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Unidad', 1, 1, 'C', 1);


// Iterar sobre los datos de gastos y agregar filas a la tabla
foreach ($datosRequisicion as $req) {
    $pdf->Cell(15, 7, $req['id_requisicion'], 1, 0, 'C',0);
    $pdf->Cell(50, 7, $req['nombres'].' '.$req['apellidoP'], 1, 0, 'C',0);
    $pdf->Cell(25, 7, $req['departamento_nombre'], 1, 0, 'C',0);
	$pdf->Cell(35, 7, $req['created_at'], 1, 0, 'C',0);
	$pdf->Cell(25, 7, $req['estado'], 1, 0, 'C',0);
	if(empty($req['unidad_id'])){
        $pdf->Cell(25, 7, 'Sin unidad', 1, 1, 'C',0);
    } elseif ($req['unidad_id'] == 1){
        $pdf->Cell(25, 7, 'No asignada', 1, 1, 'C',0);
    } else{
        $pdf->Cell(25, 7, $req['unidad_id'], 1, 1, 'C',0);
    }
}
// Generar el PDF

$pdf->Output('reporte_mensual.pdf', 'I');
