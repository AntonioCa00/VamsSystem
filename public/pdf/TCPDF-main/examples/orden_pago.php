<?php 
require_once('tcpdf_include.php'); 
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Javier Chavez');
$pdf->setTitle('Orden_pago');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' Orden de pago ', PDF_HEADER_STRING);

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
$pdf->Cell(0, 10, "Orden de pago n° ". $idcorresponde, 0, 1, 'L');
// set margins
// Definir la fuente y el tamaño de la fuente titulo
$pdf->SetFont('helvetica', 'B', 19);
// Imprimir el título del reporte

$pdf->Cell(0, 10, "Orden de pago ", 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Ln(10); // Salto de línea antes de la tabla

$pdf->Cell(0, 10, "Solicitante", 0, 1, 'C',0);
// Definir la fuente y el tamaño de la fuente
$pdf->SetFont('helvetica', 'A', 11);

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
$posicionEmpleado = $datosEmpleado[0]['rol'];
$idEmpleado = $datosEmpleado[0]['idEmpleado'];
$fechaEmpleado = date("Y/m/d");

$pdf->Cell(40, 5, $nombreEmpleado.' '.$apepatEmpleado, 1);
$pdf->Cell(40, 5, $posicionEmpleado, 1);
$pdf->Cell(40, 5, $idEmpleado, 1);
$pdf->Cell(40, 5, $fechaEmpleado, 1, 1, 'C');
$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);

// Imprimir el subtutitulo 
$pdf->Cell(0, 10, "Servicio", 0, 1, 'C',0);
// Crear la tabla de gastos
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla

$pdf->Cell(20, 10, 'Cantidad', 1, 0, 'C', 1);
$pdf->Cell(135, 10, 'Descripción del servicio', 1, 0, 'C', 1);
$pdf->Cell(25, 10, 'Monto total', 1, 1, 'C', 1);

// Iterar sobre los datos de gastos filtrados y agregar filas a la tabla
$pdf->Cell(20, 10, 1, 1);
$pdf->Cell(135, 10, $servicio->nombre_servicio, 1);
$pdf->Cell(25, 10, '$' . $importe, 1, 1, 'R');

// Imprimir el total de montos totales
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(155, 10, 'Total a pagar', 1);
$pdf->Cell(25, 10, '$' . number_format($importe , 2), 1, 1, 'R');

if (!empty($Nota)){
    $pdf->Ln(10); // Salto de línea antes de la tabl1a   
    // Definir la fuente y el tamaño de la fuente
    $pdf->SetFont('helvetica', 'A', 11);
    // Encabezados de la tabla
    $pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
    $pdf->Cell(180, 5, 'Notas', 1, 1, 'C', 1);
    // notas que agrega el solicitante 
    $pdf->MultiCell(180, 5, $Nota, 1, 1 ,'C', 0 );
}

$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 10, "Proveedor del servicio", 0, 1, 'C',0);

// Crear la tabla de gastos

$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(180, 7, 'Nombre', 1, 1, 'C', 1);
$pdf->MultiCell(180, 7, $servicio->nombre, 1,1);
$pdf->Cell(180, 7, 'Correo(s)', 1, 1, 'C', 1);
$pdf->MultiCell(180, 7, $servicio->correo, 1,1);
$pdf->Cell(35, 7, 'Telefono', 1, 0, 'C', 1);
$pdf->Cell(85, 7, 'Nombre del contacto', 1, 0, 'C', 1);
$pdf->Cell(60, 7, 'RFC', 1, 1, 'C', 1);
$pdf->Cell(35, 7, $servicio->telefono, 1);
$pdf->Cell(85, 7, $servicio->contacto, 1);
$pdf->Cell(60, 7, $servicio->rfc , 1,1);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(180, 7, 'DATOS BANCARIOS DEL PROVEEDOR', 1, 1, 'C', 1);
$pdf->SetFont('helvetica', '', 10);
if ((!empty($servicio->banco) && !empty($servicio->n_cuenta) && !empty($servicio->n_cuenta_clabe))){
    $pdf->Cell(60, 7, 'Banco:', 1, 0, 'C', 1);
    $pdf->Cell(60, 7, 'Número de cuenta', 1, 0, 'C', 1);
    $pdf->Cell(60, 7, 'Número de cuenta clabe', 1, 1, 'C', 1);
    $pdf->Cell(60, 7, $servicio->banco, 1);
    $pdf->Cell(60, 7, $servicio->n_cuenta, 1);
    $pdf->Cell(60, 7, $servicio->n_cuenta_clabe, 1,1);
}else{
    $pdf->Cell(180, 7, 'No se han cargado los datos bancarios de este proveedor', 1, 1, 'C', 1);
}


$pdf->Ln(10); // Salto de línea antes de la tabla

$pdf->SetY(260); // Ajusta la posición Y según tus necesidades
// Dibujar una línea

// Coordenadas iniciales y finales para los tres segmentos
$x1 = 10;
$x2 = 70;
$x3 = 130;

$y = $pdf->GetY(); // Obtener la posición Y actual

// Dibujar el primer segmento de la línea
$pdf->Line(10, $y, 60, $y);

// Dibujar el segundo segmento de la línea
$pdf->Line(75, $y, 130, $y);

// Dibujar el tercer segmento de la línea
$pdf->Line(140, $y, 190, $y);

$pdf->SetFont('helvetica', '', 11,);
$pdf->Cell(0, 10, '        Solicitante                                                 Finanzas                                     Gerente general ', 0, 1, 'A', 0);

// Nombre del archivo y ruta proporcionados desde el controlador
$nombreArchivo = 'pagoFijo_' . $idcorresponde. '.pdf';

$rutaDescarga = 'D:/laragon/www/VamsSystem/public/pagosFijos/' . $nombreArchivo;
//$rutaDescarga = 'C:/wamp64/www/VamsSystem/public/pagosFijos/'. $nombreArchivo;

$pdf->Output($rutaDescarga, 'F');