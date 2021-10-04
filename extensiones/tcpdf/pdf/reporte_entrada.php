<?php
session_start();
if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"]=="ok"){
setlocale(LC_ALL,"es_ES");

require_once "../../../controladores/entradasalmacen.controlador.php";
require_once "../../../modelos/entradasalmacen.modelo.php";

class reporteEntrada{

public $codigo;

public function traerReporteEntrada(){

//TRAEMOS LA INFORMACIÓN DE LA VENTA

$item = "id_entrada";
$numeroid = $_GET["codigo"];

$respuestaAlmacen = ControladorEntradasAlmacen::ctrReporteEntradaAlmacen($item, $numeroid);


//REQUERIMOS LA CLASE TCPDF
require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetFooterMargin(8);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//$pdf->startPageGroup();

$pdf->AddPage();

if($respuestaAlmacen){    
// ---------------------------------------------------------
$nombreAlmacen=$respuestaAlmacen[0]["nombrealmacen"];
if($nombreAlmacen=="ALM_VILLAH" || $nombreAlmacen="ALM_COMAL"){
	$img="images/logo_siesur.jpg";
}else{
	$img="images/logo_nuno.png";
}

$ubicacion=$respuestaAlmacen[0]["ubicacion"];
$tel_alm=$respuestaAlmacen[0]["tel_alm"];
$email_alm=$respuestaAlmacen[0]["email_alm"];

$bloque1 = <<<EOF

	<table>
		
		<tr>

			<td style="width:140px"><img src="$img"></td>

			<td style="background-color:white; width:160px">
				
				<div style="font-size:8.5px; text-align:center; line-height:15px;">
                    $ubicacion
				</div>

			</td>

			<td style="background-color:white; width:140px">

				<div style="font-size:8.5px; text-align:right; line-height:15px;">
					Teléfono: $tel_alm
					<br>
					$email_alm
				</div>
				
			</td>

			<td style="background-color:white; width:110px; text-align:center; color:red"><br>ENTRADA No.<br>$numeroid</td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($bloque1, false, false, false, false, '');
$pdf->Ln();
    
// ---------------------------------------------------------
$bloque2 = <<<EOF

<h2 style="text-align:center;">REPORTE DE ENTRADAS AL ALMACEN</h2>

EOF;

$pdf->writeHTML($bloque2, false, false, false, false, '');
$pdf->Ln(2);
// ---------------------------------------------------------

$idProv=$respuestaAlmacen[0]["id_proveedor"];
$nomProv=$respuestaAlmacen[0]["nombreproveedor"];
$FechDocto=date("d/m/Y", strtotime($respuestaAlmacen[0]["fechaentrada"]));
$idAlmacen=$respuestaAlmacen[0]["id_almacen"];
$nametype=$respuestaAlmacen[0]["nombretipo"];
$observacion=$respuestaAlmacen[0]["observacion"];
$fechEntrada=date("d/m/Y", strtotime($respuestaAlmacen[0]["fechaentrada"]));
$recibio=$respuestaAlmacen[0]["nombreusuario"];

$bloque3 = <<<EOF

	<table style="font-size:9px; padding:3px 4px;">
    
    <tr>		
		<td style="border: 1px solid #666; width:385px">Proveedor: $idProv-$nomProv</td>
		<td style="border: 1px solid #666; width:155px; text-align:left">T.Mov: $nametype</td>
	</tr>
	
	<tr>

	   <td style="border: 1px solid #666; width:125px; text-align:left">Fecha Docto: $FechDocto</td>
	   <td style="border: 1px solid #666; width:200px">Fecha recepción en Almacen: $fechEntrada</td>
        <td style="border: 1px solid #666; width:215px">Almacen de Entrada: $idAlmacen - $nombreAlmacen</td>
	</tr>

 	<tr>
		<td style="border: 1px solid #666; width:140px">Capturo: $recibio</td>
		<td style="border: 1px solid #666; width:400px">Observación: $observacion</td>
        
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
$cantEntra=$filas=0;
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
$filas++;
$pdf->writeHTML($bloque5, false, false, false, false, '');
}
// ---------------------------------------------------------
   
//$totales = number_format($totales, 2);

$bloque6 = <<<EOF

	<table style="font-size:9px; padding:5px 5px;">

	<tr bgcolor="#cccccc">
		<td style="border: 1px solid #666; width:380px; text-align:right">Total registros:</td>
		<td style="border: 1px solid #666; width:40px; text-align:center">$filas</td>
		<td style="border: 1px solid #666; width:75px; text-align:right">Total Entrada:</td>
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

$entrada = new reporteEntrada();
$entrada -> codigo = $_GET["codigo"];
$entrada -> traerReporteEntrada();

}else{
	echo "no tienes acceso a este reporte.";
}


?>