<?php
date_default_timezone_set('America/Mexico_City');
require_once dirname( __DIR__ ).'/modelos/conexion.php';
class ModeloEmpresas{

/*=============================================
	LISTAR SALIDAS
=============================================*/
static public function mdlListarEmpresas($tabla, $usuario, $todes, $status){
try {
 	//$where='1=1';
    //tabla=facturaingreso 
	$where='tb1.status="'.$status.'" ';
    if($todes>0){
        $where.=' AND tb1.ultusuario="'.$usuario.'" ';
    }
	$where.=' ORDER BY tb1.id DESC';
  
	$sql="SELECT tb1.* FROM $tabla tb1 WHERE ".$where;
  
	$stmt = Conexion::conectar()->prepare($sql);
  
	$stmt -> execute();
  
	return $stmt -> fetchAll(PDO::FETCH_ASSOC);
  
    $stmt = null;

} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}

}	

/*=========================================================================================*/   






} //fin de la clase
