<?php
date_default_timezone_set('America/Mexico_City');
require_once "conexion.php";

class ModeloOsvilla{

        
/*==============================================
	GUARDAR OS
===============================================*/
static public function mdlGuardarOsvilla($tabla, $datos){
 try {
 
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(area, osvilla, tipoos, contratante, fecha_cita, domicilio, ciudad, id_estado, folio_pisaplex, prioridad, zona, telefono_asignado, telefono_contacto, telefono_celular, tipo_cliente, email, fecha_asignada, id_tecnico, id_almacen, estatus, observaciones, datos_memo, ultusuario)
		VALUES (:area, :osvilla, :tipoos, :contratante, :fecha_cita, :domicilio, :ciudad, :id_estado, :folio_pisaplex, :prioridad, :zona, :telefono_asignado, :telefono_contacto, :telefono_celular, :tipo_cliente, :email, :fecha_asignada, :id_tecnico, :id_almacen, :estatus, :observaciones, :datos_memo, :ultusuario)");
		
		$stmt->bindParam(":area", $datos["area"], PDO::PARAM_STR);
		$stmt->bindParam(":osvilla", $datos["osvilla"], PDO::PARAM_STR);
		$stmt->bindParam(":tipoos", $datos["tipoos"], PDO::PARAM_STR);
		$stmt->bindParam(":contratante", $datos["contratante"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_cita", $datos["fecha_cita"], PDO::PARAM_STR);
		$stmt->bindParam(":domicilio", $datos["domicilio"], PDO::PARAM_STR);
		$stmt->bindParam(":ciudad", $datos["ciudad"], PDO::PARAM_STR);
		$stmt->bindParam(":id_estado", $datos["id_estado"], PDO::PARAM_INT);
		$stmt->bindParam(":folio_pisaplex", $datos["folio_pisaplex"], PDO::PARAM_STR);
		$stmt->bindParam(":prioridad", $datos["prioridad"], PDO::PARAM_INT);
		$stmt->bindParam(":zona", $datos["zona"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono_asignado", $datos["telefono_asignado"], PDO::PARAM_INT);
		$stmt->bindParam(":telefono_contacto", $datos["telefono_contacto"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono_celular", $datos["telefono_celular"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo_cliente", $datos["tipo_cliente"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_asignada", $datos["fecha_asignada"], PDO::PARAM_STR);		
		$stmt->bindParam(":id_tecnico", $datos["id_tecnico"], PDO::PARAM_INT);
		$stmt->bindParam(":id_almacen", $datos["id_almacen"], PDO::PARAM_INT);
		$stmt->bindParam(":estatus", $datos["estatus"], PDO::PARAM_INT);
		$stmt->bindParam(":observaciones", $datos["observaciones"], PDO::PARAM_STR);
		$stmt->bindParam(":datos_memo", $datos["datos_memo"], PDO::PARAM_STR);
        $stmt->bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);
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

/*==============================================
	EDITAR OS
===============================================*/
static public function mdlEditarOsvilla($tabla, $datos){
 try {
 
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET area=:area, osvilla=:osvilla, tipoos=:tipoos, contratante=:contratante, fecha_cita=:fecha_cita, 
		domicilio=:domicilio, ciudad=:ciudad, id_estado=:id_estado, folio_pisaplex=:folio_pisaplex, prioridad=prioridad, zona=:zona, 
		telefono_asignado=:telefono_asignado, telefono_contacto=:telefono_contacto, telefono_celular=:telefono_celular, tipo_cliente=:tipo_cliente,
		email=:email, fecha_asignada=:fecha_asignada, id_tecnico=:id_tecnico, id_almacen=:id_almacen, estatus=:estatus, observaciones=:observaciones, datos_memo=:datos_memo, 
		ultusuario=:ultusuario WHERE osvilla=:osvilla");

		$stmt->bindParam(":osvilla", $datos["osvilla"], PDO::PARAM_STR);
		$stmt->bindParam(":area", $datos["area"], PDO::PARAM_STR);
		$stmt->bindParam(":tipoos", $datos["tipoos"], PDO::PARAM_STR);
		$stmt->bindParam(":contratante", $datos["contratante"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_cita", $datos["fecha_cita"], PDO::PARAM_STR);
		$stmt->bindParam(":domicilio", $datos["domicilio"], PDO::PARAM_STR);
		$stmt->bindParam(":ciudad", $datos["ciudad"], PDO::PARAM_STR);
		$stmt->bindParam(":id_estado", $datos["id_estado"], PDO::PARAM_INT);
		$stmt->bindParam(":folio_pisaplex", $datos["folio_pisaplex"], PDO::PARAM_STR);
		$stmt->bindParam(":prioridad", $datos["prioridad"], PDO::PARAM_INT);
		$stmt->bindParam(":zona", $datos["zona"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono_asignado", $datos["telefono_asignado"], PDO::PARAM_INT);
		$stmt->bindParam(":telefono_contacto", $datos["telefono_contacto"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono_celular", $datos["telefono_celular"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo_cliente", $datos["tipo_cliente"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_asignada", $datos["fecha_asignada"], PDO::PARAM_STR);
		$stmt->bindParam(":id_tecnico", $datos["id_tecnico"], PDO::PARAM_INT);
		$stmt->bindParam(":id_almacen", $datos["id_almacen"], PDO::PARAM_INT);
		$stmt->bindParam(":estatus", $datos["estatus"], PDO::PARAM_INT);
		$stmt->bindParam(":observaciones", $datos["observaciones"], PDO::PARAM_STR);
		$stmt->bindParam(":datos_memo", $datos["datos_memo"], PDO::PARAM_STR);
        $stmt->bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);
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
	??? OS VILLA
=============================================*/

static public function mdlMostrarOsvilla($tabla, $item, $valor){

	$stmt = Conexion::conectar()->prepare("SELECT os.*, edo.nombreestado,tec.nombre, alm.nombre AS nomalmacen FROM $tabla os INNER JOIN catestado edo ON os.id_estado=edo.idestado 
    INNER JOIN tecnicos tec ON os.id_tecnico=tec.id
    INNER JOIN almacenes alm ON os.id_almacen=alm.id
    WHERE os.$item = :$item"); 
    
	$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

	$stmt -> execute();

    return $stmt -> fetch();

	$stmt -> close();

	$stmt = null;

}
    
    
    
/*=============================================
	LISTAR OS VILLA
=============================================*/

	static public function mdlListarOsvilla($tabla, $item, $valorFechaIni, $valorFechaFin, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT cja.id AS idcaja, cja.caja, cja.descripcion, cja.estado, cja.id_usuario, user.id, user.nombre FROM $tabla cja LEFT JOIN usuarios user ON user.id=cja.id_usuario WHERE cja.$item = :$item"); 
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare('SELECT os.id, os.osvilla, os.contratante, os.fecha_cita, 
			os.fecha_asignada, os.fecha_liquidacion, os.telefono_asignado,os.estatus,tec.nombre FROM osvilla os 
			INNER JOIN tecnicos tec ON os.id_tecnico=tec.id WHERE os.fecha_asignada>="'.$valorFechaIni.'" AND os.fecha_asignada<="'.$valorFechaFin.'"');

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

/*=============================================
	BORRAR OS
=============================================*/

static public function mdlEliminarrOsvilla($tabla, $item, $valor){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE $item = :$item");

		$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "Registro Eliminado";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

}    
    
    
    
    
    
    
} //fin de la clase
