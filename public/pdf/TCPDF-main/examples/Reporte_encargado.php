<?php 
require_once('tcpdf_include.php'); 
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Javier Chavez');
$pdf->setTitle('Reporte por encargado ');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' de reporte de tickets por encargado ', PDF_HEADER_STRING);

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


// Obtener los datos de tutulos, subtitulos y fechas 
$tituloReporte = "Reporte de tickets por encargado ";
$fechaReporte = "Enero - Diciembre 2023"; 
$subtitulo_empleado = "Datos Solicitante";

// Definir la fuente y el tamaño de la fuente titulo
$pdf->SetFont('helvetica', 'B', 19);
// Imprimir el título del reporte

$pdf->Cell(0, 10, $tituloReporte, 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);
// Imprimir la fecha del reporte
$pdf->Cell(0, 10, "Periodo de registro: $fechaReporte", 0, 1, 'C');

$pdf->Ln(10); // Salto de línea antes de la tabla

$pdf->Cell(0, 10, $subtitulo_empleado, 0, 1, 'A');
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
$nombreEmpleado = $datosEmpleado[0]['nombre'];
$posicionEmpleado = $datosEmpleado[0]['rol'];
$idEmpleado = $datosEmpleado[0]['idEmpleado'];
$fechaEmpleado = date("Y/m/d");

$pdf->Cell(40, 5, $nombreEmpleado, 1);
$pdf->Cell(40, 5, $posicionEmpleado, 1);
$pdf->Cell(40, 5, $idEmpleado, 1);
$pdf->Cell(40, 5, $fechaEmpleado, 1, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);

$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Datos encargado', 0, 1, 'A');

// Crear la tabla de encargados
$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(20, 7, 'ID_Usuario', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Rol', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Solicitudes', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Aprobadas', 1, 1, 'C', 1);

$pdf->Cell(20, 7, $encargado->id, 1);
$pdf->Cell(20, 7, $encargado->nombre, 1);
$pdf->Cell(20, 7, $encargado->departamento, 1);
$pdf->Cell(20, 7, $solicitudes, 1);
$pdf->Cell(20, 7, $completas, 1, 1); 


$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Solicitudes', 0, 1, 'A');

// Crear la tabla de gastos
$pdf->SetFont('helvetica', '', 9);

$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(20, 7, 'Id_Solicitud', 1, 0, 'C', 1);
$pdf->Cell(35, 7, 'Fecha', 1, 0, 'C', 1);
$pdf->Cell(35, 7, 'Estatus', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Unidad', 1, 0, 'C', 1);
$pdf->Cell(50, 7, 'Pdf', 1, 1, 'C', 1);

$datosSolicitud = $Requisiciones;

// Iterar sobre los datos de gastos y agregar filas a la tabla
foreach ($datosSolicitud as $soli) {
    $pdf->Cell(20, 7, $soli->id_requisicion, 1);
    $pdf->Cell(35, 7, $soli->created_at, 1);
    $pdf->Cell(35, 7, $soli->estado, 1);
    $pdf->Cell(25, 7, $soli->unidad_id, 1);
	$pdf->Cell(50, 7, $soli[4], 1, 1);
}

$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Salidas', 0, 1, 'A');

// Crear la tabla de gastos
$pdf->SetFont('helvetica', '', 9);

$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(20, 7, 'Id_Salida', 1, 0, 'C', 1);
$pdf->Cell(35, 7, 'Fecha', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Cantidad', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Unidad', 1, 0, 'C', 1);
$pdf->Cell(60, 7, 'Refaccion', 1, 1, 'C', 1);

$datosRefaccion = $salidas;

// Iterar sobre los datos de gastos y agregar filas a la tabla
foreach ($datosRefaccion as $refaccion) {
    $pdf->Cell(20, 7, $refaccion->id_salida, 1);
    $pdf->Cell(35, 7, $refaccion->created_at, 1);
    $pdf->Cell(25, 7, $refaccion->cantidad, 1);
    $pdf->Cell(25, 7, $refaccion->unidad_id, 1);
	$pdf->Cell(60, 7, $refaccion->nombre, 1, 1);
	  
}

// Generar el PDF
$pdf->Output('reporte_mensual.pdf', 'I');
