<?php
date_default_timezone_set('America/Mexico_City');
//require_once dirname( __DIR__ ).'/modelos/conexion.php';
class ModeloAdminQueja{

 /*=============================================
    GUARDAR DATOS DE DEPOSITO
=============================================*/
static public function mdlGuardarQueja($tabla, $datos){
	try{

		//$conectar=parent::conectar();
		//parent::set_names();
		// $stmt = $conectar->prepare("INSERT INTO $tabla(id_tecnico, fecha, os, telefono, distrito, cliente, motivo, operador, folio_oci, inicio_llamada, fin_llamada, minutos, observaciones, ultusuario)
        // VALUES (:id_tecnico, :fecha, :os, :telefono, :distrito, :cliente, :motivo, :operador, :folio_oci, :inicio_llamada, :fin_llamada, :minutos, :observaciones, :ultusuario)");


		 $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_tecnico, fecha, os, telefono, distrito, cliente, motivo, operador, folio_oci, inicio_llamada, fin_llamada, minutos, observaciones, estatus, ultusuario)
         VALUES (:id_tecnico, :fecha, :os, :telefono, :distrito, :cliente, :motivo, :operador, :folio_oci, :inicio_llamada, :fin_llamada, :minutos, :observaciones, :estatus, :ultusuario)");

		$dateTimeObject1 = date_create($datos["inicio_llamada"]); 
		$dateTimeObject2 = date_create($datos["fin_llamada"]); 
		$diferencia = date_diff($dateTimeObject1, $dateTimeObject2);
		$minutes = $diferencia->days * 24 * 60;
		$minutes += $diferencia->h * 60;
		$minutes += $diferencia->i*100;

		$stmt->bindParam(":id_tecnico",     $datos["id_tecnico"], PDO::PARAM_INT);
		$stmt->bindParam(":fecha",          $datos["fecha"], PDO::PARAM_STR);
		$stmt->bindParam(":os",             $datos["os"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono",       $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":distrito",       $datos["distrito"], PDO::PARAM_STR);
		$stmt->bindParam(":cliente",        $datos["cliente"], PDO::PARAM_STR);
		$stmt->bindParam(":motivo",         $datos["motivo"], PDO::PARAM_STR);
		$stmt->bindParam(":operador",       $datos["operador"], PDO::PARAM_STR);
		$stmt->bindParam(":folio_oci",      $datos["folio_oci"], PDO::PARAM_STR);
		$stmt->bindParam(":inicio_llamada", $datos["inicio_llamada"], PDO::PARAM_STR);
		$stmt->bindParam(":fin_llamada",    $datos["fin_llamada"], PDO::PARAM_STR);
		$stmt->bindParam(":minutos",        $minutes, PDO::PARAM_STR);
		$stmt->bindParam("observaciones",   $datos["observaciones"], PDO::PARAM_STR);
		$stmt->bindParam("estatus",   		$datos["estatus"], PDO::PARAM_INT);
        $stmt->bindParam(":ultusuario",     $datos["ultusuario"], PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}
    }catch(Exception $e) {
		return $e->getMessage();
    }
		$stmt = null;

}

 /*=============================================
    GUARDAR DATOS DE DEPOSITO
=============================================*/
static public function mdlActualizarQueja($tabla, $datos){
	try{

		 $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET fecha=:fecha, os=:os, telefono=:telefono, distrito=:distrito, cliente=:cliente, motivo=:motivo, operador=:operador, folio_oci=:folio_oci, inicio_llamada=:inicio_llamada, fin_llamada=:fin_llamada, minutos=:minutos, observaciones=:observaciones, estatus=:estatus, ultusuario=:ultusuario WHERE id=:id");

		$dateTimeObject1 = date_create($datos["inicio_llamada"]); 
		$dateTimeObject2 = date_create($datos["fin_llamada"]); 
		$diferencia = date_diff($dateTimeObject1, $dateTimeObject2);
		$minutes = $diferencia->days * 24 * 60;
		$minutes += $diferencia->h * 60;
		$minutes += $diferencia->i*100;

		$stmt->bindParam(":id",     		$datos["id"], PDO::PARAM_INT);
		$stmt->bindParam(":fecha",          $datos["fecha"], PDO::PARAM_STR);
		$stmt->bindParam(":os",             $datos["os"], PDO::PARAM_STR);
		$stmt->bindParam(":telefono",       $datos["telefono"], PDO::PARAM_STR);
		$stmt->bindParam(":distrito",       $datos["distrito"], PDO::PARAM_STR);
		$stmt->bindParam(":cliente",        $datos["cliente"], PDO::PARAM_STR);
		$stmt->bindParam(":motivo",         $datos["motivo"], PDO::PARAM_STR);
		$stmt->bindParam(":operador",       $datos["operador"], PDO::PARAM_STR);
		$stmt->bindParam(":folio_oci",      $datos["folio_oci"], PDO::PARAM_STR);
		$stmt->bindParam(":inicio_llamada", $datos["inicio_llamada"], PDO::PARAM_STR);
		$stmt->bindParam(":fin_llamada",    $datos["fin_llamada"], PDO::PARAM_STR);
		$stmt->bindParam(":minutos",        $minutes, PDO::PARAM_STR);
		$stmt->bindParam("observaciones",   $datos["observaciones"], PDO::PARAM_STR);
		$stmt->bindParam("estatus",   		$datos["estatus"], PDO::PARAM_INT);
        $stmt->bindParam(":ultusuario",     $datos["ultusuario"], PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}
    }catch(Exception $e) {
		return $e->getMessage();
    }

	$stmt = null;
}

/*=============================================
	LISTAR ORDENES DE SERVICIO
=============================================*/
static public function mdlListarQuejas($tabla, $fecha1, $fecha2, $idtecnico){
	try{
	
			  
		$where="os.fecha>='".$fecha1."' AND os.fecha<='".$fecha2."' ";
		$where.="AND os.estatus>0";
		if($idtecnico!=null){
			$where.=" AND os.id_tecnico=".$idtecnico;
		}
		
	  
		$stmt = Conexion::conectar()->prepare("SELECT os.*, usu.nombre AS tecnico 
		FROM $tabla os
		/*INNER JOIN tecnicos tec ON tec.id=os.id_tecnico*/
		INNER JOIN usuarios usu ON usu.user=os.id_tecnico
		WHERE ".$where); 
		$stmt -> execute();
	
		return $stmt -> fetchAll();
	
		$stmt = null;
		
	  } catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	  }
	  
}
/*===================================================================== */

/*=============================================
	TRAER DATOS DE LA QUEJA
=============================================*/
static public function mdlVerQueja($tabla, $iddep, $campo){
    try {    
        //$estatus=0;
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $campo = :$campo");
        
        $stmt->bindParam(":".$campo, $iddep, PDO::PARAM_INT);
        //$stmt->bindParam(":estatus", $estatus, PDO::PARAM_INT);

        $stmt -> execute();

        return $stmt -> fetch();

        $stmt = null;
    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }

}

/*=============================================
ELIMINAR DEPOSITO
=============================================*/
static public function mdlBorrarQueja($tabla, $id, $campo){
    try {
        
        $sql="UPDATE $tabla SET estatus=0 WHERE $campo=:$campo";

        $stmt=Conexion::conectar()->prepare($sql);
        $stmt->bindParam(":".$campo, $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt) {
            return array("respuesta" =>'ok');
        }else{
            $respuesta = array("error" =>'Eliminacion');
            return $respuesta;
        }

        $stmt = null;

    } catch (Exception $e) {
            echo "Failed: " . $e->getMessage();
    }
   
}
/*===================================================================== */

} //fin de la clase


?>
