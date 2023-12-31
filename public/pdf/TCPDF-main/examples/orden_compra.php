<?php 
require_once('tcpdf_include.php'); 
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Javier Chavez');
$pdf->setTitle('Orden_compra ');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' orden de compra ', PDF_HEADER_STRING);

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

// Definir la fuente y el tamaño de la fuente titulo
$pdf->SetFont('helvetica', 'B', 19);
// Imprimir el título del reporte

$pdf->Cell(0, 10, "Orden de compra ", 0, 1, 'C');
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
$nombreEmpleado = $datosEmpleado[0]['nombre'];
$posicionEmpleado = $datosEmpleado[0]['rol'];
$idEmpleado = $datosEmpleado[0]['idEmpleado'];
$fechaEmpleado = date("Y/m/d");

$pdf->Cell(40, 5, $nombreEmpleado, 1);
$pdf->Cell(40, 5, $posicionEmpleado, 1);
$pdf->Cell(40, 5, $idEmpleado, 1);
$pdf->Cell(40, 5, $fechaEmpleado, 1, 1, 'C');
$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);

// Imprimir el subtutitulo 
$pdf->Cell(0, 10, "Articulos", 0, 1, 'C',0);
// Crear la tabla de gastos
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(20, 10, 'cantidad', 1, 0, 'C', 1);
$pdf->Cell(100, 10, 'Descripción', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Precio_unitario', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Monto_total', 1, 1, 'C', 1);

// Iterar sobre los datos de gastos y agregar filas a la tabla
foreach ($datosOrden as &$requisicion) { // Usar '&' para modificar el valor en el arreglo original
    $montoTotal = $requisicion['cantidad'] * $requisicion['precio']; // Calcular el monto total
    $requisicion[4] = $montoTotal; // Almacenar el monto total en el arreglo
    $pdf->Cell(20, 10, $requisicion['cantidad'], 1);
    $pdf->Cell(100, 10, $requisicion['descripcion'], 1);
    $pdf->Cell(30, 10, $requisicion['precio'], 1);
    $pdf->Cell(30, 10, '$' . number_format($montoTotal, 2), 1, 1, 'R');
}

// Calcular el total de montos totales
$totalGastos = array_sum(array_column($datosOrden, 4));

// Imprimir el total de montos totales
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(150, 10, 'Total sin IVA:', 1);
$pdf->Cell(30, 10, '$' . number_format($totalGastos, 2), 1, 1, 'R');

// Definir la fuente y el tamaño de la fuente
$pdf->SetFont('helvetica', 'A', 11);
// Encabezados de la tabla
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(180, 5, 'Notas', 1, 1, 'C', 1);
// notas que agrega el solicitante 

$pdf->Cell(180, 5, $Nota, 1, 1 ,'C', 0 );

$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->Cell(0, 10, "Proveedor seleccionado", 0, 1, 'C',0);

// Crear la tabla de gastos

$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(35, 7, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Telefono', 1, 0, 'C', 1);
$pdf->Cell(55, 7, 'Correo', 1, 0, 'C', 1);
$pdf->Cell(30, 7, 'ID_proveedor', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Estado', 1, 1, 'C', 1);

$pdf->Cell(35, 7, $nombre, 1);
$pdf->Cell(25, 7, $telefono, 1);
$pdf->Cell(55, 7, $correo, 1);
$pdf->Cell(30, 7, $idProveedor , 1);
$pdf->Cell(20, 7, $estatus , 1, 1); 

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
$pdf->Cell(0, 10, '              Solicita                                         Aprueba compras                             Autoriza direccion ', 0, 1, 'A', 0);



// Nombre del archivo y ruta proporcionados desde el controlador
$nombreArchivo = 'ordenCompra_' . $numeroUnico . '.pdf';
$rutaDescarga = 'D:/laragon/www/VamsSystem/public/ordenesCompra/' . $nombreArchivo;

$pdf->Output($rutaDescarga, 'F');