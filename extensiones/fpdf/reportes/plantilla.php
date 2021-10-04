<?php
require_once('../fpdf.php');

class PDF extends FPDF{

function Header(){
    global $w;
    global $img;
    global $razonsocial;
    global $ubicacion;
    global $tel_alm;
    global $email_alm;
    global $nomtecnico;
    global $tabla;
    global $fechahoy;
    $this->AddLink();
    $this->Image($img,6,5,30,0,'','www.fipabide.com/nunosco');
    $this->SetFont('Arial','B',18);
    $this->Ln(-1);
    $w = $this->GetStringWidth($razonsocial)+6;
    $this->SetX((210-$w)/2);    
    $this->Cell($w,0,$razonsocial,0,1,'C','');
    $this->Ln();
    $this->SetFont('helvetica', 'B', 8);
    //$this->Cell(0, 10, 'Av. Rio Coatan No. 504, Col. 24 de Junio, Tuxtla Gutiérrez, Chiapas.    Teléfono: (961) 1-40-71-19   brunonunosco1998@gmail.com', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    $this->Cell(0, 10, utf8_decode("          ".$ubicacion."  ".$tel_alm."  ".$email_alm), 0, false, 'C', 0, '', 0, false, 'M', 'M');
    $this->Ln(5);
    $this->SetFont('helvetica', 'B', 9.5);
    //$this->Cell(0, 10, 'Reporte de Inventario del Técnico del Almacén '.$tabla." al $fechahoy", 0, false, 'C', 0, '', 0, false, 'M', 'M');
    $this->Ln(2);
    $this->MultiCell(0, 5, utf8_decode('Reporte de Existencia del Técnico '.$nomtecnico." en ".$tabla. " al $fechahoy"),'B','C',false);
    $this->Ln(3);
    
    // Colors, line width and bold font
    $this->SetFillColor(127);
    $this->SetTextColor(255, 255, 255);
    $this->SetFont('', 'B',10);
    
    // column titles
    $header = array('SKU', 'Código', 'Descripción', 'U.Med.', 'Exist', 'Fisico', 'Dif.');
    $w = array(20, 21, 95, 20, 13.5, 13, 13.5);
    $num_headers = count($header);
    for($i = 0; $i < $num_headers; ++$i) {
        $this->Cell($w[$i], 6.5, utf8_decode($header[$i]), 1, 0, 'C', 1);
    };

    $this->Ln();

}
/********************************************************************/

function Footer(){
    global $nombre_archivo;
    $this->SetY(-12);
    $this->SetFont('Arial','I',9);
    $this->AddLink();
    $this->Cell(5,10,'www.fipabide.com/nunosco',0,0,'L');
    $this->SetFont('Arial','I',9);
    $this->Cell(0,10,utf8_decode('Página '.$this->PageNo().' de {nb}'),0, 0, 'C', 0, '', 0, false, 'T', 'M');
    $this->Cell(5,10,$nombre_archivo,0,0,'R');
}


}   //fin de la clase inicial

