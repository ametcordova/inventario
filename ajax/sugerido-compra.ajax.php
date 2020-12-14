<?php
require_once "../controladores/reporteinventario.controlador.php";
require_once "../modelos/reporteinventario.modelo.php";

if(isset( $_GET["almacenInv"])){

    $tabla = trim(strtolower($_GET['almacenInv']));
    $item ="id_familia";
	$valor = $_GET['familiaInv'];
	
	//var_dump($valor);
    $respuesta = ControladorInventario::ctrMostrarInventario($tabla,$item, $valor);
    
    //var_dump($respuesta);
    $data= Array();
    foreach ($respuesta as $key => $value) {
            //$fechaEntro = date('d-m-Y', strtotime($value["fechaentrada"]));
        
		$resurtir = ($value["surtir"]<0)? "<button class='btn btn-danger btn-sm'>".$value["surtir"]."</button>" : "<button class='btn btn-success btn-sm'>".$value["surtir"]."</button>";
        //$importeExist=number_format($value["cant"]*$value["precio_compra"],2,".",",");
        if($value["surtir"]<1){
            $importeExist=abs($value["surtir"])*$value["precio_compra"];
        }else{
            $importeExist=0.00;
        }
		
		$data[]=array(
 			"0"=>'<td style="width:10px;">'.($key+1).'</td>',
 			"1"=>'<td style="width:10px;">'.$value["id_familia"].'</td>',
 			"2"=>'<td style="width:10px;">'.$value["id_categoria"].'</td>',
 			"3"=>'<td style="width:95px;">'.$value["codigointerno"].'</td>',
 			"4"=>'<td style="width:320px;">'.$value["descripcion"].'</td>',
 			"5"=>'<td style="width:125px;">'.$value["medida"].'</td>',
 			"6"=>'<td style="width:70px; text-align: center;">'.$value["cant"].'</td>',	
		    "7"=>'<td style="width:70px; text-align: center; background-color: yellow; color:red;">'.$value["stock"].'</td>',
 			"8"=>$resurtir,
 			"9"=>'<td style="text-align:right; width:80px;">'.number_format($value["precio_compra"],2,".",",").'</td>',
 			"10"=>number_format($importeExist,2,".",","),
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