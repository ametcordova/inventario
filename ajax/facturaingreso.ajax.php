<?php
session_start();    
// if(!strlen(session_id())>1){
// }
date_default_timezone_set("America/Mexico_City");
$fechaHoy=date("Y-m-d");

require_once "../controladores/facturaingreso.controlador.php";
require_once "../modelos/facturaingreso.modelo.php";
require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";
require_once "../funciones/timbrarfactura.php";
require_once '../funciones/funciones.php';
require_once 'readerxml.php';

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
            $serie=is_null($value["serie"])?'':trim($value["serie"]);

            $tri = '<tr><td>'.($value["id"]).'</td>';
            $trf='</tr';
            $status="Timbrado";

            $boton1 =getAccess($acceso, ACCESS_PRINTER)?"<td><button class='btn btn-success btn-sm px-1 py-1 btnPrintPdf' data-id='".$value['id']."' data-folio='".$value['folio']."' title='Generar PDF '><i class='fa fa-file-pdf-o'></i></button></td> ":"";
            $boton2 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-primary btn-sm px-1 py-1 btnEditEntradaAlmacen' idEditarEntrada='".$value['id']."' title='Editar Factura' data-toggle='modal' data-target='#modalEditarEntradasAlmacen'><i class='fa fa-edit'></i></button></td> ":"";
            $boton3 =getAccess($acceso, ACCESS_DELETE)?"<td><button class='btn btn-danger btn-sm px-1 py-1 btnDelEntradasAlmacen' idDeleteEntradaAlm='".$value['id']."' title='Eliminar Factura '><i class='fa fa-trash'></i></button></td> ":"";
            if($value["uuid"]!=""){
                $boton4 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-sm btn-dark px-1 py-1' title='Factura timbrada'><i class='fa fa-bell fa-fw'></i> </button></td> ":"";
            }else{
                $boton4 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-sm btn-danger px-1 py-1' onclick='getIdFactura(this)' title='Factura sin timbrar' data-idfactura='".$value['id']."' data-folio='".$value['folio']."' data-fechaelabora='".$value['fechaelaboracion']."' data-idempresa='".$value['id_empresa']."' data-serie='".$value['serie']."' data-rfcemisor='".$value['rfcemisor']."' ><i class='fa fa-bell-slash fa-fw'></i> </button></td> ":"";
            };
            $botones=$boton1.$boton2.$boton3;

            $data[]=array(
                $value['id'],
                $serie,
                $value["folio"],
                $value["fechaelaboracion"],
                $value["fechatimbrado"],
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

    case 'getDatosEmpresa':
        $item = "id";
        $valor = $_GET['idempresa'];

        $respuesta = ControladorFacturaIngreso::ctrGetDatosEmpresa($item, $valor);

        echo json_encode($respuesta);
    
    break;

    case 'guardar':
        try{
            if(isset($_POST["nvofolio"])){  
                
                if(preg_match('/^[#\_\/a-zA-Z0-9]+$/', $_POST["nvofolio"]) &&
                    preg_match('/^[#\.\,\-\/a-zA-Z0-9ñÑ ]+$/', $_POST["nvoClienteReceptor"]) &&
                    !empty($_POST["nvoregimenfiscalreceptor"])
                  ){
                    //Tabla a guardar datos de la factura
                    $tabla = "facturaingreso";

                    //calculo para sacar el total a facturar
                    $tasaimpuesto=16.00;
                    $totalPU=(float) array_sum($_POST["importe"]);
                    $totalimpuesto=($totalPU*$tasaimpuesto)/100;
                    $sumatotal=$totalPU+$totalimpuesto;

                    //creamos un array para los conceptos
                    $datosjson = array(); 

					foreach ($_POST["idproducto"] as $clave=>$valor){
						//echo "El valor de $clave es: $valor";
						//$array = explode(", ", $string, 3);
                        list($claveunidad, $nombreunidad) = explode('-', $_POST["claveunidad"][$clave]);
						$datosjson[]=array(
                                    "ClaveProdServ" => trim($_POST["idproducto"][$clave]),
									"Cantidad"      => $_POST["cantidad"][$clave],
									"ClaveUnidad"   => trim($claveunidad),
									"Unidad"        => trim($nombreunidad),
									"Descripcion"   => trim($_POST["descripcion"][$clave]),
									"ValorUnitario" => $_POST["preciounitario"][$clave],
									"Importe"       => $_POST["importe"][$clave],
									"ObjetoImp"     => $_POST["objetodeimpuesto"][$clave]
									);
					};	

                    //Creamos el JSON del Array
                    $datos_json=json_encode($datosjson);

                    if ($datos_json === FALSE) {
                        throw new Exception("JSON mal formado");
                    }             
                    
                    /* `inventario`.`facturaingreso` */
                    $facturaingreso = array(
                        'id_empresa' => $_POST["idEmpresa"],
                        'serie' => $_POST['serie'],
                        'folio' => $_POST["nvofolio"],
                        'uuid' => '',
                        'fechaelaboracion' => date("Y-m-d H:i:s"),
                        'fechatimbrado' => '',
                        'fechacancelado' => '',
                        'rfcemisor' => $_POST['rfcemisor'],
                        'idregimenfiscalemisor' => $_POST['idregimenfiscalemisor'],
                        'idtipocomprobante' => $_POST["idtipocomprobante"],
                        'idmoneda' => '100',
                        'idlugarexpedicion' => $_POST['codpostal'],
                        'idexportacion' => $_POST['idexportacion'],
                        'idreceptor' => $_POST["nvoClienteReceptor"],
                        'idusocfdi' => $_POST["nvoUsocfdi"],
                        'idformapago' => $_POST["nvoFormaPago"],
                        'idmetodopago' => $_POST["nvoMetodoPago"],
                        'numctapago' => '',
                        'cfdirelacionados' => NULL,
                        'conceptos' => $datos_json,
                        'observaciones' => $_POST["nvaObserva"],        //validar
                        'subtotal' => $totalPU,
                        'tasaimpuesto' =>$tasaimpuesto,
                        'impuestos' => $totalimpuesto,
                        'retenciones' => '0.00',
                        'totalfactura' => $sumatotal,
                        'rutaguardararchivos' => NULL,
                        'ultusuario' => $_POST['idDeUsuario']
                    );
                        
                        $rspta = ControladorFacturaIngreso::ctrCrearFacturaIngreso($tabla, $facturaingreso);    //$_POST[""]
                        if($rspta=='ok')
                            json_output(json_build(201, 'ok', 'Movimiento agregado con éxito'));
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
/************************************************************************************************** */
        case 'TimbrarFact':
            $fechaactual=new DateTime("now");
            $fecha = new DateTime($_GET['datafecha']);
            $diff = $fecha->diff($fechaactual);
            //var_dump($diff);
            //echo ( ($diff->days * 24 ) * 60 ) + ( $diff->i * 60 ) + $diff->s . ' seconds';
            //passed means if its negative and to go means if its positive
            // echo ($diff->invert == 1 ) ? ' passed ' : ' to go ';
            // echo $diff->d.' dias ';
            // echo $diff->h.' horas ';
            // echo $diff->i.' min ';
            // echo $diff->s.' seg ';
            
            if($diff->d>0){
                 json_output(json_build(403, null, 'Mas de 1 dia.'));
                 exit;
            }

            // if($diff->h<23 && $diff->i<50 ){
            //     json_output(json_build(403, $diff->h, 'Tiene menos de 23 horas y 50 min.'));
            // }
            
            $tabla          = "facturaingreso";
            $campo          = "id";
            $valor          = $_GET['dataid'];
            $folio          = $_GET['datafolio'];
            $dataidempresa  = $_GET['dataidempresa'];
            $dataserie      = $_GET['dataserie'];
            $datarfcemisor = $_GET['datarfcemisor'];
            
            $respuesta = ClaseFacturar::GenerarJsonFactura($tabla, $campo, $valor);

            if(intval($respuesta)===0){
                json_output(json_build(401, intval($respuesta), 'No se creo el archivo JSON'));
            }
            
            $tablatimbrada='datosfacturatimbre';
            $resp = ClaseFacturar::EnviarJsonFacturaWS($tabla, $tablatimbrada, $campo, $valor, $folio, $dataidempresa, $dataserie, $datarfcemisor);
            
            if($resp){
                json_output(json_build(201, $resp, 'Respuesta Exitosa del WS'));    
            }
                json_output(json_build(401, $resp, 'Respuesta ERRONEA del WS'));

        break;


		default:
            json_output(json_build(403, null, 'No existe opciòn'));
			return false;
		break;



} // fin del switch

