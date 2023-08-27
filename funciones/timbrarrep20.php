<?php
// Se especifica la zona horaria
date_default_timezone_set("America/Mexico_City");

// Se desactivan los mensajes de debug
//error_reporting(~(E_WARNING|E_NOTICE));
error_reporting(E_ALL);
/*********************************************/
require_once dirname( __DIR__ ).'/modelos/conexion.php';

//CLASE PARA LA CONEXION AL WS
$exist=file_exists(__DIR__ . '/class.conexion.php');

    if($exist){
        require_once __DIR__ . '/class.conexion.php';
    }else{
        $resp=array('code'=>401,'message'=>"File CONNECTION does not exists or is not readable");
        return $resp;
        exit;
     };

use TIMBRADORXPRESS\API\ConexionWS;
/*********************************************/
class ClaseTimbrarRep20{

/*========================================================= */
// SE ENVIA ARCHIVO JSON PARA SELLAR Y TIMBRAR AL WS
/*========================================================= */
    static public function EnviarJsonRep20WS($tabla, $campo, $valor, $datafolio, $datarfcemisor){
        $filename=dirname( __DIR__ ).'/archivos/filesinvoices/'.$datarfcemisor.'-REP-'.$datafolio.'.json';
        if (!file_exists($filename) || !is_readable($filename)) {
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
        //$url = 'https://dev.facturaloplus.com/ws/servicio.do?wsdl';  //endpoint DEV

        $objConexion = new ConexionWS($url);

        $ultusuario=$_SESSION['id'];

        # CREDENCIAL
        $apikey = 'd2d1f88d95db4eb6b7a8c7105b1eb264';   //api key productivo
        //$apikey = '28bcba372e324116ac4332175ef8d441'; //api key dev

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

        //ARCHIVOS DE PRUEBA
        // $jsonB64    = base64_encode(file_get_contents($dirpadre.'\archivos\filesinvoices\file.json') );
        // $keyPEM     = file_get_contents($dirpadre.'\config\Certificados\Pruebas\CSD_EKU9003173C9.key.pem');
        // $cerPEM     = file_get_contents($dirpadre.'\config\Certificados\Pruebas\CSD_EKU9003173C9.cer.pem');
        // $logoB64    = base64_encode( file_get_contents($dirpadre.'\config/logotipo.png') );

        //ARCHIVOS EN PRODUCCION
        $jsonB64    = base64_encode(file_get_contents($filename) );
        $keyPEM     = file_get_contents($dirpadre.'/config/Certificados/CSD_MATRIZ_DIGB980626MX3_20220913_165347.key.pem');
        $cerPEM     = file_get_contents($dirpadre.'/config/Certificados/00001000000515088380.cer.pem');
        $logoB64    = base64_encode( file_get_contents($dirpadre.'/config/logotipo.png') );

        //ENVIAR JSON PARA TIMBRAR Y REGRESAR XML
        $response   = $objConexion->operacion_timbrarJSON3($apikey, $jsonB64, $keyPEM, $cerPEM, $logoB64);

        # DECODIFICAR RESPUESTA DEL WEBSERVICES
        $res=json_decode($response,true);

        # CODIGOS DE RESPUESTA DEL SERVICIO
        $resp=array('code'=>$res['codigo'],'message'=>$res['mensaje']);
        /*
            200 - Solicitud procesada con éxito.
            307 - El CFDI contiene un timbre previo.
            701 - Creditos insuficientes
        */

        //Cuando sea codigo "200" o "307" se guardaran los archivos XML y PDF
        if ($res['codigo'] == '200' || $res['codigo'] == '307') {
            // Se crea el objeto de la respuesta del Servicio.
            $dataOBJ = json_decode($res['datos'], false);

            //Creamos los archivos con la extension .xml y .pdf de la respuesta obtenida del parametro "data"
            $bytes=file_put_contents('./rep20/'.$datarfcemisor."-".$datafolio.'.xml', $dataOBJ->XML);

            file_put_contents('./rep20/archivo_xml.xml', $dataOBJ->XML);   //de esta forma extraemos la info del atributo XML o alguno de los atributos UUID, FechaTimbrado, NoCertificado, NoCertificadoSAT, CadenaOriginal, CadenaOriginalSAT, Sello, SelloSAT y CodigoQR.
            file_put_contents('./rep20/archivo_codigoqr.xml', $dataOBJ->CodigoQR);
            file_put_contents('./rep20/archivo_cadenaoriginal.xml', $dataOBJ->CadenaOriginal);
            file_put_contents('./rep20/archivo_cadenaoriginalSAT.xml', $dataOBJ->CadenaOriginalSAT);
            file_put_contents('./rep20/archivo_recibido.xml', $res['datos']);
        }else{
            return $res['codigo'];      //CRP20268 si es este código, es posible error de redondeo
        }

        if($bytes===false){
            //echo "Error al escribir archivo XML.".PHP_EOL;
            $resp = array('411' => 'Error al escribir archivo XML.');	
            return $resp['data'];
       }

        //Falta validad si existe archivo, para borrarlo.
        $file = 'datos'.$datafolio.'.txt';

        //DATOS DEL WS QUE SE GUARDARAN EN LA BASE DE DATOS
        $foliofiscal=       $dataOBJ->UUID;
        $noCertificado=     $dataOBJ->NoCertificado;
        $noCertificadoSAT=  $dataOBJ->NoCertificadoSAT;
        $CodigoQR=          $dataOBJ->CodigoQR;
        $CadenaOriginal=    $dataOBJ->CadenaOriginal;
        $CadenaOriginalSAT= $dataOBJ->CadenaOriginalSAT;
        $SelloSAT=          $dataOBJ->SelloSAT;
        $Sello=             $dataOBJ->Sello;
        $FechaTimbrado=     $dataOBJ->FechaTimbrado;

        $foliofiscal .= PHP_EOL . PHP_EOL;
        file_put_contents('./rep20/'.$file, $foliofiscal, FILE_APPEND | LOCK_EX);
        $noCertificado .= PHP_EOL . PHP_EOL;
        file_put_contents('./rep20/'.$file, $noCertificado, FILE_APPEND | LOCK_EX);
        $noCertificadoSAT .= PHP_EOL . PHP_EOL;
        file_put_contents('./rep20/'.$file, $noCertificadoSAT, FILE_APPEND | LOCK_EX);
        $CodigoQR .= PHP_EOL . PHP_EOL;
        file_put_contents('./rep20/'.$file, $CodigoQR, FILE_APPEND | LOCK_EX);
        $CadenaOriginal .= PHP_EOL . PHP_EOL;
        file_put_contents('./rep20/'.$file, $CadenaOriginal, FILE_APPEND | LOCK_EX);
        $SelloSAT .= PHP_EOL . PHP_EOL;
        file_put_contents('./rep20/'.$file, $SelloSAT, FILE_APPEND | LOCK_EX);
        $Sello .= PHP_EOL . PHP_EOL;
        file_put_contents('./rep20/'.$file, $Sello, FILE_APPEND | LOCK_EX);
        $FechaTimbrado .= PHP_EOL . PHP_EOL;
        file_put_contents('./rep20/'.$file, $FechaTimbrado, FILE_APPEND | LOCK_EX);

        try {    
            $sql="UPDATE $tabla SET foliofiscal=:foliofiscal, fechatimbradorep=:fechatimbradorep, numcertificado=:numcertificado, numcertificadosat=:numcertificadosat, sellodigitalcfdi=:sellodigitalcfdi, sellodigitalsat=:sellodigitalsat, cadenaoriginal=:cadenaoriginal, cadenaoriginalsat=:cadenaoriginalsat, codigoqr=:codigoqr, ultusuario=:ultusuario WHERE $campo=:$campo";

            $stmt = Conexion::conectar()->prepare($sql);

                $stmt->bindParam(":foliofiscal",        $foliofiscal,  PDO::PARAM_STR);
                $stmt->bindParam(":fechatimbradorep",   $FechaTimbrado,  PDO::PARAM_STR);
                $stmt->bindParam(":numcertificado",     $noCertificado, PDO::PARAM_STR);
                $stmt->bindParam(":numcertificadosat",  $noCertificadoSAT, PDO::PARAM_STR);
                $stmt->bindParam(":sellodigitalcfdi",   $Sello, PDO::PARAM_STR);
                $stmt->bindParam(":sellodigitalsat",    $SelloSAT, PDO::PARAM_STR);
                $stmt->bindParam(":cadenaoriginal",     $CadenaOriginal, PDO::PARAM_STR);
                $stmt->bindParam(":cadenaoriginalsat",  $CadenaOriginalSAT, PDO::PARAM_STR);
                $stmt->bindParam(":codigoqr",           $CodigoQR, PDO::PARAM_STR);
                $stmt->bindParam(":ultusuario",         $ultusuario, PDO::PARAM_INT);
                $stmt->bindParam(":".$campo,            $valor, PDO::PARAM_INT);

            $stmt -> execute();

            if($stmt){
                //BUSCAR LOS FOLIOS QUE ABARCA EL COMPLEMENTO DE PAGO
                $query="SELECT doctosrelacionados FROM $tabla WHERE $campo=:$campo";
                $stmt1 = Conexion::conectar()->prepare($query);
                $stmt1->bindParam(":".$campo, $valor, PDO::PARAM_INT);
                $stmt1 -> execute();
                $aswer = $stmt1->fetch(PDO::FETCH_ASSOC);
                if($stmt1){
                    
                    //CONVIERTE EL JSON A ARRAY Y SACA SOLO LOS NUMEROS DE LOS FOLIOS DE LAS FACTS.
                    $folios=[];
                    $data = json_decode($aswer['doctosrelacionados'], true);
                    $fechapago=$aswer['fechapago'];
                    foreach ($data as $value) {
                         array_push($folios, $value['Folio']);
                    }

                    if(count($folios)>0){
                        //CONVIERTE EL ARRAY A STRING PARA LA CONSULTA DE ACTUALIZACION
                        $fol=implode(",",$folios);
                        //ACTUALIZA LOS FOLIOS DE LAS FACT EN LA TABLA DE FACTURAINGRESO CON EL NUM DEL R.E.P.
                        $sql="UPDATE facturaingreso SET numcomppago='$datafolio' WHERE folio IN ($fol)";
                        $stmt = Conexion::conectar()->prepare($sql);
                        $stmt -> execute();

                        $serie='A';
                        $status=1;
                        $query = Conexion::conectar()->prepare("UPDATE facturas SET numcomplemento=:numcomplemento, fechapagado=:fechapagado, status=:status WHERE serie=:serie AND numfact IN ($fol)");    //ACTUALIZA EN LA TABLA FACTURAS
                        $query->bindParam(":serie",             $serie, PDO::PARAM_STR);
                        $query->bindParam(":numcomplemento",    $datafolio, PDO::PARAM_STR);
                        $query->bindParam(":fechapagado",       $fechapago, PDO::PARAM_STR);
                        $query->bindParam(":status",            $status, PDO::PARAM_INT);
                        $query->execute();

                    }
                    
                }
            }

            return $resp;

        } catch (Exception $e) {
            $resp=array('403' => "Failed: " . $e->getMessage());	
        }
    }

    
}  //FIN DE LA CLASE
/*========================================================= */
// $data = json_decode($dato, true);
// foreach ($data as $key=>$value) {
//     //echo $key, " : ";
//     echo $value['Folio'], "\n";
// }
