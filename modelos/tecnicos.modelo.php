<?php

require_once "conexion.php";

class ModeloTecnicos{


/*=============================================
	CREAR TECNICOS
=============================================*/
public function mdlIngresarTecnico($tabla, $datos){
	try{

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre,rfc,curp,direccion,cp,ciudad,estado,telefonos,email,numero_licencia,numero_imss,expediente,usuario,contrasena,banco,num_cuenta,clabe,edo_nacimiento,alm_asignado,status,ultusuario) 
											   VALUES (:nombre, :rfc, :curp, :direccion, :cp, :ciudad, :estado, :telefonos, :email, :numero_licencia, :numero_imss, :expediente, :usuario, :contrasena, :banco, :num_cuenta, :clabe, :edo_nacimiento, :alm_asignado, :status, :ultusuario)");

		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":rfc", $datos["rfc"], PDO::PARAM_STR);
		$stmt->bindParam(":curp", $datos["curp"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":cp", $datos["cp"], PDO::PARAM_STR);
		$stmt->bindParam(":ciudad", $datos["ciudad"], PDO::PARAM_STR);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
		$stmt->bindParam(":telefonos", $datos["telefonos"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":numero_licencia", $datos["numero_licencia"], PDO::PARAM_STR);
		$stmt->bindParam(":numero_imss", $datos["numero_imss"], PDO::PARAM_STR);
		$stmt->bindParam(":expediente", $datos["expediente"], PDO::PARAM_STR);
		$stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);
		$stmt->bindParam(":contrasena", $datos["contrasena"], PDO::PARAM_STR);
		$stmt->bindParam(":banco", $datos["banco"], PDO::PARAM_STR);
		$stmt->bindParam(":num_cuenta", $datos["num_cuenta"], PDO::PARAM_STR);
		$stmt->bindParam(":clabe", $datos["clabe"], PDO::PARAM_STR);
		$stmt->bindParam(":edo_nacimiento", $datos["edo_nacimiento"], PDO::PARAM_STR);
		$stmt->bindParam(":alm_asignado", $datos["alm_asignado"], PDO::PARAM_STR);
		$stmt->bindParam(":status", $datos["status"], PDO::PARAM_STR);
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
	EDITAR TECNICOS
=============================================*/

public function mdlEditarTecnico($tabla, $datos){
try{

	$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre=:nombre, rfc=:rfc, curp=:curp, direccion=:direccion, cp=:cp, ciudad=:ciudad, estado=:estado, telefonos=:telefonos, email=:email, numero_licencia=:numero_licencia, numero_imss=:numero_imss, expediente=:expediente, usuario=:usuario, contrasena=:contrasena, banco=:banco, num_cuenta=:num_cuenta, clabe=:clabe, edo_nacimiento=:edo_nacimiento, alm_asignado=:alm_asignado, status=:status, ultusuario=:ultusuario WHERE id=:id");
         
        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":rfc", $datos["rfc"], PDO::PARAM_STR);
		$stmt->bindParam(":curp", $datos["curp"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":cp", $datos["cp"], PDO::PARAM_STR);
		$stmt->bindParam(":ciudad", $datos["ciudad"], PDO::PARAM_STR);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
		$stmt->bindParam(":telefonos", $datos["telefonos"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":numero_licencia", $datos["numero_licencia"], PDO::PARAM_STR);
		$stmt->bindParam(":numero_imss", $datos["numero_imss"], PDO::PARAM_STR);
		$stmt->bindParam(":expediente", $datos["expediente"], PDO::PARAM_STR);
		$stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);
		$stmt->bindParam(":contrasena", $datos["contrasena"], PDO::PARAM_STR);
		$stmt->bindParam(":banco", $datos["banco"], PDO::PARAM_STR);
		$stmt->bindParam(":num_cuenta", $datos["num_cuenta"], PDO::PARAM_STR);
		$stmt->bindParam(":clabe", $datos["clabe"], PDO::PARAM_STR);
		$stmt->bindParam(":edo_nacimiento", $datos["edo_nacimiento"], PDO::PARAM_STR);
		$stmt->bindParam(":alm_asignado", $datos["alm_asignado"], PDO::PARAM_STR);
		$stmt->bindParam(":status", $datos["status"], PDO::PARAM_STR);
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
	MOSTRAR TECNICOS
=============================================*/
static public function mdlMostrarTecnicos($tabla, $item, $valor){
	try{

		if($item != null){

			$sql="SELECT tec.*,alm.nombre AS almacen,est.nombreestado 
			FROM $tabla tec 
			INNER JOIN almacenes alm ON tec.alm_asignado=alm.id 
			INNER JOIN catestado est ON tec.estado=est.idestado	
			WHERE tec.$item = :$item";
			
			$stmt = Conexion::conectar()->prepare($sql);

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT tec.id,tec.nombre, tec.expediente, tec.curp, tec.rfc,tec.telefonos, tec.direccion, tec.num_cuenta, alm.nombre AS almacen,tec.status FROM $tabla tec INNER JOIN almacenes alm ON alm_asignado=alm.id");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt = null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}
	
}
    
    
	
/*=============================================
	MOSTRAR ESTADOS
=============================================*/
static public function mdlMostrarEstados($tabla, $item, $valor){
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
	
	

}   //FIN DE LA CLASE

