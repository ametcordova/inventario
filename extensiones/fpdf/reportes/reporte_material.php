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
            $this->Cell(30,6,iconv('UTF-8', 'ISO-8859-1','TELÉFONOS DE MÉXICO S.A. DE C.V.'),0,0,'C');
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
            
            $datos_material=array();$items=0;
            foreach($respuesta as $key => $val) {
                $id=$respuesta[$key]['id'];
                $os=$respuesta[$key]['ordenservicio'];
                $tel=$respuesta[$key]['telefono'];
                $factura=$respuesta[$key]['factura'];
                $datos_instalacion_json=json_decode($respuesta[$key]['datos_instalacion'],TRUE);		//decodifica los datos JSON 
                $datos_material_json=json_decode($respuesta[$key]['datos_material'],TRUE);		//decodifica los datos JSON 
                $datos_material = array_merge($datos_material, $datos_material_json);           //une en un solo array los 2 array

                if(!empty($datos_instalacion_json[0]['alfanumerico'])){

                    $itemProducto ='alfanumerico';
                    $valorOnt = $datos_instalacion_json[0]['alfanumerico'];
                    $respuestaProducto = ControladorOServicios::ctrDatosOnt($itemProducto, $valorOnt);
                    $idproducto = !isset($respuestaProducto["idproducto"])?"":$respuestaProducto["idproducto"];
                    $codigointerno = !isset($respuestaProducto["codigointerno"])?"":$respuestaProducto["codigointerno"];
                    $descripcion = !isset($respuestaProducto["descripcion"])?"":substr(trim($respuestaProducto["descripcion"]),0,47);

                    // $pdf->Cell(60,6,iconv('UTF-8', 'ISO-8859-1','NÚMERO DE SERIE:'),0,0,'R',true);
                    // $pdf->Cell(100,6,iconv('UTF-8', 'ISO-8859-1',$datos_instalacion_json[0]['numeroserie']),1,0,'L',true);
                    $pdf->Cell(60,6,iconv('UTF-8', 'ISO-8859-1','DESCRIPCIÓN ONT:'),0,0,'R',true);
                    $pdf->Cell(110,6,iconv('UTF-8', 'ISO-8859-1',$idproducto.' - '.$codigointerno.' - '. $descripcion),1,0,'L',true);
                    $pdf->Ln();
                    $pdf->Cell(60,6,iconv('UTF-8', 'ISO-8859-1','ALFANÚMERICO:'),0,0,'R',true);
                    $pdf->Cell(110,6,$datos_instalacion_json[0]['alfanumerico'],1,0,'L',true);
                    $pdf->Ln();
                    $pdf->Cell(60,6,iconv('UTF-8', 'ISO-8859-1','ID/OS/TEL:'),0,0,'R',true);
                    $pdf->Cell(110,6,iconv('UTF-8', 'ISO-8859-1',$id.' / '.$os.' / '.$tel),1,0,'L',true);
                    $pdf->Ln();
                    $pdf->Cell(60,6,iconv('UTF-8', 'ISO-8859-1','FACTURA:'),0,0,'R',true);
                    $pdf->Cell(110,6,iconv('UTF-8', 'ISO-8859-1',$factura),1,0,'L',true);
                    $pdf->Ln(10);
                    $items++;
                }
            }
/************************************************************************************************/
// ------------ A PARTIR DE AQUIE ES EL CONCENTRADO DE MATERIAL INSTALADO-------------------------
/************************************************************************************************/        
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
            $header = array('#','id', 'SKU','Código', 'Descripción', 'U.Med.', 'Cant.');
            $w = array(8,10,18, 22, 102, 20, 16);
            $num_headers = count($header);
            for($i = 0; $i < $num_headers; ++$i) {
                $pdf->Cell($w[$i], 6.5, iconv('UTF-8', 'ISO-8859-1',$header[$i]), 1, 0, 'C', 1);
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

            $total_material = 0; $ont=$i=0;
            foreach ($newArray as $key => $value) {

                $itemProducto ='id';
                $valorProducto = $key;
                $respuestaProducto = ControladorAjusteInventario::ctrDatosProducto($itemProducto, $valorProducto);
                $codigointerno = $respuestaProducto["codigointerno"];
                $sku = $respuestaProducto["sku"];
                $descripcion = substr(trim($respuestaProducto["descripcion"]),0,47);
                $medida = $respuestaProducto["medida"];
                $cant=number_format($value,2, '.',',');
                $total_material+=$value;

                $pdf->Cell(8, 6.5, ++$i, 1, 0, 'C', 1);
                $pdf->Cell(10, 6.5, $valorProducto, 1, 0, 'C', 1);
                $pdf->Cell(18, 6.5, $sku, 1, 0, 'C', 1);
                $pdf->Cell(22, 6.5, $codigointerno, 1, 0, 'C', 1);
                $pdf->Cell(102, 6.5, iconv('UTF-8', 'ISO-8859-1',$descripcion), 1, 0, 'L', 1);      //42
                $pdf->Cell(20, 6.5, $medida, 1, 0, 'C', 1);
                $pdf->Cell(16, 6.5, $cant, 1, 0, 'C', 1);

                $pdf->Ln(7.2);
                if($respuestaProducto["conseries"]==1){
                    $ont+=$cant;
                }
                
            }   //termina el foreach

            // Colors, line width and bold font
            $pdf->SetFillColor(0, 95, 100, 0);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('', 'B',10);

            $pdf->Cell(36,6.5,$items.' O.S.', 1, 0, 'C', 1);
            $pdf->Cell(22,6.5,$ont.' ONT.', 1, 0, 'C', 1);
            $pdf->Cell(122, 6.5,'Total Material:', 1, 0, 'R', 1);
            $pdf->Cell(16, 6.5, number_format($total_material,2, '.',','), 1, 0, 'C', 1);

            $reporteos="reporteos-".$factura."pdf";

            $pdf->Output('I',$reporteos);
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