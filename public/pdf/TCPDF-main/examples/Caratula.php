<?php 
require_once('tcpdf_include.php'); 
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('DIEGO CRUZ ALVAREZ');
$pdf->setTitle('Caratula de proveedor');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' Caratula Proveedor',"Transportes Vams  \nDepartamento de Compras");

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

// set margins
// Definir la fuente y el tamaño de la fuente titulo
$pdf->SetFont('helvetica', 'B', 20);
// Imprimir el título del reporte

$pdf->Cell(0, 8, "Datos de registro proveedor                      #" . $id, 0, 1, 'L');
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Ln(8); // Salto de línea antes de la tabla


$pdf->Cell(0, 8, "RAZÓN SOCIAL:", 0, 1, 'L',0);
$pdf->SetX(30); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->SetFont('helvetica', 'A', 12); // Definir la fuente y el tamaño de la fuente
$pdf->Cell(50, 8, $proveedorDatos->nombre, 0, 1, 'L',0);

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetX(16); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->Cell(0, 8, "REGIMEN FISCAL:", 0, 1, 'L',0);
$pdf->SetX(30); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->SetFont('helvetica', 'A', 12); // Definir la fuente y el tamaño de la fuente
$pdf->Cell(50, 8, $proveedorDatos->regimen_fiscal, 0, 1, 'L',0);    

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetX(16); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->Cell(0, 8, "SOBRENOMBRE:", 0, 1, 'L',0);
$pdf->SetX(30); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->SetFont('helvetica', 'A', 12); // Definir la fuente y el tamaño de la fuente
$pdf->Cell(50, 8, $proveedorDatos->sobrenombre, 0, 1, 'L',0);

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetX(16); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->Cell(0, 8, "TELEFONO:", 0, 1, 'L',0);
$pdf->SetX(30); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->SetFont('helvetica', 'A', 12); // Definir la fuente y el tamaño de la fuente
$pdf->Cell(50, 8, $proveedorDatos->telefono, 0, 1, 'L',0);

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetX(16); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->Cell(0, 8, "NOMBRE DEL CONTACTO:", 0, 1, 'L',0);
$pdf->SetX(30); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->SetFont('helvetica', 'A', 12); // Definir la fuente y el tamaño de la fuente
$pdf->Cell(50, 8, $proveedorDatos->contacto, 0, 1, 'L',0);

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetX(16); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->Cell(0, 8, "DIRECCION:", 0, 1, 'L',0);
$pdf->SetX(30); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->SetFont('helvetica', 'A', 12); // Definir la fuente y el tamaño de la fuente
$pdf->MultiCell(0,8,$proveedorDatos->domicilio,0,'L',false,1);

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetX(16); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->Cell(0, 8, "DOMICILIO FISCAL:", 0, 1, 'L',0);
$pdf->SetX(30); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->SetFont('helvetica', 'A', 12); // Definir la fuente y el tamaño de la fuente
$pdf->MultiCell(0, 8, $proveedorDatos->domicilio, 0, 'L',false,1);

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetX(16); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->Cell(0, 8, "RFC:", 0, 1, 'L',0);
$pdf->SetX(30); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->SetFont('helvetica', 'A', 12); // Definir la fuente y el tamaño de la fuente
$pdf->Cell(50, 8, $proveedorDatos->rfc, 0, 1, 'L',0);

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetX(16); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->Cell(0, 8, "CORREO:", 0, 1, 'L',0);
$pdf->SetX(30); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->SetFont('helvetica', 'A', 12); // Definir la fuente y el tamaño de la fuente
$pdf->Cell(50, 8, $proveedorDatos->correo, 0, 1, 'L',0);

$pdf->Ln(8); // Salto de línea antes de la sección de datos bancarios
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 8, "DATOS BANCARIOS", 0, 1, 'C');

$pdf->SetLineWidth(0.5);

$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());

$pdf->SetLineWidth(0.2); // Regresar al grosor normal
$pdf->Ln(5); // Salto de línea antes de los datos bancarios

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetX(16); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->Cell(0, 8, "BANCO:", 0, 1, 'L',0);
$pdf->SetX(30); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->SetFont('helvetica', 'A', 12); // Definir la fuente y el tamaño de la fuente
$pdf->Cell(50, 8, $proveedorDatos->banco, 0, 1, 'L',0);

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetX(16); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->Cell(0, 8, "NUMERO DE CUENTA:", 0, 1, 'L',0);
$pdf->SetX(30); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->SetFont('helvetica', 'A', 12); // Definir la fuente y el tamaño de la fuente
$pdf->Cell(50, 8, $proveedorDatos->n_cuenta, 0, 1, 'L',0);

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetX(16); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->Cell(0, 8, "NUMERO DE CUENTA CLABE:", 0, 1, 'L',0);
$pdf->SetX(30); // Mueve el cursor 30 mm desde el margen izquierdo
$pdf->SetFont('helvetica', 'A', 12); // Definir la fuente y el tamaño de la fuente
$pdf->Cell(50, 8, $proveedorDatos->n_cuenta_clabe, 0, 1, 'L',0);

// Nombre del archivo y ruta proporcionados desde el controlador
$nombreArchivo = 'caratula_' . $nombreEmpresa . '.pdf';

//$rutaDescarga = 'C:/laragon/www/VamsSystem/public/caratulasProv/' . $nombreArchivo;
$rutaDescarga = 'C:/wamp64/www/VamsSystem/public/caratulasProv/'. $nombreArchivo;

// Generar el PDF
$pdf->Output($rutaDescarga, 'F');
