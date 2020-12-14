<?php

require_once "../../../controladores/salidasalmacen.controlador.php";
require_once "../../../modelos/salidasalmacen.modelo.php";
require_once "../../../funciones/funciones.php";

class imprimirSalida{

public $codigo;

public function traerImpresionSalida(){

$fechahoy=fechaHoraMexico(date("d-m-Y G:i:s"));

//TRAEMOS LA INFORMACIÓN DE LA SALIDA
$campo = "id_salida";
$valor = $_GET["codigo"];

$respuestaAlmacen = ControladorSalidasAlmacen::ctrPrintSalidaAlmacen($campo, $valor);



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

			<td style="background-color:white; width:110px; text-align:center; color:red"><br><br>SALIDA No.<br>$valor</td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($bloque1, false, false, false, false, '');

$pdf->Ln(2);

// ---------------------------------------------------------
$bloque2 = <<<EOF

<h2 style="text-align:center;">REPORTE DE SALIDA DE ALMACEN</h2>


EOF;

$pdf->writeHTML($bloque2, false, false, false, false, '');
$pdf->Ln(2);
// ---------------------------------------------------------

//var_dump($respuestaAlmacen);
$idTecnico=$respuestaAlmacen[0]["id_tecnico"];
$nomTecnico=$respuestaAlmacen[0]["nombretecnico"];
//$FechDocto=date("d/m/Y", strtotime($respuestaAlmacen[0]["fechadocto"]));
$idAlmacen=$respuestaAlmacen[0]["id_almacen"];
$nombreAlmacen=$respuestaAlmacen[0]["nombrealma"];
$fechaSalida=date("d/m/Y", strtotime($respuestaAlmacen[0]["fechasalida"]));
$usuario=$respuestaAlmacen[0]["nombreusuario"];
$id_tipomov=$respuestaAlmacen[0]["id_tipomov"];
$nombre_tipo=$respuestaAlmacen[0]["nombre_tipo"];
$motivo=$respuestaAlmacen[0]['motivo'];

$bloque3 = <<<EOF

	<table style="font-size:9px; padding:5px 5px;">

	<tr>		
	<td style="text-align: right;">Fecha impresión: $fechahoy</td>
	</tr>
	
    <tr>		
		<td style="border: 1px solid #666; width:270px">Técnico: $idTecnico - $nomTecnico</td>
		<td style="border: 1px solid #666; width:270px">Fecha Salida de Almacen: $fechaSalida</td>
	</tr>
	
	<tr>
	   <td style="border: 1px solid #666; width:270px">Tipo Mov.: $id_tipomov - $nombre_tipo</td>

        <td style="border: 1px solid #666; width:270px">Almacen de Salida: $idAlmacen - $nombreAlmacen</td>
	</tr>

 	<tr>
		<td style="border: 1px solid #666; width:340px">Motivo: $motivo</td>
        <td style="border: 1px solid #666; width:200px">Capturo: $usuario</td>
	</tr>

	</table>

EOF;

$pdf->writeHTML($bloque3, false, false, false, false, '');
$pdf->Ln(4);
// ---------------------------------------------------------    
//Usuario: $usuario    
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
$cantSalida=0;
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
$cantSalida+=$row['cantidad'];
$pdf->writeHTML($bloque5, false, false, false, false, '');
}
// ---------------------------------------------------------
   
//$totales = number_format($totales, 2);

$bloque6 = <<<EOF

	<table style="font-size:9px; padding:5px 5px;">

	<tr bgcolor="#cccccc">
		<td style="border: 1px solid #666; width:495px; text-align:right">Total Salida:</td>
		<td style="border: 1px solid #666; width:45px; text-align:center">$cantSalida</td>
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
        <td style="border: 1px solid #666;width:260px; height:50px; text-align:center">Nombre y firma quien Entrega</td>
    </tr>

 	</table>

EOF;

$pdf->writeHTML($bloque7, false, false, false, false, '');     
// ---------------------------------------------------------    
//SALIDA DEL ARCHIVO 
 $nombre_archivo="reporte_salida".trim($valor).".pdf";   //genera el nombre del archivo para descargarlo
 $pdf->Output($nombre_archivo);
}else{
  
$js = <<<EOD
	app.alert('No se encontraron datos', 3, 0, 'Welcome');
EOD;

// set javascript
$pdf->IncludeJS($js);
 //$nombre_archivo="entrada";
$pdf->Output("salida.pdf","I");
//var_dump($respuestaAlmacen);
  
}
}

}

$salida = new imprimirSalida();
$salida -> codigo = $_GET["codigo"];
$salida -> traerImpresionSalida();

?>