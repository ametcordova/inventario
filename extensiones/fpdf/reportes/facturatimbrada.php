<?php
session_start();
if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"]=="ok"){
setlocale(LC_ALL,"es_ES");
ob_end_clean(); //    the buffer and never prints or returns anything.
ob_start(); // it starts buffering

    require_once "../../../controladores/facturaingreso.controlador.php";
    require_once "../../../modelos/facturaingreso.modelo.php";
    //require_once '../../../config/parametros.php';
    require_once('../rotation.php');
    require_once('../fpdf.php');

    //class PDF extends FPDF{
    class PDF extends PDF_Rotate{

        
        // Cabecera de página
        function Header()
        {
            $uuid=$GLOBALS['uuid'];
            $serfolfactura=$GLOBALS['serie'].'-'.$GLOBALS['folio'];
            $idexportacion=$GLOBALS['idexportacion'];
            $fechaelaboracion=$GLOBALS['fechaelaboracion'];
            $fechatimbrado=$GLOBALS['fechatimbrado'];
            $idlugarexpedicion=$GLOBALS['idlugarexpedicion'];
            $numerocertificado=$GLOBALS['numerocertificado'];
            $descriptipocomprobante=$GLOBALS['descriptipocomprobante'];
            $descripciontipoexporta=$GLOBALS['descripciontipoexporta'];
            
            $img="images/logo_nuno.png"; 
            // Logo
            $this->Image($img,10,8,80,32);          //10, 10, 80, 55 //izq, top, ancho
            $this->SetFont('Arial','B',12);
            $this->SetTextColor(255,0,0);
            // Movernos a la derecha
            //Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, boolean fill [, mixed link]]]]]]])
            $this->Cell(122);    // w-ancho h-alto txt-texto 0,1 ó LTRB-border 0,1,2-Posicion actual L,C,R-Alineacion true,false-fondo
            if (function_exists('iconv'))
            {
                $this->Cell(30,4,iconv('UTF-8', 'ISO-8859-1','FACTURA No. '.$serfolfactura),0,0,'C');
            }else{
                $this->Cell(30,4,iconv('UTF-8', 'ISO-8859-1','FACTURA No. '.$serfolfactura),0,0,'C');
            }         
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
            $this->Cell(0,6,iconv('UTF-8', 'ISO-8859-1','Fecha de expedición:'),0,0,1,false);
            $x2=$this->GetX();
            $this->Cell($x1-$x2+38);
            $this->SetFont('Arial','',10);
            $this->Cell(0,6,$fechaelaboracion,0,0,1,false);
            $this->Ln(4);
            $this->Cell(82);
            $this->SetFont('Arial','B',10);
            $this->Cell(0,6,iconv('UTF-8', 'ISO-8859-1','Fecha y hora de certificación:'),0,0,'L',false);
            $this->SetFont('Arial','',10);
            $this->Cell(-27,6,$fechatimbrado,0,0,'R',false);
            $this->Ln(4);
            $this->Cell(82);
            $this->SetFont('Arial','B',10);
            $this->Cell(0,6,iconv('UTF-8', 'ISO-8859-1','Lugar de expedición:'),0,0,'L',false);
            $this->SetFont('Arial','',10);
            $this->Cell(-65,6,iconv('UTF-8', 'ISO-8859-1',$idlugarexpedicion),0,0,'R',false);
            $this->Ln(4);
            $this->Cell(82);
            $this->SetFont('Arial','B',10);
            $this->Cell(0,6,iconv('UTF-8', 'ISO-8859-1','Número de certificado: '),0,0,'L',false);
            $this->SetFont('Arial','',10);
            $this->Cell(-33,6,$numerocertificado,0,0,'R',false);
            $this->Ln(4);
            $this->Cell(82);
            $this->SetFont('Arial','B',10);
            $this->Cell(38,6,iconv('UTF-8', 'ISO-8859-1','Tipo de comprobante: '),0,0,1,false);
            $this->SetFont('Arial','',10);
            $this->Cell(30,6,$GLOBALS["idtipocomprobante"].' - '.iconv('UTF-8', 'ISO-8859-1',$descriptipocomprobante),0,0,1,false);
            $this->SetFont('Arial','B',10);
            $this->Cell(22,6,iconv('UTF-8', 'ISO-8859-1','Exportación: '),0,0,1,false);
            $this->SetFont('Arial','',10);
            $this->Cell(0,6, $idexportacion.' - '.$descripciontipoexporta,0,0,1,false);
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

              $uuid=$GLOBALS['uuid'];
              if($uuid==""){
                  $this->SetFont('Arial','',45);
                  $this->SetTextColor(202, 183, 185);
                  $this->RotatedText(20,205,'FACTURA SIN VALOR FISCAL',45);
              }
 
        }

        function RotatedText($x, $y, $txt, $angle)
        {
            //Text rotated around its origin
            $this->Rotate($angle,$x,$y);
            $this->Text($x,$y,$txt);
            $this->Rotate(0);
        }
    }       //fin de la clase Header y Footer


  class ImpresiondeFactura{


    public function printFactura(){
        
        //TRAEMOS LA INFORMACIÓN 
        $tabla="facturaingreso";
        $campo = "id";
        $codigo = $_GET["codigo"];
        // Quitamos los caracteres ilegales de la variable
        $valor = filter_var($codigo, FILTER_SANITIZE_NUMBER_INT);
        $tipo='I';
        // Validamos la variable filtrada
        if(!filter_var($valor, FILTER_VALIDATE_INT)){
          return;
        }
        
        //TRAER LOS DATOS DEL ALMACEN SELECCIONADO
        $resp = ControladorFacturaIngreso::ctrObtenerDatosFactura($tabla, $campo, $valor, $tipo);
        
        if($resp){
            $GLOBALS["idtipocomprobante"] = $resp['idtipocomprobante'];
            $GLOBALS["idexportacion"] = $resp['idexportacion'];
            $GLOBALS["serie"] = $resp['serie'];
            $GLOBALS["folio"] = $resp['folio'];
            $GLOBALS["uuid"] = $resp['uuid'];
            $GLOBALS["fechaelaboracion"] = $resp['fechaelaboracion'];
            $GLOBALS["fechatimbrado"] = $resp['fechatimbrado'];
            $GLOBALS["idlugarexpedicion"] = $resp['idlugarexpedicion'];
            $GLOBALS["numerocertificado"] = $resp['numerocertificado'];
            $GLOBALS["descriptipocomprobante"] = $resp['descriptipocomprobante'];
            $GLOBALS["descripciontipoexporta"] = $resp['descripciontipoexporta'];
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
            $pdf->Cell(126,4,iconv('UTF-8', 'ISO-8859-1',$resp['nombreemisor']),0,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'RFC: ',0,0,'R',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,$resp['rfcemisor'],0,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,iconv('UTF-8', 'ISO-8859-1','Dirección: '),0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(170,4,iconv('UTF-8', 'ISO-8859-1',$resp['direccionemisor']),0,0,'L',true);
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
            $pdf->Cell(26,4,iconv('UTF-8', 'ISO-8859-1','Régimen Fiscal: '),0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(126,4,$resp['idregimenfiscalemisor'].'-'.iconv('UTF-8', 'ISO-8859-1',$resp['regimenfiscalemisor']),0,0,'L',true);
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
            $pdf->Cell(26,4,iconv('UTF-8', 'ISO-8859-1','Dirección: '),0,0,'L',true);
            $pdf->SetFont('Arial','',8.5);
            //$pdf->MultiCell(14, 5, '02', 'LTBR', 'C', 1);
            $pdf->MultiCell(170,4,iconv('UTF-8', 'ISO-8859-1',$resp['direccionreceptor']),0,'L',1);
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
            $pdf->Cell(26,4,iconv('UTF-8', 'ISO-8859-1','Régimen Fiscal: '),0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(170,4,$resp['regimenfiscal'].' - '.iconv('UTF-8', 'ISO-8859-1',$resp['regimenfiscalreceptor']),0,0,'L',true);
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
            $pdf->Cell(28,4,'Forma Pago: ',0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(76,4,iconv('UTF-8', 'ISO-8859-1',$resp['idformapago'].' - '.$resp['descripcionformapago']),0,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(28,4,'Metodo Pago: ',0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(64,4,iconv('UTF-8', 'ISO-8859-1',$resp['idmetodopago'].' - '.$resp['descripcionmp']),0,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(28,4,'Moneda: ',0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(76,4,$resp['idmoneda'].' - '.$resp['id_moneda'].' - '.iconv('UTF-8', 'ISO-8859-1',$resp['moneda']),0,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(28,4,'Tipo de cambio: ',0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(64,4,'',0,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',8.5);
            $pdf->Cell(28,4,'Condiciones Pago: ',0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(76,4,iconv('UTF-8', 'ISO-8859-1',$resp['condicionesdepago']),0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(168,4,'',0,0,'L',true);
            $pdf->Ln(4);            
// -----------------------------------------------------------------------------------------
            $pdf->SetDrawColor(51,116,255);
            $pdf->SetFillColor(51,116,255);
            $pdf->SetTextColor(255,255,255);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(0,3.5,'DATOS DE LOS CONCEPTOS A FACTURAR',1,0,'C',true);
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
            $w = array(15, 32, 87, 14, 7, 20, 21);
            $num_headers = count($header);
            for($i = 0; $i < $num_headers; ++$i) {
                $pdf->Cell($w[$i], 6, iconv('UTF-8', 'ISO-8859-1',$header[$i]), 1, 0, 'C', 1);
            };
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('', '',7.5);
            $pdf->Ln(6.3);
        
// -------------------------IMPRIMIR LOS CONCEPTOS -----------------------------------------------
$conceptos_json=json_decode($resp['conceptos'],TRUE);		//decodifica los conceptos que vienen en datos JSON
$sumasubtotal = $sumaimpuesto = $sumatotal = $y2= 0;
foreach ($conceptos_json as $row):
$sumasubtotal+=$row["Importe"];
//Save the current position 
$x=$pdf->GetX();
$y=$pdf->GetY();
if($y2>0){
    $y=$y2+.5;
    $pdf->SetY($y);
}
    
 if ($row === reset($conceptos_json)) {     // Si es el primer elemento del array
    $linea=" ";
 }else{
    $linea="T";
 }

 if(strlen($row["Descripcion"])>270){
    $hightrow=6.5;
}else{
    $hightrow=3.5;
}

$pdf->MultiCell(15, $hightrow, $row["ClaveProdServ"], $linea, 'C');

$pdf->SetXY($x+15,$y);
$pdf->MultiCell(32, $hightrow, $row["ClaveUnidad"].'-'.$row["Unidad"],  $linea, 'C', 1);

$pdf->SetXY($x+47,$y);
$pdf->MultiCell(87,$hightrow,iconv('UTF-8', 'ISO-8859-1',$row["Descripcion"]), $linea,'FJ',1);

$y2=$pdf->GetY();
$pdf->SetXY($x+134,$y);
$pdf->MultiCell(14, $hightrow, $row["ObjetoImp"],  $linea, 'C', 1);

$pdf->SetXY($x+148,$y);
$pdf->MultiCell(7, $hightrow, $row["Cantidad"],  $linea, 'C', 1);

$pdf->SetXY($x+155,$y);
$pdf->MultiCell(20,$hightrow, '$'.number_format($row["ValorUnitario"],4, '.',','),  $linea, 'R', 1);

$pdf->SetXY($x+175,$y);
$pdf->MultiCell(21,$hightrow, '$'.number_format($row["Importe"],2, '.',','),  $linea, 'R', 1);


if ($row != end($conceptos_json)) $pdf->Ln(6.5);    //si no es el Ùltimo elemento del array, interlineado de 5.5

//break;
endforeach;
// ----------------------------------------FIN DE CONCEPTOS ---------------------------------------

// --------------------------- LINEA DE SEPARACION ----------------------------------------------
$pdf->Ln(14);
$y1=$pdf->GetY();
$pdf->SetLineWidth(0.5);
$pdf->SetDrawColor(0,0,0);
$pdf->Line(10,$y1,206,$y1);
$pdf->Ln(.5);
// --------------------------- IMPORTES TOTALES ------------------------------------------------------
$sumaimpuesto=($sumasubtotal*16)/100;
$sumatotal = $sumasubtotal+$sumaimpuesto;
$pdf->SetFont('Arial','B',10);
$pdf->Cell(17,5,'Subtotal: ',0,0,'L',true);
$pdf->Cell(24,5,'$'.number_format($sumasubtotal,2, '.',','),0,0,'L',true);
$pdf->Cell(21,5,'Descuento: ',0,0,'L',true);
$pdf->Cell(22,5,'$0.00',0,0,'L',true);
$pdf->Cell(51,5,'Total Impuestos Trasladados: ',0,0,'L',true);
$pdf->Cell(20,5,'$'.number_format($sumaimpuesto,2, '.',','),0,0,'L',true);
$pdf->Cell(15,5,'Total: ',0,0,'R',true);
$pdf->Cell(26,5,'$'.number_format($sumatotal,2, '.',','),0,0,'L',true);

// --------------------------- LINEA DE SEPARACION ----------------------------------------------
$pdf->Ln(5.5);
$y1=$pdf->GetY();
$pdf->Line(10,$y1,206,$y1);
$pdf->Ln(3.5);
$y3=$pdf->GetY();
if($y3>240){
    $pdf->AddPage();
}
// --------------------- TRAEMOS DATOS DE FACTURA TIMBRADA ---------------------------------------
$tabla="datosfacturatimbre";
//$campo = "folio";
$campo = "id";
$valor = $_GET["codigo"];
//$valor = 370;
    
$response = ControladorFacturaIngreso::ctrObtenerDatosTimbre($tabla, $campo, $valor);
    
if($response){

// ------------------------------ ** SELLOS ** -----------------------------------------------------------
    $codeqr=$response["codigoqr"];

    // TAMBIEN FUNCIONA
    // $dataURI = "data:image/png;base64,".$codeqr;
    // $img = explode(',',$dataURI,2)[1];
    // $pic = 'data://text/plain;base64,'.$img;
    // $info = getimagesize($pic);
    //$pdf->Image("data:image/png;base64,".$codeqr, $x, $y, $w, $h, "png");

    $pdf->SetLineWidth(0.2);
    $x = $pdf->GetX();      //horizontal
    $y = $pdf->GetY();      //vertical
    $x1=10;
    $y1=156;
    $w=43;
    $h=30;

    $pdf->Image("data:image/png;base64,".$codeqr, $x1, $y-2, $w, $h, "png");

    //$pdf->SetFont('Arial', '', 6);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetFont('Arial', 'B', 6.5);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetXY($x-170,$y-2);
    $x = $pdf->GetX();
    $pdf->MultiCell(0, 3.8, iconv('UTF-8', 'ISO-8859-1','Sello Digítal del CFDI: '),'LRT','FJ',1);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetX($x);
    $pdf->MultiCell(0, 3.5, iconv('UTF-8', 'ISO-8859-1',$response['sellodigitalcfdi']),'LRB','FJ',1);
    $pdf->Ln(1.7);

    $x = $pdf->GetX();
    $pdf->SetX($x+45.8);
    $pdf->SetFont('Arial', 'B', 6.5);
    $pdf->MultiCell(0, 3.8, iconv('UTF-8', 'ISO-8859-1','Sello Digítal del SAT: '),'LRT','FJ',1);
    $pdf->SetX($x+45.7);
    $pdf->SetFont('Arial', '', 6);
    $pdf->MultiCell(0, 3.5, iconv('UTF-8', 'ISO-8859-1',$response['sellodigitalsat']),'LRB','FJ',1);
    $pdf->Ln(2);
    if(IS_NULL($response['cadenaoriginalsat'])){
        $pdf->SetFont('Arial', 'B', 6.5);
        $pdf->MultiCell(0, 3.5, iconv('UTF-8', 'ISO-8859-1','Cadena Original: '),'LRT','FJ',1);
        $pdf->SetX($x);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(0, 3.5, trim($response['cadenaoriginal']),'LRB','FJ',1);
        $pdf->SetFont('Arial', '', 6.5);
        $pdf->Ln(1);
    }else{
        $pdf->SetFont('Arial', 'B', 6.5);
        $pdf->MultiCell(0, 3.5, iconv('UTF-8', 'ISO-8859-1','Cadena Original SAT: '),'LRT','FJ',1);
        $pdf->SetX($x);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(0, 3.5, trim($response['cadenaoriginalsat']),'LRB','FJ',1);
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
    $pdf->MultiCell(0,0,iconv('UTF-8', 'ISO-8859-1','Este documento es una representación impresa de un CFDI Versión 4.0'),0,'C',1);
    // --------------------------- LINEA DE SEPARACION ----------------------------------------------
    $pdf->Ln(3);
    $y1=$pdf->GetY();
    $pdf->Line(10,$y1,206,$y1);
    $pdf->Ln(3);
}
// ------------------------------------- FIRMA ---------------------------------------------------     
    $pdf->Ln(10);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(65,5,' ',0,0,'C',true);
    $pdf->MultiCell(70,5,'BRUNO DIAZ GORDILLO','T','C',1);
    $pdf->Cell(50,5,'',0,0,'C',true);
    $pdf->Ln(4);
// ------------------------------------------------------------------------------------------------      
    $nombrearchivo=$resp['rfcemisor'].'-'.$GLOBALS['serie'].$GLOBALS['folio'];
    $pdf->Output('I',$nombrearchivo.'.pdf');
    ob_end_flush(); // It's printed here, because ob_end_flush "prints" what's in the buffer, rather than returning it (unlike the ob_get_* functions)        
    }else{
        //include '../../../vistas/plantilla.php'; <td style="width:65px"><img src="../../../config/logotipo.png"></td>
        echo "No hay datos para imprimir.";
    }
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