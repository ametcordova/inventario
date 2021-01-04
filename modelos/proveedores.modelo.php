<?php

require_once "conexion.php";

class ModeloProveedores{

/*=============================================
	CREAR CLIENTE
=============================================*/
static public function mdlIngresarProveedor($tabla, $datos){
	try{

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre, rfc, direccion, codpostal, ciudad, email, telefono, contacto, tel_contacto, email_contacto, estatus) VALUES (:nombre, :rfc, :direccion, :codpostal, :ciudad, :email, :telefono, :contacto, :tel_contacto, :email_contacto, :estatus)");

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":rfc", $datos["rfc"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":codpostal", $datos["codpostal"], PDO::PARAM_INT);
		$stmt->bindParam(":ciudad", $datos["ciudad"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":contacto", $datos["contacto"], PDO::PARAM_STR);
		$stmt->bindParam(":tel_contacto", $datos["tel_contacto"], PDO::PARAM_STR);
		$stmt->bindParam(":email_contacto", $datos["email_contacto"], PDO::PARAM_STR);
		$stmt->bindParam(":estatus", $datos["estatus"], PDO::PARAM_INT);

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
	MOSTRAR PROVEEDORES
=============================================*/
static public function mdlMostrarProveedores($tabla, $item, $valor){
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
	ACTUALIZAR PROVEEDOR
=============================================*/
 static public function mdlEditarProveedor($tabla, $datos){
	 try{

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre= :nombre, rfc= :rfc, direccion= :direccion, codpostal= :codpostal, ciudad= :ciudad, email= :email, telefono= :telefono, contacto= :contacto, tel_contacto= :tel_contacto, email_contacto= :email_contacto, estatus= :estatus WHERE id= :id");

		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":rfc", $datos["rfc"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
        $stmt->bindParam(":codpostal", $datos["codpostal"], PDO::PARAM_INT);
		$stmt->bindParam(":ciudad", $datos["ciudad"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":contacto", $datos["contacto"], PDO::PARAM_STR);
		$stmt->bindParam(":tel_contacto", $datos["tel_contacto"], PDO::PARAM_STR);
		$stmt->bindParam(":email_contacto", $datos["email_contacto"], PDO::PARAM_STR);
		$stmt->bindParam(":estatus", $datos["estatus"], PDO::PARAM_INT);
        
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
	ELIMINAR CLIENTE PROVEEDOR
=============================================*/
static public function mdlEliminarProveedor($tabla, $datos){
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