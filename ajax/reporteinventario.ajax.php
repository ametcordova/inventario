<?php
require_once "../controladores/reporteinventario.controlador.php";
require_once "../modelos/reporteinventario.modelo.php";

if(isset( $_GET["almacenSel"])){

    $item = "cant";
	$valor = $_GET["tiporeporte"];
	$tabla = trim(strtolower($_GET['almacenSel']));

    $respuesta = ControladorInventario::ctrMostrarInventario($tabla,$item, $valor);
    
    //var_dump($respuesta);
    $data= Array();
    foreach ($respuesta as $key => $value) {
            //$fechaEntro = date('d-m-Y', strtotime($value["fechaentrada"]));
			//$resurte=($resurtir<1)?"<td style='width:70px; text-align: center; color:red;' class='btn btn-danger btn-sm'>".$value["surtir"]."</td>" : "<td style='width:70px; text-align: center;' class='btn btn-danger btn-sm'>".$value["surtir"]."</td>";
			$stock = ($value["surtir"]<1)? "<button class='btn btn-danger btn-sm'>".$value["surtir"]."</button>" : "<button class='btn btn-success btn-sm'>".$value["surtir"]."</button>";
			$data[]=array(
 				"0"=>'<td style="width:10px;">'.($key+1).'</td>',
 				"1"=>'<td style="width:10px;">'.$value["id_categoria"].'</td>',
 				"2"=>'<td style="width:95px;">'.$value["codigointerno"].'</td>',
 				"3"=>'<td style="width:320px;">'.$value["descripcion"].'</td>',
 				"4"=>'<td style="width:125px;">'.$value["medida"].'</td>',
 				"5"=>'<td style="width:70px; text-align: center; background-color: yellow; color:red;">'.$value["minimo"].'</td>',
 				"6"=>'<td style="width:70px; text-align: center;">'.$value["cant"].'</td>',	
 				"7"=>$stock
 				);        
    }
		//"8"=>'<td style="text-align:right; width:80px;">'.$value["precio_compra"].'</td>',
		
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
    
 		echo json_encode($results);
    
    //echo $respuesta ? "Núm. de Docto. ya Existe" : 0;

}