<?php
libxml_use_internal_errors(true);
define("SALTO", "</BR>", true);
$mensajes = array(
    JSON_ERROR_NONE           => 'No ha habido ningún error',
    JSON_ERROR_DEPTH          => 'Se ha alcanzado el máximo nivel de profundidad',
    JSON_ERROR_STATE_MISMATCH => 'JSON inválido o mal formado',
    JSON_ERROR_CTRL_CHAR      => 'Error de control de caracteres, posiblemente incorrectamente codificado',
    JSON_ERROR_SYNTAX         => 'Error de sintaxis',
    JSON_ERROR_UTF8           => 'Caracteres UTF-8 mal formados, posiblemente incorrectamente codificado'
);

echo "<br/>";

//$xml = simplexml_load_file('salida/archivo_xml.xml'); Tambien funciona

//$sellado=file_get_contents('ejemplos/CFDI-40_Factura_A9529.xml');

    $xml="";
    try{
      $sellado=file_get_contents('salida/archivo_xml.xml');
      $xml = simplexml_load_string($sellado);
      if ($xml===null || !is_object($xml))
          die('Failed to load xml file.');
              
    }catch(Exception $e){
        $arrResp = array(false,array("descripcionError"=>"Problema al leer el archivo. El archivo XML es inválido.","errorPAC"=>false));
        print_r($arrResp);
    }


$ns = $xml -> getNamespaces(true);
echo "NAMESPACES";
echo "<br/>";
print_r($ns);
echo "<br/>";
$formato = 'NAMESPACES: %s';
foreach($ns as $item){
    echo sprintf("Espacio de Nombres - NAMESPACES: %s", $item);
    echo "<br/>";
}

echo 'Atributo: '.$xml->attributes();
echo "<br/>";echo "<br/>";

//CHECAR QUE HACE
$xml->registerXPathNamespace('c', $ns['cfdi']);
$xml->registerXPathNamespace('t', $ns['tfd']);


$versionXmlXpath = $xml->xpath('//cfdi:Comprobante'); 
$versionCFDI = $versionXmlXpath[0]['Version'];
echo 'Version::'.$versionCFDI;
echo SALTO;

//CFDI Comprobante
echo "<font color='blue'>".'CFDI COMPROBANTE'."</font>";
echo SALTO;
foreach ($xml->xpath('//c:Comprobante') as $cfdiComprobante){ 
    echo "Versiòn: ".$cfdiComprobante['Version']; 
    echo "<br />"; 
    echo "Serie: ".$cfdiComprobante['Serie']; 
    echo "<br />"; 
    echo "Folio: ".$cfdiComprobante['Folio']; 
    echo "<br />"; 
    echo "Fecha: ".$cfdiComprobante['Fecha']; 
    echo "<br />"; 
    echo "Forma de Pago: ".$cfdiComprobante['FormaPago']; 
    echo SALTO; 
    echo "NoCertificado: ".$cfdiComprobante['NoCertificado']; 
    echo SALTO; 
    echo "Certificado: ".$cfdiComprobante['Certificado']; 
    echo SALTO; 
    echo "Condiciones de Pago: ".$cfdiComprobante['CondicionesDePago']; 
    echo SALTO; 
    echo "Subtotal: ".$cfdiComprobante['SubTotal']; 
    echo SALTO; 
    echo "Moneda: ".$cfdiComprobante['Moneda']; 
    echo SALTO; 
    echo "Tipo de Cambio: ".$cfdiComprobante['TipoCambio']; 
    echo SALTO; 
    echo "Total: ".$cfdiComprobante['Total']; 
    echo SALTO; 
    echo "Tipo de Comprobante: ".$cfdiComprobante['TipoDeComprobante']; 
    echo SALTO; 
    echo "Exportación: ".$cfdiComprobante['Exportacion']; 
    echo SALTO; 
    echo "Método de Pago: ".$cfdiComprobante['MetodoPago']; 
    echo SALTO; 
    echo "Lugar de Expedición: ".$cfdiComprobante['LugarExpedicion']; 
    echo SALTO; 
    echo "Sello ".$cfdiComprobante['Sello']; 
}  //fin del foreach

echo SALTO;
echo SALTO;
echo "<font color='blue'>"."CFDI RELACIONADOS"."</font>";
echo SALTO;
//CFDI RELACIONADOS
foreach ($xml->xpath('//cfdi:CfdiRelacionados') as $cfdiRelacionados){ 
   echo $cfdiRelacionados['TipoRelacion']; 
   echo SALTO; 
}

echo SALTO;
//CFDI RELACIONADO
echo "<font color='blue'>"."CFDI RELACIONADO"."</font>";
foreach ($xml->xpath('//cfdi:CfdiRelacionado') as $cfdiComprobante){ 
   echo SALTO; 
   echo $cfdiComprobante['UUID']; 
   echo SALTO; 
}
echo SALTO; 
echo SALTO;
//CFDI EMISOR
echo "<font color='blue'>"."CFDI EMISOR"."</font>";
foreach ($xml->xpath('//cfdi:Emisor') as $cfdiEmisor){ 
   echo SALTO; 
   echo $cfdiEmisor['Rfc']; 
   echo SALTO; 
   echo $cfdiEmisor['Nombre']; 
   echo SALTO; 
   echo $cfdiEmisor['RegimenFiscal']; 
   echo SALTO; 
}

echo SALTO;
//CFDI RECEPTOR
echo "<font color='blue'>"."CFDI RECEPTOR"."</font>";
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $cfdiReceptor){ 
   echo SALTO; 
   echo "<font color='red'>".'RFC RECEPTOR: '."</font>".$cfdiReceptor['Rfc'];
   echo SALTO; 
   echo "<font color='red'>".'NOMBRE RECEPTOR: '."</font>".$cfdiReceptor['Nombre']; 
   echo SALTO; 
   echo "<font color='red'>".'DOM FISCAL RECEPTOR: '."</font>".$cfdiReceptor['DomicilioFiscalReceptor']; 
   echo SALTO; 
   echo "<font color='red'>".'REGIMEN FISCAL RECEPTOR: '."</font>".$cfdiReceptor['RegimenFiscalReceptor']; 
   echo SALTO; 
   echo "<font color='red'>".'USO FISCAL DEL CFDI: '."</font>".$cfdiReceptor['UsoCFDI']; 
   echo SALTO; 
}

echo SALTO;
//CFDI CONCEPTOS
echo "<font color='blue'>"."CONCEPTOS Y/O PRODUCTOS"."</font>";
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $cfdiConceptos){ 
   echo SALTO; 
   echo "<font color='red'>".'CLAVE PROD SERV: '."</font>".$cfdiConceptos['ClaveProdServ'];
   echo SALTO; 
   echo "<font color='red'>".'No DE IDENTIFICACION: '."</font>".$cfdiConceptos['NoIdentificacion'];
   echo SALTO; 
   echo "<font color='red'>".'CANTIDAD: '."</font>".$cfdiConceptos['Cantidad'];
   echo SALTO; 
   echo "<font color='red'>".'CLAVE UNIDAD: '."</font>".$cfdiConceptos['ClaveUnidad'];
   echo SALTO; 
   echo "<font color='red'>".'UNIDAD: '."</font>".$cfdiConceptos['Unidad'];
   echo SALTO; 
   echo "<font color='red'>".'DESCRIPCION: '."</font>".$cfdiConceptos['Descripcion'];
   echo SALTO; 
   echo "<font color='red'>".'VALOR UNIT.: '."</font>".$cfdiConceptos['ValorUnitario'];
   echo SALTO; 
   echo "<font color='red'>".'IMPORTE UNIT.: '."</font>".$cfdiConceptos['Importe'];
   echo SALTO; 
   echo "<font color='red'>".'OBJETO DE IMP.: '."</font>".$cfdiConceptos['ObjetoImp'];
   echo SALTO; 
}

echo SALTO;
//TRASLADO IMPUESTO
echo "<font color='grey'>".'TRASLADO IMP DE CONCEPTOS'."</font>";
echo SALTO;
foreach ($xml->xpath('//cfdi:Conceptos//c:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){ 
    echo "<font color='red'>".'BASE.: '."</font>".$Traslado['Base']; 
    echo "<br />"; 
    echo "<font color='red'>".'IMPUESTO.: '."</font>".$Traslado['Impuesto']; 
    echo "<br />";   
    echo "<font color='red'>".'TIPO FACTOR.: '."</font>".$Traslado['TipoFactor']; 
    echo "<br />"; 
    echo "<font color='red'>".'TASA O CUOTA.: '."</font>".$Traslado['TasaOCuota']; 
    echo "<br />"; 
    echo "<font color='red'>".'IMPORTE.: '."</font>".$Traslado['Importe']; 
    echo "<br />"; 
    echo "<br />"; 
 } 

 echo SALTO;
 //RETENCION IMPUESTO
 echo "<font color='blue'>".'RETENCION IMP DE CONCEPTOS'."</font>";
 echo SALTO;
 foreach ($xml->xpath('//cfdi:Conceptos//c:Impuestos//cfdi:Retenciones//cfdi:Retencion') as $Retencion){ 
     echo "<font color='red'>".'BASE.: '."</font>".$Retencion['Base']; 
     echo "<br />"; 
     echo "<font color='red'>".'IMPUESTO.: '."</font>".$Retencion['Impuesto']; 
     echo "<br />";   
     echo "<font color='red'>".'TIPO FACTOR.: '."</font>".$Retencion['TipoFactor']; 
     echo "<br />"; 
     echo "<font color='red'>".'TASA O CUOTA.: '."</font>".$Retencion['TasaOCuota']; 
     echo "<br />"; 
     echo "<font color='red'>".'IMPORTE.: '."</font>".$Retencion['Importe']; 
     echo "<br />"; 
  } 

  echo SALTO;
  //IMPUESTOS
  echo "<font color='blue'>".'IMPUESTOS'."</font>";
  echo SALTO;
  foreach ($xml->xpath('//cfdi:Comprobante//c:Impuestos') as $Impuestos){ 
      echo "<font color='red'>".'TOTAL IMP RETENIDOS.: '."</font>".$Impuestos['TotalImpuestosRetenidos']; 
      echo "<br />"; 
      echo "<font color='red'>".'TOTAL IMP TRASLADADOS.: '."</font>".$Impuestos['TotalImpuestosTrasladados']; 
      echo "<br />"; 
   } 
 
   echo SALTO;
   //IMPUESTOS
   echo "<font color='blue'>".'RETENCIONES IMPUESTOS'."</font>";
   echo SALTO;
   foreach ($xml->xpath('//c:Comprobante//c:Impuestos//c:Retenciones//c:Retencion') as $ImpuestoRetencion){ 
       echo "<font color='red'>".'TOTAL IMP.: '."</font>".$ImpuestoRetencion['Impuesto']; 
       echo "<br />"; 
       echo "<font color='red'>".'TOTAL IMP.: '."</font>".$ImpuestoRetencion['Importe']; 
       echo "<br />"; 
    } 
     
   echo SALTO;
   //RETENCION IMPUESTO
   echo "<font color='blue'>".'TRASLADOS IMPUESTOS'."</font>";
   echo SALTO;
   foreach ($xml->xpath('//c:Comprobante//c:Impuestos//c:Traslados//c:Traslado') as $Traslados){ 
      
       echo "<font color='red'>".'BASE TRANS.: '."</font>".$Traslados['Base']; 
       echo "<br />"; 
       echo "<font color='red'>".'IMPUESTO TRANS.: '."</font>".$Traslados['Impuesto']; 
       echo "<br />";   
       echo "<font color='red'>".'TIPO FACTOR TRANS.: '."</font>".$Traslados['TipoFactor']; 
       echo "<br />"; 
       echo "<font color='red'>".'TASA O CUOTA TRANS.: '."</font>".$Traslados['TasaOCuota']; 
       echo "<br />"; 
       echo "<font color='red'>".'IMPORTE TRANS.: '."</font>".$Traslados['Importe']; 
       echo "<br />"; 
    } 
  

echo SALTO;
//TIMBRE FISCAL
echo "<font color='blue'>".'TIMBRE FISCAL'."</font>";
echo SALTO;
foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
    echo "<font color='red'>".'VERSION.: '."</font>".$tfd['Version']; 
    echo "<br />"; 
    echo "<font color='red'>".'UUID.: '."</font>".$tfd['UUID']; 
    echo "<br />"; 
    echo "<font color='red'>".'FECHA TIMBRADO.: '."</font>".$tfd['FechaTimbrado']; 
    echo "<br />";     
    echo "<font color='red'>".'RFC PROV CERTIF.: '."</font>".$tfd['RfcProvCertif']; 
    echo "<br />"; 
    echo "<font color='red'>".'SELLO CFD.: '."</font>".$tfd['SelloCFD']; 
    echo "<br />"; 
    echo "<font color='red'>".'No CERTIFICADO SAT.: '."</font>".$tfd['NoCertificadoSAT']; 
    echo "<br />"; 
    echo "<font color='red'>".'SELLO SAT.: '."</font>".$tfd['SelloSAT']; 
 } 



//CHECARLO!!! 
//  $xml = simplexml_load_file( 'cfdi.xml', 'SimpleXMLElement', 0, 'cfdi', true );
//  print_r($xml);
//  echo PHP_EOL;
//  echo print_r( $xml->attributes, 1 ).PHP_EOL;

echo "<br/>";




//$response=file_get_contents('salida/archivo_recibido.xml');
// $xml=file_get_contents('ejemplos/CFDI-40_Factura_A9529.xml');

// var_dump($xml);

// $xml = simplexml_load_string($xml);
// echo SALTO; 
// echo SALTO; 
// var_dump($xml);
// echo SALTO; 
// echo SALTO; 
// echo 'fecha:'.($xml['Fecha']);
// echo SALTO; 

//$sellado=file_get_contents('ejemplos/CFDI-40_Factura_A9529.xml');
//var_dump($sellado);
//$xml = simplexml_load_string($sellado);
//var_dump($xml);
//$xxx=json_decode($xml);
// echo "IMPRIMIR RESULTADO DE simplexml_load_string:";
// echo "<br/>";
//print_r($xxx);

// $name = $xml->xpath('//c:Comprobante');
// print_r($name[0]);
// echo "<br/>";echo "<br/>";
// $version=($name[0]['Version']);
// print_r($name[0]['Version']);
// echo "<br/>";echo "<br/>";
// $folio=($name[0]['Folio']);
// $NoCertificado=$name[0]['NoCertificado'];
// $sello=$name[0]['Sello'];

// echo sprintf('%s - %s (%s) - %s', 'Version:'.$version, 'Folio:'.$folio, 'NoCertificado:'.$NoCertificado, 'Sello:'.$sello);
// echo "<br/>";echo "<br/>";
// print_r($name[0]['Folio']);
// echo "<br/>";echo "<br/>";
// $name = $xml->xpath('//c:Emisor');
// echo sprintf('RFC EMISOR: %s ', $name[0]['Rfc']);
// echo "<br/>";echo "<br/>";
// $name = $xml->xpath('//c:Receptor');
// echo sprintf('RFC RECEPTOR: %s ', $name[0]['Rfc']);
// echo "<br/>";echo "<br/>";
?>

