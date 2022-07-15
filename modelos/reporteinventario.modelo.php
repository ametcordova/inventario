<?php
date_default_timezone_set('America/Mexico_City');
require_once "conexion.php";

class ModeloInventario{

/*===================================================================== */
static Public function MdlMostrarInventario($tabla, $campo, $valor){
try{	
	if($valor=="todos"){
		$where="1=1 ORDER BY `surtir`";
	}else{
		$where="alm.$campo>0 ORDER BY `surtir`";
	}

	$sql="SELECT alm.`id`,  alm.`id_producto`, prod.sku, prod.id_categoria, cat.categoria, prod.descripcion, prod.`codigointerno`, 
	med.medida, prod.minimo, alm.cant, (alm.cant-prod.minimo) AS surtir  FROM $tabla alm 	
	INNER JOIN productos prod ON prod.id=alm.id_producto
	INNER JOIN categorias cat ON prod.id_categoria=cat.id
	INNER JOIN medidas med ON prod.id_medida=med.id
	WHERE ".$where;

	$stmt = Conexion::conectar()->prepare($sql);

	//$stmt -> bindParam(":$campo", $valor, PDO::PARAM_STR);

	$stmt -> execute();

	return $stmt -> fetchAll();      
    
	$stmt=null;
} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}
	
}		
		

/*===================================================================== */
static Public function MdlReporteInventarioAlmacen($idalmacen){
	try{	
	
		$sql="SELECT * FROM almacenes WHERE id=".$idalmacen;
	
		$stmt = Conexion::conectar()->prepare($sql);
	
		//$stmt -> bindParam(":$campo", $valor, PDO::PARAM_STR);
	
		$stmt -> execute();
	
		return $stmt -> fetch();      
		
		$stmt=null;
	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}
		
	}		
	

/*===================================================================== */
static Public function MdlReporteInventarioPorTecnico($tabla, $campo, $valor, $idalmacen){
	try{	
	
		//$sql="SELECT * FROM almacenes WHERE id=".$idalmacen;

		$sql="SELECT hist.`id_salida`,hist.`id_tecnico`,hist.`id_producto`, prod.codigointerno, prod.sku, prod.descripcion, prod.id_medida, med.medida, hist.`estatus`, SUM(hist.cantidad) as Total, SUM(hist.disponible) AS existe
		FROM hist_salidas hist 
		INNER JOIN productos prod ON hist.id_producto=prod.id
		INNER JOIN medidas med ON prod.id_medida=med.id
		WHERE hist.$campo=:campo AND hist.estatus=1 AND hist.id_tipomov>0 AND hist.id_almacen=:idalmacen GROUP BY hist.`id_tecnico`, hist.`id_producto`";
	
		$stmt = Conexion::conectar()->prepare($sql);
	
		$stmt -> bindParam(":campo", 	 $valor, PDO::PARAM_STR);
		$stmt -> bindParam(":idalmacen", $idalmacen, PDO::PARAM_STR);
	
		$stmt -> execute();
	
		return $stmt -> fetchAll();
		
		$stmt=null;
	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}
		
	}		
/*===================================================================== */	

}       //fin de la clase


/*
SELECT prod.id, prod.id_categoria, cat. categoria, prod.id_medida, med.medida, prod.codigointerno, prod.descripcion, prod.minimo,alm.cant, (alm.cant-prod.minimo) AS surtir, alm.precio_compra FROM productos prod
INNER JOIN categorias cat ON prod.id_categoria=cat.id
INNER JOIN medidas med ON prod.id_medida=med.id
LEFT JOIN alm_villah alm ON prod.id=alm.id_producto  
ORDER BY `surtir`

SELECT prod.id, prod.id_categoria, cat. categoria, prod.id_medida, med.medida, prod.codigointerno, prod.descripcion, prod.minimo,
			 alm.cant, (alm.cant-prod.minimo) AS surtir, alm.precio_compra FROM productos prod
			 INNER JOIN categorias cat ON prod.id_categoria=cat.id
			 INNER JOIN medidas med ON prod.id_medida=med.id
			 LEFT JOIN $tabla alm ON prod.id=alm.id_producto
			 WHERE alm.cant IS NOT NULL
			 ORDER BY `surtir`";
*/    
