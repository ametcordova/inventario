<?php
require_once dirname( __DIR__ ).'/config/conexion.php';

class ModeloFamilias{

/*=============================================
	CREAR FAMILIA
=============================================*/

static public function mdlIngresarFamilia($tabla, $datos){
	try{
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(familia,ultusuario) VALUES (:familia, :ultusuario)");

		$stmt->bindParam(":familia", $datos["familia"], PDO::PARAM_STR);
        $stmt->bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);
		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}
    }catch(Exception $e) {
		return $e->getMessage();
    }
		$stmt->close();
		$stmt = null;

}

/*=============================================
	EDITAR FAMILIA
=============================================*/

	public function mdlEditarFamilia($tabla, $datos){
    try{
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET familia = :familia,ultusuario = :ultusuario WHERE id = :id");

		$stmt -> bindParam(":familia", $datos["familia"], PDO::PARAM_STR);
		$stmt -> bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);
		$stmt -> bindParam(":id", $datos["id"], PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}
    }catch(Exception $e) {
		return $e->getMessage();
	}
		$stmt->close();
		$stmt = null;


}

/*=============================================
	MOSTRAR FAMILIAS
=============================================*/
static public function mdlMostrarFamilias($tabla, $item, $valor){
	try{
		if($item != null && $valor != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else if($valor!=null && $item == null) {

			$stmt = Conexion::conectar()->prepare("SELECT id, familia FROM $tabla WHERE familia LIKE '%".$valor."%' ");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}
    }catch(Exception $e) {
		return $e->getMessage();
    }
		$stmt -> close();

		$stmt = null;

}

/*=============================================
	LISTAR FAMILIAS
=============================================*/
static public function mdllistarFamilias($tabla, $item){
	try{

			//$stmt = Conexion::conectar()->prepare("SELECT id, familia, ultmodificacion FROM $tabla WHERE estado=1 ORDER BY $item ASC");
			$stmt = Conexion::conectar()->prepare("SELECT id, familia, ultmodificacion FROM familias WHERE estado=1 ORDER BY id ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();


    }catch(Exception $e) {
		return $e->getMessage();
    }
		$stmt -> close();

		$stmt = null;

}


/*=============================================
	BORRAR FAMILIA
=============================================*/

	public function mdlBorrarFamilia($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

/*=============================================
	ELIMINAR FAMILIA
=============================================*/

public function mdlEliminarFamilia($tabla, $item, $valor){

	//$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
	$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estado=0 WHERE id = :id");

	$stmt -> bindParam(":id", $valor, PDO::PARAM_INT);

	if($stmt -> execute()){

		return "success";
	
	}else{

		return "error";	

	}

	$stmt -> close();

	$stmt = null;

}


}

