<?php
date_default_timezone_set('America/Mexico_City');
require_once dirname( __DIR__ ).'/modelos/conexion.php';
class ModeloEntradasAlmacen{

/*=============================================
	LISTAR SALIDAS
=============================================*/

static public function mdlListarEntradas($tabla, $fechadev1, $fechadev2){

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

    $stmt = Conexion::conectar()->prepare("SELECT id, nombre_tipo, clase FROM $tabla WHERE $item = :$item");
    
    $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);

	$stmt -> execute();

	 return $stmt -> fetchAll();

	$stmt = null;

}

/*=============================================
	MOSTRAR TIPO DE MOV DE SALIDA
=============================================*/
static public function MdlajaxProductos($tabla, $campo, $valor){
	$estado=1;
    $stmt = Conexion::conectar()->prepare("SELECT id, codigointerno, descripcion FROM $tabla WHERE $campo LIKE '%".$valor."%' ");
	//$stmt = Conexion::conectar()->prepare("SELECT id, codigointerno, descripcion FROM $tabla");
    
    //$stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
    //$stmt->bindParam(":estado", $estado, PDO::PARAM_INT);

	$stmt -> execute();

	 return $stmt -> fetchAll();

	$stmt = null;

}

/*==========================================================*/
static Public function mdlConsultaExistenciaProd($tabla, $campo, $valor){
    $stmt = Conexion::conectar()->prepare("SELECT a.cant , m.medida 
	FROM $tabla a 
    INNER JOIN productos p ON a.id_producto=p.id
    INNER JOIN medidas m ON p.id_medida=m.id
    WHERE $campo = :$campo");

    $stmt -> bindParam(":$campo", $valor, PDO::PARAM_STR);
    $stmt -> execute();
    return $stmt -> fetch();      
	$stmt=null;
	
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
}




} //fin de la clase


?>
