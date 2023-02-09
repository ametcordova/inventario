<?php
/**
 * php -S localhost:8000 -t vendor/phpoffice/phpspreadsheet/samples
 * Ejemplo de cómo usar PDO y PHPSpreadSheet para
 * exportar datos de MySQL a Excel de manera
 * fácil, rápida y segura
 *
 * @author parzibyte
 * @see https://parzibyte.me/blog/2019/02/14/leer-archivo-excel-php-phpspreadsheet/
 * @see https://parzibyte.me/blog/2018/02/12/mysql-php-pdo-crud/
 * @see https://parzibyte.me/blog/2019/02/16/php-pdo-parte-2-iterar-cursor-comprobar-si-elemento-existe/
 * @see https://parzibyte.me/blog/2018/11/08/crear-archivo-excel-php-phpspreadsheet/
 * @see https://parzibyte.me/blog/2018/10/11/sintaxis-corta-array-php/
 *
 */
require_once "autoload.php";
require_once dirname( __DIR__ ).'../../modelos/conexion.php';

# Nuestra base de datos
//require_once "bd.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

# Obtener base de datos
//$bd = obtenerBD();

$documento = new Spreadsheet();
$documento
    ->getProperties()
    ->setCreator("Luis Cabrera Benito (parzibyte)")
    ->setLastModifiedBy('Parzibyte')
    ->setTitle('Archivo exportado desde MySQL')
    ->setDescription('Un archivo de Excel exportado desde MySQL por parzibyte');

# Como ya hay una hoja por defecto, la obtenemos, no la creamos
$hojaDeProductos = $documento->getActiveSheet();
$hojaDeProductos->setTitle("Productos");

# Escribir encabezado de los productos
$encabezado = ["Código de barras", "Descripción", "Precio de compra", "Precio de venta", "Existencia"];
# El último argumento es por defecto A1 pero lo pongo para que se explique mejor
$hojaDeProductos->fromArray($encabezado, null, 'A1');

$consulta = "select codigo, descripcion, precio_compra, precio_venta, sku from productos";

$sentencia = Conexion::conectar()->prepare($consulta, [
    PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL,
]);
$sentencia->execute();
# Comenzamos en la 2 porque la 1 es del encabezado
$numeroDeFila = 2;
while ($producto = $sentencia->fetchObject()) {
    # Obtener los datos de la base de datos
    $codigo = $producto->codigo;
    $descripcion = $producto->descripcion;
    $precioCompra = $producto->precio_compra;
    $precioVenta = $producto->precio_venta;
    $existencia = $producto->sku;
    # Escribirlos en el documento
    $hojaDeProductos->setCellValueByColumnAndRow(1, $numeroDeFila, $codigo);
    $hojaDeProductos->setCellValueByColumnAndRow(2, $numeroDeFila, $descripcion);
    $hojaDeProductos->setCellValueByColumnAndRow(3, $numeroDeFila, $precioCompra);
    $hojaDeProductos->setCellValueByColumnAndRow(4, $numeroDeFila, $precioVenta);
    $hojaDeProductos->setCellValueByColumnAndRow(5, $numeroDeFila, $existencia);
    $numeroDeFila++;
}

# Ahora los clientes
# Ahora sí creamos una nueva hoja
$hojaDeClientes = $documento->createSheet();
$hojaDeClientes->setTitle("Clientes");

# Escribir encabezado
$encabezado = ["Nombre", "Correo electrónico"];
# El último argumento es por defecto A1 pero lo pongo para que se explique mejor
$hojaDeClientes->fromArray($encabezado, null, 'A1');
# Obtener clientes de BD
$consulta = "select nombre, email from clientes";
$sentencia = Conexion::conectar()->prepare($consulta, [
    PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL,
]);
$sentencia->execute();

# Comenzamos en la 2 porque la 1 es del encabezado
$numeroDeFila = 2;
while ($cliente = $sentencia->fetchObject()) {
    # Obtener los datos de la base de datos
    $nombre = $cliente->nombre;
    $correo = $cliente->email;

    # Escribirlos en el documento
    // $hojaDeClientes->setCellValueByColumnAndRow(1, $numeroDeFila, $nombre);
    // $hojaDeClientes->setCellValueByColumnAndRow(2, $numeroDeFila, $correo);
    $hojaDeClientes->setCellValue("A".$numeroDeFila, $nombre);
    $hojaDeClientes->setCellValue("B".$numeroDeFila, $correo);
    //$hoja->setCellValue("B2", "Este va en B2");

    $numeroDeFila++;
}
# Crear un "escritor"
//$writer = new Xlsx($documento);
# Le pasamos la ruta de guardado
//$writer->save('Exportado.xlsx');



// Redirect output to a client's web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="01simple.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

//new code:
//$writer = IOFactory::createWriter($spreadsheet, 'Xls');
$writer = IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');
exit;