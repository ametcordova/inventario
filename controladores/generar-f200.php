<?php
//Fecha actual
date_default_timezone_set('America/Mexico_City');
setlocale(LC_ALL, 'es_MX');
$fecha = date("d-M-Y");
$date = new Datetime();
$fechahoy = strftime("%d-%h-%Y", $date->getTimestamp());

//TRAEMOS LA INFORMACIÓN
$POST = json_decode(file_get_contents('php://input'), true);
$tabla = "tabla_os";
$campo = "factura";
//$valor = 'A441';
if (isset($POST["factura"]) && !empty($POST["factura"])) {
    $factura = strtoupper(trim($POST['factura']));
} else {
    $response = array(
        'respuesta' => http_response_code(400),
        'mensaje' => 'Sin número de Factura detectada',
    );
    die(json_encode($response));
}

// Incluir la librería PhpSpreadsheet y demas
require_once dirname(__DIR__) . '/extensiones/vendor/autoload.php';
require_once dirname(__DIR__) . '/modelos/conexion.php';
require_once dirname(__DIR__) . '/controladores/adminoservicios.controlador.php';
require_once dirname(__DIR__) . '/modelos/adminoservicios.modelo.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
//use PhpOffice\PhpSpreadsheet\Worksheet\PageMargins;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
];

// Establecer estilos de los bordes de las celdas
//'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, borde grueso
$styleAllBorder = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

$styleExtBorder = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '000000'],
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
$spreadsheet->getActiveSheet()->getPageMargins()->setLeft(.30);
$spreadsheet->getActiveSheet()->getPageMargins()->setRight(.35);
$spreadsheet->getActiveSheet()->getPageMargins()->setTop(.40);
$spreadsheet->getActiveSheet()->getPageMargins()->setBottom(.40);

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

// nombre del formato en la celda A1
$hoja->getRowDimension(1)->setRowHeight(10);
$hoja->getStyle('A1:T1')->getFont()->setBold(false)->setSize(8);
$hoja->mergeCells('A1:T1');
$hoja->getStyle('A1:T1')->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('A1', 'FR-PE-56');

// Establecer el valor del encabezado en la celda E1
$fila = 2;
$hoja->getRowDimension($fila)->setRowHeight(12);
$hoja->getStyle('C2:Q3')->getFont()->setBold(true)->setSize(16);
$hoja->mergeCells('C2:Q3');
$hoja->getStyle('C2:Q3')->getAlignment()->setVertical('center')->setHorizontal('center'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('C2', 'CUADRE MANUAL DE MATERIALES MONTADOS EN OBRA (F200)');

$hoja->getStyle('R' . $fila)->getFont()->setBold(false)->setSize(8);
$hoja->getStyle('R' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('R' . $fila, 'SEMANA:');
$fila += 1;
$hoja->getRowDimension($fila)->setRowHeight(12);
$hoja->getStyle('R' . $fila)->getFont()->setBold(false)->setSize(8);
$hoja->getStyle('R' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('R' . $fila, 'CONSECUTIVO:');

$fila = 4;
$hoja->getRowDimension($fila)->setRowHeight(12);
$hoja->getStyle('C' . $fila . ':Q' . $fila)->getFont()->setBold(true)->setSize(14);
$hoja->mergeCells('C' . $fila . ':Q' . $fila);
$hoja->getStyle('C' . $fila . ':Q' . $fila)->getAlignment()->setVertical('center')->setHorizontal('center'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('C' . $fila, 'MOVIMIENTOS DE MATERIALES');

$hoja->getStyle('R' . $fila)->getFont()->setBold(false)->setSize(8);
$hoja->getStyle('R' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('R' . $fila, 'FACTURA:');

/******************************************************************************* */
//$hoja->getRowDimension(3)->setRowHeight(9);
//$hoja->getRowDimension(4)->setRowHeight(6);
/******************************************************************************* */

$fila = 5;
$hoja->getRowDimension($fila)->setRowHeight(12);
$hoja->getStyle('R' . $fila)->getFont()->setBold(false)->setSize(8);
$hoja->getStyle('R' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('R' . $fila, 'OC:');

$hoja->getStyle('R2:T5')->applyFromArray($styleExtBorder);

$hoja->getStyle('R2:R' . $fila)->getAlignment()->setVertical('center')->setHorizontal('left');
$hoja->getStyle('R2:R' . $fila)->getFont()->setBold(true)->setSize(8); //NEGRITAS PARA EL CUADRO CON DATOS DEL F200
/******************************************************************************* */
// SUBTITULOS
/******************************************************************************* */
$fila += 1;
$hoja->getRowDimension($fila)->setRowHeight(16);
$hoja->getStyle('A' . $fila . ':T' . $fila)->getFont()->setBold(true)->setSize(9);
$hoja->getStyle('A' . $fila . ':L' . $fila)->getAlignment()->setVertical('center')->setHorizontal('center'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('A' . $fila, 'FECHA:');
$hoja->getStyle('B' . $fila . ':C' . $fila)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('left');
$hoja->mergeCells('B' . $fila . ':C' . $fila);
$hoja->setCellValue('B' . $fila, $fechahoy);
$hoja->getStyle('D' . $fila . ':D' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('D' . $fila, 'CONTRATISTA:');
$hoja->mergeCells('E' . $fila . ':H' . $fila);
$hoja->getStyle('E' . $fila . ':H' . $fila)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('left');
$hoja->setCellValue('E' . $fila, 'BRUNO DIAZ GORDILLO');
$hoja->mergeCells('N' . $fila . ':O' . $fila);
$hoja->getStyle('N' . $fila . ':O' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('N' . $fila, 'PROYECTO:');
$hoja->getStyle('P' . $fila)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('left');

// Agregar contenido a la celda A1
//$hoja->setCellValue('A6','este es');

$fila += 1;
$hoja->getRowDimension($fila)->setRowHeight(16);
$hoja->getStyle('A' . $fila . ':T' . $fila)->getFont()->setBold(true)->setSize(9);
$hoja->getStyle('A' . $fila . ':A' . $fila)->getAlignment()->setVertical('center')->setHorizontal('center'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('A' . $fila, 'PEP:');
$hoja->mergeCells('B' . $fila . ':D' . $fila);
$hoja->getStyle('B' . $fila . ':D' . $fila)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('left');
$hoja->getStyle('E' . $fila . ':E' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('E' . $fila, 'RUTA:');
$hoja->getStyle('F' . $fila)->applyFromArray($styleBorderLower);
$hoja->getStyle('G' . $fila . ':K' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('H' . $fila, 'OEI:');
$hoja->getStyle('I' . $fila)->applyFromArray($styleBorderLower);
$hoja->setCellValue('K' . $fila, 'OE:');
$hoja->getStyle('L' . $fila)->applyFromArray($styleBorderLower);
$hoja->mergeCells('N' . $fila . ':O' . $fila);
$hoja->getStyle('N' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('N' . $fila, 'CTL/DTO:');
$hoja->getStyle('P' . $fila)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('left');
$hoja->setCellValue('P' . $fila, 'VARIOS');
$fila += 1;
$hoja->getRowDimension($fila)->setRowHeight(6);

/*********************************************************************************** */
/* EMPIEZA LOS TITULOS DE LAS CABECERAS*/
/*********************************************************************************** */
$fila = 9;
$hoja->getStyle('A' . $fila . ':T' . intval($fila+1))->getFont()->setBold(true)->setSize(8);
$hoja->getStyle('A' . $fila . ':T' . $fila)->getAlignment()->setVertical('center')->setHorizontal('center'); //CENTRADO HORIZONTAL Y VERTICAL

// Establecer los títulos de las celdas a partir de la celda A6
$hoja->mergeCells('A' . $fila . ':A' . intval($fila+1));
$hoja->setCellValue('A' . $fila, 'UNIDAD');
$hoja->mergeCells('B' . $fila . ':B' . intval($fila+1));
$hoja->setCellValue('B' . $fila, '');
$hoja->getStyle('C'. $fila)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$hoja->mergeCells('C' . $fila . ':C' . intval($fila+1));
$hoja->setCellValue('C' . $fila, 'CATALOGO AX');
$hoja->getStyle('C' . $fila)->getAlignment()->setWrapText(true); //AJUSTAR TEXTO
$hoja->mergeCells('D' . $fila . ':D' . intval($fila+1));
$hoja->setCellValue('D' . $fila, 'DENOMINACION');

$hoja->getStyle('E'. $fila . ':L'. intval($fila+1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$hoja->mergeCells('E' . $fila . ':J' . $fila);
$hoja->setCellValue('E' . $fila, 'SALIDAS');
$hoja->mergeCells('E' . intval($fila+1) . ':J' . intval($fila+1));
$hoja->setCellValue('E' . intval($fila+1), 'DIARIOS DE AX');

$hoja->mergeCells('K' . $fila . ':M' . intval($fila+1));
$hoja->setCellValue('K' . $fila, 'DEVOLUCIONES');
$hoja->mergeCells('N' . $fila . ':N' . intval($fila+1));
$hoja->setCellValue('N' . $fila, 'RECIBIDO');
$hoja->mergeCells('O' . $fila . ':O' . intval($fila+1));
$hoja->setCellValue('O' . $fila, 'MONTADO');
$hoja->mergeCells('P' . $fila . ':P' . intval($fila+1));
$hoja->setCellValue('P' . $fila, 'DIFERENCIA');
$hoja->mergeCells('Q' . $fila . ':Q' . intval($fila+1));
$hoja->setCellValue('Q' . $fila, 'DEVOLUCION');
$hoja->mergeCells('R' . $fila . ':R' . intval($fila+1));
$hoja->setCellValue('R' . $fila, 'SOBRANTE');
$hoja->mergeCells('S' . $fila . ':S' . intval($fila+1));
$hoja->setCellValue('S' . $fila, 'P.U.');
$hoja->mergeCells('T' . $fila . ':T' . intval($fila+1));
$hoja->setCellValue('T' . $fila, 'ADEUDO');

$hoja->getStyle('A' . $fila . ':T' . intval($fila+1))->applyFromArray($estilostitulos)->applyFromArray($styleAllBorder);
//$hoja->getStyle('A6:L6')->applyFromArray($styleBorder);

// Establecer ancho de columnas
$hoja->getColumnDimension('A')->setWidth(6);        //UNIDAD
$hoja->getColumnDimension('B')->setWidth(6);        //SKU
$hoja->getColumnDimension('C')->setWidth(9);       //CATALOGO AX
$hoja->getColumnDimension('D')->setWidth(45);       //DENOMINACION
$hoja->getColumnDimension('E')->setWidth(6);       //PZA
$hoja->getColumnDimension('F')->setWidth(6);       //''
$hoja->getColumnDimension('G')->setWidth(6);        //''
$hoja->getColumnDimension('H')->setWidth(6);        //''
$hoja->getColumnDimension('I')->setWidth(6);       //''
$hoja->getColumnDimension('J')->setWidth(5.5);       //''
$hoja->getColumnDimension('K')->setWidth(6);       //''
$hoja->getColumnDimension('L')->setWidth(6);       //''
$hoja->getColumnDimension('M')->setWidth(5.5);       //''
$hoja->getColumnDimension('N')->setWidth(7);
$hoja->getColumnDimension('O')->setWidth(7);
$hoja->getColumnDimension('P')->setWidth(9);
$hoja->getColumnDimension('Q')->setWidth(9);
$hoja->getColumnDimension('R')->setWidth(8);
$hoja->getColumnDimension('S')->setWidth(5.5);
$hoja->getColumnDimension('T')->setWidth(7);

/**************************************************************************************** */
# Obtener los datos de la base de datoss
try {
    $sql = "SELECT COUNT(*) AS cuantos FROM productos WHERE esfo=1 AND listar=1 AND estado=1";
    $resultado = Conexion::conectar()->prepare($sql);
    $resultado->execute();
    $datos = $resultado->fetch(PDO::FETCH_ASSOC);
    /* Comprobar el número de filas que coinciden con la sentencia SELECT */
    if (intval($datos['cuantos']) > 0) {
        $consulta = "SELECT p.id, p.codigointerno, p.descripcion, p.sku, m.medida FROM productos p INNER JOIN medidas m ON p.id_medida=m.id WHERE esfo=1 AND listar=1 AND estado=1 ORDER BY p.descripcion ASC";

        // Recorrer la tabla de MySQL y agregar los datos a la hoja de Excel
        $stmt = Conexion::conectar()->prepare($consulta);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (Exception $e) {
    $response = array(
        'respuesta' => $e->getMessage(),
        'mensaje' => 'Archivo de excel no generado.',
    );
    die(json_encode($response));
}
/**************************************************************************************** */
/* SI EXISTEN DATOS SE CREA EL LLENADO DEL FORMATO */
/**************************************************************************************** */
if (!empty($data)) {
    $row = $fila + 2;
    $idproductos = array();
    $cuantos = (intval($row) + intval($datos['cuantos'])) - 1;
    $hoja->getStyle('A' . $row . ':T' . $cuantos)->applyFromArray($styleAllBorder);
    $hoja->getStyle('A' . $row . ':T' . $cuantos)->getFont()->setBold(false)->setSize(9);
    // Rellenar la hoja de cálculo con los datos correspondientes
    foreach ($data as $rowdata) {
        //$hoja->setCellValue('A' . $row, '');
        $hoja->setCellValue('A' . $row, $rowdata['medida']);
        //$hoja->setCellValue('B'.$row, $rowdata['id'].'-'.$row);
        $hoja->setCellValue('B' . $row, '');
        $hoja->setCellValue('C' . $row, $rowdata['codigointerno']);
        $hoja->setCellValue('D' . $row, $rowdata['descripcion']);
        $hoja->setCellValue('E' . $row, '');
        $idproductos += [intval($row) => strval($rowdata['id'])];
        $row++;
    }
} else {
    $response = array(
        'respuesta' => 400,
        'mensaje' => 'No hay conceptos a imprimir',
    );
    die(json_encode($response));
}
/**************************************************************************************** */
/* TERMINA DE LLENAR EL FORMATO CON LOS DATOS DEL CATALOGO DE PRODUCTOS           */
/**************************************************************************************** */
//TRAER LOS DATOS DEL ALMACEN SELECCIONADO y tabla_os
$respuesta = ControladorOServicios::ctrGetMaterialOsFactura($tabla, $campo, $factura);

if ($respuesta) {

    /********************************************************/
    /* OBTIENE DATOS DE PEP Y PROY DE LA TABLA FACTURAINGRESO
    /*******************************************************/
    $datos = json_decode($respuesta[0]['conceptos'], true);
    $descripcion = $datos[0]['Descripcion'];
    $pep = substr(stristr($descripcion, "BRUNO DIAZ"), 0, strpos(stristr($descripcion, "BRUNO DIAZ"), "."));
    $proy = substr(stristr($descripcion, "CAR"), 0, 9);
    $odc = substr(stristr($descripcion, "00"), 0, 8);
    $serialid=$respuesta[0]['serie'].$respuesta[0]['idfact'];
    $cadena = explode("/", $pep);
    $anio = substr($cadena[0], -4);
    $semana = substr($cadena[1], 0, 2);

    $hoja->getStyle('T2:T5')->getFont()->setBold(true)->setSize(8);
    $hoja->setCellValue('T2', $semana . "-" . $anio);
    $hoja->setCellValue('T3', $serialid);
    $hoja->setCellValue('T4', $factura);
    $hoja->setCellValue('T5', $odc);
    $hoja->setCellValue('P6', $proy);
    $hoja->setCellValue('B7', $pep);

    /*************************************************** */

    $datos_instalacion = array();
    $datos_material = array();
    foreach ($respuesta as $key => $val) {
    //array_push($datos_material, json_decode($respuesta[$key]['datos_material'],TRUE));
         array_push($datos_instalacion, $respuesta[$key]['datos_instalacion']);
         array_push($datos_material, $respuesta[$key]['datos_material']);
    }

    $i = 0;
    $newArray = array();
    foreach ($datos_material as $clave => $valor) {
        //echo $clave . ' => ' . $valor.PHP_EOL;
        $x = json_decode($valor, true);
        foreach ($x as $key => $value) {
            //echo 'id:'.$value['id_producto'].' - ';
            //echo 'cant:'.$value['cantidad'].' - '.PHP_EOL;
            if (array_key_exists($value['id_producto'], $newArray)) {
                $newArray[$value['id_producto']] += floatval($value['cantidad']);
            } else {
                $newArray[$value['id_producto']] = floatval($value['cantidad']);
            }
        }
    }
    $errores = array();
    foreach ($newArray as $key => $value) {
        $pos = array_search("$key", $idproductos, true); //"$key", string)$key, strval($key) los 3 funcionan para conv numero a string
        //echo $key.'-'.$value.'-'.$fila.PHP_EOL;
        if (intval($pos) > 0) {
            $hoja->setCellValue('N' . intval($pos), $value);
            $hoja->setCellValue('O' . intval($pos), $value);
            $hoja->setCellValue('P' . intval($pos), '0');
            $hoja->setCellValue('Q' . intval($pos), '0');
            $hoja->setCellValue('R' . intval($pos), '0');
            $hoja->setCellValue('T' . intval($pos), '0');
        } else {
            array_push($errores, "No existe producto $key con cant $value");
        }
    }

/**************************************************************************************** */
/*                              ALFANUMERICOS DE ONT'S                                   */
/**************************************************************************************** */
//$row=$row+1;
$arrayalfa = array();
foreach ($datos_instalacion as $clave => $valor) {
    $mega = json_decode($valor, true);
    foreach ($mega as $key => $value) {
        array_push($arrayalfa, $value['alfanumerico']);
    }
}
$hoja->getRowDimension(1)->setRowHeight(15);
$hoja->getStyle('A'. intval($row).':T'. intval($row))->getFont()->setBold(true)->setSize(9);
$hoja->mergeCells('A'. intval($row).':T'. intval($row));
$hoja->getStyle('A'. intval($row).':T'. intval($row))->getAlignment()->setVertical('center')->setHorizontal('left'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('A'.intval($row), 'ALFANÚMERICO ONT: '.implode(", ", $arrayalfa));
$hoja->getStyle('A' . $row)->getAlignment()->setWrapText(true); //AJUSTAR TEXTO
$hoja->getStyle('A'. $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DEDED3');

/**************************************************************************************** */
/*                            TITULOS EN EL PIE DE PAGINA                                 */
/**************************************************************************************** */
    $row = $row + 4;
    $hoja->getStyle('D' . $row . ':N' . $row)->getFont()->setBold(true);
    $hoja->getStyle('D' . $row . ':D' . $row)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('center');
    $hoja->setCellValue('D' . $row, 'ING. BRUNO DIAZ GORDILLO');

    $hoja->getStyle('G' . $row . ':K' . $row)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('center');
    $hoja->mergeCells('G' . $row . ':K' . $row);
    $hoja->setCellValue('G' . $row, 'ING. FRANCISCO LIEVANO');

    $hoja->getStyle('N' . $row . ':R' . $row)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('center');
    $hoja->mergeCells('N' . $row . ':R' . $row);
    $hoja->setCellValue('N' . $row, 'ING. CARLOS HUMBERTO GOMEZ');
    $row++;
    $hoja->getStyle('D' . $row . ':D' . $row)->getAlignment()->setVertical('center')->setHorizontal('center');
    $hoja->setCellValue('D' . $row, 'CONTRATISTA');

    $hoja->getStyle('G' . $row . ':K' . $row)->getAlignment()->setVertical('center')->setHorizontal('center');
    $hoja->mergeCells('G' . $row . ':K' . $row);
    $hoja->setCellValue('G' . $row, 'COORDINADOR AX');

    $hoja->getStyle('N' . $row . ':R' . $row)->getAlignment()->setVertical('center')->setHorizontal('center');
    $hoja->mergeCells('N' . $row . ':R' . $row);
    $hoja->setCellValue('N' . $row, 'SUPERVISOR DE LA OBRA');
/**************************************************************************************** */

} else {
    $response = array(
        'respuesta' => 400,
        'mensaje' => "No existe factura " . $factura . " en la Base de datos.",
    );
    echo json_encode($response);
    exit;
}


//if (count($errores) === 0) {
    //$filename='mi_archivo_excel'.time().'.xlsx';
    // Guardar el archivo de Excel
    $writer = new Xlsx($spreadsheet);
    try {
        //$writer->save('mi_archivo_excel' . time() . '.xlsx');
        $writer->save('F200-' . trim($factura) . '.xlsx');

        $response = array(
            'respuesta' => 200,
            'mensaje' => "El archivo se genero exitosamente." . $factura,
        );
        echo json_encode($response);

    } catch (PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
        $response = array(
            'respuesta' => 400,
            'mensaje' => "'Error al guardar el archivo: Es posible que este abierto', $e",
        );
        echo json_encode($response);
    }
// } else {
//     $response = array(
//         'respuesta' => 401,
//         'mensaje' => $errores,
//     );
//     echo json_encode($response);
// }

// Cerrar la conexión a la base de datos MySQL
$stmt = null;

//echo count($errores).PHP_EOL;
//var_dump($errores);
//   var_dump($newArray);
//   echo ' '.PHP_EOL;
//   var_dump($idproductos);
//   echo ' '.PHP_EOL;
//   echo array_search($value['id_producto'],$idproductos,true);
//   echo ' '.PHP_EOL;
