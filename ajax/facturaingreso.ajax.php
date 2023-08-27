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
require_once "../funciones/generarrep20.php";
require_once "../funciones/timbrarrep20.php";
require_once '../funciones/funciones.php';
//include_once 'downloadxml.php';

switch ($_GET["op"]){

     case 'listarFacturas':

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
            $folio=$value["folio"];
            $rfcemisor=$value["rfcemisor"];
            $file=$rfcemisor.'-'.$serie.$folio.'.xml';
            $numcomppago=trim((string)$value['numcomppago'])==''?"<td><button class='btn btn-sm btn-default px-1 py-1' title='Sin Recibo de Pago'>SRP</button></td>":"<td><button class='btn btn-sm btn-warning px-1 py-1' data-numrep='".trim($value['numcomppago'])."' title='Comp. de Pago timbrada'>".trim($value['numcomppago'])."</button></td>";
            
            $cheka=true;

            if($value["uuid"]!=""){     //Factura Timbrada
                if($value["fechacancelado"]==''){
                    $boton0 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-sm btn-dark px-1 py-1' title='Factura timbrada'><i class='fa fa-bell fa-fw'></i> </button></td> ":"";
                }else{
                    $boton0 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-sm btn-dark px-1 py-1 disabled bg-danger text-white' title='Factura cancelada'><i class='fa fa-bell fa-fw'></i> </button></td> ":"";
                    $cheka=false;
                }
                
                if($value["numcomppago"]!=""){  //Si ya tiene complemento de pago
                    $cheka=false;
                }
                
                $boton3 =getAccess($acceso, ACCESS_PRINTER)?"<a href='vistas/modulos/download.php?filename=$file&ruta=1&mime=xml' title='Descargar XML' class='btn btn-sm btn-info px-1 py-1'><i class='fa fa-file-code-o'></i></a> ":"";
                $boton4 ='';
                $boton5 =getAccess($acceso, ACCESS_DELETE)?"<td><button class='btn btn-danger btn-sm px-1 py-1 btnCancelFact' data-idfact='".$value['id']."' data-rfcemisor='".$value['rfcemisor']."' data-rfcreceptor='".$value['rfcreceptor']."' data-uuid='".$value['uuid']."' data-importe='".$value['totalfactura']."' data-fechatimbrado='".$value['fechatimbrado']."' data-fechacancelado='".$value['fechacancelado']."' title='Cancelar Factura'><i class='fa fa-window-close'></i></button></td> ":"";

            }else{
                $boton0 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-sm btn-danger px-1 py-1' onclick='getIdFactura(this)' title='Factura sin timbrar' data-idfactura='".$value['id']."' data-folio='".$value['folio']."' data-fechaelabora='".$value['fechaelaboracion']."' data-idempresa='".$value['id_empresa']."' data-serie='".$value['serie']."' data-rfcemisor='".$value['rfcemisor']."' ><i class='fa fa-bell-slash fa-fw'></i> </button></td> ":"";

                $boton3 ='';

                $boton4 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-primary btn-sm px-1 py-1' data-editfact='".$value['id']."' title='Editar Factura' data-toggle='modal' data-target='#modalEditarEntradasAlmacen'><i class='fa fa-edit'></i></button></td> ":"";
                
                $boton5 =getAccess($acceso, ACCESS_DELETE)?"<td><button class='btn btn-warning btn-sm px-1 py-1 checabox btnCancelFact' data-idfact='".$value['id']."' data-folio='".$value['folio']."' data-idempresa='".$value['id_empresa']."' data-serie='".$value['serie']."' data-rfcemisor='".$value['rfcemisor']."' data-importe='".$value['totalfactura']."' title='Eliminar Factura '><i class='fa fa-trash'></i></button></td> ":"";

            };

            $chek=$cheka?"<input type='checkbox' name='ids[]' value='".$value["id"]."'>":$chek="<input type='checkbox' value='' disabled>";

            $boton1 =getAccess($acceso, ACCESS_EDIT)?$numcomppago:"";

            $boton2 =getAccess($acceso, ACCESS_PRINTER)?"<td><button class='btn btn-success btn-sm px-1 py-1 btnPrintPdf' data-id='".$value['id']."' data-folio='".$value['folio']."' title='Generar y descargar PDF '><i class='fa fa-file-pdf-o'></i></button></td> ":"";
           
            $botones=$boton2.$boton3.$boton4.$boton5;
            
            $uuid = substr((string)$value["uuid"],-12) ?? '';

            $data[]=array(
                $chek,
                $value['id'],
                $serie,
                $value["folio"],
                $value["rfcemisor"],
                $value["fechaelaboracion"],
                $value["fechatimbrado"],
                $uuid,
                $value["nombrereceptor"],
                $value["idtipocomprobante"],
                $importeFact,
                $boton0.$boton1,
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
        $campo='folio';
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

                    $mifecha= date('Y-m-d H:i:s'); 
                    $nuevaFecha = strtotime('-1 hour',strtotime($mifecha));
                    $fechaelaboracion = date ( 'Y-m-d H:i:s' , $nuevaFecha);

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
                        'fechaelaboracion' => $fechaelaboracion,
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
                        'observaciones' => "",        //validar
                        'condicionesdepago' => htmlspecialchars(trim($_POST["nvaCondicionesPago"]),ENT_QUOTES, 'UTF-8'),        //validar
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
                            json_output(json_build(201, 'ok', 'Pre-Factura guardada con éxito'));
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
//                 PARA TIMBRAR FACTURA
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
            $datarfcemisor  = $_GET['datarfcemisor'];
            
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
/************************************************************************************************** */
        case 'downloadXML':
            $folio      = $_GET['datafolio'];
            $serie      = $_GET['dataserie'];
            $rfcemisor  = $_GET['datarfcemisor'];
    
            if(empty($folio)){
                echo json_encode(401);  
            }

            $file=$rfcemisor.'-'.$serie.$folio;
            $ruta="'../../ajax/salida/'";

            echo "<a href='../vistas/modulos/download.php?filename=$file&ruta=$ruta&mime=xml' class'btn btn-sm btn-info' </a>";

            //echo json_encode();

        break;

    /************************************************************************************************** */
    //   PARA CANCELAR FACTURA TIMBRADA
     /************************************************************************************************** */
        case 'CancelarFact':
        $fechaactual=new DateTime("now");
        $fecha = new DateTime($_GET['datafechatimbrado']);
        $diff = $fecha->diff($fechaactual);
        
        // if($diff->d>0){
        //     json_output(json_build(403, null, 'Mas de 1 dia.'));
        //     exit;
        // }

        // if($diff->h<23 && $diff->i<50 ){
        //     json_output(json_build(403, $diff->h, 'Tiene menos de 23 horas y 50 min.'));
        // }
        
        $tabla          = "facturaingreso";
        $campo          = "id";
        $valor          = $_GET['dataidfact'];
        $rfcEmisor      = $_GET['datarfcemisor'];
        $rfcReceptor    = $_GET['datarfcreceptor'];
        $total          = $_GET['dataimporte'];
        $uuid           = $_GET['datauuid'];
        
        $resp = ClaseFacturar::CancelarFacturaWS($tabla, $campo, $valor, $rfcEmisor, $rfcReceptor, $uuid, $total);
        
        if($resp){
            json_output(json_build(201, $resp, 'Respuesta Exitosa del WS'));    
        }
            json_output(json_build(401, $resp, 'Respuesta ERRONEA del WS'));

    break;
/************************************************************************************************** */

    case 'downloadXMLRep':
        $folio      = $_GET['datafolio'];
        $serie      = $_GET['dataserie'];
        $rfcemisor  = $_GET['datarfcemisor'];

        if(empty($folio)){
            echo json_encode(401);  
        }

        $file=$rfcemisor.'-'.$serie.$folio;
        $ruta="'../../ajax/salida/'";

        //echo '<a href="descarga.php?file=archivoEjemplo.abc">Descargar</a>';
        echo "<a href='../vistas/modulos/download.php?filename=$file&ruta=$ruta&mime=xml'class'btn btn-sm btn-info' </a>";

    break;

    case 'getDataClavesFact':
        $tabla = "clavesfacturacion";
        $campo = "idprodservicio";
        $valor = $_GET['idprod'];

        $respuesta = ControladorFacturaIngreso::ctrgetClavesFact($tabla, $campo, $valor);

        echo json_encode($respuesta);
    
    break;        

    case 'GetDatosFact':
        $tabla = "facturaingreso";
        $campo = "id";
        $valor = $_GET['ids'];

        $respuesta = ControladorFacturaIngreso::ctrGetdatosFact($tabla, $campo, $valor);
        echo json_encode($respuesta);

        //echo json_encode($valor);
    
    break;

/************************************************************************************************** */    
    case 'GuardarREP':
        try{
            if(isset($_POST["idemisorrep"])){  
                
                    //Tabla a guardar datos de complemento de Pago 2.0
                    $tabla = "complementodepago";
                    $mifecha= date('Y-m-d H:i:s'); 
                    $nuevaFecha = strtotime('-1 hour',strtotime($mifecha));
                    $fechaelaboracion = date ( 'Y-m-d H:i:s' , $nuevaFecha);
                    //calculo para sacar el total a facturar
                    $tasaimpuesto=((float)$_POST["tasaimpcp"])+1;
                    $totalrecibo=$_POST["totalpagofact"];
                    $iva=($tasaimpuesto-1)*100;         //1.16-1=.16*100=16
                    $subtotal=round($totalrecibo,2)/$tasaimpuesto;   //29662
                    $totalimpuesto=($subtotal*round($iva,2))/100;
                    $saldoinsoluto=0;   //pendiente sumar saldos insolutos de c/factura
                    $conceptos_json=null;
                    $doctosrelacionados_json=null;
                    //creamos un array para los documentos relacionados
                    $datosjson = array(); 

					foreach ($_POST["countitems"] as $valor){
						//echo "El valor de $clave es: $valor";
						$datosjson[]=array(
                                    "idDocumento"       => trim($_POST["uuidcp$valor"]),
									"Serie"             => $_POST["serie$valor"],
									"Folio"             => $_POST["folio$valor"],
									"NumParcialidad"    => $_POST["parcialidadcp$valor"],
									"ImpSaldoAnt"       => $_POST["saldoactualcp$valor"],
									"ImpPagado"         => $_POST["importepagadocp$valor"],
									"ImpSaldoInsoluto"  => $_POST["saldoinsolutocp$valor"],
									"BaseDR"            => $_POST["basepagocp$valor"],
									"ImporteDR"         => $_POST["totalimpuestocp$valor"]
									);
					};	

                    //Creamos el JSON del Array
                    $doctosrelacionados_json=json_encode($datosjson);

                    if ($doctosrelacionados_json === FALSE) {
                        throw new Exception("JSON mal formado");
                    }             
                    
                    /* `inventario`.`complementodepago` */
                    $complementodepago= array(
                        'tipodecomprobante' => "P",
                        //'foliorep' => $_POST["foliorep"],
                        //'fechaelaboracion'  => date("Y-m-d H:i:s"),
                        'fechaelaboracion'  => $fechaelaboracion,
                        'idrfcemisor'       => $_POST['idemisorrep'],
                        'cpemisor'          => $_POST['cpemisorcp'],
                        'idrfcreceptor'     => $_POST["idreceptorrep"],
                        'fechapago'         => $_POST["fechapagocp"],
                        'idformapagorep'    => $_POST["formapagocp"],
                        'idmetodopagorep'   => $_POST["metodopagocp"],
                        'idusocfdi'         => $_POST["idusocfdi"],
                        'idobjetoimpuesto'  => $_POST["objetoimpcp"],
                        'idmoneda'          => $_POST["idtipomoneda"],
                        'numoperacion'      => $_POST["numoperacioncp"],
                        'cuentaordenante'   => $_POST["cuentaordenantercp"],
                        'cuentabeneficiario'=> $_POST["cuentabeneficiariocp"],
                        'conceptos'         => $conceptos_json,
                        'doctosrelacionados'=> $doctosrelacionados_json,
                        'idimpuesto'        => $_POST["tipoimpuestocp"],
                        'totalrecibo'       => $_POST["totalpagofact"],
                        'tasa'              => $_POST["tasaimpcp"],
                        'subtotal'          => $subtotal,
                        'totalimpuesto'     => $totalimpuesto,
                        'saldoinsoluto'     => $saldoinsoluto,
                        'ultusuario'        => $_POST['idDeUsuario']
                    );
                        
                        $rspta = ControladorFacturaIngreso::ctrGuardarRep($tabla, $complementodepago);    //$_POST[""]
                        if($rspta=='ok')
                            json_output(json_build(201, 'ok', 'Complemento de Pago guardado con éxito'));
            }else{
                throw new Exception("ID Emisor no existe");
                return false;  
            }
        } catch (Exception $e) {
            json_output(json_build(403, null, $e->getMessage()));
        }    
        break;
    /************************************************************************************************** */    

/************************************************************************************************** */
//                 PARA TIMBRAR COMPLEMENTO DE PAGO 2.0
/************************************************************************************************** */
    case 'GenerarRep20':
        try{
                if(isset($_GET['dataid'])){  
    
                    $fechaactual=new DateTime("now");
                    $fecha = new DateTime($_GET['datafecha']);
                    $diff = $fecha->diff($fechaactual);
                    
                     if($diff->d>1){
                         json_output(json_build(403, null, 'Mas de 1 dia.'));
                         exit;
                     }

                    $tabla          = "complementodepago";
                    $campo          = "id";
                    $valor          = $_GET['dataid'];
                    $datafolio      = $_GET['datafolio'];
                    $datarfcemisor  = $_GET['datarfcemisor'];
            

                    $respuesta = ClaseGenerarRep20::GenerarJsonRep20($tabla, $campo, $valor, $datafolio, $datarfcemisor );  //generarrep20.php

                    if($respuesta==='401'){
                        json_output(json_build(401, $respuesta, 'No se creo el archivo JSON'));
                    }else{
                        //json_output(json_build(201, $respuesta, 'Se creo el archivo JSON'));    
                        $respuesta = ClaseTimbrarRep20::EnviarJsonRep20WS($tabla, $campo, $valor, $datafolio, $datarfcemisor);  //timbrarrep20.php

                        json_output(json_build(201, $respuesta, 'Respuesta existosa'));    
                    }
                    
                    
                    if($respuesta){
                        json_output(json_build(201, $respuesta, 'Respuesta Exitosa del WS'));    
                    }else{
                        json_output(json_build(401, $respuesta, 'Respuesta ERRONEA del WS'));    
                    }
                }else{
                    json_output(json_build(401, $respuesta, 'Respuesta ERRONEA del WS'));
                }
        } catch (Exception $e) {
            json_output(json_build(403, null, $e->getMessage()));
        }    
    break;
/************************************************************************************************** */    
/* DATATABLE EN VENTANA MODAL DE LOS COMPLEMENTOS DE PAGO 2.0
/************************************************************************************************** */    
case 'ListCompPago20':

    if(isset($_POST["dateyear"])){
        $year=$_POST["dateyear"];	
    }else{
        break;
    }

    $tabla="usuarios";
    $usuario=$_SESSION['id'];
    $todes=$_SESSION['perfil']=="Administrador"?"":$_SESSION['id'];
    $module="pentradas";
    $campo="administracion";
    $acceso=accesomodulo($tabla, $usuario, $module, $campo);

    $tblRep20="complementodepago";
    $listar = ControladorFacturaIngreso::ctrListarRep20($tblRep20, $year, $usuario, $todes);
      
    if(count($listar) == 0){
          echo '{"data": []}';           //arreglar, checar como va
          return;
      }    

    foreach($listar as $key => $value){
        $fechaelaboracion = date('Y-m-d', strtotime($value["fechaelaboracion"]));
        $file=$value["rfcemisor"].'-'.$value["foliorep"].'.xml';

        if($value["fechatimbradorep"]!=""){     //REP está timbrado
            $boton0 =getAccess($acceso, ACCESS_ADD)?"<td><button class='btn btn-sm btn-dark px-0 py-0' title=R.E.P. Timbrada'><i class='fa fa-bell fa-fw'></i> </button></td> ":"";

            $boton1 =getAccess($acceso, ACCESS_PRINTER)?"<a href='vistas/modulos/download.php?filename=$file&ruta=2&mime=xml' title='Descargar XML' class='btn btn-sm btn-info px-1 py-0'><i class='fa fa-file-code-o'></i></a> ":"";

        }else{          //REP no está timbrado
            $boton0 =getAccess($acceso, ACCESS_ADD)?"<td><button class='btn btn-sm btn-danger px-0 py-0' onclick='TimbrarCompPago20(this)' data-id='".$value['id']."' data-folio='".$value['foliorep']."' data-rfcemisor='".$value['rfcemisor']."' data-fecha='".$value['fechaelaboracion']."'  title='R.E.P. sin timbrar'><i class='fa fa-bell fa-fw'></i> </button></td> ":"";

            $boton1 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-sm btn-primary px-1 py-0 ' data-editar='".$value['id']."' title='Editar REP' ><i class='fa fa-file-edit'></i></button></td> ":"";

        }
    
        $boton2 =getAccess($acceso, ACCESS_DELETE)?"<td><button class='btn btn-sm btn-danger px-1 py-0 ' data-eliminar='".$value['id']."' title='Eliminar registro'><i class='fa fa-trash'></i></button></td> ":"";
        $boton3 =getAccess($acceso, ACCESS_VIEW)?"<td><button class='btn btn-sm btn-success px-1 py-0 printPdfRep' data-pdf='".$value['id']."' title='Generar e imprimir PDF'><i class='fa fa-file-pdf-o'></i></button></td> ":"";

        //MOSTRAR LOS BOTONES
        $botones=$boton0.$boton3.$boton1.$boton2;

        $data[]=array(
            $value["id"],
            $value["foliorep"],
            $fechaelaboracion,
            $value["fechatimbradorep"],
            $value["fechapago"],
            $value["rfcemisor"],
            $value["rfcreceptor"],
            $value["totalrecibo"],
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
/************************************************************************************************** */
//                 PARA TIMBRAR COMPLEMENTO DE PAGO 2.0
/************************************************************************************************** */
case 'AgregaCtrlFacts':
    try{
       
        if(isset($_GET['ids'])){  

            $valor = $_GET['ids'];
            $ids=implode(",",$valor);
            $campo          = "id";
            $usuario=$_SESSION['id'];
            $tabla          = "facturaingreso";
        
            $respuesta = ControladorFacturaIngreso::ctrAgregaCtrlFacts($tabla, $campo, $valor, $ids, $usuario);

            if($respuesta){
                json_output(json_build(201, $respuesta, 'Respuesta Exitosa'));
            }else{
                json_output(json_build(401, $respuesta, 'Respuesta con Errores'));    
            }

        }else{
            json_output(json_build(401, $respuesta, 'No hay datos para procesar'));
         }
    } catch (Exception $e) {
        json_output(json_build(403, 'No hay datos', $e->getMessage()));
    }    
break; 
 /************************************************************************************************** */    
		default:
            json_output(json_build(403, null, 'No existe opciòn. Revise'));
			return false;
		break;



} // fin del switch

/*
$age = array("Peter"=>"35", "Ben"=>"37", "Joe"=>"43");

foreach($age as $x => $val) {
  echo "$x = $val<br>";
}
/*Peter = 35
Ben = 37
Joe = 43
*/

