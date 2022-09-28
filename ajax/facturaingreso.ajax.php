<?php
//if(strlen(session_id())>1)
session_start();
date_default_timezone_set("America/Mexico_City");
$fechaHoy=date("Y-m-d");

require_once "../controladores/facturaingreso.controlador.php";
require_once "../modelos/facturaingreso.modelo.php";
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
        $todes=$_SESSION['perfil']=="Administrador"?"":$_SESSION['id'];
		$module="pentradas";
		$campo="administracion";
		$acceso=accesomodulo($tabla, $usuario, $module, $campo);

        //renombramos la variable $tabla
        $tabla="facturaingreso";
		
        $listar = ControladorFacturaIngreso::ctrListarFacturas($tabla, $fechadev1, $fechadev2, $usuario, $todes);	
          
        if(count($listar) == 0){
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    

        foreach($listar as $key => $value){
            $fechaelaboracion = date('d-m-Y', strtotime($value["fechaelaboracion"]));
            $importeFact=number_format($value['totalfactura'], 2, '.',',');

            $tri = '<tr><td>'.($value["id"]).'</td>';
            $trf='</tr';
            $status="Timbrado";

            $boton1 =getAccess($acceso, ACCESS_PRINTER)?"<td><button class='btn btn-success btn-sm px-1 py-1 btnPrintEntradaAlmacen' idPrintEntrada='".$value['id']."' title='Generar PDF '><i class='fa fa-file-pdf-o'></i></button></td> ":"";
            $boton2 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-primary btn-sm px-1 py-1 btnEditEntradaAlmacen' idEditarEntrada='".$value['id']."' title='Editar Entrada' data-toggle='modal' data-target='#modalEditarEntradasAlmacen'><i class='fa fa-edit'></i></button></td> ":"";
            $boton3 =getAccess($acceso, ACCESS_DELETE)?"<td><button class='btn btn-danger btn-sm px-1 py-1 btnDelEntradasAlmacen' idDeleteEntradaAlm='".$value['id']."' title='Eliminar Entrada '><i class='fa fa-trash'></i></button></td> ":"";
            if($value["uuid"]!=""){
                $boton4 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-dark btn-sm px-1 py-1 btnUpEntradasAlmacen' idUploadEntradaAlm='".$value['id']."' title='Archivos subidos' data-idupentrada='".$value['id']."' ><i class='fa fa-bell'></i> Timbrado</button></td> ":"";
            }else{
                $boton4 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-danger btn-sm px-1 py-1 btnUpEntradasAlmacen' idUploadEntradaAlm='".$value['id']."' title='Archivos subidos' data-idupentrada='".$value['id']."' ><i class='fa fa-bell-slash'></i> Sin Timbrar</button></td> ":"";
            };
            $botones=$boton1.$boton2.$boton3;

            $data[]=array(
                $value['id'],
                $value["folio"],
                $fechaelaboracion,
                $value["nombrereceptor"],
                $value["idtipocomprobante"],
                $importeFact,
                $boton4,
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

    case 'obtenerUltimoNumero':
        $tabla = "facturaingreso";
        $campo=null;
        $respuesta = ControladorfacturaIngreso::ctrObtenerUltimoNumero($tabla, $campo);

        echo json_encode($respuesta);
    
    break;

    case 'datosreceptor':
        $tabla = "clientes";
        $campo='id';
        $valor=$_GET['idreceptor'];
        
        $respuesta = ControladorfacturaIngreso::ctrDatosReceptor($tabla, $campo, $valor);

        echo json_encode($respuesta);
    
    break;

    case 'getClavesFact':
        $tabla = "clavesfacturacion";
        $valor = trim(strip_tags($_POST['searchTerm']));
        $campo = "idprodservicio";
        $data = array();

        $respuesta = ControladorFacturaIngreso::ctrgetClavesFact($tabla, $campo, $valor);

        echo json_encode($respuesta);
    
    break;
    
    case 'getDataClavesFact':
        $tabla = "clavesfacturacion";
        $campo = "idprodservicio";
        $valor = $_GET['idprod'];

        $respuesta = ControladorFacturaIngreso::ctrgetClavesFact($tabla, $campo, $valor);

        echo json_encode($respuesta);
    
    break;

	case 'guardar':
        try{
            if(isset($_POST["nvofolio"])){  
                
                if(preg_match('/^[#\_\/a-zA-Z0-9]+$/', $_POST["nvofolio"]) &&
                    preg_match('/^[#\.\,\-\/a-zA-Z0-9ñÑ ]+$/', $_POST["nvoClienteReceptor"]) &&
                    !empty($_POST["nvoregimenfiscalreceptor"])
                  ){

                    $tabla = "facturaingreso";
                    $tasaimpuesto=16.00;
                    $totalPU=array_sum($_POST["preciounitario"]);
                    $totalimpuesto=($totalPU*$tasaimpuesto)/100;
                    $sumatotal=$totalPU+$totalimpuesto;

                    /* `inventario`.`facturaingreso` */
                    $facturaingreso = array(
                        'serie' => 'A',
                        'folio' => $_POST["nvofolio"],
                        'uuid' => '',
                        'fechaelaboracion' => date("Y-m-d H:i:s"),
                        'fechatimbrado' => '',
                        'fechacancelado' => '',
                        'rfcemisor' => 'DIGB980626MX3',
                        'idregimenfiscalemisor' => '621',
                        'idtipocomprobante' => $_POST["idtipocomprobante"],
                        'idmoneda' => '100',
                        'idlugarexpedicion' => '29047',
                        'idexportacion' => '01',
                        'idreceptor' => $_POST["nvoClienteReceptor"],
                        'idusocfdi' => $_POST["nvoUsocfdi"],
                        'idformapago' => $_POST["nvoFormaPago"],
                        'idmetodopago' => $_POST["nvoMetodoPago"],
                        'numctapago' => '',
                        'cfdirelacionados' => NULL,
                        //'conceptos' => '[{"id_producto":"1","cantidad":"1", "pu":"1105","impuesto":"176.80","retencion":"0.00","preciototal":"1281.80"},{"id_producto":"2","cantidad":"4","pu":"1105","impuesto":"707.20","retencion":"0.00","preciototal":"5127.20"}]',
                        'conceptos' => '[{"id_producto":"1","cantidad":"1", "pu":"1105","impuesto":"176.80","retencion":"0.00","preciototal":"1281.80"},{"id_producto":"2","cantidad":"4","pu":"1105","impuesto":"707.20","retencion":"0.00","preciototal":"5127.20"}]',
                        'observaciones' => $_POST["nvaObserva"],        //validar
                        'subtotal' => $totalPU,
                        'tasaimpuesto' =>$tasaimpuesto,
                        'impuestos' => $totalimpuesto,
                        'retenciones' => '0.00',
                        'totalfactura' => $sumatotal,
                        'rutaguardararchivos' => NULL,
                        'ultusuario' => '5');
                        
                        $rspta = ControladorFacturaIngreso::ctrCrearFacturaIngreso($tabla, $facturaingreso);    //$_POST[""]
                        if($rspta=='ok')
                            json_output(json_build(201, 'ok', 'Movimiento agregado con éxito'));
                        //echo $rspta;
                }else{
                    throw new Exception("Expresion no aceptada");
                    return false;  
                }
                throw new Exception("Folio no existe");
                //return false;  
            }
        } catch (Exception $e) {
            json_output(json_build(403, null, $e->getMessage()));
        }    
    
        break;

} // fin del switch

