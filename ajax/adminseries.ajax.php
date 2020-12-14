<?php
session_start();
date_default_timezone_set("America/Mexico_City");
$fechaHoy=date("Y-m-d");

require_once "../controladores/adminseries.controlador.php";
require_once "../modelos/adminseries.modelo.php";

require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";
require_once '../funciones/funciones.php';

switch ($_GET["op"]){

	case 'guardarSeries':
	
		if(isset($_POST["nuevoAlmacenSerie"]) && !empty($_POST["nuevoAlmacenSerie"])){
			if (validar_fecha_espanol($_POST["fechaEntAlmacen"])){

				$productos=$_POST["idProducto"];
				$tabla = "contenedor_series";
				$id_usuario= $_POST["idDeUsuario"];
				$numerodocto=$_POST["numdocto"];
			
                //EXTRAE EL NOMBRE DEL ALMACEN
				//$tabla =trim(substr($_POST['nuevoAlmacenSerie'],strpos($_POST['nuevoAlmacenSerie'].'-','-')+1)); 
                
                //EXTRAE EL NUMERO DE ALMACEN
                $id_almacen=strstr($_POST['nuevoAlmacenSerie'],'-',true);   			
			
				//CAMBIAR FORMATO DE FECHA
				$fechaaltaseries=date('Y-m-d',strtotime($_POST['fechaEntAlmacen']));

				$contador = count($_POST["idProducto"]);    //CUANTO PRODUCTOS VIENEN PARA EL FOR

					
				$datos = array("id_producto" 	=> $_POST["idProducto"],
					   			"numeroserie" 	=> $_POST["serienumerico"],
					   			"alfanumerico" 	=> $_POST["seriealfanumerico"]
					   		 );			
					   
				$rspta = ControladorSeries::ctrGuardarNumeroSeries($tabla, $id_almacen, $numerodocto, $datos, $id_usuario, $contador);
				
				echo json_encode($rspta);
				
			}else{
				echo json_encode("Error!!");
			};
			
		}else{
			echo json_encode("Error");
        };
			
	break;  
	
	
	case 'mostrarprod':
	
		if(isset( $_GET["almacen"])){
			
		require_once "../controladores/almacen.controlador.php";
		require_once "../modelos/almacen.modelo.php";

			$campo = "id_producto";
			$valor =$_GET['idProducto'] ;
			$tabla = trim($_GET['almacen']);

			$respuesta = ControladorAlmacen::ctrMostrarAlmacen($tabla, $campo, $valor);
			
			//var_dump($respuesta);
			
			echo json_encode($respuesta);
			
		}
	break;  

	case 'listarseries':

		$tabla="usuarios";
		$module="pcapseries";
		$campo="administracion";
		$acceso=accesomodulo($tabla, $_SESSION['id'], $module,$campo);
			  
		$item = "estado";
    	$valor = null;
		$orden = "id";
		if(isset($_POST["FechDev1"])){
			$fechadev1=$_POST["FechDev1"];	
			$fechadev2=$_POST["FechDev2"];
		}else{
			$fechadev1=$fechaHoy;
			$fechadev2=$fechaHoy;
		}

  		$listarseries = ControladorSeries::ctrListarSeries($item, $valor, $orden, $fechadev1, $fechadev2);	
		  
  		if(count($listarseries) == 0){
			//echo json_encode($listarDevTec); 
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    
        
        foreach($listarseries as $key => $value){
            $fecha = date('d-m-Y H:m', strtotime($value["ultmodificacion"]));
            //$fecha = $fechaHoy;
            $nombre = substr($value["descripcion"],0,20);    //extrae el primer nombre del tecnico
			
			$tri = '<tr class="table-success"><td>'.($value["id"]).'</td>';
            
			$botonestado=$value["estado"]==0?"<button class='btn btn-warning btn-sm px-1 py-1 btnPagarFactura' idprod='".$value['id']."' data-toggle='modal' data-target='#modalPagarFactura' title='Asignado'>Asignado</button>":"<button class='btn btn-success px-1 py-1 btn-sm' iprod='".$value['id']."' title='Disponible'>Disponible</i></button>"; 
			
			if(getAccess($acceso, ACCESS_ACTIVAR)){
			$botones='<td>
                  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acción
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-success bg-light" href="#" title="Asignar"><i class="fa fa-clipboard"></i> &nbspAsignar </a>
                    <a class="dropdown-item text-info bg-light" href="#" title="Ver"><i class="fa fa-eye"></i> &nbspVisualizar </a>
                    <a class="dropdown-item text-warning bg-light btnEditarDev" editarDev="'.$value['id'].'" data-toggle="modal" href="#modalEditarDevTec" title="Editar"><i class="fa fa-pencil"></i> &nbspEditar </a>
                    <a class="dropdown-item text-primary bg-light" href="#" title="Generar PDF "><i class="fa fa-file-pdf-o"></i> &nbspReporte en PDF</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger bg-light btnBorrarDev" href="#" idDev="'.$value['id'].'"  title="Eliminar"><i class="fa fa-eraser"></i> &nbspEliminar </a>
                  </div>
                 </td>
				</tr>';
			}else{
				$botones='<td></td>';
			}
			
			
		  	$data[]=array(
			      $tri,
			      $value["id_producto"],
			      $nombre,
			      $value["numerodocto"],
			      $value["numeroserie"],
			      $value["alfanumerico"],
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

	 
}  //FIN DE SWITCH

function validar_fecha_espanol($fecha){
	$valores = explode('-', $fecha);
	if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){
		return true;
    }
	return false;
}