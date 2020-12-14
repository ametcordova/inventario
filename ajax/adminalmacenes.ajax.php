<?php
session_start();
require_once "../controladores/almacen.controlador.php";
require_once "../modelos/almacen.modelo.php";

require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";
require_once '../funciones/funciones.php';

if(isset( $_GET["almacenSel"])){
	$tabla="usuarios";
	$module="rentradas";
	$campo="reportes";
	$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);    

    $item = null;
	$valor = null;
	$tabla = trim($_GET['almacenSel']);

    $respuesta = ControladorAlmacen::ctrMostrarEntradas($tabla,$item, $valor);
    
    //var_dump($respuesta);
    $data= Array();
    foreach ($respuesta as $key => $value) {
			$fechaEntro = date('d-m-Y', strtotime($value["fechaentrada"]));
			$boton=getAccess($acceso, ACCESS_PRINTER)?'<button class="btn btn-success btn-sm btnImprimir" idNumDocto="'.$value["numerodocto"].'" title="Generar entrada en PDF "><i class="fa fa-file-pdf-o"></button>':'';
			$data[]=array(
 				"0"=>'<td style="width:10px;">'.($key+1).'</td>',
 				"1"=>'<td style="width:10px;">'.$value["id_proveedor"].'</td>',
 				"2"=>'<td style="width:10px;">'.$value["nombre"].'</td>',
 				"3"=>'<td style="width:390px;">'.$value["numerodocto"].'</td>',
 				"4"=>'<td style="width:50px;">'.$fechaEntro.'</td>',
 				"5"=>'<td style="width:100px; text-align: center;">'.$value["entro"].'</td>',
				"6"=>'<td style="text-align:right; width:80px;">'.$value["almacen"].'</td>',
 				"7"=>$boton,
 				);        
    }
    
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
    
 		echo json_encode($results);
    
    //echo $respuesta ? "Núm. de Docto. ya Existe" : 0;

}