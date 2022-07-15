<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../controladores/control-viaticos.controlador.php";
require_once "../modelos/control-viaticos.modelo.php";

require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";
require_once '../funciones/funciones.php';

switch ($_GET["op"]){
        
    case 'guardar':
        if(isset($_POST["nvoComisionadoV"]) && !empty($_POST["nvoComisionadoV"])){
            $tabla = "tbl_viaticos";
            $datos = array(
            "id_tecnico"            => $_POST["nvoComisionadoV"],
            "fecha_dispersion"      => $_POST["nvaFecha"],
            "concepto_dispersion"   => $_POST["nvoConcepto"],
            "descripcion_dispersion"=> strtoupper($_POST["nvadescripcion"]),
            "ultusuario"            => $_POST["idDeUsuario"]
            );

            $rspta = ControladorViaticos::ctrGuardarViatico($tabla, $datos);
            echo $rspta;

        }else{
            echo false;  
        }

	break;
	
    case 'guardarAgregaViatico':
        if(isset($_POST["fechagregaviatico"]) && !empty($_POST["importeagregaviatico"])){
            $tabla = "tbl_viaticos_detalle";
            $datos = array(
            "id_viatico"          => $_POST["registroid"],
            "fecha"               => $_POST["fechagregaviatico"],
            "id_mediodeposito"    => $_POST["mediodeposito"],
            "comentario"          => $_POST["nvocomentario"],
            "importe_liberado"    => $_POST["importeagregaviatico"],
            "ultusuario"          => $_POST["idDeUsuario"]
            );

            $rspta = ControladorViaticos::ctrGuardarAgregaViatico($tabla, $datos);
            echo $rspta;

        }else{
            return false;  
        }

	break;
        
    case 'checkup':

        if(isset($_FILES)){
            //$tipos = array("image/gif","image/jpeg","image/jpg","image/png"); 
            //if (in_array($_FILES["imagen"]["type"],$tipos) && $_FILES["imagen"]["size"] <= $maximo)  //Funciona a las mil maravillas
            
            if($_FILES['facturaPdf']['type']=='application/pdf'){
                $tamayo_maximo = 2*(1024*1024);   //max 2mb.
                if( $_FILES['facturaPdf']['size'] > $tamayo_maximo ) {      //NO ENTRA AQUI CUANDO SE TIENE name="MAX_FILE_SIZE"
                    $peso=$_FILES['facturaPdf']['size'];
                    echo json_encode(array('status' => 400, 'mensaje'=> 'Archivo supera los 2MB'));
                    return;
                }

                if(isset($_POST["fechagasto"]) && !empty($_POST["conceptogasto"]) && !empty($_POST["importegasto"])){
                    $tabla = "tbl_viaticos_checkup";
                    $datos = array(
                    "id_viatico"        => $_POST["registroid"],
                    "fecha_gasto"       => $_POST["fechagasto"],
                    "numerodocto"       => $_POST["numerodocto"],
                    "concepto_gasto"    => strtoupper($_POST["conceptogasto"]),
                    "importe_gasto"     => $_POST["importegasto"],
                    "ruta_factura"      => $_FILES['facturaPdf'],
                    "ultusuario"        => $_POST["idDeUsuario"]
                    );
    
                    $rspta = ControladorViaticos::ctrGuardarCheckup($tabla, $datos);
                    if($rspta==500){
                        echo json_encode(array('status' => 400, 'mensaje'=> "Archivo ya existe.  VERIFIQUE!!"));
                        return;
                    }
                    echo json_encode(array('status' => 200, 'mensaje'=> $rspta));
                    //echo $rspta;
    
                }else{
                    echo json_encode(array('status' => 400, 'mensaje'=> 'Datos incorrectos'));
                    return false;  
                }
    
            }else{
                echo json_encode(array('status' => 400, 'mensaje'=> 'Archivo seleccionado no es .PDF o supera los 2MB'));
                return false;
            }
        
        }


 	break;
        
    case 'getDatosViatico':
        $_GET = json_decode(file_get_contents('php://input'), true);
        if(isset($_GET["idviatico"]) && !empty($_GET["idviatico"])){
            $tabla="tbl_viaticos";
            $idviatico=$_GET["idviatico"];
            $rspta = ControladorViaticos::ctrGetDatosViatico($tabla, $idviatico);
            echo json_encode($rspta);
        }else{
            echo json_encode("Error".$_GET["idviatico"]);
        }
	break;

    case 'getProofViatico':
        $_GET = json_decode(file_get_contents('php://input'), true);
        if(isset($_GET["idviatico"]) && !empty($_GET["idviatico"])){
            $item2="id_viatico";
            $idviatico=$_GET["idviatico"];
            $rspta = ControladorViaticos::ctrGetViaticoCheck($item2, $idviatico);
            echo json_encode($rspta);
        }else{
            echo json_encode("Error".$_GET["idviatico"]);
        }
	break;

    case 'PutCambiaEstatus':
        $_PUT = json_decode(file_get_contents('php://input'), true);
        if(isset($_PUT["idviatico"]) && !empty($_PUT["idviatico"])){
            $item="id";
            $idviatico=$_PUT["idviatico"];
            $idestado=$_PUT["idestado"];
            $rspta = ControladorViaticos::ctrPutCambiaEstatus($item, $idviatico, $idestado);
            //$rspta ="Entra..".$_PUT["idviatico"].$_PUT["idestado"];
            echo json_encode($rspta);
        }else{
            echo json_encode("Error".$_GET["idviatico"]);
        }
 	break;
	
    case 'borrar':
     break;
     
    case 'listar':

		$tabla="usuarios";
		$module="pctviaticos";
		$campo="administracion";
		$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);    

	    $tipo = $_GET["tiporeporte"];
	    $year = $_GET["filterYearViaticos"];
		$item = null;
    	$valor = null;
    	$orden = "id";
        $conter=0;
  		$viaticos = ControladorViaticos::ctrMostrarViaticos($item, $valor, $orden, $tipo, $year);	

  		if(count($viaticos) == 0){
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    
        
           foreach($viaticos as $key => $value){
            if($_SESSION['id']==$value['id_tecnico'] || $_SESSION['perfil']=="Administrador"){
                $conter++;
                $fechaFactura = date('d-m-Y', strtotime($value["fecha_dispersion"]));
                //$fechaPagado =$value["fechapagado"]==null?" ":date('d-m-Y', strtotime($value["fechapagado"]));
                
                $importeFact=number_format($value['importe_dispersion'], 2, '.',',');

                if(getAccess($acceso, ACCESS_ACTIVAR)){
                   $botonLock=$value["estado"]==1?"<button class='btn btn-default btn-sm px-1 py-1 mr-1 btnEstatus' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Abierto'><i class='fa fa-unlock'></i></button>"."<button class='btn btn-success btn-sm px-2 btnAgregaViatico' idviatico='".$value['id']."' idEstado='".$value['estado']."' data-toggle='modal' data-target='#modalAgregaViatico' title='Agregar Viático'><i class='fa fa-plus-circle'></i></button> "."<button class='btn btn-warning btn-sm px-2' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Editar Viático'><i class='fa fa-pencil'></i></button>":"<button class='btn btn-warning btn-sm mr-1 btnEstatus' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Cerrado'><i class='fa fa-lock'></i></button>"."<button class='btn btn-success btn-sm px-2 disabled' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Agregar Viático'><i class='fa fa-plus-circle'></i></button> "."<button class='btn btn-warning btn-sm px-2' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Editar Viático'><i class='fa fa-pencil'></i></button>";
                }else{
                    $botonLock=$value["estado"]==1?"<button class='btn btn-default btn-sm px-1 mr-1 disabled' title='Abierto'><i class='fa fa-unlock'></i></button>"."<button class='btn btn-success btn-sm px-2 disabled' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Agregar Viático'><i class='fa fa-plus-circle'></i></button>":"<button class='btn btn-warning py-1 btn-sm mr-1 disabled' idEstado='".$value['estado']."' title='Cerrado'><i class='fa fa-lock'></i></button>"."<button class='btn btn-success btn-sm px-2 disabled' title='Agregar Viático'><i class='fa fa-plus-circle'></i></button>"."<button class='btn btn-info btn-sm px-2 disabled' title='Agregar Viático'><i class='fa fa-plus-circle'></i></button>";
                }
                
                // if($_SESSION['perfil']=="Administrador"){
                //     if($value['estado']==0){    //cerrado
                //         $botones = "<button class='btn btn-primary btn-sm px-2 btnVerReporte' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Ver reporte'><i class='fa fa-desktop'></i></button>"."
                //         <button class='btn btn-dark btn-sm px-2 btnPrintViatico' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Reporte en PDF'><i class='fa fa-file-pdf-o'></i></button>"." 
                //         <button class='btn btn-danger btn-sm px-1 disabled' title='Borrar' idFactura='".$value['id']."' idEstado='".$value['estado']."'><i class='fa fa-times'></i></button>"."
                //         <button class='btn btn-info btn-sm px-2 disabled' title='Comprobación'><i class='fa fa-edit'></button>"; 
                //     }else{
                //         $botones = "<button class='btn btn-primary btn-sm px-2 btnVerReporte' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Ver reporte'><i class='fa fa-desktop'></i></button>"."
                //         <button class='btn btn-dark btn-sm px-2 btnPrintViatico' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Reporte en PDF'><i class='fa fa-file-pdf-o'></i></button>"." 
                //         <button class='btn btn-danger btn-sm px-1 btnBorrarFactura' title='Borrar' idFactura='".$value['id']."' idEstado='".$value['estado']."'><i class='fa fa-times'></i></button>"."
                //         <button class='btn btn-info btn-sm px-2 btnCheckup' idviatico='".$value['id']."' idEstado='".$value['estado']."' data-toggle='modal' data-target='#modalCheckup' title='Comprobación'><i class='fa fa-edit'></button>"; 
                //     }
                // }else{
                //     if($value['estado']==1){
                //         $botones = "<button class='btn btn-primary btn-sm px-2 btnVerReporte' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Ver reporte'><i class='fa fa-desktop'></i></button>"."
                //         <button class='btn btn-dark btn-sm px-2 btnPrintViatico' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Reporte en PDF'><i class='fa fa-file-pdf-o'></i></button>"." 
                //         <button class='btn btn-info btn-sm px-2 btnCheckup' idviatico='".$value['id']."' idEstado='".$value['estado']."' data-toggle='modal' data-target='#modalCheckup' title='Comprobación'><i class='fa fa-edit'></button>"; 
                //     }else{
                //         $botones = "<button class='btn btn-primary btn-sm px-2 btnVerReporte' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Ver reporte'><i class='fa fa-desktop'></i></button>"."
                //         <button class='btn btn-dark btn-sm px-2 btnPrintViatico' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Reporte en PDF'><i class='fa fa-file-pdf-o'></i></button>"." 
                //         <button class='btn btn-info btn-sm px-2 disabled' title='Comprobación'><i class='fa fa-edit'></button>"; 
                //     }
                // }

                $boton1=getAccess($acceso, ACCESS_VIEW)?"<button class='btn btn-primary btn-sm px-2 btnVerReporte' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Ver reporte'><i class='fa fa-desktop'></i></button> ":"";
                $boton2=getAccess($acceso, ACCESS_PRINTER)?"<button class='btn btn-dark btn-sm px-1 btnPrintViatico' idviatico='".$value['id']."' idEstado='".$value['estado']."' title='Reporte en PDF'><i class='fa fa-file-pdf-o'></i></button> ":"";
                $boton3=getAccess($acceso, ACCESS_PRINTER)?"<button class='btn btn-dark btn-sm px-1 btnExcel' title='Reporte en excel' idviatico='".$value['id']."'><i class='fa fa-file-excel-o'></i></button> ":"";
                $boton4=getAccess($acceso, ACCESS_DELETE)?"<button class='btn btn-danger btn-sm px-1 btnBorrarFactura' title='Borrar' idviatico='".$value['id']."' idEstado='".$value['estado']."'><i class='fa fa-times'></i></button> ":"";
                $boton5=getAccess($acceso, ACCESS_SELECT)?"<button class='btn btn-info btn-sm px-2 btnCheckup' idviatico='".$value['id']."' idEstado='".$value['estado']."' data-toggle='modal' data-target='#modalCheckup' title='Comprobación'><i class='fa fa-edit'></button>":"";
                
                $botones=$boton1.$boton2.$boton3.$boton4.$boton5;

                $data[]=array(
                    $value["id"],
                    $fechaFactura,
                    substr($value["usuario"],0,15),
                    substr($value["descripcion_dispersion"],0,60),
                    number_format($value["totalliberado"], 2, '.',','),
                    number_format($value["saldo_actual"], 2, '.',','),
                    $botonLock,
                    $botones,
                );
            } //fin del IF 
        }   //fin del foreach
        
        if($conter==0){
            $data=[];         //arreglar, checar como va
        };
        $results = array(
					"sEcho"=>1, //Información para el datatables
					"iTotalRecords"=>count($data), //enviamos el total registros al datatable
					"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
					"aaData"=>$data);
    echo json_encode($results);        
            
    break;

    case 'getNumViatico':
     /*=============================================
          ASIGNAR NUMERO DE VIATICO
     =============================================*/	 
        $respuesta = ControladorViaticos::ctrgetNumViatico();
        echo $respuesta ? json_encode($respuesta[0]) : json_encode(0);
    break;    

    default:
        echo "sin respuesta";

}  //FIN DE SWITCH
