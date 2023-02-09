<?php

require_once "conexion.php";

class ModeloAlmacenes{

	/*=============================================
	CREAR ALMACEN
	=============================================*/

	static public function mdlIngresarAlmacen($tabla, $datos){
	 $nombre_nvo_almacen=strtolower($datos["nombre"]);
	 $tbl_kardex='kardex_'.$nombre_nvo_almacen.' ';

	 $crear_tb_almacen = Conexion::conectar()->prepare('
	 CREATE TABLE IF NOT EXISTS '.$nombre_nvo_almacen.'(
	 id int(5) NOT NULL AUTO_INCREMENT,
	 id_producto int(5) NOT NULL,
	 codigointerno varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
	 cant float(10,2) NOT NULL DEFAULT 0,
	 precio_compra float(10,2) NOT NULL default 0.00,
	 margen_utilidad float(6,2) NOT NULL default 0.00,
	 precio_venta float(10,2) NOT NULL default 0.00,
	 fecha_entrada date NOT NULL,
	 ultmodificacion timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	 ultusuario tinyint(1) DEFAULT NULL,
	 PRIMARY KEY (id)
	 )ENGINE=InnoDB DEFAULT CHARSET=utf8;');
	 
	 $crear_kardex_almacen = Conexion::conectar()->prepare('
	 	CREATE TABLE IF NOT EXISTS '.$tbl_kardex.' (
		id int(5) NOT NULL AUTO_INCREMENT,
		id_producto int(5) NOT NULL,
		codigointerno varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
		invinicial int(6) NOT NULL,
		january decimal(7,2) NOT NULL,
		february decimal(7,2) NOT NULL,
		march decimal(7,2) NOT NULL,
		april decimal(7,2) NOT NULL,
		may decimal(7,2) NOT NULL,
		june decimal(7,2) NOT NULL,
		july decimal(7,2) NOT NULL,
		august decimal(7,2) NOT NULL,
		september decimal(7,2) NOT NULL,
		october decimal(7,2) NOT NULL,
		november decimal(7,2) NOT NULL,
		december decimal(7,2) NOT NULL,
		ultmodificacion timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
		PRIMARY KEY (id)
	  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
	  

		if($crear_tb_almacen->execute()){
	 
			if($crear_kardex_almacen->execute()){
				$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre, kardex, ubicacion, responsable, telefono, email, ultusuario) VALUES (:nombre, :kardex, :ubicacion, :responsable, :telefono, :email, :ultusuario)");

				$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
				$stmt->bindParam(":kardex", $tbl_kardex, PDO::PARAM_STR);
				$stmt->bindParam(":ubicacion", $datos["ubicacion"], PDO::PARAM_STR);
				$stmt->bindParam(":responsable", $datos["responsable"], PDO::PARAM_STR);
				$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
				$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
				$stmt->bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);
				
				if($stmt->execute()){

					return "ok";

				}else{

					return "error";
				
				}
			}else{
				return "error"; 	
			}
		}else{
			return "error"; 
		}

		$stmt = null;
		return true;

	}

/*=============================================
	MOSTRAR ALMACENES
=============================================*/
static public function mdlMostrarAlmacenes($tabla, $item, $valor, $estado=null){

	try{
		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE estado=:estado");
			$stmt -> bindParam(":estado", $estado, PDO::PARAM_INT);
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
static public function mdlEditarAlmacen($tabla, $datos){
	try{

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET ubicacion= :ubicacion, responsable= :responsable, email= :email, telefono= :telefono, estado=:estado, ultusuario= :ultusuario WHERE id= :id");

		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		$stmt->bindParam(":ubicacion", $datos["ubicacion"], PDO::PARAM_STR);
		$stmt->bindParam(":responsable", $datos["responsable"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
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
static public function mdlEliminarAlmacen($tabla, $datos){
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