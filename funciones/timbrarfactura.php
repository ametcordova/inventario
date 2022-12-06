<?php
//namespace TIMBRADORXPRESS\API;
// if(!strlen(session_id())>1){
//     session_start();
// }
// Se especifica la zona horaria
date_default_timezone_set("America/Mexico_City");

// Se desactivan los mensajes de debug
//error_reporting(~(E_WARNING|E_NOTICE));
error_reporting(E_ALL);
/*********************************************/
require_once dirname( __DIR__ ).'/modelos/conexion.php';

$exist=file_exists(__DIR__ . '/class.conexion.php');

    if($exist){
        require_once __DIR__ . '/class.conexion.php';
    }else{
        exit;
     };

use TIMBRADORXPRESS\API\ConexionWS;
/*********************************************/
class ClaseFacturar{

    static public function GenerarJsonFactura($tabla, $campo, $valor){
    $bytes=0;
    // $tabla=facturaingreso
    $sql="SELECT tb1.*, emp.razonsocial AS nombreemisor, emp.numerocertificado, emp.regimenfiscalemisor, emp.seriefacturacion, clie.rfc AS rfcreceptor, clie.nombre AS nombrereceptor, clie.codpostal AS cpreceptor, clie.regimenfiscal AS regfiscalreceptor, mon.id_moneda, cfdi.id_cfdi
    FROM $tabla tb1
    INNER JOIN empresa emp ON emp.id=tb1.id_empresa
    INNER JOIN clientes clie ON clie.id=tb1.idreceptor
    INNER JOIN c_usocfdi cfdi ON cfdi.id=tb1.idusocfdi
    INNER JOIN c_moneda mon ON mon.id=tb1.idmoneda
    WHERE tb1.$campo=:$campo";

	$stmt = Conexion::conectar()->prepare($sql);

    $stmt -> bindParam(":".$campo, $valor, PDO::PARAM_INT);

  	$stmt -> execute();

    $datosdefactura = $stmt->fetch();
    /*********************************************/
    $conceptos=json_decode($datosdefactura['conceptos'],true);
    $numerofactura=$datosdefactura['seriefacturacion'].$datosdefactura['folio'];
    // Datos de la Factura
    $datos['Comprobante']['Version'] = '4.0';
    $datos['Comprobante']['Serie'] = $datosdefactura['seriefacturacion'];
    $datos['Comprobante']['Folio'] = $datosdefactura['folio'];
    $datos['Comprobante']['Fecha'] = date('Y-m-d\TH:i:s', time() - 120);
    $datos['Comprobante']['FormaPago'] = $datosdefactura['idformapago'];
    $datos['Comprobante']['NoCertificado'] = $datosdefactura['numerocertificado'];
    $datos['Comprobante']['CondicionesDePago'] = 'NA';
    $datos['Comprobante']['SubTotal'] = $datosdefactura['subtotal'];
    $datos['Comprobante']['Moneda'] = $datosdefactura['id_moneda'];
    $datos['Comprobante']['TipoCambio'] = '1';
    $datos['Comprobante']['Total'] = $datosdefactura['totalfactura'];
    $datos['Comprobante']['TipoDeComprobante'] = $datosdefactura['idtipocomprobante'];
    $datos['Comprobante']['Exportacion'] = $datosdefactura['idexportacion'];
    $datos['Comprobante']['MetodoPago'] = $datosdefactura['idmetodopago'];
    $datos['Comprobante']['LugarExpedicion'] = $datosdefactura['idlugarexpedicion'];

    // Datos del Emisor CAMBIAR EN PRODUCCION
    $datos['Comprobante']['Emisor']['Rfc'] = $datosdefactura['rfcemisor']; //RFC DE PRUEBA
    $datos['Comprobante']['Emisor']['Nombre'] = $datosdefactura['nombreemisor'];  // EMPRESA DE PRUEBA
    $datos['Comprobante']['Emisor']['RegimenFiscal'] = $datosdefactura['regimenfiscalemisor'];      

    // Datos del receptor CAMBIAR EN PRODUCCION
    $datos['Comprobante']['Receptor']['Rfc'] = $datosdefactura['rfcreceptor'];
    $datos['Comprobante']['Receptor']['Nombre'] = $datosdefactura['nombrereceptor'];
    $datos['Comprobante']['Receptor']['DomicilioFiscalReceptor'] = $datosdefactura['cpreceptor'];
    $datos['Comprobante']['Receptor']['RegimenFiscalReceptor'] = $datosdefactura['regfiscalreceptor'];
    $datos['Comprobante']['Receptor']['UsoCFDI'] = $datosdefactura['id_cfdi'];

    // DATOS DE LOS CONCEPTOS
    $sumaimporte=0;
    foreach ($conceptos as $key => $value) {
        $datos['Comprobante']['Conceptos'][$key]['ClaveProdServ'] = $value['ClaveProdServ'];
        $datos['Comprobante']['Conceptos'][$key]['NoIdentificacion'] = "1";
        $datos['Comprobante']['Conceptos'][$key]['Cantidad'] = $value['Cantidad'];
        $datos['Comprobante']['Conceptos'][$key]['ClaveUnidad'] = $value['ClaveUnidad'];
        $datos['Comprobante']['Conceptos'][$key]['Unidad'] = $value['Unidad'];
        $datos['Comprobante']['Conceptos'][$key]['Descripcion'] = $value['Descripcion'];
        $datos['Comprobante']['Conceptos'][$key]['ValorUnitario'] = (string)$value['ValorUnitario'];
        $datos['Comprobante']['Conceptos'][$key]['Importe'] = (string)$value['Importe'];
        $datos['Comprobante']['Conceptos'][$key]['ObjetoImp'] = $value['ObjetoImp'];
        $importeimpuesto=((float)$value['Importe']*16)/100;
        $sumaimporte+=(float)$value['Importe'];
        $datos['Comprobante']['Conceptos'][$key]['Impuestos']['Traslados'][0]['Base'] = (string)$value['Importe'];
        $datos['Comprobante']['Conceptos'][$key]['Impuestos']['Traslados'][0]['Impuesto'] = '002';
        $datos['Comprobante']['Conceptos'][$key]['Impuestos']['Traslados'][0]['TipoFactor'] = 'Tasa';
        $datos['Comprobante']['Conceptos'][$key]['Impuestos']['Traslados'][0]['TasaOCuota'] = '0.160000';
        $datos['Comprobante']['Conceptos'][$key]['Impuestos']['Traslados'][0]['Importe'] = (string)$importeimpuesto;
        
    }

    //Total impuesto retenidos y trasladado
    //$datos['Comprobante']['Impuestos']['TotalImpuestosRetenidos'] = 0.00;
    $datos['Comprobante']['Impuestos']['TotalImpuestosTrasladados'] = $datosdefactura['impuestos'];

    // Se agregan los Impuestos
    //$datos['Comprobante']['Impuestos']['Traslados'][0]['Base'] = $datosdefactura[0]['totalfactura']; CORREGIR
    $datos['Comprobante']['Impuestos']['Traslados'][0]['Base'] = (string)$sumaimporte;
    $datos['Comprobante']['Impuestos']['Traslados'][0]['Impuesto'] = '002';
    $datos['Comprobante']['Impuestos']['Traslados'][0]['TipoFactor'] = 'Tasa';
    $datos['Comprobante']['Impuestos']['Traslados'][0]['TasaOCuota'] = '0.160000';
    $datos['Comprobante']['Impuestos']['Traslados'][0]['Importe'] = $datosdefactura['impuestos'];

    $invoice_json = json_encode($datos, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    $bytes = file_put_contents("../archivos/filesinvoices/file-".$numerofactura.".json", $invoice_json); 

    return intval($bytes);

    }

/*========================================================= */
// SE ENVIA ARCHIVO JSON PARA SELLAR Y TIMBRAR AL WS
/*========================================================= */
    static public function EnviarJsonFacturaWS($tabla, $tablatimbrada, $campo, $valor, $folio,  $dataidempresa, $dataserie, $datarfcemisor){
        $filename=dirname( __DIR__ ).'/archivos/filesinvoices/file-'.$dataserie.$folio.'.json';
        if (!file_exists($filename) || !is_readable($filename)) {
            //echo ("File $filename does not exists or is not readable");
            $resp=array('code'=>401,'message'=>"File:".$filename." does not exists or is not readable");
            return $resp;
            exit;
        }

        define("DEBUG", TRUE);
    
        if(DEBUG)
        {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        }

        header('Content-Type: application/json');
    
        # OBJETO DEL API DE CONEXION
        $url = 'https://app.facturaloplus.com/ws/servicio.do?wsdl';    //endpoint productivo
        //$url = 'https://dev.facturaloplus.com/ws/servicio.do?wsdl';

        $objConexion = new ConexionWS($url);
        //$folio=$valor;
        $ultusuario=$_SESSION['id'];

        # CREDENCIAL
        $apikey = 'd2d1f88d95db4eb6b7a8c7105b1eb264';   //api key productivo
        //$apikey = '28bcba372e324116ac4332175ef8d441'; //api key dev
        //$apikey = '4518afef427f487ba7b8942a29c8ea90';

        //OBTENER EL DIRECTORIO PRINCIPAL
        $dirpadre = dirname(__DIR__);
        $exist=file_exists($filename);

        if(!$exist){
            $resp=array('code'=>401,'message'=>"File JSON:".$filename." does not exists");
            return $resp;
            exit;
        };

        //if(!file_exists($dirpadre.'\config\Certificados\Pruebas\CSD_EKU9003173C9.key.pem')){
        if(!file_exists($dirpadre.'/config/Certificados/CSD_MATRIZ_DIGB980626MX3_20220913_165347.key.pem')){
            $resp=array('code'=>401,'message'=>"File KEY does not exists");
            return $resp;
            exit;
        };

        //if(!file_exists($dirpadre.'\config\Certificados\Pruebas\CSD_EKU9003173C9.cer.pem')){
        if(!file_exists($dirpadre.'/config/Certificados/00001000000515088380.cer.pem')){
            $resp=array('code'=>401,'message'=>"File CER does not exists");
            return $resp;
            exit;
        };


        if(!file_exists($dirpadre.'/config/logotipo.png')){
            $resp=array('code'=>401,'message'=>"Logotipo does not exists");
            return $resp;
            exit;
        };


        // $jsonB64    = base64_encode(file_get_contents($dirpadre.'\archivos\filesinvoices\file.json') );
        // $keyPEM     = file_get_contents($dirpadre.'\config\Certificados\Pruebas\CSD_EKU9003173C9.key.pem');
        // $cerPEM     = file_get_contents($dirpadre.'\config\Certificados\Pruebas\CSD_EKU9003173C9.cer.pem');
        // $logoB64    = base64_encode( file_get_contents($dirpadre.'\config/logotipo.png') );

        $jsonB64    = base64_encode(file_get_contents($filename) );
        $keyPEM     = file_get_contents($dirpadre.'/config/Certificados/CSD_MATRIZ_DIGB980626MX3_20220913_165347.key.pem');
        $cerPEM     = file_get_contents($dirpadre.'/config/Certificados/00001000000515088380.cer.pem');
        $logoB64    = base64_encode( file_get_contents($dirpadre.'/config/logotipo.png') );

        $response   = $objConexion->operacion_timbrarJSON3($apikey, $jsonB64, $keyPEM, $cerPEM, $logoB64);

        $res=json_decode($response,true);


        # RESPUESTA DEL SERVICIO
        //echo 'code: '.$res['codigo'].'message: '.$res['mensaje'];
        $resp=array('code'=>$res['codigo'],'message'=>$res['mensaje']);
        //return $resp;

        /*
            200 - Solicitud procesada con éxito.
            307 - El CFDI contiene un timbre previo.
            701 - Creditos insuficientes
        */

        //Cuando sea codigo "200" o "307" se guardaran los archivos XML y PDF
        if ($res['codigo'] == '200' || $res['codigo'] == '307') {
            // Se crea el objeto de la respuesta del Servicio.
            $dataOBJ = json_decode($res['datos'], false);

            //Creamos los archivos con la extencion .xml y .pdf de la respuesta obtenida del parametro "data"
            $bytes=file_put_contents('./salida/'.$datarfcemisor."-".$dataserie.$folio.'.xml', $dataOBJ->XML);

            file_put_contents('./salida/archivo_xml.xml', $dataOBJ->XML);   //de esta forma extraemos la información del atributo XML o alguno de los atributos UUID, FechaTimbrado, NoCertificado, NoCertificadoSAT, CadenaOriginal, CadenaOriginalSAT, Sello, SelloSAT y CodigoQR.
            //file_put_contents('./salida/archivo_codigoqr.xml', $dataOBJ->CodigoQR);
            file_put_contents('./salida/archivo_cadenaoriginal.xml', $dataOBJ->CadenaOriginal);
            file_put_contents('./salida/archivo_cadenaoriginalSAT.xml', $dataOBJ->CadenaOriginalSAT);
            //file_put_contents('./salida/archivo_recibido.xml', $res['datos']);
            //file_put_contents('./salida/archivo.xml', $dataOBJ);
        }else{
            return $resp;
        }

        if($bytes===false){
            //echo "Error al escribir archivo XML.".PHP_EOL;
            $resp = array('411' => 'Error al escribir archivo XML.');	
            return $resp;
       }

        //Falta validad si existe archivo, para borrarlo.
        $file = 'datos'.$folio.'.txt';

        $uuid=              $dataOBJ->UUID;
        $noCertificado=     $dataOBJ->NoCertificado;
        $noCertificadoSAT=  $dataOBJ->NoCertificadoSAT;
        $CodigoQR=          $dataOBJ->CodigoQR;
        $CadenaOriginal=    $dataOBJ->CadenaOriginal;
        $CadenaOriginalSAT= $dataOBJ->CadenaOriginalSAT;
        $SelloSAT=          $dataOBJ->SelloSAT;
        $Sello=             $dataOBJ->Sello;
        $FechaTimbrado=     $dataOBJ->FechaTimbrado;

        try {    
            $query = "INSERT INTO $tablatimbrada (id_empresa, serie, folio, fechahoratimbre, numcertificado, numcertificadosat, sellodigitalcfdi, sellodigitalsat, cadenaoriginal, cadenaoriginalsat, codigoqr, ultusuario) VALUES (:id_empresa, :serie, :folio, :fechahoratimbre, :numcertificado, :numcertificadosat, :sellodigitalcfdi, :sellodigitalsat, :cadenaoriginal, :cadenaoriginalsat, :codigoqr, :ultusuario)";

            $stmt = Conexion::conectar()->prepare($query);

            $stmt->bindParam(":id_empresa", 	    $dataidempresa, PDO::PARAM_INT);
            $stmt->bindParam(":serie", 	            $dataserie, PDO::PARAM_STR);
            $stmt->bindParam(":folio", 	            $folio, PDO::PARAM_INT);
            $stmt->bindParam(":fechahoratimbre",    $FechaTimbrado, PDO::PARAM_STR);
            $stmt->bindParam(":numcertificado",     $noCertificado, PDO::PARAM_STR);
            $stmt->bindParam(":numcertificadosat",  $noCertificadoSAT, PDO::PARAM_STR);
            $stmt->bindParam(":sellodigitalcfdi",   $Sello, PDO::PARAM_STR);
            $stmt->bindParam(":sellodigitalsat",    $SelloSAT, PDO::PARAM_STR);
            $stmt->bindParam(":cadenaoriginal",     $CadenaOriginal, PDO::PARAM_STR);
            $stmt->bindParam(":cadenaoriginalsat",  $CadenaOriginalSAT, PDO::PARAM_STR);
            $stmt->bindParam(":codigoqr",           $CodigoQR, PDO::PARAM_STR);
            $stmt->bindParam(":ultusuario",         $ultusuario, PDO::PARAM_INT);
            
            $stmt -> execute();
            if($stmt){
                $sql="UPDATE $tabla SET uuid=:uuid, fechatimbrado=:fechatimbrado WHERE $campo=:$campo";
                $stmt1 = Conexion::conectar()->prepare($sql);

                $stmt1 -> bindParam(":uuid",            $uuid,  PDO::PARAM_STR);
                $stmt1 -> bindParam(":fechatimbrado",   $FechaTimbrado,  PDO::PARAM_STR);
                $stmt1 -> bindParam(":".$campo,         $valor, PDO::PARAM_INT);

                $stmt1 -> execute();
            
            }

            if(!$stmt1){
                $resp = array('403' => 'Hubo un error al guardar');	
                return $resp;
            }

            //array_push($resp, array('bytes'=>$bytes));

            $uuid .= PHP_EOL . PHP_EOL;
            file_put_contents('./salida/'.$file, $uuid, FILE_APPEND | LOCK_EX);
            $noCertificado .= PHP_EOL . PHP_EOL;
            file_put_contents('./salida/'.$file, $noCertificado, FILE_APPEND | LOCK_EX);
            $noCertificadoSAT .= PHP_EOL . PHP_EOL;
            file_put_contents('./salida/'.$file, $noCertificadoSAT, FILE_APPEND | LOCK_EX);
            $CodigoQR .= PHP_EOL . PHP_EOL;
            file_put_contents('./salida/'.$file, $CodigoQR, FILE_APPEND | LOCK_EX);
            $CadenaOriginal .= PHP_EOL . PHP_EOL;
            file_put_contents('./salida/'.$file, $CadenaOriginal, FILE_APPEND | LOCK_EX);
            $SelloSAT .= PHP_EOL . PHP_EOL;
            file_put_contents('./salida/'.$file, $SelloSAT, FILE_APPEND | LOCK_EX);
            $Sello .= PHP_EOL . PHP_EOL;
            file_put_contents('./salida/'.$file, $Sello, FILE_APPEND | LOCK_EX);
            $FechaTimbrado .= PHP_EOL . PHP_EOL;
            file_put_contents('./salida/'.$file, $FechaTimbrado, FILE_APPEND | LOCK_EX);
            
            return $resp;

        } catch (Exception $e) {
            $resp=array('403' => "Failed: " . $e->getMessage());	
        }
    }

/*========================================================= */
//  FUNCION PARA CANCELAR FACTURA
/*========================================================= */
static public function CancelarFacturaWS($tabla, $campo, $valor, $rfcEmisor, $rfcReceptor, $uuid, $total){

    header('Content-Type: application/json');

    # OBJETO DEL API DE CONEXION
    $url = 'https://app.facturaloplus.com/ws/servicio.do?wsdl';    //endpoint productivo
    //$url = 'https://dev.facturaloplus.com/ws/servicio.do?wsdl';     //endpoint de pruebas

    $objConexion = new ConexionWS($url);
    //$folio=$valor;
    $ultusuario=$_SESSION['id'];

    //OBTENER EL DIRECTORIO PRINCIPAL
    $dirpadre = dirname(__DIR__);

    if(!file_exists($dirpadre.'/config/Certificados/CSD_MATRIZ_DIGB980626MX3_20220913_165347.key')){
        $resp=array('code'=>401,'message'=>"File KEY does not exists");
        return $resp;
        exit;
    };

    if(!file_exists($dirpadre.'/config/Certificados/00001000000515088380.cer')){
        $resp=array('code'=>401,'message'=>"File CER does not exists");
        return $resp;
        exit;
    };

/********************************************************************** */
    # CREDENCIALES Y DATOS PARA CANCELACION DE FACTURA
/********************************************************************** */    
    $apikey = 'd2d1f88d95db4eb6b7a8c7105b1eb264';   //api key productivo
    $motivo = '02';
    $folioSustitucion='';
    $total=floatval($total);
    $keyCSD = base64_encode(file_get_contents($dirpadre.'/config/Certificados/CSD_MATRIZ_DIGB980626MX3_20220913_165347.key'));
    $cerCSD = base64_encode(file_get_contents($dirpadre.'/config/Certificados/00001000000515088380.cer'));
    $passCSD = 'B26D06GV';
    $response= $objConexion->operacion_cancelar2($apikey, $keyCSD, $cerCSD, $passCSD, $uuid, $rfcEmisor, $rfcReceptor, $total, $motivo, $folioSustitucion);
    $res=json_decode($response,true);

/*********************************************************************** */
//DE PRUEBA
/*********************************************************************** */
// $apikey = '28bcba372e324116ac4332175ef8d441'; //api key dev - pruebas
// $uuid = '4a5dc24d-e0a9-4172-9fdd-38b2dfbd4435';
// $rfcEmisor = 'EKU9003173C9';
// $rfcReceptor = 'XAXX010101000';
// $total = 1.16;
// $keyCSD = base64_encode(file_get_contents($dirpadre.'/config/Certificados/Pruebas/CSD_EKU9003173C9.key') );
// $cerCSD = base64_encode(file_get_contents($dirpadre.'/config/Certificados/pruebas/CSD_EKU9003173C9.cer') );
// $passCSD = '12345678a';
// $motivo = '02';
// $folioSustitucion = '';
// $response=$objConexion->operacion_cancelar2($apikey, $keyCSD, $cerCSD, $passCSD, $uuid, $rfcEmisor, $rfcReceptor, $total, $motivo, $folioSustitucion);
// $res=json_decode($response,true);
/*********************************************************************** */

    # RESPUESTA DEL SERVICIO
    //echo 'code: '.$res['codigo'].'message: '.$res['mensaje'];
    $resp=array('code'=>$res['codigo'],'message'=>$res['mensaje']);

    /*
        201 - Solicitud procesada con éxito.
        307 - El CFDI contiene un timbre previo.
        701 - Creditos insuficientes
    */

    $coderesp = array(
        'Solicitud de cancelación exitosa' => 201,
        'Solicitud de cancelación previamente enviada' => 202,
        'UUID No corresponde el RFC del emisor y de quien solicita la cancelación.' => 203,
        'No Existe'=> 205
    );              //echo array_search(201,$coderesp, true);

    //Cuando sea codigo "200" o "307" se guardaran los archivos XML y PDF
    if ($res['codigo'] == '200' || $res['codigo'] == '201') {

    	## GUARDAR ACUSE EN DIRECTORIO ACTUAL ##
		file_put_contents('./cancelado/acuse_cancelacion.xml', $res['acuse']);

        // Se crea el objeto de la respuesta del Servicio.
        $dataOBJ = json_decode($res['datos'], false);

        //Creamos los archivos con la extencion .xml y .pdf de la respuesta obtenida del parametro "data"
        $bytes=file_put_contents('./cancelado/'.$rfcEmisor."-"."-CANCELADO".'.xml', $dataOBJ->XML);

        file_put_contents('./cancelado/archivo_recibido.xml', $res['resultado']);
        file_put_contents('./cancelado/archivo.xml', $dataOBJ);

    }else{
        $existcode=in_array($res['codigo'], $coderesp, true);
        if($existcode){
            //return(array_search(201,$coderesp, true));
            return $resp;
        }
        return $resp;
    }

    if($bytes===false){
        //echo "Error al escribir archivo XML.".PHP_EOL;
        $resp = array('411' => 'Error al escribir archivo XML.');	
        return $resp;
   }

    //Falta validad si existe archivo, para borrarlo.
    $file = 'datoscancelacion.txt';

    $uuid= $dataOBJ->UUID;

    try {    
            $fechacancelado=date("Y-m-d H:i:s");
            $sql="UPDATE $tabla SET fechacancelado=:fechacancelado WHERE $campo=:$campo";
            $stmt1 = Conexion::conectar()->prepare($sql);

            $stmt1 -> bindParam(":fechatimbrado",   $fechacancelado,  PDO::PARAM_STR);
            $stmt1 -> bindParam(":".$campo,         $valor, PDO::PARAM_INT);

            $stmt1 -> execute();
        

        if(!$stmt1){
            $resp = array('403' => 'Hubo un error al guardar');	
            return $resp;
        }

        //array_push($resp, array('bytes'=>$bytes));

        $uuid .= PHP_EOL . PHP_EOL;
        file_put_contents('./cancelado/'.$file, $uuid, FILE_APPEND | LOCK_EX);
        
        return $resp;

    } catch (Exception $e) {
        $resp=array('403' => "Failed: " . $e->getMessage());	
    }
}
    
}  //FIN DE LA CLASE
/*========================================================= */
?>