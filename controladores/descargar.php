<?php
//$filename = 'F200-A437.xlsx';
if (empty(strtoupper(trim($_GET['invoice'])))) {
    exit;
}
$filename = 'F200-' . strtoupper(trim($_GET['invoice'])) . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

readfile($filename);
