<?php
require_once "../controladores/almacen.controlador.php";
require_once "../modelos/almacen.modelo.php";

if(isset( $_GET["almacenSel"])){

    $item = null;
	$valor = null;
	$tabla = trim($_GET['almacenSel']);

    $respuesta = ControladorAlmacen::ctrMostrarAlmacen($tabla,$item, $valor);
    
    //var_dump($respuesta);
    $data= Array();
    foreach ($respuesta as $key => $value) {
	  	/*=============================================
 	 		STOCK
  			=============================================*/ 
			$minimo=round($value["minimo"],0); 
			$media=($value["minimo"]/2);

  			if($value["cant"] <= $media){

  				//$stock = "<button class='btn btn-danger btn-sm' title='Mínimo $minimo'>".$value["cant"]."</button>";
				$stock="<span class='badge badge-danger' title='Mínimo $minimo'>".$value["cant"]."</span>";	

  			}else if($value["cant"] > $media && $value["cant"] <= $minimo){

  				//$stock = "<button class='btn btn-warning btn-sm' title='Mínimo $minimo'>".$value["cant"]."</button>";
				$stock="<span class='badge badge-warning' title='Mínimo $minimo'>".$value["cant"]."</span>";	

  			}else{

  				//$stock = "<button class='btn btn-success btn-sm' title='Mínimo $minimo'>".$value["cant"]."</button>";
				$stock="<span class='badge badge-success' title='Mínimo $minimo'>".$value["cant"]."</span>";	

  			}
            $fechaEntro = date('d-m-Y', strtotime($value["fecha_entrada"]));
			$data[]=array(
 				"0"=>$value["id"],
 				"1"=>'<td style="width:10px;">'.$value["id_producto"].'</td>',
 				"2"=>'<td style="width:10px;">'.$value["codigointerno"].'</td>',
 				"3"=>'<td class="" style="width:410px; text-align: left !important;">'.$value["descripcion"].'</td>',
 				"4"=>'<td style="width:50px;">'.$value["medida"].'</td>',
 				"5"=>'<td class="text-center" style="width:90px;">'.$stock.'</td>',
 				"6"=>'<span class="badge badge-dark">'.$minimo.'</span>',
 				"7"=>'<td style="text-align:right; width:80px; ">'.$value["precio_compra"].'</td>',
 				"8"=>'<td style="width:50px;">'.$fechaEntro.'</td>',
 				);        
    }
    
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar  "6"=>'<button class="btn btn-success btn-sm">'.$minimo.'</button>',
 			"aaData"=>$data);
    
 		echo json_encode($results);
    
    //echo $respuesta ? "Núm. de Docto. ya Existe" : 0;

}