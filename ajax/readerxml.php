<?php
libxml_use_internal_errors(true);
$mensajes = array(
    JSON_ERROR_NONE           => 'No ha habido ningún error',
    JSON_ERROR_DEPTH          => 'Se ha alcanzado el máximo nivel de profundidad',
    JSON_ERROR_STATE_MISMATCH => 'JSON inválido o mal formado',
    JSON_ERROR_CTRL_CHAR      => 'Error de control de caracteres, posiblemente incorrectamente codificado',
    JSON_ERROR_SYNTAX         => 'Error de sintaxis',
    JSON_ERROR_UTF8           => 'Caracteres UTF-8 mal formados, posiblemente incorrectamente codificado'
);

//leerxml();

function leerxml($tablatimbrada, $campo, $valor, $dataidempresa){
$xml="";
    try{
      $sellado=file_get_contents('salida/archivo_recibido.xml');
      $xml = simplexml_load_string($sellado);
      if ($xml===null || !is_object($xml))
          die('Failed to load xml file.');
              
    }catch(Exception $e){
        $arrResp = array(false,array("descripcionError"=>"Problema al leer el archivo. El archivo XML es inválido.","errorPAC"=>false));
        print_r($arrResp);
    }


$ns = $xml -> getNamespaces(true);

//CHECAR QUE HACE
$xml->registerXPathNamespace('c', $ns['cfdi']);
$xml->registerXPathNamespace('t', $ns['tfd']);


$versionXmlXpath = $xml->xpath('//cfdi:Comprobante'); 
$versionCFDI = $versionXmlXpath[0]['Version'];

//CFDI Comprobante
foreach ($xml->xpath('//c:Comprobante') as $cfdiComprobante){ 
    $Version=$cfdiComprobante['Version']; 
    $Serie=$cfdiComprobante['Serie']; 
    $Folio=$cfdiComprobante['Folio']; 
    $Fecha=$cfdiComprobante['Fecha']; 
    $FormadePago=$cfdiComprobante['FormaPago']; 
    $NoCertificado=$cfdiComprobante['NoCertificado']; 
    $Certificado=$cfdiComprobante['Certificado']; 
    $CondicionesdePago=$cfdiComprobante['CondicionesDePago']; 
    $Subtotal=$cfdiComprobante['SubTotal']; 
    $Moneda=$cfdiComprobante['Moneda']; 
    $TipodeCambio=$cfdiComprobante['TipoCambio']; 
    $Total=$cfdiComprobante['Total']; 
    $TipodeComprobante=$cfdiComprobante['TipoDeComprobante']; 
    $Exportacion=$cfdiComprobante['Exportacion']; 
    $MetododePago=$cfdiComprobante['MetodoPago']; 
    $LugardeExpedicion=$cfdiComprobante['LugarExpedicion']; 
    $Sello=$cfdiComprobante['Sello']; 
}  //fin del foreach

//CFDI EMISOR
foreach ($xml->xpath('//cfdi:Emisor') as $cfdiEmisor){ 
   $rfcEmisor=$cfdiEmisor['Rfc']; 
   $nombreEmisor=$cfdiEmisor['Nombre']; 
   $regEmisor=$cfdiEmisor['RegimenFiscal']; 

}

//CFDI RECEPTOR
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $cfdiReceptor){ 
   $rfcReceptor=$cfdiReceptor['Rfc'];
   $nombreReceptor=$cfdiReceptor['Nombre']; 
   $domReceptor=$cfdiReceptor['DomicilioFiscalReceptor']; 
   $regFiscalReceptor=$cfdiReceptor['RegimenFiscalReceptor']; 
   $usoCFDI=$cfdiReceptor['UsoCFDI']; 
}

//TIMBRE FISCAL
foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
   $version =$tfd['Version']; 
   $uuid =$tfd['UUID']; 
   $fechaTimbrado =$tfd['FechaTimbrado']; 
   $rfcProvCertif =$tfd['RfcProvCertif']; 
   $selloCFD=$tfd['SelloCFD']; 
   $noCertificadoSAT =$tfd['NoCertificadoSAT']; 
   $selloSAT=$tfd['SelloSAT']; 
   $CodigoQR=$tfd['CodigoQR']; 
 } 

 echo $uuid.' No: '.$dataidempresa.' '.$selloSAT.' '.$fechaTimbrado;
 //echo $uuid.' No: '.$dataidempresa;

//  $query = "INSERT INTO $tablatimbrada (id_empresa, serie, folio, fechahoratimbre, numcertificado, numcertificadosat, sellodigitalcfdi, sellodigitalsat, cadenaoriginal, codigoqr, ultusuario) VALUES (:id_empresa, :serie, :folio, :fechahoratimbre, :numcertificado, :numcertificadosat, :sellodigitalcfdi, :sellodigitalsat, :cadenaoriginal, :codigoqr, :ultusuario)";

//  $stmt = Conexion::conectar()->prepare($query);

//$stmt->bindParam(":id_empresa", 	        $facturaingreso["id_empresa"], PDO::PARAM_INT);
//  $stmt->bindParam(":serie", 	            $Serie, PDO::PARAM_STR);
//  $stmt->bindParam(":folio", 	            $Folio, PDO::PARAM_INT);
//  $stmt->bindParam(":fechahoratimbre",       $fechaTimbrado, PDO::PARAM_STR);
//  $stmt->bindParam(":numcertificado",        $NoCertificado, PDO::PARAM_STR);
//  $stmt->bindParam(":numcertificadosat",     $noCertificadoSAT, PDO::PARAM_STR);
//  $stmt->bindParam(":sellodigitalcfdi",      $selloCFD, PDO::PARAM_STR);
//  $stmt->bindParam(":sellodigitalsat",       $selloSAT, PDO::PARAM_INT);
 //$stmt->bindParam(":cadenaoriginal",         , PDO::PARAM_STR);
 //$stmt->bindParam(":codigoqr",               , PDO::PARAM_INT);
 //$stmt->bindParam(":ultusuario",             $ultusuario, PDO::PARAM_INT);
 
 //$stmt -> bindParam(":".$campo, $valor, PDO::PARAM_INT);

 //$stmt -> execute();

}

?>

