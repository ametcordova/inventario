<?php
session_start();
if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"]=="ok"){
setlocale(LC_ALL,"es_ES");
ob_start();
require_once "../../../controladores/reporteinventario.controlador.php";
require_once "../../../modelos/reporteinventario.modelo.php";
require_once "../../../funciones/funciones.php";

//REQUERIMOS LA CLASE TCPDF
require_once('tcpdf_include.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	
	public $nomtecnico;	

    //Page header
    public function Header() {

		$tabla=$_GET["idNomAlma"];    
		$idalmacen=$_GET["idNumAlma"];
		$valor = $_GET["idNumTec"];
		$item="id";
		$tablatecnicos="tecnicos";
		$encabezado = ControladorInventario::ctrReporteInventarioAlmacen($idalmacen);

		$nombretecnico=mdlVerTecnicos($tablatecnicos, $item, $valor);
		//var_dump($nombretecnico);
		//die();
		$nomtecnico=$nombretecnico["nombre"];
		
		if($tabla=="alm_villah" || $tabla=="alm_comal"){
			$img="images/logo_siesur.jpg";
			$razonsocial='S E I S U R';
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
        $this->Image($img, 6, 5, 23);	//izq, top, ancho
		//$this->Image('images/logo_nuno.jpg',10,8,25);
        // Set font
		$this->Ln(1.5);
        $this->SetFont('helvetica', 'B', 16);
        // Title
		$this->Cell(0, 10, $razonsocial, 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->Ln(4.5);
		$this->SetFont('helvetica', 'B', 8);
        //$this->Cell(0, 10, 'Av. Rio Coatan No. 504, Col. 24 de Junio, Tuxtla Gutiérrez, Chiapas.    Teléfono: (961) 1-40-71-19   brunonunosco1998@gmail.com', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->Cell(0, 10, "          ".$ubicacion."  ".$tel_alm."  ".$email_alm, 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->Ln(5);
		$this->SetFont('helvetica', 'B', 9);
		//$this->Cell(0, 10, 'Reporte de Inventario del Técnico del Almacén '.$tabla." al $fechahoy", 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->Cell(0, 10, '     Reporte de Existencia del Técnico '.$nomtecnico." en ".$tabla. " al $fechahoy", 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->Ln(3);
		
		// Colors, line width and bold font
        $this->SetFillColor(127);
        $this->SetTextColor(0, 0, 0, 100);
        $this->SetFont('', 'B',10);
		
		// column titles
		$header = array('SKU', 'Código', 'Producto', 'U.Med.', 'Exist', 'Fisico', 'Dif.');
        $w = array(20, 20, 99, 20, 13.5, 13, 13.5);
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
$idalmacen=$_GET["idNumAlma"];
$campo = "id_tecnico";
$valor = $_GET["idNumTec"];
$item="id";
$tablatecnicos="tecnicos";

$nombretecnico=mdlVerTecnicos($tablatecnicos, $item, $valor);
//var_dump($nombretecnico);
//die();
$nomtecnico=$nombretecnico["nombre"];

/*======================================================== */
//TRAER LOS DATOS DEL ALMACEN SELECCIONADO POR TECNICO
/*======================================================== */
$respuestaInventario = ControladorInventario::ctrReporteInventarioPorTecnico($tabla, $campo, $valor, $idalmacen);
if($respuestaInventario){
/*======================================================== */
$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
	$pdf->SetMargins(5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
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

$existe=number_format($row["existe"]);
$descripcion=substr($row['descripcion'],0,45);
$bloque4 = <<<EOF

 	<table style="font-size:9px; padding:5px 3px;">

	  <tr>
		<td style="border: 1px solid #666; width:56.5px; text-align:center">$row[sku]</td>
		<td style="border: 1px solid #666; width:57px; text-align:center">$row[codigointerno]</td>
		<td style="border: 1px solid #666; width:280.5px; text-align:left">$descripcion</td>
		<td style="border: 1px solid #666; width:56.5px; text-align:center">$row[medida]</td>
		<td style="border: 1px solid #666; width:38px; text-align:center">$existe</td>
		<td style="border: 1px solid #666; width:38px; text-align:right"></td>
		<td style="border: 1px solid #666; width:38px; text-align:right"></td>
      </tr>
     
	 </table>
	 
EOF;

//$page = $pdf->getAliasNumPage();

$cantEntra+=$row['existe'];
$pdf->writeHTML($bloque4, false, false, false, false, '');
}
// ---------------------------------------------------------

$cantEntra=number_format($cantEntra);
$bloque6 = <<<EOF

	<table style="font-size:10px; padding:5px 3px;">

	<tr bgcolor="#cccccc">
		<td style="border: 1px solid #666; width:450px; text-align:right">Totales:</td>
		<td style="border: 1px solid #666; width:38.5px; text-align:center">$cantEntra</td>
		<td style="border: 1px solid #666; background-color:white; width:38px; text-align:right"></td>
		<td style="border: 1px solid #666; background-color:white; width:38px; text-align:right"></td>
    </tr>
    
 	</table>

EOF;

$pdf->writeHTML($bloque6, false, false, false, false, '');    
$pdf->Ln(6);
// ---------------------------------------------------------

$bloque7 = <<<EOF

	<table style="font-size:9px; padding:5px 5px; align:center;">

    <tr>
        <td style="border: 1px solid #666;width:275px; height:50px; text-align:center">Nombre y firma del Técnico
		<br><br><br>$nomtecnico
		</td>
        <td style="width:15px;height:50px; text-align:center"></td>
        <td style="border: 1px solid #666;width:275px; height:50px; text-align:center">Nombre y firma quien Revisa</td>
    </tr>

 	</table>

EOF;

$pdf->writeHTML($bloque7, false, false, false, false, '');    

// ---------------------------------------------------------    
//SALIDA DEL ARCHIVO 
//$fechahoy=fechaHoraMexico(date("d-m-Y G:i:s"));
$hoy1 = date("Ymd");    
$hoy2 = date("His");
$fecha_elabora=$hoy1.$hoy2;

 $nombre_archivo="inventario_".$fecha_elabora.".pdf";   //genera el nombre del archivo para descargarlo
 $pdf->Output($nombre_archivo);
}else{
  //$nombre_archivo="inventario";
$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));	

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);		

$pdf->AddPage();

$bloque7 = <<<EOF

	<table style="font-size:9px; padding:5px 5px; align:center;">

    <tr>
        <td style="border: 1px solid #666;width:564px; height:25px; text-align:center">
		NO EXISTE MATERIAL PARA ESTE TÉCNICO.
		</td>
    </tr>

 	</table>

EOF;

$pdf->writeHTML($bloque7, false, false, false, false, '');    


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


	


/*
SELECT hist.`id_salida`,hist.`id_tecnico`,hist.`id_producto`, prod.sku, prod.descripcion, prod.id_medida, med.medida, hist.`estatus`, SUM(hist.cantidad) as Total, SUM(hist.disponible) AS existe
FROM hist_salidas hist 
INNER JOIN productos prod ON hist.id_producto=prod.id
INNER JOIN medidas med ON prod.id_medida=med.id
WHERE hist.id_tecnico=1 AND hist.estatus=1
GROUP BY hist.`id_tecnico`, hist.`id_producto`
*/


}else{
	echo "no tienes acceso a este reporte o no estas logueado.";
}


/*=============================================
        MOSTRAR TECNICOS
    =============================================*/
    function mdlVerTecnicos($tablatecnicos, $item, $valor){
        try{

            $stmt = Conexion::conectar()->prepare("SELECT id, nombre FROM $tablatecnicos WHERE $item=:$item AND status=1");

            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_INT);

            $stmt -> execute();

            return $stmt -> fetch(PDO::FETCH_ASSOC);

            $stmt = null;

        } catch (Exception $e) {
            echo "Failed: " . $e->getMessage();
        } // fin de la funcion mdlVerTecnicos


    }
?>

