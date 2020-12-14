<?php
date_default_timezone_set('America/Mexico_City');
require_once "config/conexion.php";

class ModeloInventario1{

    
//PRODUCTOS BAJO STOCK
static Public function MdlProductosBajoStock($tabla, $campo, $valor){
    
$sql="select pri.id_producto, (pri.cant-pro.stock) AS surtir from $tabla pri
INNER JOIN productos pro ON pri.id_producto=pro.id where (pri.cant-pro.stock)<1";

	$stmt = Conexion::conectar()->prepare($sql);

	$stmt -> execute();

	return $stmt -> fetchAll();      
    
    $stmt->close();
       
    $stmt=null;

}		

//FUNCION PARA VISUALIZAR CON DATATABLE
static Public function MdlMostrarInventario1($tabla, $campo, $valor){

	$where='alm.cant IS NOT NULL';      //SI NO EXISTE EN ALM, NO LO MUESTRE
		
	$idFam = (int) $valor;
	if($idFam>0){
	 $where.=' AND prod.'.$campo.'="'.$valor.'"';
	}; 
	$where.=' ORDER BY `id_categoria`';
		
	$sql="SELECT prod.id, prod.id_familia, prod.id_categoria, cat. categoria, prod.id_medida, med.medida, prod.codigointerno, prod.descripcion, prod.stock,
				 alm.cant, (alm.cant-prod.stock) AS surtir, alm.precio_venta FROM productos prod
				 INNER JOIN categorias cat ON prod.id_categoria=cat.id
				 INNER JOIN medidas med ON prod.id_medida=med.id
				 LEFT JOIN $tabla alm ON prod.id=alm.id_producto
				 WHERE ".$where;
	
		$stmt = Conexion::conectar()->prepare($sql);
	
		//$stmt -> bindParam(":$campo", $valor, PDO::PARAM_STR);
	
		$stmt -> execute();
	
		return $stmt -> fetchAll();      
		
		$stmt->close();
		$stmt=null;
	
	}	
	
//FUNCION PARA REPORTE EN TCPDF
static Public function MdlReporteInventario1($tabla, $campo, $valor){
    
	$where='alm.cant IS NOT NULL';      //SI NO EXISTE EN ALM, NO LO MUESTRE
		
	$idFam = (int) $valor;
	if($idFam>0){
	 $where.=' AND prod.'.$campo.'="'.$valor.'"';
	}; 
	$where.=' ORDER BY `id_categoria`';
		
		
	$sql="SELECT prod.id, prod.id_familia, fa.familia, prod.id_categoria, cat. categoria, prod.id_medida, med.medida, prod.codigointerno, prod.descripcion, prod.stock, alm.cant, prod.unidadxcaja, (alm.cant-prod.stock) AS surtir, prod.precio_compra, prod.precio_venta FROM productos prod
	LEFT JOIN familias fa ON prod.id_familia=fa.id
	INNER JOIN categorias cat ON prod.id_categoria=cat.id
	INNER JOIN medidas med ON prod.id_medida=med.id
	LEFT JOIN $tabla alm ON prod.id=alm.id_producto
	WHERE ".$where;
	
		$stmt = Conexion::conectar()->prepare($sql);
	
		//$stmt -> bindParam(":$campo", $valor, PDO::PARAM_STR);
	
		$stmt -> execute();
	
		return $stmt -> fetchAll();      
		
		$stmt->close();
		   
		$stmt=null;
	
	}		

}       //fin de la clase

