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
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' orden de compra                                                    '."Requisicion #". $rid, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(10, PDF_MARGIN_TOP, 10);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Agregar una página
$pdf->AddPage();
// set margins
// Definir la fuente y el tamaño de la fuente titulo
$pdf->SetFont('helvetica', 'B', 19);
// Imprimir el título del reporte

$pdf->Cell(0, 10, "Orden de compra #".$idnuevaorden, 0, 1, 'C');
$pdf->Ln(3); // Salto de línea antes de la tabla
// Definir la fuente y el tamaño de la fuente
$pdf->SetFont('helvetica', 'A', 11);

// Encabezados de la tabla
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(60, 5, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(40, 5, 'Area', 1, 0, 'C', 1);
$pdf->Cell(40, 5, 'Usuario', 1, 0, 'C', 1);
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

$pdf->Cell(60, 5, $nombreEmpleado.' '.$apepatEmpleado, 1);
$pdf->Cell(40, 5, $posicionEmpleado, 1, 0, 'C',0);
$pdf->Cell(40, 5, $idEmpleado, 1, 0, 'C',0);
$pdf->Cell(40, 5, $fechaEmpleado, 1, 1, 'C');
$pdf->Ln(3); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);

// Imprimir el subtutitulo
$pdf->Cell(0, 10, "Articulos", 0, 1, 'C',0);
// Crear la tabla de gastos
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla

$pdf->Cell(10, 10, 'Cant', 1, 0, 'C', 1);
$pdf->Cell(20, 10, 'Medida', 1, 0, 'C', 1);
$pdf->Cell(110, 10, 'Descripción', 1, 0, 'C', 1);
$pdf->Cell(25, 10, 'Precio_unitario', 1, 0, 'C', 1);
$pdf->Cell(25, 10, 'Total bruto', 1, 1, 'C', 1);

// Iterar sobre los datos de gastos filtrados y agregar filas a la tabla
foreach ($articulosFiltrados as &$articulo) {
    $montoTotal = $articulo['cantidad'] * $articulo['precio_unitario'];
    $articulo['monto_total'] = $montoTotal;
    $pdf->Cell(10, 10, $articulo['cantidad'], 1, 0, 'C', 0);
    $pdf->Cell(20, 10, $articulo['unidad'], 1, 0, 'C', 0);
    $pdf->Cell(110, 10, $articulo['descripcion'], 1);
    $pdf->Cell(25, 10, '$' . number_format($articulo['precio_unitario'], 2), 1);
    $pdf->Cell(25, 10, '$' . number_format($montoTotal, 2), 1, 1, 'R');
}

// Calcular el total de montos totales dentro del bucle
$totalGastos = array_sum(array_column($articulosFiltrados, 'monto_total'));

if (!empty($descuento)){
    $totalGastos = $totalGastos - $descuento;
    // Imprimir el total de montos totales
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(155, 10, 'Descuento:', 1);
    $pdf->Cell(25, 10, '$' . number_format($descuento , 2), 1, 1, 'R');
}

// Imprimir el total de montos totales
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(165, 10, 'Subtotal (IVA/ Retenciones no incluido):', 1);
$pdf->Cell(25, 10, '$' . number_format($totalGastos , 2), 1, 1, 'R');

if(!empty($unidad)){
    // Definir la fuente y el tamaño de la fuente
    $pdf->SetFont('helvetica', 'A', 11);
    // Encabezados de la tabla
    $pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
    $pdf->Cell(140, 7, 'Unidad', 1, 0, 'C', 1);
    $pdf->Cell(50, 7, 'Tipo de Mantenimiento', 1, 1, 'C', 1);

    // notas que agrega el solicitante
    $pdf->Cell(50, 6,'N° economico: '.$unidad->Numero_ec, 1 );
    $pdf->Cell(90, 6,'N° serie: '.$unidad->n_de_serie, 1 );

    $pdf->Cell(50, 6, $mantenimiento, 1, 1,'C');

}

$pdf->Ln(5); // Salto de línea antes de la tabla
// Definir la fuente y el tamaño de la fuente
$pdf->SetFont('helvetica', 'A', 11);
// Encabezados de la tabla
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(165, 7, 'Notas', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Cuenta Pago', 1, 1 ,'C', 1);
// notas que agrega el solicitante
$pdf->Cell(165, 7, $Nota, 1, 0 , 0 );
$pdf->Cell(25, 7, '7865', 1, 1 ,'C', 0 );

$pdf->Ln(5); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 10, "Proveedor seleccionado", 0, 1, 'C',0);

// Crear la tabla de gastos
$pdf->Cell(95, 7, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(95, 7, 'Sobrenombre', 1, 1, 'C', 1);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla

// Obtener la altura de la celda según el contenido
$anchoColumna = 95;
// Altura mínima de la celda
$altoMinimo = 7;

// Calcular la altura de la celda para el nombre y sobrenombre
$alturaNombre = $pdf->getStringHeight($anchoColumna, $datosProveedor->nombre);
$alturaSobrenombre = $pdf->getStringHeight($anchoColumna, $datosProveedor->sobrenombre);

// La fila nunca será menor de 7 mm
$alturaFila = max($altoMinimo, $alturaNombre, $alturaSobrenombre);

// Obtener la posición actual
$x = $pdf->GetX();
$y = $pdf->GetY();

// Imprimir el nombre y sobrenombre en celdas separadas
$pdf->MultiCell($anchoColumna, $alturaFila, $datosProveedor->nombre, 1, 'L', false, 0, $x, $y);
$pdf->MultiCell($anchoColumna, $alturaFila, $datosProveedor->sobrenombre, 1, 'L', false, 1, $x + $anchoColumna, $y);
$pdf->Cell(35, 7, 'Telefono', 1, 0, 'C', 1);
$pdf->Cell(95, 7, 'Nombre del contacto', 1, 0, 'C', 1);
$pdf->Cell(60, 7, 'RFC', 1, 1, 'C', 1);
$pdf->Cell(35, 7, $datosProveedor->telefono, 1);
$pdf->Cell(95, 7, $datosProveedor->contacto, 1);
$pdf->Cell(60, 7, $datosProveedor->rfc , 1,1);
$pdf->SetFont('helvetica', 'B', 10);

// Altura por fila
$alturaFila = 7;

// Calcular cuántas filas ocupará el bloque bancario
$filas = 1; // DATOS BANCARIOS DEL PROVEEDOR

if ($tipoPago == 1) {

    $filas += 1; // Pago con tarjeta

} else {

    if (
        !empty($datosProveedor->banco) &&
        (!empty($datosProveedor->n_cuenta) || !empty($datosProveedor->n_cuenta_clabe))
    ) {
        $filas += 4;
    } else {
        $filas += 1;
    }
}


// ==========================================
// RESERVAR ESPACIO PARA LAS FIRMAS
// ==========================================

// Las firmas comenzarán 35 mm antes del final
$posicionFirmas = $pdf->getPageHeight() - 35;

// Espacio de seguridad entre contenido y firmas
$espacioSeguridad = 8;

// Límite donde puede llegar el contenido
$limiteContenido = $posicionFirmas - $espacioSeguridad;


// ==========================================
// VALIDAR SI LOS DATOS BANCARIOS CABEN
// ==========================================

$alturaBloqueBancario = $filas * $alturaFila;

if ($pdf->GetY() + $alturaBloqueBancario > $limiteContenido) {
    $pdf->AddPage();
}


// ==========================================
// DATOS BANCARIOS
// ==========================================

$pdf->Cell(190, 7, 'DATOS BANCARIOS DEL PROVEEDOR', 1, 1, 'C', 1);

$pdf->SetFont('helvetica', '', 10);

if ($tipoPago == 1) {
    // Pago con tarjeta
    $pdf->Cell(180,7,'Pago con tarjeta',1,1,'C',1);
} else {

    if (
        !empty($datosProveedor->banco) &&
        (!empty($datosProveedor->n_cuenta) || !empty($datosProveedor->n_cuenta_clabe))
    ) {

        $pdf->Cell(65, 7, 'Banco:', 1, 0, 'C', 1);
        $pdf->Cell(60, 7, 'Número de cuenta', 1, 0, 'C', 1);
        $pdf->Cell(65, 7, 'Número de cuenta clabe', 1, 1, 'C', 1);
        $pdf->Cell(65, 7, $datosProveedor->banco, 1);
        $pdf->Cell(60, 7, $datosProveedor->n_cuenta, 1);
        $pdf->Cell(65, 7, $datosProveedor->n_cuenta_clabe, 1, 1);
        $pdf->Cell(65,7,'Condicion de pago: ' . $condiciones,1,0,'C',1);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(125,7,'Dia de pago acordado: ' . $dias,1,1,'C',1);
        $pdf->SetFont('helvetica', '', 10);
    } else {
        $pdf->Cell(180,7,'No se han cargado los datos bancarios de este proveedor',1,1,'C',1);
    }
}

// ==========================================
// FIRMAS AL FINAL DE LA HOJA
// ==========================================

$pdf->SetY(-38);

$y = $pdf->GetY();

$pdf->Line(15, $y, 65, $y);
$pdf->Line(80, $y, 135, $y);
$pdf->Line(145, $y, 195, $y);

$pdf->SetFont('helvetica', '', 9);

$pdf->SetY($y + 2);

$pdf->Cell(65, 10, 'Compras', 0, 0, 'C');
$pdf->Cell(15, 10, '', 0, 0, 'C');
$pdf->Cell(40, 10, 'Contabilidad', 0, 0, 'C');
$pdf->Cell(10, 10, '', 0, 0, 'C');
$pdf->Cell(65, 10, 'Gerente general', 0, 1, 'C');

// Nombre del archivo y ruta proporcionados desde el controlador
$nombreArchivo = 'ordenCompra_' . $idnuevaorden. '.pdf';

//$rutaDescarga = 'C:/laragon/www/VamsSystem/public/ordenesCompra/' . $nombreArchivo;
$rutaDescarga = 'C:/wamp64/www/VamsSystem/public/ordenesCompra/'. $nombreArchivo;

$pdf->Output($rutaDescarga, 'F');
