<?php

require_once "conexion.php";

class ModeloUsuarios{

static Public function MdlMostrarUsuarios($tabla, $campo, $valor){
try {     
     if($campo !=null){    
        $stmt=Conexion::conectar()->prepare("SELECT usu.*, tec.alm_asignado
		FROM $tabla usu 
		LEFT JOIN tecnicos tec ON tec.id=usu.user
		WHERE usu.$campo=:$campo");
        
        $stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
 	}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$stmt -> execute();

			return $stmt -> fetchAll();

	 }        
        
        
		$stmt=null;
		
} catch (Exception $e) {
	echo "Failed: " . $e->getMessage();
}
	
}
    
static Public function MdlListarUsuariosActivos($tabla, $item, $valor){
	try {     

			$stmt=Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item=:$item");
			
			$stmt->bindParam(":".$item, $valor, PDO::PARAM_INT);
			
			$stmt->execute();
			
			return $stmt->fetchAll();
			
			$stmt=null;
			
	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}
		
}
    
/*=============================================
	REGISTRO DE USUARIO
=============================================*/
static public function mdlIngresarUsuario($tabla, $datos){
try {
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre, usuario, password, perfil, user, foto) VALUES (:nombre, :usuario, :password, :perfil, :user, :foto)");

		$stmt->bindParam(":nombre", 	$datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":usuario", 	$datos["usuario"], PDO::PARAM_STR);
		$stmt->bindParam(":password", 	$datos["password"], PDO::PARAM_STR);
		$stmt->bindParam(":perfil", 	$datos["perfil"], PDO::PARAM_STR);
		$stmt->bindParam(":user", 		$datos["user"], PDO::PARAM_INT);
		$stmt->bindParam(":foto", 		$datos["foto"], PDO::PARAM_STR);


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
    
/*=============================================
	EDITAR USUARIO
=============================================*/
static public function mdlEditarUsuario($tabla, $datos){
try {	
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, password = :password, perfil = :perfil, user=:user, foto = :foto WHERE usuario = :usuario");

		$stmt -> bindParam(":nombre", 	$datos["nombre"], PDO::PARAM_STR);
		$stmt -> bindParam(":password", $datos["password"], PDO::PARAM_STR);
		$stmt -> bindParam(":perfil", 	$datos["perfil"], PDO::PARAM_STR);
		$stmt -> bindParam(":foto", 	$datos["foto"], PDO::PARAM_STR);
		$stmt -> bindParam(":user",		$datos["user"], PDO::PARAM_INT);
		$stmt -> bindParam(":usuario", 	$datos["usuario"], PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}


		$stmt = null;

} catch (Exception $e) {
	echo "Failed: " . $e->getMessage();
}
	
}    
    
    
    /*=============================================
	ACTUALIZAR USUARIO  <no pasa por el controlador>
	=============================================*/

static public function mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2, $logueado){
	try{
		//$logueado=1;
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1, logueado=:logueado WHERE $item2 = :$item2");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":".$item2, $valor2, PDO::PARAM_STR);
		$stmt -> bindParam(":logueado", $logueado, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt = null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}
	

}
/**********************************************************************************/
static public function mdlDesloguearse($tabla, $id){
try{	
	$logueado=0;
	$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET logueado=:logueado WHERE id = :id");

	$stmt -> bindParam(":id", $id, PDO::PARAM_INT);
	$stmt -> bindParam(":logueado", $logueado, PDO::PARAM_INT);

	if($stmt -> execute()){

		return "ok";
	
	}else{

		return "error";	

	}

	$stmt = null;

} catch (Exception $e) {
	echo "Failed: " . $e->getMessage();
}

}
/**********************************************************************************/
    
/*=============================================
	BORRAR USUARIO
=============================================*/
static public function mdlBorrarUsuario($tabla, $datos){
	try{

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt = null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
    }

}    
    
    
    
    
}   // fin de la clase