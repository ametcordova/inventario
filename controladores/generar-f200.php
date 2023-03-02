<?php
//Fecha actual
date_default_timezone_set('America/Mexico_City');
setlocale(LC_ALL, 'es_MX');
$fecha= date("d-M-Y");
$date = new Datetime();
$fechahoy = strftime("%d-%h-%Y", $date->getTimestamp());

// Incluir la librería PhpSpreadsheet
require_once '../extensiones/vendor/autoload.php';
require_once dirname( __DIR__ ).'../modelos/conexion.php';

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

// Establecer el estilo del borde inferior para la celda 'x'
$styleBorderLower = [
    'borders' => [
        'bottom' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '000000'],
        ],
    ],
];

$proyecto='CAR094395';
$trabajo='BRUNO DIAZ FO-A 22022023/08 SUR2';

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

$hoja->getRowDimension(4)->setRowHeight(6);

$hoja->getStyle('A5:L5')->getFont()->setBold(true)->setSize(9);
$hoja->getStyle('A5:A5')->getAlignment()->setVertical('center')->setHorizontal('center');  //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('A5', 'FECHA:');
$hoja->getStyle('B5:C5')->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('left'); 
$hoja->mergeCells('B5:C5');
$hoja->setCellValue('B5', $fechahoy);
$hoja->getStyle('D5:D5')->getAlignment()->setVertical('center')->setHorizontal('right');  //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('D5', 'CONTRATISTA:');
$hoja->mergeCells('E5:H5');
$hoja->getStyle('E5:H5')->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('left'); 
$hoja->setCellValue('E5', 'BRUNO DIAZ GORDILLO');

// Establecer la altura de la fila 6 en 7
$hoja->getRowDimension(6)->setRowHeight(7);
// Agregar contenido a la celda A1
//$hoja->setCellValue('A6','este es');

$hoja->getStyle('K5:K5')->getAlignment()->setVertical('center')->setHorizontal('center');  //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('K5', 'PROYECTO:');
$hoja->getStyle('L5')->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('left'); 
$hoja->setCellValue('L5', $proyecto);

$hoja->getStyle('A7:L7')->getFont()->setBold(true)->setSize(9);
$hoja->getStyle('A7:A7')->getAlignment()->setVertical('center')->setHorizontal('center');  //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('A7', 'PEP:');
$hoja->mergeCells('B7:D7');
$hoja->getStyle('B7:D7')->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('left');  
$hoja->setCellValue('B7', $trabajo);
$hoja->getStyle('E7:E7')->getAlignment()->setVertical('center')->setHorizontal('right');  //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('E7', 'RUTA:');
$hoja->getStyle('F7')->applyFromArray($styleBorderLower);
$hoja->getStyle('G7:K7')->getAlignment()->setVertical('center')->setHorizontal('right');  //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('G7', 'OEI:');
$hoja->getStyle('H7')->applyFromArray($styleBorderLower);
$hoja->setCellValue('I7', 'OE:');
$hoja->getStyle('J7')->applyFromArray($styleBorderLower);
$hoja->setCellValue('K7', 'CTL/DTO:');
$hoja->getStyle('L7')->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('left');  
$hoja->setCellValue('L7', 'VARIOS');
$hoja->getRowDimension(8)->setRowHeight(6);


/*********************************************************************************** */
/* EMPIEZA LOS TITULOS DE LAS CABECERAS*/
/*********************************************************************************** */
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
$hoja->getColumnDimension('D')->setWidth(50);
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

/**************************************************************************************** */
# Obtener los datos de la base de datoss
try{
    $sql = "SELECT COUNT(*) AS cuantos FROM productos WHERE esfo=1";
    $resultado = Conexion::conectar()->prepare($sql);
    $resultado->execute();
    $datos = $resultado->fetch(PDO::FETCH_ASSOC);    
        /* Comprobar el número de filas que coinciden con la sentencia SELECT */
        if (intval($datos['cuantos'])>0){
                $consulta = "SELECT *, m.medida FROM productos p INNER JOIN medidas m ON p.id_medida=m.id WHERE esfo=1 ORDER BY p.descripcion ASC";

                // Recorrer la tabla de MySQL y agregar los datos a la hoja de Excel
                $stmt = Conexion::conectar()->prepare($consulta);
                $stmt->execute();
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

} catch (Exception $e) {
    $response =  array(
        'response' => $e->getMessage(),
        'mensaje' =>'Archivo de excel no generado.'
    );
    die(json_encode($response));
}
/**************************************************************************************** */

if (!empty($data)) {
    $row = 11;
    $cuantos=(intval($row)+intval($datos['cuantos']))-1;
    $hoja->getStyle('A11:O'.$cuantos)->applyFromArray($styleBorder);
    $hoja->getStyle('A11:O'.$cuantos)->getFont()->setBold(false)->setSize(10);
    // Rellenar la hoja de cálculo con los datos correspondientes
    foreach ($data as $rowdata) {
        $hoja->setCellValue('A'.$row, $rowdata['medida']);
        $hoja->setCellValue('B'.$row, '');
        $hoja->setCellValue('C'.$row, $rowdata['codigointerno']);
        $hoja->setCellValue('D'.$row, $rowdata['descripcion']);
        $row++;
    }
}else{
    $response =  array(
        'response' => 400,
        'mensaje' =>'No hay datos a imprimir'
    );
    die(json_encode($response));
}  

// Rellenar la hoja de cálculo con los datos correspondientes
// $hoja->setCellValue('A11', 'PZA');
// $hoja->setCellValue('B11', '');
// $hoja->setCellValue('C11', '000143560');
// $hoja->setCellValue('D11', 'ARGOLLA P/CORD. PARALELO POSTE SOLIDO');

// $hoja->setCellValue('A12', 'PZA');
// $hoja->setCellValue('B12', '');
// $hoja->setCellValue('C12', '000143670');
// $hoja->setCellValue('D12', 'ARGOLLA P/CORDON DE ACOMETIDA');


$hoja->setCellValue('I11', '1');
$hoja->setCellValue('J11', '1');
$hoja->setCellValue('K11', '0');
$hoja->setCellValue('L11', '0');
$hoja->setCellValue('M11', '0');

$hoja->setCellValue('I12', '20');
$hoja->setCellValue('J12', '20');
$hoja->setCellValue('K12', '0');
$hoja->setCellValue('L12', '0');
$hoja->setCellValue('M12', '0');


// Guardar el archivo de Excel
$writer = new Xlsx($spreadsheet);
try {
    $writer->save('mi_archivo_excel'.time().'.xlsx');
    echo 'El archivo se guardó exitosamente.';
} catch (PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
    echo 'Error al guardar el archivo: Es posible que este abierto',  $e->getMessage(), "\n";
}
// Cerrar la conexión a la base de datos MySQL
$stmt = null;
// header('Content-Type: application/vnd.ms-excel');
// header('Content-Disposition: attachment;filename="salida.xlsx"');
// $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
// $writer->save('php://output');

