<?php
date_default_timezone_set('America/Mexico_City');
require_once "conexion.php";

class ModeloSeries{

	/*=============================================
	REGISTRO DE SERIES
	=============================================*/
static public function mdlGuardarNumeroSeries($tabla, $id_almacen, $numerodocto, $datos, $id_usuario, $contador){
  try{        
        //$contador = count($productos);    //CUANTO PRODUCTOS VIENEN PARA EL FOR 
        $estado=1;
				//ACTUALIZA EXISTENCIA EN EL ALMACEN SELECCIONADO
				for($i=0;$i<$contador;$i++) { 
          $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_producto, id_almacen, numerodocto, numeroserie, alfanumerico, estado, ultusuario) 
          VALUES (:id_producto, :id_almacen, :numerodocto, :numeroserie, :alfanumerico, :estado, :ultusuario)");
                
                $stmt->bindParam(":id_producto",  $datos["id_producto"][$i], PDO::PARAM_INT);
                $stmt->bindParam(":id_almacen",   $id_almacen, PDO::PARAM_INT);
                $stmt->bindParam(":numerodocto",  $numerodocto, PDO::PARAM_STR);
                $stmt->bindParam(":numeroserie",  $datos["numeroserie"][$i], PDO::PARAM_STR);
                $stmt->bindParam(":alfanumerico", $datos["alfanumerico"][$i], PDO::PARAM_STR);
                $stmt->bindParam(":estado",       $estado, PDO::PARAM_INT);
                $stmt->bindParam(":ultusuario",   $id_usuario, PDO::PARAM_INT);
                $stmt->execute();
        }		//FIN DEL FOR
				 
				 if($stmt){
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
	LISTAR DEV TECNICOS
=============================================*/
static public function mdlListarSeries($tabla, $item, $valor, $orden, $fechadev1, $fechadev2, $filtroanual){
try{
    if(!empty($filtroanual)){
       $where="YEAR(cont_series.fechaentrada)='".$filtroanual."'";
     }else{
       $where="cont_series.fechaentrada>='".$fechadev1."' AND cont_series.fechaentrada<='".$fechadev2."'";
       //$where = "os.fecha_instalacion>='" . $fechadev1 . "' AND os.fecha_instalacion<='" . $fechadev2 . "' ";
    }
    
    $valor=intval($valor);
    if($valor<99){    //1=disponible 0=transito 2=asignado
      //$where.='AND cont_series.estado="'.$valor.'"';
      $where.=" AND cont_series.estado='".$valor."'";
    }

    $sql="SELECT cont_series.id, cont_series.id_producto, cont_series.id_almacen, prod.descripcion, cont_series.numerodocto, cont_series.numeroserie, cont_series.alfanumerico, cont_series.id_asignado, cont_series.notasalida, cont_series.os, cont_series.estado, cont_series.fechaentrada, cont_series.ultmodificacion, alm.nombre AS nombrealmacen 
    FROM $tabla cont_series 
    INNER JOIN productos prod ON prod.id=cont_series.id_producto
    INNER JOIN almacenes alm ON alm.id=cont_series.id_almacen
    WHERE ".$where;

    //echo $sql;
    

    $stmt = Conexion::conectar()->prepare($sql);


    //$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

     $stmt -> execute();

    return $stmt -> fetchAll(PDO::FETCH_ASSOC);

    $stmt = null;
    
  } catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
  }
  
}
/************************************************************************ */
static public function mdlMostrarONT($tabla, $item, $valor, $estado){
	try{
    //$estado=1;

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item AND $estado = :$estado  ORDER BY id ASC");

			$stmt -> bindParam(":$item", $valor, PDO::PARAM_INT);
			$stmt -> bindParam(":$estado", $estado, PDO::PARAM_INT);

			$stmt -> execute();

			return $stmt -> fetchAll(PDO::FETCH_ASSOC);

		$stmt = null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}

}
/************************************************************************ */
static public function mdlValidData($tabla, $campo1, $valor1, $campo2, $valor2, $campo3, $valor3){
	try{
    $numerodocto=(string)$valor2;   //Método 1. Mediante un casting de tipos.

			 $stmt = Conexion::conectar()->prepare("SELECT id_almacen, numerodocto, id_producto 
       FROM $tabla
       WHERE $campo1 = :$campo1 AND $campo2=:$campo2 AND $campo3=:$campo3 LIMIT 1");

			$stmt -> bindParam(":$campo1", $valor1, PDO::PARAM_INT);
			$stmt -> bindParam(":$campo2", $numerodocto, PDO::PARAM_STR);
			$stmt -> bindParam(":$campo3", $valor3, PDO::PARAM_INT);

			$stmt -> execute();

			return $stmt -> fetch(PDO::FETCH_ASSOC);

		$stmt = null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}

}
/************************************************************************ */

/*==========================================================================================*/
public static function mdlGuardarEditaSerie($tabla, $datos)
{
    try {

            $sql = "UPDATE $tabla SET id_almacen=:id_almacen, numerodocto=:numerodocto, numeroserie=:numeroserie, alfanumerico=:alfanumerico, id_asignado=:id_asignado, os=:os, estado=:estado, ultusuario=:ultusuario WHERE id = :id";

            $stmt = Conexion::conectar()->prepare($sql);

            //$stmt->bindParam(":id_producto",  $datos["id_producto"], PDO::PARAM_INT);
            $stmt->bindParam(":id",           $datos["id"], PDO::PARAM_INT);
            $stmt->bindParam(":id_almacen",   $datos["id_almacen"], PDO::PARAM_INT);
            $stmt->bindParam(":numerodocto",  $datos["numerodocto"], PDO::PARAM_STR);
            $stmt->bindParam(":numeroserie",  $datos["numeroserie"], PDO::PARAM_STR);
            $stmt->bindParam(":alfanumerico", $datos["alfanumerico"], PDO::PARAM_STR);
            $stmt->bindParam(":id_asignado",  $datos["id_asignado"], PDO::PARAM_INT);
            $stmt->bindParam(":os",           $datos["os"], PDO::PARAM_STR);
            $stmt->bindParam(":estado",       $datos["estado"], PDO::PARAM_INT);
            $stmt->bindParam(":ultusuario",   $datos["ultusuario"], PDO::PARAM_INT);
            $stmt->execute();

            return $stmt;

        $stmt = null;

    } catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
    }
}

/*===========================================================================================*/
/****************
SELECT *
FROM contenedor_series
WHERE YEAR(`fechaentrada`) = 2023;

* 
*/
}       //fin de la clase