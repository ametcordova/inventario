<?php
date_default_timezone_set('America/Mexico_City');
require_once dirname( __DIR__ ).'/config/conexion.php';
class ModeloMovto{

/*=============================================
	CREAR MOVTO
=============================================*/
static public function mdlCrearMovto($tabla, $datos){
    try {
           
           $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre_tipo, clase, estado, idusuario) VALUES (:nombre_tipo, :clase, :estado, :idusuario)");
   
           $stmt->bindParam(":nombre_tipo", $datos["nombre_tipo"], PDO::PARAM_STR);
           $stmt->bindParam(":clase", $datos["clase"], PDO::PARAM_STR);
           $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
           $stmt->bindParam(":idusuario", $datos["idusuario"], PDO::PARAM_INT);
           if($stmt->execute()){
               
                return "ok";
               
           }else{
   
               return "error";
           
           }
    } catch (Exception $e) {
       return $e->getMessage();
    }
          
           $stmt->close();
           $stmt = null;
   
           
   }
  
 
/*=============================================
	ACTIVAR CAJA
=============================================*/
static public function mdlActivarMovto($tabla, $item, $valor){
 try {
        
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_caja, fecha_venta) VALUES (:id_caja, :fecha_venta)");
        $fechadeHoy=date("Y-m-d");
		$stmt->bindParam(":id_caja", $valor, PDO::PARAM_INT);
		$stmt->bindParam(":fecha_venta", $fechadeHoy, PDO::PARAM_STR);
		if($stmt->execute()){
            
			 return "Abierto";
            
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
	MOSTRAR CAJAS
=============================================*/

	static public function mdlMostrarMovto($tabla, $item, $valor, $orden){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item"); 
			
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			//$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id ASC"); 

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

/*=============================================
	MOSTRAR TIPO DE MOV DE SALIDA
=============================================*/
static public function MdlMostrarTipoMov($tabla, $item, $valor){

    $stmt = Conexion::conectar()->prepare("SELECT id, nombre_tipo FROM $tabla WHERE $item = :$item");
    
    $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);

	$stmt -> execute();

	 return $stmt -> fetch();

	$stmt -> close();

	$stmt = null;

}
  
/*=============================================
	EDITAR CAJA
=============================================*/

	static public function mdlEditarMovto($tabla, $datos){
    try{
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre_tipo=:nombre_tipo, estado=:estado, idusuario=:idusuario WHERE id=:id");

		$stmt -> bindParam(":nombre_tipo", $datos["nombre_tipo"], PDO::PARAM_STR);
		$stmt -> bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
		$stmt -> bindParam(":idusuario", $datos["idusuario"], PDO::PARAM_INT);
		$stmt -> bindParam(":id", $datos["id"], PDO::PARAM_INT);

        $stmt->execute(); 
        
        if($stmt==true){
            return true;
        }else{
            return false;
        }

    }catch(Exception $e) {
        return $e->getMessage();
    }

		$stmt->close();
		$stmt = null;

}

	/*=============================================
	BORRAR CAJA
	=============================================*/

	static public function mdlBorrarMovto($tabla, $item, $valor, $estado){
        
		//$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estado=:estado WHERE $item = :$item");
        
        $stmt -> bindParam(":".$item, $valor, PDO::PARAM_INT);
        $stmt -> bindParam(":estado", $estado, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}




	
} //fin de la clase

