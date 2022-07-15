<?php
session_start();
date_default_timezone_set("America/Mexico_City");
$fechaHoy=date("Y-m-d");

require_once "../controladores/devolucion-tecnicos.controlador.php";
require_once "../modelos/devolucion-tecnicos.modelo.php";

require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";
require_once '../funciones/funciones.php';

switch ($_GET["op"]){

	case 'guardarDev':
	
		if(isset($_POST["TecnicoDev"]) && !empty($_POST["TecnicoDev"])){
			if (validar_fecha_espanol($_POST["fechaDevolucion"])){

				$productos=$_POST["idProducto"];
				$cantidades=$_POST["cantidad"];
				$tablaDev = "devolucion_tecnicos";
			
                //EXTRAE EL NOMBRE DEL ALMACEN
				$tabla =trim(substr($_POST['nuevaDevolucionAlmacen'],strpos($_POST['nuevaDevolucionAlmacen'].'-','-')+1)); 
                
                //EXTRAE EL NUMERO DE ALMACEN
                $id_almacen=strstr($_POST['nuevaDevolucionAlmacen'],'-',true);   			
			
				//CAMBIAR FORMATO DE FECHA
				$fechadev=date('Y-m-d',strtotime($_POST['fechaDevolucion']));

				//$contador = count($_POST["idProducto"]);    //CUANTO PRODUCTOS VIENEN PARA EL FOR

				$datosjson = array(); //creamos un array para el JSON
							
					foreach ($_POST["idProducto"] as $clave=>$valor){
						//echo "El valor de $clave es: $valor";
						
						$datosjson[]=array("id_producto" => $_POST["idProducto"][$clave],
									"cantidad"=> $_POST["cantidad"][$clave]
									);
					};	

					/* 
					SE PUEDE INCLUIR UNA CONDICION DENTRO DE UN ARAY
					"serie" => isset($_POST['serie']) ? $_POST['serie'][$clave] : NULL 
					SE PUEDE AÑADIR UN ELEMENTO  A UN ARRAY
					if (isset($_POST["serie"][$clave])) {
						array_push($datosjson, "serie", $_POST["serie"][$clave]);
					}			
					*/


				//Creamos el JSON
				$datos_memo=json_encode($datosjson);			
					
				$datos = array("id_tecnico" => $_POST["TecnicoDev"],
					   "fecha_devolucion"	=> $fechadev,
					   "id_almacen"			=> $id_almacen,
					   "motivodevolucion" 	=> $_POST["nvomotivodevolucion"],
					   "datos_devolucion" 	=> $datos_memo,
					   "id_usuario" 		=> $_POST["idDeUsuario"]
					   );			
					   
				$rspta = ControladorDevolucion::ctrGuardarDevolucionTecnico($tablaDev, $tabla, $datos, $productos, $cantidades);
				
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

	case 'listar':
	
		$tabla="usuarios";
		$module="pdevalm";
		$campo="administracion";
		$acceso=accesomodulo($tabla, $_SESSION['id'], $module,$campo);

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
		
		

  		$listarDevTec = ControladorDevolucion::ctrListarDevTec($item, $valor, $orden, $fechadev1, $fechadev2);	

		  
  		if(count($listarDevTec) == 0){
			//echo json_encode($listarDevTec); 
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    
        
        foreach($listarDevTec as $key => $value){
            $fechaDevolucion = date('d-m-Y', strtotime($value["fecha_devolucion"]));
            $tecnico = substr($value["nombre"],0,20);    //extrae el primer nombre del tecnico
            $almacen = substr($value["almacen"],0,15);    //extrae el primer nombre 
			
			$tri = '<tr class="table-success"><td>'.($key+1).'</td>';

			if(getAccess($acceso, ACCESS_ACTIVAR)){
				$botones='<td>
                  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acción
                  </button>
                  <div class="dropdown-menu">

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
			      $value["id"],
			      $tecnico,
			      $fechaDevolucion,
			      $almacen,
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