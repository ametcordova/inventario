<?php
date_default_timezone_set('America/Mexico_City');
require_once dirname( __DIR__ ).'/modelos/conexion.php';
class ModeloSalidasAlmacen{

/*=============================================
	REGISTRO DE PRODUCTO
=============================================*/
static public function mdlAltaSalidasAlmacen($tabla_almacen, $tabla, $datos){
	try {      

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(fechasalida, id_tecnico, id_tipomov, id_almacen, id_usuario, motivo, ultusuario) VALUES (:fechasalida, :id_tecnico, :id_tipomov, :id_almacen, :id_usuario, :motivo, :ultusuario)");
        $stmt->bindParam(":fechasalida", 	$datos["fechasalida"], PDO::PARAM_STR);
        $stmt->bindParam(":id_tecnico", 	$datos["id_tecnico"], PDO::PARAM_INT);
        $stmt->bindParam(":id_tipomov", 	$datos["id_tipomov"], PDO::PARAM_INT);
        $stmt->bindParam(":id_almacen",    $datos["id_almacen"], PDO::PARAM_INT);
        $stmt->bindParam(":id_usuario",    $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":motivo", 	    $datos["motivo"], PDO::PARAM_STR);
        $stmt->bindParam(":ultusuario",    $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->execute();
        
        if($stmt){
            //OBTENEMOS EL ÚLTIMO ID GUARDADO EN tbl_salidas
            $campo=null;
            $query=self::mdlObtenerUltimoId($tabla, $campo);
            $idnumsalida=$query[0];
            if(is_null($idnumsalida)){
            	$idnumsalida=1;
            }

            $contador = count($datos["productos"]);    //CUANTO PRODUCTOS VIENEN PARA EL FOR
            $nombremes_actual = strtolower(date('F'));  // NOMBRE DEL MES ACTUAL PARA ACTUALIZAR KARDEX 
            $tabla_kardex="kardex_".$tabla_almacen;     //TABLA KARDEX DEL SEGUN ALMACEN SELECCIONADO
            
            //SCRIP QUE REGISTRA LA SALIDA EN HIST_SALIDA
            $stmt = Conexion::conectar()->prepare("INSERT INTO hist_salidas(id_salida, id_tecnico, fecha_salida, id_producto, cantidad, id_almacen, id_tipomov, id_usuario, ultusuario) VALUES (:id_salida, :id_tecnico, :fecha_salida, :id_producto, :cantidad, :id_almacen, :id_tipomov, :id_usuario, :ultusuario)");
                
            for($i=0;$i<$contador;$i++) { 
                $stmt->bindParam(":id_salida", $idnumsalida, PDO::PARAM_INT);
                $stmt->bindParam(":id_tecnico", $datos["id_tecnico"], PDO::PARAM_INT);
                $stmt->bindParam(":fecha_salida", $datos["fechasalida"], PDO::PARAM_STR);
                $stmt->bindParam(":id_producto", $datos["productos"][$i], PDO::PARAM_INT);
                $stmt->bindParam(":cantidad", $datos["cantidades"][$i], PDO::PARAM_INT);
                $stmt->bindParam(":id_almacen", $datos["id_almacen"], PDO::PARAM_INT);
                $stmt->bindParam(":id_tipomov", $datos["id_tipomov"], PDO::PARAM_INT);
                $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
                $stmt->bindParam(":ultusuario", $datos["id_usuario"], PDO::PARAM_INT);
                $stmt->execute();
            }      //termina ciclo 1er for 
            
            if($stmt){
                //SCRIP QUE REGISTRA LA SALIDA EN EL ALMACEN ELEGIDO
                for($i=0;$i<$contador;$i++) { 
                   
                    $stmt = Conexion::conectar()->prepare("UPDATE $tabla_almacen SET cant=cant-(:cant), ultusuario=:ultusuario WHERE id_producto = :id_producto");
                     $stmt->bindParam(":id_producto", $datos["productos"][$i], PDO::PARAM_INT);
                     $stmt->bindParam(":cant", $datos["cantidades"][$i], PDO::PARAM_INT);
                     $stmt->bindParam(":ultusuario", $datos["id_usuario"], PDO::PARAM_INT);
                     $stmt->execute();

   					//GUARDA EN KARDEX DEL ALMACEN ELEGIDO
					$query = Conexion::conectar()->prepare("UPDATE $tabla_kardex SET $nombremes_actual=$nombremes_actual-(:$nombremes_actual) WHERE id_producto = :id_producto");
					$query->bindParam(":id_producto", $datos["productos"][$i], PDO::PARAM_INT);
					$query->bindParam(":".$nombremes_actual, $datos["cantidades"][$i], PDO::PARAM_STR);
					$query->execute();

                   }   //termina ciclo 2do for                    
                   
                      if($stmt){
                          return "ok";
                       }else{
                            return "error";
                       }




            }

            return "ok";
        }else{
           return "error";
        }

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
   }

}
/*==========================================================*/

static Public function mdlConsultaExistenciaProd($tabla, $campo, $valor){
    //$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $campo = :$campo");
    $stmt = Conexion::conectar()->prepare("SELECT a.cant ,p.id_medida,m.medida FROM $tabla a 
    INNER JOIN productos p ON a.id_producto=p.id
    INNER JOIN medidas m ON p.id_medida=m.id
    WHERE $campo = :$campo");

    $stmt -> bindParam(":$campo", $valor, PDO::PARAM_STR);
    $stmt -> execute();
    return $stmt -> fetch();      
    $stmt=null;
}

 /*=============================================
	LISTAR SALIDAS
=============================================*/

static public function mdlListarSalidas($tabla, $fechadev1, $fechadev2){

 	//$where='1=1';
		  
	$where='tb1.fechasalida>="'.$fechadev1.'" AND tb1.fechasalida<="'.$fechadev2.'" ';
  
	$where.=' ORDER BY tb1.id DESC';
  
	$sql="SELECT tb1.*, alm.nombre AS nombrealmacen, tec.nombre AS nombretecnico, usu.usuario FROM $tabla tb1 
    INNER JOIN almacenes alm ON tb1.id_almacen=alm.id 
    INNER JOIN tecnicos tec ON tb1.id_tecnico=tec.id 
    INNER JOIN usuarios usu ON tb1.id_usuario=usu.id 
    WHERE ".$where;
  
	$stmt = Conexion::conectar()->prepare($sql);
  
	$stmt -> execute();
  
	return $stmt -> fetchAll();
  
    $stmt = null;
  
}	

/*=============================================
	MOSTRAR PARA EDITAR SALIDAS DEL ALMACEN
=============================================*/			
static Public function mdlMostrarSalidaAlmacen($tabla, $campo, $valor){
try {          

    $sql="SELECT tb1.id, tb1.id_tecnico,t.nombre AS nombretecnico, tb1.fechasalida, h.cantidad,tb1.motivo, h.id_producto,
				a.descripcion,a.codigointerno, a.id_medida, m.medida, tb1.id_almacen,b.nombre AS nombrealma,tb1.id_tipomov,
				s.nombre_tipo,tb1.id_usuario,u.nombre AS nombreusuario 
				FROM $tabla tb1
                INNER JOIN hist_salidas h ON tb1.id=h.id_salida
                INNER JOIN tecnicos t ON tb1.id_tecnico=t.id
				INNER JOIN productos a ON h.id_producto=a.id
				INNER JOIN almacenes b ON tb1.id_almacen=b.id
				INNER JOIN medidas m ON a.id_medida=m.id
				INNER JOIN tipomovsalida s ON tb1.id_tipomov=s.id
				INNER JOIN usuarios u ON tb1.id_usuario=u.id
                WHERE h.$campo=:$campo";
                
    $stmt=Conexion::conectar()->prepare($sql);

    $stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
                
    $stmt->execute();
               
    return $stmt->fetchAll();

    $stmt = null;


} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}
  
}


/*=============================================
	REPORTE DE SALIDAS DEL ALMACEN
=============================================*/			
static Public function mdlPrintSalidaAlmacen($tabla, $campo, $valor){
try {      
    $sql="SELECT tb1.id, tb1.id_tecnico,t.nombre AS nombretecnico, tb1.fechasalida, h.cantidad,tb1.motivo, h.id_producto,
				a.descripcion,a.codigointerno, a.id_medida, m.medida, tb1.id_almacen,b.nombre AS nombrealma,tb1.id_tipomov,
				s.nombre_tipo,tb1.id_usuario,u.nombre AS nombreusuario 
				FROM $tabla tb1
                INNER JOIN hist_salidas h ON tb1.id=h.id_salida
                INNER JOIN tecnicos t ON tb1.id_tecnico=t.id
				INNER JOIN productos a ON h.id_producto=a.id
				INNER JOIN almacenes b ON tb1.id_almacen=b.id
				INNER JOIN medidas m ON a.id_medida=m.id
				INNER JOIN tipomovsalida s ON tb1.id_tipomov=s.id
				INNER JOIN usuarios u ON tb1.id_usuario=u.id
                WHERE h.$campo=:$campo";
                
    $stmt=Conexion::conectar()->prepare($sql);

    $stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
                
    $stmt->execute();
               
    return $stmt->fetchAll();

    $stmt = null;
        
                
} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}

}

/*=============================================
	ELIMINAR PRODUCTO DE SALIDA DE ALMACEN
=============================================*/
static public function mdEditEliminarRegSA($tabla_almacen, $id_almacen, $tablahist, $tablakardex, $key, $value, $id_salida, $nombremes_actual){
    try {      
        //MODIFICAR PARA PRODUCCION. DEBE SER DELETE
        //$stmt = Conexion::conectar()->prepare("UPDATE $tablahist SET precio_venta=:precio_venta WHERE id_producto = :id_producto AND id_almacen=:id_almacen");
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tablahist WHERE id_salida=:id_salida AND id_producto=:id_producto AND id_almacen=:id_almacen");
        $stmt->bindParam(":id_salida", $id_salida, PDO::PARAM_INT);
        $stmt->bindParam(":id_producto", $key, PDO::PARAM_INT);
        $stmt->bindParam(":id_almacen", $id_almacen, PDO::PARAM_INT);

        $stmt->execute();
        
        if($stmt){

            $stmt = Conexion::conectar()->prepare("UPDATE $tabla_almacen SET cant=:cant+cant WHERE id_producto = :id_producto");
            $stmt->bindParam(":id_producto", $key, PDO::PARAM_INT);
            $stmt->bindParam(":cant", $value, PDO::PARAM_INT);
            $stmt->execute();
    
   			//GUARDA EN KARDEX DEL ALMACEN ELEGIDO
            $stmt = Conexion::conectar()->prepare("UPDATE $tablakardex SET $nombremes_actual=:$nombremes_actual+$nombremes_actual WHERE id_producto = :id_producto");
            $stmt->bindParam(":id_producto", $key, PDO::PARAM_INT);
            $stmt->bindParam(":".$nombremes_actual, $value, PDO::PARAM_INT);
            $stmt->execute();
   
            return "ok";
         }else{
              return "error";
         }

    } catch (Exception $e) {
            echo "Failed: " . $e->getMessage();
    }
   
}

/*=============================================
	REGISTRO DE PRODUCTO
=============================================*/
static public function mdlEditAdicionarRegSA($tabla_almacen, $tablahist, $tablakardex, $nombremes_actual, $datos){
	try {      
            
            //SCRIP QUE REGISTRA LA SALIDA EN HIST_SALIDA
            $stmt = Conexion::conectar()->prepare("INSERT INTO $tablahist(id_salida, num_salida, id_tecnico, fecha_salida, id_producto, cantidad, id_almacen, id_tipomov, id_usuario, ultusuario) VALUES (:id_salida, :num_salida, :id_tecnico, :fecha_salida, :id_producto, :cantidad, :id_almacen, :id_tipomov, :id_usuario, :ultusuario)");
                
            $stmt->bindParam(":id_salida", $datos["id_salida"], PDO::PARAM_INT);
            $stmt->bindParam(":num_salida", $datos["id_salida"], PDO::PARAM_INT);
            $stmt->bindParam(":id_tecnico", $datos["id_tecnico"], PDO::PARAM_INT);
            $stmt->bindParam(":fecha_salida", $datos["fechasalida"], PDO::PARAM_STR);
            $stmt->bindParam(":id_producto", $datos["productos"], PDO::PARAM_INT);
            $stmt->bindParam(":cantidad", $datos["cantidades"], PDO::PARAM_INT);
            $stmt->bindParam(":id_almacen", $datos["id_almacen"], PDO::PARAM_INT);
            $stmt->bindParam(":id_tipomov", $datos["id_tipomov"], PDO::PARAM_INT);
            $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
            $stmt->bindParam(":ultusuario", $datos["id_usuario"], PDO::PARAM_INT);
            $stmt->execute();

            
            if($stmt){
                //SCRIP QUE REGISTRA LA SALIDA EN EL ALMACEN ELEGIDO
                   
                    $stmt = Conexion::conectar()->prepare("UPDATE $tabla_almacen SET cant=cant-(:cant), ultusuario=:ultusuario WHERE id_producto = :id_producto");
                     $stmt->bindParam(":id_producto", $datos["productos"], PDO::PARAM_INT);
                     $stmt->bindParam(":cant", $datos["cantidades"], PDO::PARAM_INT);
                     $stmt->bindParam(":ultusuario", $datos["id_usuario"], PDO::PARAM_INT);
                     $stmt->execute();

   					//GUARDA EN KARDEX DEL ALMACEN ELEGIDO
					$query = Conexion::conectar()->prepare("UPDATE $tablakardex SET $nombremes_actual=$nombremes_actual-(:$nombremes_actual) WHERE id_producto = :id_producto");
					$query->bindParam(":id_producto", $datos["productos"], PDO::PARAM_INT);
					$query->bindParam(":".$nombremes_actual, $datos["cantidades"], PDO::PARAM_STR);
					$query->execute();

                    if($stmt){
                        return "ok";
                    }else{
                        return "error";
                    }
            }else{
                return "error";
            }

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
   }

}
/*==========================================================*/    
/*=============================================
	AUMENTA CANT EN LA EDICION DE SALIDA DE ALM
=============================================*/
static public function mdEditAumentarRegSA($tabla_almacen, $id_almacen, $tablahist, $tablakardex, $key, $nuevovalor, $nombremes_actual){
    try {      
        $stmt = Conexion::conectar()->prepare("UPDATE $tablahist SET cantidad=:cantidad+cantidad WHERE id_producto = :id_producto AND id_almacen=:id_almacen");
        $stmt->bindParam(":id_producto", $key, PDO::PARAM_INT);
        $stmt->bindParam(":id_almacen", $id_almacen, PDO::PARAM_INT);
        $stmt->bindParam(":cantidad", $nuevovalor, PDO::PARAM_INT);
        $stmt->execute();
        
        if($stmt){

            $stmt = Conexion::conectar()->prepare("UPDATE $tabla_almacen SET cant=cant-(:cant) WHERE id_producto = :id_producto");
            $stmt->bindParam(":id_producto", $key, PDO::PARAM_INT);
            $stmt->bindParam(":cant", $nuevovalor, PDO::PARAM_INT);
            $stmt->execute();
    
   			//GUARDA EN KARDEX DEL ALMACEN ELEGIDO
            $stmt = Conexion::conectar()->prepare("UPDATE $tablakardex SET $nombremes_actual=$nombremes_actual-(:$nombremes_actual) WHERE id_producto = :id_producto");
            $stmt->bindParam(":id_producto", $key, PDO::PARAM_INT);
            $stmt->bindParam(":".$nombremes_actual, $nuevovalor, PDO::PARAM_INT);
            $stmt->execute();
   
            return "ok";
         }else{
              return "error";
         }

    } catch (Exception $e) {
            echo "Failed: " . $e->getMessage();
    }
   
}

/*=============================================
DISMINUYE CANT EN LA EDICION DE SALIDA DE ALM
=============================================*/
static public function mdEditDisminuirRegSA($tabla_almacen, $id_almacen, $tablahist, $tablakardex, $key, $nuevovalor, $nombremes_actual){
    try {      
        $stmt = Conexion::conectar()->prepare("UPDATE $tablahist SET cantidad=cantidad-(:cantidad) WHERE id_producto = :id_producto AND id_almacen=:id_almacen");
        $stmt->bindParam(":id_producto", $key, PDO::PARAM_INT);
        $stmt->bindParam(":id_almacen", $id_almacen, PDO::PARAM_INT);
        $stmt->bindParam(":cantidad", $nuevovalor, PDO::PARAM_INT);
        $stmt->execute();
        
        if($stmt){

            $stmt = Conexion::conectar()->prepare("UPDATE $tabla_almacen SET cant=:cant+cant WHERE id_producto = :id_producto");
            $stmt->bindParam(":id_producto", $key, PDO::PARAM_INT);
            $stmt->bindParam(":cant", $nuevovalor, PDO::PARAM_INT);
            $stmt->execute();
    
   			//GUARDA EN KARDEX DEL ALMACEN ELEGIDO
            $stmt = Conexion::conectar()->prepare("UPDATE $tablakardex SET $nombremes_actual=:$nombremes_actual+$nombremes_actual WHERE id_producto = :id_producto");
            $stmt->bindParam(":id_producto", $key, PDO::PARAM_INT);
            $stmt->bindParam(":".$nombremes_actual, $nuevovalor, PDO::PARAM_INT);
            $stmt->execute();
   
            return "ok";
         }else{
              return "error";
         }

    } catch (Exception $e) {
            echo "Failed: " . $e->getMessage();
    }
   
}

/*=============================================
OBTIENE LOS DATOS PARA ELIMINAR SALIDA DEL ALMACEN
=============================================*/
static public function mdlBorrarSalidaAlmacen($tabla_hist, $idaborrar, $campo){
    try {      
        //$idaborrar=200;
        $sql="SELECT tb1.id, tb1.id_salida, tb1.id_tecnico, tb1.fecha_salida, tb1.cantidad, tb1.id_producto,
        tb1.id_almacen,b.nombre AS nombrealmacen,tb1.id_tipomov,
        tb1.id_usuario
        FROM $tabla_hist tb1
        INNER JOIN almacenes b ON tb1.id_almacen=b.id
        WHERE tb1.$campo=:$campo";

        $stmt=Conexion::conectar()->prepare($sql);

        $stmt->bindParam(":".$campo, $idaborrar, PDO::PARAM_INT);
                    
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }else{
            //$respuesta = array("error" =>'vacio');
            $respuesta = "error";
            return $respuesta;
        }
                
        $stmt = null;

     } catch (Exception $e) {
            echo "Failed: " . $e->getMessage();
    }
   
}

/*=============================================
GUARDA CANCELACION EN TABLA cancelacion_salidas
=============================================*/
static public function mdlGuardaCancelacion($tabla_cancela, $idnumcancela, $datos, $idusuario){
try {      

    foreach ($datos as $value) {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla_cancela(id_cancelacion, id_tecnico, id_salida, fecha_salida, id_producto, cantidad, id_almacen, id_capturo, ultusuario) VALUES (:id_cancelacion, :id_tecnico, :id_salida, :fecha_salida, :id_producto, :cantidad, :id_almacen, :id_capturo, :ultusuario)");

        $stmt->bindParam(":id_cancelacion", $idnumcancela, PDO::PARAM_INT);
        $stmt->bindParam(":id_tecnico", 	$value["id_tecnico"], PDO::PARAM_INT);
        $stmt->bindParam(":id_salida", 	    $value["id_salida"], PDO::PARAM_INT);
        $stmt->bindParam(":fecha_salida", 	$value["fecha_salida"], PDO::PARAM_STR);
        $stmt->bindParam(":id_producto", 	$value["id_producto"], PDO::PARAM_INT);
        $stmt->bindParam(":cantidad", 	    $value["cantidad"], PDO::PARAM_INT);
        $stmt->bindParam(":id_almacen",     $value["id_almacen"], PDO::PARAM_INT);
        $stmt->bindParam(":id_capturo",     $value["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":ultusuario",     $idusuario, PDO::PARAM_INT);
        $stmt->execute();
        
    }

    if($stmt){
        return "ok";
    }else{
        return "error";
    }

} catch (Exception $e) {
        echo "Failed: " . $e->getMessage();
}

}

/*=============================================
ACTUALIZA EXISTENCIA POR CANCELACION DE SALIDA
=============================================*/
static public function mdlActualizaExistencia($tabla_almacen, $tabla_kardex, $nombremes_actual, $datos){
    try {      
    
        foreach ($datos as $value) {

            $stmt = Conexion::conectar()->prepare("UPDATE $tabla_almacen SET cant=:cant+cant WHERE id_producto = :id_producto");
            $stmt->bindParam(":id_producto",    $value["id_producto"], PDO::PARAM_INT);
            $stmt->bindParam(":cant",           $value["cantidad"], PDO::PARAM_INT);
            $stmt->execute();
    
   			//GUARDA EN KARDEX DEL ALMACEN ELEGIDO
            $stmt = Conexion::conectar()->prepare("UPDATE $tabla_kardex SET $nombremes_actual=:$nombremes_actual+$nombremes_actual WHERE id_producto = :id_producto");
            $stmt->bindParam(":id_producto",        $value["id_producto"], PDO::PARAM_INT);
            $stmt->bindParam(":".$nombremes_actual, $value["cantidad"], PDO::PARAM_INT);
            $stmt->execute();
            
        }
    
        if($stmt){
            return "ok";
        }else{
            return "error";
        }
    
    } catch (Exception $e) {
            echo "Failed: " . $e->getMessage();
    }
    
}
    
/*=============================================
ELIMINAR DATOS EN EL HIST_SALIDAS
=============================================*/
static public function mdlEliminarDatos($tabla, $idaborrar, $campo){
    try {      
        //$idaborrar=200;
        $sql="DELETE FROM $tabla WHERE $campo=:$campo";
        $stmt=Conexion::conectar()->prepare($sql);
        $stmt->bindParam(":".$campo, $idaborrar, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt) {
            return "ok";
        }else{
            $respuesta = array("error" =>'Eliminacion');
            //$respuesta = null;
            return $respuesta;
        }
                
        $stmt = null;

     } catch (Exception $e) {
            echo "Failed: " . $e->getMessage();
    }
   
}


/*=============================================
	OBTENER EL ULTIMO REGISTRO-ID CAPTURADO 
=============================================*/
static public function mdlObtenerUltimoId($tabla, $campo){
	try {
			
        if($campo==null){
            $stmt=Conexion::conectar()->prepare("SELECT MAX(id) AS id FROM $tabla");
        }else{
            $stmt=Conexion::conectar()->prepare("SELECT MAX($campo) AS idcancela FROM $tabla");
        }

        $stmt->execute();
        
        return $stmt->fetch();

		$stmt = null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
	}

}  


} //fin de la clase

/*
copiar datos de una columna de una tabla a otra tabla
INSERT INTO `kardex_alm_tuxtla`(`id_producto`, `november`) SELECT id_producto, cant FROM alm_tuxtla
 */

?>
