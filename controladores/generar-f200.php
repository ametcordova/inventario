<?php
//Fecha actual
date_default_timezone_set('America/Mexico_City');
setlocale(LC_ALL, 'es_MX');
$fecha= date("d-M-Y");
$date = new Datetime();
$fechahoy = strftime("%d-%h-%Y", $date->getTimestamp());
// Incluir la librería PhpSpreadsheet
//require 'vendor/autoload.php';
require_once '../extensiones/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
//use PhpOffice\PhpSpreadsheet\Worksheet\PageMargins;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Crear un nuevo objeto Spreadsheet
$spreadsheet = new Spreadsheet();

$spreadsheet
    ->getProperties()
    ->setCreator("Amet Córdova Sánchez")
    ->setLastModifiedBy('@Kordova') // última vez modificado por
    ->setTitle('Archivo F200')
    ->setSubject('Material Utilizado')
    ->setDescription('Este documento fue generado para @Kordova')
    ->setKeywords('F200, carso, Nunosco, @Kordova')
    ->setCategory('Categoría: Formato F200');
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

// Establecer estilos para el encabezado y títulos de las celdas
$estilostitulos = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
];

// Establecer estilos de los bordes de las celdas
$styleBorder = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
        ],
    ],
];

// Obtener el objeto de configuración de página de la hoja activa
$pageSetup = $spreadsheet->getActiveSheet()->getPageSetup();

// Establecer el ajuste de la impresión a una página
$pageSetup->setFitToPage(true);

// Establecer el ancho y la altura de la página
$pageSetup->setPaperSize(PageSetup::PAPERSIZE_LETTER);

// Establecer la orientación de la página
$pageSetup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

//To center a page horizontally
$spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);

// Establecer los márgenes izquierdo, derecho, superior e inferior en milímetros, Header y footer
$spreadsheet->getActiveSheet()->getPageMargins()->setHeader(0);
$spreadsheet->getActiveSheet()->getPageMargins()->setFooter(0);
$spreadsheet->getActiveSheet()->getPageMargins()->setLeft(.45);
$spreadsheet->getActiveSheet()->getPageMargins()->setRight(.45);
$spreadsheet->getActiveSheet()->getPageMargins()->setTop(.45);
$spreadsheet->getActiveSheet()->getPageMargins()->setBottom(.45);

// Seleccionar la hoja activa
$hoja = $spreadsheet->getActiveSheet();
$hoja->setTitle("F200");

// Insertar imagen en celdas A1 a D5
$dibujo = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$dibujo->setPath('../config/imagenes/logocarso.png');
$dibujo->setCoordinates('A1');
$dibujo->setOffsetX(4);
$dibujo->setOffsetY(4);
$dibujo->setHeight(75);
$dibujo->setWorksheet($hoja);

// Establecer el valor del encabezado en la celda E1
$hoja->getStyle('C1:O1')->getFont()->setBold(true)->setSize(16);
$hoja->mergeCells('C1:O1');
$hoja->getStyle('C1:O1')->getAlignment()->setVertical('center')->setHorizontal('center');  //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('C1', 'CUADRE MANUAL DE MATERIALES MONTADOS EN OBRA (F200)');

$hoja->getStyle('C3:O3')->getFont()->setBold(true)->setSize(14);
$hoja->mergeCells('C3:O3');
$hoja->getStyle('C3:O3')->getAlignment()->setVertical('center')->setHorizontal('center');  //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('C3', 'MOVIMIENTOS DE MATERIALES');

$hoja->getStyle('A5:C5')->getFont()->setBold(true)->setSize(9);
$hoja->getStyle('A5:A5')->getAlignment()->setVertical('center')->setHorizontal('center');  //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('A5', 'FECHA:');
$hoja->mergeCells('B5:C5');
$hoja->setCellValue('B5', $fechahoy);

$hoja->getStyle('D5:E5')->getFont()->setBold(true)->setSize(9);
$hoja->getStyle('D5:D5')->getAlignment()->setVertical('center');
$hoja->setCellValue('D5', 'CONTRATISTA:');
$hoja->getStyle('D5:D5')->getAlignment()->setVertical('center')->setHorizontal('left');  //CENTRADO HORIZONTAL Y VERTICAL
$hoja->mergeCells('E5:H5');
$hoja->setCellValue('E5', 'BRUNO DIAZ GORDILLO');




$hoja->getStyle('A10:O10')->getFont()->setBold(true)->setSize(10);
$hoja->getStyle('A10:O10')->getAlignment()->setVertical('center')->setHorizontal('center');  //CENTRADO HORIZONTAL Y VERTICAL

// Establecer los títulos de las celdas a partir de la celda A6
$hoja->setCellValue('A10', 'UNIDAD');
$hoja->setCellValue('B10', 'SKU');
$hoja->setCellValue('C10', 'CATALOGO AX');
$hoja->getStyle('C10')->getAlignment()->setWrapText(true);      //AJUSTAR TEXTO
$hoja->setCellValue('D10', 'DENOMINACION');

$hoja->mergeCells('E10:F10');
$hoja->setCellValue('E10', 'SALIDAS');
$hoja->mergeCells('G10:H10');
$hoja->setCellValue('G10', 'DEVOLUCIONES');

$hoja->setCellValue('I10', 'RECIBIDO');
$hoja->setCellValue('J10', 'MONTADO');
$hoja->setCellValue('K10', 'DIFERENCIA');
$hoja->setCellValue('L10', 'DEVOLUCION');
$hoja->setCellValue('M10', 'SOBRANTE');
$hoja->setCellValue('N10', 'P.U.');
$hoja->setCellValue('O10', 'ADEUDO');

$hoja->getStyle('A10:O10')->applyFromArray($estilostitulos)->applyFromArray($styleBorder);
//$hoja->getStyle('A6:L6')->applyFromArray($styleBorder);

// Establecer ancho de columnas
$hoja->getColumnDimension('A')->setWidth(7);
$hoja->getColumnDimension('B')->setWidth(7);
$hoja->getColumnDimension('C')->setWidth(10);
$hoja->getColumnDimension('D')->setWidth(45);
$hoja->getColumnDimension('E')->setWidth(10);
$hoja->getColumnDimension('F')->setWidth(10);
$hoja->getColumnDimension('G')->setWidth(10);
$hoja->getColumnDimension('H')->setWidth(10);
$hoja->getColumnDimension('I')->setWidth(10);
$hoja->getColumnDimension('J')->setWidth(12);
$hoja->getColumnDimension('K')->setWidth(10);
$hoja->getColumnDimension('L')->setWidth(12);
$hoja->getColumnDimension('M')->setWidth(10);
$hoja->getColumnDimension('N')->setWidth(10);
$hoja->getColumnDimension('O')->setWidth(10);

$hoja->getStyle('A11:O12')->applyFromArray($styleBorder);
$hoja->getStyle('A11:O20')->getFont()->setBold(false)->setSize(10);
// Rellenar la hoja de cálculo con los datos correspondientes
$hoja->setCellValue('A11', 'PZA');
$hoja->setCellValue('B11', '');
$hoja->setCellValue('C11', '000143560');
$hoja->setCellValue('D11', 'ARGOLLA P/CORD. PARALELO POSTE SOLIDO');
$hoja->setCellValue('I11', '1');
$hoja->setCellValue('J11', '1');
$hoja->setCellValue('K11', '0');
$hoja->setCellValue('L11', '0');
$hoja->setCellValue('M11', '0');

$hoja->setCellValue('A12', 'PZA');
$hoja->setCellValue('B12', '');
$hoja->setCellValue('C12', '000143670');
$hoja->setCellValue('D12', 'ARGOLLA P/CORDON DE ACOMETIDA');
$hoja->setCellValue('I12', '20');
$hoja->setCellValue('J12', '20');
$hoja->setCellValue('K12', '0');
$hoja->setCellValue('L12', '0');
$hoja->setCellValue('M12', '0');


// Guardar el archivo de Excel
$writer = new Xlsx($spreadsheet);
try {
    $writer->save('mi_archivo_excel.xlsx');
    echo 'El archivo se guardó exitosamente.';
} catch (PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
    echo 'Error al guardar el archivo: Es posible que este abierto',  $e->getMessage(), "\n";
}
// header('Content-Type: application/vnd.ms-excel');
// header('Content-Disposition: attachment;filename="salida.xlsx"');
// $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
// $writer->save('php://output');
