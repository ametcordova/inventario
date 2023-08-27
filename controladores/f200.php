<?php
// Incluir la librería PhpSpreadsheet
//require 'vendor/autoload.php';
require_once '../extensiones/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Crear una instancia de la hoja de cálculo
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

// Seleccionar la hoja activa
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("F200");

// Establecer el valor del encabezado en la celda A1
$sheet->mergeCells('A1:J1');
$sheet->setCellValue('A1', 'CUADRE MANUAL DE MATERIALES MONTADOS EN OBRA (F200)');

// Establecer los títulos de las celdas a partir de la celda A2
$sheet->setCellValue('A2', 'ID');
$sheet->setCellValue('B2', 'DESCRIPCION');
$sheet->setCellValue('C2', 'UNIDAD');
$sheet->setCellValue('D2', 'EXISTENCIA');

// Establecer estilos para el encabezado y títulos de las celdas
$estilos = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
];
$sheet->getStyle('A1:J1')->applyFromArray($estilos);
$sheet->getStyle('A2:D2')->applyFromArray($estilos);

// Ajustar el ancho de las columnas
$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);

// Rellenar la hoja de cálculo con los datos correspondientes
$sheet->setCellValue('A3', '1');
$sheet->setCellValue('B3', 'Producto 2');
$sheet->setCellValue('C3', '2');
$sheet->setCellValue('D3', '15');

$sheet->setCellValue('A4', '3');
$sheet->setCellValue('B4', 'Producto 3');
$sheet->setCellValue('C4', '1');
$sheet->setCellValue('D4', '20');

// Crear un objeto Writer para guardar el archivo
$writer = new Xlsx($spreadsheet);

// Guardar el archivo en el disco duro
$writer->save('hoja-de-excel.xlsx');
?>
