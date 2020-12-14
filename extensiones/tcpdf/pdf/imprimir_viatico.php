<?php
session_start();
if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"]=="ok"){

require_once "../../../controladores/control-viaticos.controlador.php";
require_once "../../../modelos/control-viaticos.modelo.php";
/*
include ('Numbers/Words.php');
*/


class imprimirViatico{

public $codigo;

public function traerImpresionViatico(){

//TRAEMOS LA INFORMACIÓN DE LA VENTA

$item1 = "id";
$item2 = "id_viatico";
$idviatico = $_GET["codigo"];

$respuestaV = ControladorViaticos::ctrGetViatico($item1, $idviatico);
$respuestaD = ControladorViaticos::ctrGetViaticoDet($item2, $idviatico);
$respuestaC = ControladorViaticos::ctrGetViaticoCheck($item2, $idviatico);

/*
$fecha = substr($respuestaVenta["fecha"],0,-8);
$productos = json_decode($respuestaVenta["productos"], true);
$neto = number_format($respuestaVenta["neto"],2);
$impuesto = number_format($respuestaVenta["impuesto"],2);
$total = number_format($respuestaVenta["total"],2);
*/
// Pie de página

//REQUERIMOS LA CLASE TCPDF
require_once('tcpdf_include.php');


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);


// set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, 0);	// 0 PARA QUITAR EL MARGEN INFERIOR
$pdf->setHeaderMargin(1.2);
//$pdf->setPrintHeader(false);		//sin pie de página
$pdf->SetFooterMargin(8);
//$pdf->setPrintFooter(false);		//sin pie de página
//$pdf->startPageGroup();
//$pdf->SetFont('helvetica', '', 8);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();

    
// ---------------------------------------------------------

$bloque1 = <<<EOF
<table>
<tr>
	<td style="width:120px"><img src="images/logo_nuno.png"></td>

	<td style="background-color:white; width:150px">

	<div style="font-size:8.5px; text-align:right; line-height:15px;">

	Av. Rio Coatan No.504, Col. 24 de Junio, Tuxtla Gutiérrez, Chiapas.

	</div>

	</td>

<td style="background-color:white; width:150px">

	<div style="font-size:8.5px; text-align:right; line-height:15px;">

	Teléfono: Tel: (961)-1407119

	<br>
	brunonunosco1998@gmail.com

	</div>

</td>

	<td style="background-color:white; width:130px; text-align:center; color:red"><br>VIATICO No.<br>$idviatico</td>

</tr>

</table>

EOF;

$pdf->writeHTML($bloque1, false, false, false, false, '');

$pdf->Ln(-2);

// ---------------------------------------------------------
if($respuestaV){
// ---------------------------------------------------------
$bloque2 = <<<EOF

	<h2 style="text-align:center;">   REPORTE DE GASTOS A COMPROBAR</h2>

EOF;
$pdf->writeHTML($bloque2, false, false, false, false, '');
$pdf->Ln(1);
// ---------------------------------------------------------

//var_dump($respuestaV);
$idTec=$respuestaV["id_tecnico"];
$nombreTec=$respuestaV["nombre"];
$fechaDispersion=date("d/m/Y", strtotime($respuestaV["fecha_dispersion"]));
$descripcion=$respuestaV["descripcion_dispersion"];
$idConcepto=$respuestaV["concepto_dispersion"];
if($idConcepto="1"){
    $concepto_dispersion="GASTOS A COMPROBAR";
}else{
    $concepto_dispersion="OTROS";
}
$id_usuario=$respuestaV["ultusuario"];
$usuario=$respuestaV["nomusuario"];

$bloque3 = <<<EOF
<table style="font-size:9px; padding:4px 4px;">
<tr>		
	<td style="border: 1px solid #666; width:425px">Comisionado: $idTec - $nombreTec</td>
	<td style="border: 1px solid #666; width:130px; text-align:left">Fecha Docto: $fechaDispersion
</td>
</tr>

<tr>
	<td style="border: 1px solid #666; width:270px">Dispersó: $id_usuario - $usuario</td>
	<td style="border: 1px solid #666; width:285px">Concepto: $idConcepto - $concepto_dispersion</td>
</tr>

<tr>
	<td style="border: 1px solid #666; width:555px">Motivo de la comisión: $descripcion</td>
</tr>

</table>
EOF;
$pdf->writeHTML($bloque3, false, false, false, false, '');
$pdf->Ln(2.5);
// ---------------------------------------------------------    

// ---------------------------------------------------------
$bloque2 = <<<EOF
<h4 style="text-align:center;">IMPORTES DISPERSADOS</h4>
EOF;
$pdf->writeHTML($bloque2, false, false, false, false, '');
$pdf->Ln(.5);
// ---------------------------------------------------------
$pdf->setCellPaddings(1, .2, 1, .2);	// set cell padding Left=2, Top=4, Right=6, Bottom=8 fila completa=196

// ------------------DISPERSADO ---------------------------------------
$bloque4 = <<<EOF
<table style="font-size:10px; padding:3px 3px;">
<tr bgcolor="#cccccc" class="text-center">
	<td style="border: 1px solid #666; width:32px; text-align:center">id</td>
	<td style="border: 1px solid #666; width:55px; text-align:center;">Fecha</td>
	<td style="border: 1px solid #666; width:108px; text-align:center;">Medio deposito</td>
	<td style="border: 1px solid #666; width:295px; text-align:center">Descripción</td>
	<td style="border: 1px solid #666; width:65px; text-align:center">Importe</td>
</tr>

</table>
EOF;
$pdf->writeHTML($bloque4, false, false, false, false, '');

// ---------------------------------------------------------
$totaldisperso=0;$TotalDisp=0;
foreach ($respuestaD as $row) {
$comentario=$row["comentario"];
$fecha=date("d-m-Y", strtotime($row["fecha"]));
$importeliberado=number_format($row["importe_liberado"],2,".",",");
$bloque5 = <<<EOF

<table style="font-size:9px; padding:3px 3px;">

<tr>
	<td style="border: 1px solid #666; width:32px; text-align:center">$row[id]</td>
	<td style="border: 1px solid #666; width:55px; text-align:center">$fecha</td>
	<td style="border: 1px solid #666; width:108px; text-align:center;">$row[establecimiento]</td>
	<td style="border: 1px solid #666; width:295px; text-align:left">$comentario</td>
	<td style="border: 1px solid #666; width:65px; text-align:right">$$importeliberado</td>
</tr>

</table>

EOF;
$totaldisperso+=$row['importe_liberado'];
$TotalDisp = number_format($totaldisperso,2,".",",");    
$pdf->writeHTML($bloque5, false, false, false, false, '');

}
// ---------------------------------------------------------
$bloque6 = <<<EOF

<table style="font-size:9px; padding:3px 3px;">

<tr bgcolor="#cccccc">
	<td style="border: 1px solid #666; width:490px; text-align:right">Total Dispersado:</td>
	<td style="border: 1px solid #666; width:65px; text-align:right">$$TotalDisp</td>
</tr>

</table>

EOF;
$pdf->writeHTML($bloque6, false, false, false, false, '');    
$pdf->Ln(2.5);
// ---------------------------------------------------------

// ---------------------------------------------------------
$bloque7 = <<<EOF
<h4 style="text-align:center;">COMPROBACIÓN DE GASTOS</h4>
EOF;
$pdf->writeHTML($bloque7, false, false, false, false, '');
$pdf->Ln(.5);
// ---------------------------------------------------------

// ------------------  COMPROBACION  -------------------------------
$bloque8 = <<<EOF
<table style="font-size:10px; padding:3px 3px;">
<tr bgcolor="#cccccc" class="text-center">
	<td style="border: 1px solid #666; width:34px; text-align:center">id</td>
	<td style="border: 1px solid #666; width:55px; text-align:center;">Fecha</td>
	<td style="border: 1px solid #666; width:85px; text-align:center;"># Docto.</td>
	<td style="border: 1px solid #666; width:315px; text-align:center">Motivo del Gasto</td>
	<td style="border: 1px solid #666; width:65px; text-align:center">Importe</td>
</tr>

</table>
EOF;
$pdf->writeHTML($bloque8, false, false, false, false, '');

// ---------------------------------------------------------
$totalgasto=$TotalComprobado=0;
foreach ($respuestaC as $row) {
$fecha=date("d-m-Y", strtotime($row["fecha_gasto"]));
$conceptogasto=$row["concepto_gasto"];
$importegasto=number_format($row["importe_gasto"],2,".",",");
$bloque9 = <<<EOF

<table style="font-size:9px; padding:3px 3px;">

<tr>
	<td style="border: 1px solid #666; width:34px; text-align:center">$row[id]</td>
	<td style="border: 1px solid #666; width:55px; text-align:center">$fecha</td>
	<td style="border: 1px solid #666; width:85px; text-align:left">$row[numerodocto]</td>
	<td style="border: 1px solid #666; width:315px; text-align:left">$conceptogasto</td>
	<td style="border: 1px solid #666; width:65px; text-align:right">$$importegasto</td>
</tr>

</table>

EOF;
$totalgasto+=$row['importe_gasto'];
$TotalComprobado = number_format($totalgasto,2,".",",");    
$pdf->writeHTML($bloque9, false, false, false, false, '');
}
// ---------------------------------------------------------
$diferencia=$totaldisperso-$totalgasto;
if($diferencia>0){
    $difporcomprobar="$".number_format($diferencia,2,".",",");
    $difafavor="----------------";
}else{
    $difafavor="$".number_format(($diferencia*-1),2,".",",");
    $difporcomprobar="-----------------";
}
$bloque10 = <<<EOF

<table style="font-size:9px; padding:3px 3px;">

<tr bgcolor="#cccccc">
	<td style="border: 1px solid #666; width:489px; text-align:right">Total Ejercido:</td>
	<td style="border: 1px solid #666; width:65px; text-align:right">$$TotalComprobado</td>
</tr>
<tr bgcolor="#EAEDF0">
	<td style="border: 1px solid #666; width:489px; text-align:right">Diferencia por comprobar o reintegrar:</td>
	<td style="border: 1px solid #666; width:65px; text-align:right">$difporcomprobar</td>
</tr>
<tr bgcolor="#F4F6F8">
	<td style="border: 1px solid #6666; width:489px; text-align:right">Diferencia a favor del comisionista:</td>
	<td style="border: 1px solid #6666; width:65px; text-align:right">$difafavor</td>
</tr>

</table>
EOF;
$pdf->writeHTML($bloque10, false, false, false, false, '');    
$pdf->Ln(3.5);
$pdf->SetFont('helvetica', '', 8);
$pdf->MultiCell(200,0,'COMENTARIO:_______________________________________________________________________________________________________________', '', 'L', 0, 1, '', '', true, 0, false, true, 0, 'M');
$pdf->Ln(3);

// ---------------------------------------------------------
$bloque11 = <<<EOF

<table style="font-size:9px; padding:3px 3px;">
<tr>
	<td style="border: 1px solid #666;width:265px; height:50px; text-align:center">Nombre y firma quien comprueba</td>
	<td style="width:25px;height:50px; text-align:center"></td>
	<td style="border: 1px solid #666;width:265px; height:50px; text-align:center">Nombre y firma quien Revisa</td>
</tr>
</table>

EOF;

$pdf->writeHTML($bloque11, false, false, false, false, '');     
// ---------------------------------------------------------    
if(isMobile()){
$nombre_archivo="viatico0".trim($idviatico).".pdf";   //genera el nombre del archivo para descargarlo
$pdf->Output($nombre_archivo,'D');
}else{
//SALIDA DEL ARCHIVO 
$nombre_archivo="viatico0".trim($idviatico).".pdf";   //genera el nombre del archivo para descargarlo
$pdf->Output($nombre_archivo);
}  // fin del if isMobile
}else{
$bloque12 = <<<EOF
<h2 style="text-align:center;">NO HAY INFORMACION</h2>
EOF;
$pdf->writeHTML($bloque12, false, false, false, false, '');
$pdf->Ln(2);
$pdf->Output("viatico.pdf");
}
}
}

$viatico = new imprimirViatico();
//$viatico -> codigo = $_GET["codigo"];
$viatico -> traerImpresionViatico();

}else{
	//include '../../../vistas/plantilla.php';
	echo "no tienes acceso a este reporte.";
}

//Funcion para saber si es movil o web
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini
|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}	
?>
