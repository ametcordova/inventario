<?php
//============================================================+
// File name   : example_058.php
// Begin       : 2010-04-22
// Last Update : 2013-05-14
//
// Description : Example 058 for TCPDF class
//               SVG Image
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: SVG Image
 * @author Nicola Asuni
 * @since 2010-05-02
 */

session_start();
if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"]=="ok"){
setlocale(LC_ALL,"es_ES");
ob_start();

require_once "../../../../controladores/adminoservicios.controlador.php";
require_once "../../../../modelos/adminoservicios.modelo.php";
require_once "../../../../controladores/ajusteinventario.controlador.php";
require_once "../../../../modelos/ajusteinventario.modelo.php";
require_once '../../../../config/parametros.php';

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

	class MYPDF extends TCPDF {

		//Page header
		public function Header() {
			// Set font
			$this->SetFont('Helvetica', 'B', 16);
			// Title
			$this->Cell(0,0,'TELÉFONOS DE MÉXICO S.A. DE C.V.',0,false,'C',0,'',0,false, 'M','M');
				// Salto de línea
			$this->Ln(5);
			$this->SetFont('helvetica','B',10);
			$this->Cell(0,0,'PLANTA EXTERNA',0,false,'C',0,'',0,false, 'M','M');
		}

		// Page footer
		public function Footer() {
			// Position at 15 mm from bottom
			$this->SetY(-15);
			// Set font
			$this->SetFont('helvetica', 'I', 8);
			// Page number
			$this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		}

	}


        //TRAEMOS LA INFORMACIÓN 
        $tabla="tabla_os";
        $campo = "id";
        $valor = $_GET["codigo"];
        //$valor = 286;
        
        //TRAER LOS DATOS DEL ALMACEN SELECCIONADO
        $respuesta = ControladorOServicios::ctrObtenerOS($tabla, $campo, $valor);
        
		if($respuesta){


			// create new PDF document
			$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->setCreator(PDF_CREATOR);
			$pdf->setAuthor('Nicola Asuni');
			$pdf->setTitle('REPORTE DE OS');
			$pdf->setSubject('Reporte de Ordenes de Servicio');
			$pdf->setKeywords('TCPDF, PDF, Reporte, OS, Nunosco');

			// set default header data
			//$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' '.$valor, PDF_HEADER_STRING);

			// set header and footer fonts
			//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

			// set default monospaced font
			//$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			//$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT); PDF_MARGIN_FOOTER
			$pdf->SetMargins(5,5,5);
			$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

			// set auto page breaks
			//$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			$pdf->setAutoPageBreak(TRUE, 5);

			// set image scale factor
			//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			// set some language-dependent strings (optional)
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}

			// ---------------------------------------------------------

			// set font
			//$pdf->setFont('helvetica', '', 10);

			// add a page
			$pdf->AddPage();
			$fechaInstalacion=date("d-m-Y", strtotime($respuesta['fecha_instalacion']));
			$factura=$respuesta['factura'];
			$idos=$respuesta['id'];

			$datos_instalacion_json=json_decode($respuesta['datos_instalacion'],TRUE);		//decodifica los datos JSON 
			$datos_material_json=json_decode($respuesta['datos_material'],TRUE);		//decodifica los datos JSON 
			//echo $datos_instalacion_json[0]['numpisaplex'];
			$pdf->Ln(7.5);
			$pdf->SetDrawColor(0,0,0);		//color borde
			$pdf->SetFillColor(250,239,8);	//color relleno
			$pdf->SetTextColor(0,0,0);		//color Texto
			$pdf->SetLineWidth(.3);     //GRUESO DE LOS BORDES
			$pdf->SetFont('Helvetica','B',10);
			$pdf->Cell(0,6,'ORDEN DE SERVICIO No. '.$idos,1,0,'C',true);
			$pdf->Ln(7.5);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetFillColor(255,255,255);
			$pdf->SetTextColor(0,0,0);
			$pdf->Ln(.2);
			// ---------------------------------------------------------		
			$pdf->Cell(10,6,'',0,0,'R',true);
			$pdf->Cell(23,6,iconv('UTF-8', 'ISO-8859-1','TELEFONO:'),0,0,'L',true);
			$pdf->Cell(28,6,$respuesta['telefono'],1,0,'L',true);
			$pdf->SetX(71);    
			$pdf->Cell(8,6,'',0,0,'R',true);
			$pdf->Cell(25,6,'FECHA:',0,0,'R',true);
			$pdf->Cell(30,6,$fechaInstalacion,1,0,'C',true);
			$pdf->SetX(140);    
			$pdf->Cell(10,6,'',0,0,'R',true);
			$pdf->Cell(25,6,'FACTURA:',0,0,'R',true);
			$pdf->Cell(25,6,$factura,1,0,'C',true);
			$pdf->Ln(7.5);
	
			$pdf->Cell(33,6,'NÚMERO DE OS:',0,0,'L',true);
			$pdf->Cell(30,6,$respuesta['ordenservicio'],1,0,'C',true);
			$pdf->SetX(75);
			$pdf->Cell(21,6,'PISAPLEX:',0,0,'L',true);
			$pdf->Cell(25,6,$datos_instalacion_json[0]['numpisaplex'],1,0,'C',true);
			$pdf->SetX(123);
			$pdf->Cell(12,6,'TIPO:',0,0,'L',true);
			$pdf->Cell(20,6,$datos_instalacion_json[0]['numtipo'],1,0,'C',true);
			$pdf->SetX(156);
			$pdf->Cell(24,6,'FOLIO TEC:',0,0,'C',true);
			$pdf->Cell(20,6,'',1,0,'C',true);
			$pdf->Ln(7.5);
	
			$pdf->Cell(55,6,'NOMBRE DEL CONTRATANTE:',0,0,'R',true);
			$pdf->Cell(140,6,$respuesta['nombrecontrato'],1,0,'L',true);
			$pdf->Ln();
			$pdf->Cell(55,6,'DIRECCIÓN:',0,0,'R',true);
			$pdf->Cell(140,6,$datos_instalacion_json[0]['direccionos'],1,0,'L',true);
			// $pdf->Ln();
			// $pdf->Cell(55,6,utf8_decode('ENTRE CALLES:'),0,0,'R',true);
			// $pdf->Cell(120,6,utf8_decode(''),1,0,'L',true);
			$pdf->Ln();
			$pdf->Cell(55,6,'COLONIA:',0,0,'R',true);
			$pdf->Cell(140,6,$datos_instalacion_json[0]['coloniaos'],1,0,'L',true);
			$pdf->Ln(8);
	
			$pdf->Cell(55,6,'EDIFICIO:',0,0,'R',true);
			$pdf->Cell(50,6,'',1,0,'L',true);
			$pdf->SetX(125);
			$pdf->Cell(15,6,'DEPTO:',0,0,'L',true);
			$pdf->Cell(25,6,'',1,0,'C',true);
			$pdf->Ln(7);
	
			$pdf->SetFont('helvetica','B',7);
			$pdf->Cell(178,2,'NAVEGACIÓN',0,'B','R',true);
			$pdf->Ln();
			$pdf->Cell(35,5,'DISTRITO',0,0,'C',true);
			$pdf->SetX(47);
			$pdf->Cell(25,5,'TERMINAL',0,0,'C',true);
			$pdf->SetX(74);
			$pdf->Cell(20,5,'PUERTO',0,0,'C',true);
			$pdf->SetX(96);
			$pdf->Cell(30,5,'POTENCIA TERMINAL',0,0,'C',true);
			$pdf->SetX(128);
			$pdf->Cell(25,5,'POTENCIA ROSETA',0,0,'C',true);
			$pdf->SetX(155);
			$pdf->Cell(23,5,'DESCARGA',0,0,'C',true);
			$pdf->SetX(180);
			$pdf->Cell(23,5,'CARGA',0,0,'C',true);
			$pdf->Ln(4.5);	

			$pdf->SetFont('helvetica','',8);
			$pdf->Cell(35,6,$datos_instalacion_json[0]['distritoos'],1,0,'C',true);
			$pdf->SetX(47);
			$pdf->Cell(25,6,$datos_instalacion_json[0]['terminalos'],1,0,'C',true);
			$pdf->SetX(74);
			$pdf->Cell(20,6,$datos_instalacion_json[0]['puertoos'],1,0,'C',true);
			$pdf->SetX(96);
			$pdf->Cell(30,6,'',1,0,'C',true);
			$pdf->SetX(128);
			$pdf->Cell(25,6,'',1,0,'C',true);
			$pdf->SetX(155);
			$pdf->Cell(23,6,'',1,0,'C',true);
			$pdf->SetX(180);
			$pdf->Cell(23,6,'',1,0,'C',true);
			$pdf->Ln(7.5);
	
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetFillColor(250,239,8);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetLineWidth(.3);     //GRUESO DE LOS BORDES
			$pdf->SetFont('helvetica','B',10);
			$pdf->Cell(0,6,'DATOS DEL MODEM RETIRADO AL CLIENTE',1,0,'C',true);
			$pdf->Ln(7.5);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetFillColor(255,255,255);
			$pdf->SetTextColor(0,0,0);
			$pdf->Ln(1);
			
			$modemretirado=isset($datos_instalacion_json[0]['modemretirado'])?$datos_instalacion_json[0]['modemretirado']:'';
			$modemnumserie=isset($datos_instalacion_json[0]['modemnumserie'])?$datos_instalacion_json[0]['modemnumserie']:'';

			$pdf->Cell(60,6,'MARCA Y MODELO:',0,0,'R',true);
			$pdf->Cell(100,6,$modemretirado,1,0,'L',true);
			$pdf->Ln();
			$pdf->Cell(60,6,'NÚMERO DE SERIE:',0,0,'R',true);
			$pdf->Cell(100,6,$modemnumserie,1,0,'L',true);
			$pdf->Ln(8);

			$pdf->SetDrawColor(0,0,0);
			$pdf->SetFillColor(250,239,8);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetLineWidth(.3);     //GRUESO DE LOS BORDES
			$pdf->SetFont('helvetica','B',10);
			$pdf->Cell(0,6,'DATOS DE LA ONT INSTALADA',1,0,'C',true);
			$pdf->Ln(7.5);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetFillColor(255,255,255);
			$pdf->SetTextColor(0,0,0);
			$pdf->Ln(1);
	
			$pdf->Cell(60,6,'NÚMERO DE SERIE:',0,0,'R',true);
			$pdf->Cell(100,6,$datos_instalacion_json[0]['numeroserie'],1,0,'L',true);
			$pdf->Ln();
			$pdf->Cell(60,6,'ALFANÚMERICO:',0,0,'R',true);
			$pdf->Cell(100,6,$datos_instalacion_json[0]['alfanumerico'],1,0,'L',true);
			$pdf->Ln();
			$pdf->Cell(60,6,'KEY:',0,0,'R',true);
			$pdf->Cell(100,6,'',1,0,'L',true);
			$pdf->Ln(8);
	
			$pdf->SetFont('helvetica','B',7);
			$pdf->Cell(0,10,'OBSERVACIONES:',1,0,'L',true);
			$pdf->Ln(25);

				// --------------------FIRMA-------------------------------------			
				$getx=$pdf->GetX();
				$gety=$pdf->GetY();

				$cadena = $respuesta['firma'];
				// echo $cadena;
				// exit;
				if($cadena!="Sin Firma"){
					$separador = "image/svg+xml,";
					$firma = explode($separador, $cadena);
					//$imgdata = base64_decode($imageArray[1]);
					$pdf->ImageSVG('@'.$firma[1], $x=$getx+75, $y=$gety-57, $w='50', $h=97, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				}
				// -------------------------------------------------------------			

			$pdf->SetFont('Helvetica','',8);
			$pdf->Cell(205,4,$datos_instalacion_json[0]['nombrefirma'],0,0,'C',true);
			$pdf->Ln();
			$pdf->Cell(62,6,$respuesta['id_tecnico'].'-'.$respuesta['tecnico'],1,0,'C',true);
			$pdf->SetX(77);
			$pdf->SetFont('helvetica','B',7);
			$pdf->Cell(62,5,'RECIBÍ SERVICIO DE CONFORMIDAD:','LTR',0,'C',true);
			$pdf->SetX(144);
			$pdf->Cell(62,5,'NO DESEO EL SERVICIO.:','LTR',0,'C',true);
			$pdf->Ln();
			$pdf->Cell(62,4,'NOMBRE Y FIRMA DEL INSTALADOR','LRB',0,'C',true);
			$pdf->SetX(77);
			$pdf->Cell(62,4,'NOMBRE Y FIRMA (CLIENTE)','LRB',0,'C',true);
			$pdf->SetX(144);
			$pdf->Cell(62,4,'NOMBRE Y FIRMA (CLIENTE)','LRB',0,'C',true);
			$pdf->Ln(7);
	
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetFillColor(250,239,8);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetLineWidth(.3);     
			$pdf->SetFont('helvetica','B',10);
			$pdf->Cell(0,6,'MATERIAL INSTALADO',1,0,'C',true);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetFillColor(255,255,255);
			$pdf->SetTextColor(0,0,0);
			$pdf->Ln(7.5);

			// Colors, line width and bold font
			$pdf->SetDrawColor(2,20,70);
			$pdf->SetFillColor(2, 20, 70);
			$pdf->SetTextColor(255, 255, 255);
			$pdf->SetFont('', 'B',10);
			
			// column titles
			$header = array('id', 'SKU','Código', 'Descripción', 'U.Med.', 'Cant.');
			$w = array(10, 20, 25, 106, 24, 15);
			$num_headers = count($header);
			for($i = 0; $i < $num_headers; ++$i) {
				$pdf->Cell($w[$i], 6.5, $header[$i], 1, 0, 'C', 1);
			};
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetFillColor(255,255,255);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('', '',10);
			$pdf->Ln(7.5);
        	// ---------------------------------------------------------			
			$total_material = 0;
			$items=sizeof($datos_material_json);
			foreach ($datos_material_json as $row) {
	
				$itemProducto ='id';
				$valorProducto = $row['id_producto'];
				$respuestaProducto = ControladorAjusteInventario::ctrDatosProducto($itemProducto, $valorProducto);
				$codigointerno = $respuestaProducto["codigointerno"];
				$sku = $respuestaProducto["sku"];
				$descripcion = substr(trim($respuestaProducto["descripcion"]),0,47);
				$medida = $respuestaProducto["medida"];
				$existe=number_format($row["cantidad"],2, '.',',');
				$total_material+=$row["cantidad"];
	
				$pdf->Cell(10, 6.5, $valorProducto, 1, 0, 'C', 1);
				$pdf->Cell(20, 6.5, $sku, 1, 0, 'C', 1);
				$pdf->Cell(25, 6.5, $codigointerno, 1, 0, 'C', 1);
				$pdf->Cell(106, 6.5, $descripcion, 1, 0, 'L', 1);      //42
				$pdf->Cell(24, 6.5, $medida, 1, 0, 'C', 1);
				$pdf->Cell(15, 6.5, $existe, 1, 0, 'C', 1);
	
				$pdf->Ln(7.2);
			}   //termina el foreach

				// Colors, line width and bold font
				$pdf->SetDrawColor(2,20,70);
				$pdf->SetFillColor(2, 20, 70);
				$pdf->SetTextColor(255, 255, 255);
				$pdf->SetFont('', 'B',10);

				$pdf->Cell(25, 6.5,'Total Items:', 1, 0, 'R', 1);
				$pdf->Cell(15, 6.5, $items, 1, 0, 'L', 1);			
				$pdf->Cell(145, 6.5,'Total Material:', 1, 0, 'R', 1);
				$pdf->Cell(15, 6.5, number_format($total_material,2, '.',','), 1, 0, 'C', 1);			
				$pdf->Ln();

		}


		// ---------------------------------------------------------
		//Close and output PDF document
		$pdf->Output('reporte_os.pdf', 'I');


//============================================================+
// END OF FILE
//============================================================+
}else{
	//include '../../../vistas/plantilla.php'; <td style="width:65px"><img src="../../../config/logotipo.png"></td>
	echo "no tienes acceso a este reporte.";
}


$reporteOS = new reportedeOrdendeServicio();
$reporteOS -> reporteOS();



?>