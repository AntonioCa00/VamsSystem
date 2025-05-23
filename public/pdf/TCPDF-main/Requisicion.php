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

$pdf->Cell(0, 10, "Requisicion de unsumos ", 0, 1, 'C');
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

// Datos del empleado (simulados)
$nombreEmpleado = "Juan Pérez";
$posicionEmpleado = "Encargado_Taller";
$idEmpleado = "12345";
$fechaEmpleado = "2023-09-21";

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
$datosRequisicion = [
    [100, "Camisas y blusas manga larga marca BIBO", 370.00, 250.00, 0.00], // Agregar una columna para el monto total
    [100, "Playera polo Eurocotton", 135, 135.00, 0.00], // Agregar una columna para el monto total
    [200, "bordado logo vams chico frente", 45.00, 800.00, 0.00], // Agregar una columna para el monto total
    // ... Agregar más filas de gastos según sea necesario
];

// Iterar sobre los datos de gastos y agregar filas a la tabla
foreach ($datosRequisicion as &$requisicion) { // Usar '&' para modificar el valor en el arreglo original
    $montoTotal = $requisicion[0] * $requisicion[2]; // Calcular el monto total
    $requisicion[4] = $montoTotal; // Almacenar el monto total en el arreglo
    $pdf->Cell(20, 10, $requisicion[0], 1);
    $pdf->Cell(100, 10, $requisicion[1], 1);
    $pdf->Cell(30, 10, $requisicion[2], 1);
    $pdf->Cell(30, 10, '$' . number_format($montoTotal, 2), 1, 1, 'R');
}

// Calcular el total de montos totales
$totalGastos = array_sum(array_column($datosRequisicion, 4));

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
$Notas = "Se solicita una respuesta para antes de el dia 3 de noviembre";

$pdf->Cell(180, 5, $Notas, 1, 1 ,'C', 0 );


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



// Generar el PDF
$pdf->Output('requisicion.pdf', 'I');
