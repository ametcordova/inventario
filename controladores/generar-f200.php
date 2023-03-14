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
    $factura = strtoupper($POST['factura']);
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

// nombre del formato en la celda A1
$hoja->getRowDimension(1)->setRowHeight(10);
$hoja->getStyle('A1:N1')->getFont()->setBold(false)->setSize(8);
$hoja->mergeCells('A1:N1');
$hoja->getStyle('A1:N1')->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('A1', 'FR-PE-56');

// Establecer el valor del encabezado en la celda E1
$fila = 2;
$hoja->getRowDimension($fila)->setRowHeight(12);
$hoja->getStyle('C2:M3')->getFont()->setBold(true)->setSize(16);
$hoja->mergeCells('C2:M3');
$hoja->getStyle('C2:M3')->getAlignment()->setVertical('center')->setHorizontal('center'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('C2', 'CUADRE MANUAL DE MATERIALES MONTADOS EN OBRA (F200)');

$hoja->getStyle('N' . $fila)->getFont()->setBold(false)->setSize(8);
$hoja->getStyle('N' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('N' . $fila, 'SEMANA:');
$fila += 1;
$hoja->getRowDimension($fila)->setRowHeight(12);
$hoja->getStyle('N' . $fila)->getFont()->setBold(false)->setSize(8);
$hoja->getStyle('N' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('N' . $fila, 'CONSECUTIVO:');

$fila = 4;
$hoja->getRowDimension($fila)->setRowHeight(12);
$hoja->getStyle('C' . $fila . ':M' . $fila)->getFont()->setBold(true)->setSize(14);
$hoja->mergeCells('C' . $fila . ':M' . $fila);
$hoja->getStyle('C' . $fila . ':M' . $fila)->getAlignment()->setVertical('center')->setHorizontal('center'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('C' . $fila, 'MOVIMIENTOS DE MATERIALES');

$hoja->getStyle('N' . $fila)->getFont()->setBold(false)->setSize(8);
$hoja->getStyle('N' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('N' . $fila, 'FACTURA:');

/******************************************************************************* */
//$hoja->getRowDimension(3)->setRowHeight(9);
//$hoja->getRowDimension(4)->setRowHeight(6);
/******************************************************************************* */

$fila = 5;
$hoja->getRowDimension($fila)->setRowHeight(12);
$hoja->getStyle('N' . $fila)->getFont()->setBold(false)->setSize(8);
$hoja->getStyle('N' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('N' . $fila, 'OC:');

$hoja->getStyle('N2:O5')->applyFromArray($styleExtBorder);

$hoja->getStyle('O2:O' . $fila)->getAlignment()->setVertical('center')->setHorizontal('left');
$hoja->getStyle('O2:O' . $fila)->getFont()->setBold(true)->setSize(8); //NEGRITAS PARA EL CUADRO CON DATOS DEL F200
/******************************************************************************* */
// SUBTITULOS
/******************************************************************************* */
$fila += 1;
$hoja->getRowDimension($fila)->setRowHeight(16);
$hoja->getStyle('A' . $fila . ':M' . $fila)->getFont()->setBold(true)->setSize(9);
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
$hoja->getStyle('L' . $fila . ':L' . $fila)->getAlignment()->setVertical('center')->setHorizontal('center'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('L' . $fila, 'PROYECTO:');
$hoja->getStyle('M' . $fila)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('left');

// Agregar contenido a la celda A1
//$hoja->setCellValue('A6','este es');

$fila += 1;
$hoja->getRowDimension($fila)->setRowHeight(16);
$hoja->getStyle('A' . $fila . ':M' . $fila)->getFont()->setBold(true)->setSize(9);
$hoja->getStyle('A' . $fila . ':A' . $fila)->getAlignment()->setVertical('center')->setHorizontal('center'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('A' . $fila, 'PEP:');
$hoja->mergeCells('B' . $fila . ':D' . $fila);
$hoja->getStyle('B' . $fila . ':D' . $fila)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('left');
$hoja->getStyle('E' . $fila . ':E' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('E' . $fila, 'RUTA:');
$hoja->getStyle('F' . $fila)->applyFromArray($styleBorderLower);
$hoja->getStyle('G' . $fila . ':K' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('G' . $fila, 'OEI:');
$hoja->getStyle('H' . $fila)->applyFromArray($styleBorderLower);
$hoja->setCellValue('I' . $fila, 'OE:');
$hoja->getStyle('J' . $fila)->applyFromArray($styleBorderLower);
$hoja->getStyle('L' . $fila)->getAlignment()->setVertical('center')->setHorizontal('right'); //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('L' . $fila, 'CTL/DTO:');
$hoja->getStyle('M' . $fila)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('left');
$hoja->setCellValue('M' . $fila, 'VARIOS');
$fila += 1;
$hoja->getRowDimension($fila)->setRowHeight(4);

/*********************************************************************************** */
/* EMPIEZA LOS TITULOS DE LAS CABECERAS*/
/*********************************************************************************** */
$fila = 10;
$hoja->getStyle('A' . $fila . ':O' . $fila)->getFont()->setBold(true)->setSize(10);
$hoja->getStyle('A' . $fila . ':O' . $fila)->getAlignment()->setVertical('center')->setHorizontal('center'); //CENTRADO HORIZONTAL Y VERTICAL

// Establecer los títulos de las celdas a partir de la celda A6
$hoja->setCellValue('A' . $fila, 'UNIDAD');
$hoja->setCellValue('B' . $fila, 'SKU');
$hoja->setCellValue('C' . $fila, 'CATALOGO AX');
$hoja->getStyle('C' . $fila)->getAlignment()->setWrapText(true); //AJUSTAR TEXTO
$hoja->setCellValue('D' . $fila, 'DENOMINACION');

$hoja->mergeCells('E' . $fila . ':F' . $fila);
$hoja->setCellValue('E' . $fila, 'SALIDAS');
$hoja->mergeCells('G' . $fila . ':H' . $fila);
$hoja->setCellValue('G' . $fila, 'DEVOLUCIONES');

$hoja->setCellValue('I' . $fila, 'RECIBIDO');
$hoja->setCellValue('J' . $fila, 'MONTADO');
$hoja->setCellValue('K' . $fila, 'DIFERENCIA');
$hoja->setCellValue('L' . $fila, 'DEVOLUCION');
$hoja->setCellValue('M' . $fila, 'SOBRANTE');
$hoja->setCellValue('N' . $fila, 'P.U.');
$hoja->setCellValue('O' . $fila, 'ADEUDO');

$hoja->getStyle('A' . $fila . ':O' . $fila)->applyFromArray($estilostitulos)->applyFromArray($styleAllBorder);
//$hoja->getStyle('A6:L6')->applyFromArray($styleBorder);

// Establecer ancho de columnas
$hoja->getColumnDimension('A')->setWidth(9);
$hoja->getColumnDimension('B')->setWidth(7);
$hoja->getColumnDimension('C')->setWidth(10);
$hoja->getColumnDimension('D')->setWidth(47);
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
    $row = $fila + 1;
    $idproductos = array();
    $cuantos = (intval($row) + intval($datos['cuantos'])) - 1;
    $hoja->getStyle('A' . $row . ':O' . $cuantos)->applyFromArray($styleAllBorder);
    $hoja->getStyle('A' . $row . ':O' . $cuantos)->getFont()->setBold(false)->setSize(10);
    // Rellenar la hoja de cálculo con los datos correspondientes
    foreach ($data as $rowdata) {
        $hoja->setCellValue('A' . $row, $rowdata['medida']);
        //$hoja->setCellValue('B'.$row, $rowdata['id'].'-'.$row);
        $hoja->setCellValue('B' . $row, '');
        $hoja->setCellValue('C' . $row, $rowdata['codigointerno']);
        $hoja->setCellValue('D' . $row, $rowdata['descripcion']);
        $idproductos += [intval($row) => $rowdata['id']];
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

$errores = array();
//TRAER LOS DATOS DEL ALMACEN SELECCIONADO
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
    $cadena = explode("/", $pep);
    $anio = substr($cadena[0], -4);
    $semana = substr($cadena[1], 0, 2);
    $hoja->setCellValue('O2', $semana . "-" . $anio);
    $hoja->setCellValue('O3', "XY");
    $hoja->setCellValue('O4', $factura);
    $hoja->setCellValue('O5', $odc);
    $hoja->setCellValue('M6', $proy);
    $hoja->setCellValue('B7', $pep);

    /*************************************************** */

    $datos_material = array();
    foreach ($respuesta as $key => $val) {
        //array_push($datos_material, json_decode($respuesta[$key]['datos_material'],TRUE));
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

    foreach ($newArray as $key => $value) {
        $pos = array_search("$key", $idproductos, true); //"$key", string)$key, strval($key) los 3 funcionan para conv numero a string
        //echo $key.'-'.$value.'-'.$fila.PHP_EOL;
        if (intval($fila) > 0) {
            $hoja->setCellValue('I' . intval($pos), $value);
            $hoja->setCellValue('J' . intval($pos), $value);
            $hoja->setCellValue('K' . intval($pos), '0');
            $hoja->setCellValue('L' . intval($pos), '0');
            $hoja->setCellValue('M' . intval($pos), '0');
        } else {
            array_push($errores, "No existe producto $key con cant $value");
        }
    }
/**************************************************************************************** */
/*                            TITULOS EN EL PIE DE PAGINA                                 */
/**************************************************************************************** */
    $row = $row + 3;
    $hoja->getStyle('D' . $row . ':J' . $row)->getFont()->setBold(true);
    $hoja->getStyle('D' . $row . ':D' . $row)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('center');
    $hoja->setCellValue('D' . $row, 'ING. BRUNO DIAZ GORDILLO');

    $hoja->getStyle('F' . $row . ':H' . $row)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('center');
    $hoja->mergeCells('F' . $row . ':H' . $row);
    $hoja->setCellValue('F' . $row, 'ING. FRANCISCO LIEVANO');

    $hoja->getStyle('J' . $row . ':M' . $row)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('center');
    $hoja->mergeCells('J' . $row . ':M' . $row);
    $hoja->setCellValue('J' . $row, 'ING. CARLOS HUMBERTO GOMEZ');
    $row++;
    $hoja->getStyle('D' . $row . ':D' . $row)->getAlignment()->setVertical('center')->setHorizontal('center');
    $hoja->setCellValue('D' . $row, 'CONTRATISTA');

    $hoja->getStyle('F' . $row . ':H' . $row)->getAlignment()->setVertical('center')->setHorizontal('center');
    $hoja->mergeCells('F' . $row . ':H' . $row);
    $hoja->setCellValue('F' . $row, 'COORDINADOR AX');

    $hoja->getStyle('J' . $row . ':M' . $row)->getAlignment()->setVertical('center')->setHorizontal('center');
    $hoja->mergeCells('J' . $row . ':M' . $row);
    $hoja->setCellValue('J' . $row, 'SUPERVISOR DE LA OBRA');
/**************************************************************************************** */

} else {
    $response = array(
        'respuesta' => 400,
        'mensaje' => "No existe factura " . $factura . " en la Base de datos.",
    );
    echo json_encode($response);
    exit;
}

if (count($errores) == 0) {
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
} else {
    $response = array(
        'respuesta' => 400,
        'mensaje' => $errores,
    );
    echo json_encode($response);
}

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
