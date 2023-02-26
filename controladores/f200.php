<?php
// Incluir la librería PhpSpreadsheet
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Crear una instancia de la hoja de cálculo
$spreadsheet = new Spreadsheet();

// Seleccionar la hoja activa
$sheet = $spreadsheet->getActiveSheet();

// Añadir los títulos a las celdas
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'DESCRIPCIÓN');
$sheet->setCellValue('C1', 'CANTIDAD');
$sheet->setCellValue('D1', 'EXISTENCIA');

// Ajustar el ancho de las columnas
$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);

// Rellenar la hoja de cálculo con los datos correspondientes
$sheet->setCellValue('A2', '1');
$sheet->setCellValue('B2', 'Producto 1');
$sheet->setCellValue('C2', '5');
$sheet->setCellValue('D2', '10');

$sheet->setCellValue('A3', '2');
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
