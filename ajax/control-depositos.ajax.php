<?php
//if(strlen(session_id())>1)
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("America/Mexico_City");
$fechaHoy=date("Y-m-d");

require_once "../controladores/control-depositos.controlador.php";
require_once "../modelos/control-depositos.modelo.php";
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
		$module="pdeposito";
		$campo="administracion";
		$acceso=accesomodulo($tabla, $usuario, $module, $campo);

        $tabla="control_depositos";
		
        $listarSalidas = ControladorCtrolDepositos::ctrListarDepositos($tabla, $fechadev1, $fechadev2);	
          
        if(count($listarSalidas) == 0){
  			echo '{"data": []}';           //arreglar, checar como va
		  	return;
  		}    

        foreach($listarSalidas as $key => $value){
            $fecha = date('d-m-Y', strtotime($value["fecha_transaccion"]));
            
            //$numerotarjeta ='<td>'.$value["numerotarjeta"].'</td>';
            $motivo=strlen($value["motivo"])>35?substr($value["motivo"],0,40).'...':$value["motivo"];

            $trf='</tr';

            $boton1 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-sm text-info px-1 py-1 btn-copiarreferencia' title=' &nbsp&nbsp Doble click para\n copiar referencia.'><i class='fa fa-code'></i></button></td> ":"";
            $boton2 =getAccess($acceso, ACCESS_EDIT)?"<td><button class='btn btn-sm text-primary px-1 py-1 btnEditDeposito' idEditDeposito='".$value['id']."' title='Editar Depósito' data-toggle='modal' data-target='#modalEditarDeposito'><i class='fa fa-edit'></i></button></td> ":""; 
            $boton3 =getAccess($acceso, ACCESS_DELETE)?"<td><button class='btn btn-sm px-1 py-1 text-danger btnDelDeposito' idDeleteDeposito='".$value['id']."' title='Eliminar Deposito '><i class='fa fa-trash-o'></i></button></td> ":"";

            $botones=$boton1.$boton2.$boton3;

            $data[]=array(
                $value["id"],
                $value["nombrecuentahab"],
                $motivo,
                $value["monto_transaccion"],
                $value["monto_comision"],
                $value["nombre"],
                $value["numerotarjeta"],
                $fecha,
                $value["sucursal"],
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

     case 'ajaxBeneficiario':

        $valor = trim(strip_tags($_POST['searchTerm']));
        $tabla = "cuentahabientes";
        $campo = "nombrecuentahab";
        $estatus=1;

        $respuesta = ControladorCtrolDepositos::ctrAjaxBeneficiario($tabla, $campo, $valor, $estatus);

        echo json_encode($respuesta);
    
    break;

     case 'datosBeneficiario':
        
        $valor = $_POST['numero_id'];
        $tabla = "cuentahabientes";
        $campo = "id";

        $respuesta = ControladorCtrolDepositos::ctrDatosBeneficiario($tabla, $campo, $valor);

        echo json_encode($respuesta);
    
    break;

    case 'agregar':

        if(isset($_POST["idDeUsuario"])){

        $respuesta=[];
        $tabla="control_depositos";

        $nvoMotivoDeposito = trim($_POST["nvoMotivoDeposito"]); 
        $sane = $stringsanitizado=$sane_char="";

        if (!preg_match('/^[$\.\,\-\/a-zA-Z0-9\u00E0-\u00FC ]+$/', $_POST["nvoMotivoDeposito"])) {
            $respuesta = array('nvoMotivoDeposito' => $nvoMotivoDeposito, 'result2' => 'datos no permitidos');
        }else{
            $respuesta = array('nvoMotivoDeposito' => $nvoMotivoDeposito, 'result2' => 'datos permitidos');
        }

        // Caracteres prohibidos porque en algunos sistemas operativos no son admitidos como nombre de fichero
        // Obtenida del código fuente de WordPress.org
        $forbidden_chars = array(
            "?", "[", "]", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&",
            "$", "#", "*", "(", ")" , "|", "~", "`", "!", "{", "}", "%", "+" , chr(0));

        // Caracteres que queremos reemplazar por otros que hacen el texto igualmente legible    
        $replace_chars = array(
            'áéíóúäëïöüàèìòùñ ',
            'aeiouaeiouaeioun_'
        );

        // Convertimos el texto a analizar a minúsculas
        $source = strtoupper($nvoMotivoDeposito);

        //Comprobamos cada carácterIdit1611
        for( $i=0 ; $i < strlen($source) ; $i++ ) {
            $sane_char = $source_char = $source[$i];
            
            if ( in_array( $source_char, $forbidden_chars ) ) {
                $sane_char = "_";
                $sane .= $sane_char;
                continue;
            }
/*
            $pos = strpos( $replace_chars[0], $source_char);
            if ( $pos !== false ) {
                $sane_char = $replace_chars[1][$pos];
                $sane .= $sane_char;
                continue;
            }
*/
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
                $respuesta += array('idDeUsuario' => $idDeUsuario, 'result' => 'entero');
            }else{
                $respuesta += array('idDeUsuario' => $idDeUsuario, 'result' => 'noentero');
            }

            $nvoImporte=$_POST["nvoImporte"];
            if (filter_var($nvoImporte, FILTER_SANITIZE_NUMBER_FLOAT) || !filter_var($nvoImporte, FILTER_SANITIZE_NUMBER_FLOAT) === false) {
                $respuesta += array('nvoImporte' => $nvoImporte, 'result0' => 'flotante');
            }else{
                $respuesta += array('nvoImporte' => $nvoImporte, 'result0' => 'noflotante');
            }
            
            $nvoComision=$_POST["nvoComision"];
            if (filter_var($nvoComision, FILTER_SANITIZE_NUMBER_FLOAT) || !filter_var($nvoComision, FILTER_SANITIZE_NUMBER_FLOAT) === false) {
                $respuesta += array('nvoComision' => $nvoComision, 'result1' => 'flotante');
            }else{
                $respuesta += array('nvoComision' => $nvoComision, 'result1' => 'noflotante');
            }
            
            $idBanco=$_POST["idBanco"];
            if (filter_var($idBanco, FILTER_VALIDATE_INT) || !filter_var($idBanco, FILTER_VALIDATE_INT) === false) {
                $respuesta += array('idBanco' => $idBanco, 'result3' => 'entero');
            }else{
                $respuesta += array('idBanco' => $idBanco, 'result3' => 'noentero');
            }

            $patron = "/^[[:digit:]]+$/";       //solo acepte digitos
            $nvoCuenta=trim($_POST["nvoCuenta"]);
            if (preg_match($patron, $nvoCuenta)) {
                $respuesta += array('nvoCuenta' => $nvoCuenta, 'result4' => 'solo digitos');
            }else{
                $respuesta += array('nvoCuenta' => $nvoCuenta, 'result4' => 'no solo digitos');
            }

            $nvoSucursal=$_POST["nvoSucursal"];
            if (filter_var($nvoSucursal, FILTER_SANITIZE_STRING) || !filter_var($nvoSucursal, FILTER_SANITIZE_STRING) === false) {
                $respuesta += array('nvoSucursal' => $nvoSucursal, 'result5' => 'texto');
            }else{
                $respuesta += array('nvoSucursal' => $nvoSucursal, 'result5' => 'notexto');
            }

            $nvoEstatus=$_POST["nvoEstatus"];
            if (filter_var($nvoEstatus, FILTER_VALIDATE_INT) || !filter_var($nvoEstatus, FILTER_VALIDATE_INT) === false) {
                $respuesta += array('nvoEstatus' => $nvoEstatus, 'result5' => 'entero');
            }else{
                $respuesta += array('nvoEstatus' => $nvoEstatus, 'result5' => 'noentero');
            }

            

            $datos = array(
            "id_cuentahabiente" =>$_POST["nvoBeneficiario"],
            "id_destino"        =>$idBanco,
            "motivo"            =>$stringsanitizado,
            "monto_transaccion" =>$nvoImporte,
            "monto_comision"    =>$nvoComision,
            "fecha_transaccion" =>$_POST["nvoFecha"],
            "sucursal"           =>$nvoSucursal,
            "estatus"           =>$nvoEstatus,
            "ultusuario"        =>$idDeUsuario
            );


            $respuesta = ControladorCtrolDepositos::ctrGuardarDeposito($tabla, $datos);
            
            echo json_encode($respuesta);

       }else{

            //$respuesta += array('idDeUsuario' => $idDeUsuario, 'error' => 'sindatos');
            $respuesta = array('error' => 'sindatos');
            echo json_encode($respuesta);
       }
    break;  

    case 'actualizar':

        if(isset($_POST["idDeUsuario"])){

        $resp=[];
        $tabla="control_depositos";

        $nvoMotivoDeposito = trim($_POST["nvoMotivoDeposito"]); 
        $sane = $stringsanitizado=$sane_char="";

        if (!preg_match('/^[$\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nvoMotivoDeposito"])) {
            $resp = array('nvoMotivoDeposito' => $nvoMotivoDeposito, 'result2' => 'datos no permitidos');
        }else{
            $resp = array('nvoMotivoDeposito' => $nvoMotivoDeposito, 'result2' => 'datos permitidos');
        }

        // Caracteres prohibidos porque en algunos sistemas operativos no son admitidos como nombre de fichero
        // Obtenida del código fuente de WordPress.org
        $forbidden_chars = array(
            "?", "[", "]", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&",
            "$", "#", "*", "(", ")" , "|", "~", "`", "!", "{", "}", "%", "+" , chr(0));

        // Caracteres que queremos reemplazar por otros que hacen el texto igualmente legible    
        $replace_chars = array(
            'áéíóúäëïöüàèìòùñ ',
            'aeiouaeiouaeioun_'
        );

        // Convertimos el texto a analizar a minúsculas
        $source = strtoupper($nvoMotivoDeposito);

        //Comprobamos cada carácterIdit1611
        for( $i=0 ; $i < strlen($source) ; $i++ ) {
            $sane_char = $source_char = $source[$i];
            
            if ( in_array( $source_char, $forbidden_chars ) ) {
                $sane_char = "_";
                $sane .= $sane_char;
                continue;
            }
/*
            $pos = strpos( $replace_chars[0], $source_char);
            if ( $pos !== false ) {
                $sane_char = $replace_chars[1][$pos];
                $sane .= $sane_char;
                continue;
            }
*/
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
                $resp += array('idDeUsuario' => $idDeUsuario, 'result' => 'entero');
            }else{
                $resp += array('idDeUsuario' => $idDeUsuario, 'result' => 'noentero');
            }

            $nvoImporte=$_POST["nvoImporte"];
            if (filter_var($nvoImporte, FILTER_SANITIZE_NUMBER_FLOAT) || !filter_var($nvoImporte, FILTER_SANITIZE_NUMBER_FLOAT) === false) {
                $resp += array('nvoImporte' => $nvoImporte, 'result0' => 'flotante');
            }else{
                $resp += array('nvoImporte' => $nvoImporte, 'result0' => 'noflotante');
            }
            
            $nvoComision=$_POST["nvoComision"];
            if (filter_var($nvoComision, FILTER_SANITIZE_NUMBER_FLOAT) || !filter_var($nvoComision, FILTER_SANITIZE_NUMBER_FLOAT) === false) {
                $resp += array('nvoComision' => $nvoComision, 'result1' => 'flotante');
            }else{
                $resp += array('nvoComision' => $nvoComision, 'result1' => 'noflotante');
            }
            
            $idBanco=$_POST["idBanco"];
            if (filter_var($idBanco, FILTER_VALIDATE_INT) || !filter_var($idBanco, FILTER_VALIDATE_INT) === false) {
                $resp += array('idBanco' => $idBanco, 'result3' => 'entero');
            }else{
                $resp += array('idBanco' => $idBanco, 'result3' => 'noentero');
            }

            $patron = "/^[[:digit:]]+$/";       //solo acepte digitos
            $nvoCuenta=trim($_POST["nvoCuenta"]);
            if (preg_match($patron, $nvoCuenta)) {
                $resp += array('nvoCuenta' => $nvoCuenta, 'result4' => 'solo digitos');
            }else{
                $resp += array('nvoCuenta' => $nvoCuenta, 'result4' => 'no solo digitos');
            }

            $nvoSucursal=$_POST["nvoSucursal"];
            if (filter_var($nvoSucursal, FILTER_SANITIZE_STRING) || !filter_var($nvoSucursal, FILTER_SANITIZE_STRING) === false) {
                $resp += array('nvoSucursal' => $nvoSucursal, 'result5' => 'texto');
            }else{
                $resp += array('nvoSucursal' => $nvoSucursal, 'result5' => 'notexto');
            }

            $nvoEstatus=$_POST["nvoEstatus"];
            if (filter_var($nvoEstatus, FILTER_VALIDATE_INT) || !filter_var($nvoEstatus, FILTER_VALIDATE_INT) === false) {
                $resp += array('nvoEstatus' => $nvoEstatus, 'result5' => 'entero');
            }else{
                $resp += array('nvoEstatus' => $nvoEstatus, 'result5' => 'noentero');
            }

            $id=$_POST["identifica"];

            $datos = array(
            "id"                =>$_POST["identifica"],
            "id_destino"        =>$idBanco,
            "motivo"            =>$stringsanitizado,
            "monto_transaccion" =>$nvoImporte,
            "monto_comision"    =>$nvoComision,
            "fecha_transaccion" =>$_POST["nvoFecha"],
            "sucursal"          =>$nvoSucursal,
            "estatus"           =>$nvoEstatus,
            "ultusuario"        =>$idDeUsuario
            );

            $respuesta=ControladorCtrolDepositos::ctrActualizarDeposito($tabla, $datos);
            if($respuesta){
                echo json_encode($respuesta);
            }else{
                echo json_encode($resp);
            }
            
            
            

       }else{

            //$respuesta += array('idDeUsuario' => $idDeUsuario, 'error' => 'sindatos');
            $respuesta = array('error' => 'sindatos');
            echo json_encode($respuesta);
       }
    break; 

    case 'guardarCuantaHabiente':

        $tabla="cuentahabientes";

        if(isset($_POST["idDeUsuario"]) && !empty($_POST["idDeUsuario"])){
            if (filter_var($_POST["idDeUsuario"], FILTER_VALIDATE_INT) || !filter_var($_POST["idDeUsuario"], FILTER_VALIDATE_INT) === false){
                $idDeUsuario=$_POST["idDeUsuario"];
            };

            $nombrecuentahab=checkstring($_POST["nvoCuentaHabiente"]);
            if(empty($nombrecuentahab)){
                $nombrecuentahab="Datos Incorrectos";
            }
            
            $usodeposito=checkstring($_POST["nvoUsoDeposito"]);
            if(empty($usodeposito)){
                $usodeposito="Datos Incorrectos";
            }

            if (filter_var($_POST["nvoDestinatario"], FILTER_VALIDATE_INT) || !filter_var($_POST["nvoDestinatario"], FILTER_VALIDATE_INT) === false){
                $id_destino=$_POST["nvoDestinatario"];
            };

            $patron = "/^[[:digit:]]+$/";       //solo acepte digitos

            $nvoCuenta=trim($_POST["nvoNumeroTarjeta"]);
            if (preg_match($patron, $nvoCuenta)) {
                $numerotarjeta=$nvoCuenta;
            }

            $chekCuentaClabe=trim($_POST["nvoCuentaClabe"]);
            if(!empty($chekCuentaClabe)){
                if (preg_match($patron, $chekCuentaClabe)) {
                    $cuentaclabe=$chekCuentaClabe;
                }
            }else{
                $cuentaclabe=$chekCuentaClabe;
            }

            $fechapago=(!empty($_POST["nvoFechaPago"]))?$_POST["nvoFechaPago"]:$fechaHoy;

            $estatus=(!empty($_POST["nvoStatus"]) || $_POST["nvoStatus"]<2)?$_POST["nvoStatus"]:1;

            $datos = array(
                "nombrecuentahab"   =>$nombrecuentahab,
                "id_destino"        =>$id_destino,
                "numerotarjeta"     =>$numerotarjeta,
                "cuentaclabe"       =>$cuentaclabe,
                "usodeposito"       =>$usodeposito,
                "fechapago"         =>$fechapago,
                "estatus"           =>$estatus,
                "ultusuario"        =>$idDeUsuario
            );

            $respuesta = ControladorCtrolDepositos::ctrGuardarCuentaHabiente($tabla, $datos);
            
            echo json_encode($respuesta);


        }else{
            $respuesta = array('error' => 'sindatos');
            echo json_encode($respuesta);
        }

    break;  

    case 'borrar':
        //$_POST = json_decode(file_get_contents('php://input'), true);
        if(isset($_POST["idDep"]) && !empty($_POST["idDep"])){
            $stat="error";
            $tabla="control_depositos";
            $campo="id";

            $respuesta = ControladorCtrolDepositos::ctrBorrarDeposito($tabla, $_POST["idDep"], $campo);

            echo json_encode($respuesta);

        }else{
			$respuesta = array("respuesta:" =>$stat);
			echo json_encode($respuesta);
        }

    break;     

    //traer datos del deposito seleccionado.
    case 'editardeposito':
        //$_POST = json_decode(file_get_contents('php://input'), true);
        $stat="error";
        if(isset($_POST["iddep"]) && !empty($_POST["iddep"])){
            $tabla="control_depositos";
            $campo="id";

            $resp = ControladorCtrolDepositos::ctrDatosDeposito($tabla, $_POST["iddep"], $campo);
            $respuesta = array('respuesta' => 400, $resp);
            echo json_encode($respuesta);

        }else{
			$respuesta = array("Respuesta:" =>$stat);
			echo json_encode($respuesta);
        }

    break;     

     default:
        $error = array("error"=>"sin datos");
        echo json_encode($error);        
     break;

} // fin del switch


