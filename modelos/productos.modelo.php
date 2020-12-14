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
        $estado=1;
		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item  ORDER BY id ASC");

			$stmt -> bindParam(":$item", $valor, PDO::PARAM_STR);
			//$stmt -> bindParam(":$estado", $estado, PDO::PARAM_INT);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE estado=$estado ORDER BY $orden ASC");
            
            //$stmt -> bindParam(":$estado", $estado, PDO::PARAM_INT);
            
			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	REGISTRO DE PRODUCTO
	=============================================*/
	static public function mdlIngresarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_categoria, id_medida, codigo, codigointerno, descripcion, imagen, stock, minimo, sku, precio_compra, precio_venta, conseries, ultusuario) VALUES (:id_categoria, :id_medida, :codigo, :codigointerno, :descripcion, :imagen, :stock, :minimo, :sku, :precio_compra, :precio_venta, :conseries, :ultusuario)");

		$stmt->bindParam(":id_categoria", 	$datos["id_categoria"], PDO::PARAM_INT);
		$stmt->bindParam(":id_medida", 		$datos["id_medida"], PDO::PARAM_INT);
		$stmt->bindParam(":codigo", 		$datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":codigointerno", 	$datos["codigointerno"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", 	$datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen", 		$datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", 			$datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":minimo", 		$datos["minimo"], PDO::PARAM_INT);
		$stmt->bindParam(":sku", 			$datos["sku"], PDO::PARAM_INT);
		$stmt->bindParam(":precio_compra", 	$datos["precio_compra"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_venta", 	$datos["precio_venta"], PDO::PARAM_STR);
		$stmt->bindParam(":conseries", 		$datos["conseries"], PDO::PARAM_STR);
		$stmt->bindParam(":ultusuario", 	$datos["ultusuario"], PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			return $datos["descripcion"];
			
		
		}

		$stmt = null;

	}

	/*=============================================
	EDITAR PRODUCTO
	=============================================*/
	static public function mdlEditarProducto($tabla, $datos){

	$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET id_categoria = :id_categoria, id_medida = :id_medida, codigo=:codigo, codigointerno = :codigointerno, descripcion = :descripcion, imagen = :imagen, stock = :stock, minimo = :minimo, sku =:sku, precio_compra = :precio_compra, precio_venta = :precio_venta, conseries = :conseries, ultusuario = :ultusuario WHERE id = :id");

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
		$stmt->bindParam(":precio_compra", $datos["precio_compra"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);
		$stmt->bindParam(":conseries", $datos["conseries"], PDO::PARAM_STR);
		$stmt->bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt = null;

	}

	/*=============================================
	BORRAR PRODUCTO
	=============================================*/

	static public function mdlEliminarProducto($tabla, $datos){
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

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	ACTUALIZAR PRODUCTO
	=============================================*/

	static public function mdlActualizarProducto($tabla, $item1, $valor1, $valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $valor, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR SUMA VENTAS
	=============================================*/	

	static public function mdlMostrarSumaVentas($tabla){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(ventas) as total FROM $tabla");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;
	}


}