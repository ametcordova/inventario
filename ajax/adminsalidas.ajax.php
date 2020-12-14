<?php
session_start();
require_once "../controladores/salidas.controlador.php";
require_once "../modelos/salidas.modelo.php";

require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";
require_once '../funciones/funciones.php';

if(isset( $_GET["almacenSel"])){

	$tabla="usuarios";
	$module="rsalidas";
	$campo="reportes";
	$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);    

    $item = "id_tecnico";
	$valor = trim($_GET['tecnicoSel']);
	$tabla = trim($_GET['almacenSel']);

    $respuesta = ControladorSalidas::ctrMostrarSalidas($tabla,$item, $valor);
    
    //var_dump($respuesta);
    $data= Array();
    foreach ($respuesta as $key => $value) {
			$fechaSalio = date('d-m-Y', strtotime($value["fecha_salida"]));
			$boton=getAccess($acceso, ACCESS_PRINTER)?'<button class="btn btn-info btn-sm btnImprimirNotSal" idNumSalida="'.$value["num_salida"].'" title="Generar salida en PDF "><i class="fa fa-file-pdf-o"></button>':"";
			$data[]=array(
 				"0"=>'<td style="width:10px;">'.($key+1).'</td>',
 				"1"=>'<td style="width:10px;">'.$value["id_tecnico"].'</td>',
 				"2"=>'<td style="width:10px;">'.$value["nombretecnico"].'</td>',
 				"3"=>'<td style="width:390px;">'.$value["num_salida"].'</td>',
 				"4"=>'<td style="width:50px;">'.$fechaSalio.'</td>',
 				"5"=>'<td style="width:100px; text-align: center;">'.$value["salio"].'</td>',
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