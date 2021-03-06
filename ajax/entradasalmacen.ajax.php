<?php
//if(strlen(session_id())>1)
session_start();
date_default_timezone_set("America/Mexico_City");
$fechaHoy=date("Y-m-d");

require_once "../controladores/entradasalmacen.controlador.php";
require_once "../modelos/entradasalmacen.modelo.php";
require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";

require_once '../funciones/funciones.php';

switch ($_GET["op"]){

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
		$module="pentradas";
		$campo="administracion";
		$acceso=accesomodulo($tabla, $usuario, $module, $campo);

        $tabla="tbl_entradas";
		
        $listar = ControladorEntradasAlmacen::ctrListarEntradas($tabla, $fechadev1, $fechadev2);	
          
        if(count($listar) == 0){
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    

        foreach($listar as $key => $value){
            $fechaentrada = date('d-m-Y', strtotime($value["fechaentrada"]));

            $tri = '<tr><td>'.($value["id"]).'</td>';
            $trf='</tr';

            $boton1 =getAccess($acceso, ACCESS_PRINTER)?"<td><button class='btn btn-sm btn-success btnPrintEntradaAlmacen' idPrintEntrada='".$value['id']."' title='Generar PDF '><i class='fa fa-file-pdf-o'></i></button></td> ":""; 
            $boton2 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-sm btn-primary btnEditEntradaAlmacen' idEditarEntrada='".$value['id']."' title='Editar Entrada' data-toggle='modal' data-target='#modalEditarEntradasAlmacen'><i class='fa fa-edit'></i></button></td> ":""; 
            $boton3 =getAccess($acceso, ACCESS_DELETE)?"<td><button class='btn btn-sm btn-danger btnDelEntradasAlmacen' idDeleteEntradaAlm='".$value['id']."' title='Eliminar Entrada '><i class='fa fa-trash'></i></button></td> ":"";

            $botones=$boton1.$boton2.$boton3;

            $data[]=array(
                $tri,
                $fechaentrada,
                $value["nombrealmacen"],
                $value["nombreproveedor"],
                $value["nombretipomov"],
                $value["usuario"],
                $botones.$trf,
            );
        }
  
            $results = array(
                  "sEcho"=>1, //Información para el datatables
                  "iTotalRecords"=>count($data), //enviamos el total registros al datatable
                  "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
                  "aaData"=>$data);
          echo json_encode($results);        

     break;

    case 'obtenerUltimoNumero':
        $tabla = "tbl_entradas";
        $campo=null;
        $respuesta = ControladorEntradasAlmacen::ctrObtenerUltimoNumero($tabla, $campo);

        echo json_encode($respuesta);
    
    break;

    case 'ajaxProductos':
        $valor = trim(strip_tags($_POST['searchTerm']));
        $tabla = "productos";
        $campo = "descripcion";
        $data = array();

        $respuesta = ControladorEntradasAlmacen::ctrajaxProductos($tabla, $campo, $valor);

        echo json_encode($respuesta);
    
    break;

    case 'consultaExistenciaProd':

        $tabla = trim(strtolower($_GET['almacen']));;
        $campo = "id_producto";
        $valor = $_GET['idprod'] ;

        // echo "<pre>"; print_r($_GET['almacen']); echo "</pre>";
        // return;

        $respuesta = ControladorEntradasAlmacen::ctrConsultaExistenciaProd($tabla, $campo, $valor);
    
        echo json_encode($respuesta);
            
    break;

    case 'guardarEntradasAlmacen':
        if(isset($_POST["idproducto"])){

            //EXTRAE EL NOMBRE DEL ALMACEN
        	$tablatmp =trim(substr($_POST['idAlmacenEntrada'],strpos($_POST['idAlmacenEntrada'].'-','-')+1)); 
            $tabla_almacen=strtolower($tablatmp);
            //EXTRAE EL NUMERO DE ALMACEN
            $id_almacen=strstr($_POST['idAlmacenEntrada'],'-',true);
            $tabla="tbl_entradas";

            $datos = array(
                "fechaentrada"  =>$_POST["nvaFechaEntradaAlmacen"],
                "id_proveedor"  =>$_POST["nvoProveedorEntrada"],
                "id_almacen"    =>$id_almacen,
                "id_tipomov"    =>$_POST["nvoTipoEntradaAlmacen"],
                "observacion"   =>strtoupper($_POST["nvaObservacion"]),
                "ultusuario"    =>$_POST["idDeUsuario"],
                "productos"     =>$_POST["idproducto"],
                "cantidades"    =>$_POST["cantidad"]
            );
                unset ($tablatmp);
                
                $respuesta = ControladorEntradasAlmacen::ctrAltaEntradasAlmacen($tabla_almacen, $tabla, $datos);

                echo json_encode($respuesta);
       }else{

            $respuesta = array('idproducto' => $idalmacen, 'error' => 'sindatos');	           
            echo json_encode($respuesta);
       }

    break;

    case 'getDataInStore':

        if(isset($_GET["idEditarEntrada"])){
            $campo="id_entrada";

            //$respuesta = array("identrada" =>$_GET["idEditarEntrada"]);

            $respuesta = ControladorEntradasAlmacen::ctrMostrarEntradasAlmacen($campo, $_GET["idEditarEntrada"]);
            
            echo json_encode($respuesta);    

        }else{
            
            $array = array("error" =>$POST['idsalida']);
            echo json_encode($array);
        }
        
     break;

    case 'guardarEditaEntradasAlmacen':

        if(isset($_POST["idproducto"])){

            //EXTRAE EL NOMBRE DEL ALMACEN
        	$tablatmp =trim(substr($_POST['idEditarAlmacenEntrada'],strpos($_POST['idEditarAlmacenEntrada'].'-','-')+1)); 
            $tabla_almacen=strtolower($tablatmp);
            //EXTRAE EL NUMERO DE ALMACEN
            $id_almacen=strstr($_POST['idEditarAlmacenEntrada'],'-',true);
            //NUMERO DE ENTRADA
            $id_entrada=$_POST["numEditarEntradaAlmacen"];

            $oldarray=array_combine($_POST["oldproducto"], $_POST["oldcantidad"]);      //combina 2 array en 1. (indice)prod y (valor)cant
            $newarray=array_combine($_POST["idproducto"], $_POST["cantidad"]);

            //ARRAY PARA SACAR PROD(S) A ELIMINAR DE LA ENTRADA
            $aeliminar=array_diff_key($oldarray, $newarray);        
            if(!empty($aeliminar)) {
                foreach ($aeliminar as $key => $value) {
                    $respuesta = ControladorEntradasAlmacen::ctrEditEliminarRegEA($tabla_almacen, $id_almacen, $key, $value, $id_entrada);
                    if($respuesta=="ok"){
                        unset($oldarray[$key]);
                    }
                }
            }

            //ARRAY PARA SACAR PROD(S) A AGREGAR A LA ENTRADA
            $aadicionar=(array_diff_key($newarray, $oldarray));            
            if(!empty($aadicionar)) {
                foreach ($aadicionar as $key => $value) {
                    $datos = array(
                    "id_entrada"    =>$_POST["numEditarEntradaAlmacen"],
                    "fechaentrada"  =>$_POST["EditarFechaEntradaAlmacen"],
                    "id_proveedor"  =>$_POST["EditarProveedorEntrada"],
                    "id_tipomov"    =>$_POST["EditarTipoEntradaAlmacen"],
                    "id_almacen"    =>$id_almacen,
                    "id_usuario"    =>$_POST["idDeUsuario"],
                    "productos"     =>$key,
                    "cantidades"    =>$value,
                    "ultusuario"    =>$_POST["idDeUsuario"]
                    );
    
                     $respuesta = ControladorEntradasAlmacen::ctrEditAdicionarRegEA($tabla_almacen, $datos);
                     if($respuesta=="ok"){
                        unset($newarray[$key]);
                    }
                }
            }            

            //ITERAR CON LOS PRODUCTOS QUE QUEDAN CON INDICES IGUALES, PERO DIF. O IGUALES EN CANTIDAD
            foreach($oldarray as $key => $value){
                $nuevovalor=$newarray[$key]-$value;

                    //SI ES MAYOR A 0, QUE AUMENTE EXISTENCIA
                    if($nuevovalor>0){
                        $respuesta = ControladorEntradasAlmacen::ctrEditAumentarRegEA($tabla_almacen, $id_almacen, $key, $nuevovalor);
                    }    

                    //SI ES MENOR A 0, QUE DISMINUYA EXISTENCIA
                    if($nuevovalor<0){
                        $respuesta = ControladorEntradasAlmacen::ctrEditDisminuirRegEA($tabla_almacen, $id_almacen, $key, abs($nuevovalor));
                    }
            }

            unset($tablatmp);
            echo json_encode($respuesta);
            
        }else{

            $respuesta = array('idproducto' => $_POST["idproducto"], 'error' => 'sindatos');		           
            echo json_encode($respuesta);

        }
   
    break;

    case 'deleteIdEntradaAlmacen':

        if(isset($_GET["idaborrar"])){
            $respuesta="error";
            $nombremes_actual = strtolower(date('F'));  // NOMBRE DEL MES ACTUAL PARA ACTUALIZAR KARDEX 
            $tabla_hist="hist_entrada";
            $tabla_cancela="cancelacion_entrada";
            $campo="id_entrada";
            $id_cancela="id_cancelacion";   //campo para obtener el consecutivo
            $idusuario=$_SESSION['id'];     //id del usuario que cancela

            //TRAER LOS DATOS DE LA ENTRADA QUE SE VA A ELIMINAR
            $datos = ControladorEntradasAlmacen::ctrBorrarEntradaAlmacen($tabla_hist, $_GET["idaborrar"], $campo);

            if($datos=="error"){
                return $respuesta = array("error" =>'vacio');
            }else{
                $tabla_almacen=strtolower($datos[0]['nombrealmacen']);
                $tabla_kardex="kardex_".$tabla_almacen;

                //TRAER NUMERO DE LA ULTIMA CANCELACION
                $query = ControladorEntradasAlmacen::ctrObtenerUltimoNumero($tabla_cancela, $id_cancela);
                $idnumcancela=$query[0];
                if(is_null($idnumcancela)){
                    $idnumcancela=1;
                }else{
                    $idnumcancela+=1;
                }
            }

                //GUARDA DATOS DE CANCELACION EN TABLA CANCELACION_ENTRADA
                $respuesta = ControladorEntradasAlmacen::ctrGuardaCancelacion($tabla_cancela, $idnumcancela, $datos, $idusuario);

                 if($respuesta="ok"){

                    //ACTUALIZA EXISTENCIA EN LAS TABLAS DEL ALMACEN Y DE KARDEX 
                    $respuesta = ControladorEntradasAlmacen::ctrActualizaExistencia($tabla_almacen, $tabla_kardex, $nombremes_actual, $datos);

                    if($respuesta="ok"){

                        //ELIMINAR DATOS EN EL HIST_ENTRADAS
                        $respuesta = ControladorEntradasAlmacen::ctrEliminarDatos($tabla_hist, $_GET["idaborrar"], $campo);

                        if($respuesta="ok"){
                            //ELIMINAR DATOS EN TBL_ENTRADA
                            $tabla="tbl_entradas";
                            $campo="id";
                            $respuesta = ControladorEntradasAlmacen::ctrEliminarDatos($tabla, $_GET["idaborrar"], $campo);
                
                        }

                    }

                }

            $respuesta = array("respuesta:" =>$respuesta);
            echo json_encode($respuesta);    

        }else{
            $respuesta = array("error" =>$_GET['idaborrar']);
            echo json_encode($respuesta);
        }

    break;    

} // fin del switch