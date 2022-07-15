<?php
//if(strlen(session_id())>1)
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("America/Mexico_City");
$fechaHoy=date("Y-m-d");

require_once "../controladores/adminquejas.controlador.php";
require_once "../modelos/adminquejas.modelo.php";
require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";

require_once '../funciones/funciones.php';

switch ($_GET["op"]){

     case 'listar':


		if(isset($_POST["fecha1"])){
			$fecha1=$_POST["fecha1"];	
			$fecha2=$_POST["fecha2"];
		}else{
			$fecha1=$fecha2=$fechaHoy;
		}


		$tabla="usuarios";
        $usuario=$_SESSION['id'];
		$module="pcapquejas";
		$campo="administracion";
		$acceso=accesomodulo($tabla, $usuario, $module, $campo);

        $tabla="tbl_lorg";
		
        $listarQuejas = ControladorAdminQueja::ctrListarQuejas($tabla, $fecha1, $fecha2);	
          
        if(count($listarQuejas) == 0){
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    

        foreach($listarQuejas as $key => $value){
            $fecha = date('d-m-Y', strtotime($value["fecha"]));
            
            //$numerotarjeta ='<td>'.$value["numerotarjeta"].'</td>';
            $motivo=strlen($value["motivo"])>47?substr($value["motivo"],0,47).'...':$value["motivo"];

            $trf='</tr';
            $estatus=$value["estatus"]==1?"<button class='btn btn-warning btn-sm px-1 py-1' title='Llamada'><i class='fa fa-volume-control-phone'></i></button>":" <button class='btn btn-primary btn-sm px-1 py-1' title='Por diadema'><i class='fa fa-headphones'></i></button>";
            $boton1 =getAccess($acceso, ACCESS_VIEW)?"<td><button class='btn btn-sm text-info  btnVerQueja' idQueja='".$value['id']."' title='Visualizar queja.'><i class='fa fa-eye'></i></button></td> ":"";
            $boton2 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-sm text-primary  btnEditQueja' idQueja='".$value['id']."' title='Editar Queja' data-toggle='modal' data-target='#modalEditarQueja'><i class='fa fa-edit'></i></button></td> ":""; 
            $boton3 =getAccess($acceso, ACCESS_DELETE)?"<td><button class='btn btn-sm  text-danger btnBorraQueja' idQueja='".$value['id']."' title='Eliminar Registro '><i class='fa fa-trash-o'></i></button></td> ":"";

            $botones=$boton1.$boton2.$boton3;

            $data[]=array(
                $value["id"],
                $value["id_empresa"],
                $value["tecnico"],
                $value["os"],
                $value["telefono"],
                $value["folio_oci"],
                $motivo,
                trim($value["cliente"]),
                $fecha,
                $estatus,
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

    case 'guardarQueja':

        if(isset($_POST["idDeUsuario"])){

            $respuesta=[];
            $tabla="tbl_lorg";

            $cliente=strtoupper(trim($_POST["nombrecontrato"]));
            $operador=strtoupper(trim($_POST["operador"]));
            $observaciones=strtoupper(trim($_POST["altaobservaciones"]));
            $foliooci=trim($_POST["foliooci"]);
            $sane = $stringsanitizado=$sane_char="";
            $motivo = trim($_POST["motivo"]);
            $estatus = (isset($_POST['medioqueja']) ? ($_POST['medioqueja']=='op2' ? 2 : 1) : 1);

            // if (!preg_match('/^[$\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $motivo)) {
            //     $respuesta = array('motivo' => $motivo, 'result2' => 'datos no permitidos');
            // }else{
            //     $respuesta = array('motivo' => $motivo, 'result2' => 'datos permitidos');
            // }

            $patron = "/^[[:digit:]]+$/";       //solo acepte digitos
            // Caracteres prohibidos porque en algunos sistemas operativos no son admitidos como nombre de fichero
            // Obtenida del código fuente de WordPress.org
            $forbidden_chars = array(
                "?", "[", "]", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&",
                "$", "#", "*", "(", ")" , "|", "~", "`", "!", "{", "}", "%", "+" , chr(0));


            // Convertimos el texto a analizar a minúsculas
            $source = strtoupper($motivo);

            //Comprobamos cada carácter
            for( $i=0 ; $i < strlen($source) ; $i++ ) {
                $sane_char = $source_char = $source[$i];
                
                if ( in_array( $source_char, $forbidden_chars ) ) {
                    $sane_char = "_";
                    $sane .= $sane_char;
                    continue;
                }
                
                if ( ord($source_char) < 32 || ord($source_char) > 128 ) {
                    /* Todos los caracteres codificados por debajo de 32 o encima de 128 son especiales Ver http://www.asciitable.com/ */
                    $sane_char = "_";
                    $sane .= $sane_char;
                    continue;
                }
                // Si ha pasado todos los controles, aceptamos el carácter
                $stringsanitizado .= $sane_char;
            }


                $idDeUsuario=$_POST["idDeUsuario"];
                if (filter_var($idDeUsuario, FILTER_VALIDATE_INT) || !filter_var($idDeUsuario, FILTER_VALIDATE_INT) === false) {
                    //$respuesta += array('idDeUsuario' => $idDeUsuario, 'result' => 'entero');
                }else{
                    $respuesta += array('idDeUsuario' => $idDeUsuario, 'result' => 'noentero');
                }

                $numeroos=$_POST["numeroos"];
                if (!preg_match($patron, $numeroos)) {
                    $respuesta += array('os:' => $numeroos, 'result4' => 'no solo digitos');
                }
                
                $numtelefono=trim($_POST["numtelefono"]);
                if (!preg_match($patron, $numtelefono)) {
                    $respuesta += array('numtelefono' => $numtelefono, 'result4' => 'no solo digitos');
                }

                $totalmin=$_POST["totalmin"].'00';

                $datos = array(
                "id_tecnico"    =>$_POST["idDeUsuario"],
                "fecha"         =>$_POST["fechacapt"],
                "os"            =>$numeroos,
                "telefono"      =>$numtelefono,
                "distrito"      =>$_POST["distritoos"],
                "cliente"       =>$cliente,
                "motivo"        =>$stringsanitizado,
                "operador"      =>$operador,
                "folio_oci"     =>$foliooci,
                "inicio_llamada"=>$_POST["datetimepicker1"],
                "fin_llamada"   =>$_POST["datetimepicker3"],
                "minutos"       =>$totalmin,
                "observaciones" =>$observaciones,
                "estatus"       =>$estatus,
                "ultusuario"    =>$idDeUsuario
                );

                if(empty($respuesta) || sizeof($respuesta)==0){
                    $respuesta = ControladorAdminQueja::ctrGuardarQueja($tabla, $datos);
                }
                
                echo json_encode($respuesta);

        }else{

            //$respuesta += array('idDeUsuario' => $idDeUsuario, 'error' => 'sindatos');
            $respuesta = array('error' => 'sindatos');
            echo json_encode($respuesta);
        }
    break;  

    case 'actualizarQueja':

        if(isset($_POST["idDeUsuario"])){

            $respuesta=[];
            $tabla="tbl_lorg";

            $cliente=strtoupper(trim($_POST["nombrecontrato"]));
            $operador=strtoupper(trim($_POST["operador"]));
            $observaciones=strtoupper(trim($_POST["altaobservaciones"]));
            $foliooci=trim($_POST["foliooci"]);
            $sane = $stringsanitizado=$sane_char="";
            $motivo = trim($_POST["motivo"]);

            $estatus = (isset($_POST['medioqueja']) ? ($_POST['medioqueja']=='op2' ? 2 : 1) : 1);

            $patron = "/^[[:digit:]]+$/";       //solo acepte digitos
            // Caracteres prohibidos porque en algunos sistemas operativos no son admitidos como nombre de fichero
            // Obtenida del código fuente de WordPress.org
            $forbidden_chars = array(
                "?", "[", "]", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&",
                "$", "#", "*", "(", ")" , "|", "~", "`", "!", "{", "}", "%", "+" , chr(0));


            // Convertimos el texto a analizar a minúsculas
            $source = strtoupper($motivo);

            //Comprobamos cada carácter
            for( $i=0 ; $i < strlen($source) ; $i++ ) {
                $sane_char = $source_char = $source[$i];
                
                if ( in_array( $source_char, $forbidden_chars ) ) {
                    $sane_char = "_";
                    $sane .= $sane_char;
                    continue;
                }
                
                if ( ord($source_char) < 32 || ord($source_char) > 128 ) {
                    /* Todos los caracteres codificados por debajo de 32 o encima de 128 son especiales Ver http://www.asciitable.com/ */
                    $sane_char = "_";
                    $sane .= $sane_char;
                    continue;
                }
                // Si ha pasado todos los controles, aceptamos el carácter
                $stringsanitizado .= $sane_char;
            }


                $idDeUsuario=$_POST["idDeUsuario"];
                if (filter_var($idDeUsuario, FILTER_VALIDATE_INT) || !filter_var($idDeUsuario, FILTER_VALIDATE_INT) === false) {
                    //$respuesta += array('idDeUsuario' => $idDeUsuario, 'result' => 'entero');
                }else{
                    $respuesta += array('idDeUsuario' => $idDeUsuario, 'result' => 'noentero');
                }

                $numeroos=$_POST["numeroos"];
                if (!preg_match($patron, $numeroos)) {
                    $respuesta += array('os:' => $numeroos, 'result4' => 'no solo digitos');
                }
                
                $numtelefono=trim($_POST["numtelefono"]);
                if (!preg_match($patron, $numtelefono)) {
                    $respuesta += array('numtelefono' => $numtelefono, 'result4' => 'no solo digitos');
                }

                $totalmin=$_POST["totalmin"].'00';

                $datos = array(
                "id"            =>$_POST["ctrlid"],
                "id_tecnico"    =>$_POST["idDeUsuario"],
                "fecha"         =>$_POST["fechacapt"],
                "os"            =>$numeroos,
                "telefono"      =>$numtelefono,
                "distrito"      =>$_POST["distritoos"],
                "cliente"       =>$cliente,
                "motivo"        =>$stringsanitizado,
                "operador"      =>$operador,
                "folio_oci"     =>$foliooci,
                "inicio_llamada"=>$_POST["datetimepicker1"],
                "fin_llamada"   =>$_POST["datetimepicker3"],
                "minutos"       =>$totalmin,
                "observaciones" =>$observaciones,
                "estatus"       =>$estatus,
                "ultusuario"    =>$idDeUsuario
                );

                if(empty($respuesta) || sizeof($respuesta)==0){
                    $respuesta = ControladorAdminQueja::ctrActualizarQueja($tabla, $datos);
                }
                
                echo json_encode($respuesta);

        }else{

            //$respuesta += array('idDeUsuario' => $idDeUsuario, 'error' => 'sindatos');
            $respuesta = array('error' => 'sindatos');
            echo json_encode($respuesta);
        }
    break;  

    //traer datos del deposito seleccionado.
    case 'verqueja':
        $_POST = json_decode(file_get_contents('php://input'), true);
        $stat="error";
        if(isset($_POST["id"]) && !empty($_POST["id"])){
            $tabla="tbl_lorg";
            $campo="id";

            $resp = ControladorAdminQueja::ctrVerQueja($tabla, $_POST["id"], $campo);
            $respuesta = array('respuesta' => 400, $resp);
            echo json_encode($respuesta);

        }else{
			$respuesta = array("Respuesta:" =>$stat);
			echo json_encode($respuesta);
        }

    break;     

    case 'borrar':

        if(isset($_POST["id"]) && !empty($_POST["id"])){
            $stat="error";
            $tabla="tbl_lorg";
            $campo="id";

            $respuesta = ControladorAdminQueja::ctrBorrarQueja($tabla, $_POST["id"], $campo);

            echo json_encode($respuesta);

        }else{
			$respuesta = array("respuesta:" =>$stat);
			echo json_encode($respuesta);
        }

    break;     

     default:
        $error = array("error"=>"sin datos");
        echo json_encode($error);        
     break;

} // fin del switch

