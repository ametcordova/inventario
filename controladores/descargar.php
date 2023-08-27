<?php
//$filename = 'F200-A437.xlsx';
if (empty(strtoupper(trim($_GET['invoice'])))) {
    exit;
}
//$xfile=htmlspecialchars($_GET['invoice'], ENT_QUOTES, 'UTF-8');
$file = getcwd()."/"."F200-".strtoupper(trim($_GET['invoice'])).".xlsx";
$filename = "F200-".strtoupper(trim($_GET['invoice'])).".xlsx";
$sizefile=filesize($file);

header('Pragma: public');
header('Expires: 0');
header('Cache-control: must-revalidate, post-check=0, pre-check=0');
header('Cache-control: public');
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename=\"".$filename."\"");
header('Content-Length: '.$sizefile);

//archivo esta en la misma ruta que el archivo PHP
readfile($file);
unlink($filename);
exit
?>

