<?php
date_default_timezone_set('America/Mexico_City');
require_once "conexion.php";

class ModeloInventario{
	
static Public function MdlMostrarInventario($tabla, $campo, $valor){
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

}		
		
    

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
