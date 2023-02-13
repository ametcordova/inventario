<?php

require_once "conexion.php";

class ModeloClientes{

/*=============================================
	CREAR CLIENTE
=============================================*/

static public function mdlIngresarCliente($tabla, $datos){
	try{

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_empresa, nombre, rfc, curp, num_int_ext, colonia, codpostal, ciudad, estado, regimenfiscal, act_economica, formadepago, email, telefono, direccion, fecha_creacion) VALUES (:id_empresa, :nombre, :rfc, :curp, :num_int_ext, :colonia, :codpostal, :ciudad, :estado, :regimenfiscal, :act_economica, :formadepago, :email, :telefono, :direccion, :fecha_creacion)");

		$stmt->bindParam(":id_empresa", $datos["id_empresa"], PDO::PARAM_INT);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":rfc", $datos["rfc"], PDO::PARAM_STR);
		$stmt->bindParam(":curp", $datos["curp"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":num_int_ext", $datos["num_int_ext"], PDO::PARAM_STR);
		$stmt->bindParam(":colonia", $datos["colonia"], PDO::PARAM_STR);
		$stmt->bindParam(":codpostal", $datos["codpostal"], PDO::PARAM_INT);
		$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":ciudad", $datos["ciudad"], PDO::PARAM_STR);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
		$stmt->bindParam(":regimenfiscal", $datos["regimenfiscal"], PDO::PARAM_INT);
		$stmt->bindParam(":act_economica", $datos["act_economica"], PDO::PARAM_STR);
		$stmt->bindParam(":formadepago", $datos["formadepago"], PDO::PARAM_INT);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_creacion", $datos["fecha_creacion"], PDO::PARAM_STR);

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
	MOSTRAR CLIENTES
=============================================*/
static public function mdlMostrarClientes($tabla, $item, $valor){
	try{
		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch(PDO::FETCH_ASSOC);

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
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET id_empresa=:id_empresa, nombre= :nombre, rfc= :rfc, curp=:curp, email= :email, telefono= :telefono, direccion= :direccion, num_int_ext=:num_int_ext, colonia=:colonia, codpostal=:codpostal, ciudad=:ciudad, estado=:estado, regimenfiscal=:regimenfiscal, act_economica=:act_economica, formadepago=:formadepago, id_usocfdi=:id_usocfdi, metodopago=:metodopago, fecha_creacion= :fecha_creacion, ultusuario=:ultusuario WHERE id= :id");

		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		$stmt->bindParam(":id_empresa", $datos["id_empresa"], PDO::PARAM_INT);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":rfc", $datos["rfc"], PDO::PARAM_STR);
		$stmt->bindParam(":curp", $datos["curp"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":num_int_ext", $datos["num_int_ext"], PDO::PARAM_STR);
		$stmt->bindParam(":colonia", $datos["colonia"], PDO::PARAM_STR);
		$stmt->bindParam(":codpostal", $datos["codpostal"], PDO::PARAM_STR);
		$stmt->bindParam(":ciudad", $datos["ciudad"], PDO::PARAM_STR);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
		$stmt->bindParam(":regimenfiscal", $datos["regimenfiscal"], PDO::PARAM_INT);
		$stmt->bindParam(":act_economica", $datos["act_economica"], PDO::PARAM_STR);
		$stmt->bindParam(":formadepago", $datos["formadepago"], PDO::PARAM_INT);
		$stmt->bindParam(":id_usocfdi", $datos["id_usocfdi"], PDO::PARAM_INT);
		$stmt->bindParam(":metodopago", $datos["metodopago"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_creacion", $datos["fecha_creacion"], PDO::PARAM_STR);
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




