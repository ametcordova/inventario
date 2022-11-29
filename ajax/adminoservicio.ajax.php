<?php
session_start();

date_default_timezone_set("America/Mexico_City");

$fechaHoy=date("Y-m-d");

require_once "../controladores/adminoservicios.controlador.php";
require_once "../modelos/adminoservicios.modelo.php";

require_once "../controladores/entradasalmacen.controlador.php";
require_once "../modelos/entradasalmacen.modelo.php";

require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";
require_once '../funciones/funciones.php';

switch ($_GET["op"]){

	case 'listaroservicios':

		$tabla="usuarios";
		$module="pcostuxtla";
		$campo="administracion";
		$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);
			  
		$item = "id";
    	$valor = $_SESSION['user'];
		$orden = "id";
		if(isset($_POST["FechDev1"])){
			$fechadev1=$_POST["FechDev1"];	
			$fechadev2=$_POST["FechDev2"];
		}else{
			$fechadev1=$fechaHoy;
			$fechadev2=$fechaHoy;
		}

  		$listarseries = ControladorOServicios::ctrListarOServicios($item, (int)$valor, $orden, $fechadev1, $fechadev2);	
		  
  		if(count($listarseries) == 0){

  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    
        
        foreach($listarseries as $key => $value){

            $fecha = date('d-m-Y', strtotime($value["fecha_instalacion"]));
            $nombre = substr($value["tecnico"],0,30);    //extrae el primer nombre del tecnico
			$fact=$value["factura"];
			$fagr=$fecha;
			$capturo='<h6><span class="badge badge-info" title="'.$value["capturo"].'">'.$value["ultusuario"].'</span></h6>';
			
			//$tri = '<tr class="table-success"><td>'.($value["id"]).'</td>';
            $botonestado=$value["estatus"]==0?"<button class='btn btn-dark btn-sm px-1 py-1 btnEstado' data-id='".$value['id']."' data-estado=".$value['estatus']." title='Fact. $fact' style='font-size:.7rem;'> <i class='fa fa-folder-o'></i> Facturado</button>":
			"<button class='btn btn-success px-1 py-1 btn-sm btnEstado' data-id='".$value['id']."' data-estado=".$value['estatus']." title='Activo'  style='font-size:.7rem;'> <i class='fa fa-check-circle-o'></i> Por Facturar</button>"; 

			if(getAccess($acceso, ACCESS_ACTIVAR)){
			$botones='<td>
                    <button class="btn btn-sm btn-info" href="#" title="Ver"><i class="fa fa-eye"></i></button>
                    <button class="btn btn-sm btn-warning btnEditarOS" data-id="'.$value['id'].'" editarDev="'.$value['id'].'" title="Editar"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-sm btn-primary  btnPrintOS" idos="'.$value['id'].'" href="#" title="Generar PDF "><i class="fa fa-file-pdf-o"></i></button>
                    <button class="btn btn-sm btn-danger  btnBorrarDev" href="#" idDev="'.$value['id'].'"  title="Eliminar"><i class="fa fa-eraser"></i></button>
                 </td>
				';
			}else{
				$botones='<td></td>';
			}
			
		  	$data[]=array(
				  $value["id"],
			      $value["id_empresa"],
			      $nombre,
			      $value["ordenservicio"],
			      $value["telefono"],
			      $value["almacen"],
			      $fecha,
				  $fagr,
				  $capturo,
			      $botonestado,
				  $value["factura"],
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

    case 'buscarProdx':
        $valor = trim(strip_tags($_POST['searchTerm']));
        $tabla = "hist_salidas";
        $orden = "id_producto";
		$idalmacen=$_POST['id_almacen'];
		$idtecnico=$_POST['id_tecnico'];
		
        $respuesta = ControladorOServicios::ctrReporteExistenciaPorTecnico($tabla, $valor, $idalmacen, $idtecnico, $orden);

        echo json_encode($respuesta);
    
    break;

    case 'guardarOS':

        if(isset($_POST["idproducto"])){

            //EXTRAE EL NOMBRE DEL ALMACEN
            //$tablatmp =trim(substr($_POST['nuevoAlmacenOS'],strpos($_POST['nuevoAlmacenOS'].'-','-')+1)); 
            //$tabla_almacen=strtolower($tablatmp);
            //EXTRAE EL NUMERO DE ALMACEN
            $id_almacen=strstr($_POST['nuevoAlmacenOS'],'-',true);   

            $tabla="tabla_os";

			$productos=$_POST["idproducto"];
			$cantidades=$_POST["cantidad"];

			$datos_inst_array = array(); //creamos un array para guardar los datos de la Inst. en el campo JSON
			$datos_inst_array[]=array(
				"numpisaplex"	=> $_POST["numpisaplex"],
				"numtipo"		=> strtoupper($_POST["numtipo"]),
				"direccionos"	=> strtoupper($_POST["direccionos"]),
				"coloniaos"		=> strtoupper($_POST["coloniaos"]),
				"distritoos"	=> strtoupper($_POST["distritoos"]),
				"terminalos"	=> strtoupper($_POST["terminalos"]),
				"puertoos"		=> $_POST["puertoos"],
				"nombrefirma"	=> strtoupper($_POST["nombrefirma"]),
				"modemretirado"	=> $_POST["modemretirado"],
				"modemnumserie"	=> strtoupper($_POST["modemnumserie"]),
				"numeroserie"	=> $_POST["numeroSerie"],
				"alfanumerico"	=> strtoupper($_POST["alfanumerico"])
			);
			//Creamos el JSON
			$datos_instalacion=json_encode($datos_inst_array);

			$datos_material_array = array(); //creamos un array para guardar los prod y cant de material en el campo JSON
			foreach ($_POST["idproducto"] as $clave=>$valor){
				$datos_material_array[]=array("id_producto" => $_POST["idproducto"][$clave], "cantidad"=> $_POST["cantidad"][$clave]);
			};	
			//Creamos el JSON
			$datos_material=json_encode($datos_material_array);

			//$archivo=$_FILES['imagen']['tmp_name'];//Contiene el archivo
			$firma=isset($_POST["firma"])?$_POST["firma"]:"Sin Firma";

			$datos = array(
				"ordenservicio"		=>$_POST["numeroos"],
				"telefono" 			=>$_POST["numtelefono"],
				"id_almacen"		=>$id_almacen,
				"id_tecnico"		=>$_POST["nvotecnico"],
				"fecha_instalacion"	=>$_POST["fechainst"],
				"nombrecontrato"	=>$_POST["nombrecontrato"],
				"datos_instalacion"	=>$datos_instalacion,
				"datos_material"	=>$datos_material,
				"firma"				=>$firma,
				"observaciones"		=>$_POST["observacionesos"],
				"ultusuario"		=>$_POST["idDeUsuario"]
			);

			$rspta = ControladorOServicios::ctrGuardarOS($tabla, $datos, $productos, $cantidades);
			echo json_encode($rspta);

			//echo json_encode($_POST["firma"]);

		}else{
            $respuesta = array('idproducto' => $idproducto, 'error' => 'sindatos', 'status'=>http_response_code(400));		           
            echo json_encode($respuesta);
       }
    break;    


    case 'ActualizarOS':

        if(isset($_POST["id"])){

            //$id_almacen=strstr($_POST['nuevoAlmacenOS'],'-',true);

            $tabla="tabla_os";

			$datos_inst_array = array(); //creamos un array para guardar los datos de la Inst. en el campo JSON
			$datos_inst_array[]=array(
				"numpisaplex"	=> $_POST["editnumpisaplex"],
				"numtipo"		=> strtoupper($_POST["editnumtipo"]),
				"direccionos"	=> strtoupper($_POST["editdireccionos"]),
				"coloniaos"		=> strtoupper($_POST["editcoloniaos"]),
				"distritoos"	=> strtoupper($_POST["editdistritoos"]),
				"terminalos"	=> strtoupper($_POST["editterminalos"]),
				"puertoos"		=> $_POST["editpuertoos"],
				"nombrefirma"	=> strtoupper($_POST["editnombrefirma"]),
				//"modemretirado"	=> $_POST["modemretirado"],
				//"modemnumserie"	=> strtoupper($_POST["modemnumserie"]),
				"numeroserie"	=> $_POST["editnumeroSerie"],
				"alfanumerico"	=> strtoupper($_POST["editalfanumerico"])
			);

			//Creamos el JSON
			$datos_instalacion=json_encode($datos_inst_array);
			//$firma=empty($_POST["firma"])?'':$_POST["firma"];
			$firma=$_POST["firma"];
			//echo $firma;
			//exit;
			$datos = array(
				"id"				=>$_POST["id"],
				"ordenservicio"		=>$_POST["editnumeroos"],
				"telefono" 			=>$_POST["editnumtelefono"],
				"fecha_instalacion"	=>$_POST["editfechainst"],
				"nombrecontrato"	=>$_POST["editnombrecontrato"],
				"datos_instalacion"	=>$datos_instalacion,
				//"datos_material"	=>$datos_material,
				"firma"				=>$firma,
				"observaciones"		=>$_POST["editobservaos"],
				"ultusuario"		=>$_POST["idDeUsuario"]
			);

			$rspta = ControladorOServicios::ctrActualizarOS($tabla, $datos);
			return $rspta;

		}else{
            $respuesta = array('idproducto' => $idproducto, 'error' => 'sindatos', 'status'=>http_response_code(400));		           
            echo json_encode($respuesta);
       }
    break;    

	case 'ActualizaImagen':
		$tabla="tabla_os";
		$firma1=$_POST["data1"];
		$firma2=$_POST["data2"];
		$id=$_POST["id"];
		$firma='data:'.$firma1.",".$firma2;
		$rspta = ControladorOServicios::ctrActualizarImagen($tabla, $firma, $id);
		return $rspta;

	break;


    case 'cambiarEstadoOS':
		$POST = json_decode(file_get_contents('php://input'), true);
		$valor=$POST['idos'];
		$estado=$POST['estado'];
		$factura=$POST['factura'];
		$campo='id';
		$tabla="tabla_os";
		
		// se va directo al modelo, sin pasar por el controlador
		$respuesta = ModeloOServicios::mdlCambiarEstadoOS($tabla, $campo, $valor, $estado, $factura);

		echo json_encode($respuesta);

    break;

    case 'getDataOS':

        if(isset($_GET["idos"])){
            $campo="id";
			$tabla='tabla_os';
            $respuesta = ControladorOServicios::ctrGetDataOServicios($tabla, $campo, $_GET["idos"]);
            echo json_encode($respuesta);    

        }elseif(isset($_GET["dataids"])){
            $campo="id";
			$status=1;
			$tabla='productos';
            $respuesta = ControladorOServicios::ctrGetDataOServicios($tabla, $campo, $_GET["dataids"], $status);

			//echo $respuesta;    
			echo json_encode($respuesta);    

			// $error = array("error" =>$_GET['dataids']);
			// echo json_encode($error);    
            
        }else{
            
            $array = array("error" =>$_GET['idos']);
            echo json_encode($array);
        }
		
        
     break;

	 case 'getDataNumOS':
		$numos=trim($_GET["numeroos"]);
        if(!isset($numos)){
			json_output(json_build(400, '', 'No trae numero de OS'));
        }

		$campo="ordenservicio";
		$tabla='tabla_os';

		$respuesta = ControladorOServicios::ctrGetDataNumOS($tabla, $campo, $numos);

		echo json_encode($respuesta);
     
     break;

		case 'traeridos':
	
			$tabla="observa_os";
				  
			$item = "id_os";
			if(isset($_GET["id"])){
				$valor=$_GET["id"];
			}
	
			  $traeridos = ControladorOServicios::ctrTraerIdOs($tabla, $item, (int)$valor);	
			  
			  if(count($traeridos) == 0){
	
				  echo '{"data": []}';           //arreglar, checar como va
				  return;
			  }    
			
			foreach($traeridos as $key => $value){
	
				  $data[]=array(
					  $value["id"],
					  $value["fecha"],
					  $value["observa"],
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

function validar_fecha_espanol($fecha){
	$valores = explode('-', $fecha);
	if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){
		return true;
    }
	return false;
}




