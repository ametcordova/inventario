<?php
ob_start();
require_once "../../../controladores/ajusteinventario.controlador.php";
require_once "../../../modelos/ajusteinventario.modelo.php";

class imprimirAjusteInv{

public $codigo;

public function traerImpresionAjusteInv(){

//TRAEMOS LA INFORMACIÓN DE LA VENTA

$item = "id";
$numdeajusteinv = $_GET["codigo"];

$respuestaAlmacen = ControladorAjusteInventario::ctrReporteAjusteInv($item, $numdeajusteinv);

if($respuestaAlmacen){

//PARAMETROS DE ENCABEZADO DEL REPORTE
//$razonsocial=defined('RAZON_SOCIAL')?RAZON_SOCIAL:'SIN DATO DE RAZON SOCIAL';
$direccion=defined('DIRECCION')?DIRECCION:'SIN DATO DE DIRECCION';
$colonia=defined('COLONIA')?COLONIA:'SIN DATO DE COLONIA';
$ciudad=defined('CIUDAD')?CIUDAD:'SIN DATO DE CIUDAD';
$telefono=defined('TELEFONO')?TELEFONO:'SIN DATO DE TELEFONO';
$correo=defined('CORREO')?CORREO:'SIN DATO DE CORREO';

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

			<td style="width:65px"><img src="../../../config/logotipo.png"></td>

			<td style="background-color:white; width:187.5px">
				
				<div style="font-size:8.5px; text-align:center; line-height:15px;">
					
					<br>
						$direccion, $colonia, $ciudad
				</div>

			</td>

			<td style="background-color:white; width:187.5px">

				<div style="font-size:8.5px; text-align:center; line-height:15px;">
					
					<br>
					$telefono 
					
					<br>
					$correo

				</div>
				
			</td>

			<td style="background-color:white; width:110px; text-align:center; color:red"><br><br>AJUSTE INV. No.<br>$numdeajusteinv</td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($bloque1, false, false, false, false, '');

$pdf->Ln(2);
    
// ---------------------------------------------------------
$bloque2 = <<<EOF

<h2 style="text-align:center;">REPORTE DE AJUSTE DE INVENTARIO</h2>


EOF;

$pdf->writeHTML($bloque2, false, false, false, false, '');
$pdf->Ln(2);
// ---------------------------------------------------------

//var_dump($respuestaAlmacen);
//$FechDocto=date("d/m/Y", strtotime($respuestaAlmacen[0]["fechadocto"]));
$idAlmacen=$respuestaAlmacen[0]["id_almacen"];
$nombreAlmacen=$respuestaAlmacen[0]["almacen"];
$fechaAjuste=date("d/m/Y h:i:s A", strtotime($respuestaAlmacen[0]["ultmodificacion"]));
$usuario=$respuestaAlmacen[0]["nombreusuario"];
$motivo=$respuestaAlmacen[0]["motivo_ajuste"];
$datosAjuste=$respuestaAlmacen[0]["datos_ajuste"];
$id_tipomov=$respuestaAlmacen[0]["tipomov"];
$nombre_tipo=$respuestaAlmacen[0]["nombre_tipo"];

$bloque3 = <<<EOF

	<table style="font-size:9px; padding:5px 5px;">
    
	<tr>
	   <td style="border: 1px solid #666; width:300px">Usuario: $usuario</td>
       <td style="border: 1px solid #666; width:240px">Almacen: $idAlmacen - $nombreAlmacen</td>
	</tr>

 	<tr>
		<td style="border: 1px solid #666; width:300px">Tipo Mov.: $id_tipomov - $nombre_tipo</td>
        <td style="border: 1px solid #666; width:240px">Fecha y hora: $fechaAjuste</td>
	</tr>

    <tr>
        <td style="border: 1px solid #666; width:90px">Motivo Ajuste.:</td>
        <td style="border: 1px solid #666; width:450px">$motivo</td>
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
		<td style="border: 1px solid #666; width:120px; text-align:center;">Código SKU</td>
		<td style="border: 1px solid #666; width:255px; text-align:center">Producto</td>
		<td style="border: 1px solid #666; width:85px; text-align:center">U.Med.</td>
        <td style="border: 1px solid #666; width:45px; text-align:center">Cant.</td>
     </tr>
     
	</table>

EOF;

$pdf->writeHTML($bloque4, false, false, false, false, '');

// ---------------------------------------------------------
$granTotal=$filas=0;
$datos_json=json_decode($datosAjuste,TRUE);		//decodifica los datos JSON 

foreach($datos_json as $valor) {
    $itemProducto ='id';
    $valorProducto = $valor['id_producto'];
    $respuestaProducto = ControladorAjusteInventario::ctrDatosProducto($itemProducto, $valorProducto);
    $codigointerno = $respuestaProducto["sku"];
    $descripcion = $respuestaProducto["descripcion"];
    $medida = $respuestaProducto["medida"];

$bloque5 = <<<EOF

 	<table style="font-size:9px; padding:3px 3px;">

      <tr>
       
       <td style="border: 1px solid #666; width:35px; text-align:center">$valor[id_producto]</td>
       <td style="border: 1px solid #666; width:120px; text-align:center">$codigointerno</td>
       <td style="border: 1px solid #666; width:255px; text-align:left">$descripcion</td>
       <td style="border: 1px solid #666; width:85px; text-align:center">$medida</td>
       <td style="border: 1px solid #666; width:45px; text-align:center">$valor[cantidad]</td>
      </tr>
     
 	</table>

EOF;
$granTotal+=$valor['cantidad'];
$filas++;
$pdf->writeHTML($bloque5, false, false, false, false, '');
}
// ---------------------------------------------------------

$bloque6 = <<<EOF

	<table style="font-size:9px; padding:3px 3px;">

    <tr bgcolor="#cccccc">
        <td style="border: 1px solid #666; width:365px; text-align:right">Items:</td>
        <td style="border: 1px solid #666; width:45px; text-align:center">$filas</td>

		<td style="border: 1px solid #666; width:85px; text-align:right">Cant. Registrada</td>
		<td style="border: 1px solid #666; width:45px; text-align:center">$granTotal</td>
    </tr>
    
 	</table>

EOF;

$pdf->writeHTML($bloque6, false, false, false, false, ''); 
$pdf->Ln(6);
// ---------------------------------------------------------

// ---------------------------------------------------------    
//SALIDA DEL ARCHIVO 
$nombre_archivo="ajusteinv".trim($numdeajusteinv).".pdf";   //genera el nombre del archivo para descargarlo
ob_end_clean();
$pdf->Output($nombre_archivo);
ob_end_flush();
}else{
ob_end_clean();
$pdf->Output("ajusteinv.pdf");
}
}
}
$ajusteinv = new imprimirAjusteInv();
$ajusteinv -> codigo=$_GET["codigo"];
$ajusteinv -> traerImpresionAjusteInv();
?>