<?php
session_start();
require_once "../controladores/tipomov.controlador.php";
require_once "../modelos/tipomov.modelo.php";
require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";

require_once '../funciones/funciones.php';

switch ($_GET["op"]){

        
    case 'guardar':
    
		if(isset($_POST["nvoNombreMov"])){
            
			if(preg_match('/^[#\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nvoNombreMov"])
            ){
				$tabla = "tipomovimiento";

				$datos = array("nombre_tipo"    => strtoupper($_POST["nvoNombreMov"]),
                               "clase"          => $_POST["nvaClaseMov"],
                               "estado"         => $_POST["nvoEstadoMov"],
						       "idusuario"     => $_POST["idDeUsuario"]
							   );
                
                $rspta = ControladorMovto::ctrCrearMovto($tabla, $datos);
                echo $rspta;
            }else{
              return false;  
            }
			return false;  
        }
	break;
        
	case 'mostrar':
        if(isset($_POST["idMovto"])){
            $item = "id";
            $valor = $_POST["idMovto"];
            $orden = "id";

            $respuesta = ControladorMovto::ctrMostrarMovto($item, $valor, $orden);

            echo json_encode($respuesta);

        };
 	break;
        
	case 'editar':

            $rspta=false;
            if(isset($_POST["editNombreMov"])){
            
                if(preg_match('/^[#\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editNombreMov"])
                ){
                    $tabla = "tipomovimiento";
    
                    $datos = array("nombre_tipo"    => strtoupper($_POST["editNombreMov"]),
                                   "estado"         => $_POST["editEstadoMov"],
                                   "id"             => $_POST["idMovto"],
                                   "idusuario"      => $_POST["idDeUsuario"]
                                   );
                    
                    $rspta = ControladorMovto::ctrEditarMovto($tabla, $datos);

                    echo $rspta ? true : "Movto no se pudo actualizar";
                }else{
                    return false;  
                }
            }else{
                
                echo $rspta ? "Movto actualizado!!" : "Movto no se pudo actualizar!!";
                
            }

	break;

	case 'des_activar':
        if(isset($_POST["idMovto"])){
            $item = "id";
            $valor = $_POST["idMovto"];
            $estado = $_POST["idEstado"];

            $respuesta = ControladorMovto::ctrBorrarMovto($item, $valor, $estado);

            echo json_encode($respuesta);

        };
 	break;
	
    case 'listarmov':

		$tabla="usuarios";
        $usuario=$_SESSION['id'];
		$module="tiposmov";
		$campo="catalogo";
		$acceso=accesomodulo($tabla, $usuario, $module, $campo);

		$item = null;
    	$valor = null;
    	$orden = "id";

  		$movtos = ControladorMovto::ctrMostrarMovto($item, $valor, $orden);	

  		if(count($movtos) == 0){
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    
        
            foreach($movtos as $key => $value){
            $fechaAlta = date('d-m-Y', strtotime($value["ultmodificacion"]));
            $clase=$value["clase"]=='E'?'ENTRADA':'SALIDA';
            if(getAccess($acceso, ACCESS_ACTIVAR)){
                $botonLock=$value["estado"]==1?"<button class='btn btn-success btn-sm btnBorrarMovto' idMovto='".$value['id']."' idEstado='".$value['estado']."' title='Desactivar Movto'><i class='fa fa-unlock'></i></button>":"<button class='btn btn-danger btn-sm btnBorrarMovto ' idMovto='".$value['id']."' idEstado='".$value['estado']."' title='Activar Movto'><i class='fa fa-lock'></i></button>";
            }else{
                $botonLock=""; 
            } 
                
            $botontipo1 = getAccess($acceso, ACCESS_EDIT)?"<button class='btn btn-warning btn-sm btnEditarMovto' idMovto='".$value['id']."' data-toggle='modal' data-target='#modalEditarMovto'><i class='fa fa-pencil'></i></button>":"";
            $botontipo2 = getAccess($acceso, ACCESS_DELETE)?" <button class='btn btn-danger btn-sm btnBorrarMovto' idMovto='".$value['id']."' idEstado='".$value['estado']."' ><i class='fa fa-times'></i></button>":""; 

            $botones=$botontipo1.$botontipo2;

		  	$data[]=array(
			      $value["id"],
			      substr($value["nombre_tipo"],0,35),
				  $clase,
                  $botonLock,
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
