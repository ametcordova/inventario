<?php
date_default_timezone_set('America/Mexico_City');
require_once "conexion.php";

class ModeloDevolucion{

/*=============================================
	REGISTRO DE PRODUCTO
=============================================*/
static public function mdlGuardarDevolucionTecnico($tablaDev, $tabla, $datos, $productos, $cantidades){
  try{
    $tablahist="hist_salidas";
      //$contador = count($id_producto);    //CUANTO PRODUCTOS VIENEN PARA EL FOR
        
      //SCRIP QUE REGISTRA LA DEVOLUCION $tabladev=devolucion_tecnicos
       $stmt1 = Conexion::conectar()->prepare("INSERT INTO $tablaDev(id_tecnico, fecha_devolucion, id_almacen, datos_devolucion, ultusuario) 
		  VALUES (:id_tecnico, :fecha_devolucion, :id_almacen, :datos_devolucion, :ultusuario)");
            
        $stmt1->bindParam(":id_tecnico",       $datos["id_tecnico"], PDO::PARAM_INT);
        $stmt1->bindParam(":fecha_devolucion", $datos["fecha_devolucion"], PDO::PARAM_STR);
        $stmt1->bindParam(":id_almacen",       $datos["id_almacen"], PDO::PARAM_INT);
        $stmt1->bindParam(":datos_devolucion", $datos["datos_devolucion"], PDO::PARAM_STR);
        $stmt1->bindParam(":ultusuario",       $datos["id_usuario"], PDO::PARAM_INT);
        $stmt1->execute();
        
        if($stmt1){
              
                $contador = count($productos);    //CUANTO PRODUCTOS VIENEN PARA EL FOR 
                //ACTUALIZA EXISTENCIA EN EL ALMACEN SELECCIONADO
                for($i=0;$i<$contador;$i++) { 
                  $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET cant=:cant+cant, ultusuario=:ultusuario WHERE id_producto = :id_producto");
                          
                  $stmt->bindParam(":id_producto", $productos[$i], PDO::PARAM_INT);
                  $stmt->bindParam(":cant", $cantidades[$i], PDO::PARAM_INT);
                  $stmt->bindParam(":ultusuario", $datos["id_usuario"], PDO::PARAM_INT);
                  $stmt->execute();

                  /*********************************************************************************/
                  //ACTUALIZAR EXIST EN TRANSITO DEL TECNICO EN LA TABLA hist_salidas
                  /********************************************************************************/
                  $sql = Conexion::conectar()->prepare("SELECT id, id_producto, id_tecnico, id_almacen, id_tipomov, disponible FROM $tablahist WHERE id_producto = :id_producto AND id_tecnico=:id_tecnico AND id_almacen=:id_almacen AND id_tipomov>0 AND disponible>0 ORDER BY fecha_salida ASC");
                  
                  $sql->bindParam(":id_producto", $productos[$i], PDO::PARAM_INT);
                  $sql->bindParam(":id_tecnico", $datos["id_tecnico"], PDO::PARAM_INT);
                  $sql->bindParam(":id_almacen", $datos["id_almacen"], PDO::PARAM_INT);
                  $sql->execute();

                  $respuesta = $sql->fetchAll(PDO::FETCH_ASSOC);
                  $cant = (float) $cantidades[$i];
                  if ($respuesta) {
                      foreach ($respuesta as $value) {
                          $id = $value['id'];
                          $disponible = (float) $value['disponible'];

                          if ($disponible === $cant) { //Disponible es igual que la cant que sale
                              $rsp = actualizaDataDev($tablahist, $cant, $datos["id_usuario"], $id, $disponible);
                              break 1; //SALE DEL FOREACH

                          } elseif ($disponible > $cant) { //Disponible es Mayor que la cant que sale
                              $rsp = actualizaDataDev($tablahist, $cant, $datos["id_usuario"], $id, $disponible);
                              break 1; //SALE DEL FOREACH

                          } elseif ($disponible < $cant) { //Disponible es Menor que la cant que sale
                              $rsp = actualizaDataDev($tablahist, $cant, $datos["id_usuario"], $id, $disponible);
                              $cant = $rsp;

                          } else {
                              //$entra+=4;
                          }

                      } //FIN DEL FOREACH PARA ACTUALIZAR HIST_SALIDAS
                    }

                }		//FIN DEL FOR
          
              if($stmt){
                return "ok";
              }else{
                return "error";
              }

        }else{
			    return "error";
		    }
        
    $stmt1 = null;
    $stmt = null;
    $sql = null;

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
/**************************************************************************** */
/* CONSULTA PARA MOSTRAR LA EXIST EN TRANSITO DEL TECNICO
/**************************************************************************** */
static Public function MdlMostrarTransito($tabla, $tablaalmacen, $campo, $valor, $estado=null, $idtec){
  try{

    //$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $campo = :$campo");
    $stmt = Conexion::conectar()->prepare("SELECT h.id_producto, p.id_medida,m.medida, sum(h.disponible) AS exist FROM `hist_salidas` h
    INNER JOIN productos p ON h.id_producto=p.id
    INNER JOIN medidas m ON p.id_medida=m.id
    WHERE h.$campo=:$campo AND h.id_tecnico=:id_tecnico AND h.id_almacen=:id_almacen AND h.id_tipomov>0");
  
    $stmt -> bindParam(":$campo", $valor, PDO::PARAM_STR);
    $stmt -> bindParam(":id_tecnico", $idtec, PDO::PARAM_INT);
    $stmt -> bindParam(":id_almacen", $tablaalmacen, PDO::PARAM_INT);
  
    $stmt -> execute();
  
    return $stmt -> fetch(PDO::FETCH_ASSOC);
  
    $stmt=null;
  
  } catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
  }
      
  }

}       //fin de la clase

/**************************************************************************************************/
// FUNCION QUE REALIZA ACTUALIZACION DE LA CANT DISPONIBLE EN EL HIST_SALIDAS. FUERA DE LA CLASE
/***************************************************************************************************/
function actualizaDataDev($tablahist, $cant, $ultusuario, $id, $disponible)
{
    $restadisp = 0;
    try {
        $query = Conexion::conectar()->prepare("UPDATE $tablahist SET disponible=disponible-(:disponible), ultusuario=:ultusuario WHERE id = :id");

        if ($cant === $disponible) {
            $restadisp = $cant;
            $nvosaldo = 0;

        } elseif ($cant < $disponible) {
            $restadisp = $cant;
            $nvosaldo = 0;

        } elseif ($cant > $disponible) {
            $restadisp = $disponible;
            $nvosaldo = $cant - $disponible;

        } else {
            $nvosaldo = 0;
        }
        ;

        $query->bindParam(":id", $id, PDO::PARAM_INT);
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

/****************
 * SELECT hist.`id_salida`,hist.`id_tecnico`,hist.`id_producto`, prod.codigointerno, prod.sku, prod.descripcion, prod.id_medida, med.medida, hist.`estatus`, SUM(hist.cantidad) as Total, SUM(hist.disponible) AS existe
		FROM hist_salidas hist 
		INNER JOIN productos prod ON hist.id_producto=prod.id
		INNER JOIN medidas med ON prod.id_medida=med.id
		WHERE hist.id_tecnico=13 AND hist.estatus=1 AND hist.id_tipomov>0 AND hist.id_almacen=1 GROUP BY hist.`id_tecnico`, hist.`id_producto`
 * 
 * 
 */