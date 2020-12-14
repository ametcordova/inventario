<?php

require_once "../../../controladores/entradas.controlador.php";
require_once "../../../modelos/entradas.modelo.php";

require_once "../../../controladores/proveedores.controlador.php";
require_once "../../../modelos/proveedores.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

include ('Numbers/Words.php');

class imprimirEntrada{

public $codigo;

public function traerImpresionEntrada(){

//TRAEMOS LA INFORMACIÓN DE LA VENTA

$itemVenta = "numerodocto";
$numdeDocto = $_GET["codigo"];

$respuestaAlmacen = ControladorEntradas::ctrEntradaAlm($itemVenta, $numdeDocto);

/*
$fecha = substr($respuestaVenta["fecha"],0,-8);
$productos = json_decode($respuestaVenta["productos"], true);
$neto = number_format($respuestaVenta["neto"],2);
$impuesto = number_format($respuestaVenta["impuesto"],2);
$total = number_format($respuestaVenta["total"],2);
*/
//TRAEMOS LA INFORMACIÓN DEL CLIENTE

//$itemProveedor = "id";
//$valorProveedor = $respuestaVenta["id_proveedor"];

//$respuestaProveedor = ControladorProveedores::ctrMostrarProveedores($itemProveedor, $valorProveedor);

//TRAEMOS LA INFORMACIÓN DEL VENDEDOR

//$itemCapturo = "id";
//$valorCapturo = $respuestaVenta["id_usuario"];

//$respuestaCapturo = ControladorUsuarios::ctrMostrarUsuarios($itemCapturo, $valorCapturo);

//REQUERIMOS LA CLASE TCPDF

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->startPageGroup();

$pdf->AddPage();
    
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

			<td style="background-color:white; width:110px; text-align:center; color:red"><br><br>ENTRADA No.<br>$numdeDocto</td>

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
$nomProv=$respuestaAlmacen[0]["nombreprov"];
$FechDocto=date("d/m/Y", strtotime($respuestaAlmacen[0]["fechadocto"]));
$idAlmacen=$respuestaAlmacen[0]["id_almacen"];
$nombreAlmacen=$respuestaAlmacen[0]["nombre"];
$fechEntrada=date("d/m/Y", strtotime($respuestaAlmacen[0]["fechaentrada"]));
$recibio=$respuestaAlmacen[0]["recibio"];

$bloque3 = <<<EOF

	
	<table style="font-size:9px; padding:5px 5px;">
    
    <tr>		
		<td style="border: 1px solid #666; width:540px">Proveedor: $idProv - $nomProv</td>
	</tr>
	
	<tr>
	   <td style="border: 1px solid #666; width:165px">Numero de Docto: $numdeDocto</td>

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
		<td style="border: 1px solid #666; width:32px; text-align:center">id</td>
		<td style="border: 1px solid #666; width:71px; text-align:center;">codigo</td>
		<td style="border: 1px solid #666; width:247px; text-align:center">Producto</td>
		<td style="border: 1px solid #666; width:44px; text-align:center">Cant.</td>
		<td style="border: 1px solid #666; width:71px; text-align:center">Precio Unit.</td>
		<td style="border: 1px solid #666; width:75px; text-align:center">Precio Total</td>
     </tr>
     
	</table>

EOF;

$pdf->writeHTML($bloque4, false, false, false, false, '');

// ---------------------------------------------------------
$subtotal=0;
foreach ($respuestaAlmacen as $row) {

$valorUnitario = number_format($row["precio_compra"], 2);

$preciosSubtotal = $row["cantidad"]*$row["precio_compra"];
    
$precioTotal = number_format($row["cantidad"]*$row["precio_compra"], 2);    

$bloque5 = <<<EOF


 
	<table style="font-size:9px; padding:5px 5px;">

	  <tr>
		<td style="border: 1px solid #666; width:32px; text-align:center">$row[id_producto]</td>
		<td style="border: 1px solid #666; width:71px; text-align:center">$row[codigointerno]</td>
		<td style="border: 1px solid #666; width:247px; text-align:left">$row[descripcion]</td>
		<td style="border: 1px solid #666; width:44px; text-align:center">$row[cantidad]</td>
		<td style="border: 1px solid #666; width:71px; text-align:right">$$valorUnitario</td>
		<td style="border: 1px solid #666; width:75px; text-align:right">$$precioTotal</td>
     </tr>
     
 	</table>

EOF;
$subtotal+=$preciosSubtotal;
$pdf->writeHTML($bloque5, false, false, false, false, '');
}

// ---------------------------------------------------------
$iva=$subtotal*(16/100);
$totales=$subtotal+$iva;

$nw = new Numbers_Words();
$total=sprintf("%01.2f",$totales);
$decimales = explode(".",$total);
$enletras=strtoupper($nw->toWords($decimales[0], "es")." pesos ".$decimales[1]."/100 M.N.");
    
$subtotal = number_format($subtotal, 2);    
$iva = number_format($iva, 2);
$totales = number_format($totales, 2);
    

$bloque6 = <<<EOF

 
	<table style="font-size:9px; padding:5px 5px;">

	<tr>
		<td style="width:394px;text-align:center">Cantidad en Letras:</td>
		<td style="border: 1px solid #666; width:71px; text-align:right">Subtotal:</td>
		<td style="border: 1px solid #666; width:75px; text-align:right">$$subtotal</td>
    </tr>
	<tr>
		<td style="width:394px; text-align:center">($enletras)</td>
		<td style="border: 1px solid #666; width:71px; text-align:right">IVA 16%:</td>
		<td style="border: 1px solid #666; width:75px; text-align:right">$$iva</td>
    </tr>
	<tr>
		<td style="width:394px; text-align:center"></td>
		<td style="border: 1px solid #666; width:71px; text-align:right">Totales:</td>
		<td style="border: 1px solid #666; width:75px; text-align:right">$$totales</td>
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

$pdf->Output('entrada.pdf');

}

}

$entrada = new imprimirEntrada();
$entrada -> codigo = $_GET["codigo"];
$entrada -> traerImpresionEntrada();

?>