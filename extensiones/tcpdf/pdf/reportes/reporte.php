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
    $razonsocial='F I P A B I D E';
}

$ubicacion=$encabezado["ubicacion"];
$tel_alm=$encabezado["telefono"];
$email_alm=$encabezado["email"];
$fechahoy=fechaHoraMexico(date("d-m-Y G:i:s"));

$pdf = new PDF('P', 'mm','letter','UTF-8', false);
$title = 'NUNOSCO';
$pdf->SetTitle($title);     //se puede quitar
$pdf->AddPage();
$pdf->AliasNbPages();
//$pdf->SetFont('Arial','B',18);
// Title



//$pdf->Cell(190,12,'Hola Mundo!','B',1,'C',0);
//$pdf->Cell(190,12, utf8_decode('RUY AMET CÓRDOVA SANCHEZ'),0,0,'C');
$pdf->Output();








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