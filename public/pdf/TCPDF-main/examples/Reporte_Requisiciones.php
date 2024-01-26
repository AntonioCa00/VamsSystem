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

//Funcion para agragar el titulo del reporte, Si se solicita un reporte mensual, anual, etc.

if ($tipoReporte == "anual") {
	$tituloReporte = "Reporte general de gastos anual";
} elseif ($tipoReporte == "mensual") {
	$tituloReporte = "Reporte general de gastos mensual";
} elseif ($tipoReporte == "semanal") {
	$tituloReporte = "Reporte general de gastos semanal";
} else {
	$tituloReporte = "Reporte general de gastos";
}


// Obtener los datos de títulos, subtitulos y fechas 
// $tituloReporte = "Reporte general de gastos anual "; //Agregar el nombre del reporte segun el lapso de tiempo

// $fechaReporte = "Enero - Diciembre 2023";

// Establecer la configuración regional a Español
setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish');

// Obtener el mes actual en español
$currentMonth = ucfirst(strftime('%B'));

// Obtener el año actual
$currentYear = date('Y');

// Obtener el lapso de tiempo
$lapsoTiempo = '';

if ($tipoReporte == "anual") {
    $lapsoTiempo = "Enero - Diciembre $currentYear";
} elseif ($tipoReporte == "mensual") {
    $lapsoTiempo = $currentMonth . ' ' . $currentYear;
} elseif ($tipoReporte == "semanal") {
    $lapsoTiempo = "Semana actual";
} else {
    $lapsoTiempo = "Todas las requisiciones";
}

$fechaReporte = $lapsoTiempo;


// Definir la fuente y el tamaño de la fuente titulo
$pdf->SetFont('helvetica', 'B', 19);
// Imprimir el título del reporte

$pdf->Cell(0, 10, $tituloReporte, 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);
// Imprimir la fecha del reporte
$pdf->Cell(0, 10, "Periodo de registro: $fechaReporte", 0, 1, 'C');

$pdf->Ln(6); // Salto de línea antes de la tabla

$pdf->Cell(0, 10, 'Datos del usuario', 0, 1, 'A');
// Definir la fuente y el tamaño de la fuente
$pdf->SetFont('helvetica', 'A', 10);

// Encabezados de la tabla
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(40, 5, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(40, 5, 'Tipo_Perfil', 1, 0, 'C', 1);
$pdf->Cell(40, 5, 'ID del Empleado', 1, 0, 'C', 1);
$pdf->Cell(40, 5, 'Fecha', 1, 1, 'C', 1);

// Deserializar los datos del empleado
$datosEmpleadoSerializados = file_get_contents($rutaArchivo);
$datosEmpleado = unserialize($datosEmpleadoSerializados);

// Datos del empleado (simulados)
$nombreEmpleado = $datosEmpleado[0]['nombres'];
$apepatEmpleado = $datosEmpleado[0]['apellidoP'];
$apematEmpleado = $datosEmpleado[0]['apellidoM'];
$posicionEmpleado = $datosEmpleado[0]['dpto'];
$idEmpleado = $datosEmpleado[0]['idEmpleado'];
$fechaEmpleado = date("Y/m/d");

$pdf->Cell(40, 5, $nombreEmpleado.' '.$apepatEmpleado, 1, 0, 'C',0);
if ($datosEmpleado[0]['rol'] === 'Compras' || $datosEmpleado[0]['rol'] === 'Gerencia General'){
    $pdf->Cell(40, 5, $datosEmpleado[0]['rol'], 1, 0, 'C',0);    
} else {
    $pdf->Cell(40, 5, $posicionEmpleado, 1, 0, 'C',0);
}
$pdf->Cell(40, 5, $idEmpleado, 1, 0, 'C',0);
$pdf->Cell(40, 5, $fechaEmpleado, 1, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);

$pdf->Ln(4); // Salto de línea antes de la tabla
// Imprimir el subtutitulo 
$pdf->Cell(0, 10, "Registro de solicitudes creadas ", 0, 1, 'A');
//  ID requisicion	Encargado:	Fecha solicitud:	Estado:	Unidad:	Requisicion	Orden Compra:
// Crear la tabla de gastos
$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(25, 7, 'Id Requsicion', 1, 0, 'C', 1);
$pdf->Cell(50, 7, 'Solicitante', 1, 0, 'C', 1);
$pdf->Cell(35, 7, 'Fecha solicitud', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Estado', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Unidad', 1, 1, 'C', 1);


// Iterar sobre los datos de gastos y agregar filas a la tabla
foreach ($datosRequisicion as $req) {
    $pdf->Cell(25, 7, $req['id_requisicion'], 1, 0, 'C',0);
    $pdf->Cell(50, 7, $req['nombres'].' '.$req['apellidoP'], 1, 0, 'C',0); 
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