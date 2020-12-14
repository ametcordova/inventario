<?php
session_start();
date_default_timezone_set("America/Mexico_City");
$fechaHoy=date("Y-m-d");

require_once "../controladores/cajas.controlador.php";
require_once "../modelos/cajas.modelo.php";

require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";
require_once '../funciones/funciones.php';

switch ($_GET["op"]){

    case 'listarcortes':

		//VALIDA ACCESO
		$tabla="usuarios";
		$module="rcortes";
		$campo="reportes";

		$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);
	
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
		
  		$listarcortes = ControladorCajas::ctrListarCorteVentas($item, $valor, $orden, $fechadev1, $fechadev2);	
		  
  		if(count($listarcortes) == 0){
			//echo json_encode($listarDevTec); 
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    
        
        foreach($listarcortes as $key => $value){
			$fechaventa = date('d-m-Y', strtotime($value["fecha_venta"]));
			$totdeventas=$value['ventasgral']+$value['ventaspromo'];
			$tri = '<tr class="table-success"><td>'.($value["id"]).'</td>';

			if(getAccess($acceso, ACCESS_PRINTER)){
				if($value['estatus']==1){
					$botones = "<td><button  class='btn btn-info btn-sm p-0 px-1 btnVerCorteVenta'idCorteVenta='".$value['id']."' data-fechaventa=$fechaventa data-fechacutvta='".$value['fecha_venta']."' data-caja='".$value['id_caja']."' data-nomcaja='".$value['caja']."' data-cajachica='".$value['cajachica']."' data-vtas='".$totdeventas."' data-env='".$value['ventasenvases']."' data-abarrotes='".$value['ventasabarrotes']."' data-serv='".$value['ventasservicios']."' data-cred='".$value['ventascredito']."' data-ing='".$value['monto_ingreso']."' data-egr='".$value['monto_egreso']."' title='Ver Corte de Venta '><i class='fa fa-eye'></i></button>"." 
					<button class='btn btn-success btn-sm p-0 px-1' title='Cerrado'><i class='fa fa-lock'></i></button>
					</td></tr>"; 
				}else{
					$botones = "<td><button  class='btn btn-info btn-sm p-0 px-1 disabled btnVerCorteVenta'idCorteVenta='".$value['id']."' data-fechaventa=$fechaventa data-fechacutvta='".$value['fecha_venta']."' data-caja='".$value['id_caja']."' data-nomcaja='".$value['caja']."' data-vtas='".$totdeventas."' data-cajachica='".$value['cajachica']."' data-env='".$value['ventasenvases']."' data-abarrotes='".$value['ventasabarrotes']."' data-serv='".$value['ventasservicios']."' data-cred='".$value['ventascredito']."' data-ing='".$value['monto_ingreso']."' data-egr='".$value['monto_egreso']."' title='Ver Corte de Venta '><i class='fa fa-eye'></i></button>"." 
					<button class='btn btn-danger btn-sm p-0 px-1 btnMakeCut' title='Sin cerrar corte' id_corte='".$value['id']."' id_caja='".$value['id_caja']."' idFechaCut='".$value['fecha_venta']."'><i class='fa fa-unlock'></i></button>
					</td></tr>"; 
				}
			}else{
				$botones = "";
			}		
			
/*
			$botones='<td>
			data-toggle='modal' data-target='#cortedecaja'
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
                  $value["id_caja"],
                  $fechaventa,
			      $value["ventasgral"],
			      $value["ventaspromo"],
			      $value["ventasenvases"],
			      $value["ventasservicios"],
			      $value["ventasabarrotes"],
			      $value["ventascredito"],
			      $value["total_venta"],
			      $value["monto_ingreso"],
			      $value["monto_egreso"],
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
