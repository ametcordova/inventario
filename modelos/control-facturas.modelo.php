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

		   $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(numfact, serie, fechafactura, cliente, numorden, subtotal, iva, imp_retenido, importe, tipotrabajo, fechaentregado, status, observaciones, contrato, rutaexpediente, idusuario) VALUES (:numfact, :serie, :fechafactura, :cliente, :numorden, :subtotal, :iva, :imp_retenido, :importe, :tipotrabajo, :fechaentregado, :status, :observaciones, :contrato, :rutaexpediente, :idusuario)");
   
			$stmt->bindParam(":numfact", $datos["numfact"], PDO::PARAM_INT);
			$stmt->bindParam(":serie", $datos["serie"], PDO::PARAM_STR);
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
			$stmt->bindParam(":contrato", $datos["contrato"], PDO::PARAM_STR);
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

		   $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET fechafactura=:fechafactura, serie=:serie, cliente=:cliente, numorden=:numorden, subtotal=:subtotal, iva=:iva, imp_retenido=:imp_retenido, importe=:importe, tipotrabajo=:tipotrabajo, fechaentregado=:fechaentregado, fechapagado=:fechapagado, status=:status, observaciones=:observaciones, contrato=:contrato, construccion=:construccion, rutaexpediente=:rutaexpediente, idusuario=:idusuario WHERE id=:id");

		   $stmt->bindParam(":id", $datos["idregistro"], PDO::PARAM_INT);
		   $stmt->bindParam(":serie", $datos["serie"], PDO::PARAM_STR);
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
		   $stmt->bindParam(":contrato", $datos["contrato"], PDO::PARAM_STR);
		   $stmt->bindParam(":construccion", $datos["construccion"], PDO::PARAM_INT);
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
static public function mdlMostrarFacturas($tabla, $item, $valor, $valor2, $orden, $tipo, $year, $monthinicial, $monthfinal, $solopagadas){
try{
		if($item != null){
			$orden=intval($orden);
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item =:$item AND borrado=0"); 
			
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{
			//$solopagadas=intval($solopagadas);
			$solopagadas = (int)$solopagadas;
			if($solopagadas===0) {

				if($tipo=='todos'){
					$where='1=1';
				}elseif($tipo=='porpagar'){
					$where='status=0 AND borrado=0';
				}elseif($tipo=='pagado'){
					$where='status=1 AND borrado=0';
				}else{		//CANCELADOS
					$where='borrado=1';
				}


				if(isset($year) && !empty($year)){
					//$where.=' AND EXTRACT(YEAR FROM `fechafactura`) = "'.$year.'" ';
					$where.=' AND YEAR(`fechafactura`) = "'.$year.'" ';
				}

				if(isset($monthinicial) && !empty($monthinicial)){
					$where.=' AND MONTH(`fechafactura`) >= "'.$monthinicial.'" ';
				}

				if(isset($monthfinal) && !empty($monthfinal)){
					$where.=' AND MONTH(`fechafactura`) <= "'.$monthfinal.'" ';
				}
			}else{
				
				if($tipo=='pagado'){
					$where='status=1 AND borrado=0';
				}elseif($tipo=='borrado'){		//CANCELADOS
					$where='borrado=1';
				}else{
					$where='status=1 AND borrado=0';
				}

				if(isset($year) && !empty($year)){
					//$where.=' AND EXTRACT(YEAR FROM `fechapagado`) = "'.$year.'" ';
					$where.=' AND YEAR(`fechapagado`) = "'.$year.'" ';
				}

				if(isset($monthinicial) && !empty($monthinicial)){
					$where.=' AND MONTH(`fechapagado`) >= "'.$monthinicial.'" ';
				}

				if(isset($monthfinal) && !empty($monthfinal)){
					$where.=' AND MONTH(`fechapagado`) <= "'.$monthfinal.'" ';
				}

			}
			
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE ".$where); 

			$stmt -> execute();

			//echo sprintf('Consulta es: %s %s', $where, $solopagadas);
			//die();
			return $stmt -> fetchAll();

		}

} catch (Exception $e) {
	echo "Failed: " . $e->getMessage();
}        
	
		$stmt = null;
}

/*=============================================
	MOSTRAR SALDO DISPONIBLE
=============================================*/
static public function mdlMostrarSaldoDisponible($tabla, $item, $valor){
	try{
			if($item != null){
				$valor=intval($valor);

				$stmt = Conexion::conectar()->prepare("SELECT saldodisponible FROM $tabla WHERE $item =:$item"); 
								
				$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
	
				$stmt -> execute();
	
				return $stmt -> fetch();
	
				//echo sprintf('Consulta es: %s %s', $where, $solopagadas);
				//die();
	
			}
	
	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}        
		
			$stmt = null;
	}

/*=============================================
	MO SALDO DISPONIBLE
=============================================*/
static public function mdlModificarSaldoDisp($tabla, $item, $valor, $datos, $operacion){
	try{

		if($operacion=="resta"){

			if($datos["construccion"]==0){

				$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET saldodisponible=saldodisponible-(:saldodisponible) WHERE $item =:$item"); 
	
				$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
	
				$stmt->bindParam(":saldodisponible", $datos["subtotal"], PDO::PARAM_STR);

			}else{
				$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET saldoconstruccion=saldoconstruccion-(:saldoconstruccion) WHERE $item =:$item"); 
	
				$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
	
				$stmt->bindParam(":saldoconstruccion", $datos["subtotal"], PDO::PARAM_STR);
				
			}
			
			$stmt -> execute();

			return 'ok';

		}else{

			$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET saldodisponible=:saldodisponible+saldodisponible WHERE $item =:$item"); 

			$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt->bindParam(":saldodisponible", $datos["subtotanterior"], PDO::PARAM_STR);

			$stmt -> execute();

			return 'ok';

		}
	
	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}        
		
			$stmt = null;
	}

		
} //fin de la clase

