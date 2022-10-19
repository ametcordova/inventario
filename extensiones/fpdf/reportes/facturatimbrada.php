<?php
session_start();
if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"]=="ok"){
setlocale(LC_ALL,"es_ES");
ob_start();

require_once "../../../controladores/adminoservicios.controlador.php";
require_once "../../../modelos/adminoservicios.modelo.php";
require_once "../../../controladores/ajusteinventario.controlador.php";
require_once "../../../modelos/ajusteinventario.modelo.php";
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
            $this->Ln(4);
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
        $tabla="tabla_os";
        $campo = "id";
        $valor = $_GET["codigo"];
        
        //TRAER LOS DATOS DEL ALMACEN SELECCIONADO
        $respuesta = ControladorOServicios::ctrObtenerMaterialOS($tabla, $campo, $valor);
        
        if($respuesta){
            
            // Creación del objeto de la clase heredada
            //$pdf = new PDF();
            $pdf = new PDF('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->SetDisplayMode(150);
            $pdf->AliasNbPages();
            $pdf->SetFont('Arial','',12);
            $pdf->AddPage();
// -----------------------------------------------------------------------------------------        
            $pdf->SetDrawColor(51,116,255);
            $pdf->SetFillColor(51,116,255);
            $pdf->SetTextColor(255,255,255);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(0,4,'DATOS DEL EMISOR',1,0,'C',true);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln(5);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,'Nombre: ',1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(126,4,'BRUNO DIAZ GORDILLO Y/O NUNOSCO',1,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'RFC: ',1,0,'R',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,'DIGB980626MX3',1,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,utf8_decode('Dirección: '),1,0,'L',true);
            $pdf->Cell(170,4,'',1,0,'L',true);
            $pdf->Ln();
            $pdf->Cell(26,4,'e-mail: ',1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(126,4,'brunonunosco1998@gmail.com',1,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'Tel: ',1,0,'R',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,'9611407119',1,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,utf8_decode('Régimen Fiscal: '),1,0,'L',true);
            $pdf->Cell(126,4,'',1,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'C.P.: ',1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,'29047',1,0,'L',true);
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
            $pdf->Cell(126,4,'CARSO INFRAESTRUCTURA Y CONSTRUCCION',0,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'RFC: ',0,0,'R',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,'CIC991214L94',0,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,utf8_decode('Dirección: '),0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(170,4,'CALLE LAGO ZURICH No 245 EDIFICIO FRISCO PISO 2 AMPLIACION GRANADA C.P 11529 CIUDAD DE MEXICO,CIUDAD DE MEXICO',0,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,'e-mail: ',0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(126,4,'brunonunosco1998@gmail.com',0,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'Tel: ',0,0,'R',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,'9611407119',0,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,utf8_decode('Régimen Fiscal: '),0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(170,4,'623 - Opcional para Grupos de Sociedades',0,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(26,4,'Uso CFDI: ',0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(126,4,'I01 - Construcciones.',0,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(10,4,'C.P.: ',0,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(34,4,'11529',0,0,'L',true);
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
            $pdf->Cell(76,4,utf8_decode('99 - Transferencia eléctronica de fondos'),1,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(28,4,'Metodo Pago: ',1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(64,4,'PPD - Pago en parcialidades o diferido',1,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(28,4,'Moneda: ',1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(76,4,'MXN - Peso Mexicano',1,0,'L',true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(28,4,'Tipo de cambio: ',1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(64,4,'',1,0,'L',true);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',8.5);
            $pdf->Cell(28,4,'Condiciones Pago',1,0,'L',true);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(168,4,'',1,0,'L',true);
            $pdf->Ln(7.5);            
// -----------------------------------------------------------------------------------------
            // Colors, line width and bold font
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
        
// -----------------------------------------------------------------------------------------
$descripcion="BAJANTE AEREO DE 50 M. MIGRACIONES 07102022/40 SUR1.PROY: CAR051456. ODC: 00065850. No. REPSE: 09679. CONTRATO: BAJSUR063 .";
$cadena=strlen($descripcion);

//Save the current position '72151602'.
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell(15, 4, $y, 'LTBR', 'C');

$pdf->SetXY($x+15,$y);
$pdf->MultiCell(32, 4, 'E48 - Unidad de Servicio', 'LTBR', 'C', 1);

$pdf->SetXY($x+47,$y);
$pdf->MultiCell(87,3.5,utf8_decode($descripcion).$y,'LTBR','FJ',1);
$y1=$pdf->GetY();

$pdf->SetXY($x+134,$y);
$pdf->MultiCell(14, 5, '02', 'LTBR', 'C', 1);

$pdf->SetXY($x+148,$y);
$pdf->MultiCell(7, 5, '4', 'LTBR', 'C', 1);

$pdf->SetXY($x+155,$y);
$pdf->MultiCell(20,5, '$999,999.0000', 'LTBR', 0, 'R', 1);

$pdf->SetXY($x+175,$y);
$pdf->MultiCell(21,5, '$999,999.99', 'LTBR', 0, 'R', 1);
$pdf->Ln(6.5);

$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->MultiCell(15, 4, $y, 'LTBR', 'C');

$pdf->SetXY($x+15,$y);
$pdf->MultiCell(32, 4, 'E48 - Unidad de Servicio', 'LTBR', 'C', 1);

$pdf->SetXY($x+47,$y);
$pdf->MultiCell(87,3.5,utf8_decode($descripcion).$y,'LTBR','FJ',1);
$y1=$pdf->GetY();

$pdf->SetXY($x+134,$y);
$pdf->MultiCell(14, 5, '02', 'LTBR', 'C', 1);

$pdf->SetXY($x+148,$y);
$pdf->MultiCell(7, 5, '4', 'LTBR', 'C', 1);

$pdf->SetXY($x+155,$y);
$pdf->MultiCell(20,5, '$999,999.0000', 'LTBR', 0, 'R', 1);

$pdf->SetXY($x+175,$y);
$pdf->MultiCell(21,5, '$999,999.99', 'LTBR', 0, 'R', 1);

$pdf->Ln(27.2);

// -----------------------------------------------------------------------------------------
            
            $pdf->Output('I','facturatimbrada.pdf');
        }else{
            //include '../../../vistas/plantilla.php'; <td style="width:65px"><img src="../../../config/logotipo.png"></td>
            echo "no tienes acceso a este reporte.";
        }
    }
    }   //fin de la clase


}
$reporteOS = new reportedeOrdendeServicio();
//$reporteOS -> codigo=$_GET["codigo"];
$reporteOS -> reporteOS();


?>