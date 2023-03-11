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
require_once dirname( __DIR__ ).'../controladores/adminoservicios.controlador.php';
require_once dirname( __DIR__ ).'../modelos/adminoservicios.modelo.php';


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
$styleAllBorder = [
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
//$hoja->setCellValue('L5', $proyecto);

$hoja->getStyle('A7:L7')->getFont()->setBold(true)->setSize(9);
$hoja->getStyle('A7:A7')->getAlignment()->setVertical('center')->setHorizontal('center');  //CENTRADO HORIZONTAL Y VERTICAL
$hoja->setCellValue('A7', 'PEP:');
$hoja->mergeCells('B7:D7');
$hoja->getStyle('B7:D7')->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('left');  
//$hoja->setCellValue('B7', $trabajo);
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

$hoja->getStyle('A10:O10')->applyFromArray($estilostitulos)->applyFromArray($styleAllBorder);
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
try{
    $sql = "SELECT COUNT(*) AS cuantos FROM productos WHERE esfo=1 AND listar=1 AND estado=1";
    $resultado = Conexion::conectar()->prepare($sql);
    $resultado->execute();
    $datos = $resultado->fetch(PDO::FETCH_ASSOC);    
        /* Comprobar el número de filas que coinciden con la sentencia SELECT */
        if (intval($datos['cuantos'])>0){
            $consulta = "SELECT p.id, p.codigointerno, p.descripcion, p.sku, m.medida FROM productos p INNER JOIN medidas m ON p.id_medida=m.id WHERE esfo=1 AND listar=1 AND estado=1 ORDER BY p.descripcion ASC";

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
/* SI EXISTEN DATOS SE CREA EL LLENADO DEL FORMATO */
/**************************************************************************************** */
if (!empty($data)) {
    $row = 11;
    $idproductos=array();
    $cuantos=(intval($row)+intval($datos['cuantos']))-1;
    $hoja->getStyle('A11:O'.$cuantos)->applyFromArray($styleAllBorder);
    $hoja->getStyle('A11:O'.$cuantos)->getFont()->setBold(false)->setSize(10);
    // Rellenar la hoja de cálculo con los datos correspondientes
    foreach ($data as $rowdata) {
        $hoja->setCellValue('A'.$row, $rowdata['medida']);
        $hoja->setCellValue('B'.$row, $rowdata['id'].'-'.$row);
        $hoja->setCellValue('C'.$row, $rowdata['codigointerno']);
        $hoja->setCellValue('D'.$row, $rowdata['descripcion']);
        $idproductos+=[intval($row) => $rowdata['id']];
        $row++;
    }
}else{
    $response =  array(
        'response' => 400,
        'mensaje' =>'No hay datos a imprimir'
    );
    die(json_encode($response));
}  
/**************************************************************************************** */
/* TERMINA DE LLENAR EL FORMATO CON LOS DATOS DEL CATALOGO DE PRODUCTOS           */
/**************************************************************************************** */
    
    //TRAEMOS LA INFORMACIÓN 
    $tabla="tabla_os";
    $campo = "factura";
    $valor='A441';
    //$valor = $_GET["idsfacturas"];
    $errores=array();
    //TRAER LOS DATOS DEL ALMACEN SELECCIONADO
    $respuesta = ControladorOServicios::ctrGetMaterialOsFactura($tabla, $campo, $valor);
        
    if($respuesta){
        
        /********************************************************/
        /* OBTIENE DATOS DE PEP Y PROY DE LA TABLA FACTURAINGRESO
        /*******************************************************/
        $datos=json_decode($respuesta[0]['conceptos'],true);
        $descripcion=$datos[0]['Descripcion'];
        $pep=substr(stristr($descripcion, "BRUNO DIAZ"),0,strpos(stristr($descripcion, "BRUNO DIAZ"),"."));
        $proy=substr(stristr($descripcion,"CAR"),0,9);
        $odc=substr(stristr($descripcion, "00"),0,8);
        $hoja->setCellValue('L5', $proy);
        $hoja->setCellValue('B7', $pep);
        /*************************************************** */

        $datos_material=array();
        foreach($respuesta as $key => $val) {
            //array_push($datos_material, json_decode($respuesta[$key]['datos_material'],TRUE));
            array_push($datos_material, $respuesta[$key]['datos_material']);
        }

        $i=0;$newArray = array(); 
        foreach ($datos_material as $clave => $valor) {
            //echo $clave . ' => ' . $valor.PHP_EOL;
            $x=json_decode($valor, true);
            foreach ($x as $key => $value) {
                //echo 'id:'.$value['id_producto'].' - ';
                //echo 'cant:'.$value['cantidad'].' - '.PHP_EOL;
             if(array_key_exists($value['id_producto'], $newArray)) {
                 $newArray[$value['id_producto']] += floatval($value['cantidad']);
             } else {
                 $newArray[$value['id_producto']] = floatval($value['cantidad']);
             }

            }
            //exit;
          }

          //echo ' '.PHP_EOL;
          foreach ($newArray as $key => $value) {
            $fila=array_search("$key",$idproductos,true);      //"$key", string)$key, strval($key) los 3 funcionan para conv numero a string
            //echo $key.'-'.$value.'-'.$fila.PHP_EOL;
            if(intval($fila)>0){
                $hoja->setCellValue('I'.$fila, $value);
                $hoja->setCellValue('J'.$fila, $value);
                $hoja->setCellValue('K'.$fila, '0');
                $hoja->setCellValue('L'.$fila, '0');
                $hoja->setCellValue('M'.$fila, '0');
            }else{
                array_push($errores, "No existe producto $key con cant $value");
            }
          }
/**************************************************************************************** */
/*                            TITULOS EN EL PIE DE PAGINA                                 */
/**************************************************************************************** */
          $row=$row+3;
          $hoja->getStyle('D'.$row.':J'.$row)->getFont()->setBold(true);
          $hoja->getStyle('D'.$row.':D'.$row)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('center'); 
          $hoja->setCellValue('D'.$row, 'ING. BRUNO DIAZ GORDILLO');

          $hoja->getStyle('F'.$row.':H'.$row)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('center'); 
          $hoja->mergeCells('F'.$row.':H'.$row);
          $hoja->setCellValue('F'.$row, 'ING. FRANCISCO LIEVANO');

          $hoja->getStyle('J'.$row.':M'.$row)->applyFromArray($styleBorderLower)->getAlignment()->setVertical('center')->setHorizontal('center'); 
          $hoja->mergeCells('J'.$row.':M'.$row);
          $hoja->setCellValue('J'.$row, 'ING. CARLOS HUMBERTO GOMEZ');
          $row++;
          $hoja->getStyle('D'.$row.':D'.$row)->getAlignment()->setVertical('center')->setHorizontal('center'); 
          $hoja->setCellValue('D'.$row, 'CONTRATISTA');

          $hoja->getStyle('F'.$row.':H'.$row)->getAlignment()->setVertical('center')->setHorizontal('center'); 
          $hoja->mergeCells('F'.$row.':H'.$row);
          $hoja->setCellValue('F'.$row, 'COORDINADOR AX');

          $hoja->getStyle('J'.$row.':M'.$row)->getAlignment()->setVertical('center')->setHorizontal('center'); 
          $hoja->mergeCells('J'.$row.':M'.$row);
          $hoja->setCellValue('J'.$row, 'SUPERVISOR DE LA OBRA');
/**************************************************************************************** */

    }else{
        $response =  array(
            'response' => 400,
            'mensaje' =>"No existen factura"
        );
        echo json_encode($response);
        exit;
    }

    if(count($errores)==0){
        //$filename='mi_archivo_excel'.time().'.xlsx';
        // Guardar el archivo de Excel
        $writer = new Xlsx($spreadsheet);
        try {
            $writer->save('mi_archivo_excel'.time().'.xlsx');
            echo 'El archivo se guardó exitosamente.';

        } catch (PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
            echo 'Error al guardar el archivo: Es posible que este abierto',  $e->getMessage(), "\n";
        }
    }else{
        $response =  array(
            'response' => 400,
            'mensaje' =>$errores
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
