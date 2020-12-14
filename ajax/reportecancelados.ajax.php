<?php
session_start();
date_default_timezone_set("America/Mexico_City");
$fechaHoy=date("Y-m-d");

require_once "../controladores/salidas.controlador.php";
require_once "../modelos/salidas.modelo.php";
require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";
require_once '../funciones/funciones.php';


switch ($_GET["op"]){

    case 'listarcancelaciones':

		//VALIDA ACCESO
		$tabla="usuarios";
		$module="rcancela";
		$campo="reportes";

		$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);
		
		$item = null;
    	$valor = null;
		$orden = "id";
		if(isset($_POST["FechDev1"])){
			$fechadev1=$_POST["FechDev1"];	
			$fechadev2=$_POST["FechDev2"];
		}else{
			$fechadev1=$fechaHoy;
			$fechadev2=$fechaHoy;
		}
		
  		$listarcancelaciones = ControladorSalidas::ctrListarCancelaciones($item, $valor, $orden, $fechadev1, $fechadev2);	
		  
  		if(count($listarcancelaciones) == 0){
			//echo json_encode($listarDevTec); 
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    
        
        foreach($listarcancelaciones as $key => $value){
			$fechasalida = date('d-m-Y', strtotime($value["fecha_salida"]));
			$totdeventa=number_format($value['sinpromo']+$value['promo'],2,".",",");
			$tri = '<tr class="table-success"><td>'.($value["id_cliente"]).'</td>';
			$nombre=$value['nombre'];

			$botones=getAccess($acceso, ACCESS_PRINTER)?"<td><button class='btn btn-success btn-sm btnImprimirCancelacion' idNumCancela='".$value['num_cancelacion']."' title='Generar PDF '><i class='fa fa-file-pdf-o'></i></button></td></tr>":"";
			
		  	$data[]=array(
				  $value["num_cancelacion"],
				  $tri,
			      $nombre,
                  $value["num_salida"],
                  $fechasalida,
			      $value["cant"],
			      $totdeventa,
			      $value["ultmodificacion"],
                  $botones,
           );
        }
    
        $results = array(
					"sEcho"=>1, //Información para el datatables
					"iTotalRecords"=>count($data), //enviamos el total registros al datatable
					"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
					"aaData"=>$data);
    echo json_encode($results);        
            
    break;	

  
}  //FIN DE SWITCH  
