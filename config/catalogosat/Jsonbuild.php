<?php

header('Content-Type: text/plain');
date_default_timezone_set('America/Mexico_City');
$params = array(
    "url"=>"http://services.test.sw.com.mx",
    "user"=>"demo",
    "password"=> "123456789"
);

$emisor["Rfc"]="LAN8507268IA";
$emisor["Nombre"]="NombreRazonSocial";
$emisor["RegimenFiscal"]="601";
$receptor["Rfc"] = "AAA010101AAA";
$receptor["Nombre"] = "NombreRazonSocial";
$receptor["ResidenciaFiscalSpecified"] = false;
$receptor["NumRegIdTrib"] = null;
$receptor["UsoCFDI"] = "G03";
$conceptos = null;
$ImpuestosTotales = null;
$complemento = null;
$totalImpuestosTrasladados = 0;
$Subtotal = 0;

$comprobante["Version"] = "3.3";
$comprobante["Serie"] = "A";
$comprobante["Folio"] = "123456";
$comprobante["Fecha"] = date('Y-m-d\TH:i:s');
$comprobante["Moneda"] = "MXN";
$comprobante["TipoDeComprobante"] = "I";
$comprobante["LugarExpedicion"] = "45400";
$comprobante["Emisor"] = $emisor;
$comprobante["Receptor"] = $receptor;
$comprobante["Complemento"] = $complemento;
$comprobante["MetodoPagoSpecified"] = true;
$comprobante["MetodoPago"] = "PUE";

for($i=0; $i<5; $i++){
    $traslado[0]["Base"] = "200.00";
    $Subtotal += (float) $traslado[0]["Base"];
    $traslado[0]["Impuesto"] = "002";
    $traslado[0]["TipoFactor"] = "Tasa";
    $traslado[0]["TasaOCuota"] = "0.160000";
    $traslado[0]["TasaOCuotaSpecified"] = true;
    $traslado[0]["Importe"] = "32.00";
    $totalImpuestosTrasladados +=(float) $traslado[0]["Importe"];
    $traslado[0]["ImporteSpecified"] = true;
    $impuesto["Traslados"] = $traslado;
    $concepto["ClaveProdServ"] = "50211503";
    $concepto["NoIdentificacion"] = "UT421511";
    $concepto["Cantidad"] = 1;
    $concepto["ClaveUnidad"] = "H87";
    $concepto["Unidad"] = "Pieza";
    $concepto["Descripcion"] = "Cigarros";
    $concepto["ValorUnitario"] = "200.00";
    $concepto["Importe"] = "200.00";

    $conceptos[$i]=$concepto;
    $conceptos[$i]["Impuestos"] = $impuesto;
}

$comprobante["Conceptos"] = $conceptos;
$ImpuestosTotales["Retenciones"] = null;
$ImpuestosTotales["Traslados"][0]["Impuesto"] = "002";
$ImpuestosTotales["Traslados"][0]["TipoFactor"] = "Tasa";
$ImpuestosTotales["Traslados"][0]["TasaOCuota"] = "0.160000";
$ImpuestosTotales["Traslados"][0]["Importe"] = (string)$totalImpuestosTrasladados;
$ImpuestosTotales["TotalImpuestosRetenidosSpecified"] = false;
$ImpuestosTotales["TotalImpuestosTrasladados"] = (string)$totalImpuestosTrasladados;
$ImpuestosTotales["TotalImpuestosTrasladadosSpecified"] = true;
$comprobante["Impuestos"] = $ImpuestosTotales;


$comprobante["SubTotal"] = (string)$Subtotal;
$comprobante["Total"] = (string)$Subtotal + $totalImpuestosTrasladados;

$json = json_encode($comprobante);
//echo $json;

try{
    $basePath = "c:\\";
    $jsonIssuerStamp = jsonEmisionTimbrado::Set($params);
    $resultadoJson = $jsonIssuerStamp::jsonEmisionTimbradoV4($json);
    
    if($resultadoJson->status=="success"){
        //save CFDI
        $ruta=$basePath.$resultadoJson->data->uuid.".xml";
        file_put_contents($ruta, $resultadoJson->data->cfdi);
        echo $resultadoJson->data->cfdi;
        //save QRCode
        $nombreyRuta = $resultadoJson->data->uuid.".png";
        imagepng(imagecreatefromstring(base64_decode($resultadoJson->data->qrCode)), $basePath.$nombreyRuta);
    }
    else{
        //save data error
        $ruta = $basePath."Error-".$comprobante["Serie"]."-".$comprobante["Folio"].".txt";
        $mensaje= $resultadoJson->message."\n".$resultadoJson->messageDetail;
        file_put_contents($ruta, $message);
    }
    //var_dump($resultadoJson);
}
catch(Exception $e){
    echo $e->getMessage();
}

?>