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
            // Logo
            //$this->Image('logo.png',10,8,33);
            // Arial bold 15
            $this->SetFont('Arial','B',16);
            // Movernos a la derecha
            $this->Cell(80);    // w-ancho h-alto txt-texto 0,1 ó LTRB-border 0,1,2-Posicion actual L,C,R-Alineacion true,false-fondo
            // Título
            $this->Cell(30,6,utf8_decode('TELÉFONOS DE MÉXICO S.A. DE C.V.'),0,0,'C');
            // Salto de línea
            $this->Ln(5);
            $this->SetFont('Arial','B',10);
            $this->Cell(80);
            $this->Cell(0,8,'PLANTA EXTERIOR',0,0,1);
            $this->Ln();
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
        $respuesta = ControladorOServicios::ctrObtenerOS($tabla, $campo, $valor);
        
        if($respuesta){
            
        // Creación del objeto de la clase heredada
        //$pdf = new PDF();
        $pdf = new PDF('P', 'mm', 'Letter', true, 'UTF-8', false);
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial','',12);
        $pdf->AddPage();

        $fechaInstalacion=date("d-m-Y", strtotime($respuesta['fecha_instalacion']));
        $factura=$respuesta['factura'];
        
        $datos_instalacion_json=json_decode($respuesta['datos_instalacion'],TRUE);		//decodifica los datos JSON 
        $datos_material_json=json_decode($respuesta['datos_material'],TRUE);		//decodifica los datos JSON 
        //echo $datos_instalacion_json[0]['numpisaplex'];

        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(255,0,0);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetLineWidth(.3);     //GRUESO DE LOS BORDES
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(0,6,'ORDEN DE SERVICIO',1,0,'C',true);
        $pdf->Ln(7.5);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln(2);

        $pdf->Cell(10,6,'',0,0,'R',true);
        $pdf->Cell(23,6,utf8_decode('TELEFONO:'),0,0,'L',true);
        $pdf->Cell(28,6,$respuesta['telefono'],1,0,'L',true);
        $pdf->SetX(71);    
        $pdf->Cell(8,6,'',0,0,'R',true);
        $pdf->Cell(25,6,'FECHA:',0,0,'R',true);
        $pdf->Cell(30,6,$fechaInstalacion,1,0,'C',true);
        $pdf->SetX(140);    
        $pdf->Cell(10,6,'',0,0,'R',true);
        $pdf->Cell(25,6,'FACTURA:',0,0,'R',true);
        $pdf->Cell(20,6,$factura,1,0,'C',true);
        $pdf->Ln(10);

        $pdf->Cell(33,6,utf8_decode('NÚMERO DE OS:'),0,0,'L',true);
        $pdf->Cell(30,6,$respuesta['ordenservicio'],1,0,'C',true);
        $pdf->SetX(75);
        $pdf->Cell(21,6,'PISAPLEX:',0,0,'L',true);
        $pdf->Cell(25,6,$datos_instalacion_json[0]['numpisaplex'],1,0,'C',true);
        $pdf->SetX(123);
        $pdf->Cell(12,6,'TIPO:',0,0,'L',true);
        $pdf->Cell(24,6,$datos_instalacion_json[0]['numtipo'],1,0,'C',true);
        $pdf->SetX(160);
        $pdf->Cell(24,6,'FOLIO TEC:',0,0,'C',true);
        $pdf->Cell(20,6,'',1,0,'C',true);
        $pdf->Ln(10);

        $pdf->Cell(55,6,'NOMBRE DEL CONTRATANTE:',0,0,'R',true);
        $pdf->Cell(140,6,utf8_decode($respuesta['nombrecontrato']),1,0,'L',true);
        $pdf->Ln();
        $pdf->Cell(55,6,utf8_decode('DIRECCIÓN:'),0,0,'R',true);
        $pdf->Cell(140,6,utf8_decode($datos_instalacion_json[0]['direccionos']),1,0,'L',true);
        // $pdf->Ln();
        // $pdf->Cell(55,6,utf8_decode('ENTRE CALLES:'),0,0,'R',true);
        // $pdf->Cell(120,6,utf8_decode(''),1,0,'L',true);
        $pdf->Ln();
        $pdf->Cell(55,6,utf8_decode('COLONIA:'),0,0,'R',true);
        $pdf->Cell(140,6,utf8_decode($datos_instalacion_json[0]['coloniaos']),1,0,'L',true);
        $pdf->Ln(8);

        $pdf->Cell(55,6,utf8_decode('EDIFICIO:'),0,0,'R',true);
        $pdf->Cell(50,6,utf8_decode(''),1,0,'L',true);
        $pdf->SetX(125);
        $pdf->Cell(15,6,'DEPTO:',0,0,'L',true);
        $pdf->Cell(25,6,'',1,0,'C',true);
        $pdf->Ln(11);

        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(178,2,utf8_decode('NAVEGACIÓN'),0,'B','R',true);
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
        $pdf->Ln();

        $pdf->SetFont('Arial','',8);
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
        $pdf->Ln(9);

        $pdf->SetDrawColor(128,0,0);
        $pdf->SetFillColor(255,0,0);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetLineWidth(.3);     //GRUESO DE LOS BORDES
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(0,6,'DATOS DE LA ONT INSTALADA',1,0,'C',true);
        $pdf->Ln(7.5);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln(5);

        $pdf->Cell(60,6,utf8_decode('NÚMERO DE SERIE:'),0,0,'R',true);
        $pdf->Cell(100,6,utf8_decode($datos_instalacion_json[0]['numeroserie']),1,0,'L',true);
        $pdf->Ln();
        $pdf->Cell(60,6,utf8_decode('ALFANÚMERICO:'),0,0,'R',true);
        $pdf->Cell(100,6,$datos_instalacion_json[0]['alfanumerico'],1,0,'L',true);
        $pdf->Ln();
        $pdf->Cell(60,6,utf8_decode('KEY:'),0,0,'R',true);
        $pdf->Cell(100,6,utf8_decode(''),1,0,'L',true);
        $pdf->Ln(15);


        $pdf->SetFont('Arial','',8);
        $pdf->Cell(196,5,utf8_decode($datos_instalacion_json[0]['nombrefirma']),0,0,'C',true);
        $pdf->Ln();
        $pdf->Cell(62,6,utf8_decode($respuesta['tecnico']),1,0,'C',true);
        $pdf->SetX(77);
        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(62,5,utf8_decode('RECIBÍ SERVICIO DE CONFORMIDAD:'),'LTR',0,'C',true);
        $pdf->SetX(144);
        $pdf->Cell(62,5,utf8_decode('NO DESEO EL SERVICIO.:'),'LTR',0,'C',true);
        $pdf->Ln();
        $pdf->Cell(62,4,utf8_decode('NOMBRE Y FIRMA DEL INSTALADOR'),'LRB',0,'C',true);
        $pdf->SetX(77);
        $pdf->Cell(62,4,utf8_decode('NOMBRE Y FIRMA (CLIENTE)'),'LRB',0,'C',true);
        $pdf->SetX(144);
        $pdf->Cell(62,4,utf8_decode('NOMBRE Y FIRMA (CLIENTE)'),'LRB',0,'C',true);
        $pdf->Ln(10);

        $pdf->SetDrawColor(128,0,0);
        $pdf->SetFillColor(255,0,0);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetLineWidth(.3);     
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(0,6,'MATERIAL INSTALADO',1,0,'C',true);
        $pdf->Ln(7.5);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln(5);


        // Colors, line width and bold font
        $pdf->SetFillColor(0, 95, 100, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('', 'B',10);
        
        // column titles
        $header = array('id', 'SKU','Código', 'Descripción', 'U.Med.', 'Cant.');
        $w = array(10, 20, 25, 106, 20, 15);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $pdf->Cell($w[$i], 6.5, utf8_decode($header[$i]), 1, 0, 'C', 1);
        };
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('', '',10);
        $pdf->Ln(7.5);
        // ---------------------------------------------------------
        $total_material = 0;
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
            $pdf->Cell(106, 6.5, utf8_decode($descripcion), 1, 0, 'L', 1);      //42
            $pdf->Cell(20, 6.5, utf8_decode($medida), 1, 0, 'C', 1);
            $pdf->Cell(15, 6.5, $existe, 1, 0, 'C', 1);

            $pdf->Ln(7.2);
        }   //termina el foreach

            // Colors, line width and bold font
            $pdf->SetFillColor(0, 95, 100, 0);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('', 'B',10);

            $pdf->Cell(181, 6.5,'Total Material:', 1, 0, 'R', 1);
            $pdf->Cell(15, 6.5, number_format($total_material,2, '.',','), 1, 0, 'C', 1);

        $pdf->Output();
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