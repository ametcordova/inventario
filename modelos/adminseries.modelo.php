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
static public function mdlListarSeries($tabla, $item, $valor, $orden, $fechadev1, $fechadev2){
try{
  //if($item = null){

    $stmt = Conexion::conectar()->prepare("SELECT series.id, series.id_producto, prod.descripcion, series.numerodocto, series.numeroserie, 
    series.alfanumerico, series.estado, series.ultmodificacion FROM $tabla series 
    INNER JOIN productos prod ON prod.id=series.id_producto"); 
    //$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

    $stmt -> execute();

    return $stmt -> fetchAll();

  //}else{

    //$where='1=1';
        
/*     $where='dev.fecha_devolucion>="'.$fechadev1.'" AND dev.fecha_devolucion<="'.$fechadev2.'" ';

    $where.=' ORDER BY dev.id DESC';

    $sql="SELECT dev.id, tec.nombre, dev.fecha_devolucion, dev.id_almacen,  alm.nombre AS almacen FROM $tabla dev INNER JOIN tecnicos tec ON dev.id_tecnico=tec.id INNER JOIN almacenes alm ON dev.id_almacen=alm.id WHERE ".$where;

    $stmt = Conexion::conectar()->prepare($sql);

    $stmt -> execute();

    return $stmt -> fetchAll();
 */    //return $sql;

  //}

    $stmt = null;
    
  } catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
  }
  
}
        
    

}       //fin de la clase