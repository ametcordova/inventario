<?php
date_default_timezone_set('America/Mexico_City');
require_once dirname( __DIR__ ).'/modelos/conexion.php';
class ModeloAjusteInventario{

/*=============================================
	REGISTRO DE PRODUCTO
=============================================*/
static public function mdlGuardarAjusteInv($tablaAjuste, $tabla, $datos, $productos, $cantidades, $codigosinternos, $tipomov, $id_tipomov){
	try {      
		//$contador = count($id_producto);    //CUANTO PRODUCTOS VIENEN PARA EL FOR
		$nombremes_actual = strtolower(date('F'));
		$tablakardex="kardex_tuxtla";
		//SCRIP QUE REGISTRA EL AJUSTE
		 $estatus=1; 
		 $contador = count($productos);    //CUANTO PRODUCTOS VIENEN PARA EL FOR 
		 $stmt = Conexion::conectar()->prepare("INSERT INTO $tablaAjuste(fecha_ajuste, tipomov, id_almacen, motivo_ajuste,datos_ajuste, estatus, id_usuario) VALUES (:fecha_ajuste, :tipomov, :id_almacen, :motivo_ajuste, :datos_ajuste, :estatus, :id_usuario)");
			  
			  $stmt->bindParam(":fecha_ajuste", 	$datos["fecha_ajuste"], PDO::PARAM_STR);
			  $stmt->bindParam(":tipomov", 			$datos["tipomov"], PDO::PARAM_STR);
			  $stmt->bindParam(":id_almacen",       $datos["id_almacen"], PDO::PARAM_INT);
			  $stmt->bindParam(":motivo_ajuste", 	$datos["motivo_ajuste"], PDO::PARAM_STR);
			  $stmt->bindParam(":datos_ajuste", 	$datos["datos_ajuste"], PDO::PARAM_STR);
			  $stmt->bindParam(":estatus", 			$estatus, PDO::PARAM_STR);
			  $stmt->bindParam(":id_usuario",       $datos["id_usuario"], PDO::PARAM_INT);
			  $stmt->execute();
		  
			  if($stmt){
				  //ACTUALIZA EXISTENCIA EN EL ALMACEN SELECCIONADO
				  for($i=0;$i<$contador;$i++) { 

					 if($tipomov=="E"){		//SI ES ENTRADA
						//CHECA SI EXISTE EL PRODUCTO
						$chekExist=Conexion::conectar()->prepare("SELECT id_producto FROM $tabla WHERE id_producto=$productos[$i]");
						$chekExist->execute();
						$cuantosReg = $chekExist->fetchAll();	
						if(count($cuantosReg) > 0) {	//SI EXISTE, ACTUALIZA
							$stmt1 = Conexion::conectar()->prepare("UPDATE $tabla SET cant=:cant+cant, ultusuario=:ultusuario WHERE id_producto = :id_producto");
							$stmt1->bindParam(":id_producto", $productos[$i], PDO::PARAM_INT);
							$stmt1->bindParam(":cant", $cantidades[$i], PDO::PARAM_INT);
							$stmt1->bindParam(":ultusuario", $datos["id_usuario"], PDO::PARAM_INT);
							$stmt1->execute();
							
							//GUARDA EN KARDEX DEL ALMACEN ELEGIDO
							$query = Conexion::conectar()->prepare("UPDATE $tablakardex SET $nombremes_actual=:$nombremes_actual+$nombremes_actual WHERE id_producto = :id_producto");
							$query->bindParam(":id_producto", $productos[$i], PDO::PARAM_INT);
							$query->bindParam(":".$nombremes_actual, $cantidades[$i], PDO::PARAM_STR);
							$query->execute();

						}else{		//DE LO CONTRARIO INSERTA
							$stmt2 = Conexion::conectar()->prepare("INSERT INTO $tabla (id_producto, codigointerno, cant, fecha_entrada, ultusuario) VALUES (:id_producto, :codigointerno, :cant, :fecha_entrada, :ultusuario)");
							$stmt2->bindParam(":id_producto", $productos[$i], PDO::PARAM_INT);
							$stmt2->bindParam(":codigointerno", $codigosinternos[$i], PDO::PARAM_STR);
							$stmt2->bindParam(":cant", $cantidades[$i], PDO::PARAM_INT);
							$stmt2->bindParam(":fecha_entrada", $datos["fecha_ajuste"], PDO::PARAM_STR);
							$stmt2->bindParam(":ultusuario", $datos["id_usuario"], PDO::PARAM_INT);
							$stmt2->execute();

							//GUARDA EN KARDEX DEL ALMACEN ELEGIDO
							$query = Conexion::conectar()->prepare("INSERT INTO $tablakardex (id_producto, $nombremes_actual) VALUES (:id_producto, :$nombremes_actual)");
							$query->bindParam(":id_producto", $productos[$i], PDO::PARAM_INT);
							$query->bindParam(":".$nombremes_actual, $cantidades[$i], PDO::PARAM_STR);
							$query->execute();

						}	

					 }else{		//SI ES SALIDA
						//CHECA SI EXISTE EL PRODUCTO
						$chekExist=Conexion::conectar()->prepare("SELECT id_producto FROM $tabla WHERE id_producto=$productos[$i]");
						$chekExist->execute();
						$cuantosReg = $chekExist->fetchAll();	
						if(count($cuantosReg) > 0) {	//SI EXISTE, ACTUALIZA
							$stmt1 = Conexion::conectar()->prepare("UPDATE $tabla SET cant=cant-(:cant), ultusuario=:ultusuario WHERE id_producto = :id_producto");
							$stmt1->bindParam(":id_producto", $productos[$i], PDO::PARAM_INT);
							$stmt1->bindParam(":cant", $cantidades[$i], PDO::PARAM_INT);
							$stmt1->bindParam(":ultusuario", $datos["id_usuario"], PDO::PARAM_INT);
							$stmt1->execute();

							//GUARDA EN KARDEX DEL ALMACEN ELEGIDO
							$query = Conexion::conectar()->prepare("UPDATE $tablakardex SET $nombremes_actual=$nombremes_actual-(:$nombremes_actual) WHERE id_producto = :id_producto");
							$query->bindParam(":id_producto", $productos[$i], PDO::PARAM_INT);
							$query->bindParam(":".$nombremes_actual, $cantidades[$i], PDO::PARAM_STR);
							$query->execute();

						}else{		//DE LO CONTRARIO INSERTA
							$qty=$cantidades[$i];
							$stmt2 = Conexion::conectar()->prepare("INSERT INTO $tabla (id_producto, codigointerno, cant, fecha_entrada, ultusuario) VALUES (:id_producto, :codigointerno, cant-(:cant), :fecha_entrada, :ultusuario)");
							$stmt2->bindParam(":id_producto", $productos[$i], PDO::PARAM_INT);
							$stmt2->bindParam(":codigointerno", $codigosinternos[$i], PDO::PARAM_STR);
							$stmt2->bindParam(":cant", $cantidades[$i], PDO::PARAM_INT);
							$stmt2->bindParam(":fecha_entrada", $datos["fecha_ajuste"], PDO::PARAM_STR);
							$stmt2->bindParam(":ultusuario", $datos["id_usuario"], PDO::PARAM_INT);
							$stmt2->execute();
							
							//GUARDA EN KARDEX DEL ALMACEN ELEGIDO
							$query = Conexion::conectar()->prepare("INSERT INTO $tablakardex (id_producto, $nombremes_actual) VALUES (:id_producto, $nombremes_actual-(:$nombremes_actual))");
							$query->bindParam(":id_producto", $productos[$i], PDO::PARAM_INT);
							$query->bindParam(":".$nombremes_actual, $cantidades[$i], PDO::PARAM_STR);
							$query->execute();
							
					 	}	
					}				  

				   }		//FIN DEL FOR
				   
				   if($stmt){
					  return "ok";
				   }else{
					  return "error";
				   }

			  }else{
			   return "error";
			  }
		  
		  $stmt = null;
		  $stmt1 = null;
		  $stmt2 = null;
		  $chekExist = null;
		  $query=null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
   }
	   
  
}

 /*=============================================
	MOSTRAR 
=============================================*/
static public function mdlObtenerLastId($tabla, $item, $valor){
	try {
			
		// SELECT MAX(id) AS id FROM productos
        $stmt=Conexion::conectar()->prepare("SELECT MAX(id) AS id FROM $tabla");

        $stmt->execute();
        
        return $stmt->fetch();

		//$stmt -> close();

		$stmt = null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}

}  

/*=============================================
	LISTAR AJUSTE DE INVENTARIO
=============================================*/
static public function mdlListarAjusteInv($tabla, $item, $valor, $orden, $fechadev1, $fechadev2){
try{
	if($item != null){
  
	 // $stmt = Conexion::conectar()->prepare("SELECT cja.id AS idcaja, cja.caja, cja.descripcion, cja.estado, cja.id_usuario, user.id, user.nombre FROM $tabla cja LEFT JOIN usuarios user ON user.id=cja.id_usuario WHERE cja.$item = :$item"); 
	  //$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
  
	  //$stmt -> execute();
  
	  //return $stmt -> fetch();
  
	}else{
  
	  //$where='1=1';
		  
	  $where='ai.fecha_ajuste>="'.$fechadev1.'" AND ai.fecha_ajuste<="'.$fechadev2.'" ';
  
	  $where.=' ORDER BY ai.id DESC';
  
	  $sql="SELECT ai.id, tm.nombre_tipo, ai.fecha_ajuste, ai.motivo_ajuste, ai.id_almacen, alm.nombre AS almacen, ai.id_usuario, usu.nombre AS usuario FROM $tabla ai 
	  INNER JOIN tipomovimiento tm ON ai.tipomov=tm.id 
	  INNER JOIN almacenes alm ON ai.id_almacen=alm.id 
	  INNER JOIN usuarios usu ON ai.id_usuario=usu.id 
	  WHERE ".$where;
  
	  $stmt = Conexion::conectar()->prepare($sql);
  
	  $stmt -> execute();
  
	  return $stmt -> fetchAll();
	  //return $sql;
  
	}
  
	$stmt = null;

} catch (Exception $e) {
	echo "Failed: " . $e->getMessage();
}

}	

/*=============================================
	REPORTE NOTA DE SALIDAS
=============================================*/	
static Public function MdlReporteAjusteInv($tabla, $campo, $valor){
try{     
	if($campo !=null){    

		$sql="SELECT ai.id, ai.tipomov, tm.nombre_tipo, ai.fecha_ajuste, ai.motivo_ajuste, ai.datos_ajuste, ai.id_almacen, alm.nombre AS almacen, ai.id_usuario, usu.nombre AS nombreusuario, ai.ultmodificacion FROM $tabla ai 
		INNER JOIN tipomovimiento tm ON ai.tipomov=tm.id 
		INNER JOIN almacenes alm ON ai.id_almacen=alm.id 
		INNER JOIN usuarios usu ON ai.id_usuario=usu.id 
		WHERE ai.$campo=:$campo AND estatus=1";

	   $stmt=Conexion::conectar()->prepare($sql);
	   
	   $stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
	   
	   $stmt->execute();
	   
	   return $stmt->fetchAll();
	   
		//if ( $stmt->rowCount() > 0 ) { do something here }
	   
	}else{

		   return false;

	}        
	   
	   $stmt=null;

} catch (Exception $e) {
	echo "Failed: " . $e->getMessage();
}
}    

/*=============================================
	MOSTRAR PRODUCTOS  
=============================================*/
static public function mdlDatosProducto($tabla, $item, $valor){
try{
	$stmt = Conexion::conectar()->prepare("SELECT prod.id, prod.id_medida, prod.codigointerno, prod.descripcion, prod.sku, prod.conseries, med.medida 
	FROM $tabla prod
	INNER JOIN medidas med ON med.id=prod.id_medida
	WHERE prod.$item=:$item AND estado=1");

	$stmt -> bindParam(":$item", $valor, PDO::PARAM_STR);
	//$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);  tmb funciona

	$stmt -> execute();

		return $stmt -> fetch();

	$stmt = null;

} catch (Exception $e) {
	echo "Failed: " . $e->getMessage();
}

}

/*=============================================
	MOSTRAR TIPO DE MOV DE SALIDA
=============================================*/
static public function MdlMostrarTipoMovs($tabla, $item, $valor){
try{	
	if($item !=null){    
	  $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item=:$item");
  
	  $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
  
	  $stmt -> execute();
  
	   return $stmt -> fetchAll();
	   
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

} //fin de la clase
?>