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

} //fin de la clase


?>
