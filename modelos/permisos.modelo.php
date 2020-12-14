<?php
date_default_timezone_set('America/Mexico_City');
//require_once dirname( __DIR__ ).'/config/conexion.php';
require_once "conexion.php";
class ModeloPermisos{

/*=============================================
	REGISTRO DE PRODUCTO
=============================================*/
static public function mdlGuardarPermisos($tabla, $datajson, $usuario, $campo){
	try {      

		//GUARDA 
		
		$query = Conexion::conectar()->prepare("UPDATE $tabla SET $campo=:$campo WHERE id=:id");
		$query->bindParam(":id", $usuario, PDO::PARAM_INT);
		$query->bindParam(":".$campo, $datajson, PDO::PARAM_STR);
		
		$query->execute();

		   if($query){
			  return "ok";
		   }else{
			  return "error";
		   }
  
		  $query=null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
   }
}

/*=============================================
	OBTENER ACCESO SEGUN ID Y MODULO
=============================================*/
static public function mdlGetAccesos($tabla, $usuario, $module, $campo){
	try {      

		$query = Conexion::conectar()->prepare("SELECT JSON_EXTRACT($campo, '$.$module') AS acceso FROM $tabla WHERE id=:id");
		$query->bindParam(":id", $usuario, PDO::PARAM_INT);
		//$query->bindParam(":permisos", $datajson, PDO::PARAM_STR);
		
		$query->execute();

			return $query -> fetch();

		  $query=null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
   }
} 	//fin de la funcion

/*=======================================================
OBTENER ACCESO SEGUN ID Y MODULO PARA EL MENU PRINCIPAL
========================================================*/
static public function mdlGetPermisos($tabla, $usuario, $modulo){
	try {      

		$query = Conexion::conectar()->prepare("SELECT $modulo FROM $tabla WHERE id=:id");
		
		$query->bindParam(":id", $usuario, PDO::PARAM_INT);
		
		$query->execute();

			return $query -> fetch(PDO::FETCH_OBJ);

		  $query=null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
   }
} 	//fin de la funcion

/*=============================================
	OBTENER ACCESO SEGUN ID Y MODULO
=============================================*/
// static public function mdlGetPermisosCat($tabla, $usuario){
// 	try {      

// 		$query = Conexion::conectar()->prepare("SELECT catalogo FROM $tabla WHERE id=:id");
		
// 		$query->bindParam(":id", $usuario, PDO::PARAM_INT);
		
// 		$query->execute();

// 			return $query -> fetch(PDO::FETCH_OBJ);

// 		  $query=null;

// 	} catch (Exception $e) {
// 		echo "Failed: " . $e->getMessage();
//    }
// } 	//fin de la funcion

} //fin de la clase
?>