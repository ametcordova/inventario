<?php
date_default_timezone_set('America/Mexico_City');
require_once "conexion.php";

class ModeloOServicios{

/*=============================================
	REGISTRO ORDEN DE SERVICIO
=============================================*/
static public function mdlGuardarOS($tabla, $datos, $productos, $cantidades){
	
	try {      
		 $estatus=1; 
		 $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(ordenservicio, telefono, id_almacen, id_tecnico, fecha_instalacion, nombrecontrato, datos_instalacion, datos_material, firma, observaciones, estatus, ultusuario) VALUES (:ordenservicio, :telefono, :id_almacen, :id_tecnico, :fecha_instalacion, :nombrecontrato, :datos_instalacion, :datos_material, :firma, :observaciones, :estatus, :ultusuario)");
			  
			  $stmt->bindParam(":ordenservicio", 	$datos["ordenservicio"], PDO::PARAM_STR);
			  $stmt->bindParam(":telefono", 		$datos["telefono"], PDO::PARAM_STR);
			  $stmt->bindParam(":id_almacen",       $datos["id_almacen"], PDO::PARAM_INT);
			  $stmt->bindParam(":id_tecnico", 	    $datos["id_tecnico"], PDO::PARAM_STR);
			  $stmt->bindParam(":fecha_instalacion",$datos["fecha_instalacion"], PDO::PARAM_STR);
			  $stmt->bindParam(":nombrecontrato", 	$datos["nombrecontrato"], PDO::PARAM_STR);
			  $stmt->bindParam(":datos_instalacion",$datos["datos_instalacion"], PDO::PARAM_STR);
			  $stmt->bindParam(":datos_material", 	$datos["datos_material"], PDO::PARAM_STR);
			  $stmt->bindParam(":firma", 			$datos["firma"], PDO::PARAM_STR);
			  $stmt->bindParam(":observaciones", 	$datos["observaciones"], PDO::PARAM_STR);
			  $stmt->bindParam(":estatus", 			$estatus, PDO::PARAM_STR);
			  $stmt->bindParam(":ultusuario",       $datos["ultusuario"], PDO::PARAM_INT);
			  $stmt->execute();

        if($stmt){
          return "ok";
        }else{
          return "error";
        }
  } catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
  }
}

/*=============================================
	REGISTRO ORDEN DE SERVICIO
=============================================*/
static public function mdlActualizarOS($tabla, $datos){
	
	try {      
			$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET ordenservicio=:ordenservicio, telefono=:telefono, fecha_instalacion=:fecha_instalacion, nombrecontrato=:nombrecontrato, datos_instalacion=:datos_instalacion, firma=:firma, observaciones=:observaciones, ultusuario=:ultusuario WHERE id=:id");
			  
			  $stmt->bindParam(":id", 				$datos["id"], PDO::PARAM_INT);
			  $stmt->bindParam(":ordenservicio", 	$datos["ordenservicio"], PDO::PARAM_STR);
			  $stmt->bindParam(":telefono", 		$datos["telefono"], PDO::PARAM_STR);
			  $stmt->bindParam(":fecha_instalacion",$datos["fecha_instalacion"], PDO::PARAM_STR);
			  $stmt->bindParam(":nombrecontrato", 	$datos["nombrecontrato"], PDO::PARAM_STR);
			  $stmt->bindParam(":datos_instalacion",$datos["datos_instalacion"], PDO::PARAM_STR);
			  $stmt->bindParam(":firma", 			$datos["firma"], PDO::PARAM_STR);
			  $stmt->bindParam(":observaciones", 	$datos["observaciones"], PDO::PARAM_STR);
			  $stmt->bindParam(":ultusuario",       $datos["ultusuario"], PDO::PARAM_INT);
			  $stmt->execute();

        if($stmt){
          return "ok";
        }else{
          return "error";
        }
  } catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
  }
}

/*=============================================
	REGISTRO ORDEN DE SERVICIO
=============================================*/
static public function mdlActualizarImagen($tabla, $firma, $id){
	
	try {      
			$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET firma=:firma WHERE id=:id");
			  
			  $stmt->bindParam(":id", 				$id, PDO::PARAM_INT);
			  $stmt->bindParam(":firma", 			$firma, PDO::PARAM_STR);
			  $stmt->execute();

        if($stmt){
          return "ok";
        }else{
          return "error";
        }
  } catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
  }
}

/*=============================================
 UPDATE HIST_SALIDAS SEGUN ORDEN DE SERVICIO
=============================================*/
static public function mdlActualizarTransito($tabla, $datos, $productos, $cantidades){
	$cant=0;
	//$entra=0;
	try {      

		$contador = count($productos);    //CUANTOS PRODUCTOS VIENEN PARA EL FOR 
		//ACTUALIZA HIST_SALIDAS
		for($i=0;$i<$contador;$i++) {
			//MUESTRA TODOS LOS REGISTROS CON EL PROD. A ACTUALIZAR EXISTENCIAS
			$sql=Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id_producto = :id_producto AND id_tecnico=:id_tecnico AND id_almacen=:id_almacen AND id_tipomov>0 AND disponible>0 ORDER BY fecha_salida ASC");
			$sql->bindParam(":id_producto",$productos[$i], PDO::PARAM_INT);
			$sql->bindParam(":id_tecnico", $datos["id_tecnico"], PDO::PARAM_INT);
			$sql->bindParam(":id_almacen", $datos["id_almacen"], PDO::PARAM_INT);
			$sql->execute();
			
			$respuesta = $sql->fetchAll(PDO::FETCH_ASSOC);
			$cant=(float)$cantidades[$i];
			if($respuesta){
				
				foreach ($respuesta as $value) {
					
					$id=$value['id']; $disponible=(float)$value['disponible'];

					if($disponible===$cant){        //Disponible es igual que la cant que sale
						$rsp=actualizaDataOS($tabla, $cant, $datos["ultusuario"], $id, $disponible);
						//$entra+=1;
						break 1;	//SALE DEL FOREACH
					}elseif($disponible>$cant){        //Disponible es Mayor que la cant que sale
						$rsp=actualizaDataOS($tabla, $cant, $datos["ultusuario"], $id, $disponible);
						//$entra+=2;
						break 1;	//SALE DEL FOREACH
					}elseif($disponible<$cant){        //Disponible es Menor que la cant que sale
						$rsp=actualizaDataOS($tabla, $cant, $datos["ultusuario"], $id, $disponible);
						$cant=$rsp;
						//$entra+=3;
					}else{
						//$entra+=4;
					}

				}	//FIN DEL FOREACH

			}
			
		}		//FIN DEL FOR

		//return 'resp:'.$rsp.' id:'.$id.' disp:'.$disponible.' cant:'.$cant.' entra:'.$entra;
		return http_response_code(200);

		$sql = null;		

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}
}		//fin de la funcion



/*=============================================
	LISTAR ORDENES DE SERVICIO
=============================================*/
static public function mdlListarOServicios($tabla, $item, $valor, $orden, $fechadev1, $fechadev2){
try{

 	//$where='1=1' AND os.estatus>0;
		  
	$where="os.fecha_instalacion>='".$fechadev1."' AND os.fecha_instalacion<='".$fechadev2."' ";
	$admon=$_SESSION["perfil"];
	if($admon=="Administrador"){
		$where.=" AND os.id_tecnico>0";
	}else{
		$where.=" AND os.id_tecnico='".$valor."' ";
	}
	

    $stmt = Conexion::conectar()->prepare("SELECT os.`id`,os.`id_empresa`, os.id_tecnico, os.`ordenservicio`,os.`telefono`,user.nombre AS tecnico, alm.nombre AS almacen, os.`fecha_instalacion`, os.`estatus`, os.factura, os.ultusuario, usu.nombre AS capturo
    FROM $tabla os
    INNER JOIN almacenes alm ON alm.id=os.id_almacen
    INNER JOIN usuarios user ON user.user=os.id_tecnico
    INNER JOIN tecnicos tec ON tec.id=os.id_tecnico
    INNER JOIN usuarios usu ON usu.id=os.ultusuario
    WHERE ".$where); 

    $stmt -> execute();

    return $stmt -> fetchAll();

    $stmt = null;
    
  } catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
  }
  
}
        
/*===================================================================== */
static Public function MdlReporteExistenciaPorTecnico($tabla, $valor, $idalmacen, $idtecnico, $orden){
	try{	
	
		$sql="SELECT hist.id_salida,hist.id_tecnico,hist.id_producto, prod.codigointerno, prod.sku, prod.descripcion, prod.id_medida, med.medida, hist.estatus, SUM(hist.cantidad) as Total, SUM(hist.disponible) AS existe, hist.id_tipomov, prod.conseries
		FROM $tabla hist
		INNER JOIN productos prod ON hist.id_producto=prod.id
		INNER JOIN medidas med ON prod.id_medida=med.id
		WHERE hist.id_tecnico=:id_tecnico AND (prod.descripcion LIKE '$valor%' OR prod.codigointerno LIKE '$valor%') AND hist.id_tipomov>0 AND hist.id_almacen=:id_almacen GROUP BY hist.$orden ASC";

		$stmt = Conexion::conectar()->prepare($sql);
	
		//$stmt -> bindParam(":campo", $valor, PDO::PARAM_STR);
		$stmt -> bindParam(":id_almacen", $idalmacen, PDO::PARAM_INT);
		$stmt -> bindParam(":id_tecnico", $idtecnico, PDO::PARAM_INT);
	
		$stmt -> execute();
	
		return $stmt -> fetchAll();
		
		$stmt=null;
	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}	
		
	}		
/*==========================================================================================*/
/*=============================================
	
=============================================*/	
static Public function mdlObtenerOS($tabla, $campo, $valor){
	try{     
		if($campo !=null){    
	
			$sql="SELECT os.id, os.ordenservicio, os.telefono, os.id_tecnico, tec.nombre AS tecnico, os.fecha_instalacion, os.nombrecontrato, os.datos_instalacion, os.datos_material, os.estatus, os.factura, os.firma FROM $tabla os
			INNER JOIN tecnicos tec ON tec.id=os.id_tecnico
			WHERE os.$campo=:$campo";
	
		   $stmt=Conexion::conectar()->prepare($sql);
		   
		   $stmt->bindParam(":".$campo, $valor, PDO::PARAM_INT);
		   
		   $stmt->execute();
		   
		   return $stmt->fetch();
		   
		   
		}else{
	
			   return false;
	
		}        
		   
		   $stmt=null;
	
	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}
	}    

/*=============================================
	REPORTE DE MAT. UTILIZADO EN LA OS
=============================================*/	
static Public function mdlObtenerMaterialOS($tabla, $campo, $valor){
	try{     
		if($campo !=null){    
	
			$sql="SELECT os.id, os.ordenservicio, os.telefono, os.id_tecnico, tec.nombre AS tecnico, os.fecha_instalacion, os.nombrecontrato, os.datos_instalacion, os.datos_material, os.estatus, os.factura FROM $tabla os
			INNER JOIN tecnicos tec ON tec.id=os.id_tecnico
			WHERE os.id IN ($valor)";
	
		   $stmt=Conexion::conectar()->prepare($sql);
		   
		   //$stmt->bindParam(":".$campo, $valor, PDO::PARAM_INT);
		   
		   $stmt->execute();
		   
		   return $stmt->fetchAll();
		   
		   
		}else{
	
			   return false;
	
		}        
		   
		   $stmt=null;
	
	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}
	}    

/*==========================================================================================*/
static Public function mdlCambiarEstadoOS($tabla, $campo, $valor, $estado, $factura){
	try{     
		if($campo !=null){    
	
		$sql="UPDATE $tabla SET estatus=:estatus, factura=:factura WHERE $campo=:$campo";
	
		$stmt=Conexion::conectar()->prepare($sql);
		   
		   $stmt->bindParam(":".$campo, $valor, PDO::PARAM_INT);
		   $stmt->bindParam(":estatus", $estado, PDO::PARAM_INT);
		   $stmt->bindParam(":factura", $factura, PDO::PARAM_STR);
		   
		   $stmt->execute();
		   
		   return $stmt;
		   
		   
		}else{
	
		   return false;
	
		}        
		   
		$stmt=null;
	
	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}
}    

/*=============================================

=============================================*/	
static Public function mdlGetDataOServicios($tabla, $campo, $valor, $status){
	try{     
		if($valor !=null && $status==null){    
	
			$sql="SELECT os.id, os.ordenservicio, os.telefono, os.id_almacen, alm.nombre AS nombrealmacen, os.id_tecnico, tec.nombre AS tecnico, os.fecha_instalacion, os.nombrecontrato, os.datos_instalacion, os.datos_material, os.estatus, os.factura, os.firma, os.observaciones FROM $tabla os
			INNER JOIN tecnicos tec ON tec.id=os.id_tecnico
			INNER JOIN almacenes alm ON alm.id=os.id_almacen
			WHERE os.$campo=:$campo";
	
		   $stmt=Conexion::conectar()->prepare($sql);
		   
		   $stmt->bindParam(":".$campo, $valor, PDO::PARAM_INT);
		   
		   $stmt->execute();
		   
		   return $stmt->fetch(PDO::FETCH_ASSOC);

		   $stmt=null;
		   
		}elseif ($valor !=null && $status!=null){
	
			$sql="SELECT `id`, `codigo`, `codigointerno`, `descripcion`, `estado` FROM `productos` WHERE estado=1 AND id IN ($valor) ORDER BY id ASC";

			$stmt=Conexion::conectar()->prepare($sql);
		   
			//$stmt->bindParam(":".$campo, $valor, PDO::PARAM_INT);
			
			$stmt->execute();
			
			return $stmt->fetchAll();
 
		}else{
			return false;
	
		}        
		   
		   $stmt=null;
	
	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}
}    
/************************************************************************************* */
	static Public function mdlGetDataNumOS($tabla, $campo, $numos){

		try{     
			$sql="SELECT ordenservicio FROM $tabla WHERE $campo=:$campo";
		
			$stmt=Conexion::conectar()->prepare($sql);
			
			$stmt->bindParam(":".$campo, $numos, PDO::PARAM_STR);
			
			$stmt->execute();
			
			return $stmt->fetch();
			
		} catch (Exception $e) {
				echo "Failed: " . $e->getMessage();
		}
			
	}
/************************************************************************************* */

/************************************************************************************* */
static Public function mdlTraerIdOs($tabla, $item, $valor){

	try{     
		$sql="SELECT * FROM $tabla WHERE $item=:$item";
	
		$stmt=Conexion::conectar()->prepare($sql);
		
		$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
		
		$stmt->execute();
		
		return $stmt->fetchAll();
		
	} catch (Exception $e) {
			echo "Failed: " . $e->getMessage();
	}
		
}
/************************************************************************************* */

}       //fin de la clase

/********************************************************************************* */
// FUNCION QUE REALIZA LA ACTUALIZACION DE LA CANT DISPONIBLE EN EL HIST_SALIDAS
/********************************************************************************* */
function actualizaDataOS($tabla, $cant, $ultusuario, $id, $disponible){
	$restadisp=0;
	try {
		$query=Conexion::conectar()->prepare("UPDATE $tabla SET disponible=disponible-(:disponible), ultusuario=:ultusuario 
		WHERE id = :id");

		if($cant===$disponible){
			$restadisp=$cant;
			$nvosaldo=0;

		}elseif($cant<$disponible) {
			$restadisp=$cant;
			$nvosaldo=0;

		}elseif($cant>$disponible){
			$restadisp=$disponible;
			$nvosaldo=$cant-$disponible;

		}else{
			$nvosaldo=0;
		};

		$query->bindParam(":id"		   ,$id, PDO::PARAM_INT);
		$query->bindParam(":disponible", $restadisp, PDO::PARAM_STR);
		$query->bindParam(":ultusuario", $ultusuario, PDO::PARAM_INT);

		$query->execute();
		return $nvosaldo;
		//code...
	} catch (\Throwable $th) {
		//throw $th;
		return $th;
	}
}



/************************************************************************************* */
/*SELECT hist.`id_salida`,hist.`id_tecnico`,hist.`id_producto`, prod.codigointerno, prod.sku, prod.descripcion, prod.id_medida, med.medida, hist.`estatus`, SUM(hist.cantidad) as Total, SUM(hist.disponible) AS existe, hist.id_tipomov
FROM hist_salidas hist
INNER JOIN productos prod ON hist.id_producto=prod.id
INNER JOIN medidas med ON prod.id_medida=med.id
WHERE hist.id_tecnico=13 AND prod.descripcion LIKE 'ON%' OR prod.codigointerno LIKE 'ON%' AND hist.id_tipomov=1 AND hist.id_almacen=1 GROUP BY hist.`id_producto`

SELECT * FROM hist_salidas WHERE id_producto = 4 AND id_tecnico=13 AND id_almacen=1 AND id_tipomov=1

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET disponible=disponible-(:disponible), ultusuario=:ultusuario WHERE id_producto = :id_producto AND id_tecnico=:id_tecnico AND id_almacen=:id_almacen AND disponible>0 LIMIT 1");
		$stmt->bindParam(":id_producto",$productos[$i], PDO::PARAM_INT);
		$stmt->bindParam(":disponible", $cantidades[$i], PDO::PARAM_STR);
		$stmt->bindParam(":id_tecnico", $datos["id_tecnico"], PDO::PARAM_INT);
		$stmt->bindParam(":id_almacen", $datos["id_almacen"], PDO::PARAM_INT);
		$stmt->bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);
		$stmt->execute();
	}		//FIN DEL FOR
		if($stmt){

			//ACTUALIZA EXISTENCIA EN EL ALMACEN SELECCIONADO X
			// for($i=0;$i<$contador;$i++) {
			// 	$stmt = Conexion::conectar()->prepare("UPDATE $tabla_almacen SET cant=cant-(:cant), ultusuario=:ultusuario WHERE id_producto = :id_producto");
			// 	$stmt->bindParam(":id_producto",$productos[$i], PDO::PARAM_INT);
			// 	$stmt->bindParam(":cant", $cantidades[$i], PDO::PARAM_STR);
			// 	$stmt->bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);
			// 	$stmt->execute();
			// }		//FIN DEL FOR


		SELECT hist.id_salida,hist.id_tecnico,hist.id_producto, prod.codigointerno, prod.sku, prod.descripcion, prod.id_medida, med.medida, hist.estatus, SUM(hist.cantidad) as Total, SUM(hist.disponible) AS existe, hist.id_tipomov, prod.conseries
		FROM hist_salidas hist
		INNER JOIN productos prod ON hist.id_producto=prod.id
		INNER JOIN medidas med ON prod.id_medida=med.id
		WHERE hist.id_tecnico=13 AND (prod.descripcion LIKE '00014%' OR prod.codigointerno LIKE '00014%') AND hist.id_tipomov>1 AND hist.id_almacen=1 GROUP BY hist.id_producto ASC

		$stmt=Conexion::conectar()->prepare("UPDATE $tabla SET disponible=disponible-(:disponible), ultusuario=:ultusuario 
		WHERE id = :id AND id_producto = :id_producto AND id_tecnico= :id_tecnico AND id_almacen= :id_almacen AND id_tipomov>0 AND disponible>0
		ORDER BY fecha_salida");

//Si el usuario no existe lo insertamos,
//y si ya existe lo actualizamos
$consulta_sql = "INSERT INTO usuarios(id, imagen, nombre)\n".
                "    VALUES ('$id', '$img', '$nombre')\n".
                "ON DUPLICATE KEY\n".
                "    UPDATE imagen = VALUES(imagen), nombre=VALUES(nombre);";		

*/	
