<?php
date_default_timezone_set('America/Mexico_City');
require_once dirname( __DIR__ ).'/modelos/conexion.php';
class ModeloEntradasAlmacen{

/*=============================================
	LISTAR SALIDAS
=============================================*/

static public function mdlListarEntradas($tabla, $fechadev1, $fechadev2){
try {
 	//$where='1=1';
		  
	$where='tb1.fechaentrada>="'.$fechadev1.'" AND tb1.fechaentrada<="'.$fechadev2.'" ';
  
	$where.=' ORDER BY tb1.id DESC';
  
	$sql="SELECT tb1.*, alm.nombre AS nombrealmacen, prov.nombre AS nombreproveedor, mov.nombre_tipo AS nombretipomov, usu.usuario 
    FROM $tabla tb1 
    INNER JOIN almacenes alm ON tb1.id_almacen=alm.id 
    INNER JOIN proveedores prov ON tb1.id_proveedor=prov.id 
    INNER JOIN tipomovimiento mov ON tb1.id_tipomov=mov.id 
    INNER JOIN usuarios usu ON tb1.ultusuario=usu.id 
    WHERE ".$where;
  
	$stmt = Conexion::conectar()->prepare($sql);
  
	$stmt -> execute();
  
	return $stmt -> fetchAll();
  
    $stmt = null;

} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}

}	

/*=============================================
	OBTENER EL ULTIMO REGISTRO-ID CAPTURADO 
=============================================*/
static public function mdlObtenerUltimoNumero($tabla, $campo){
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

/*=============================================
	MOSTRAR TIPO DE MOV DE SALIDA
=============================================*/
static public function MdlMostrarTipoMov($tabla, $item, $valor){
try {
    $stmt = Conexion::conectar()->prepare("SELECT id, nombre_tipo, clase FROM $tabla WHERE $item = :$item");
    
    $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);

	$stmt -> execute();

	 return $stmt -> fetchAll();

	$stmt = null;
} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}

}

/*=============================================
	MOSTRAR TIPO DE MOV DE SALIDA
=============================================*/
static public function MdlajaxProductos($tabla, $campo, $valor){
try {    

    $stmt = Conexion::conectar()->prepare("SELECT tb1.id, tb1.codigointerno, tb1.descripcion, med.medida 
    FROM $tabla tb1
    INNER JOIN medidas med ON tb1.id_medida=med.id
    WHERE tb1.$campo LIKE '%".$valor."%' ");
	//$stmt = Conexion::conectar()->prepare("SELECT id, codigointerno, descripcion FROM $tabla");
    
    //$stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
    //$stmt->bindParam(":estado", $estado, PDO::PARAM_INT);

	$stmt -> execute();

	 return $stmt -> fetchAll();

	$stmt = null;
} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}

}

/*==========================================================*/
static Public function mdlConsultaExistenciaProd($tabla, $campo, $valor){
try {        
    $stmt = Conexion::conectar()->prepare("SELECT a.cant , m.medida 
	FROM $tabla a 
    INNER JOIN productos p ON a.id_producto=p.id
    INNER JOIN medidas m ON p.id_medida=m.id
    WHERE $campo = :$campo");

    $stmt -> bindParam(":$campo", $valor, PDO::PARAM_STR);
    $stmt -> execute();
    return $stmt -> fetch();      
	$stmt=null;
} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}
	
}

/*=============================================
	REGISTRO DE PRODUCTO
=============================================*/
static public function mdlAltaEntradasAlmacen($tabla_almacen, $tabla, $datos){
	try {      
        //GUARDAMOS EN TBL_ENTRADAS
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(fechaentrada, id_proveedor, id_almacen, id_tipomov, observacion, ultusuario) VALUES (:fechaentrada, :id_proveedor, :id_almacen, :id_tipomov, :observacion, :ultusuario)");
        $stmt->bindParam(":fechaentrada", 	$datos["fechaentrada"], PDO::PARAM_STR);
        $stmt->bindParam(":id_proveedor", 	$datos["id_proveedor"], PDO::PARAM_INT);
        $stmt->bindParam(":id_almacen",     $datos["id_almacen"], PDO::PARAM_INT);
        $stmt->bindParam(":id_tipomov", 	$datos["id_tipomov"], PDO::PARAM_INT);
        $stmt->bindParam(":observacion",    $datos["observacion"], PDO::PARAM_STR);
        $stmt->bindParam(":ultusuario",     $datos["ultusuario"], PDO::PARAM_INT);
        $stmt->execute();

        if($stmt){

            //OBTENEMOS EL ÚLTIMO ID GUARDADO EN TBL_ENTRADAS
            $campo=null;
            $query=self::mdlObtenerUltimoNumero($tabla, $campo);
            $idnumentrada=$query[0];
            if(is_null($idnumentrada)){
            	$idnumentrada=1;
            }

            $contador = count($datos["productos"]);    //CUANTO PRODUCTOS VIENEN PARA EL FOR
            $nombremes_actual = strtolower(date('F'));  // NOMBRE DEL MES ACTUAL PARA ACTUALIZAR KARDEX 
            $tabla_kardex="kardex_".$tabla_almacen;     //TABLA KARDEX SEGUN ALMACEN SELECCIONADO

            //GUARDA ENTRADAS DE PRODUCTOS EN HIST_ENTRADAS
            $stmt = Conexion::conectar()->prepare("INSERT INTO hist_entrada(id_entrada, id_proveedor, fechadocto, numerodocto, fechaentrada, tipomov, id_producto, cantidad, id_almacen, ultusuario) VALUES (:id_entrada,:id_proveedor,:fechadocto, :numerodocto, :fechaentrada, :tipomov, :id_producto, :cantidad, :id_almacen, :ultusuario)");
            
            for($i=0;$i<$contador;$i++) {
                $stmt->bindParam(":id_entrada",     $idnumentrada, PDO::PARAM_INT);
                $stmt->bindParam(":id_proveedor",   $datos["id_proveedor"], PDO::PARAM_INT);
                $stmt->bindParam(":fechadocto",     $datos["fechaentrada"], PDO::PARAM_STR);
                $stmt->bindParam(":numerodocto",    $idnumentrada, PDO::PARAM_STR);
                $stmt->bindParam(":fechaentrada",   $datos["fechaentrada"], PDO::PARAM_STR);
                $stmt->bindParam(":tipomov",        $datos["id_tipomov"], PDO::PARAM_INT);
                $stmt->bindParam(":id_producto",    $datos["productos"][$i], PDO::PARAM_INT);
                $stmt->bindParam(":cantidad",       $datos["cantidades"][$i], PDO::PARAM_INT);
                $stmt->bindParam(":id_almacen",     $datos["id_almacen"], PDO::PARAM_INT);
                $stmt->bindParam(":ultusuario",     $datos["ultusuario"], PDO::PARAM_INT);
                $stmt->execute();
                if($stmt){
                    $id_articulo=$datos["productos"][$i];
                    //CONSULTA SI PRODUCTO EXISTE, PARA ACTUALIZAR O INSERTAR
                    $existeid=Conexion::conectar()->prepare("SELECT IF(EXISTS(SELECT * FROM $tabla_almacen WHERE id_producto=:id_producto), 1, 0) AS idx");
                    $existeid->bindParam(":id_producto", $id_articulo, PDO::PARAM_INT);
                    $existeid->execute();
                    $rsp = $existeid->fetch();
                    
                    if($rsp["idx"]){        //SI EXISTE ACTUALIZA
                        //ACTUALIZA PRODUCTO EN ALMACEN SELECCIONADO
                        $stmt1 = Conexion::conectar()->prepare("UPDATE $tabla_almacen SET cant=:cant+cant, fecha_entrada=:fecha_entrada, ultusuario=:ultusuario WHERE id_producto = :id_producto");
						
                        $stmt1->bindParam(":id_producto", $datos["productos"][$i], PDO::PARAM_INT);
                        $stmt1->bindParam(":cant", $datos["cantidades"][$i], PDO::PARAM_INT);
                        $stmt1->bindParam(":fecha_entrada", $datos["fechaentrada"], PDO::PARAM_STR);
                        $stmt1->bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);
                        $stmt1->execute();


                        //ACTUALIZA PRODUCTO EN KARDEX DEL ALMACEN ELEGIDO
                        $stmt2 = Conexion::conectar()->prepare("UPDATE $tabla_kardex SET $nombremes_actual=:$nombremes_actual+$nombremes_actual WHERE id_producto = :id_producto");
                        $stmt2->bindParam(":id_producto",        $datos["productos"][$i], PDO::PARAM_INT);
                        $stmt2->bindParam(":".$nombremes_actual, $datos["cantidades"][$i], PDO::PARAM_INT);
                        $stmt2->execute();
      
                        
                      }else{    //DE LO CONTRARIO INSERTA

                        //INSERTA PRODUCTO EN ALMACEN SELECCIONADO
                        $stmt1 = Conexion::conectar()->prepare("INSERT INTO $tabla_almacen(id_producto, cant, fecha_entrada, ultusuario) VALUES (:id_producto, :cant, :fecha_entrada, :ultusuario)");

                        $stmt1->bindParam(":id_producto", $datos["productos"][$i], PDO::PARAM_INT);
                        $stmt1->bindParam(":cant", $datos["cantidades"][$i], PDO::PARAM_INT);
                        $stmt1->bindParam(":fecha_entrada", $datos["fechaentrada"], PDO::PARAM_STR);
                        $stmt1->bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);
                        $stmt1->execute();

                        //INSERTA PRODUCTO EN KARDEX DEL ALMACEN ELEGIDO
                        $stmt2 = Conexion::conectar()->prepare("INSERT INTO $tabla_kardex (id_producto, $nombremes_actual) VALUES (:id_producto, :$nombremes_actual)");
                        $stmt2->bindParam(":id_producto",        $datos["productos"][$i], PDO::PARAM_INT);
                        $stmt2->bindParam(":".$nombremes_actual, $datos["cantidades"][$i], PDO::PARAM_INT);
                        $stmt2->execute();

                    }

                }else{
                    return "error";
               }
    
            }      //fin del For

           return "ok";
        }else{
            return "error";
        }

        $stmt=null;
        $stmt1=null;
        $stmt2=null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
   }

}


/*=============================================
	REPORTE DE ENTRADAS
=============================================*/	
static Public function mdlReporteEntradaAlmacen($tabla, $tabla_hist, $item, $numeroid){
try {         
    $sql="SELECT tb1.id, tb1.fechaentrada, tb1.id_proveedor,prov.nombre AS nombreproveedor, tb1.observacion, h.id_producto, h.cantidad, a.descripcion, a.codigointerno, a.id_medida, m.medida, tb1.id_almacen, b.nombre AS nombrealmacen, tb1.id_tipomov, tm.nombre_tipo AS nombretipo, tb1.ultusuario, u.nombre AS nombreusuario 
    FROM $tabla tb1
    INNER JOIN $tabla_hist h ON tb1.id=h.id_entrada
    INNER JOIN proveedores prov ON tb1.id_proveedor=prov.id
    INNER JOIN productos a ON h.id_producto=a.id
    INNER JOIN almacenes b ON tb1.id_almacen=b.id
    INNER JOIN medidas m ON a.id_medida=m.id
    INNER JOIN tipomovimiento tm ON tb1.id_tipomov=tm.id
    INNER JOIN usuarios u ON tb1.ultusuario=u.id
    WHERE h.$item=:$item";
    
       $stmt=Conexion::conectar()->prepare($sql);
       
       $stmt->bindParam(":".$item, $numeroid, PDO::PARAM_INT);
       
       $stmt->execute();
       
       return $stmt->fetchAll();
       
       $stmt=null;
       
} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
}

}

/*=============================================
  MOSTRAR ENTRADAS AL ALMACEN PARA EDITAR 
============================================*/
static public function mdlMostrarEntradasAlmacen($tabla, $campo, $valor){

    try {          

        $sql="SELECT tbl.id, tbl.id_proveedor,prov.nombre AS nombreproveedor, tbl.fechaentrada, h.cantidad,tbl.observacion, h.id_producto,
        a.descripcion,a.codigointerno, a.id_medida, m.medida, tbl.id_almacen,b.nombre AS nombrealmacen,tbl.id_tipomov,
        tm.nombre_tipo,tbl.ultusuario,u.nombre AS nombreusuario 
        FROM $tabla tbl
        INNER JOIN hist_entrada h ON tbl.id=h.id_entrada
        INNER JOIN proveedores prov ON tbl.id_proveedor=prov.id
        INNER JOIN productos a ON h.id_producto=a.id
        INNER JOIN almacenes b ON tbl.id_almacen=b.id
        INNER JOIN medidas m ON a.id_medida=m.id
        INNER JOIN tipomovimiento tm ON tbl.id_tipomov=tm.id
        INNER JOIN usuarios u ON tbl.ultusuario=u.id
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
	ELIMINAR PRODUCTO DE ENTRADA DE ALMACEN
=============================================*/
static public function mdlEditEliminarRegEA($tabla_almacen, $id_almacen, $tablahist, $tablakardex, $key, $value, $id_entrada, $nombremes_actual){
    try {      
        //MODIFICAR PARA PRODUCCION. DEBE SER DELETE
        //$stmt = Conexion::conectar()->prepare("UPDATE $tablahist SET precio_venta=:precio_venta WHERE id_producto = :id_producto AND id_almacen=:id_almacen");
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tablahist WHERE id_entrada=:id_entrada AND id_producto=:id_producto AND id_almacen=:id_almacen");
        $stmt->bindParam(":id_entrada", $id_entrada, PDO::PARAM_INT);
        $stmt->bindParam(":id_producto", $key, PDO::PARAM_INT);
        $stmt->bindParam(":id_almacen", $id_almacen, PDO::PARAM_INT);

        $stmt->execute();
        
        if($stmt){

            //GUARDA EN EL ALMACEN ELEGIDO
            $stmt = Conexion::conectar()->prepare("UPDATE $tabla_almacen SET cant=cant-(:cant) WHERE id_producto = :id_producto");
            $stmt->bindParam(":id_producto", $key, PDO::PARAM_INT);
            $stmt->bindParam(":cant", $value, PDO::PARAM_INT);
            $stmt->execute();
    
   			//GUARDA EN KARDEX DEL ALMACEN ELEGIDO
            $stmt = Conexion::conectar()->prepare("UPDATE $tablakardex SET $nombremes_actual=$nombremes_actual-(:$nombremes_actual) WHERE id_producto = :id_producto");
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

/*==================================================
ALTA Y ACTUALIZA PRODUCTO EN LA EDICION DE ENTRADA AL ALMACEN
===================================================*/
static public function mdlEditAdicionarRegEA($tabla_almacen, $tablahist, $tablakardex, $nombremes_actual, $datos){
	try {      
            
            //SCRIP QUE REGISTRA LA ENTRADA EN HIST_ENTRADA
            $stmt = Conexion::conectar()->prepare("INSERT INTO $tablahist(id_entrada, fechadocto, numerodocto, id_proveedor, fechaentrada, id_producto, cantidad, id_almacen, tipomov, ultusuario) VALUES (:id_entrada, :fechadocto, :numerodocto, :id_proveedor, :fechaentrada, :id_producto, :cantidad, :id_almacen, :tipomov, :ultusuario)");
                
            $stmt->bindParam(":id_entrada", $datos["id_entrada"], PDO::PARAM_INT);
            $stmt->bindParam(":fechadocto", $datos["fechaentrada"], PDO::PARAM_STR);
            $stmt->bindParam(":id_proveedor", $datos["id_proveedor"], PDO::PARAM_INT);
            $stmt->bindParam(":numerodocto", $datos["id_entrada"], PDO::PARAM_STR);
            $stmt->bindParam(":fechaentrada", $datos["fechaentrada"], PDO::PARAM_STR);
            $stmt->bindParam(":id_producto", $datos["productos"], PDO::PARAM_INT);
            $stmt->bindParam(":cantidad", $datos["cantidades"], PDO::PARAM_INT);
            $stmt->bindParam(":id_almacen", $datos["id_almacen"], PDO::PARAM_INT);
            $stmt->bindParam(":tipomov", $datos["id_tipomov"], PDO::PARAM_INT);
            $stmt->bindParam(":ultusuario", $datos["id_usuario"], PDO::PARAM_INT);
            $stmt->execute();

            
            if($stmt){
                
                $id_articulo=$datos["productos"];
                //CONSULTA SI PRODUCTO EXISTE, PARA ACTUALIZAR O INSERTAR
                $existeid=Conexion::conectar()->prepare("SELECT IF(EXISTS(SELECT * FROM $tabla_almacen WHERE id_producto=:id_producto), 1, 0) AS idx");
                $existeid->bindParam(":id_producto", $id_articulo, PDO::PARAM_INT);
                $existeid->execute();
                $rsp = $existeid->fetch();
                
                if($rsp["idx"]){        //SI EXISTE=1 ACTUALIZA

                    //SCRIP QUE REGISTRA LA ENTRADA EN EL ALMACEN ELEGIDO   
                    $stmt = Conexion::conectar()->prepare("UPDATE $tabla_almacen SET cant=:cant+cant, ultusuario=:ultusuario WHERE id_producto = :id_producto");
                     $stmt->bindParam(":id_producto", $datos["productos"], PDO::PARAM_INT);
                     $stmt->bindParam(":cant", $datos["cantidades"], PDO::PARAM_INT);
                     $stmt->bindParam(":ultusuario", $datos["id_usuario"], PDO::PARAM_INT);
                     $stmt->execute();

   					//GUARDA EN KARDEX DEL ALMACEN ELEGIDO
					$query = Conexion::conectar()->prepare("UPDATE $tablakardex SET $nombremes_actual=:$nombremes_actual+$nombremes_actual WHERE id_producto = :id_producto");
					$query->bindParam(":id_producto", $datos["productos"], PDO::PARAM_INT);
					$query->bindParam(":".$nombremes_actual, $datos["cantidades"], PDO::PARAM_STR);
					$query->execute();

                    if($stmt){
                        return "ok";
                    }else{
                        return "error";
                    }
                }else{    //DE LO CONTRARIO=0 INSERTA

                    //INSERTA PRODUCTO EN ALMACEN SELECCIONADO
                    $stmt1 = Conexion::conectar()->prepare("INSERT INTO $tabla_almacen(id_producto, cant, fecha_entrada, ultusuario) VALUES (:id_producto, :cant, :fecha_entrada, :ultusuario)");

                    $stmt1->bindParam(":id_producto", $datos["productos"], PDO::PARAM_INT);
                    $stmt1->bindParam(":cant", $datos["cantidades"], PDO::PARAM_INT);
                    $stmt1->bindParam(":fecha_entrada", $datos["fechaentrada"], PDO::PARAM_STR);
                    $stmt1->bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);
                    $stmt1->execute();

                    //INSERTA PRODUCTO EN KARDEX DEL ALMACEN ELEGIDO
                    $stmt2 = Conexion::conectar()->prepare("INSERT INTO $tablakardex (id_producto, $nombremes_actual) VALUES (:id_producto, :$nombremes_actual)");
                    $stmt2->bindParam(":id_producto",        $datos["productos"], PDO::PARAM_INT);
                    $stmt2->bindParam(":".$nombremes_actual, $datos["cantidades"], PDO::PARAM_INT);
                    $stmt2->execute();

                    if($stmt2){
                        return "ok";
                    }else{
                        return "error";
                    }

                }         

            }else{
                return "error";
            }

            $stmt = null;
            $stmt1 = null;
            $stmt2 = null;
            $existeid=null;

	} catch (Exception $e) {
		echo "Failed: " . $e->getMessage();
   }

}

/*=============================================
	AUMENTA CANT EN LA EDICION DE ENTRADA AL ALM
=============================================*/
static public function mdlEditAumentarRegEA($tabla_almacen, $id_almacen, $tablahist, $tablakardex, $key, $nuevovalor, $nombremes_actual){
    try {      
        $stmt = Conexion::conectar()->prepare("UPDATE $tablahist SET cantidad=:cantidad+cantidad WHERE id_producto = :id_producto AND id_almacen=:id_almacen");
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
DISMINUYE CANT EN LA EDICION DE ENTRADA DE ALM
=============================================*/
static public function mdlEditDisminuirRegEA($tabla_almacen, $id_almacen, $tablahist, $tablakardex, $key, $nuevovalor, $nombremes_actual){
    try {      
        $stmt = Conexion::conectar()->prepare("UPDATE $tablahist SET cantidad=cantidad-(:cantidad) WHERE id_producto = :id_producto AND id_almacen=:id_almacen");
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
OBTIENE LOS DATOS PARA ELIMINAR ENTRADA DEL ALMACEN
=============================================*/
static public function mdlBorrarEntradaAlmacen($tabla_hist, $idaborrar, $campo){
    try {      
        //$idaborrar=200;
        $sql="SELECT tb1.id, tb1.id_entrada, tb1.id_proveedor, tb1.fechaentrada, tb1.cantidad, tb1.id_producto,
        tb1.id_almacen, b.nombre AS nombrealmacen,tb1.tipomov, tb1.ultusuario
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
GUARDA CANCELACION EN TABLA CANCELACION_ENTRADAS
=============================================*/
static public function mdlGuardaCancelacion($tabla_cancela, $idnumcancela, $datos, $idusuario){
    try {      
    
        foreach ($datos as $value) {
            $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla_cancela(id_cancelacion, id_entrada, id_proveedor, fecha_entrada, id_producto, cantidad, id_almacen, id_capturo, ultusuario) VALUES (:id_cancelacion, :id_entrada, :id_proveedor, :fecha_entrada, :id_producto, :cantidad, :id_almacen, :id_capturo, :ultusuario)");
    
            $stmt->bindParam(":id_cancelacion", $idnumcancela, PDO::PARAM_INT);
            $stmt->bindParam(":id_entrada", 	$value["id_entrada"], PDO::PARAM_INT);
            $stmt->bindParam(":id_proveedor", 	$value["id_proveedor"], PDO::PARAM_INT);
            $stmt->bindParam(":fecha_entrada", 	$value["fechaentrada"], PDO::PARAM_STR);
            $stmt->bindParam(":id_producto", 	$value["id_producto"], PDO::PARAM_INT);
            $stmt->bindParam(":cantidad", 	    $value["cantidad"], PDO::PARAM_INT);
            $stmt->bindParam(":id_almacen",     $value["id_almacen"], PDO::PARAM_INT);
            $stmt->bindParam(":id_capturo",     $value["ultusuario"], PDO::PARAM_INT);
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
ACTUALIZA EXISTENCIA POR CANCELACION DE ENTRADA
=============================================*/
static public function mdlActualizaExistencia($tabla_almacen, $tabla_kardex, $nombremes_actual, $datos){
    try {      
    
        foreach ($datos as $value) {

            $stmt = Conexion::conectar()->prepare("UPDATE $tabla_almacen SET cant=cant-(:cant) WHERE id_producto = :id_producto");
            $stmt->bindParam(":id_producto",    $value["id_producto"], PDO::PARAM_INT);
            $stmt->bindParam(":cant",           $value["cantidad"], PDO::PARAM_INT);
            $stmt->execute();
    
   			//GUARDA EN KARDEX DEL ALMACEN ELEGIDO
            $stmt = Conexion::conectar()->prepare("UPDATE $tabla_kardex SET $nombremes_actual=$nombremes_actual-(:$nombremes_actual) WHERE id_producto = :id_producto");
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
ELIMINAR DATOS EN EL HIST_ENTRADAS
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
            return $respuesta;
        }
                
        $stmt = null;

     } catch (Exception $e) {
            echo "Failed: " . $e->getMessage();
    }
   
}


} //fin de la clase


?>
