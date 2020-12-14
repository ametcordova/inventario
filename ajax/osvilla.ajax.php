<?php
session_start();
require_once "../controladores/osvilla.controlador.php";
require_once "../modelos/osvilla.modelo.php";

switch ($_GET["op"]){

	case 'guardarOS':
		if(isset($_POST["nvaArea"])){
            
			if(preg_match('/^[#\_\/a-zA-Z0-9]+$/', $_POST["nvaOs"]) &&
               preg_match('/^[#\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nvoContratante"])
            ){
				$tabla = "osvilla";
				
				//CAMBIAR FORMATO DE FECHA: FUNCIONA DE LAS 2 FORMAS
				//$date = date_create($_POST["nvaFechaCita"]);
				//$fechaCita=	date_format($date,"Y-m-d");
				
				$fechaCita=date("Y-m-d",strtotime($_POST["nvaFechaCita"])); 
				$fechaAsigna=date("Y-m-d",strtotime($_POST["nvaFechaAsigna"])); 
				
				$datosjson = array(); //creamos un array
				
				$datosjson[]=array("tarjlin1a"=> $_POST["tarjlin1a"],
								"tarjlin2a"=> $_POST["tarjlin2a"],
								 "telefono1"=> $_POST["telefono1"],
								 "telefono2"=> $_POST["telefono2"],
								 "distrito1"=> $_POST["distrito1"],
								 "distrito2"=> $_POST["distrito2"],
								 "terminal1"=> $_POST["terminal1"],
								 "terminal2"=> $_POST["terminal2"],
								 "st1"=> $_POST["st1"],
								 "st2"=> $_POST["st2"],
								 "par1"=> $_POST["par1"],
								 "par2"=> $_POST["par2"],
								 "dispositivo1"=> $_POST["dispositivo1"],
								 "dispositivo2"=> $_POST["dispositivo2"],
								 "localiza1"=> $_POST["localiza1"],
								 "localiza2"=> $_POST["localiza2"],
								 
								 "eqser11"=> $_POST["eqser11"],
								 "eqser12"=> $_POST["eqser12"],
								 "eqser13"=> $_POST["eqser13"],
								 "eqser14"=> $_POST["eqser14"],
								 "eqser15"=> $_POST["eqser15"],
								 "eqser16"=> $_POST["eqser16"],
								 "eqser17"=> $_POST["eqser17"],
								 "descripcion11"=> $_POST["descripcion11"],
								 "descripcion12"=> $_POST["descripcion12"],
								 "descripcion13"=> $_POST["descripcion13"],
								 "descripcion14"=> $_POST["descripcion14"],
								 "descripcion15"=> $_POST["descripcion15"],
								 "descripcion16"=> $_POST["descripcion16"],
								 "descripcion17"=> $_POST["descripcion17"],
								 "cantidad11"=> $_POST["cantidad11"],
								 "cantidad12"=> $_POST["cantidad12"],
								 "cantidad13"=> $_POST["cantidad13"],
								 "cantidad14"=> $_POST["cantidad14"],
								 "cantidad15"=> $_POST["cantidad15"],
								 "cantidad16"=> $_POST["cantidad16"],
								 "cantidad17"=> $_POST["cantidad17"],
								 
								 "eqser21"=> $_POST["eqser21"],
								 "eqser22"=> $_POST["eqser22"],
								 "eqser23"=> $_POST["eqser23"],
								 "eqser24"=> $_POST["eqser24"],
								 "eqser25"=> $_POST["eqser25"],
								 "eqser26"=> $_POST["eqser26"],
								 "eqser27"=> $_POST["eqser27"],
								 "descripcion21"=> $_POST["descripcion21"],
								 "descripcion22"=> $_POST["descripcion22"],
								 "descripcion23"=> $_POST["descripcion23"],
								 "descripcion24"=> $_POST["descripcion24"],
								 "descripcion25"=> $_POST["descripcion25"],
								 "descripcion26"=> $_POST["descripcion26"],
								 "descripcion27"=> $_POST["descripcion27"],
								 "cantidad21"=> $_POST["cantidad21"],
								 "cantidad22"=> $_POST["cantidad22"],
								 "cantidad23"=> $_POST["cantidad23"],
								 "cantidad24"=> $_POST["cantidad24"],
								 "cantidad25"=> $_POST["cantidad25"],
								 "cantidad26"=> $_POST["cantidad26"],
								 "cantidad27"=> $_POST["cantidad27"],
								 
								 "grupo"=> $_POST["grupo"],
								 "dslam"=> $_POST["dslam"],
								 "clase"=> $_POST["clase"],
								 "rementrada"=> $_POST["rementrada"],
								 "dispdigital"=> $_POST["dispdigital"],
								 "remsalida"=> $_POST["remsalida"],
								 
								 "producto"=> $_POST["producto"],
								 "confvelocidad"=> $_POST["confvelocidad"],
								 "confdescripcion"=> $_POST["confdescripcion"],
								 "contvelocidad"=> $_POST["contvelocidad"],
								 "contdescripcion"=> $_POST["contdescripcion"],
								 "perfilconf"=> $_POST["perfilconf"]
				);
				
				//Creamos el JSON
				$datos_memo=json_encode($datosjson);
				
				$datos = array("area"       		=> strtoupper($_POST["nvaArea"]),
                               "osvilla"			=> strtoupper($_POST["nvaOs"]),
                               "tipoos"				=> $_POST["nvoTipoOs"],
							   "contratante"		=> strtoupper($_POST["nvoContratante"]),
							   "fecha_cita"			=> $fechaCita,
							   "domicilio"			=> strtoupper($_POST["nvoDomicilio"]),
							   "ciudad"				=> strtoupper($_POST["nvaCiudad"]),
							   "id_estado"			=> $_POST["nvoEstado"],
							   "folio_pisaplex"		=> $_POST["nvoFolio"],
							   "prioridad"			=> $_POST["nvaPrioridad"],
							   "zona"				=> $_POST["nvaZona"],
							   "telefono_asignado"	=> $_POST["nvoTelefono"],
							   "telefono_contacto"	=> $_POST["nvoTelContacto"],
							   "telefono_celular"	=> $_POST["nvoTelCelular"],
							   "tipo_cliente"		=> $_POST["nvoTipoCliente"],
							   "email"				=> $_POST["nvoEmail"],
							   "fecha_asignada"		=> $fechaAsigna,
							   "id_tecnico"			=> $_POST["nvoTecnico"],
							   "id_almacen"			=> $_POST["nvoAlmacen"],
                               "estatus"     		=> $_POST["nvoEstatus"],
						       "observaciones" 		=> $_POST["nvaObservacion"],
						       "datos_memo" 		=> $datos_memo,
						       "ultusuario" 		=> $_POST["idDeUsuario"]
							   );
							   
							   $rspta = ControladorOsvilla::ctrGuardarOsvilla($tabla, $datos);
                
                if($rspta){
				  echo "guardado";
				}else{
				  echo "no guardado";
				};
                
            }else{
              echo "error!!";  
			};
          }else{
			echo $_POST["nvaArea"];
        };
	break;

	case 'editarOS':
		if(isset($_POST["editarArea"])){
            
			if(preg_match('/^[#\_\/a-zA-Z0-9 ]+$/', $_POST["editarArea"]) &&
               preg_match('/^[#\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarContratante"])
            ){
				$tabla = "osvilla";
				
				//CAMBIAR FORMATO DE FECHA: FUNCIONA DE LAS 2 FORMAS
				//$date = date_create($_POST["nvaFechaCita"]);
				//$fechaCita=	date_format($date,"Y-m-d");
				$fechaCita=date("Y-m-d",strtotime($_POST["editarFechaCita"])); 
				$fechaAsigna=date("Y-m-d",strtotime($_POST["editarFechaAsigna"])); 
				
				$datosjson = array(); //creamos un array
				
				$datosjson[]=array("tarjlin1a"=> $_POST["tarjlin1a"],
								"tarjlin2a"=> $_POST["tarjlin2a"],
								 "telefono1"=> $_POST["telefono1"],
								 "telefono2"=> $_POST["telefono2"],
								 "distrito1"=> $_POST["distrito1"],
								 "distrito2"=> $_POST["distrito2"],
								 "terminal1"=> $_POST["terminal1"],
								 "terminal2"=> $_POST["terminal2"],
								 "st1"=> $_POST["st1"],
								 "st2"=> $_POST["st2"],
								 "par1"=> $_POST["par1"],
								 "par2"=> $_POST["par2"],
								 "dispositivo1"=> $_POST["dispositivo1"],
								 "dispositivo2"=> $_POST["dispositivo2"],
								 "localiza1"=> $_POST["localiza1"],
								 "localiza2"=> $_POST["localiza2"],
								 
								 "eqser11"=> $_POST["eqser11"],
								 "eqser12"=> $_POST["eqser12"],
								 "eqser13"=> $_POST["eqser13"],
								 "eqser14"=> $_POST["eqser14"],
								 "eqser15"=> $_POST["eqser15"],
								 "eqser16"=> $_POST["eqser16"],
								 "eqser17"=> $_POST["eqser17"],
								 "descripcion11"=> $_POST["descripcion11"],
								 "descripcion12"=> $_POST["descripcion12"],
								 "descripcion13"=> $_POST["descripcion13"],
								 "descripcion14"=> $_POST["descripcion14"],
								 "descripcion15"=> $_POST["descripcion15"],
								 "descripcion16"=> $_POST["descripcion16"],
								 "descripcion17"=> $_POST["descripcion17"],
								 "cantidad11"=> $_POST["cantidad11"],
								 "cantidad12"=> $_POST["cantidad12"],
								 "cantidad13"=> $_POST["cantidad13"],
								 "cantidad14"=> $_POST["cantidad14"],
								 "cantidad15"=> $_POST["cantidad15"],
								 "cantidad16"=> $_POST["cantidad16"],
								 "cantidad17"=> $_POST["cantidad17"],
								 
								 "eqser21"=> $_POST["eqser21"],
								 "eqser22"=> $_POST["eqser22"],
								 "eqser23"=> $_POST["eqser23"],
								 "eqser24"=> $_POST["eqser24"],
								 "eqser25"=> $_POST["eqser25"],
								 "eqser26"=> $_POST["eqser26"],
								 "eqser27"=> $_POST["eqser27"],
								 "descripcion21"=> $_POST["descripcion21"],
								 "descripcion22"=> $_POST["descripcion22"],
								 "descripcion23"=> $_POST["descripcion23"],
								 "descripcion24"=> $_POST["descripcion24"],
								 "descripcion25"=> $_POST["descripcion25"],
								 "descripcion26"=> $_POST["descripcion26"],
								 "descripcion27"=> $_POST["descripcion27"],
								 "cantidad21"=> $_POST["cantidad21"],
								 "cantidad22"=> $_POST["cantidad22"],
								 "cantidad23"=> $_POST["cantidad23"],
								 "cantidad24"=> $_POST["cantidad24"],
								 "cantidad25"=> $_POST["cantidad25"],
								 "cantidad26"=> $_POST["cantidad26"],
								 "cantidad27"=> $_POST["cantidad27"],
								 
								 "grupo"=> $_POST["grupo"],
								 "dslam"=> $_POST["dslam"],
								 "clase"=> $_POST["clase"],
								 "rementrada"=> $_POST["rementrada"],
								 "dispdigital"=> $_POST["dispdigital"],
								 "remsalida"=> $_POST["remsalida"],
								 
								 "producto"=> $_POST["producto"],
								 "confvelocidad"=> $_POST["confvelocidad"],
								 "confdescripcion"=> $_POST["confdescripcion"],
								 "contvelocidad"=> $_POST["contvelocidad"],
								 "contdescripcion"=> $_POST["contdescripcion"],
								 "perfilconf"=> $_POST["perfilconf"]
				);
				
				//Creamos el JSON
				$datos_memo=json_encode($datosjson);
				
				$datos = array("area"       		=> strtoupper($_POST["editarArea"]),
                               "osvilla"			=> strtoupper($_POST["editarOs"]),
                               "tipoos"				=> $_POST["editarTipoOs"],
							   "contratante"		=> strtoupper($_POST["editarContratante"]),
							   "fecha_cita"			=> $fechaCita,
							   "domicilio"			=> strtoupper($_POST["editarDomicilio"]),
							   "ciudad"				=> strtoupper($_POST["editarCiudad"]),
							   "id_estado"			=> $_POST["editarEstado"],
							   "folio_pisaplex"		=> $_POST["editarFolio"],
							   "prioridad"			=> $_POST["editarPrioridad"],
							   "zona"				=> $_POST["editarZona"],
							   "telefono_asignado"	=> $_POST["editarTelefono"],
							   "telefono_contacto"	=> $_POST["editarTelContacto"],
							   "telefono_celular"	=> $_POST["editarTelCelular"],
							   "tipo_cliente"		=> $_POST["editarTipoCliente"],
							   "email"				=> $_POST["editarEmail"],
							   "fecha_asignada"		=> $fechaAsigna,
							   "id_tecnico"			=> $_POST["editarTecnico"],
							   "id_almacen"			=> $_POST["editarAlmacen"],
                               "estatus"     		=> $_POST["editarEstatus"],
						       "observaciones" 		=> $_POST["editarObservacion"],
						       "datos_memo" 		=> $datos_memo,
						       "ultusuario" 		=> $_POST["idDeUsuario"]
							   );
							   
							   $rspta = ControladorOsvilla::ctrEditarOsvilla($tabla, $datos);
                
							if($rspta){
							  echo "Guardado";
							}else{
							  echo "No Guardado";
							};
                
            }else{
              echo "error!!";  
			};
          }else{
			echo $_POST["editarArea"];
        };
	break;

    case 'listar':
		$item = null;
    	$valorFechaIni = $_POST["fechaini"];
    	$valorFechaFin = $_POST["fechafin"];
    	$orden = "id";

  		$osvilla = ControladorOsvilla::ctrListarOsvilla($item, $valorFechaIni, $valorFechaFin, $orden);	

  		if(count($osvilla) == 0){
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    
        
        foreach($osvilla as $key => $value){
            $fechaCita = date('d-m-Y', strtotime($value["fecha_cita"]));
            $fecha_asignada = ($value["fecha_asignada"]<>null)?date('d-m-Y', strtotime($value["fecha_asignada"])):"";
			if($value["fecha_liquidacion"]!=null){
				$fecha_liquidacion = date('d-m-Y', strtotime($value["fecha_liquidacion"]));
			}else{
				$fecha_liquidacion = "";
			}
            
            $tecnico = strstr($value["nombre"], ' ', true);    //extrae el primer nombre del tecnico
			
			//<!-- $tri = '<tr class="table-success"><td>'.($key+1).'</td>'; -->
			$tri = '<tr class="table-success"><td>'.$value["id"].'</td>';
			
            $botonLock=$value["estatus"]==0?"<button class='btn btn-success btn-sm p-1'>Pendiente</button>":
			($value["estatus"]==1?"<button class='btn btn-warning btn-sm p-1'>Liquidado</button>":"<button class='btn btn-danger btn-sm p-1'>Objetado</button>");
			
            //$botonesCaja = "<button class='btn btn-warning btn-sm btnEditarCaja' idCaja='".$value['id']."' data-toggle='modal' data-target='#modalEditarCaja'><i class='fa fa-pencil'></i></button>"." <button class='btn btn-danger btn-sm btnBorrarCaja' idCaja='".$value['id']."'><i class='fa fa-times'></i></button>"; 

			$botones='<td>
                  <button type="button" class="btn btn-info btn-sm p-1 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acción
                  </button>
                  <div class="dropdown-menu">
					<a class="dropdown-item text-success bg-light" data-toggle="modal" href="#modalLiquidarOsvilla" idOs="'.$value['osvilla'].'" title="Generar Liquidación"><i class="fa fa-ticket"></i> &nbspGenerar Liquidación</a>
                    <a class="dropdown-item text-info bg-light" href="#" title="Ver"><i class="fa fa-eye"></i> &nbspVisualizar OS</a>
                    <a class="dropdown-item text-warning bg-light btnEditarOs" editarOs="'.$value['osvilla'].'" data-toggle="modal" href="#modalEditarOsvilla" title="Editar"><i class="fa fa-pencil"></i> &nbspEditar OS</a>
                    <a class="dropdown-item text-primary bg-light" href="#" title="Generar OS en PDF "><i class="fa fa-file-pdf-o"></i> &nbspReporte OS en PDF</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger bg-light btnBorrarOs" href="#" idOs="'.$value['osvilla'].'" idEstatus="'.$value['estatus'].'" title="Eliminar OS"><i class="fa fa-eraser"></i> &nbspEliminar OS</a>
                  </div>
                 </td>
                </tr>';
			
		  	$data[]=array(
			      $tri,
			      $value["osvilla"],
				  $tecnico,
				  $fecha_asignada,
				  $fecha_liquidacion,
				  $value["telefono_asignado"],
			      substr($value["contratante"],0,40),
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
	
    case 'MostrarOS':
     if(isset($_POST["mostrarOs"])){ 
        $item = "osvilla";
    	$valor = $_POST["mostrarOs"];
    	$tabla = "osvilla";

  		$respuesta = ControladorOsvilla::ctrMostrarOsvilla($tabla, $item, $valor);	
     }
        echo json_encode($respuesta);
    break;    

    case 'eliminarOS':
     if(isset($_POST["idOs"])){ 
        $item = "osvilla";
    	$valor = $_POST["idOs"];
    	$tabla = "osvilla";

  		$respuesta = ControladorOsvilla::ctrEliminarOsvilla($tabla, $item, $valor);	
     }
        echo json_encode($respuesta);
    break;    

        
        
}  //FIN DE SWITCH

