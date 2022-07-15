<?php
date_default_timezone_set('America/Mexico_City');
require_once dirname( __DIR__ ).'/modelos/conexion.php';
class ModeloCtrolDepositos{


 /*=============================================
	LISTAR SALIDAS
=============================================*/
static public function mdlListarDepositos($tabla, $fechadev1, $fechadev2){
try {      
 	//$where='1=1';
		  
	$where='cd.fecha_transaccion>="'.$fechadev1.'" AND cd.fecha_transaccion<="'.$fechadev2.'" ';
  
	$where.=' AND cd.estatus=1 ORDER BY cd.id DESC';
  
	$sql="SELECT cd.id, cd.`id_cuentahabiente`,ca.nombrecuentahab, cd.`id_destino`, dt.nombre, cd.`motivo`, cd.`monto_transaccion`, cd.`monto_comision`, ca.numerotarjeta, cd.sucursal, cd.`fecha_transaccion`, cd.estatus 
    FROM $tabla cd 
    INNER JOIN cuentahabientes ca ON cd.`id_cuentahabiente`=ca.id
    INNER JOIN destinatarios dt ON cd.`id_destino`=dt.id
    WHERE ".$where;
  
	$stmt = Conexion::conectar()->prepare($sql);
  
	$stmt -> execute();
  
	return $stmt -> fetchAll();
  
    $stmt = null;

} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}
  
}	

/*=============================================
	MOSTRAR BENEFICIARIOS
=============================================*/
static public function mdlAjaxBeneficiario($tabla, $campo, $valor){
    try {    

        $stmt = Conexion::conectar()->prepare("SELECT tb1.id, tb1.nombrecuentahab, tb1.numerotarjeta, tb1.usodeposito, dest.nombre 
        FROM $tabla tb1
        INNER JOIN destinatarios dest ON tb1.id_destino=dest.id
        WHERE tb1.$campo LIKE '%".$valor."%' OR tb1.numerotarjeta LIKE '%".$valor."%' ");
        
		//WHERE nombres like :nombres y $stmt->bindValue(':nombres', '%'.$search.'%', PDO::PARAM_STR);
        //$stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
        //$stmt->bindParam(":estado", $estado, PDO::PARAM_INT);

        $stmt -> execute();

        return $stmt -> fetchAll();

        $stmt = null;
    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }

}
    
/*=============================================
	TRAER DATOS DE BENEFICIARIO
=============================================*/
static public function mdlDatosBeneficiario($tabla, $campo, $valor){
    try {    
        $estatus=1;
        $stmt = Conexion::conectar()->prepare("SELECT tb1.id, tb1.nombrecuentahab, tb1.numerotarjeta, tb1.usodeposito, tb1.id_destino, dest.nombre AS banco
        FROM $tabla tb1
        INNER JOIN destinatarios dest ON tb1.id_destino=dest.id
        WHERE tb1.$campo=:$campo AND tb1.estatus=:estatus");
        
        $stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
        $stmt->bindParam(":estatus", $estatus, PDO::PARAM_INT);

        $stmt -> execute();

        return $stmt -> fetch();

        $stmt = null;
    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }

}

/*=============================================
    GUARDAR DATOS DE DEPOSITO
=============================================*/
static public function mdlGuardarDeposito($tabla, $datos){
	try{
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_cuentahabiente, id_destino, motivo, monto_transaccion, monto_comision, fecha_transaccion, sucursal, estatus, ultusuario) 
        VALUES (:id_cuentahabiente, :id_destino, :motivo, :monto_transaccion, :monto_comision, :fecha_transaccion, :sucursal, :estatus, :ultusuario)");

		$stmt->bindParam(":id_cuentahabiente",  $datos["id_cuentahabiente"], PDO::PARAM_INT);
		$stmt->bindParam(":id_destino",         $datos["id_destino"], PDO::PARAM_INT);
		$stmt->bindParam(":motivo",             $datos["motivo"], PDO::PARAM_STR);
		$stmt->bindParam(":monto_transaccion",  $datos["monto_transaccion"], PDO::PARAM_STR);
		$stmt->bindParam(":monto_comision",     $datos["monto_comision"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_transaccion",  $datos["fecha_transaccion"], PDO::PARAM_STR);
		$stmt->bindParam(":sucursal",           $datos["sucursal"], PDO::PARAM_STR);
		$stmt->bindParam(":estatus",            $datos["estatus"], PDO::PARAM_INT);
        $stmt->bindParam(":ultusuario",         $datos["ultusuario"], PDO::PARAM_INT);

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
    ACTUALIZAR DATOS DE DEPOSITO
=============================================*/
static public function mdlActualizarDeposito($tabla, $datos){
	try{

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET id_destino=:id_destino, motivo=:motivo, monto_transaccion=:monto_transaccion, monto_comision=:monto_comision, fecha_transaccion=:fecha_transaccion, sucursal=:sucursal, estatus=:estatus, ultusuario=:ultusuario WHERE id=:id");

		$stmt->bindParam(":id",                 $datos["id"], PDO::PARAM_INT);
		$stmt->bindParam(":id_destino",         $datos["id_destino"], PDO::PARAM_INT);
		$stmt->bindParam(":motivo",             $datos["motivo"], PDO::PARAM_STR);
		$stmt->bindParam(":monto_transaccion",  $datos["monto_transaccion"], PDO::PARAM_STR);
		$stmt->bindParam(":monto_comision",     $datos["monto_comision"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_transaccion",  $datos["fecha_transaccion"], PDO::PARAM_STR);
		$stmt->bindParam(":sucursal",           $datos["sucursal"], PDO::PARAM_STR);
		$stmt->bindParam(":estatus",            $datos["estatus"], PDO::PARAM_INT);
        $stmt->bindParam(":ultusuario",         $datos["ultusuario"], PDO::PARAM_INT);

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
    GUARDAR DATOS DE CUENTAHABIENTE
=============================================*/
static public function mdlGuardarCuentaHabiente($tabla, $datos){
	try{
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombrecuentahab, id_destino, numerotarjeta, cuentaclabe, usodeposito, fechapago, estatus, ultusuario) 
        VALUES (:nombrecuentahab, :id_destino, :numerotarjeta, :cuentaclabe, :usodeposito, :fechapago, :estatus, :ultusuario)");

		$stmt->bindParam(":nombrecuentahab",    $datos["nombrecuentahab"], PDO::PARAM_STR);
		$stmt->bindParam(":id_destino",         $datos["id_destino"], PDO::PARAM_INT);
		$stmt->bindParam(":numerotarjeta",      $datos["numerotarjeta"], PDO::PARAM_STR);
		$stmt->bindParam(":cuentaclabe",        $datos["cuentaclabe"], PDO::PARAM_STR);
		$stmt->bindParam(":usodeposito",        $datos["usodeposito"], PDO::PARAM_STR);
		$stmt->bindParam(":fechapago",          $datos["fechapago"], PDO::PARAM_STR);
		$stmt->bindParam(":estatus",            $datos["estatus"], PDO::PARAM_INT);
        $stmt->bindParam(":ultusuario",         $datos["ultusuario"], PDO::PARAM_INT);

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
	TRAER DATOS DE BENEFICIARIO / EMP
=============================================*/
static public function mdlMostrarDestinatarios($tabla, $item, $valor){
    try {    

        $stmt = Conexion::conectar()->prepare("SELECT *
        FROM $tabla
        WHERE $item=:$item");
        
        $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);

        $stmt -> execute();

        if($stmt){
            return $stmt -> fetchAll();
        }else{
            return "error";
        }

        $stmt = null;
    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }

}

/*=============================================
ELIMINAR DEPOSITO
=============================================*/
static public function mdlBorrarDeposito($tabla, $idDep, $campo){
    try {
        
        $sql="UPDATE $tabla SET estatus=0 WHERE $campo=:$campo";

        $stmt=Conexion::conectar()->prepare($sql);
        $stmt->bindParam(":".$campo, $idDep, PDO::PARAM_INT);
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


/*=============================================
	TRAER DATOS DE BENEFICIARIO
=============================================*/
static public function mdlDatosDeposito($tabla, $iddep, $campo){
    try {    
        $estatus=1;
        $stmt = Conexion::conectar()->prepare("SELECT cd.id, cd.id_cuentahabiente, ch.nombrecuentahab, cd.id_destino, dt.nombre, cd.motivo, cd.monto_transaccion, cd.monto_comision, ch.numerotarjeta, cd.fecha_transaccion, cd.sucursal 
        FROM $tabla cd 
        INNER JOIN cuentahabientes ch ON ch.id=cd.id_cuentahabiente
        INNER JOIN destinatarios dt ON dt.id=cd.id_destino
        WHERE cd.$campo=:$campo AND cd.estatus=1");
        
        $stmt->bindParam(":".$campo, $iddep, PDO::PARAM_INT);
        //$stmt->bindParam(":estatus", $estatus, PDO::PARAM_INT);

        $stmt -> execute();

        return $stmt -> fetch();

        $stmt = null;
    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }

}

/********************************************************************/





} //fin de la clase

/*
SELECT cd.id, cd.`id_cuentahabiente`,ca.nombrecuentahab, cd.`id_destino`, dt.nombre, cd.`motivo`, cd.`monto_transaccion`, cd.`monto_comision`, ca.numerotarjeta, cd.`fecha_transaccion` FROM `control_depositos` cd 
INNER JOIN cuentahabientes ca ON cd.`id_cuentahabiente`=ca.id
INNER JOIN destinatarios dt ON cd.`id_destino`=dt.id
WHERE cd.`estatus`=1
 */

?>
