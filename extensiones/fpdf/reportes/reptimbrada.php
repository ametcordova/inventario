<?php
session_start();
if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"]=="ok"){
setlocale(LC_ALL,"es_ES");
ob_end_clean(); //    the buffer and never prints or returns anything.
ob_start(); // it starts buffering

    require_once "../../../controladores/facturaingreso.controlador.php";
    require_once "../../../modelos/facturaingreso.modelo.php";
    //require_once '../../../config/parametros.php';
    require_once('../fpdf.php');

    class PDF extends FPDF{

        
        // Cabecera de página
        function Header()
        {
            $uuid=$GLOBALS['uuid'];
            //$serfolfactura=$GLOBALS['serie'].'-'.$GLOBALS['folio'];
            $folio=$GLOBALS['folio'];
            $fechaelaboracion=$GLOBALS['fechaelaboracion'];
            $fechatimbrado=$GLOBALS['fechatimbrado'];
            $idlugarexpedicion=$GLOBALS['idlugarexpedicion'];
            $numerocertificado=$GLOBALS['numerocertificado'];
            $descriptipocomprobante=$GLOBALS['descriptipocomprobante'];
            
            $img="images/logo_nuno.png"; 
            // Logo
            $this->Image($img,10,8,80,32);          //10, 10, 80, 55 //izq, top, ancho
            $this->SetFont('Arial','B',12);
            $this->SetTextColor(255,0,0);
            // Movernos a la derecha
            //Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, boolean fill [, mixed link]]]]]]])
            $this->Cell(122);    // w-ancho h-alto txt-texto 0,1 ó LTRB-border 0,1,2-Posicion actual L,C,R-Alineacion true,false-fondo
            $this->Cell(30,4,utf8_decode('RECIBO DE PAGO No. '.$folio),0,0,'C');
            $this->Ln(4.5);
            $this->SetFont('Arial','B',10);
            $this->SetTextColor(0,0,0);
            $this->Cell(82);
            $x1=$this->GetX();
            $this->Cell(0,6,'Folio Fiscal: ',0,0,'L',false);
            $x2=$this->GetX();
            $this->Cell($x1-$x2+22);
            $this->SetFont('Arial','',10);
            $this->Cell(0,6,$uuid,0,0,'L',false);
            $this->Ln(4);
            $this->Cell(82);
            $x1=$this->GetX();
            $this->SetFont('Arial','B',10);
            $this->Cell(0,6,utf8_decode('Fecha de expedición:'),0,0,1,false);
            $x2=$this->GetX();
            $this->Cell($x1-$x2+38);
            $this->SetFont('Arial','',10);
            $this->Cell(0,6,$fechaelaboracion,0,0,1,false);
            $this->Ln(4);
            $this->Cell(82);
            $this->SetFont('Arial','B',10);
            $this->Cell(0,6,utf8_decode('Fecha y hora de certificación:'),0,0,'L',false);
            $this->SetFont('Arial','',10);
            $this->Cell(-27,6,$fechatimbrado,0,0,'R',false);
            $this->Ln(4);
            $this->Cell(82);
            $this->SetFont('Arial','B',10);
            $this->Cell(0,6,utf8_decode('Lugar de expedición:'),0,0,'L',false);
            $this->SetFont('Arial','',10);
            $this->Cell(-65,6,utf8_decode($idlugarexpedicion),0,0,'R',false);
            $this->SetFont('Arial','B',10);
            $this->Cell(10);
            $this->Cell(38,6,utf8_decode('Tipo de comprobante: '),0,0,1,false);
            $this->SetFont('Arial','',10);
            $this->Cell(0,6,$GLOBALS["tipodecomprobante"].' - '.utf8_decode($descriptipocomprobante),0,0,1,false);
            $this->SetFont('Arial','B',10);

            $this->Ln(4);
            $this->Cell(82);
            $this->SetFont('Arial','B',10);
            $this->Cell(0,6,utf8_decode('Número de certificado: '),0,0,'L',false);
            $this->SetFont('Arial','',10);
            $this->Cell(-33,6,$numerocertificado,0,0,'R',false);
            $this->Ln(4);
            $this->Cell(82);
            $this->SetFont('Arial','B',10);
            $this->Cell(16,6,utf8_decode('Moneda: '),0,0,1,false);
            $this->SetFont('Arial','',10);
            $this->Cell(14,6,'XXX',0,0,1,false);
            $this->Cell(30);
            $this->SetFont('Arial','B',10);
            $this->Cell(46,6,utf8_decode('Versión del complemento: '),0,0,1,false);
            $this->SetFont('Arial','',10);
            $this->Cell(0,6, '2.0',0,0,1,false);
            $this->Ln(6);

            $this->SetDrawColor(0,0,0);
            $this->SetFillColor(0,0,0);     // color del relleno
            $this->Cell(0,1,' ',1,0,'C',true);
            if($this->PageNo()>1){
                $this->Ln(2.5);
            }
            $this->Ln(1.5);

        }

        // FUNCION para el Pie de página
        function Footer(){
             $this->SetY(-15);  // Posición: a 1,5 cm del final
             $this->SetFont('Arial','I',8); // Arial italic 8
             $this->Cell(0,10,'Hoja '.$this->PageNo().' de {nb}',0,0,'C');  // Número de página
        }


    }       //fin de la clase Header y Footer


  class ImpresiondeFactura{


    public function printFactura(){
        
        //TRAEMOS LA INFORMACIÓN 
        $tabla="complementodepago";
        $campo = "id";
        $codigo = $_GET["codigo"];
        // Quitamos los caracteres ilegales de la variable
        $valor = filter_var($codigo, FILTER_SANITIZE_NUMBER_INT);
        $tipo='P';
        // Validamos la variable filtrada
        if(!filter_var($valor, FILTER_VALIDATE_INT)){
          return;
        }
        
        //TRAER LOS DATOS DEL ALMACEN SELECCIONADO
        $resp = ControladorFacturaIngreso::ctrObtenerDatosFactura($tabla, $campo, $valor, $tipo);
        
        if($resp){
            $GLOBALS["tipodecomprobante"] = $resp['tipodecomprobante'];
            $GLOBALS["folio"] = $resp['foliorep'];
            $GLOBALS["uuid"] = $resp['foliofiscal'];
            $GLOBALS["fechaelaboracion"] = $resp['fechaelaboracion'];
            $GLOBALS["fechatimbrado"] = $resp['fechatimbradorep'];
            $GLOBALS["idlugarexpedicion"] = $resp['cpemisor'];
            $GLOBALS["numerocertificado"] = $resp['numerocertificado'];
            $GLOBALS["descriptipocomprobante"] = $resp['descriptipocomprobante'];
            // Creación del objeto de la clase heredada
// --------------------- CONFIGURAR DATOS DEL REPORTE ------------------------------------------------------  
            //$pdf = new PDF();
            $pdf = new PDF('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetAutoPageBreak(true, 12);
            $pdf->SetDisplayMode(150);
            $pdf->SetTitle('Factura V. 4.0 - Nunosco');
            $pdf->SetAuthor('@Kordova');
            $pdf->SetDisplayMode('fullpage', 'single');
            $pdf->SetKeywords('Factura, SAT, México, Version 4.0', true);
            $pdf->SetSubject('Archivo PDF Factura SAT', true,);
            $pdf->AliasNbPages();
            $pdf->SetFont('Arial','',12);
            $pdf->AddPage();
// -----------------------------------------------------------------------------------------        
            $pdf->SetDrawColor(51,116,255);     // color de la linea
            $pdf->SetFillColor(51,116,255);     // color del relleno
            $pdf->SetTextColor(255,255,255);    // color del texto
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(0,3.4,'DATOS DEL EMISOR',1,0,'C',true);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln(4);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,'Nombre: ',0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(126,4,utf8_decode($resp['nombreemisor']),0,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'RFC: ',0,0,'R',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,$resp['rfcemisor'],0,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,utf8_decode('Dirección: '),0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(170,4,utf8_decode($resp['direccionemisor']),0,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,'e-mail: ',0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(126,4,$resp['mailempresa'],0,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'Tel: ',0,0,'R',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,$resp['telempresa'],0,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,utf8_decode('Régimen Fiscal: '),0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(126,4,$resp['regimenfiscalemisor'].'-'.utf8_decode($resp['descregimenfiscalemisor']),0,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'C.P.: ',0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,$resp['codpostal'],0,0,'L',true);
            $pdf->Ln(4);
// -----------------------------------------------------------------------------------------        
            $pdf->SetDrawColor(51,134,255);
            $pdf->SetFillColor(51,116,255);
            $pdf->SetTextColor(255,255,255);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(0,3.5,'DATOS DEL RECEPTOR',1,0,'C',true);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln(4);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,'Nombre: ',0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(126,4,$resp['nombrereceptor'],0,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'RFC: ',0,0,'R',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,$resp['rfcreceptor'],0,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,utf8_decode('Dirección: '),0,0,'L',true);
            $pdf->SetFont('Arial','',8.5);
            //$pdf->MultiCell(14, 5, '02', 'LTBR', 'C', 1);
            $pdf->MultiCell(170,4,utf8_decode($resp['direccionreceptor']),0,'L',1);
            $pdf->Ln(.2);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,'e-mail: ',0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(126,4,$resp['email'],0,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'Tel: ',0,0,'R',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,$resp['telefono'],0,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,utf8_decode('Régimen Fiscal: '),0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(170,4,$resp['regimenfiscal'].' - '.utf8_decode($resp['regimenfiscalreceptor']),0,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,'Uso CFDI: ',0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(126,4,   $resp['id_cfdi'].' - '.$resp['usocfdi'],0,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'C.P.: ',0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,$resp['codpostalreceptor'],0,0,'L',true);

            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln(4);
// -----------------------------------------------------------------------------------------        
             $pdf->SetDrawColor(51,116,255);
             $pdf->SetFillColor(51,116,255);
             $pdf->SetTextColor(255,255,255);
             $pdf->SetFont('Arial','B',8);
             $pdf->Cell(0,3.5,'DATOS GENERALES DEL COMPROBANTE',1,0,'C',true);
             $pdf->Ln(4);
             $pdf->SetDrawColor(0,0,0);
             $pdf->SetFillColor(255,255,255);
             $pdf->SetTextColor(0,0,0);

             $pdf->SetFont('Arial','B',9);
             $pdf->Cell(24.5,4,'Fecha de Pago: ',0,0,'L',true);
             $pdf->SetFont('Arial','',9);
             $pdf->Cell(32,4,utf8_decode($resp['fechapago']),0,0,'L',true);
             $pdf->SetFont('Arial','B',9);
             $pdf->Cell(25,4,'Forma de Pago: ',0,0,'L',true);
             $pdf->SetFont('Arial','',9);
             $pdf->Cell(60,4,utf8_decode($resp['idformapagorep'].' - '.$resp['descripcionformapago']),0,0,'L',true);
             $pdf->SetFont('Arial','B',9);
             $pdf->Cell(14,4,'Moneda: ',0,0,'L',true);
             $pdf->SetFont('Arial','',9);
             $pdf->Cell(50,4,$resp['idmoneda'].' - '.$resp['id_moneda'].' - '.utf8_decode($resp['moneda']),0,0,'L',true);
             $pdf->Ln(4);            

             $pdf->SetFont('Arial','B',9);
             $pdf->Cell(28,4,utf8_decode('No. de Operación: '),0,0,'L',true);
             $pdf->SetFont('Arial','',9);
             $pdf->Cell(29.5,4,utf8_decode($resp['numoperacion']),0,0,'L',true);
             $pdf->SetFont('Arial','B',9);
             $pdf->Cell(24.5,4,'Cuenta Origen: ',0,0,'L',true);
             $pdf->SetFont('Arial','',9);
             $pdf->Cell(48,4,$resp['cuentaordenante'],0,0,'L',true);
             $pdf->SetFont('Arial','B',9);
             $pdf->Cell(26,4,'Cuenta Destino: ',0,0,'L',true);
             $pdf->SetFont('Arial','',9);
             $pdf->Cell(45,4,$resp['cuentabeneficiario'],0,0,'L',true);
             $pdf->Ln(4);            

             $pdf->SetFont('Arial','B',9);
             $pdf->Cell(15,4,utf8_decode('Subtotal: '),0,0,'L',true);
             $pdf->SetFont('Arial','',9);
             $pdf->Cell(41.5,4,'$'.number_format($resp['subtotal'],2, '.',','),0,0,'L',true);
             $pdf->SetFont('Arial','B',9);
             $pdf->Cell(25,4,'Total Impuesto: ',0,0,'L',true);
             $pdf->SetFont('Arial','',9);
             $pdf->Cell(55.5,4,'$'.number_format($resp['totalimpuesto'],2, '.',','),0,0,'L',true);
             $pdf->SetFont('Arial','B',9);
             $pdf->Cell(19,4,'Total Pago: ',0,0,'L',true);
             $pdf->SetFont('Arial','',9);
             $pdf->Cell(25,4,'$'.number_format($resp['totalrecibo'],2, '.',','),0,0,'L',true);
             $pdf->Ln(4.5);            
             // -----------------------------------------------------------------------------------------
             $pdf->SetDrawColor(51,116,255);
             $pdf->SetFillColor(51,116,255);
             $pdf->SetTextColor(255,255,255);
             $pdf->SetFont('Arial','B',8);
             $pdf->Cell(0,3.5,'CONCEPTO A FACTURAR ',1,0,'C',true);
             $pdf->Ln(4);
             $pdf->SetDrawColor(0,0,0);
             $pdf->SetFillColor(255,255,255);
             $pdf->SetTextColor(0,0,0);

            // Colors, line width and bold font
            $pdf->SetDrawColor(255, 255, 255);
            $pdf->SetFillColor(0, 95, 100, 0);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('', 'B',8);
            
            // column titles
            $header = array('Prod/Serv', 'Clave y Unidad','Descripción', 'Obj. Imp.', 'Cant', 'Valor Unit.', 'Importe');
            $w = array(15, 32, 65, 35, 8, 20, 21);
            $num_headers = count($header);
            for($i = 0; $i < $num_headers; ++$i) {
                $pdf->Cell($w[$i], 6, utf8_decode($header[$i]), 1, 0, 'C', 1);
            };
            
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('', '',7.5);
            $pdf->Ln(6.3);
            $pdf->Cell(15,4,'84111506',0,0,'L',true);
            $pdf->Cell(15,4,'ACT-Actividad',0,0,'L',true);
            $pdf->cell(47);
            $pdf->Cell(12,4,'Pago',0,0,'L',true);
            $pdf->cell(23);
            $pdf->Cell(14,4,'01-No objeto de Impuesto',0,0,'L',true);
            $pdf->cell(23.5);
            $pdf->Cell(4,4,'1',0,0,'L',true);
            $pdf->cell(12);
            $pdf->Cell(4,4,'$0.00',0,0,'L',true);
            $pdf->cell(17);
            $pdf->Cell(4,4,'$0.00',0,0,'L',true);
             // -----------------------------------------------------------------------------------------
             $pdf->Ln(4.5);
             $pdf->SetDrawColor(51,116,255);
             $pdf->SetFillColor(51,116,255);
             $pdf->SetTextColor(255,255,255);
             $pdf->SetFont('Arial','B',8);
             $pdf->Cell(0,3.5,'DOCUMENTO(S) RELACIONADO(S)',1,0,'C',true);
             $pdf->Ln(4.5);
             $pdf->SetDrawColor(0,0,0);
             $pdf->SetFillColor(255,255,255);
             $pdf->SetTextColor(0,0,0);

// -------------------------IMPRIMIR LOS CONCEPTOS -----------------------------------------------
            // Colors, line width and bold font
            $pdf->SetDrawColor(255, 255, 255);
            $pdf->SetFillColor(0, 95, 100, 0);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('', 'B',8);

            // column titles
            $header = array('UUID', 'Ser.','Folio', 'Mon.','Obj. Imp.', 'Parcial', 'Saldo Ant.', 'Pagado', 'Saldo Ins.', 'Impuestos');
            $w = array(63, 6, 8, 9, 28, 10, 17, 17, 14, 24);
            $num_headers = count($header);
            for($i = 0; $i < $num_headers; ++$i) {
                $pdf->Cell($w[$i], 6, utf8_decode($header[$i]), 1, 0, 'C', 1);
            };
            $pdf->Ln(6.5);

            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('', '',8);
            
            $conceptos_json=json_decode($resp['doctosrelacionados'],TRUE);		//decodifica los conceptos que vienen en datos JSON
            $iva=$resp['tasa']*100;
            $w = array(63, 6, 8, 9, 28, 10, 17, 17, 14, 24);
            $alin=array("", "C", "C", "C", "C", "C", "L", "R", "R", "R");
            foreach ($conceptos_json as $row):
                $data = array($row["idDocumento"], $row["Serie"],$row["Folio"], 'MXN.','02-Obj.de Impuesto', '1', '$'.number_format($row["ImpSaldoAnt"],2, '.',','), '$'.number_format($row["ImpPagado"],2, '.',','), '$'.number_format($row["ImpSaldoInsoluto"],2, '.',','), $iva.'% - '.'$'.number_format($row["ImporteDR"],2, '.',','));
                $num_headers = count($data);
                for($i = 0; $i < $num_headers; ++$i) {
                    $pdf->Cell($w[$i], 6, utf8_decode($data[$i]), 1, 0, $alin[$i], 1);
                };
                $pdf->Ln(6);
            endforeach;                


// --------------------------- LINEA DE SEPARACION ----------------------------------------------
$pdf->Ln(1.5);
$y1=$pdf->GetY();
$pdf->SetDrawColor(0,0,255);
$pdf->Line(10,$y1,206,$y1);
$pdf->Ln(3.5);
$y3=$pdf->GetY();
$pdf->SetDrawColor(0,0,0);
if($y3>240){
    $pdf->AddPage();
}
// ------------------------------ ** SELLOS ** -----------------------------------------------------------
    $codeqr=$resp["codigoqr"];      //GUARDAMOS EN UNA VARIABLE EL CODIGO QR.

    $pdf->SetLineWidth(0.2);
    $x = $pdf->GetX();      //horizontal
    $y = $pdf->GetY();      //vertical
    $x1=10;
    $y1=156;
    $w=43;
    $h=30;

    $pdf->Image("data:image/png;base64,".$codeqr, $x1, $y-2, $w, $h, "png");

    $pdf->SetFillColor(255,255,255);
    $pdf->SetFont('Arial', 'B', 6.5);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetXY($x-170,$y-2);
    $x = $pdf->GetX();
    $pdf->MultiCell(0, 3.8, utf8_decode('Sello Digítal del CFDI: '),'LRT','FJ',1);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetX($x);
    $pdf->MultiCell(0, 3.5, utf8_decode(trim($resp['sellodigitalcfdi'])),'LRB','FJ',1);
    $pdf->Ln(1.7);

    $x = $pdf->GetX();
    $pdf->SetX($x+45.8);
    $pdf->SetFont('Arial', 'B', 6.5);
    $pdf->MultiCell(0, 3.8, utf8_decode('Sello Digítal del SAT: '),'LRT','FJ',1);
    $pdf->SetX($x+45.7);
    $pdf->SetFont('Arial', '', 6);
    $pdf->MultiCell(0, 3.5, utf8_decode(trim($resp['sellodigitalsat'])),'LRB','FJ',1);
    $pdf->Ln(1.7);
    if(IS_NULL($resp['cadenaoriginalsat'])){
        $pdf->SetFont('Arial', 'B', 6.5);
        $pdf->MultiCell(0, 3.5, utf8_decode('Cadena Original: '),'LRT','FJ',1);
        $pdf->SetX($x);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(0, 3.5, trim($resp['cadenaoriginal']),'LRB','FJ',1);
        $pdf->SetFont('Arial', '', 6.5);
        $pdf->Ln(1);
    }else{
        $pdf->SetFont('Arial', 'B', 6.5);
        $pdf->MultiCell(0, 3.5, utf8_decode('Cadena Original SAT: '),'LRT','FJ',1);
        $pdf->SetX($x);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(0, 3.5, trim($resp['cadenaoriginalsat']),'LRB','FJ',1);
        $pdf->SetFont('Arial', '', 6.5);
        $pdf->Ln(1);
    }
    // --------------------------- LINEA DE SEPARACION ----------------------------------------------
    $y1=$pdf->GetY();
    $pdf->SetLineWidth(0.5);
    $pdf->SetDrawColor(0,0,0);
    $pdf->Line(10,$y1,206,$y1);
    $pdf->Ln(3);
    // --------------------------- IMPORTES TOTALES ------------------------------------------------------
    $pdf->SetFont('Arial','B',10);
    $pdf->MultiCell(0,0,utf8_decode('Este documento es una representación impresa de un CFDI Versión 4.0'),0,'C',1);
    // --------------------------- LINEA DE SEPARACION ----------------------------------------------
    $pdf->Ln(3);
    $y1=$pdf->GetY();
    $pdf->Line(10,$y1,206,$y1);
    $pdf->Ln(4);
}
// ------------------------------------------------------------------------------------------------     
 

// ------------------------------------------------------------------------------------------------      
    $nombrearchivo=$resp['rfcemisor'].'-'.$GLOBALS['folio'];
    
    $pdf->Output('I',$nombrearchivo.'.pdf');
    ob_end_flush(); // It's printed here, because ob_end_flush "prints" what's in the buffer, rather than returning it (unlike the ob_get_* functions)        
    //}else{
        //include '../../../vistas/plantilla.php'; <td style="width:65px"><img src="../../../config/logotipo.png"></td>
        //echo "No hay datos para imprimir.";
    //}
  }

  }   //fin de la clase

}else{
    //include '../../../vistas/plantilla.php'; <td style="width:65px"><img src="../../../config/logotipo.png"></td>
    echo "no tienes acceso a este reporte.";

}

$printerinvoice = new ImpresiondeFactura();
$printerinvoice -> printFactura();



/*
cruzar la hoja con una linea
$width=$pdf->GetPageWidth(); // Width of Current Page
$height=$pdf->GetPageHeight(); // Height of Current Page
$pdf->Line(0, 0,$width,$height); // Line one Cross
$pdf->Line($width, 0,0,$height); // Line two Cross
*/
?>