<?php
date_default_timezone_set('America/Mexico_City');
require_once "conexion.php";

class ModeloDevolucion{

/*=============================================
	REGISTRO DE PRODUCTO
=============================================*/
static public function mdlGuardarDevolucionTecnico($tablaDev, $tabla, $datos, $productos, $cantidades){
  try{
      //$contador = count($id_producto);    //CUANTO PRODUCTOS VIENEN PARA EL FOR
        
      //SCRIP QUE REGISTRA LA DEVOLUCION
       $stmt = Conexion::conectar()->prepare("INSERT INTO $tablaDev(id_tecnico, fecha_devolucion, id_almacen, datos_devolucion, ultusuario) 
		  VALUES (:id_tecnico, :fecha_devolucion, :id_almacen, :datos_devolucion, :ultusuario)");
            
        $stmt->bindParam(":id_tecnico",       $datos["id_tecnico"], PDO::PARAM_INT);
        $stmt->bindParam(":fecha_devolucion", $datos["fecha_devolucion"], PDO::PARAM_STR);
        $stmt->bindParam(":id_almacen",       $datos["id_almacen"], PDO::PARAM_INT);
        $stmt->bindParam(":datos_devolucion", $datos["datos_devolucion"], PDO::PARAM_STR);
        $stmt->bindParam(":ultusuario",       $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->execute();
        
        if($stmt){
              
                $contador = count($productos);    //CUANTO PRODUCTOS VIENEN PARA EL FOR 
                //ACTUALIZA EXISTENCIA EN EL ALMACEN SELECCIONADO
                for($i=0;$i<$contador;$i++) { 
                  $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET cant=:cant+cant, ultusuario=:ultusuario WHERE id_producto = :id_producto");
                          
                  $stmt->bindParam(":id_producto", $productos[$i], PDO::PARAM_INT);
                  $stmt->bindParam(":cant", $cantidades[$i], PDO::PARAM_INT);
                  $stmt->bindParam(":ultusuario", $datos["id_usuario"], PDO::PARAM_INT);
                  $stmt->execute();
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

  } catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
  }
    

  }
    
/*=============================================
	LISTAR DEV TECNICOS
=============================================*/

static public function mdlListarDevTec($tabla, $item, $valor, $orden, $fechadev1, $fechadev2){
  try{
    if($item != null){

      $stmt = Conexion::conectar()->prepare("SELECT cja.id AS idcaja, cja.caja, cja.descripcion, cja.estado, cja.id_usuario, user.id, user.nombre FROM $tabla cja LEFT JOIN usuarios user ON user.id=cja.id_usuario WHERE cja.$item = :$item"); 
      $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

      $stmt -> execute();

      return $stmt -> fetch();

    }else{

      //$where='1=1';
          
      $where='dev.fecha_devolucion>="'.$fechadev1.'" AND dev.fecha_devolucion<="'.$fechadev2.'" ';

      $where.=' ORDER BY dev.id DESC';

      $sql="SELECT dev.id, tec.nombre, dev.fecha_devolucion, dev.id_almacen,  alm.nombre AS almacen FROM $tabla dev INNER JOIN tecnicos tec ON dev.id_tecnico=tec.id INNER JOIN almacenes alm ON dev.id_almacen=alm.id WHERE ".$where;

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
        
    

}       //fin de la clase