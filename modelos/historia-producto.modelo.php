<?php
date_default_timezone_set('America/Mexico_City');
$fechaHoy=date("d/m/Y");
require_once dirname( __DIR__ ).'/config/conexion.php';
class ModeloKardex{


//FUNCION PARA REPORTE EN TCPDF
//======================== CONSULTA ENTRADA POR COMPRA ===============================
static Public function MdlEntradaKardex($campo, $idproducto, $idalmacen, $fechainicial){

$idProd = (int) $idproducto;
$where=$idProd>0?' ent.id_producto="'.$idproducto.'" ':'';

$where.=' AND ent.fechaentrada>="'.$fechainicial.'" ';

$where.='AND ent.id_almacen="'.$idalmacen.'"';

$where.=' ORDER BY ent.fechaentrada';
    
	$sql="SELECT pro.id, ent.id_producto,pro.codigointerno, pro.descripcion, ent.fechaentrada, ent.numerodocto, ent.recibio,ent.cantidad, ent.precio_compra, ent.id_almacen, ent.id_tipomov, tm.nombre_tipo 
    FROM hist_entrada ent
    INNER JOIN productos pro ON pro.id=ent.id_producto
    INNER JOIN tipomovimiento tm ON tm.id=ent.id_tipomov
    WHERE".$where;
    
	$stmt = Conexion::conectar()->prepare($sql);

	$stmt -> execute();

	return $stmt -> fetchAll();      
    
    $stmt->close();
       
    $stmt=null;

}		
//================================== ENTRADA POR AJUSTE DE INVENTARIO ====================================
static Public function MdlMovtoAjuste($tabla, $tipomov, $idalmacen, $fechainicial){

    $where='tm.clase="'.$tipomov.'"';
    $where.=' AND aj.fecha_ajuste>="'.$fechainicial.'" ';
    
    $where.='AND aj.id_almacen="'.$idalmacen.'"';
    
    $where.=' ORDER BY aj.fecha_ajuste';
        
        $sql="SELECT aj.id, aj.tipomov,aj.fecha_ajuste, aj.id_almacen,aj.motivo_ajuste, aj.datos_ajuste, tm.nombre_tipo ,tm.clase,aj.id_usuario,us.nombre 
        FROM $tabla aj
        INNER JOIN tipomovimiento tm ON aj.tipomov=tm.id
        INNER JOIN usuarios us ON aj.id_usuario=us.id
        WHERE ".$where;

        $stmt = Conexion::conectar()->prepare($sql);
    
        $stmt -> execute();
    
        return $stmt -> fetchAll();      
        
        $stmt->close();
           
        $stmt=null;
    
    }		

//==========================================================================================================

static Public function MdlSalidaKardex($campo, $idproducto, $idalmacen, $fechainicial){

    //SELECT `fecha_salida`,`id_producto`, SUM(`cantidad`)as cant,`id_almacen`,`id_tipomov`,`id_caja` FROM `hist_salidas` WHERE `id_producto`="20" and fecha_salida>="2019-10-01" GROUP BY fecha_salida ORDER BY fecha_salida

    $idProd = (int) $idproducto;
    $where=$idProd>0?' id_producto="'.$idproducto.'" ':'';
    
    $where.=' AND fecha_salida>="'.$fechainicial.'" ';
    
    $where.='AND id_almacen="'.$idalmacen.'"';
    
    $where.=' GROUP BY fecha_salida ORDER BY fecha_salida';
        
        $sql="SELECT fecha_salida, id_producto, sum(cantidad) AS cant, id_almacen, id_tipomov
        FROM hist_salidas
        WHERE".$where;
        
        $stmt = Conexion::conectar()->prepare($sql);
    
        $stmt -> execute();
    
        return $stmt -> fetchAll();      
        
        $stmt->close();
           
        $stmt=null;
    
    }		
    

/*=============================================
	MOSTRAR PRODUCTOS  
=============================================*/

static public function MdlTraerProduct($tabla, $campo, $idproducto, $estado){
try{
		
	$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $campo = :$campo AND estado=$estado");

	$stmt -> bindParam(":$campo", $idproducto, PDO::PARAM_STR);
	//$stmt -> bindParam(":$estado", $estado, PDO::PARAM_INT);

	$stmt -> execute();

	return $stmt -> fetch();

	$stmt -> close();

	$stmt = null;
} catch (Exception $e) {
	echo "Failed: " . $e->getMessage();
}

}

/* ============================================================================================*/
static public function MdlTraerExist($campo, $idproducto, $tabla){
    try{
            
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $campo = :$campo");
    
        $stmt -> bindParam(":$campo", $idproducto, PDO::PARAM_STR);
        //$stmt -> bindParam(":$estado", $estado, PDO::PARAM_INT);
    
        $stmt -> execute();
    
        return $stmt -> fetch();
    
        $stmt -> close();
    
        $stmt = null;
    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }
    
    }
    
/* ============================================================================================*/


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

    "SELECT pro.id, ent.`id_producto`,pro.codigointerno, pro.descripcion, ent.`fechaentrada`, ent.numerodocto, ent.`recibio`,ent.`cantidad`, ent.`precio_compra`, ent.`id_almacen`, ent.`id_tipomov`, tm.nombre_tipo FROM `hist_entrada` ent
    INNER JOIN productos pro ON pro.id=ent.id_producto
    INNER JOIN tipomovimiento tm ON tm.id=ent.id_tipomov
    WHERE `id_producto`="20" AND fechaentrada>="2019-10-01" AND ent.id_almacen=1 ORDER by ent.fechaentrada"

SELECT aj.id, aj.tipomov,aj.fecha_ajuste, aj.id_almacen,aj.motivo_ajuste, aj.datos_ajuste, tm.nombre_tipo ,tm.clase,aj.id_usuario,us.nombre FROM `ajusteinventario` aj
INNER JOIN tipomovimiento tm ON aj.tipomov=tm.id
INNER JOIN usuarios us ON aj.id_usuario=us.id
WHERE tm.clase="E" AND aj.fecha_ajuste>="2020-05-01" AND aj.fecha_ajuste<="2020-05-31"
/*Parse error: syntax error, unexpected end of file in C:\xampp\htdocs\cervecentro\extensiones\tcpdf\pdf\kardex-producto.php on line 487
*/