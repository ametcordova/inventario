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
		$module="pcapseries";
		$campo="administracion";
		$acceso=accesomodulo($tabla, $_SESSION['id'], $module,$campo);
			  
		$item = "id";
    	$valor = null;
		$orden = "id";
		if(isset($_POST["FechDev1"])){
			$fechadev1=$_POST["FechDev1"];	
			$fechadev2=$_POST["FechDev2"];
		}else{
			$fechadev1=$fechaHoy;
			$fechadev2=$fechaHoy;
		}

  		$listarseries = ControladorOServicios::ctrListarOServicios($item, $valor, $orden, $fechadev1, $fechadev2);	
		  
  		if(count($listarseries) == 0){

  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    
        
        foreach($listarseries as $key => $value){

            $fecha = date('d-m-Y', strtotime($value["fecha_instalacion"]));
            $nombre = substr($value["tecnico"],0,30);    //extrae el primer nombre del tecnico
			$fact=$value["factura"];
			
			//$tri = '<tr class="table-success"><td>'.($value["id"]).'</td>';
            $botonestado=$value["estatus"]==0?"<button class='btn btn-dark btn-sm px-1 py-1 btnEstado' data-id='".$value['id']."' data-estado=".$value['estatus']." title='Fact. $fact'> <i class='fa fa-folder-o'></i> Facturado</button>":
			"<button class='btn btn-success px-1 py-1 btn-sm btnEstado' data-id='".$value['id']."' data-estado=".$value['estatus']." title='Activo'> <i class='fa fa-check-circle-o'></i> Por Facturar</button>"; 

			if(getAccess($acceso, ACCESS_ACTIVAR)){
			$botones='<td>
                  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acción
                  </button>
                  <div class="dropdown-menu btn-sm">
                    <a class="dropdown-item text-info bg-light" href="#" title="Ver"><i class="fa fa-eye"></i> &nbspVisualizar </a>
                    <a class="dropdown-item text-warning bg-light btnEditarOS" data-id="'.$value['id'].'" editarDev="'.$value['id'].'" data-toggle="modal" href="#modalAgregarOS" title="Editar"><i class="fa fa-pencil"></i> &nbspEditar </a>
                    <a class="dropdown-item text-primary bg-light btnPrintOS" idos="'.$value['id'].'" href="#" title="Generar PDF "><i class="fa fa-file-pdf-o"></i> &nbspReporte en PDF</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger bg-light btnBorrarDev" href="#" idDev="'.$value['id'].'"  title="Eliminar"><i class="fa fa-eraser"></i> &nbspEliminar </a>
                  </div>
                 </td>
				</tr>';
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
			      $botonestado,
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

			$datos = array(
				"ordenservicio"		=>$_POST["numeroos"],
				"telefono" 			=>$_POST["numtelefono"],
				"id_almacen"		=>$id_almacen,
				"id_tecnico"		=>$_POST["nvotecnico"],
				"fecha_instalacion"	=>$_POST["fechainst"],
				"nombrecontrato"	=>$_POST["nombrecontrato"],
				"datos_instalacion"	=>$datos_instalacion,
				"datos_material"	=>$datos_material,
				"firma"				=>$firma=isset($_POST["firma"])?$_POST["firma"]:"",
				"observaciones"		=>$_POST["observacionesos"],
				"ultusuario"		=>$_POST["idDeUsuario"]
			);

			$rspta = ControladorOServicios::ctrGuardarOS($tabla, $datos, $productos, $cantidades);
				
			echo json_encode($rspta);

		}else{
            $respuesta = array('idproducto' => $idproducto, 'error' => 'sindatos');		           
            echo json_encode($respuesta);
       }
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

}  //FIN DE SWITCH

function validar_fecha_espanol($fecha){
	$valores = explode('-', $fecha);
	if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){
		return true;
    }
	return false;
}




