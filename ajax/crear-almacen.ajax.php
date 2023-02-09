<?php
session_start();
require_once "../controladores/crear-almacen.controlador.php";
require_once "../modelos/crear-almacen.modelo.php";
require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";
require_once '../funciones/funciones.php';


/************************************************************** */
switch ($_GET["op"]){
	/*=============================================
	EDITAR ALMACEN
	=============================================*/	

	case 'editStore':

		if(isset($_POST["idAlmacen"])){

			$item = "id";
			$valor = $_POST["idAlmacen"];
			$estado = null;
	
			$respuesta = ControladorAlmacenes::ctrMostrarAlmacenes($item, $valor, $estado);
	
			echo json_encode($respuesta);
			
		}

	break;

	case 'listarAlmacenes':

		$tabla="usuarios";
		$module="pcostuxtla";
		$campo="administracion";
		$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);


            $item = null;
            $valor = null;
            $estado=null;
			
            $almacenes = ControladorAlmacenes::ctrMostrarAlmacenes($item, $valor, $estado);

				if(count($almacenes) == 0){
					echo '{"data": []}';           //arreglar, checar como va
					return;
				}    
	  
                if(!empty($almacenes)){

                  	foreach ($almacenes as $key => $value) {
						if($value["estado"]==1){
							$botonestado="<button class='btn btn-sm px-1 py-1 btn-success'><i class='fa fa-unlock'></i></button>";
						}else{
							$botonestado="<button class='btn btn-sm px-1 py-1 btn-danger'><i class='fa fa-lock'></i></button>";
						}

						$boton0 =getAccess($acceso, ACCESS_EDIT)?"<button class='btn btn-sm px-1 py-1 btn-warning btnEditarAlmacen' data-toggle='modal' data-target='#modalEditarAlmacen' idAlmacen='".$value['id']."'><i class='fa fa-pencil'></i></button></td> ":" ";

						$boton1 =getAccess($acceso, ACCESS_DELETE)?'<button class="btn btn-sm px-1 py-1 btn-danger btnEliminarAlmacen" idAlmacen="' . $value["id"] . '"><i class="fa fa-times"></i></button>':'';

						$botones=$boton0.$boton1;
		
						$data[]=array(
							$value["id"],
							$value["nombre"],
							$value["ubicacion"],
							$value["responsable"],
							$value["telefono"],
							$value["email"],
							$botonestado,
							$botones,
						 );
                	}
				}
	  
		  $results = array(
					  "sEcho"=>1, //Información para el datatables
					  "iTotalRecords"=>count($data), //enviamos el total registros al datatable
					  "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
					  "aaData"=>$data);

	  		echo json_encode($results);    

    break;	

	default:
		json_output(json_build(403, null, 'No existe opciòn. Revise'));
		return false;
	break;
}
