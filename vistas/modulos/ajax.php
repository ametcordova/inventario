<?php
define ('DB_USER', "root");
define ('DB_PASSWORD', "");
define ('DB_DATABASE', "inventario");
define ('DB_HOST', "localhost");
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
$sql = "SELECT * FROM proveedores name LIKE '%".$_GET['q']."%'"; 
$result = $mysqli->query($sql);
$json = [];
while($row = $result->fetch_assoc()){
     $json[] = ['id'=>$row['id'], 'text'=>$row['nombre']];
}
echo json_encode($json);