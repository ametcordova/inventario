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
            $boton3 =getAccess($acceso, ACCESS_DELETE)?"<td><button class='btn btn-sm btn-danger btnDelEntradasAlmacen' idDeleteSalAlm='".$value['id']."' title='Eliminar Salida '><i class='fa fa-trash'></i></button></td> ":"";

            $botones=$boton1.$boton2.$boton3;

            $data[]=array(
                $tri,
                $fechaentrada,
                $value["nombrealmacen"],
                $value["nombreproveedor"],
                $value["nombretipomov"],
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

        //print_r($respuesta);

        // foreach($respuesta as $key => $value){
        //     $data[] = array("id"=>$row['id'], "text"=>$row['codigointerno'], "text"=>$row['descripcion']);
        // }

        // echo json_encode($data);                

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




} // fin del switch