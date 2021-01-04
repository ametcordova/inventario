<?php

require_once "conexion.php";

class ModeloClientes{

/*=============================================
	CREAR CLIENTE
=============================================*/

public function mdlIngresarCliente($tabla, $datos){
	try{

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre, rfc, email, telefono, direccion, fecha_nacimiento) 
														VALUES (:nombre, :rfc, :email, :telefono, :direccion, :fecha_nacimiento)");

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":rfc", $datos["rfc"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_nacimiento", $datos["fecha_nacimiento"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}
			

	}

/*=============================================
	MOSTRAR CLIENTES
	=============================================*/
static public function mdlMostrarClientes($tabla, $item, $valor){
	try{
		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}


		$stmt = null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}
	
	}	
    
/*=============================================
	EDITAR CLIENTE
=============================================*/
static public function mdlEditarCliente($tabla, $datos){
	try{
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre= :nombre, rfc= :rfc, email= :email, telefono= :telefono, direccion= :direccion, fecha_nacimiento= :fecha_nacimiento WHERE id= :id");

		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":rfc", $datos["rfc"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_nacimiento", $datos["fecha_nacimiento"], PDO::PARAM_STR);
        
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
	ELIMINAR CLIENTE
=============================================*/
static public function mdlEliminarCliente($tabla, $datos){

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

}  //fin de la clase




