<?php
session_start();
if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"]=="ok"){
setlocale(LC_ALL,"es_ES");
ob_start();

require_once "../../../controladores/reporteinventario.controlador.php";
require_once "../../../modelos/reporteinventario.modelo.php";
require_once "../../../funciones/funciones.php";
include 'plantilla.php';

$tabla=$_GET["idNomAlma"];    
$idalmacen=$_GET["idNumAlma"];
$campo = "id_tecnico";
$valor = $_GET["idNumTec"];
$item="id";
$tablatecnicos="tecnicos";
$encabezado = ControladorInventario::ctrReporteInventarioAlmacen($idalmacen);
$nombretecnico=mdlVerTecnicos($tablatecnicos, $item, $valor);
$nomtecnico=$nombretecnico["nombre"];

if($tabla=="alm_villah" || $tabla=="alm_comal"){
    $img="images/logo_siesur.jpg";
    $razonsocial='S E I S U R';
}else{
    $img="images/logo_nuno.png";
    $razonsocial='N U N O S C O';
}

$ubicacion=$encabezado["ubicacion"];
$tel_alm=$encabezado["telefono"];
$email_alm=$encabezado["email"];
$fechahoy=fechaHoraMexico(date("d-m-Y G:i:s"));

$pdf = new PDF('P', 'mm','letter','UTF-8', false);
$title = 'NUNOSCO';
$pdf->SetCreator('AdminINV');
$pdf->SetAuthor('Amet Córdova @Kordova');
$pdf->SetTitle($title);     //se puede quitar
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',9);
//TRAER LOS DATOS DEL ALMACEN SELECCIONADO
$respuestaInventario = ControladorInventario::ctrReporteInventarioPorTecnico($tabla, $campo, $valor, $idalmacen);
if($respuestaInventario){

// ---------------------------------------------------------
$cantEntra=0;

foreach ($respuestaInventario as $row) {
    $existe=number_format($row["existe"]);
    $descripcion=substr($row['descripcion'],0,47);

        $pdf->Cell($w[0],6,$row['id_producto'],'LRB');
        $pdf->Cell($w[1],6,$row['sku'],'LRB');
        $pdf->Cell($w[2],6,$row['codigointerno'],'LRB');
        $pdf->Cell($w[3],6,utf8_decode($descripcion),'LRB',0,'L');
        $pdf->Cell($w[4],6,$row['medida'],'LRB',0,'L');
        $pdf->Cell($w[5],6,$existe,'LRB',0,'R');
        $pdf->Cell($w[6],6,'','LRB',0,'R');
        $pdf->Cell($w[7],6,'','LRB',0,'R');
        $pdf->Ln();

    $cantEntra+=$row['existe'];

}   //termina el foreach

    $pdf->Cell($w[0]+$w[1]+$w[2]+$w[3]+$w[4],6,'Totales:','LRB',0,'R');
    $pdf->Cell($w[5],6,number_format($cantEntra),'LRB',0,'R');
    $pdf->Cell($w[6],6,'','LRB',0,'R');
    $pdf->Cell($w[7],6,'','LRB',0,'R');
    $pdf->Ln(8);

    $pdf->Cell(196,5,'Observaciones:','B',0,'L');
    $pdf->Ln(12);

    $pdf->Cell(87,5,utf8_decode('Nombre y firma Técnico'),'LRT',0,'C');
    $pdf->Cell(22,5,utf8_decode(''),0,0,'C');
    $pdf->Cell(87,5,utf8_decode('Nombre y firma quien Revisa'),'LRT',1,'C');
    $pdf->Cell(87,10,utf8_decode(''),'LRB',0,'C');
    $pdf->Cell(22,10,utf8_decode(''),0,0,'C');
    $pdf->Cell(87,10,utf8_decode(''),'LRB',1,'C');

    $pdf->Ln();

// ---------------------------------------------------------
    $hoy1 = date("Ymd");    
    $hoy2 = date("His");
    $fecha_elabora=$hoy1.$hoy2;
    $nombre_archivo="invxtecnico".$fecha_elabora.".pdf";   //genera el nombre del archivo para descargarlo
    $pdf->Output('I',$nombre_archivo);

}else{

    $pdf->Ln(5);
    $pdf->Cell(196,8,'NO EXISTE INFORMACION CON LOS DATOS SOLICITADOS','1',0,'C');
    $hoy1 = date("Ymd");    
    $hoy2 = date("His");
    $fecha_elabora=$hoy1.$hoy2;
    $nombre_archivo="invxtecnico".$fecha_elabora.".pdf";   //genera el nombre del archivo para descargarlo
    $pdf->Output('I',$nombre_archivo);
   
}   //fin del IF





}// fin de la clase
?>

<?php
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