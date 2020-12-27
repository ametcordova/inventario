<?php
date_default_timezone_set('America/Mexico_City');
require_once "conexion.php";

class ModeloFacturas{


/*=============================================
	CREAR FACTURA
=============================================*/
static public function mdlCrearFactura($tabla, $datos){
	try {
        //CAMBIAR EL FORMATO DE FECHA A yyyy-mm-dd   
		//$newFechaFact=date("Y-m-d",strtotime($datos["fechafactura"])); 
		//$newFechaEntrega=date("Y-m-d",strtotime($datos["fechaentregado"])); 
		
		$newFechaFact=date("Y-m-d",strtotime($datos["fechafactura"])); 
		$newFechaEntrega=$datos["fechaentregado"]==""?null:$datos["fechaentregado"]; 

		   $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(numfact, fechafactura, cliente, numorden, subtotal, iva, imp_retenido, importe, tipotrabajo, fechaentregado, status, observaciones, rutaexpediente, idusuario) VALUES (:numfact, :fechafactura, :cliente, :numorden, :subtotal, :iva, :imp_retenido, :importe, :tipotrabajo, :fechaentregado, :status, :observaciones, :rutaexpediente, :idusuario)");
   
		   $stmt->bindParam(":numfact", $datos["numfact"], PDO::PARAM_STR);
		   $stmt->bindParam(":fechafactura", $newFechaFact, PDO::PARAM_STR);
		   $stmt->bindParam(":cliente", $datos["cliente"], PDO::PARAM_STR);
		   $stmt->bindParam(":numorden", $datos["numorden"], PDO::PARAM_STR);
		   $stmt->bindParam(":subtotal", $datos["subtotal"], PDO::PARAM_STR);
		   $stmt->bindParam(":iva", $datos["iva"], PDO::PARAM_STR);
		   $stmt->bindParam(":imp_retenido", $datos["imp_retenido"], PDO::PARAM_STR);
		   $stmt->bindParam(":importe", $datos["importe"], PDO::PARAM_STR);
		   $stmt->bindParam(":tipotrabajo", $datos["tipotrabajo"], PDO::PARAM_STR);
		   $stmt->bindParam(":fechaentregado", $newFechaEntrega, PDO::PARAM_STR);
		   $stmt->bindParam(":status", $datos["status"], PDO::PARAM_INT);
		   $stmt->bindParam(":observaciones", $datos["observaciones"], PDO::PARAM_STR);
		   $stmt->bindParam(":rutaexpediente", $datos["rutaexpediente"], PDO::PARAM_STR);
		   $stmt->bindParam(":idusuario", $datos["idusuario"], PDO::PARAM_INT);
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
	GUARDAR EDICION DE FACTURA
=============================================*/
static public function mdlGuardarEditarFactura($tabla, $datos){
	try {
        //CAMBIAR EL FORMATO DE FECHA A yyyy-mm-dd   
		//$nuevaFechaFact=date("Y-m-d",strtotime($datos["fechafactura"])); 
		//$nuevaFechaEntrega=date("Y-m-d",strtotime($datos["fechaentregado"])); 
		//$nuevaFechaPagado=date("Y-m-d",strtotime($datos["fechapagado"])); 

		$nuevaFechaFact=$datos["fechafactura"]; 
		$nuevaFechaEntrega=$datos["fechaentregado"]==""?null:$datos["fechaentregado"]; 
		$nuevaFechaPagado=$datos["fechapagado"]==""?null:$datos["fechapagado"];

		   $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET fechafactura=:fechafactura, cliente=:cliente, numorden=:numorden, subtotal=:subtotal, iva=:iva, imp_retenido=:imp_retenido, importe=:importe, tipotrabajo=:tipotrabajo, fechaentregado=:fechaentregado, fechapagado=:fechapagado, status=:status, observaciones=:observaciones, rutaexpediente=:rutaexpediente, idusuario=:idusuario WHERE id=:id");

		   $stmt->bindParam(":id", $datos["idregistro"], PDO::PARAM_INT);
		   $stmt->bindParam(":fechafactura", $nuevaFechaFact, PDO::PARAM_STR);
		   $stmt->bindParam(":cliente", $datos["cliente"], PDO::PARAM_STR);
		   $stmt->bindParam(":numorden", $datos["numorden"], PDO::PARAM_STR);
		   $stmt->bindParam(":subtotal", $datos["subtotal"], PDO::PARAM_STR);
		   $stmt->bindParam(":iva", $datos["iva"], PDO::PARAM_STR);
		   $stmt->bindParam(":imp_retenido", $datos["imp_retenido"], PDO::PARAM_STR);
		   $stmt->bindParam(":importe", $datos["importe"], PDO::PARAM_STR);
		   $stmt->bindParam(":tipotrabajo", $datos["tipotrabajo"], PDO::PARAM_STR);
		   $stmt->bindParam(":fechaentregado", $nuevaFechaEntrega, PDO::PARAM_STR);
		   $stmt->bindParam(":fechapagado", $nuevaFechaPagado, PDO::PARAM_STR);
		   $stmt->bindParam(":status", $datos["status"], PDO::PARAM_INT);
		   $stmt->bindParam(":observaciones", $datos["observaciones"], PDO::PARAM_STR);
		   $stmt->bindParam(":rutaexpediente", $datos["rutaexpediente"], PDO::PARAM_STR);
		   $stmt->bindParam(":idusuario", $datos["idusuario"], PDO::PARAM_INT);
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


/* ===============================================================================   
	FECHA PAGO DE  FACTURA
===============================================================================*/
static public function mdlGuardarPagoFactura($tabla, $datos){
try {

	//$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
	$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET fechapagado=:fechapagado, numcomplemento=:numcomplemento, status=:status, idusuario=:idusuario WHERE id=:id");

	$stmt->bindParam(":id", $datos["registroid"], PDO::PARAM_INT);
	$stmt->bindParam(":fechapagado", $datos["fechapagado"], PDO::PARAM_STR);
	$stmt->bindParam(":numcomplemento", $datos["numcomplemento"], PDO::PARAM_STR);
	$stmt->bindParam(":status", $datos["status"], PDO::PARAM_INT);
	$stmt->bindParam(":idusuario", $datos["idusuario"], PDO::PARAM_INT);

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

/* ===============================================================================   
	FECHA PAGO DE  FACTURA
===============================================================================*/
static public function mdlGuardarFechaPagoFactura($tabla, $datos){
	try {
	
		//$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET fechapagado=:fechapagado, numcomplemento=:numcomplemento, idusuario=:idusuario WHERE numfact=:numfact");
	
		$stmt->bindParam(":numfact", $datos["registroid"], PDO::PARAM_STR);
		$stmt->bindParam(":fechapagado", $datos["fechapagado"], PDO::PARAM_STR);
		$stmt->bindParam(":numcomplemento", $datos["numcomplemento"], PDO::PARAM_STR);
		$stmt->bindParam(":idusuario", $datos["idusuario"], PDO::PARAM_INT);
	
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
	

/* ===============================================================================   
	BORRAR FACTURA
===============================================================================*/
static public function mdlBorrarFactura($tabla, $item, $valor, $estado){
try{     
	//$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
	$numorden="CANCELADO";
	$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET numorden=:numorden, borrado=:borrado WHERE $item = :$item");
	
	$stmt -> bindParam(":".$item, $valor, PDO::PARAM_INT);
	$stmt -> bindParam(":numorden", $numorden, PDO::PARAM_STR);
	$stmt -> bindParam(":borrado", $estado, PDO::PARAM_INT);

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
	MOSTRAR FACTURAS
=============================================*/
static public function mdlMostrarFacturas($tabla, $item, $valor, $orden, $tipo, $year){
try{
		if($item != null){
			$orden=intval($orden);
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item =:$item AND borrado=$orden"); 
			
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			if($tipo=='todos'){
				$where='1=1';
			}elseif($tipo=='porpagar'){
				$where='status=0 AND borrado="'.$orden.'"';
			}elseif($tipo=='pagado'){
				$where='status=1 AND borrado="'.$orden.'"';
			}else{		//CANCELADOS
				$where='borrado=1';
			}
			
			if(isset($year) && !empty($year)){
				//$where.=' AND EXTRACT(YEAR FROM `fechafactura`) = $year';
				$where.=' AND YEAR(`fechafactura`) = "'.$year.'" ';
			}
			
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE ".$where); 

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

} catch (Exception $e) {
	echo "Failed: " . $e->getMessage();
}        
	
		$stmt = null;
}


	
} //fin de la clase

