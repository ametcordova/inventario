<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../controladores/control-facturas.controlador.php";
require_once "../modelos/control-facturas.modelo.php";

require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";
require_once '../funciones/funciones.php';

switch ($_GET["op"]){
        
	case 'guardar':
		if(isset($_POST["nuevaFactura"])){
            
			if(preg_match('/^[#\_\/a-zA-Z0-9]+$/', $_POST["nuevaFactura"]) &&
               preg_match('/^[#\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoCliente"])
            ){
				$tabla = "facturas";

/*=============================================================================
	RUTA DONDE VAMOS A GUARDAR ARCHIVO PDF
===============================================================================*/
				$rutaexpediente=null;
				if(isset($_FILES['nuevoPdf']) && $_FILES['nuevoPdf']['type']=='application/pdf'){

					if ($_FILES["nuevoPdf"]["error"] > 0){
						 echo "ha ocurrido un error:";
							//$rutaexpediente=null;
					} else {
							$rutaexpediente = "vistas/expedientes/"."expediente".$_POST["nuevaFactura"].".pdf";
							$rutaarchivo="../vistas/expedientes/"."expediente".$_POST["nuevaFactura"].".pdf";
						
							$resultado = move_uploaded_file($_FILES["nuevoPdf"]["tmp_name"], $rutaarchivo);	// Finalmente sube el archivo
							
							if(!$resultado){
								$rutaexpediente="ERROR";
								echo $rspta ="error:".$rutaexpediente;
							}
					}
				}
									
/* ================================================================================*/

				$datos = array("numfact"        => $_POST["nuevaFactura"],
                               "serie"			=> $_POST["nuevaSerie"],
                               "fechafactura"   => $_POST["nvaFechaFactura"],
                               "cliente"        => strtoupper($_POST["nuevoCliente"]),
                               "numorden"       => $_POST["nuevaOrden"],
                               "subtotal"       => $_POST["nvoSubtotal"],
                               "iva"        	=> $_POST["nvoIva"],
                               "imp_retenido"  	=> $_POST["nvaRetencion"],
                               "importe"        => $_POST["nvoImporteFactura"],
                               "tipotrabajo"    => $_POST["nvoTipoTrabajo"],
                               "fechaentregado" => $_POST["nvaFechaEntregado"],
                               "observaciones"  => strtoupper($_POST["nvaObservacion"]),
                               "contrato"  		=> strtoupper($_POST["nuevoContrato"]),
                               "status"         => $_POST["nvoStatusFactura"],
						       "idusuario"      => $_POST["idDeUsuario"],
							   "rutaexpediente"	=> $rutaexpediente
							   );
                
                $rspta = ControladorFacturas::ctrCrearFactura($tabla, $datos);
                echo $rspta;
            }else{
              return false;  
            }
			return false;  
        }
	break;
	
        
	case 'mostrar':
        if(isset($_POST["idFactura"])){
            $item = "id";
			$valor = $_POST["idFactura"];
			$orden = $_POST["idBorrado"];

            $respuesta = ControladorFacturas::ctrMostrarFacturas($item, $valor, $orden, $tipo=null, $year=null, $monthinicial=null, $monthfinal=null, $solopagadas=null);

            echo json_encode($respuesta);

        };
 	break;
        
	case 'guardareditar':
		if(isset($_POST["editaFactura"])){
            $rspta=false;
			if(preg_match('/^[#\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editaCliente"])
            ){
				$tabla = "facturas";
				
/*=============================================
	RUTA DONDE VAMOS A GUARDAR ARCHIVO PDF
=============================================*/
				$rutaexpediente=null;
				if(isset($_FILES['editarPdf']) && $_FILES['editarPdf']['type']=='application/pdf'){

					if ($_FILES["editarPdf"]["error"] > 0){
						 echo "ha ocurrido un error:";
						 exit;
							//$rutaexpediente=null;
					} else {
							$rutaexpediente = "vistas/expedientes/"."expediente".$_POST["editaFactura"].".pdf";
							$rutaarchivo="../vistas/expedientes/"."expediente".$_POST["editaFactura"].".pdf";
						
							$resultado = move_uploaded_file($_FILES["editarPdf"]["tmp_name"], $rutaarchivo);	// Finalmente sube el archivo
							if(!$resultado){
								$rutaexpediente="ERROR";
							echo $rspta ="error:".$rutaexpediente;
					}
				}
				}else{
					if(isset($_POST['actualPdf']) && !empty($_POST['actualPdf'])){
						$rutaexpediente=$_POST['actualPdf'];
					}
				}					
				
/* =============================== ARRAY PARA GUARDAR =========================*/
                $datos = array("idregistro"     => $_POST["idregistro"],
							   "serie"			=> $_POST["editaSerie"],
                               "fechafactura"   => $_POST["editaFechaFactura"],
                               "cliente"        => strtoupper($_POST["editaCliente"]),
                               "numorden"       => $_POST["editaOrden"],
                               "subtotal"       => $_POST["editaSubtotal"],
                               "subtotanterior"	=> $_POST["subtotalanterior"],
                               "iva"        	=> $_POST["editaIva"],
                               "imp_retenido"  	=> $_POST["editaRetencion"],
                               "importe"        => $_POST["editaImporteFactura"],
                               "tipotrabajo"    => $_POST["editaTipoTrabajo"],
                               "fechaentregado" => $_POST["editaFechaEntregado"],
                               "observaciones"  => strtoupper($_POST["editaObservacion"]),
                               "contrato"  		=> strtoupper($_POST["editaContrato"]),
                               "fechapagado"    => $_POST["editaFechaPagado"],
                               "status"         => $_POST["editaStatusFactura"],
						       "idusuario"      => $_POST["idDeUsuario"],
							   "rutaexpediente"	=> $rutaexpediente
                               );
                                               
                $rspta = ControladorFacturas::ctrGuardarEditarFactura($tabla, $datos);

                echo $rspta ? true : "Factura no se pudo actualizar";

            }else{
                
                echo $rspta ? "Registro actualizado!!" : "Registro no se pudo actualizar!!";
                
            }
        }
	break;

	case 'pagarfactura':
		if(isset($_POST["fechaPagoFactura"])){
            $rspta=false;
			$tabla = "facturas";

                $datos = array("registroid"     => $_POST["registroid"],
							   "fechapagado"    => $_POST["fechaPagoFactura"],
							   "numcomplemento" => $_POST["complementoPagoFact"],
                               "status"         => 1,
						       "idusuario"      => $_POST["idDeUsuario"]
                               );
                                               
                $rspta = ControladorFacturas::ctrGuardarPagoFactura($tabla, $datos);

                echo $rspta ? true : "Factura no se pudo actualizar";

        }
	break;
	
	case 'fechapagofactura':
		if(isset($_POST["fechaPagoFactura"])){
			$tabla = "facturas";

                $datos = array("registroid"     => trim($_POST["registroid"]),
							   "fechapagado"    => $_POST["fechaPagoFactura"],
						       "idusuario"      => $_POST["idDeUsuario"]
                               );
                                               
                $rspta = ControladorFacturas::ctrGuardarFechaPagoFactura($tabla, $datos);

                echo $rspta ? $rspta : "Factura no se pudo actualizar";

        }else{
			echo $rspta = "Factura no se pudo actualizar";
		}
	break;

	
	case 'borrar':
        if(isset($_POST["idFactura"])){
            $item = "id";
            $valor = $_POST["idFactura"];
            $estado = $_POST["idEstado"];
			$subtotal=$_POST["subtotalFactura"];
			if($estado!=0){
				return;
			}

            $respuesta = ControladorFacturas::ctrBorrarFactura($item, $valor, $estado, $subtotal);

            echo json_encode($respuesta);

        };
 	break;
	
	case 'listar':

		$tabla="usuarios";
		$module="pctfacts";
		$campo="administracion";
		$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);    
	
	    $tipo = $_GET["tiporeporte"];
	    $year = $_GET["filteryear"];
	    $monthinicial = isset($_GET["filtermonthstart"]) ? $_GET["filtermonthstart"] : null;
	    $monthfinal = isset($_GET["filtermonthend"]) ? $_GET["filtermonthend"] : null;
	    $solopagadas = isset($_GET["factpagadas"]) ? $_GET["factpagadas"] : 0;
		$item = null;
    	$valor = null;
    	$orden = "id";
		$conter=0;
  		$facturas = ControladorFacturas::ctrMostrarFacturas($item, $valor, $orden,$tipo, $year, $monthinicial, $monthfinal, $solopagadas);
		$saldo=ControladorFacturas::ctrMostrarSaldoDisponible();
		$saldodisponible=$saldo[0];
  		if(count($facturas) == 0){
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    

			foreach($facturas as $key => $value){
				$conter++;
				$fechaFactura = date('d-m-Y', strtotime($value["fechafactura"]));
				$fechaEntregado = $value["fechaentregado"]==null?"":date('d-m-Y', strtotime($value["fechaentregado"]));
				$fechaPagado =$value["fechapagado"]==null?" ":date('d-m-Y', strtotime($value["fechapagado"]));
				$importeFact=number_format($value['importe'], 2, '.',',');
				$odc=str_replace(' ', '',$value["numorden"])==''?'S/O':str_replace(' ', '',$value["numorden"]);
				$ob=str_replace(' ', '',$value["observaciones"])==''?'S/P':str_replace(' ', '',$value["observaciones"]);
				
				//." <button class='btn btn-primary btn-sm px-1 py-1 btnAgregaGastos' idFactura='".$value['id']."' numFactura='".$value['numfact']."' idEstado='".$value['status']."' data-toggle='modal' data-target='#modalAgregarGastos' title=''><i class='fa fa-plus-circle'></i></button>"

				if(getAccess($acceso, ACCESS_SELECT)){
					$botonLock=$value["status"]==0?"<button class='btn btn-warning btn-sm px-1 py-1 btnPagarFactura' dFechaPago='".$value['fechapagado']."' idFactura='".$value['id']."' numfactura='".$value['numfact']."' data-toggle='modal' data-target='#modalPagarFactura' title='Cap. Fecha Pago'><i class='fa fa-unlock'></i></button>":
					"<button class='btn btn-success py-1 btn-sm' idEstado='".$value['status']."' title='Cerrado'><i class='fa fa-lock'></i></button>";
				}else{
					$botonLock=$value["status"]==0?"<button class='btn btn-warning btn-sm px-1 py-1 disabled' title='Cap. Fecha Pago'><i class='fa fa-unlock'></i></button>":
					"<button class='btn btn-success py-1 btn-sm disabled' title='Cerrado'><i class='fa fa-lock'></i></button>";				
				}

				$boton1=getAccess($acceso, ACCESS_EDIT)?"<button class='btn btn-primary btn-sm px-1 btnEditarFactura' idFactura='".$value['id']."' numFactura='".$value['numfact']."' idEstado='".$value['status']."' idBorrado='".$value['borrado']."' data-toggle='modal' data-target='#modalEditarFactura' title='Editar Factura'><i class='fa fa-pencil'></i></button> ":"";
				$boton2=getAccess($acceso, ACCESS_PRINTER)?"<button class='btn btn-info btn-sm px-1 btnVerFactura' id=fila".$conter." idFactura='".$value['id']."' numFactura='".$value['numfact']."' idEstado='".$value['status']."' idBorrado='".$value['borrado']."' title='Ver Expediente'><i class='fa fa-eye'></i></button> ":"";
				if($value["borrado"]==0){
					$boton3=getAccess($acceso, ACCESS_DELETE)?"<button class='btn btn-danger btn-sm px-1 btnBorrarFactura' title='Borrar Factura' idFactura='".$value['id']."' numFactura='".$value['numfact']."' subtotalFactura='".$value['subtotal']."' idEstado='".$value['status']."' idBorrado='".$value['borrado']."'><i class='fa fa-times'></i></button>":"";
				}else{
					$boton3=getAccess($acceso, ACCESS_DELETE)?"<button class='btn btn-danger btn-sm px-1 disabled' title='Borrar Factura' ><i class='fa fa-times'></i></button>":"";
				}
				$botones = $boton1.$boton2.$boton3;

				$data[]=array(
					$value["numfact"],
					$value["serie"],
					substr($value["cliente"],0,30),
					substr($value["tipotrabajo"],0,30),
					substr($odc,0,10),
					substr($ob,0,10),
					$fechaFactura,
					number_format($value["subtotal"], 2, '.',','),
					number_format($value["iva"], 2, '.',','),
					number_format($value["imp_retenido"], 2, '.',','),
					$importeFact,
					$fechaEntregado,
					$fechaPagado,
					$value["numcomplemento"],
					$botonLock,
					$botones,
					$saldodisponible
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
