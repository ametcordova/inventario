<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Mexico_City');

require_once "conexion.php";

use PhpOffice\PhpSpreadsheet\Cell\CellAddress;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
require_once '../extensiones/vendor/autoload.php';
$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','.xls', '.xlsx');

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
}else{
        die("Archivo no permitido... REVISE.");
}

$num=0;
for ($row = 2; $row <= $highestRow; $row++){ $num++;?>
        <tr>
          <td><?php echo $worksheet->getCell("A".$row)->getValue();?></td></br>
        </tr> 
        
    <?php    
}
//****************************************************************************************** */     
?>
