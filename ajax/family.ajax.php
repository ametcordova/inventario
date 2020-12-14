<?php
session_start();
require_once "../controladores/familias.controlador.php";
require_once "../modelos/familias.modelo.php";

switch ($_GET["op"]){

	case 'ingresocaja':
        if(isset($_POST["importeIngreso"])){
            $tabla="cortes";
            $item = "id_caja";
            $valor1 = $_POST["importeIngreso"];
            $valor2 = $_POST["motivoIngreso"];
            $valor3 = $_POST["numcaja"];

            $respuesta = ControladorCajas::ctrIngresoCaja($tabla, $item, $valor1, $valor2, $valor3);
            if($respuesta="ok"){
                //$_SESSION["abierta"]="ok";
            }
            echo json_encode($respuesta);

        };
 	break;
        
     case 'egresocaja':
     if(isset($_POST["importeEgreso"])){
         $tabla="cortes";
         $item = "id_caja";
         $valor1 = $_POST["importeEgreso"];
         $valor2 = $_POST["motivoEgreso"];
         $valor3 = $_POST["numcaja"];

         $respuesta = ControladorCajas::ctrEgresoCaja($tabla, $item, $valor1, $valor2, $valor3);
         if($respuesta="ok"){
             //$_SESSION["abierta"]="ok";
         }
         echo json_encode($respuesta);

     };
  break;

  //con VUE
 case 'eliminarFamilia':
        if(isset($_POST["itemEliminar"])){
            $tabla="familias";
            $item = "id";
            $valor = $_POST["itemEliminar"];

            $respuesta = ControladorFamilias::ctrEliminarFamilia($tabla, $item, $valor);

            echo $respuesta;

        };
 	break;
        
	case 'guardar':
		if(isset($_POST["nvaCaja"])){
            
			if(preg_match('/^[#\_\/a-zA-Z0-9]+$/', $_POST["nvaCaja"]) &&
               preg_match('/^[#\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nvadescripcionCaja"])
            ){
				$tabla = "cajas";

				$datos = array("caja"       => strtoupper($_POST["nvaCaja"]),
                               "descripcion"=> strtoupper($_POST["nvadescripcionCaja"]),
                               "estado"     => $_POST["nvoestadoCaja"],
                               "id_usuario" => $_POST["nvousuarioCaja"],
						       "ultusuario" => $_POST["idDeUsuario"]
							   );
                
                $rspta = ControladorCajas::ctrCrearCaja($tabla, $datos);
                echo $rspta;
            }else{
              return false;  
            }
			return false;  
        }
	break;
        
	case 'listarFamilias':
            $item = "id";

            $respuesta = ControladorFamilias::ctrlistarFamilias($item);

            echo json_encode($respuesta);

 	break;
        
	case 'editar':
		if(isset($_POST["editarCaja"])){
            $rspta=false;
			if(preg_match('/^[#\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editardescripcionCaja"])
            ){
				$tabla = "cajas";

				$datos = array("descripcion"=> strtoupper($_POST["editardescripcionCaja"]),
                               "estado"     => $_POST["editarestadoCaja"],
							   "id_usuario" => $_POST["editarusuarioCaja"],
						       "ultusuario" => $_POST["idDeUsuario"],
						       "id"         => $_POST["idCaja"]);
                
                $rspta = ControladorCajas::ctrEditarCaja($tabla, $datos);

                echo $rspta ? true : "Caja no se pudo actualizar";

            }else{
                
                echo $rspta ? "Caja actualizado!!" : "Caja no se pudo actualizar!!";
                
            }
        }
	break;

	case 'des_activar':
        if(isset($_POST["idCaja"])){
            $item = "id";
            $valor = $_POST["idCaja"];
            $estado = $_POST["idEstado"];

            $respuesta = ControladorCajas::ctrBorrarCaja($item, $valor, $estado);

            echo json_encode($respuesta);

        };
 	break;
	
    case 'listarcaja':
		$item = null;
    	$valor = null;
    	$orden = "id";

  		$cajas = ControladorCajas::ctrMostrarCajas($item, $valor, $orden);	

  		if(count($cajas) == 0){
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    
        
            foreach($cajas as $key => $value){
            $fechaAlta = date('d-m-Y', strtotime($value["ultmodificacion"]));
                
            $botonLock=$value["estado"]==1?"<button class='btn btn-success btn-sm btnBorrarCaja px-1' idCaja='".$value['idcaja']."' idEstado='".$value['estado']."' title='Desactivar caja'><i class='fa fa-unlock'></i></button>":"<button class='btn btn-danger btn-sm btnBorrarCaja px-1' idCaja='".$value['idcaja']."' idEstado='".$value['estado']."' title='Activar caja'><i class='fa fa-lock'></i></button>";
                
            $botonesCaja = "<button class='btn btn-warning btn-sm btnEditarCaja' idCaja='".$value['idcaja']."' data-toggle='modal' data-target='#modalEditarCaja'><i class='fa fa-pencil'></i></button>"." <button class='btn btn-danger btn-sm btnBorrarCaja' idCaja='".$value['idcaja']."' idEstado='".$value['estado']."' ><i class='fa fa-times'></i></button>"; 


		  	$data[]=array(
                "0"=>($key+1),
                "1"=>$value["caja"],
                "2"=>substr($value["nombre"],0,15),
                "3"=>$value["descripcion"],
                "4"=>$fechaAlta,
                "5"=>$botonLock,
                "6"=>$botonesCaja,
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
