<?php
//if(strlen(session_id())>1)
session_start();
date_default_timezone_set("America/Mexico_City");
$fechaHoy=date("Y-m-d");

require_once "../controladores/salidasalmacen.controlador.php";
require_once "../modelos/salidasalmacen.modelo.php";
require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";

require_once '../funciones/funciones.php';

switch ($_GET["op"]){

    case 'guardarSalidasAlmacen':

        if(isset($_POST["idproducto"])){

            //EXTRAE EL NOMBRE DEL ALMACEN
            $tablatmp =trim(substr($_POST['idAlmacenSalida'],strpos($_POST['idAlmacenSalida'].'-','-')+1)); 
            $tabla_almacen=strtolower($tablatmp);
            //EXTRAE EL NUMERO DE ALMACEN
            $id_almacen=strstr($_POST['idAlmacenSalida'],'-',true);   

            $tabla="tbl_salidas";

            $datos = array(
                "fechasalida"=>$_POST["nvaFechaSalidaAlmacen"],
                "id_tecnico" =>$_POST["idTecnicoRecibe"],
                "id_tipomov" =>$_POST["idTipoSalidaAlmacen"],
                "id_almacen" =>$id_almacen,
                "id_usuario" =>$_POST["idDeUsuario"],
                "productos"  =>$_POST["idproducto"],
                "cantidades" =>$_POST["cantidad"],
                "motivo"     =>strtoupper($_POST["nvaObservacion"]),
                "ultusuario" =>$_POST["idDeUsuario"]
            );
                unset ($tablatmp);

                $respuesta = ControladorSalidasAlmacen::ctrAltaSalidasAlmacen($tabla_almacen, $tabla, $datos);

                echo json_encode($respuesta);
       }else{

            $respuesta = array('idproducto' => $idproducto, 'error' => 'sindatos');		           
            echo json_encode($respuesta);
       }
    break;    

    case 'deleteIdSalidaAlmacen':
        $POST = json_decode(file_get_contents('php://input'), true);
        if(isset($POST["idaborrar"])){
            $respuesta="error";
            $nombremes_actual = strtolower(date('F'));  // NOMBRE DEL MES ACTUAL PARA ACTUALIZAR KARDEX 
            $tabla_hist="hist_salidas";
            $tabla_cancela="cancelacion_salidas";
            $campo="id_salida";
            $id_cancela="id_cancelacion";   //campo para obtener el consecutivo
            $idusuario=$_SESSION['id'];     //id del usuario que cancela

            //TRAER LOS DATOS DE LA SALIDA QUE SE VA A ELIMINAR
            $datos = ControladorSalidasAlmacen::ctrBorrarSalidaAlmacen($tabla_hist, $POST["idaborrar"], $campo);

            if($datos=="error"){
                //$respuesta = array("error" =>'vacio');
            }else{
                $tabla_almacen=strtolower($datos[0]['nombrealmacen']);
                $tabla_kardex="kardex_".$tabla_almacen;

                //TRAER NUMERO DE LA ULTIMA CANCELACION
                $query = ControladorSalidasAlmacen::ctrObtenerUltimoId($tabla_cancela, $id_cancela);
                $idnumcancela=$query[0];
                if(is_null($idnumcancela)){
                    $idnumcancela=1;
                }else{
                    $idnumcancela+=1;
                }

                //GUARDA DATOS DE CANCELACION EN TABLA cancelacion_salidas
                $respuesta = ControladorSalidasAlmacen::ctrGuardaCancelacion($tabla_cancela, $idnumcancela, $datos, $idusuario);

                if($respuesta="ok"){

                    //ACTUALIZA EXISTENCIA EN LAS TABLAS DEL ALMACEN Y DE KARDEX 
                    $respuesta = ControladorSalidasAlmacen::ctrActualizaExistencia($tabla_almacen, $tabla_kardex, $nombremes_actual, $datos);

                    if($respuesta="ok"){

                        //ELIMINAR DATOS EN EL HIST_SALIDAS
                        $tabla="hist_salidas";
                        $respuesta = ControladorSalidasAlmacen::ctrEliminarDatos($tabla, $POST["idaborrar"], $campo);

                        if($respuesta="ok"){
                            //ELIMINAR DATOS EN TBL_SALIDAS
                            $tabla="tbl_salidas";
                            $campo="id";
                            $respuesta = ControladorSalidasAlmacen::ctrEliminarDatos($tabla, $POST["idaborrar"], $campo);
                
                        }
        
                    }

                }
                
            }

            echo json_encode($respuesta);    

        }else{
            $respuesta = array("error" =>$POST['idaborrar']);
            echo json_encode($respuesta);
        }


    break;

    case 'guardarEditaSalidasAlmacen':
        
            //EXTRAE EL NOMBRE DEL ALMACEN
            $tablatmp =trim(substr($_POST['EditidAlmacenSalida'],strpos($_POST['EditidAlmacenSalida'].'-','-')+1)); 
            $tabla_almacen=strtolower($tablatmp);
            //EXTRAE EL NUMERO DE ALMACEN
            $id_almacen=strstr($_POST['EditidAlmacenSalida'],'-',true);   
            //NUMERO DE SALIDA
            $id_salida=$_POST["EditidNumSalAlm"];
        
        if(isset($_POST["oldproducto"])){
            $oldarray=array_combine($_POST["oldproducto"], $_POST["oldcantidad"]);      //combina 2 array. (indice)prod y (valor)cant
            $newarray=array_combine($_POST["idproducto"], $_POST["cantidad"]);
            
            $aeliminar=array_diff_key($oldarray, $newarray);
                if(!empty($aeliminar)) {
                 foreach ($aeliminar as $key => $value) {
                     $respuesta = ControladorSalidasAlmacen::ctrEditEliminarRegSA($tabla_almacen, $id_almacen, $key, $value, $id_salida);
                     if($respuesta=="ok"){
                         unset($oldarray[$key]);
                     }
                 }
                }
            
            $aadicionar=(array_diff_key($newarray, $oldarray));            
            if(!empty($aadicionar)) {
    
                foreach ($aadicionar as $key => $value) {
                    $datos = array(
                        "id_salida"  =>$_POST["EditidNumSalAlm"],
                        "fechasalida"=>$_POST["EditFechaSalidaAlmacen"],
                        "id_tecnico" =>$_POST["EditidTecnicoRecibe"],
                        "id_tipomov" =>$_POST["EditidTipoSalidaAlmacen"],
                        "id_almacen" =>$id_almacen,
                        "id_usuario" =>$_POST["idDeUsuario"],
                        "productos"  =>$key,
                        "cantidades" =>$value,
                        "ultusuario" =>$_POST["idDeUsuario"]
                    );
    
                     $respuesta = ControladorSalidasAlmacen::ctrEditAdicionarRegSA($tabla_almacen, $datos);
                     if($respuesta=="ok"){
                        unset($newarray[$key]);
                    }
                }
            }

            // $iguales=array();
            // $demenos=array();
            // $demas=array();
            // foreach($oldarray as $key => $value){
            //     $nuevovalor=$newarray[$key]-$value;
            //         if($value===$newarray[$key]){
            //             array_push($iguales,$key,$nuevovalor);
            //         }else{
            //             if($nuevovalor>0){
            //                 array_push($demas,$key,$nuevovalor);
            //             }else{
            //                 array_push($demenos,$key,$nuevovalor);
            //             }
            //         };
            // }
            
            // $iguales=array();
            // $demenos=array();
            // $demas=array();

            //ITERAR CON LOS REGISTROS QUE QUEDAN
            foreach($oldarray as $key => $value){
                $nuevovalor=$newarray[$key]-$value;

                    // if($value===$newarray[$key]){
                    //      array_push($iguales,$key,$nuevovalor);   //si son prod. y cant. iguales no se hace nada
                    // }     

                    if($nuevovalor>0){
                        //array_push($demas,$key,$nuevovalor);
                        $respuesta = ControladorSalidasAlmacen::ctrEditAumentarRegSA($tabla_almacen, $id_almacen, $key, $nuevovalor);
                    }    

                    if($nuevovalor<0){
                        //array_push($demenos,$key,$nuevovalor);
                        $respuesta = ControladorSalidasAlmacen::ctrEditDisminuirRegSA($tabla_almacen, $id_almacen, $key, abs($nuevovalor));
                    }
            }
            //$respuesta = array('old:', $oldarray, 'new:', $newarray, 'eliminar',$aeliminar, 'adicionar', $aadicionar, 'iguales:',$iguales, 'demenos:', $demenos, 'demas:', $demas, 'respuesta:', $respuesta);  
            
            echo json_encode($respuesta);
       }else{

            $respuesta = array('idproducto' => $_POST["idproducto"], 'error' => 'sindatos');		           
            echo json_encode($respuesta);
       }
    break;

    case 'getDataOutStore':
        $POST = json_decode(file_get_contents('php://input'), true);
        //var_dump($POST);
        if(isset($POST["idsalida"])){
            $campo="id_salida";
            $respuesta = ControladorSalidasAlmacen::ctrMostrarSalidasAlmacen($campo, $POST["idsalida"]);
            
            echo json_encode($respuesta);    
        }else{
            $array = array("error" =>$POST['idsalida']);
            echo json_encode($array);
        }
        
     break;


    case 'consultaExistenciaProd':
        // require_once "../controladores/salidasalmacen.controlador.php";
        // require_once "../modelos/salidasalmacen.modelo.php";
            
        $tabla = trim(strtolower($_POST['almacen']));;
        $campo = "id_producto";
        $valor = $_POST['idProducto'] ;

        $respuesta = ControladorSalidasAlmacen::ctrConsultaExistenciaProd($tabla, $campo, $valor);
    
        //var_dump($respuesta);
        
        echo json_encode($respuesta);
            
     break;

     case 'listar':

		if(isset($_POST["FechDev1"])){
			$fechadev1=$_POST["FechDev1"];	
			$fechadev2=$_POST["FechDev2"];
		}else{
			$fechadev1=$fechaHoy;
			$fechadev2=$fechaHoy;
        }

		$tabla="usuarios";
        $usuario=$_SESSION['id'];
		$module="psalidas";
		$campo="administracion";
		$acceso=accesomodulo($tabla, $usuario, $module, $campo);

        $tabla="tbl_salidas";
		
        $listarSalidas = ControladorSalidasAlmacen::ctrListarSalidas($tabla, $fechadev1, $fechadev2);	
          
        if(count($listarSalidas) == 0){
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    

        foreach($listarSalidas as $key => $value){
            $fechasalida = date('d-m-Y', strtotime($value["fechasalida"]));

            $tri = '<tr><td>'.($value["id"]).'</td>';
            $trf='</tr';

            $boton1 =getAccess($acceso, ACCESS_PRINTER)?"<td><button class='btn btn-sm btn-success btnPrintSalidaAlmacen' idPrintSalida='".$value['id']."' title='Generar PDF '><i class='fa fa-file-pdf-o'></i></button></td> ":""; 
            $boton2 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-sm btn-primary btnEditSalidaAlmacen' idEditSalida='".$value['id']."' title='Editar Salida' data-toggle='modal' data-target='#modalEditarSalidasAlmacen'><i class='fa fa-edit'></i></button></td> ":""; 
            $boton3 =getAccess($acceso, ACCESS_DELETE)?"<td><button class='btn btn-sm btn-danger btnDelSalidaAlmacen' idDeleteSalAlm='".$value['id']."' title='Eliminar Salida '><i class='fa fa-trash'></i></button></td> ":"";

            $botones=$boton1.$boton2.$boton3;

            $data[]=array(
                $tri,
                $fechasalida,
                $value["nombrealmacen"],
                $value["nombretecnico"],
                substr($value["motivo"],0,35).' ...',
                $value["usuario"],
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

     case 'obtenerUltimoId':
        $tabla = "tbl_salidas";
        $campo=null;
        $respuesta = ControladorSalidasAlmacen::ctrObtenerUltimoId($tabla, $campo);

        echo json_encode($respuesta);
    
    break;

} // fin del switch