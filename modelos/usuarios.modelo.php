<?php

require_once "conexion.php";

class ModeloUsuarios{

static Public function MdlMostrarUsuarios($tabla, $campo, $valor){
try {     
     if($campo !=null){    
        $stmt=Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $campo=:$campo");
        
        $stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $stmt->fetch();
        
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
    
    
/*=============================================
	REGISTRO DE USUARIO
=============================================*/
static public function mdlIngresarUsuario($tabla, $datos){
try {
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre, usuario, password, perfil, foto) VALUES (:nombre, :usuario, :password, :perfil, :foto)");

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);
		$stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
		$stmt->bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);
		$stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);


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
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, password = :password, perfil = :perfil, foto = :foto WHERE usuario = :usuario");

		$stmt -> bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt -> bindParam(":password", $datos["password"], PDO::PARAM_STR);
		$stmt -> bindParam(":perfil", $datos["perfil"], PDO::PARAM_STR);
		$stmt -> bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
		$stmt -> bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);

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

static public function mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2){
	try{
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE $item2 = :$item2");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":".$item2, $valor2, PDO::PARAM_STR);

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

		$stmt -> close();

		$stmt = null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
    }

}    
    
    
    
    
}   // fin de la clase