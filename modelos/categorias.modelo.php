<?php

require_once "conexion.php";

class ModeloCategorias{

/*=============================================
	CREAR CATEGORIA
=============================================*/
static public function mdlIngresarCategoria($tabla, $datos){
try{
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(categoria) VALUES (:categoria)");

		$stmt->bindParam(":categoria", $datos, PDO::PARAM_STR);

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
	MOSTRAR CATEGORIAS
=============================================*/
static public function mdlMostrarCategorias($tabla, $item, $valor){
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
	EDITAR CATEGORIA
=============================================*/
public function mdlEditarCategoria($tabla, $datos){
    try{
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET categoria = :categoria WHERE id = :id");

		$stmt -> bindParam(":categoria", $datos["categoria"], PDO::PARAM_STR);
		$stmt -> bindParam(":id", $datos["id"], PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt = null;

    }catch(Exception $e) {
                die($e->getMessage());
    }
}
/*=============================================
	BORRAR CATEGORIA
=============================================*/
public function mdlBorrarCategoria($tabla, $datos){
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

}

