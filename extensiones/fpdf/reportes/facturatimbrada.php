<?php
session_start();
if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"]=="ok"){
setlocale(LC_ALL,"es_ES");
ob_start();

    require_once "../../../controladores/facturaingreso.controlador.php";
    require_once "../../../modelos/facturaingreso.modelo.php";
    require_once '../../../config/parametros.php';
    require_once('../fpdf.php');

    class PDF extends FPDF{

        
        // Cabecera de página
        function Header()
        {

            $img="images/logo_nuno.png";
            // Logo
            $this->Image($img,10,8,80,32);          //10, 10, 80, 55 //izq, top, ancho
            $this->SetFont('Arial','B',12);
            $this->SetTextColor(255,0,0);
            // Movernos a la derecha
            $this->Cell(122);    // w-ancho h-alto txt-texto 0,1 ó LTRB-border 0,1,2-Posicion actual L,C,R-Alineacion true,false-fondo
            $this->Cell(30,4,utf8_decode('FACTURA No. '),0,0,'C');
            $this->Ln(4.5);
            $this->SetFont('Arial','B',10);
            $this->SetTextColor(0,0,0);
            $this->Cell(82);
            $this->Cell(0,6,'Folio Fiscal: ',0,0,1);
            $this->Ln(4);
            $this->Cell(82);
            $this->Cell(0,6,utf8_decode('Fecha de expedición:'),0,0,1,false);
            $this->Ln(4);
            $this->Cell(82);
            $this->Cell(0,6,utf8_decode('Fecha y hora de certificación:'),0,0,1,false);
            $this->Ln(4);
            $this->Cell(82);
            $this->Cell(0,6,utf8_decode('Lugar de expedición:'),0,0,1,false);
            $this->Ln(4);
            $this->Cell(82);
            $this->Cell(0,6,utf8_decode('Número de certificado: '),0,0,1,false);
            $this->Ln(4);
            $this->Cell(82);
            $this->SetFont('Arial','B',10);
            $this->Cell(38,6,utf8_decode('Tipo de comprobante: '),0,0,1,false);
            $this->SetFont('Arial','',10);
            $this->Cell(30,6,'- I - Ingreso',0,0,1,false);
            //$this->Cell(82);
            $this->SetFont('Arial','B',10);
            $this->Cell(22,6,utf8_decode('Exportación: '),0,0,1,false);
            $this->SetFont('Arial','',10);
            $this->Cell(0,6,'01- No Aplica',0,0,1,false);
            $this->Ln(7);

            $this->SetDrawColor(0,0,0);
            $this->Cell(0,1,' ',1,0,'C',true);
            $this->Ln(3);

    
        }

        // Pie de página
        function Footer(){
            // Posición: a 1,5 cm del final
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            // Número de página
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }


    }       //fin de la clase Header y Footer


    class reportedeOrdendeServicio{


    public function reporteOS(){
        
        //TRAEMOS LA INFORMACIÓN 
        $tabla="facturaingreso";
        $campo = "id";
        $valor = $_GET["codigo"];
        //$valor = 370;
        
        //TRAER LOS DATOS DEL ALMACEN SELECCIONADO
        $resp = ControladorFacturaIngreso::ctrObtenerDatosFactura($tabla, $campo, $valor);
        
        if($resp){

            // Creación del objeto de la clase heredada
            //$pdf = new PDF();
            $pdf = new PDF('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetDisplayMode(150);
            $pdf->AliasNbPages();
            $pdf->SetFont('Arial','',12);
            $pdf->AddPage();
// -----------------------------------------------------------------------------------------        
            $pdf->SetDrawColor(51,116,255);     // color de la linea
            $pdf->SetFillColor(51,116,255);     // color del relleno
            $pdf->SetTextColor(255,255,255);    // color del texto
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(0,4,'DATOS DEL EMISOR',1,0,'C',true);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln(5);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,'Nombre: ',1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(126,4,$resp['nombreemisor'],1,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'RFC: ',1,0,'R',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,utf8_decode($resp['rfcemisor']),1,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,utf8_decode('Dirección: '),1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(170,4,utf8_decode($resp['direccionemisor']),1,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,'e-mail: ',1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(126,4,$resp['mailempresa'],1,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'Tel: ',1,0,'R',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,$resp['telempresa'],1,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,utf8_decode('Régimen Fiscal: '),1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(126,4,$resp['idregimenfiscalemisor'].'-'.utf8_decode($resp['regimenfiscalemisor']),1,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'C.P.: ',1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,$resp['codpostal'],1,0,'L',true);
            $pdf->Ln(2);

             $datos_material=array();
             $long=0;
// -----------------------------------------------------------------------------------------        
            $pdf->Ln();
            $pdf->SetDrawColor(51,134,255);
            $pdf->SetFillColor(51,116,255);
            $pdf->SetTextColor(255,255,255);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(0,4,'DATOS DEL RECEPTOR',1,0,'C',true);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln(5);
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
            $pdf->Ln(5);

            //$pdf->Ln(7.5);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln(0);
// -----------------------------------------------------------------------------------------        
            $pdf->SetDrawColor(51,116,255);
            $pdf->SetFillColor(51,116,255);
            $pdf->SetTextColor(255,255,255);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(0,4,'DATOS GENERALES DEL COMPROBANTE',1,0,'C',true);
            $pdf->Ln(5);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);

            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(28,4,'Forma Pago: ',1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(76,4,utf8_decode($resp['idformapago'].' - '.$resp['descripcionformapago']),1,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(28,4,'Metodo Pago: ',1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(64,4,utf8_decode($resp['idmetodopago'].' - '.$resp['descripcionmp']),1,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(28,4,'Moneda: ',1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(76,4,$resp['idmoneda'].' - '.$resp['id_moneda'].' - '.utf8_decode($resp['moneda']),1,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(28,4,'Tipo de cambio: ',1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(64,4,'',1,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',8.5);
            $pdf->Cell(28,4,'Condiciones Pago',1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(168,4,'',1,0,'L',true);
            $pdf->Ln(6);            
// -----------------------------------------------------------------------------------------
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
                $pdf->Cell($w[$i], 6.5, utf8_decode($header[$i]), 1, 0, 'C', 1);
            };
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('', '',7.5);
            $pdf->Ln(7.5);
        
// -------------------------IMPRIMIR LOS CONCEPTOS -----------------------------------------------
$conceptos_json=json_decode($resp['conceptos'],TRUE);		//decodifica los datos JSON
$sumasubtotal = $sumaimpuesto = $sumatotal = 0;
foreach ($conceptos_json as $row):
$sumasubtotal+=$row["Importe"];
// if ($row === reset($conceptos_json)) {
//     echo "PRIMER ELEMENTO";
// }
//Save the current position '72151602'.
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell(15, 4, $row["ClaveProdServ"], 'LTBR', 'C');

$pdf->SetXY($x+15,$y);
$pdf->MultiCell(32, 4, $row["ClaveUnidad"].'-'.$row["Unidad"], 'LTBR', 'C', 1);

$pdf->SetXY($x+47,$y);
$pdf->MultiCell(87,3.5,utf8_decode($row["Descripcion"]),'LTBR','FJ',1);

$pdf->SetXY($x+134,$y);
$pdf->MultiCell(14, 5, $row["ObjetoImp"], 'LTBR', 'C', 1);

$pdf->SetXY($x+148,$y);
$pdf->MultiCell(7, 5, $row["Cantidad"], 'LTBR', 'C', 1);

$pdf->SetXY($x+155,$y);
$pdf->MultiCell(20,5, '$'.number_format($row["ValorUnitario"],4, '.',','), 'LTBR', 0, 'R', 1);

$pdf->SetXY($x+175,$y);
$pdf->MultiCell(21,5, '$'.number_format($row["Importe"],2, '.',','), 'LTBR', 0, 'R', 1);

if ($row != end($conceptos_json)) $pdf->Ln(6.5);    //si no es el Ùltimo elemento del array, interlineado de 6.5

//break;
endforeach;
// ----------------------------------------FIN DE CONCEPTOS ---------------------------------------

// --------------------------- LINEA DE SEPARACION ----------------------------------------------
$pdf->Ln(7);
$y1=$pdf->GetY();
$pdf->SetLineWidth(0.5);
$pdf->SetDrawColor(0,0,0);
$pdf->Line(10,$y1,206,$y1);
$pdf->Ln(1);
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
$pdf->Ln(4);

// --------------------- TRAEMOS DATOS DE FACTURA TIMBRADA ---------------------------------------
$tabla="datosfacturatimbre";
$campo = "folio";
$valor = $_GET["codigo"];
//$valor = 370;
    
//TRAER LOS DATOS DEL ALMACEN SELECCIONADO
$response = ControladorFacturaIngreso::ctrObtenerDatosTimbre($tabla, $campo, $valor);
    
if($response){

        
}

// ------------------------------ ** SELLOS ** -----------------------------------------------------------
$qr=$response['codigoqr'];
// $imgsrc="data:image/png;base64,'.$qr.'";
// $pic = 'data:image/png;base64,'.$qr;
//$info = getimagesize($pic);
// Example of Image from data stream ('PHP rules')
//$imgdata = base64_decode($qr);
$pic = 'data:image/png;base64,'.$qr;
//$info = getimagesize($pic);
// The '@' character is used to indicate that follows an image data stream and not an image file name
$pdf->Image('@'.$pic);

$pdf->SetLineWidth(0.2);
$x = $pdf->GetX();
$y = $pdf->GetY();
//$pdf->Cell(11,11, $pdf->Image('images/prueba.jpg', $pdf->GetX(), $pdf->GetY(),11),1);
//$pdf->MultiCell(45, 20, $pdf->Image('@'.$pic, 'png'), 'LRTB', 'L', 1);
$pdf->SetFont('Arial', '', 6.5);
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial', 'B', 7);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->SetXY($x-170,$y-20);
$x = $pdf->GetX();
$pdf->MultiCell(0, 3.8, utf8_decode('Sello Digítal del CFDI: '),'LRTB','FJ',1);
$pdf->SetFont('Arial', '', 6.5);
$pdf->SetX($x);
$pdf->MultiCell(0, 3.7, utf8_decode($response['sellodigitalcfdi'].$x.'-'.$y),'LRTB','FJ',1);
$pdf->Ln(1.7);

$x = $pdf->GetX();
$pdf->SetX($x+45.8);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(0, 3.8, utf8_decode('Sello Digítal del SAT: '),'LRTB','FJ',1);
$pdf->SetX($x+45.7);
$pdf->SetFont('Arial', '', 6.5);
$pdf->MultiCell(0, 3.7, utf8_decode($response['sellodigitalsat'].$x.'-'.$y),'LRTB','FJ',1);
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(0, 3.5, utf8_decode('Cadena Original: '),'','FJ',1);
$pdf->SetX($x);
$pdf->SetFont('Arial', '', 6.5);
$pdf->MultiCell(0, 3.5, utf8_decode($response['cadenaoriginal'].$x.'-'.$y),'','FJ',1);
$pdf->SetFont('Arial', '', 6.5);
$pdf->Ln(6.5);




// -----------------------------------------------------------------------------------------            
        $pdf->Output('I','facturatimbrada.pdf');
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

$reporteOS = new reportedeOrdendeServicio();
//$reporteOS -> codigo=$_GET["codigo"];
$reporteOS -> reporteOS();



/*
cruzar la hoja con una linea
$width=$pdf->GetPageWidth(); // Width of Current Page
$height=$pdf->GetPageHeight(); // Height of Current Page
$pdf->Line(0, 0,$width,$height); // Line one Cross
$pdf->Line($width, 0,0,$height); // Line two Cross
*/
?>