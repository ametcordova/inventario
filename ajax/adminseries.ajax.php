<?php
session_start();
date_default_timezone_set("America/Mexico_City");
$fechaHoy=date("Y-m-d");
//$yearcurdate=date("Y");

require_once "../controladores/adminseries.controlador.php";
require_once "../modelos/adminseries.modelo.php";

require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";
require_once '../funciones/funciones.php';

//$GET = json_decode(file_get_contents('php://input'), true);

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
	
	case 'guardarEditaSerie':
	
		if(isset($_POST["numeroid"]) && !empty($_POST["numeroid"])){

			$tabla="contenedor_series";
             //EXTRAE EL NUMERO DE ALMACEN
			$id_almacen=strstr($_POST["idalmacen"],'-',true);

			$datos = array("id"			=> $_POST["numeroid"],
						"id_almacen" 	=> $id_almacen,
						"numerodocto" 	=> trim($_POST["iddoctoserie"]),
						"numeroserie" 	=> $_POST["idnumeroserie"],
						"alfanumerico" 	=> $_POST["idalfanumerico"],
						"id_asignado" 	=> $_POST["idasignado"],
						"os" 			=> $_POST["idos"],
						"estado"		=> $_POST["idestado"],
						"ultusuario" 	=> $_POST["idDeUsuario"]
				 );			
					   
				$rspta = ControladorSeries::ctrGuardarEditaSerie($tabla, $datos);
				//$rspta=array("success"=>$id_almacen, "almacen"=>$datos);
				echo json_encode($rspta);
				
			
		}else{
			echo json_encode(array("Error"=>401));
        };
			
	break;  

	case 'validdata':

		//if(isset($_GET["idalmacen"]) && isset($_GET["numdocto"]) && isset($_GET["idproducto"])){
		if(isset($_GET["idalmacen"])){

			$campo1 = "id_almacen";
			$valor1 = $_GET["idalmacen"];
			$campo2 = "numerodocto";
			$valor2 = trim($_GET["numdocto"]);
			$campo3 = "id_producto";
			$valor3 =$_GET['idproducto'];
			$tabla="hist_entrada";

			$respuesta = ControladorSeries::ctrValidData($tabla, $campo1, $valor1, $campo2, $valor2, $campo3, $valor3);
			//$respuesta = array('idproducto' => $_GET["idproducto"], 'numdocto' => $_GET["numdocto"], 'status' => http_response_code(201));
			echo json_encode($respuesta);

		}else{

			$respuesta = array('status' => http_response_code(400));
			echo json_encode($respuesta);
		}
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
    	$valor = intval($_GET["typeview"]);	//1=disponible 0=transito 2=asignado
		$orden = "id";
		$filtroanual=null;
		$fechadev1=null;
		$fechadev2=null;
		if(!empty($_GET["filteryear"])){
			$filtroanual=$_GET["filteryear"];
		}else{
			if(empty($_GET["FechDev1"])){
				$fechadev1=$fechaHoy;
				$fechadev2=$fechaHoy;
			}else{
				$fechadev1=$_GET["FechDev1"];	
				$fechadev2=$_GET["FechDev2"];
			}
		}

  		$listarseries = ControladorSeries::ctrListarSeries($item, $valor, $orden, $fechadev1, $fechadev2, $filtroanual);
		
		//echo $listarseries;

  		if(count($listarseries) == 0){
			echo json_encode($listarseries); 
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    
        
        foreach($listarseries as $key => $value){
            //$fecha = date('d-m-Y h:i:s', strtotime($value["ultmodificacion"]));
            //$fecha = $value["fechaentrada"];
			$fecha = date("d-m-Y h:i:s");
            //$fecha = $fechaHoy;
            $nombre = substr($value["descripcion"],0,30);    //extrae el primer nombre del tecnico
			
			$tri = '<tr class="table-success"><td>'.($value["id"]).'</td>';
            
			$botonestado=$value["estado"]==2?"<button class='btn btn-dark btn-sm px-1 py-1' title='Asignado'>Asignado</button>":($value["estado"]==0?"<button class='btn btn-warning px-1 py-1 btn-sm' title='Transito'>Transito</i></button>":"<button class='btn btn-success px-1 py-1 btn-sm' title='Disponible'>Disponible</i></button>"); 
			
			if(getAccess($acceso, ACCESS_ACTIVAR)){
			$botones='<td>
                  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acción
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-success bg-light" href="#" title="Asignar"><i class="fa fa-clipboard"></i> &nbspAsignar </a>
                    <a class="dropdown-item text-info bg-light" href="#" title="Ver"><i class="fa fa-eye"></i> &nbspVisualizar </a>
                    <a class="dropdown-item text-warning bg-light" data-numeroid="'.$value['id'].'" data-idproduct="'.$value['id_producto'].'" data-idnombreont="'.$nombre.'" data-idnumserie="'.$value['numeroserie'].'" data-iddoctoserie="'.$value['numerodocto'].'" data-idalmacen="'.$value['id_almacen'].'-'.$value["nombrealmacen"].'" data-idalfanumerico="'.$value['alfanumerico'].'" data-idasignado="'.$value['id_asignado'].'" data-idos="'.$value['os'].'" data-idestado="'.$value['estado'].'" data-toggle="modal" href="#modalEditarSerie" title="Editar"><i class="fa fa-pencil"></i> &nbspEditar </a>
                    <a class="dropdown-item text-primary bg-light" href="#" title="Generar PDF "><i class="fa fa-file-pdf-o"></i> &nbspReporte en PDF</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger bg-light btnBorrarDev" href="#" idDev="'.$value['id'].'" title="Eliminar"><i class="fa fa-eraser"></i> &nbspEliminar </a>
                  </div>
                 </td>
				</tr>';
			}else{
				$botones='<td></td>';
			}
				
		  	$datos[]=array(
			      $tri,
			      $value["id_almacen"].'-'.$value["nombrealmacen"],
			      $value["id_producto"],
			      $nombre,
			      $value["numerodocto"],
			      $value["numeroserie"],
			      $value["alfanumerico"],
			      $value["id_asignado"],
			      $value["notasalida"],
			      $value["os"],
			      $fecha,
			      $botonestado,
                  $botones,
           );
        }
		    
        $results = array(
					"sEcho"=>1, //Información para el datatables
					"iTotalRecords"=>count($datos), //enviamos el total registros al datatable
					"iTotalDisplayRecords"=>count($datos), //enviamos el total registros a visualizar
					"aaData"=>$datos);
	//echo $listarseries;
    echo json_encode($results);
    break;	

	default:
		$respuesta = array('response' => http_response_code(405));
		echo json_encode($respuesta);	
	 
}  //FIN DE SWITCH

function validar_fecha_espanol($fecha){
	$valores = explode('-', $fecha);
	if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){
		return true;
    }
	return false;
}