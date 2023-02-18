<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Mexico_City');

require_once "../modelos/conexion.php";
require_once '../funciones/funciones.php';

use PhpOffice\PhpSpreadsheet\Cell\CellAddress;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
require_once '../extensiones/vendor/autoload.php';
$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','.xls', '.xlsx');
try{
    if (isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {
        $arr_file = explode('.', $_FILES['file']['name']);
        $extension = end($arr_file);
        if ('csv' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } elseif ('xls' == $extension){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
            //$highestColumn = $worksheet->getHighestColumn(); 
            //$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); 
        if(isset($_POST['aplicar']) && $_POST['aplicar']==1){
            $fact=$_POST['factura'];
            json_output(json_build(http_response_code(200), null, $fact));
        }else{
            $datos=$sincaptura=array();
            $flag=false;
            for ($row = 2; $row <= $highestRow; $row++){ 
                $os=$worksheet->getCell("A".$row)->getValue();
                $tel=$worksheet->getCell("B".$row)->getValue();

                $sql = "SELECT ordenservicio, telefono, factura, estatus FROM tabla_os WHERE ordenservicio=$os";
                $stmt = Conexion::conectar()->prepare($sql);
                $stmt->execute();
                $arr = $stmt->fetch(PDO::FETCH_ASSOC);
                
                    if($arr){

                        if($tel!=$arr['telefono']){
                            $datos[]=array('OS'=>$os, 'Telefono'=>$tel, 'OBS'=>$arr['telefono']);
                            $flag=true;
                        }

                        if($arr["factura"]!=''){
                            $datos[]=array('OS'=>$os, 'Telefono'=>$tel, 'OBS'=>$arr['factura']);
                        }else{
                            if(!$flag)
                                $datos[]=array('OS'=>$os, 'Telefono'=>$tel, 'OBS'=>'ENCONTRADO');
                        }

                    }else{
                        $sincaptura[]=array('OS'=>$os, 'Telefono'=>$tel, 'OBS'=>'SIN O.S.');
                    };
                    $flag=false;

            };      //fin del For

            //si no existen OS en la BD, se agrega al array
            if(!empty($sincaptura) || sizeof($sincaptura)>0){
                $datos=array_merge($datos,$sincaptura);    
            }
        }; 

        $datos=json_encode($datos);
        echo $datos;

    }else{
        //$resp=array(400=>'Archivo no permitido');
        //$datos=json_encode(http_response_code(400));
        //echo $datos;
        json_output(json_build(http_response_code(400), null, 'Archivo no Permitido'));
    }
} catch (Exception $e) {
    json_output(json_build(403, null, $e->getMessage()));
}    

//****************************************************************************************** */     
?>
