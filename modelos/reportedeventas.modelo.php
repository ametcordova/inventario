<?php
date_default_timezone_set('America/Mexico_City');
$fechaHoy=date("d/m/Y");
require_once dirname( __DIR__ ).'/config/conexion.php';
class ModeloVentas{


//FUNCION PARA REPORTE EN TCPDF
static Public function MdlReporteVentas($tabla, $campo, $valor, $fechavta1, $fechavta2, $idNumCaja, $idNumCliente, $idNumProds, $idTipoMovs){

//CAMBIAR EL FORMATO DE FECHA A yyyy-mm-dd Y SI ESTA VACIA TOMA FECHA ACTUAL
/*$fechavta = explode('/', $fechavta);

if(count($fechavta) == 3){
  $FechaVta = $fechavta[2].'-'.$fechavta[1].'-'.$fechavta[0];
}else{
  $fechavta = explode('/', $fechaHoy);
  $FechaVta = $fechavta[2].'-'.$fechavta[1].'-'.$fechavta[0];
}
*/
    
$where='sal.id_almacen="'.$tabla.'"';      //
    
$idFam = (int) $valor;

$where.=$idFam>0?' AND pro.'.$campo.' IN ('.$valor.')':'';

if($fechavta1==$fechavta2){
 $where.=' AND sal.fecha_salida="'.$fechavta1.'"';    
}else{
 $where.=' AND sal.fecha_salida>="'.$fechavta1.'" AND sal.fecha_salida<="'.$fechavta2.'" ';
}

$idNumCaja = (int) $idNumCaja;
if($idNumCaja>'0'){
	$where.=' AND sal.id_caja="'.$idNumCaja.'"';
}
	
$idNumCliente = (int) $idNumCliente;
if($idNumCliente>0){
	$where.=' AND sal.id_cliente="'.$idNumCliente.'"';
}

$idProd = (int) $idNumProds;
$where.=$idProd>0?' AND sal.id_producto IN ('.$idNumProds.')':'';

$idMovs = (int) $idTipoMovs;
$where.=$idMovs>0?' AND sal.id_tipomov IN ('.$idTipoMovs.')':'';


$where.=' GROUP BY sal.id_tipomov, pro.id_familia, pro.id_categoria, sal.id_producto';
    

	$sql="SELECT sal.fecha_salida, sal.id_producto,pro.id_familia, fam.familia, pro.id_categoria, cat.categoria, pro.id_medida, med.medida, pro.precio_compra, sal.id_cliente, cli.nombre AS cliente, sal.id_tipomov, pro.codigointerno,pro.descripcion,
	sum(sal.cantidad) AS cant, sum(sal.cantidad*sal.precio_venta) AS venta,
	SUM(IF(sal.`es_promo` = 0, sal.`cantidad`*sal.`precio_venta`,0)) AS sinpromo, SUM(IF(sal.`es_promo` = 1, sal.`precio_venta`,0)) AS promo, sal.id_caja
	FROM hist_salidas sal 
	INNER JOIN productos pro ON sal.id_producto=pro.id
	INNER JOIN familias fam ON pro.id_familia=fam.id
	INNER JOIN categorias cat ON pro.id_categoria=cat.id
	INNER JOIN medidas med ON pro.id_medida=med.id
	INNER JOIN clientes cli ON sal.id_cliente=cli.id
	WHERE ".$where;
    
	$stmt = Conexion::conectar()->prepare($sql);

	//$stmt -> bindParam(":$campo", $valor, PDO::PARAM_STR);

	$stmt -> execute();

	return $stmt -> fetchAll();      
    
    $stmt->close();
       
    $stmt=null;

}		

//FUNCION PARA REPORTE EN TCPDF
static Public function MdlListarVentas($tabla, $campo, $valor, $fechavta1, $fechavta2, $idNumCaja, $idNumCliente, $idNumProds, $idTipoMovs){

	$where='sal.id_almacen="'.$tabla.'"';      //
		
	$idFam = (int) $valor;
	$valor = isset($valor)?implode(",", $valor):null;
	$where.=$idFam>0?' AND pro.'.$campo.' IN ('.$valor.')':'';
	
	if($fechavta1==$fechavta2){
	 $where.=' AND sal.fecha_salida="'.$fechavta1.'"';    
	}else{
	 $where.=' AND sal.fecha_salida>="'.$fechavta1.'" AND sal.fecha_salida<="'.$fechavta2.'" ';
	}
	
	$idNumCaja = (int) $idNumCaja;
	if($idNumCaja>'0'){
		$where.=' AND sal.id_caja="'.$idNumCaja.'"';
	}
		
	$idNumCliente = (int) $idNumCliente;
	if($idNumCliente>0){
		$where.=' AND sal.id_cliente="'.$idNumCliente.'"';
	}
	
	$idProd = (int) $idNumProds;
	$idNumProds = isset($idNumProds)?implode(",", $idNumProds):null;
	$where.=$idProd>0?' AND sal.id_producto IN ('.$idNumProds.')':'';

	$idMovs = (int) $idTipoMovs;
	$idTipoMovs = isset($idTipoMovs)?implode(",", $idTipoMovs):null;
	$where.=$idMovs>0?' AND sal.id_tipomov IN ('.$idTipoMovs.')':'';

	
	$where.=' GROUP BY sal.id_tipomov, pro.id_familia, pro.id_categoria, sal.id_producto';
		
	
		$sql="SELECT sal.fecha_salida, sal.id_producto,pro.id_familia, fam.familia, pro.id_categoria, cat.categoria, pro.id_medida, med.medida, pro.precio_compra, sal.id_cliente, cli.nombre AS cliente, pro.codigointerno,pro.descripcion,
		sum(sal.cantidad) AS cant, sum(sal.cantidad*sal.precio_venta) AS venta,
		SUM(IF(sal.`es_promo` = 0, sal.`cantidad`*sal.`precio_venta`,0)) AS sinpromo, SUM(IF(sal.`es_promo` = 1, sal.`precio_venta`,0)) AS promo, sal.id_caja
		FROM hist_salidas sal 
		INNER JOIN productos pro ON sal.id_producto=pro.id
		INNER JOIN familias fam ON pro.id_familia=fam.id
		INNER JOIN categorias cat ON pro.id_categoria=cat.id
		INNER JOIN medidas med ON pro.id_medida=med.id
		INNER JOIN clientes cli ON sal.id_cliente=cli.id
		WHERE ".$where;
		
		$stmt = Conexion::conectar()->prepare($sql);
	
		//$stmt -> bindParam(":$campo", $valor, PDO::PARAM_STR);
	
		$stmt -> execute();
	
		return $stmt -> fetchAll();      
		
		$stmt->close();
		   
		$stmt=null;
	
	}	
    
	static Public function MdlMostrarTotVentasFam($tablaVtaFam, $fechaInimes, $fechaFinmes, $limite){

		if($fechaInimes==$fechaFinmes){
		 $where='sal.fecha_salida="'.$fechaInimes.'"';    
		}else{
		 $where='sal.fecha_salida>="'.$fechaInimes.'" AND sal.fecha_salida<="'.$fechaFinmes.'" ';
		}

		$where.='and fam.familia<>"ENVASES"';
		
		$where.=' GROUP BY pro.id_familia ORDER BY venta DESC';

		$sql= "SELECT sal.fecha_salida, pro.id_familia, fam.familia, 
		SUM(IF(sal.es_promo = 0, sal.cantidad*sal.precio_venta,0)) + SUM(IF(sal.es_promo = 1, sal.precio_venta,0)) AS venta
		FROM  $tablaVtaFam sal 
		INNER JOIN productos pro ON sal.id_producto=pro.id
		INNER JOIN familias fam ON pro.id_familia=fam.id
		WHERE ".$where; 

			$stmt = Conexion::conectar()->prepare($sql);
		
			$stmt -> execute();
		
			return $stmt -> fetchAll();      
			
			$stmt->close();
			   
			$stmt=null;
		
		}	
	

}       //fin de la clase


/*
SELECT sal.fecha_salida, sal.id_producto,pro.id_familia, fam.familia, pro.id_categoria, cat.categoria, pro.id_medida, med.medida, pro.codigointerno,pro.descripcion, sum(sal.cantidad) as cant, sum(sal.precio_venta)AS venta FROM hist_salidas sal 
INNER JOIN productos pro ON sal.id_producto=pro.id
INNER JOIN familias fam ON pro.id_familia=fam.id
INNER JOIN categorias cat ON pro.id_categoria=cat.id
INNER JOIN medidas med ON pro.id_medida=med.id
WHERE sal.fecha_salida='2019-04-18' AND sal.id_almacen=$tabla AND pro.id_familia=1 GROUP BY pro.id_familia, pro.id_categoria, sal.id_producto



SELECT sal.fecha_salida, sal.id_producto,pro.id_familia, fam.familia, pro.id_categoria, cat.categoria, pro.id_medida, med.medida, pro.codigointerno,pro.descripcion, sum(sal.cantidad) as cant, sum(sal.cantidad*sal.precio_venta)AS venta FROM hist_salidas sal 
INNER JOIN productos pro ON sal.id_producto=pro.id
INNER JOIN familias fam ON pro.id_familia=fam.id
INNER JOIN categorias cat ON pro.id_categoria=cat.id
INNER JOIN medidas med ON pro.id_medida=med.id
WHERE sal.fecha_salida>="2019-04-15" and sal.fecha_salida<="2019-04-21" GROUP BY pro.id_familia, pro.id_categoria, sal.id_producto

CONSULTA PARA LA IMPRESION DEL TICKET DEL CORTE DE CAJA
SELECT `num_salida`,`fecha_salida`,`id_producto`,sum(`cantidad`),`precio_venta`,sum(`precio_venta`*`cantidad`) as venta FROM `hist_salidas` WHERE `id_caja`=3 and fecha_salida="2019-07-16" GROUP BY id_producto,`precio_venta`

SELECT P.*,
    IFNULL(E.total_entradas, 0) entradas,
    IFNULL(S.total_salidas, 0) salidas,
    IFNULL(E.total_entradas, 0) - IFNULL(S.total_salidas, 0) stock
    FROM productos P
    LEFT JOIN
    (SELECT id_producto, SUM(cantidad) total_entradas FROM entradas
    GROUP BY id_producto) E
    ON P.id_producto = E.id_producto
    LEFT JOIN
    (SELECT id_producto, SUM(cantidad) total_salidas FROM salidas
    GROUP BY id_producto) S
	ON P.id_producto = S.id_producto;
	
/*	
SELECT P.id,P.codigointerno, P.descripcion, IFNULL(E.cantidad, 0) entradas, IFNULL(E.fechadocto,null) AS fechaentrada, IFNULL(S.cantidad, 0) salidas, IFNULL(S.fecha_salida,null) AS fechasalida, IFNULL(E.cantidad, 0) - IFNULL(S.cantidad, 0) stock
    FROM productos P
    LEFT JOIN
    (SELECT id_producto, fechadocto, cantidad, SUM(cantidad) total_entradas FROM hist_entrada
    GROUP BY fechadocto, id_producto) E
    ON P.id = E.id_producto
    LEFT JOIN
    (SELECT id_producto, fecha_salida, cantidad, SUM(cantidad) total_salidas FROM hist_salidas
    GROUP BY fecha_salida, id_producto) S
	ON P.id = S.id_producto WHERE p.id="20"    
	
// consulta para el grafico de ventas por familia
SELECT pro.id_familia, fam.familia,
    SUM(IF(sal.`es_promo` = 0, sal.`cantidad`*sal.`precio_venta`,0)) + SUM(IF(sal.`es_promo` = 1, sal.`precio_venta`,0)) AS venta
	FROM hist_salidas sal 
	INNER JOIN productos pro ON sal.id_producto=pro.id
	INNER JOIN familias fam ON pro.id_familia=fam.id
	WHERE sal.fecha_salida>="2020-02-1" and sal.fecha_salida<="2020-02-29" and fam.id<>6 GROUP BY pro.id_familia ORDER BY venta DESC

*/