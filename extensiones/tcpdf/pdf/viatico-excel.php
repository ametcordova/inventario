<?php
session_start();
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=formato-viaticos.xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"]=="ok"){
setlocale(LC_ALL,"es_ES");
require_once "../../../controladores/control-viaticos.controlador.php";
require_once "../../../modelos/control-viaticos.modelo.php";

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

?>

<table style="font-size:18px; padding:4px 4px;">

	<tr>
		<td colspan="5" style="background-color:white; text-align:center; line-height:15px;">
			Av. Rio Coatan No.504, Col. 24 de Junio, Tuxtla Guti&eacute;rrez, Chiapas.
		</td>
		<td rowspan="2" style="background-color:white; text-align:center; color:red">
			VIATICO No. <br><?php echo $idviatico?>
		</td>
	</tr>

	<tr>
		<td colspan="5" style="background-color:white; text-align:center; line-height:15px;">
			Tel&eacute;fono: Tel: 961-1407119.      email: brunonunosco1998@gmail.com
		</td>
	</tr>	

</table>


<?php
// ----------------------------------------------------------------
if($respuestaV){
?>
<!----------------------------------------------------------------->
	<h2 style="text-align:center;">REPORTE DE GASTOS A COMPROBAR </h2>
<!--  --------------------------------------------------------- -->
<?php
//var_dump($respuestaV);
$fecharep=date('d-m-Y');
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

?>
<table style="font-size:15px; padding:4px 4px;">
<tr>		
	<td colspan="4" style="border: 1px solid #666;">Comisionado: <?php echo $idTec."-".$nombreTec;?> </td>
	<td style="border: 1px solid #666; text-align:left">Fecha Docto: <?php echo $fechaDispersion; ?> </td>
	<td style="border: 1px solid #666; text-align:left">Fecha Rep: <?php echo $fecharep ;?> </td>
</tr>

<tr>
	<td colspan="4" style="border: 1px solid #666; ">Dispers&oacute;: <?php echo $id_usuario."-".$usuario;?></td>
	<td colspan="2" style="border: 1px solid #666; text-align:left">Concepto: <?php echo $idConcepto."-".$concepto_dispersion;?></td>
</tr>

<tr>
	<td colspan="6" style="border: 1px solid #666; ">Motivo de la comisi&oacute;n: <?php echo $descripcion;?> </td>
</tr>

</table>
<!-- ---------------------------------------------------------  -->

<!-- --------------------------------------------------------- -->
<h4 style="text-align:center;">IMPORTES DISPERSADOS</h4>
<!-- ------------------DISPERSADO --------------------------------------- -->
<table style="font-size:15px; padding:3px 3px;">
<tr bgcolor="#cccccc" class="text-center">
	<th colspan="1" style="border: 1px solid #666; text-align:center">id</th>
	<th style="border: 1px solid #666; text-align:center;">Fecha</th>
	<th style="border: 1px solid #666; text-align:center;">Medio deposito</th>
	<th colspan="2" style="border: 1px solid #666; text-align:center">Descripci&oacute;n</th>
	<th style="border: 1px solid #666; text-align:center">Importe</th>
</tr>

</table>

<!-- --------------------------------------------------------- -->
<?php
$totaldisperso=0;$TotalDisp=$item=0;
foreach ($respuestaD as $row) {
$item++;
$comentario=$row["comentario"];
$fecha=date("d-m-Y", strtotime($row["fecha"]));
$importeliberado=number_format($row["importe_liberado"],2,".",",");
?>

<table style="font-size:15px; padding:3px 3px;">

<tr>
	<td colspan="1" style="border: 1px solid #666; text-align:center"><?php echo $item;?> </td>
	<td style="border: 1px solid #666; text-align:center"><?php echo $fecha;?></td>
	<td style="border: 1px solid #666; text-align:center;"><?php echo $row['establecimiento'];?></td>
	<td colspan="2" style="border: 1px solid #666; text-align:left"><?php echo $comentario;?></td>
	<td style="border: 1px solid #666; text-align:right"><?php echo "$".$importeliberado;?></td>
</tr>

</table>

<?php
$totaldisperso+=$row['importe_liberado'];
$TotalDisp = number_format($totaldisperso,2,".",",");    
}
?>
<!-- --------------------------------------------------------- -->

<table style="font-size:15px; padding:3px 3px;">

<tr bgcolor="#cccccc">
	<td colspan="5" style="border: 1px solid #666; text-align:right">Total Dispersado:</td>
	<td style="border: 1px solid #666; text-align:right"><b><?php echo "$".$TotalDisp;?></b></td>
</tr>

</table>

<!-- --------------------------------------------------------- -->

<h4 style="text-align:center;">COMPROBACI&Oacute;N DE GASTOS</h4>

<!-- ------------------  COMPROBACION  ------------------------------- -->
<table style="font-size:15px; padding:3px 3px;">
<tr bgcolor="#cccccc" class="text-center">
	<td colspan="1" style="border: 1px solid #666; text-align:center">id</td>
	<td style="border: 1px solid #666; text-align:center;">Fecha</td>
	<td style="border: 1px solid #666; text-align:center;"># Docto.</td>
	<td colspan="2" style="border: 1px solid #666; text-align:center">Motivo del Gasto</td>
	<td style="border: 1px solid #666; text-align:center">Importe</td>
</tr>

</table>

<!-- --------------------------------------------------------- -->
<?php
$totalgasto=$TotalComprobado=$item=0;
foreach ($respuestaC as $row) {
$fecha=date("d-m-Y", strtotime($row["fecha_gasto"]));
$conceptogasto=$row["concepto_gasto"];
$importegasto=number_format($row["importe_gasto"],2,".",",");
$item++;
?>

<table style="font-size:15px; padding:3px 3px;">

<tr>
	<td colspan="1" style="border: 1px solid #666; text-align:center"><?php echo $item;?></td>
	<td style="border: 1px solid #666; text-align:center"><?php echo $fecha;?></td>
	<td style="border: 1px solid #666; text-align:left"><?php echo $row['numerodocto'];?></td>
	<td colspan="2" style="border: 1px solid #666; text-align:left"><?php echo $conceptogasto;?></td>
	<td style="border: 1px solid #666; text-align:right"><?php echo "$".$importegasto;?></td>
</tr>

</table>

<?php
$totalgasto+=$row['importe_gasto'];
$TotalComprobado = number_format($totalgasto,2,".",",");    
}
//---------------------------------------------------------
$diferencia=$totaldisperso-$totalgasto;
if($diferencia>0){
    $difporcomprobar="$".number_format($diferencia,2,".",",");
    $difafavor="------------------";
}else{
    $difafavor="$".number_format(($diferencia*-1),2,".",",");
    $difporcomprobar="-------------------";
}
?>

<table style="font-size:15px; padding:3px 3px;">

<tr bgcolor="#cccccc">
	<td colspan="5" style="border: 1px solid #666; text-align:right">Total Ejercido:</td>
	<td style="border: 1px solid #666; text-align:right"><b><?php echo "$".$TotalComprobado;?></b></td>
</tr>
<tr bgcolor="#EAEDF0">
	<td colspan="5" style="border: 1px solid #666; text-align:right">Diferencia por comprobar o reintegrar:</td>
	<td style="border: 1px solid #666; text-align:right"><b><?php echo $difporcomprobar;?></b></td>
</tr>
<tr bgcolor="#EAEDF0">
	<td colspan="5" style="border: 1px solid #6666; text-align:right">Diferencia a favor del comisionista:</td>
	<td style="border: 1px solid #6666; text-align:right"><b><?php echo $difafavor;?></b></td>
</tr>

</table>

<!-- --------------------------------------------------------- -->
<br>
<table style="font-size:15px; padding:3px 3px;">
<tr>
	<td colspan="3" style="border: 1px solid #666;height:100px; text-align:center">Nombre y firma quien comprueba</td>
	<td style="width:10px; height:100px; text-align:center"></td>
	<td colspan="2" style="border: 1px solid #666;height:100px; text-align:center">Nombre y firma quien Revisa</td>
</tr>
</table>
<!-- --------------------------------------------------------- -->

<?php
}else{
?>
<h2 style="text-align:center;">NO HAY INFORMACION</h2>
<?php
}
}
}

$viatico = new imprimirViatico();
$viatico -> traerImpresionViatico();

}else{
	echo "no tienes acceso a este reporte.";
}

?>

