<?php

require_once "conexion.php";

class ModeloProductos{

	/*=============================================
	MOSTRAR PRODUCTOS  
	SELECT P.ID, P.DESCRIPCION, P.ID_CATEGORIA, C.ID,C.CATEGORIA, P.ID_MEDIDA, M.ID, M.MEDIDA FROM PRODUCTOS P 
	INNER JOIN CATEGORIAS C ON P.ID_CATEGORIA=C.ID 
	INNER JOIN MEDIDAS M ON P.ID_MEDIDA=M.ID
	=============================================*/
static public function mdlMostrarProductos($tabla, $item, $valor, $orden){
	try{
        //$estado=1;
		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id ASC");

			$stmt -> bindParam(":$item", $valor, PDO::PARAM_STR);
			//$stmt -> bindParam(":$estado", $estado, PDO::PARAM_INT);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY $orden ASC");
			//$stmt = Conexion::conectar()->prepare("SELECT * FROM `productos` ORDER BY `descripcion` ASC");
            
            //$stmt -> bindParam(":$estado", $estado, PDO::PARAM_INT);
            
			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt = null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}
	

}

/*=============================================
	REGISTRO DE PRODUCTO
=============================================*/
static public function mdlIngresarProducto($tabla, $datos){
	try{

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_categoria, id_medida, codigo, codigointerno, descripcion, imagen, stock, minimo, sku, esfo, escobre, esconstruccion, conseries, listar, ultusuario, estado) VALUES (:id_categoria, :id_medida, :codigo, :codigointerno, :descripcion, :imagen, :stock, :minimo, :sku, :esfo, :escobre, :esconstruccion, :conseries, :listar, :ultusuario, :estado)");

		$stmt->bindParam(":id_categoria", 	$datos["id_categoria"], PDO::PARAM_INT);
		$stmt->bindParam(":id_medida", 		$datos["id_medida"], PDO::PARAM_INT);
		$stmt->bindParam(":codigo", 		$datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":codigointerno", 	$datos["codigointerno"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", 	$datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen", 		$datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", 			$datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":minimo", 		$datos["minimo"], PDO::PARAM_INT);
		$stmt->bindParam(":sku", 			$datos["sku"], PDO::PARAM_INT);
		$stmt->bindParam(":esfo", 			$datos["esfo"], PDO::PARAM_INT);
		$stmt->bindParam(":escobre", 		$datos["escobre"], PDO::PARAM_INT);
		$stmt->bindParam(":esconstruccion",	$datos["esconstruccion"], PDO::PARAM_INT);
		$stmt->bindParam(":conseries", 		$datos["conseries"], PDO::PARAM_STR);
		$stmt->bindParam(":listar",			$datos["listar"], PDO::PARAM_INT);
		$stmt->bindParam(":ultusuario", 	$datos["ultusuario"], PDO::PARAM_INT);
		$stmt->bindParam(":estado", 		$datos["estado"], PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			return $datos["descripcion"];
			
		
		}

		$stmt = null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}
	

}

/*=============================================
	EDITAR PRODUCTO
=============================================*/
static public function mdlEditarProducto($tabla, $datos){
	try{

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET id_categoria = :id_categoria, id_medida = :id_medida, codigo=:codigo, codigointerno = :codigointerno, descripcion = :descripcion, imagen = :imagen, stock = :stock, minimo = :minimo, sku =:sku, conseries = :conseries, esfo = :esfo, escobre = :escobre, esconstruccion = :esconstruccion, listar = :listar, ultusuario = :ultusuario, estado=:estado WHERE id = :id");

		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		$stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
		$stmt->bindParam(":id_medida", $datos["id_medida"], PDO::PARAM_STR);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":codigointerno", $datos["codigointerno"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":minimo", $datos["minimo"], PDO::PARAM_INT);
		$stmt->bindParam(":sku", 	$datos["sku"], PDO::PARAM_STR);
		$stmt->bindParam(":conseries", $datos["conseries"], PDO::PARAM_STR);
		$stmt->bindParam(":esfo", $datos["esfo"], PDO::PARAM_INT);
		$stmt->bindParam(":escobre", $datos["escobre"], PDO::PARAM_INT);
		$stmt->bindParam(":esconstruccion", $datos["esconstruccion"], PDO::PARAM_INT);
		$stmt->bindParam(":listar", $datos["listar"], PDO::PARAM_INT);
		$stmt->bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);

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
	BORRAR PRODUCTO
=============================================*/
static public function mdlEliminarProducto($tabla, $datos){
	try{
        $estado=0;
		//$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estado=:estado WHERE id = :id");

		$stmt -> bindParam(":estado", $estado, PDO::PARAM_INT);
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

/*=============================================
	ACTUALIZAR PRODUCTO
=============================================*/
static public function mdlActualizarProducto($tabla, $item1, $valor1, $valor){
	try{

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $valor, PDO::PARAM_STR);

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