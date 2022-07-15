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
        $valor = $_GET["idsfacturas"];
        
        //TRAER LOS DATOS DEL ALMACEN SELECCIONADO
        $respuesta = ControladorOServicios::ctrObtenerMaterialOS($tabla, $campo, $valor);
        
        if($respuesta){
            
            // Creación del objeto de la clase heredada
            //$pdf = new PDF();
            $pdf = new PDF('P', 'mm', 'Letter', true, 'UTF-8', false);
            $pdf->AliasNbPages();
            $pdf->SetFont('Arial','',12);
            $pdf->AddPage();

            $pdf->SetDrawColor(128,0,0);
            $pdf->SetFillColor(255,0,0);
            $pdf->SetTextColor(255,255,255);
            $pdf->SetLineWidth(.3);     //GRUESO DE LOS BORDES
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(0,6,'DATOS DE LA ONTs INSTALADAS',1,0,'C',true);
            $pdf->Ln(7.5);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln(1);
            
            $datos_material=array();
            foreach($respuesta as $key => $val) {
                $os=$respuesta[$key]['ordenservicio'];
                $tel=$respuesta[$key]['telefono'];
                $factura=$respuesta[$key]['factura'];
                $datos_instalacion_json=json_decode($respuesta[$key]['datos_instalacion'],TRUE);		//decodifica los datos JSON 
                $datos_material_json=json_decode($respuesta[$key]['datos_material'],TRUE);		//decodifica los datos JSON 
                $datos_material = array_merge($datos_material, $datos_material_json);           //une en un solo array los dif. array

                $pdf->Cell(60,6,utf8_decode('NÚMERO DE SERIE:'),0,0,'R',true);
                $pdf->Cell(100,6,utf8_decode($datos_instalacion_json[0]['numeroserie']),1,0,'L',true);
                $pdf->Ln();
                $pdf->Cell(60,6,utf8_decode('ALFANÚMERICO:'),0,0,'R',true);
                $pdf->Cell(100,6,$datos_instalacion_json[0]['alfanumerico'],1,0,'L',true);
                $pdf->Ln();
                $pdf->Cell(60,6,utf8_decode('OS/TEL:'),0,0,'R',true);
                $pdf->Cell(100,6,utf8_decode($os.' / '.$tel),1,0,'L',true);
                $pdf->Ln();
                $pdf->Cell(60,6,utf8_decode('FACTURA:'),0,0,'R',true);
                $pdf->Cell(100,6,utf8_decode($factura),1,0,'L',true);
                $pdf->Ln(10);

            }
// -----------------------------------------------------------------------------------------        
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
            $pdf->Ln(0);

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
        
// -----------------------------------------------------------------------------------------
        
            $newArray = array();
            foreach($datos_material as $key => $value){
                if(array_key_exists($value['id_producto'], $newArray)) {
                    $newArray[$value['id_producto']] += $value['cantidad'];
                } else {
                    $newArray[$value['id_producto']] = $value['cantidad'];
                }
            }

            $total_material = 0;
            foreach ($newArray as $key => $value) {

                $itemProducto ='id';
                $valorProducto = $key;
                $respuestaProducto = ControladorAjusteInventario::ctrDatosProducto($itemProducto, $valorProducto);
                $codigointerno = $respuestaProducto["codigointerno"];
                $sku = $respuestaProducto["sku"];
                $descripcion = substr(trim($respuestaProducto["descripcion"]),0,47);
                $medida = $respuestaProducto["medida"];
                $existe=number_format($value,2, '.',',');
                $total_material+=$value;

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


            $pdf->Output('I','reporteos.pdf');
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


function cabecera(){
    
}
?>