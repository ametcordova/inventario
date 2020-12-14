<?php
session_start();
date_default_timezone_set("America/Mexico_City");
$fechaHoy=date("Y-m-d");

require_once "../controladores/ajusteinventario.controlador.php";
require_once "../modelos/ajusteinventario.modelo.php";
require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";

require_once '../funciones/funciones.php';

switch ($_GET["op"]){

	case 'guardarAjusteInv':
	
		if(isset($_POST["nvoAlmAjuste"]) && !empty($_POST["nvoAlmAjuste"])){

                //EXTRAE EL TIPO DE MOV campo CLASE (e/s)
				$tipomov =trim(substr($_POST['nvoTipoAjuste'],strpos($_POST['nvoTipoAjuste'].'-','-')+1)); 
                //EXTRAE EL NUMERO DE TIPO DE MOV
                $id_tipomov=strstr($_POST['nvoTipoAjuste'],'-',true);   			
                //$tablaHist = $tipomov=="E"?"hist_entrada":"hist_salidas";

				$productos=$_POST["idProducto"];
				$cantidades=$_POST["cantidad"];
				$codigosinternos=$_POST["codigointerno"];
				$tablaAjuste = "ajusteinventario";
			
                //EXTRAE EL NOMBRE DEL ALMACEN
				$tabla =trim(strtolower(substr($_POST['nvoAlmAjuste'],strpos($_POST['nvoAlmAjuste'].'-','-')+1))); 
                
                //EXTRAE EL NUMERO DE ALMACEN
                $id_almacen=strstr($_POST['nvoAlmAjuste'],'-',true);   			
			
				//CAMBIAR FORMATO DE FECHA
				//$fechadev=date('Y-m-d',strtotime($_POST['fechaDevolucion']));
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
				$datos_json=json_encode($datosjson);			
					
				$datos = array("fecha_ajuste"   => $_POST["nvaFechaAjuste"],
					           "tipomov"        => $id_tipomov,
					           "id_almacen"     => $id_almacen,
					           "motivo_ajuste"  => strtoupper($_POST["nvoMotivoAjuste"]),
					           "datos_ajuste"   => $datos_json,
					           "id_usuario" 	=> $_POST["idDeUsuario"]
					   );			
					   
				$rspta = ControladorAjusteInventario::ctrGuardarAjusteInv($tablaAjuste, $tabla, $datos, $productos, $cantidades, $codigosinternos, $tipomov, $id_tipomov);
				
				echo json_encode($rspta);
				
		}else{
			echo json_encode("Error");
        };
			

  	case 'obtenerLastId':
            $tabla = "ajusteinventario";
            $item = "id";
            $valor = null;

            $respuesta = ControladorAjusteInventario::ctrObtenerLastId($tabla, $item, $valor);

			echo json_encode($respuesta);
            
 	break;

    case 'queryExist':
        require_once "../controladores/almacen.controlador.php";
        require_once "../modelos/almacen.modelo.php";
            
        $tabla = trim(strtolower($_POST['almacen']));;
        $campo = "id_producto";
        $valor = $_POST['idProducto'] ;

        $respuesta = ControladorAlmacen::ctrMostrarAlmacen($tabla, $campo, $valor);
    
        //var_dump($respuesta);
        
        echo json_encode($respuesta);
            
     break;

    case 'listar':
	
		$tabla="usuarios";
        $usuario=$_SESSION['id'];
		$module="ajusteinv";
		$campo="administracion";
		$acceso=accesomodulo($tabla, $usuario, $module, $campo);

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
		
  		$listarAjusteInv = ControladorAjusteInventario::ctrListarAjusteInv($item, $valor, $orden, $fechadev1, $fechadev2);	
		  
  		if(count($listarAjusteInv) == 0){
			//echo json_encode($listarDevTec); 
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    
        
        foreach($listarAjusteInv as $key => $value){
            $fechaAjuste = date('d-m-Y', strtotime($value["fecha_ajuste"]));
            $nomtipomov = substr($value["nombre_tipo"],0,25);    //extrae el nombre de tipo de movimiento
            $almacen = substr($value["almacen"],0,15);    //extrae el nombre del almacen
			
			$tri = '<tr class="table-success"><td>'.($value["id"]).'</td>';

            $botones =getAccess($acceso, ACCESS_PRINTER)?"<td><button class='btn btn-success btn-sm btnImprimirAjusteInv' idNumAjusteInv='".$value['id']."' title='Generar PDF '><i class='fa fa-file-pdf-o'></i></button></td></tr>":""; 
/*
			$botones='<td>
                  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acción
                  </button>
                  <div class="dropdown-menu">

                    <a class="dropdown-item text-info bg-light" href="#" title="Ver"><i class="fa fa-eye"></i> &nbspVisualizar </a>
                    <a class="dropdown-item text-primary bg-light btnImprimirAjusteInv" href="#" idNumAjusteInv="'.$value["id"].'" title="Generar PDF "><i class="fa fa-file-pdf-o"></i> &nbspReporte en PDF</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger bg-light btnBorrarAjuste" href="#" idAjuste="'.$value['id'].'"  title="Eliminar"><i class="fa fa-eraser"></i> &nbspEliminar </a>
                  </div>
                 </td>
                </tr>';
*/			
		  	$data[]=array(
			      $tri,
                  $nomtipomov,
                  $almacen,
			      substr($value["motivo_ajuste"],0,35).' ...',
			      $value["usuario"],
			      $fechaAjuste, 
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
