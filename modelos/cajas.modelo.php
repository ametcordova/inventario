<?php
date_default_timezone_set('America/Mexico_City');
require_once dirname( __DIR__ ).'/config/conexion.php';
class ModeloCajas{


/*=============================================
	VER INGRESO A CAJA
=============================================*/
static public function mdlVerIngresoCaja($tabla, $item, $valor1, $valor2){

	$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item AND fecha_venta=curdate() AND estatus=0"); 
	$stmt -> bindParam(":".$item, $valor1, PDO::PARAM_STR);

	$stmt -> execute();
	
		return $stmt -> fetch();

	$stmt -> close();

	$stmt = null;

}

/*=============================================
	INGRESO CAJA
=============================================*/
static public function mdlIngresoCaja($tabla, $item, $valor1, $valor2, $valor3){
 try {
        
$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET monto_ingreso=:monto_ingreso, motivo_ingreso=:motivo_ingreso WHERE id_caja=:id_caja and fecha_venta=:fecha_venta and estatus=0");
     
        $fechadeHoy=date("Y-m-d");
        $stmt -> bindParam(":".$item, $valor3, PDO::PARAM_INT);
		$stmt->bindParam(":monto_ingreso", $valor1, PDO::PARAM_STR);
		$stmt->bindParam(":motivo_ingreso", $valor2, PDO::PARAM_STR);
		$stmt->bindParam(":fecha_venta", $fechadeHoy, PDO::PARAM_STR);
		if($stmt->execute()){
            
			 return "Ingresado";
            
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
	EGRESO CAJA
=============================================*/
static public function mdlEgresoCaja($tabla, $item, $valor1, $valor2, $valor3){
	try {
		   
   $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET monto_egreso=:monto_egreso, motivo_egreso=:motivo_egreso WHERE id_caja=:id_caja and fecha_venta=:fecha_venta and estatus=0");
		
		   $fechadeHoy=date("Y-m-d");
		   $stmt -> bindParam(":".$item, $valor3, PDO::PARAM_INT);
		   $stmt->bindParam(":monto_egreso", $valor1, PDO::PARAM_STR);
		   $stmt->bindParam(":motivo_egreso", $valor2, PDO::PARAM_STR);
		   $stmt->bindParam(":fecha_venta", $fechadeHoy, PDO::PARAM_STR);
		   if($stmt->execute()){
			   
				return "Egresado";
			   
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
	ACTIVAR CAJA
=============================================*/
static public function mdlActiveCaja($tabla, $item, $valor, $cajachica){
 try {
        
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_caja, cajachica, fecha_venta) VALUES (:id_caja, :cajachica,:fecha_venta)");
        $fechadeHoy=date("Y-m-d");
		$stmt->bindParam(":id_caja", $valor, PDO::PARAM_INT);
		$stmt->bindParam(":cajachica", $cajachica, PDO::PARAM_STR);
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
	CREAR CAJA
=============================================*/
static public function mdlIngresarCaja($tabla, $datos){
 try {
        
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(caja, descripcion, estado, id_usuario, ultusuario) VALUES (:caja, :descripcion, :estado, :id_usuario, :ultusuario)");

		$stmt->bindParam(":caja", $datos["caja"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
		$stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
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
	MOSTRAR CAJAS
=============================================*/
static public function mdlMostrarCajas($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT cja.id AS idcaja, cja.caja, cja.descripcion, cja.estado, 
			cja.id_usuario, user.id AS iduser, user.nombre FROM $tabla cja LEFT JOIN usuarios user ON user.id=cja.id_usuario WHERE cja.$item = :$item"); 
			
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			//$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
			$stmt = Conexion::conectar()->prepare("SELECT cja.id AS idcaja, cja.caja, cja.descripcion, cja.estado, 
			cja.id_usuario, user.id AS iduser, user.nombre, cja.ultmodificacion FROM $tabla cja LEFT JOIN usuarios user ON user.id=cja.id_usuario"); 
			

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

}

	/*=============================================
	EDITAR CAJA
	=============================================*/

	static public function mdlEditarCaja($tabla, $datos){
    try{
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET descripcion=:descripcion, estado=:estado, id_usuario=:id_usuario, ultusuario=:ultusuario WHERE id=:id");

		$stmt -> bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt -> bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
		$stmt -> bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
		$stmt -> bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);
		$stmt -> bindParam(":id", $datos["id"], PDO::PARAM_INT);

        $stmt->execute(); 
        
        if($stmt==true){
            return true;
        }else{
            return false;
        }
		$stmt->close();
		$stmt = null;

    }catch(Exception $e) {
                die($e->getMessage());
    }
}

	/*=============================================
	BORRAR CAJA
	=============================================*/

	static public function mdlBorrarCaja($tabla, $item, $valor, $estado){
        
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



/*=============================================
	VALIDAR CAJA ABIERTA
=============================================*/
static public function mdlChecarCaja($tabla, $item, $valor){

	$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item AND fecha_venta=curdate() AND estatus=0"); 
	$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

	$stmt -> execute();
	
		return $stmt -> fetch();

	$stmt -> close();

	$stmt = null;

}
	

//FUNCION PARA REPORTE EN TCPDF
static Public function MdlReporteCortex($tabla, $campo, $valor, $fechaventa){
    
//$where='alm.cant IS NOT NULL';      //SI NO EXISTE EN ALM, NO LO MUESTRE
    
$idcaja = (int) $valor;
if($idcaja>0){
 $where='sal.'.$campo.'="'.$valor.'"';
}; 
if($fechaventa!=null){
	$where.=' and sal.fecha_salida="'.$fechaventa.'" and sal.cerrado=1';
}else{
	$where.=' and sal.fecha_salida=curdate() and sal.cerrado=0';
}


$where.=' GROUP BY sal.id_producto,sal.precio_venta';
    
    
	$sql="SELECT sal.`num_salida`,sal.`fecha_salida`,sal.`id_producto`,pro.descripcion, pro.totaliza, sum(sal.`cantidad`) AS cant ,sal.`precio_venta`,SUM(IF(sal.`es_promo` = 0, sal.`cantidad`*sal.`precio_venta`,0)) AS sinpromo, SUM(IF(sal.`es_promo` = 1, sal.`precio_venta`,0)) AS promo, sal.es_promo, sal.id_caja, sal.cerrado, sal.ultmodificacion 
	FROM $tabla sal INNER JOIN productos pro ON sal.id_producto=pro.id WHERE ".$where;

	$stmt = Conexion::conectar()->prepare($sql);

	//$stmt -> bindParam(":$campo", $valor, PDO::PARAM_STR);

	$stmt -> execute();

	return $stmt -> fetchAll();      
    
    $stmt->close();
       
    $stmt=null;

}	

/*=============================================
	LISTAR CORTES DE VENTAS
=============================================*/
static public function mdlListarCorteVentas($tabla, $item, $valor, $orden, $fechadev1, $fechadev2){

	if($item != null){

	}else{
		$where='fecha_venta>="'.$fechadev1.'" AND fecha_venta<="'.$fechadev2.'" ';
  
		$where.=' ORDER BY '.$orden.' DESC';
  
		$stmt = Conexion::conectar()->prepare("SELECT ct.*, cj.caja FROM $tabla ct INNER JOIN cajas cj ON ct.id_caja=cj.id WHERE ".$where); 

		$stmt -> execute();

		return $stmt -> fetchAll();

	}

	$stmt -> close();

	$stmt = null;

}	

} //fin de la clase


//SELECT sal.`num_salida`,sal.`fecha_salida`,sal.`id_producto`,pro.descripcion, sum(sal.`cantidad`) AS cant ,sal.`precio_venta`,sum(sal.`precio_venta`*sal.`cantidad`) as venta, sal.cerrado FROM $tabla sal INNER JOIN productos pro ON sal.id_producto=pro.id WHERE ".$where