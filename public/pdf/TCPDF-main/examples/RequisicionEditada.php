<?php
require_once('tcpdf_include.php');
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Javier Chavez');
$pdf->setTitle('Requisicion ');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' requisicion de insumos ', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetFont('helvetica', '', 12); // Define the font, size, and style

$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Agregar una página
$pdf->AddPage();
$pdf->Cell(0, 10, 'Requisicion n° '.$datos['id_requisicion'] , 0, 1, 'L'); // Print the number "123" at the top left of the page
// set margins
// Definir la fuente y el tamaño de la fuente titulo
$pdf->SetFont('helvetica', 'B', 19);
// Imprimir el título del reporte

$pdf->Cell(0, 10, "Requisicion de insumos ", 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Ln(10); // Salto de línea antes de la tabla

$pdf->Cell(0, 10, "Solicitante", 0, 1, 'C',0);
// Definir la fuente y el tamaño de la fuente
$pdf->SetFont('helvetica', 'A', 11);

// Encabezados de la tabla
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(40, 5, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(40, 5, 'Departamento_perfil', 1, 0, 'C', 1);
$pdf->Cell(40, 5, 'ID del Empleado', 1, 0, 'C', 1);
$pdf->Cell(40, 5, 'Fecha', 1, 1, 'C', 1);

// Datos del empleado que realizó dicha requisición
$nombreEmpleado = $datos['nombres'];
$apepatEmpleado = $datos['apellidoP'];
$posicionEmpleado = $datos['departamento'];
$idEmpleado = $datos['usuario_id'];
$fechaEmpleado = $datos['created_at'];

$pdf->Cell(40, 5, $nombreEmpleado.' '.$apepatEmpleado, 1);
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
$pdf->Cell(20, 10, 'Cantidad', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Unidad de medida', 1, 0, 'C', 1);
$pdf->Cell(100, 10, 'Descripción', 1, 1, 'C', 1);

// Iterar sobre los datos de gastos y agregar filas a la tabla
foreach ($articulos as $articulo) {
    $montoTotal = $articulo['cantidad'] * 0; // Calcular el monto total (en este caso, se multiplica por 0, ajusta esto según tus necesidades)
    $pdf->Cell(20, 10, $articulo['cantidad'], 1);
    $pdf->Cell(40, 10, $articulo['unidad'], 1);
    $pdf->Cell(100, 10, $articulo['descripcion'], 1);
    $pdf->Ln(10); // Salto de línea antes de la tabla
}

if(!empty($unidad)){
    // Definir la fuente y el tamaño de la fuente
    $pdf->SetFont('helvetica', 'A', 11);
    // Encabezados de la tabla
    $pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
    $pdf->Cell(125, 7, 'Unidad', 1, 0, 'C', 1);
    $pdf->Cell(35, 7, 'Mantenimiento', 1, 1, 'C', 1);

    // notas que agrega el solicitante
    if ($unidad->tipo === "AUTOMOVIL" ){
        $pdf->Cell(125, 6,'N° serie: '.$unidad->n_de_serie.' - Descripcion: '.$unidad->id_unidad, 1 );
    } else {
        $pdf->Cell(125, 6,'N° serie: '.$unidad->n_de_serie.' - Descripcion: '.$unidad->n_de_permiso, 1);
    }

    $pdf->Cell(35, 6, $mantenimiento, 1, 1, 'C');

}

$pdf->Ln(10); // Salto de línea antes de la tabla
// Definir la fuente y el tamaño de la fuente
$pdf->SetFont('helvetica', 'A', 11);
// Encabezados de la tabla
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(160, 5, 'Notas', 1, 1, 'C', 1);
if($urgencia != null && $notas != null){
    // notas que agrega el solicitante
    $pdf->MultiCell(160, 5, 'Requisicion de urgencia. Fecha programada para recibir articulos: '.$fechaProgramada.'
Notas de requisicion: '. $notas, 1, 1 ,'C', 0 );
}  elseif($urgencia != null && $notas === null){
    $pdf->MultiCell(160, 5, 'Requisicion de urgencia. Fecha programada para recibir articulos: '.$fechaProgramada, 1, 1 ,'C', 0 );
} else{
   // notas que agrega el solicitante
    $pdf->MultiCell(160, 5, $notas, 1, 1 ,'C', 0 );
}

$pdf->SetY(265); // Ajusta la posición Y según tus necesidades
// Dibujar una línea

// Coordenadas iniciales y finales para los tres segmentos
$x1 = 10;
$x2 = 70;
$x3 = 130;

$y = $pdf->GetY(); // Obtener la posición Y actual

// Dibujar el primer segmento de la línea
$pdf->Line(15, $y, 65, $y);

// Dibujar el segundo segmento de la línea
$pdf->Line(75, $y, 125, $y);

// Dibujar el tercer segmento de la línea
$pdf->Line(135, $y, 185, $y);

$pdf->SetFont('helvetica', '', 11,);
$pdf->Cell(0, 7, '   Solicitante Requisicion              Encargado área (Requisicion)          Autoriza Gerencia (Cotización) ', 0, 1, 'A', 0);

/*

Si se requiere agregar otra linea para firmas, se debe de cambiar en el archivo tcpdf_config_alt.php
$pdf->SetY(275); // Ajusta la posición Y según tus necesidades
$y = $pdf->GetY(); // Obtener la posición Y actual

// Dibujar el primer segmento de la línea
$pdf->Line(15, $y, 65, $y);

// Dibujar el segundo segmento de la línea
$pdf->Line(75, $y, 125, $y);

// Dibujar el tercer segmento de la línea
$pdf->Line(135, $y, 185, $y);

$pdf->Cell(0, 7, '   Solicitante Cotización              Encargado área (Cotización )          Autoriza Gerencia (Cotización) ', 0, 1, 'A', 0);
*/


// Nombre del archivo y ruta proporcionados desde el controlador
$nombreArchivo = 'requisicion_' .$datos['id_requisicion']. '.pdf';

//$rutaDescarga = 'C:/laragon/www/VamsSystem/public/requisiciones/' . $nombreArchivo;
$rutaDescarga = 'C:/wamp64/www/VamsSystem/public/requisiciones/' . $nombreArchivo;

$pdf->Output($rutaDescarga, 'F');
