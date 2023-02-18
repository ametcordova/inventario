<?php
session_start();    
// if(!strlen(session_id())>1){
// }
date_default_timezone_set("America/Mexico_City");
$fechaHoy=date("Y-m-d");

require_once "../controladores/gestion-empresas.controlador.php";
require_once "../modelos/gestion-empresas.modelo.php";
require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";
require_once '../funciones/funciones.php';
//include_once 'downloadxml.php';

switch ($_GET["op"]){

     case 'listarEmpresas':
        $status=0;
		if(isset($_POST["status"])){
			$status=$_POST["status"];
        }

		$tabla="usuarios";
        $usuario=$_SESSION['id'];
        $todes=$_SESSION['perfil']=="Administrador"?"":$_SESSION['id'];
		$module="pentradas";
		$campo="administracion";
		$acceso=accesomodulo($tabla, $usuario, $module, $campo);

        //renombramos la variable $tabla
        $tabla="empresa";
		
        $listar = ControladorEmpresas::ctrListarEmpresas($tabla, $usuario, $todes, $status);	
          
        if(count($listar) == 0){
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    

        foreach($listar as $key => $value){

            $serie=is_null($value["seriefacturacion"])?'':trim($value["seriefacturacion"]);
            
                $boton0 =getAccess($acceso, ACCESS_VIEW)?"<td><button class='btn btn-sm btn-info px-1 py-1' title='Factura sin timbrar' data-idempresa='".$value['id']."' data-toggle='modal' data-target='#modalVerEmpresa'><i class='fa fa-eye'></i> </button></td> ":"";

                $boton1 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-primary btn-sm px-1 py-1' data-idempresa='".$value['id']."' title='Editar Factura' data-toggle='modal' data-target='#modalEditarEmpresa'><i class='fa fa-edit'></i></button></td> ":"";

                $boton2 =getAccess($acceso, ACCESS_DELETE)?"<td><button class='btn btn-danger btn-sm px-1 py-1' data-idempresa='".$value['id']."'  title='Eliminar Empresa'><i class='fa fa-trash'></i></button></td> ":"";

          
            $botones=$boton0.$boton1.$boton2;

            $data[]=array(
                $value['id'],
                $value['razonsocial'],
                $value["rfc"],
                $value["direccion"],
                $value["colonia"],
                $value["telempresa"],
                $value["mailempresa"],
                $value["vigencia_cert"],
                $status,
                $botones
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

 /************************************************************************************************** */    
		default:
            json_output(json_build(403, null, 'No existe opciòn. Revise'));
			return false;
		break;



} // fin del switch

