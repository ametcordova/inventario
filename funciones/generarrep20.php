<?php

// Se especifica la zona horaria
date_default_timezone_set("America/Mexico_City");

// Se desactivan los mensajes de debug
//error_reporting(~(E_WARNING|E_NOTICE));
error_reporting(E_ALL);
/*********************************************/
require_once dirname( __DIR__ ).'/modelos/conexion.php';

/*********************************************/
class ClaseGenerarRep20{

    static public function GenerarJsonRep20($tabla, $campo, $valor){
    $bytes=0;
    // $tabla=complementodepago
    $sql="SELECT tb1.*, emp.rfc, emp.razonsocial AS nombreemisor, emp.numerocertificado, emp.regimenfiscalemisor, emp.serierep, emp.id_exportacion, clie.rfc AS rfcreceptor, clie.nombre AS nombrereceptor, clie.codpostal AS cpreceptor, clie.regimenfiscal AS regfiscalreceptor, mon.id_moneda, cfdi.id_cfdi
    FROM $tabla tb1
    INNER JOIN empresa emp ON emp.id=tb1.idrfcemisor
    INNER JOIN clientes clie ON clie.id=tb1.idrfcreceptor
    INNER JOIN c_usocfdi cfdi ON cfdi.id=tb1.idusocfdi
    INNER JOIN c_moneda mon ON mon.id=tb1.idmoneda
    WHERE tb1.$campo=:$campo";

	$stmt = Conexion::conectar()->prepare($sql);

    $stmt -> bindParam(":".$campo, $valor, PDO::PARAM_INT);

  	$stmt -> execute();

    $datosdefactura = $stmt->fetch(PDO::FETCH_ASSOC);
    /*********************************************/
    $doctosrelacionados=json_decode($datosdefactura['doctosrelacionados'],true);
    $foliorep=substr($datosdefactura['foliorep'],1);
    // Datos de la Factura
    $datos['Comprobante']['Version'] = '4.0';
    $datos['Comprobante']['Serie'] = $datosdefactura['serierep'];
    $datos['Comprobante']['Folio'] = $foliorep;
    $datos['Comprobante']['Fecha'] = date('Y-m-d\TH:i:s', time() - 120);
    $datos['Comprobante']['NoCertificado'] = $datosdefactura['numerocertificado'];
    $datos['Comprobante']['SubTotal'] = 0;
    $datos['Comprobante']['Moneda'] = 'XXX';
    $datos['Comprobante']['Total'] = 0;
    $datos['Comprobante']['TipoDeComprobante'] = $datosdefactura['tipodecomprobante'];
    $datos['Comprobante']['Exportacion'] = $datosdefactura['id_exportacion'];
    $datos['Comprobante']['LugarExpedicion'] = $datosdefactura['cpemisor'];

    // Datos del Emisor CAMBIAR EN PRODUCCION
    $datos['Comprobante']['Emisor']['Rfc'] = $datosdefactura['rfc']; //RFC DE PRUEBA
    $datos['Comprobante']['Emisor']['Nombre'] = $datosdefactura['nombreemisor'];  // EMPRESA DE PRUEBA
    $datos['Comprobante']['Emisor']['RegimenFiscal'] = $datosdefactura['regimenfiscalemisor'];      

    // Datos del receptor CAMBIAR EN PRODUCCION
    $datos['Comprobante']['Receptor']['Rfc'] = $datosdefactura['rfcreceptor'];
    $datos['Comprobante']['Receptor']['Nombre'] = $datosdefactura['nombrereceptor'];
    $datos['Comprobante']['Receptor']['DomicilioFiscalReceptor'] = $datosdefactura['cpreceptor'];
    $datos['Comprobante']['Receptor']['RegimenFiscalReceptor'] = $datosdefactura['regfiscalreceptor'];
    $datos['Comprobante']['Receptor']['UsoCFDI'] = $datosdefactura['id_cfdi'];
    
    // DATOS DE LOS CONCEPTOS
    $datos['Comprobante']['Conceptos'][0]['ClaveProdServ'] = '84111506';
    $datos['Comprobante']['Conceptos'][0]['Cantidad'] = 1;
    $datos['Comprobante']['Conceptos'][0]['ClaveUnidad'] = 'ACT';
    $datos['Comprobante']['Conceptos'][0]['Descripcion'] = 'Pago';
    $datos['Comprobante']['Conceptos'][0]['ValorUnitario'] = 0;
    $datos['Comprobante']['Conceptos'][0]['Importe'] = 0;
    $datos['Comprobante']['Conceptos'][0]['ObjetoImp'] = '01';

    // NODO COMPLEMENTO
    $datos['Comprobante']['Complemento'][0]['Pagos20']['Version'] = '2.0';
    $datos['Comprobante']['Complemento'][0]['Pagos20']['Totales']["TotalTrasladosBaseIVA16"] =  $datosdefactura['subtotal'];
    $datos['Comprobante']['Complemento'][0]['Pagos20']['Totales']["TotalTrasladosImpuestoIVA16"] =  $datosdefactura['totalimpuesto'];
    $datos['Comprobante']['Complemento'][0]['Pagos20']['Totales']["MontoTotalPagos"] =  $datosdefactura['totalrecibo'];

    $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]['FechaPago'] =  $datosdefactura['fechapago'];
    $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["FormaDePagoP"] =  $datosdefactura['idformapagorep'];
    $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["MonedaP"] =  "MXN";   //Modificar
    $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["TipoCambioP"] =  "1";   //Modificar
    $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["NumOperacion"] = $datosdefactura['numoperacion'];
    $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["Monto"] = $datosdefactura['totalrecibo'];

    foreach ($doctosrelacionados as $key => $value) {
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["DoctoRelacionado"][$key]["IdDocumento"] = $value['idDocumento'];
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["DoctoRelacionado"][$key]["Serie"] = $value['Serie'];
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["DoctoRelacionado"][$key]["Folio"] = $value['Folio'];
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["DoctoRelacionado"][$key]["MonedaDR"] = 'MXN';
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["DoctoRelacionado"][$key]["EquivalenciaDR"] = '1';
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["DoctoRelacionado"][$key]["NumParcialidad"] = $value['NumParcialidad'];
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["DoctoRelacionado"][$key]["ImpSaldoAnt"] = $value['ImpSaldoAnt'];
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["DoctoRelacionado"][$key]["ImpPagado"] = $value['ImpPagado'];
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["DoctoRelacionado"][$key]["ImpSaldoInsoluto"] = $value['ImpSaldoInsoluto'];
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["DoctoRelacionado"][$key]["ObjetoImpDR"] = $datosdefactura['idobjetoimpuesto'];

        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["DoctoRelacionado"][$key]["ImpuestosDR"]["TrasladosDR"][0]["BaseDR"] = $value['BaseDR'];
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["DoctoRelacionado"][$key]["ImpuestosDR"]["TrasladosDR"][0]["ImpuestoDR"] = $datosdefactura['idimpuesto'];
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["DoctoRelacionado"][$key]["ImpuestosDR"]["TrasladosDR"][0]["TipoFactorDR"] = 'Tasa';    
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["DoctoRelacionado"][$key]["ImpuestosDR"]["TrasladosDR"][0]["TasaOCuotaDR"] = $datosdefactura['tasa'];
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["DoctoRelacionado"][$key]["ImpuestosDR"]["TrasladosDR"][0]["ImporteDR"] = $value['ImporteDR'];
    }

        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["ImpuestosP"]["TrasladosP"][0]["BaseP"] = $datosdefactura['subtotal'];
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["ImpuestosP"]["TrasladosP"][0]["ImpuestoP"] = $datosdefactura['idimpuesto'];
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["ImpuestosP"]["TrasladosP"][0]["TipoFactorP"] = 'Tasa';
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["ImpuestosP"]["TrasladosP"][0]["TasaOCuotaP"] = $datosdefactura['tasa'];
        $datos['Comprobante']['Complemento'][0]['Pagos20']["Pago"][0]["ImpuestosP"]["TrasladosP"][0]["ImporteP"] = $datosdefactura['totalrecibo'];


    $invoice_json = json_encode($datos, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    $bytes = file_put_contents("../archivos/filesinvoices/REP-P".$foliorep.".json", $invoice_json); 

    return intval($bytes);

    }

  
}  //FIN DE LA CLASE
/*========================================================= */
?>