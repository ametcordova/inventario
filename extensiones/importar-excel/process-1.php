<?php

use PhpOffice\PhpSpreadsheet\Cell\CellAddress;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
require_once '../vendor/autoload.php';
$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
if (isset($_POST["submit"])) {
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
        $highestColumn = $worksheet->getHighestColumn(); 
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); 
    }else{
        die("Archivo no permitido... REVISE.");
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorial - Aprende a leer un archivo excel y obtener sus datos con PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body>
    <div class="container-fluid">
        <h1 class="text-center">Leer un archivo en excel y obtener sus datos - <code>Resultado</code></h1>
        <div class="row pt-4">
            <div class="col-md-12">
                <?php
                echo '<table class="table table-sm table-bordered table-hover compact table-striped" style="font-size:10px;">
                <thead class="thead-dark">
                </thead>
                ' . "\n";
                for ($row = 1; $row <= $highestRow; ++$row) {
                    echo '<tr style="font-size:9.5px;">' . PHP_EOL;
                    for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                        //$coordenadas=CellAddress($col, $row);
                        $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                        //$value = $worksheet->getCell($col.$row)->getValue();
                        echo '<td>' . $value . PHP_EOL;
                    }
                    echo '</tr>' . PHP_EOL;
                }
                echo '</table>' . PHP_EOL;
/****************************************************************************************** */
                echo '<table>' . PHP_EOL;
                foreach ($worksheet->getRowIterator() as $row) {
                    echo '<tr>' . PHP_EOL;
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                                                                       //    even if a cell value is not set.
                                                                       // For 'TRUE', we loop through cells
                                                                       //    only when their value is set.
                                                                       // If this method is not called,
                                                                       //    the default value is 'false'.
                    foreach ($cellIterator as $cell) {
                        echo '<td>' .
                             $cell->getValue() .
                             '</td>' . PHP_EOL;
                    }
                    echo '</tr>' . PHP_EOL;
                }
                echo '</table>' . PHP_EOL;                
/****************************************************************************************** */     
echo '<table>' . "\n";
for ($row = 1; $row <= $highestRow; ++$row) {
    echo '<tr>' . PHP_EOL;
    for ($col = 'A'; $col != $highestColumn; ++$col) {

        echo '<td>' .$worksheet->getCell($col . $row)->getValue() .'</td>' . PHP_EOL;
    }
    echo '</tr>' . PHP_EOL;
}
echo '</table>' . PHP_EOL;           
/****************************************************************************************** */     
                ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>
</html>