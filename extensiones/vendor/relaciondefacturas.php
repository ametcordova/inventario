<?php
/**
 * php -S localhost:8000 -t vendor/phpoffice/phpspreadsheet/samples
 * Ejemplo de cómo usar PDO y PHPSpreadSheet para
 * exportar datos de MySQL a Excel de manera
 * fácil, rápida y segura
 *
 * @author @KORDOVA
 *
 */
require_once "autoload.php";

require_once dirname( __DIR__ ).'../../modelos/conexion.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$valor = $_GET['ids'];
$ids=implode(",",$valor);
//$ids="31,32,33";
$id=0;

// echo sprintf('ids %s ', $ids);
// exit;

$documento = new Spreadsheet();

$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
        ],
    ],
];

$documento->getProperties()
    ->setCreator("R. AMET CÓRDOVA (NUNOSCO)")
    ->setLastModifiedBy('@KORDOVA')
    ->setTitle('Archivo exportado desde MySQL')
    ->setDescription('Generar archivo en Excel exportado desde MySQL por @KORDOVA');

    $documento->getDefaultStyle()->getFont()->setName('Century Gothic');
    
# Escribir encabezado de los productos
$encabezado = ["#", "CONTRATISTA", "NOTA DE CRÉDITO", "No. DE FACTURA", "SUBTOTAL", "TOTAL", "PACO", "ASIENTO CONTABLE", "PROYECTO", "NOMBRE ARCHIVO PDF"];

# Como ya hay una hoja por defecto, la obtenemos, no la creamos
$hoja1 = $documento->getActiveSheet();
$hoja1->setTitle("RELACION");

# El último argumento es por defecto A1 pero lo pongo para que se explique mejor
$hoja1->fromArray($encabezado, null, 'A1');

//Adding data to the excel sheet *- TAMBIEN FUNCIONA-*
// $hoja1
//     ->setCellValue('A1', '#')
//     ->setCellValue('B1', 'CONTRATISTA')
//     ->setCellValue('C1', 'NOTA DE CRÉDITO')
//     ->setCellValue('D1', 'No. DE FACTURA')
//     ->setCellValue('E1', 'SUBTOTAL')
//     ->setCellValue('F1', 'TOTAL')
//     ->setCellValue('G1', 'PACO')
//     ->setCellValue('H1', 'ASIENTO CONTABLE')
//     ->setCellValue('I1', 'PROYECTO')
//     ->setCellValue('J1', 'NOMBRE ARCHIVO');
//$hoja1->getColumnDimension('A')->setAutoSize(true);

$hoja1->getStyle('A1:J1')->getFont()->setBold(true)->setSize(10);
$hoja1->getStyle('A1:J1')->getAlignment()->setVertical('center')->setHorizontal('center');  //CENTRADO HORIZONTAL Y VERTICAL
$hoja1->getColumnDimension('A')->setWidth(20, 'px');
$hoja1->getColumnDimension('B')->setWidth(150, 'px');
$hoja1->getColumnDimension('C')->setWidth(70, 'px');
$hoja1->getStyle('C1')->getAlignment()->setWrapText(true);      //AJUSTAR TEXTO
$hoja1->getColumnDimension('D')->setWidth(73, 'px');
$hoja1->getStyle('D1')->getAlignment()->setWrapText(true);
$hoja1->getColumnDimension('E')->setWidth(72, 'px');
$hoja1->getColumnDimension('F')->setWidth(75, 'px');
$hoja1->getColumnDimension('G')->setWidth(220, 'px');
$hoja1->getColumnDimension('H')->setWidth(80, 'px');
$hoja1->getStyle('H1')->getAlignment()->setWrapText(true);      
$hoja1->getColumnDimension('I')->setWidth(82, 'px');
$hoja1->getColumnDimension('J')->setWidth(220, 'px');
$hoja1->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('bcbcbc');
$hoja1->getStyle('A1:J1')->applyFromArray($styleArray);
/**************************************************************************************** */
# Obtener los datos de la base de datoss
$consulta = "SELECT fact.id_empresa, fact.serie, fact.folio, fact.conceptos, fact.subtotal, fact.totalfactura, emp.razonsocial FROM facturaingreso fact INNER JOIN empresa emp ON fact.id_empresa=emp.id WHERE fact.id IN ($ids)";

$sentencia = Conexion::conectar()->prepare($consulta, [
    PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL,
]);
$sentencia->execute();
/**************************************************************************************** */
$hoja1->getStyle('A2:J20')->getFont()->setBold(false)->setSize(10);
$hoja1->getRowDimension('1')->setRowHeight(40, 'pt');

# Comenzamos en la 2 porque la 1 es del encabezado
$numeroDeFila = 2;
while ($facturas = $sentencia->fetchObject()) {
    $id++;
    $razonsocial = $facturas->razonsocial;
    $nofactura = $facturas->serie.'-'.$facturas->folio;
    $subtotal= $facturas->subtotal;
    $totalfactura= $facturas->totalfactura;
    $nombrearchivo = "FACT {$nofactura} {$razonsocial}";
    $datos=json_decode($facturas->conceptos,true);
    
    foreach ($datos as $key => $value) {
        $descripcion=$value['Descripcion'];
    }
    
    $oper=substr(stristr($descripcion, "BRUNO DIAZ"),0,strpos(stristr($descripcion, "BRUNO DIAZ"),"."));

    $proy=substr(stristr($descripcion,"CAR"),0,9);

    $odc=substr(stristr($descripcion, "00"),0,8);

    # Escribirlos en el documento
    $hoja1->getStyle("A".$numeroDeFila)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $hoja1->setCellValue("A".$numeroDeFila, $id);
    $hoja1->setCellValue("B".$numeroDeFila, $razonsocial);
    $hoja1->setCellValue("C".$numeroDeFila, " ");
    $hoja1->getStyle("D".$numeroDeFila)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $hoja1->setCellValue("D".$numeroDeFila, $nofactura);
    $hoja1->getStyle("E".$numeroDeFila)->getNumberFormat()->setFormatCode('#,##0.00');
    $hoja1->setCellValue("E".$numeroDeFila, $subtotal);
    $hoja1->getStyle("F".$numeroDeFila)->getNumberFormat()->setFormatCode('#,##0.00');
    $hoja1->setCellValue("F".$numeroDeFila, $totalfactura);
    $hoja1->setCellValue("G".$numeroDeFila, $oper);
    $hoja1->getStyle("H".$numeroDeFila)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $hoja1->setCellValue("H".$numeroDeFila, $odc);
    $hoja1->getStyle("I".$numeroDeFila)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $hoja1->setCellValue("I".$numeroDeFila, $proy);
    $hoja1->setCellValue("J".$numeroDeFila, $nombrearchivo);
    $hoja1->getRowDimension($numeroDeFila)->setRowHeight(16, 'pt');    
    $numeroDeFila++;
}

$hoja1->getStyle('A2:J'.($numeroDeFila-1))->getAlignment()->setVertical('center');
$hoja1->getStyle('A1:J'.($numeroDeFila-1))->applyFromArray($styleArray);

# Crear un "escritor"
//$writer = IOFactory::createWriter($documento, 'Xlsx');
# Le pasamos la ruta de guardado
//$writer->save('Exportado.xlsx');

$namefile="relaciondefacturas".date('Y-m-d_His').".xlsx";
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, 'Xlsx');

// redirect output to client browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename={$namefile}'); 
header('Cache-Control: max-age=0');

ob_start();
$writer->save('php://output');
$xlsdata = ob_get_contents();
ob_end_clean();

$response =  array(
    'op' => 'ok',
    'namefile' => $namefile,
    'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($xlsdata)
);

echo json_encode($response);        // o die(json_encode($response))

?>