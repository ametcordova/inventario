<?php

require_once "../../../controladores/reporteinventario.controlador.php";
require_once "../../../modelos/reporteinventario.modelo.php";
require_once "../../../funciones/funciones.php";

//REQUERIMOS LA CLASE TCPDF
require_once('tcpdf_include.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	
    //Page header
    public function Header() {
		$tabla=$_GET["idNomAlma"];    
		$idalmacen=$_GET["idNumAlma"];
		$encabezado = ControladorInventario::ctrReporteInventarioAlmacen($idalmacen);
		//$nombreprov=$encabezado["nombreproveedor"];
		if($tabla=="alm_villah"){
			$img="images/logo_siesur.jpg";
			$razonsocial='S I E S U R';
		}else{
			$img="images/logo_nuno.png";
			$razonsocial='F I P A B I D E';
		}
		
		$ubicacion=$encabezado["ubicacion"];
		$tel_alm=$encabezado["telefono"];
		$email_alm=$encabezado["email"];
		
		
		$fechahoy=fechaHoraMexico(date("d-m-Y G:i:s"));
        // Logo
        //$image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($img, 15, 8, 23);	//izq, top, ancho
		//$this->Image('images/logo_nuno.jpg',10,8,25);
        // Set font
        $this->SetFont('helvetica', 'B', 16);
        // Title
		$this->Cell(0, 10, $razonsocial, 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->Ln(6);
		$this->SetFont('helvetica', 'B', 10);
        //$this->Cell(0, 10, 'Av. Rio Coatan No. 504, Col. 24 de Junio, Tuxtla Gutiérrez, Chiapas.    Teléfono: (961) 1-40-71-19   brunonunosco1998@gmail.com', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->Cell(0, 10, $ubicacion."  ".$tel_alm."  ".$email_alm, 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->Ln(6);
		$this->SetFont('helvetica', 'B', 14);
		$this->Cell(0, 10, 'Reporte de Inventario del Almacén '.$tabla." al $fechahoy", 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->Ln(3.5);
		
		// Colors, line width and bold font
        $this->SetFillColor(127);
        $this->SetTextColor(0, 0, 0, 100);
        $this->SetFont('', 'B',10);
		
		// column titles
		$header = array('id', 'SKU', 'Código', 'Producto', 'U.Med.', 'Exist', 'Fisico', 'Dif.');
        $w = array(15, 35, 30, 120, 25, 15, 15, 14);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 6.5, $header[$i], 1, 0, 'C', 1);
        };

        $this->Ln();
		
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// 540 max de la fila P y 800 L

class reporteInventario{

public function traerReporteInventario(){

//TRAEMOS LA INFORMACIÓN 
$tabla=$_GET["idNomAlma"];
$campo = "cant";
$clave = $_GET["tiporeporte"];

//TRAER LOS DATOS DEL ALMACEN SELECCIONADO
$respuestaInventario = ControladorInventario::ctrMostrarInventario($tabla, $campo, $clave);

if($respuestaInventario){


$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	$pdf->SetCreator('AdminINV');
	$pdf->SetAuthor('Amet Córdova @Kordova');
	$pdf->SetTitle('Reporte de Inventario NUNOSCO');

	// set default header data
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));	

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);		
	
	// set auto page breaks
	//$pdf->SetAutoPageBreak(TRUE, 0);	// 0 PARA QUITAR EL MARGEN INFERIOR
	$pdf->SetAutoPageBreak(TRUE, 15);
	//$pdf->SetFooterMargin(10);
	$pdf->setPrintFooter(true);		//true para que imprima numero de paginas

	// set auto page breaks
	//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	//$pdf->SetAutoPageBreak(TRUE, 20);
	
	// set image scale factor
	//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);	

$pdf->startPageGroup();

$pdf->AddPage();
    
// ---------------------------------------------------------
$cantEntra=0;


foreach ($respuestaInventario as $row) {

$existe=number_format($row["cant"]);

$bloque4 = <<<EOF

 	<table style="font-size:9px; padding:5px 3px;">

	  <tr>
		<td style="border: 1px solid #666; width:42px; text-align:center">$row[id_producto]</td>
		<td style="border: 1px solid #666; width:100px; text-align:center">$row[sku]</td>
		<td style="border: 1px solid #666; width:84.5px; text-align:center">$row[codigointerno]</td>
		<td style="border: 1px solid #666; width:340.5px; text-align:left">$row[descripcion]</td>
		<td style="border: 1px solid #666; width:71px; text-align:center">$row[medida]</td>
		<td style="border: 1px solid #666; width:42.5px; text-align:center">$existe</td>
		<td style="border: 1px solid #666; width:42.5px; text-align:right"></td>
		<td style="border: 1px solid #666; width:39.5px; text-align:right"></td>
      </tr>
     
	 </table>
	 
EOF;

//$page = $pdf->getAliasNumPage();

$cantEntra+=$row['cant'];
$pdf->writeHTML($bloque4, false, false, false, false, '');
}
// ---------------------------------------------------------

$cantEntra=number_format($cantEntra);
$bloque6 = <<<EOF

	<table style="font-size:10px; padding:5px 3px;">

	<tr bgcolor="#cccccc">
		<td style="border: 1px solid #666; width:638px; text-align:right">Totales:</td>
		<td style="border: 1px solid #666; width:42.5px; text-align:center">$cantEntra</td>
		<td style="border: 1px solid #666; background-color:white; width:42.5px; text-align:right"></td>
		<td style="border: 1px solid #666; background-color:white; width:39.5	px; text-align:right"></td>
    </tr>
    
 	</table>

EOF;

$pdf->writeHTML($bloque6, false, false, false, false, '');    
$pdf->Ln(16);
// ---------------------------------------------------------

$bloque7 = <<<EOF

	<table style="font-size:9px; padding:5px 5px; align:center;">

    <tr>
        <td style="border: 1px solid #666;width:370px; height:50px; text-align:center">Nombre y firma quien Elabora</td>
        <td style="width:20px;height:50px; text-align:center"></td>
        <td style="border: 1px solid #666;width:370px; height:50px; text-align:center">Nombre y firma quien Revisa</td>
    </tr>

 	</table>

EOF;

$pdf->writeHTML($bloque7, false, false, false, false, '');    

// ---------------------------------------------------------    
//SALIDA DEL ARCHIVO 
$fechahoy=fechaHoraMexico(date("d-m-Y G:i:s"));
$hoy1 = date("Ymd");    
$hoy2 = date("His");
$fecha_elabora=$hoy1.$hoy2;

 $nombre_archivo="inventario_".$fecha_elabora.".pdf";   //genera el nombre del archivo para descargarlo
 $pdf->Output($nombre_archivo);
}else{
  //$nombre_archivo="inventario";
$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->Output("inventario.pdf");
}
}

}

$fechahoy=fechaHoraMexico(date("d-m-Y G:i:s"));
$hoy1 = date("Ymd");    
$hoy2 = date("His");
$fecha_elabora=$hoy1.$hoy2;

$inventario = new reporteInventario();
$idalmacen=$_GET["idNumAlma"];
$tabla=$_GET["idNomAlma"];
$inventario -> traerReporteInventario();

?>
