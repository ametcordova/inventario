<?php
 
 
require_once "../../../controllers/paquetes.controller.php";
require_once "../../../models/paquetes.model.php";
 
require_once "../../../controllers/terminals.controller.php";
require_once "../../../models/terminals.model.php";
 
include "../../../vendor/setasign/fpdf/fpdf.php";
 
class imprimirTicket{
public $ticket;
 
public function impresionTicket(){
 
  
    $itemTicket = "folio";
    $valorTicket = $this->ticket;
 
    $respuestaTicket = ControladorPaquetes::ctrMostrarPaquetes($itemTicket, $valorTicket);
 
    $itemOrigen = "idterminales";
    $valorOrigen = $respuestaTicket["idorigen"];
    $respuestaOrigen  = ControladorTerminales::ctrMostrarTerminales($itemOrigen, $valorOrigen);
 
    $itemDestino = "idterminales";
    $valorDestino = $respuestaTicket["iddestino"];
    $respuestaDestino  = ControladorTerminales::ctrMostrarTerminales($itemDestino, $valorDestino);
 
    $paquetes = json_decode($respuestaTicket["contenido"], true);
 
    $pdf = new FPDF($orientation='P',$unit='mm', array(50,350));
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',13);    //Letra Arial, negrita (Bold), tam. 20
    $textypos = 5;
    $pdf->setY(2);
    $pdf->setX(1);
    $pdf->Cell(5,$textypos,"AUTOBUSES EXAL");
    $pdf->SetFont('Arial','',5);    //Letra Arial, negrita (Bold), tam. 20
    $textypos+=6;
    $pdf->setX(1);
    $pdf->Cell(1,$textypos,'___________________________________________________');
    $textypos+=6;
    $pdf->setX(1);
    $pdf->Image('exal.png',1,10,-180);
    $pdf->setX(1);
    $textypos+=25;
    $pdf->Cell(1,$textypos,'___________________________________________________');
    $textypos+=13;
    $pdf->SetFont('Arial','B',16);    //Letra Arial, negrita (Bold), tam. 20
    $pdf->Cell(5,$textypos,"FOLIO: $respuestaTicket[folio]");
    $pdf->setX(1);
    $textypos+=13;
    $pdf->SetFont('Arial','',8); 
    $pdf->Cell(5,$textypos,"Fecha: $respuestaTicket[fecha]");
    $textypos+=10;
    $pdf->setX(1); 
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(5,$textypos,"Origen:");
    $textypos+=10;
    $pdf->SetFont('Arial','',8);
    $pdf->setX(1);
    $pdf->Cell(5,$textypos,"$respuestaOrigen[terminal]");
 
    $textypos+=10;
    $pdf->setX(1); 
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(5,$textypos,"Destino:");
    $textypos+=10;
    $pdf->SetFont('Arial','',8);
    $pdf->setX(1);
    $pdf->Cell(5,$textypos,"$respuestaDestino[terminal]");
 
    $textypos+=6;
    $pdf->setX(1);
    $pdf->Cell(1,$textypos,'___________________________________________________');
    $textypos+=10;
 
    $pdf->setX(1); 
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(5,$textypos,"Envia: $respuestaTicket[envia]");
    $textypos+=10;
    $pdf->SetFont('Arial','',8);
    $pdf->setX(1);
    $pdf->Cell(5,$textypos,"Recibe: $respuestaTicket[recibe]");
    $textypos+=10;
    $pdf->setX(1);
    $pdf->Cell(1,$textypos,"Telefono: $respuestaTicket[telefono]");
    $textypos+=5;
    //Paquetes
        foreach ($paquetes as $key => $item){
            $valorTipo = $item["tipo"];
            $valorCantidad = $item["cantidad"];
            $valorPrecio = number_format($item["precio"],2);
            $valorDescripcion = $item["descripcion"];
            $valorValor = number_format($item["valor"],2);
 
            $pdf->SetFont('Arial','',6);    //Letra Arial, negrita (Bold), tam. 20
            $textypos+=6;
            $pdf->setX(2);
            $pdf->Cell(1,$textypos,"Cant. $valorCantidad, Tipo: $valorTipo\n");
            $textypos+=6;
            $pdf->setX(2);
            $pdf->Cell(1,$textypos,"$valorDescripcion\n");
 
            $textypos+=6;
            $pdf->setX(2);
            $pdf->Cell(1,$textypos,"Precio: $ $valorPrecio\n");
 
            $textypos+=6;
            $pdf->setX(2);
            $pdf->Cell(1,$textypos,"Aprox.  $ $valorValor\n");
            $textypos+=6;
            $pdf->setX(1);
            
        }
        $textypos+=6;
        $pdf->setX(1);
        $pdf->Cell(1,$textypos,"Seguro pagado: $respuestaTicket[seguro].\n");
 
        $textypos+=6;
        $pdf->setX(1);
        $pdf->Cell(1,$textypos,"Estado de pago: $respuestaTicket[pagado].\n");
 
        $textypos+=6;
        $pdf->setX(1);
        $pdf->Cell(1,$textypos,"Estado del envio: $respuestaTicket[estado].\n");
        
        $pdf->setX(1);
        $textypos+=10;
        $pdf->SetFont('Arial','B',10);    //Letra Arial, negrita (Bold), tam. 20
        $pdf->Cell(5,$textypos,"Costo: $ $respuestaTicket[costo]\n");
        
 
    
       
        $pdf->output();
    }
}
 
$ticket = new imprimirTicket();
$ticket -> ticket = $_GET["ticket"];
$ticket -> impresionTicket();
 
 
?>