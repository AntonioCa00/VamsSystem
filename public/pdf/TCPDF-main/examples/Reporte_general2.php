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


// Obtener los datos de tutulos, subtitulos y fechas 
$tituloReporte = "Reporte general de gastos anual ";
$fechaReporte = "Enero - Diciembre 2023"; 
$subtitulo_empleado = "Datos Solicitante";
$subtitulo_unidad = "Datos unidades";
$Subtitulo_registros = "Registro de compras";
$Subtitulo_Encargado = "Lista de encargados";
$Subtitulo_refacciones = "Refacciones en stock";

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

// Imprimir el subtutitulo 
$pdf->Cell(0, 10, $Subtitulo_registros, 0, 1, 'A');
// Crear la tabla de gastos
$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(20, 7, 'Id Compra', 1, 0, 'C', 1);
$pdf->Cell(35, 7, 'Administrador', 1, 0, 'C', 1);
$pdf->Cell(40, 7, 'Fecha', 1, 0, 'C', 1);
$pdf->Cell(40, 7, 'Unidad', 1, 0, 'C', 1);
$pdf->Cell(40, 7, 'Costo total', 1, 1, 'C', 1);
$datosGastos = $compras;

// Iterar sobre los datos de gastos y agregar filas a la tabla
foreach ($datosGastos as $gasto) {
    $pdf->Cell(20, 7, $gasto->id_orden, 1);
    $pdf->Cell(35, 7, $gasto->nombre, 1);
    $pdf->Cell(40, 7, $gasto->created_at, 1);
    $pdf->Cell(40, 7, $gasto->unidad_id, 1);    
    $pdf->Cell(40, 7, '$' . number_format($gasto->costo_total, 2), 1, 1, 'R');
}

// Calcular el total de gastos
$totalGastos = $datosGastos->sum('costo_total');

// Imprimir el total de gastos
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(135, 7, 'Total de Gastos:', 1);
$pdf->Cell(40, 7, '$' . number_format($totalGastos, 2), 1, 1, 'R');

// Agregar gráficos, comentarios, conclusiones, recomendaciones, etc., según sea necesario

$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->Cell(0, 10, $subtitulo_unidad, 0, 1, 'A');

// Crear la tabla de gastos

$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(25, 7, 'Matricula', 1, 0, 'C', 1);
$pdf->Cell(25, 7, 'Tipo', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Año', 1, 0, 'C', 1);
$pdf->Cell(30, 7, 'Marca', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Estado', 1, 1, 'C', 1);

$datosUnidades = $unidades;
foreach ($datosUnidades as $datos) {
    $pdf->Cell(25, 7, $datos->id_unidad, 1);
    $pdf->Cell(25, 7, $datos->tipo, 1);
    $pdf->Cell(20, 7, $datos->anio_unidad, 1);
    $pdf->Cell(30, 7, $datos->marca, 1);
    $pdf->Cell(20, 7, $datos->estado, 1, 1); 
	    
}
$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, $Subtitulo_Encargado, 0, 1, 'A');

// Crear la tabla de encargados
$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(20, 7, 'ID_Usuario', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Rol', 1, 0, 'C', 1);
$pdf->Cell(45, 7, 'Departamento', 1, 0, 'C', 1);
$pdf->Cell(35, 7, 'Fecha_registro', 1, 1, 'C', 1);

$datosEncargado = $usuarios;
foreach ($datosEncargado as $datosE) {
    $pdf->Cell(20, 7, $datosE->id, 1);
    $pdf->Cell(20, 7, $datosE->nombre, 1);
    $pdf->Cell(20, 7, $datosE->rol, 1);
    if ($datosE->departamento){
        $pdf->Cell(45, 7, $datosE->departamento, 1);
    } else { 
        $pdf->Cell(45, 7, 'No asiganado a departamento', 1);     
    }
    $pdf->Cell(35, 7, $datosE->created_at, 1, 1); 
	    
}
$pdf->Ln(10); // Salto de línea antes de la tabla
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, $Subtitulo_refacciones, 0, 1, 'A');
// Crear la tabla de gastos
$pdf->SetFont('helvetica', '', 9);
$pdf->SetFillColor(240, 240, 240); // Color de fondo de la cabecera de la tabla
$pdf->Cell(20, 7, 'Id_refaccion', 1, 0, 'C', 1);
$pdf->Cell(38, 7, 'Nombre', 1, 0, 'C', 1);
$pdf->Cell(20, 7, 'Modelo', 1, 0, 'C', 1);
$pdf->Cell(15, 7, 'Año', 1, 0, 'C', 1);
$pdf->Cell(15, 7, 'Marca', 1, 0, 'C', 1);
$pdf->Cell(60, 7, 'Descripcion', 1, 0, 'C', 1);
$pdf->Cell(10, 7, 'Stock ', 1, 1, 'C', 1);

$datosStock = $refacciones;

// Iterar sobre los datos de gastos y agregar filas a la tabla
foreach ($datosStock as $gastoR) {
    $pdf->Cell(20, 7, $gastoR->id_refaccion, 1);
    $pdf->Cell(38, 7, $gastoR->nombre, 1);
    $pdf->Cell(20, 7, $gastoR->modelo, 1);
    $pdf->Cell(15, 7, $gastoR->anio, 1);
    $pdf->Cell(15, 7, $gastoR->marca, 1); 
	$pdf->Cell(60, 7, $gastoR->descripcion, 1); 
	$pdf->Cell(10, 7, $gastoR->stock, 1, 1);
	  
}


// Generar el PDF
$pdf->Output('reporte_mensual.pdf', 'I');
