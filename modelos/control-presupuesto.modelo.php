<?php
date_default_timezone_set('America/Mexico_City');
require_once "conexion.php";

class ModeloIngreso{

 
/*=============================================
	CREAR CAJA
=============================================*/
static public function mdlIngreso($tabla, $datos){
 try {
        
	$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(fecha_ingreso, concepto_ingreso, descripcion_ingreso, importe_ingreso, ultusuario) VALUES (:fecha_ingreso, :concepto_ingreso, :descripcion_ingreso, :importe_ingreso, :ultusuario)");

		$stmt->bindParam(":fecha_ingreso", $datos["fecha_ingreso"], PDO::PARAM_STR);
		$stmt->bindParam(":concepto_ingreso", $datos["concepto_ingreso"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion_ingreso", $datos["descripcion_ingreso"], PDO::PARAM_STR);
		$stmt->bindParam(":importe_ingreso", $datos["importe_ingreso"], PDO::PARAM_STR);
        $stmt->bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);
		if($stmt->execute()){
            
			 return "ok";
            
        }else{

			return "error";
		
		}

		$stmt = null;

 } catch (Exception $e) {
  echo "Failed: " . $e->getMessage();
 }
        
}
	
} //fin de la clase
