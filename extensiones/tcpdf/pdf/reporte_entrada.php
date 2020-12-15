<?php

require_once "../../../controladores/entradasalmacen.controlador.php";
require_once "../../../modelos/entradasalmacen.modelo.php";
/*
require_once "../../../controladores/proveedores.controlador.php";
require_once "../../../modelos/proveedores.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

*/
class imprimirEntrada{

public $codigo;

public function traerImpresionEntrada(){

//TRAEMOS LA INFORMACIÓN DE LA VENTA

$item = "id_entrada";
$numeroid = $_GET["codigo"];

$respuestaAlmacen = ControladorEntradasAlmacen::ctrReporteEntradaAlmacen($item, $numeroid);

/*
$fecha = substr($respuestaVenta["fecha"],0,-8);
$productos = json_decode($respuestaVenta["productos"], true);
$neto = number_format($respuestaVenta["neto"],2);
$impuesto = number_format($respuestaVenta["impuesto"],2);
$total = number_format($respuestaVenta["total"],2);
*/

//REQUERIMOS LA CLASE TCPDF
require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->startPageGroup();

$pdf->AddPage();

if($respuestaAlmacen){    
// ---------------------------------------------------------

$bloque1 = <<<EOF

	<table>
		
		<tr>

			<td style="width:150px"><img src="images/logo_nuno.png"></td>

			<td style="background-color:white; width:140px">
				
				<div style="font-size:8.5px; text-align:right; line-height:15px;">
					
					<br>
                    Av. Rio Coatan No.504, Col. 24 de Junio, Tuxtla Gutiérrez, Chiapas.


				</div>

			</td>

			<td style="background-color:white; width:140px">

				<div style="font-size:8.5px; text-align:right; line-height:15px;">
					
					<br>
					Teléfono: Tel: (961)-1407119
					
					<br>
					brunonunosco1998@gmail.com

				</div>
				
			</td>

			<td style="background-color:white; width:110px; text-align:center; color:red"><br><br>ENTRADA No.<br>$numeroid</td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($bloque1, false, false, false, false, '');

$pdf->Ln(2);
    
// ---------------------------------------------------------
$bloque2 = <<<EOF

<h2 style="text-align:center;">REPORTE DE ENTRADAS AL ALMACEN</h2>


EOF;

$pdf->writeHTML($bloque2, false, false, false, false, '');
$pdf->Ln(2);
// ---------------------------------------------------------

//var_dump($respuestaAlmacen);
$idProv=$respuestaAlmacen[0]["id_proveedor"];
$nomProv=$respuestaAlmacen[0]["nombreproveedor"];
$FechDocto=date("d/m/Y", strtotime($respuestaAlmacen[0]["fechaentrada"]));
$idAlmacen=$respuestaAlmacen[0]["id_almacen"];
$nombreAlmacen=$respuestaAlmacen[0]["nombrealmacen"];
$fechEntrada=date("d/m/Y", strtotime($respuestaAlmacen[0]["fechaentrada"]));
$recibio=$respuestaAlmacen[0]["nombreusuario"];

$bloque3 = <<<EOF

	<table style="font-size:9px; padding:5px 5px;">
    
    <tr>		
		<td style="border: 1px solid #666; width:540px">Proveedor: $idProv - $nomProv</td>
	</tr>
	
	<tr>
	   <td style="border: 1px solid #666; width:165px">Numero de Docto: $numeroid</td>

	   <td style="border: 1px solid #666; width:135px; text-align:left">Fecha Docto: $FechDocto
	   </td>

        <td style="border: 1px solid #666; width:240px">Almacen de Entrada: $idAlmacen - $nombreAlmacen</td>
	</tr>

 	<tr>
		<td style="border: 1px solid #666; width:300px">Recibio: $recibio</td>
        <td style="border: 1px solid #666; width:240px">Fecha recepción en Almacen: $fechEntrada</td>
	</tr>

	</table>

EOF;

$pdf->writeHTML($bloque3, false, false, false, false, '');
$pdf->Ln(4);
// ---------------------------------------------------------    
    
// ---------------------------------------------------------

$bloque4 = <<<EOF

 	<table style="font-size:10px; padding:5px 5px;">

	  <tr bgcolor="#cccccc" class="text-center">
		<td style="border: 1px solid #666; width:35px; text-align:center">id</td>
		<td style="border: 1px solid #666; width:75px; text-align:center;">Código</td>
		<td style="border: 1px solid #666; width:310px; text-align:center">Producto</td>
		<td style="border: 1px solid #666; width:75px; text-align:center">U.Med.</td>
		<td style="border: 1px solid #666; width:45px; text-align:center">Cant.</td>
     </tr>
     
	</table>

EOF;

$pdf->writeHTML($bloque4, false, false, false, false, '');

// ---------------------------------------------------------
$cantEntra=0;
foreach ($respuestaAlmacen as $row) {

$bloque5 = <<<EOF

 	<table style="font-size:9px; padding:5px 5px;">

	  <tr>
		<td style="border: 1px solid #666; width:35px; text-align:center">$row[id_producto]</td>
		<td style="border: 1px solid #666; width:75px; text-align:center">$row[codigointerno]</td>
		<td style="border: 1px solid #666; width:310px; text-align:left">$row[descripcion]</td>
		<td style="border: 1px solid #666; width:75px; text-align:center">$row[medida]</td>
		<td style="border: 1px solid #666; width:45px; text-align:center">$row[cantidad]</td>
      </tr>
     
 	</table>

EOF;
$cantEntra+=$row['cantidad'];
$pdf->writeHTML($bloque5, false, false, false, false, '');
}
// ---------------------------------------------------------
   
//$totales = number_format($totales, 2);

$bloque6 = <<<EOF

	<table style="font-size:9px; padding:5px 5px;">

	<tr bgcolor="#cccccc">
		<td style="border: 1px solid #666; width:495px; text-align:right">Total Entrada:</td>
		<td style="border: 1px solid #666; width:45px; text-align:center">$cantEntra</td>
    </tr>
    
 	</table>

EOF;

$pdf->writeHTML($bloque6, false, false, false, false, '');    
$pdf->Ln(6);
// ---------------------------------------------------------
$bloque7 = <<<EOF

	<table style="font-size:9px; padding:5px 5px;">

    <tr>
        <td style="border: 1px solid #666;width:260px; height:50px; text-align:center">Nombre y firma quien Recibe</td>
        <td style="width:20px;height:50px; text-align:center"></td>
        <td style="border: 1px solid #666;width:260px; height:50px; text-align:center">Nombre y firma quien Revisa</td>
    </tr>

 	</table>

EOF;

$pdf->writeHTML($bloque7, false, false, false, false, '');     
// ---------------------------------------------------------    
//SALIDA DEL ARCHIVO 
    $nombre_archivo="entrada".trim($numeroid).".pdf";   //genera el nombre del archivo para descargarlo
    $pdf->Output($nombre_archivo);
}else{
    $nombre_archivo="entradax".trim($numeroid).".pdf";   //genera el nombre del archivo para descargarlo
    $pdf->Output($nombre_archivo);  
}
}

}

$entrada = new imprimirEntrada();
$entrada -> codigo = $_GET["codigo"];
$entrada -> traerImpresionEntrada();

?>