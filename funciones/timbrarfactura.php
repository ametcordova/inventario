<?php
//namespace TIMBRADORXPRESS\API;
// if(!strlen(session_id())>1){
//     session_start();
// }
// Se especifica la zona horaria
date_default_timezone_set("America/Mexico_City");
// Varible con la fecha actual
$fechaHoy=date("Y-m-d");

// Se desactivan los mensajes de debug
//error_reporting(~(E_WARNING|E_NOTICE));
error_reporting(E_ALL);
/*********************************************/
require_once dirname( __DIR__ ).'/modelos/conexion.php';

$exist=file_exists(__DIR__ . '/class.conexion.php');

        if($exist){
            // echo 'si existe';
            require_once __DIR__ . '/class.conexion.php';
        }else{
            exit;
            //echo 'No Existe archivo class.conexion';
        };

use TIMBRADORXPRESS\API\ConexionWS;
/*********************************************/
class ClaseFacturar{

    static public function GenerarJsonFactura($tabla, $campo, $valor){
    $bytes=0;
    //TRAEMOS LA INFORMACIÓN 
    // $tabla="facturaingreso";
    // $campo = "id";
    // $valor = $_GET["id"];       //12
    $salto="</br>";

    $sql="SELECT tb1.*, emp.razonsocial AS nombreemisor, emp.numerocertificado, emp.regimenfiscalemisor, clie.rfc AS rfcreceptor, clie.nombre AS nombrereceptor, clie.codpostal AS cpreceptor, clie.regimenfiscal AS regfiscalreceptor, mon.id_moneda, cfdi.id_cfdi
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
    //echo $datosdefactura['conceptos'];
    $conceptos=json_decode($datosdefactura['conceptos'],true);
    $cuantos=count($conceptos);
    // foreach ($conceptos as $key => $value) {
    //         $cadena = "la Clave Prod es: ". $value['ClaveProdServ'] ." Cantidad es: ". $value['Cantidad']." Cve Unidad: ".$value['ClaveUnidad']." Unidad:".$value['Unidad']." Descripcion: ".$value['Descripcion']." Valor Unitario: ".$value['ValorUnitario']." Importe: ".$value['Importe']." Objeto Imp: ".$value['ObjetoImp'];
    //print($cadena);
    //     }

        // Datos de la Factura
    $datos['Comprobante']['Version'] = '4.0';
    $datos['Comprobante']['Serie'] = 'A';
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
    $bytes = file_put_contents("../archivos/filesinvoices/file.json", $invoice_json); 

    return intval($bytes);

    }

/*========================================================= */
// SE ENVIA ARCHIVO JSON PARA SELLAR Y TIMBRAR AL WS
/*========================================================= */
    static public function EnviarJsonFacturaWS($tabla, $tablatimbrada, $campo, $valor, $dataidempresa, $dataserie){
        $filename=dirname( __DIR__ ).'/archivos/filesinvoices/file.json';
        if (!file_exists($filename) || !is_readable($filename)) {
            echo ("File $filename does not exists or is not readable");
        }

        define("DEBUG", TRUE);
    
        if(DEBUG)
        {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        }

        header('Content-Type: application/json');
    
        # OBJETO DEL API DE CONEXION
        $url = 'https://dev.facturaloplus.com/ws/servicio.do?wsdl';
        $objConexion = new ConexionWS($url);
        $folio=$valor;
        $ultusuario=$_SESSION['id'];

        # CREDENCIAL
        $apikey = '28bcba372e324116ac4332175ef8d441';
        //$apikey = '4518afef427f487ba7b8942a29c8ea90';

        //OBTENER EL DIRECTORIO PRINCIPAL
        $dirpadre = dirname(__DIR__);
        $exist=file_exists($dirpadre.'\archivos\filesinvoices\file.json');

        if($exist){
            //echo 'si existe';
        }else{
            echo 'No Existe archivo file.json';
            exit;
        };

        if(!file_exists($dirpadre.'\config\Certificados\Pruebas\CSD_EKU9003173C9.key.pem')){
            echo "sale0";
            exit;
        };

        if(!file_exists($dirpadre.'\config\Certificados\Pruebas\CSD_EKU9003173C9.cer.pem')){
            echo "sale1";
            exit;
        };


        if(!file_exists($dirpadre.'\config/logotipo.png')){
            echo "sale2";
            exit;
        };


        $jsonB64    = base64_encode(file_get_contents($dirpadre.'\archivos\filesinvoices\file.json') );
        $keyPEM     = file_get_contents($dirpadre.'\config\Certificados\Pruebas\CSD_EKU9003173C9.key.pem');
        $cerPEM     = file_get_contents($dirpadre.'\config\Certificados\Pruebas\CSD_EKU9003173C9.cer.pem');
        $logoB64    = base64_encode( file_get_contents($dirpadre.'\config/logotipo.png') );

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
            file_put_contents('./salida/archivo_xml.xml', $dataOBJ->XML);   //de esta forma extraemos la información del atributo XML o alguno de los atributos UUID, FechaTimbrado, NoCertificado, NoCertificadoSAT, CadenaOriginal, CadenaOriginalSAT, Sello, SelloSAT y CodigoQR.
            file_put_contents('./salida/archivo_codigoqr.xml', $dataOBJ->CodigoQR);
            file_put_contents('./salida/archivo_cadenaoriginal.xml', $dataOBJ->CadenaOriginal);
            file_put_contents('./salida/archivo_recibido.xml', $res['datos']);
            file_put_contents('./salida/archivo.xml', $resp);
        }else{
            echo $resp;
        }
        //Falta validad si existe archivo, para borrarlo.
        $file = 'datos'.$folio.'.txt';

        $uuid=              $dataOBJ->UUID;
        $noCertificado=     $dataOBJ->NoCertificado;
        $noCertificadoSAT=  $dataOBJ->NoCertificadoSAT;
        $CodigoQR=          $dataOBJ->CodigoQR;
        $CadenaOriginal=    $dataOBJ->CadenaOriginal;
        $SelloSAT=          $dataOBJ->SelloSAT;
        $Sello=             $dataOBJ->Sello;
        $FechaTimbrado=     $dataOBJ->FechaTimbrado;

        $archivoxml='filexml'.$folio.'.xml';

        $bytes = file_put_contents('./salida/'.$archivoxml, $response, LOCK_EX);

        if($bytes===false){
             echo "Error al escribir archivo.".PHP_EOL;
        }

        try {    
            $query = "INSERT INTO $tablatimbrada (id_empresa, serie, folio, fechahoratimbre, numcertificado, numcertificadosat, sellodigitalcfdi, sellodigitalsat, cadenaoriginal, codigoqr, ultusuario) VALUES (:id_empresa, :serie, :folio, :fechahoratimbre, :numcertificado, :numcertificadosat, :sellodigitalcfdi, :sellodigitalsat, :cadenaoriginal, :codigoqr, :ultusuario)";

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
                $resp = array('403' => 'Hubo un error');	
                return $resp;
            }
            array_push($resp, array('bytes'=>$bytes));

            $uuid .= PHP_EOL . PHP_EOL;
            file_put_contents('./salida/'.$file, $uuid, FILE_APPEND | LOCK_EX);
            $nocertificado .= PHP_EOL . PHP_EOL;
            file_put_contents('./salida/'.$file, $nocertificado, FILE_APPEND | LOCK_EX);
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
            echo "Failed: " . $e->getMessage();
        }
    }
    
}  //FIN DE LA CLASE
/*========================================================= */
?>