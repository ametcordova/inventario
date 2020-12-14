<?php
date_default_timezone_set('America/Mexico_City');
require_once "conexion.php";

class ModeloViaticos{


/*=============================================
	GUARDAR VIATICO 
=============================================*/
static public function mdlGuardarViatico($tabla, $datos){
	try {
	
		//$newFechaFact=date("Y-m-d",strtotime($datos["fechafactura"])); 
		//$newFechaEntrega=$datos["fechaentregado"]==""?null:$datos["fechaentregado"]; 

		   $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_tecnico, fecha_dispersion, concepto_dispersion, descripcion_dispersion, ultusuario) VALUES (:id_tecnico, :fecha_dispersion, :concepto_dispersion, :descripcion_dispersion, :ultusuario)");
   
		   $stmt->bindParam(":id_tecnico", 				$datos["id_tecnico"], PDO::PARAM_INT);
		   $stmt->bindParam(":fecha_dispersion", 		$datos["fecha_dispersion"], PDO::PARAM_STR);
		   //$stmt->bindParam(":id_mediodeposito", 		$datos["id_mediodeposito"], PDO::PARAM_INT);
		   $stmt->bindParam(":concepto_dispersion", 	$datos["concepto_dispersion"], PDO::PARAM_INT);
		   $stmt->bindParam(":descripcion_dispersion",  $datos["descripcion_dispersion"], PDO::PARAM_STR);
		   //$stmt->bindParam(":importe_dispersion", 		$datos["importe_dispersion"], PDO::PARAM_STR);
		   //$stmt->bindParam(":saldo_actual", 			$datos["saldo_actual"], PDO::PARAM_STR);
		   $stmt->bindParam(":ultusuario", 				$datos["ultusuario"], PDO::PARAM_INT);
		   if($stmt->execute()){
			// $getnumviatico=self::MdlAsignarNumViatico($tabla);
			// $ultnumeroviatico=$getnumviatico["num_viatico"];
			// $stmt1 = Conexion::conectar()->prepare("INSERT INTO tbl_viaticos_detalle(id_viatico, fecha, id_mediodeposito, comentario, importe_liberado) VALUES (:id_viatico, :fecha, :id_mediodeposito, :comentario, :importe_liberado)");
			// 	$stmt1->bindParam(":id_viatico",	 $ultnumeroviatico, PDO::PARAM_INT);
			// 	$stmt1->bindParam(":fecha",			 $datos["fecha_dispersion"], PDO::PARAM_STR);
			// 	$stmt1->bindParam(":id_mediodeposito",$datos["id_mediodeposito"], PDO::PARAM_INT);
			// 	$stmt1->bindParam(":comentario",	$datos["descripcion_dispersion"], PDO::PARAM_STR);
			// 	$stmt1->bindParam(":importe_liberado",$datos["importe_dispersion"], PDO::PARAM_STR);
			// 	if($stmt1->execute()){
			// 		return "ok";
			// 	}else{
			// 		return "error";
			// 	}
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
	GUARDAR FECHA COMPROBACION 
===============================================================================*/
static public function mdlGuardarAgregaViatico($tabla, $datos){
        
	$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_viatico, fecha, id_mediodeposito, comentario, importe_liberado) VALUES (:id_viatico, :fecha, :id_mediodeposito, :comentario, :importe_liberado)");

	$stmt->bindParam(":id_viatico", 		$datos["id_viatico"], PDO::PARAM_INT);
	$stmt->bindParam(":fecha", 				$datos["fecha"], PDO::PARAM_STR);
	$stmt->bindParam(":id_mediodeposito",	$datos["id_mediodeposito"], PDO::PARAM_INT);
	$stmt->bindParam(":comentario",			$datos["comentario"], PDO::PARAM_STR);
	$stmt->bindParam(":importe_liberado",	$datos["importe_liberado"], PDO::PARAM_STR);

	if($stmt -> execute()){

		$stmt = Conexion::conectar()->prepare("UPDATE tbl_viaticos SET saldo_actual=saldo_actual+(:saldo_actual) WHERE id=:id");

		$stmt->bindParam(":id", $datos["id_viatico"], PDO::PARAM_INT);
		$stmt->bindParam(":saldo_actual", $datos["importe_liberado"], PDO::PARAM_STR);
	
		if($stmt -> execute()){
			return "ok";
		}else{
			return "error";	
		}
	
	}else{
		return "error";	
	}
	$stmt -> close();
	$stmt = null;
}
/*=============================================*/


/* ===============================================================================   
	GUARDAR FECHA COMPROBACION 
===============================================================================*/
static public function mdlGuardarCheckup($tabla, $datos){
        
	$stmt = Conexion::conectar()->prepare("INSERT INTO tbl_viaticos_checkup(id_viatico, fecha_gasto, numerodocto, concepto_gasto, importe_gasto, ultusuario) VALUES (:id_viatico, :fecha_gasto, :numerodocto, :concepto_gasto, :importe_gasto, :ultusuario)");

	$stmt->bindParam(":id_viatico", 	$datos["id_viatico"], PDO::PARAM_INT);
	$stmt->bindParam(":fecha_gasto", 	$datos["fecha_gasto"], PDO::PARAM_STR);
	$stmt->bindParam(":numerodocto", 	$datos["numerodocto"], PDO::PARAM_STR);
	$stmt->bindParam(":concepto_gasto", $datos["concepto_gasto"], PDO::PARAM_STR);
	$stmt->bindParam(":importe_gasto",  $datos["importe_gasto"], PDO::PARAM_STR);
	$stmt->bindParam(":ultusuario", 	$datos["ultusuario"], PDO::PARAM_INT);

	if($stmt -> execute()){

		$stmt = Conexion::conectar()->prepare("UPDATE tbl_viaticos SET saldo_actual=saldo_actual-(:saldo_actual) WHERE id=:id");
																				
		$stmt->bindParam(":id", $datos["id_viatico"], PDO::PARAM_INT);
		$stmt->bindParam(":saldo_actual", $datos["importe_gasto"], PDO::PARAM_STR);
	
		if($stmt -> execute()){
	
			return "ok";
		
		}else{
	
			return "error";	
	
		}
	
	
	}else{

		return "error";	

	}

	$stmt = null;

}
/*=============================================*/

/*=============================================
	LISTAR VIATICOS DATATABLE
=============================================*/

static public function mdlMostrarViaticos($tabla, $item, $valor, $orden, $tipo, $year){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item =:$item AND estado=1"); 
			
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			if($tipo=='todos'){
				$where='1=1';
			}elseif($tipo=='porpagar'){
				$where='tb1.estado=1';
			}else{
				$where='tb1.estado=0';
			}
			
			if(isset($year) && !empty($year)){
				//$where.=' AND EXTRACT(YEAR FROM `fechafactura`) = $year';
				$where.=' AND YEAR(tb1.`fecha_dispersion`) = "'.$year.'" ';
			}

			$where.=" GROUP BY tb1.id";
			
			$stmt = Conexion::conectar()->prepare("SELECT tb1.*, sum(tb2.importe_liberado) AS totalliberado, us1.usuario 
			FROM $tabla tb1 
			LEFT JOIN usuarios us1 ON tb1.id_tecnico=us1.id
			LEFT JOIN tbl_viaticos_detalle tb2 ON tb1.id=tb2.id_viatico
			WHERE ".$where); 

			$stmt -> execute();

			return $stmt -> fetchAll();

		}


		$stmt = null;
}

/*=============================================
	ASIGNAR NUMERO DE VIATICOS
=============================================*/	    
static Public function MdlAsignarNumViatico($tabla){
     
	if($tabla !=null){    
	   // SELECT MAX(id) AS id FROM productos // SELECT MAX(num_salida) AS num_salida FROM hist_salidas
	   //$stmt=Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id DESC limit 1");
	   $query=Conexion::conectar()->prepare("SELECT MAX(id) AS num_viatico FROM $tabla");

	   $query->execute();
	   return $query->fetch();
	}else{
		   return false;
	}        
	   $query->close();
	   $query=null;
}

/*=============================================
	ASIGNAR NUMERO DE VIATICOS
=============================================*/	    
static Public function mdlPutCambiaEstatus($tabla, $item, $idviatico, $swestado){
 try {          
	if($tabla !=null){    
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estado=:estado WHERE $item = :$item");

        $stmt -> bindParam(":".$item, $idviatico, PDO::PARAM_INT);
        $stmt -> bindParam(":estado", $swestado, PDO::PARAM_INT);

		if($stmt -> execute()){
			return "ok";
		}else{
			return "error";	
		}

		$stmt = null;
	}

 }  catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
 };
}
   
/*=============================================
	OBTENER DATOS DE VIATICOS
=============================================*/	    
static Public function mdlGetViatico($tabla, $item1, $idviatico){
	try {     
	   $stmt=Conexion::conectar()->prepare("SELECT v1.id, v1.id_tecnico, u2.nombre, v1.fecha_dispersion, v1.descripcion_dispersion, v1.concepto_dispersion, v1.descripcion_dispersion, v1.importe_dispersion, v1.saldo_actual, v1.estado, v1.ultusuario, u1.nombre as nomusuario FROM $tabla v1 
	   INNER JOIN usuarios u1 ON v1.ultusuario=u1.id
	   INNER JOIN usuarios u2 ON v1.id_tecnico=u2.id
	   WHERE v1.$item1=:id");

	   $stmt -> bindParam(":".$item1, $idviatico, PDO::PARAM_STR);

	   $stmt -> execute();

	   return $stmt -> fetch();
  
	   
	   $stmt=null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	};
   
}

/*=============================================
	OBTENER DATOS DE VIATICOS
=============================================*/	    
static Public function mdlGetDatosViatico($tabla, $idviatico){
	try {    

		$stmt=Conexion::conectar()->prepare("SELECT u2.nombre AS comisionista, v1.fecha_dispersion, d1.fecha, d1.comentario,v1.descripcion_dispersion, u1.nombre AS disperso, v1.saldo_actual, v1.concepto_dispersion, v1.descripcion_dispersion, d1.importe_liberado, m1.establecimiento,d1.comentario
		FROM $tabla v1
		LEFT JOIN tbl_viaticos_detalle d1 ON v1.id=d1.id_viatico
		INNER JOIN usuarios u1 ON v1.ultusuario=u1.id
		INNER JOIN usuarios u2 ON v1.id_tecnico=u2.id
		LEFT JOIN mediodeposito m1 ON d1.id_mediodeposito=m1.id
		WHERE v1.id=:id ORDER BY d1.fecha ASC");
		
	//    $stmt=Conexion::conectar()->prepare("SELECT d1.id, v1.ultusuario, u1.nombre AS disperso, v1.concepto_dispersion, v1.descripcion_dispersion, v1.id_tecnico, u2.nombre AS comisionista, d1.id_viatico, d1.fecha,d1.id_mediodeposito, m1.establecimiento, d1.comentario,d1.importe_liberado, v1.saldo_actual
	//    FROM $tabla v1
	//    LEFT JOIN tbl_viaticos_detalle d1 ON d1.id_viatico=v1.id
	//    INNER JOIN usuarios u1 ON v1.ultusuario=u1.id
	//    INNER JOIN usuarios u2 ON v1.id_tecnico=u2.id
	//    INNER JOIN mediodeposito m1 ON d1.id_mediodeposito=m1.id
	//    WHERE v1.id=:id
	//    ORDER BY d1.fecha ASC");

	   $stmt -> bindParam(":id", $idviatico, PDO::PARAM_INT);

	   $stmt -> execute();

	   return $stmt -> fetchAll();
  
   
	   $stmt=null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	};
   
}

/*=============================================
	OBTENER DATOS DE VIATICOS COMPROBACION
=============================================*/	    
static Public function mdlGetViaticoCheck($tabla, $item2, $idviatico){
	try {     
	   $stmt=Conexion::conectar()->prepare("SELECT id, id_viatico, fecha_gasto, numerodocto, concepto_gasto, importe_gasto FROM $tabla WHERE $item2=:id_viatico ORDER BY fecha_gasto ASC");

	   $stmt -> bindParam(":".$item2, $idviatico, PDO::PARAM_STR);

	   $stmt -> execute();

	   return $stmt -> fetchAll();
  
	   $stmt=null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	};
   
}

/*=============================================
	OBTENER DATOS DE VIATICOS DETALLE
=============================================*/	    
static Public function mdlGetViaticoDet($tabla, $item2, $idviatico){
	try {     
	   $stmt=Conexion::conectar()->prepare("SELECT tb1.id, tb1.id_viatico,tb1.fecha,tb1.id_mediodeposito, tb1.comentario, tb1.importe_liberado, md.establecimiento 
	   FROM $tabla tb1 
	   LEFT JOIN mediodeposito md ON tb1.id_mediodeposito=md.id
	   WHERE tb1.$item2=:id_viatico ORDER BY tb1.fecha ASC");

	   $stmt -> bindParam(":".$item2, $idviatico, PDO::PARAM_STR);

	   $stmt -> execute();

	   return $stmt -> fetchAll();
  
	   $stmt=null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	};
   
}


} //fin de la clase


/*
SELECT u2.nombre AS comisionista, d1.fecha, d1.comentario,v1.descripcion_dispersion, u1.nombre AS disperso, v1.saldo_actual, v1.concepto_dispersion, v1.descripcion_dispersion, d1.importe_liberado
FROM `tbl_viaticos` v1
LEFT JOIN tbl_viaticos_detalle d1 ON v1.id=d1.id_viatico
INNER JOIN usuarios u1 ON v1.ultusuario=u1.id
INNER JOIN usuarios u2 ON v1.id_tecnico=u2.id
LEFT JOIN mediodeposito m1 ON d1.id_mediodeposito=m1.id
WHERE v1.`id`=14
*/