<?php
date_default_timezone_set('America/Mexico_City');
require_once "conexion.php";

class ModeloAlmacen{
	
static Public function MdlMostrarAlmacen($tabla, $campo, $valor){
try{
  if($valor != null){
			//$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $campo = :$campo");
			$stmt = Conexion::conectar()->prepare("SELECT a.*,p.id_medida,m.medida FROM $tabla a 
            INNER JOIN productos p ON a.id_producto=p.id
            INNER JOIN medidas m ON p.id_medida=m.id
            WHERE $campo = :$campo");

			$stmt -> bindParam(":$campo", $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();      
  }else{
	 $sql="SELECT a.id, a.id_producto, a.codigointerno, p.descripcion, p.id_medida, m.medida, a.cant,p.minimo, a.precio_compra,a.fecha_entrada FROM $tabla a INNER JOIN productos p ON a.id_producto=p.id INNER JOIN medidas m ON p.id_medida=m.id ";
	 
        $stmt=Conexion::conectar()->prepare($sql);
		
        //$stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $stmt->fetchAll();
        
         //if ( $stmt->rowCount() > 0 ) { do something here }
  }
    
      if($stmt){

     	}else{
 		      return false;
      }         
        
        $stmt->close();
        
        $stmt=null;

} catch (Exception $e) {
  echo "Failed: " . $e->getMessage();
}
    
}


static Public function MdlMostrarEntradas($tabla, $campo, $valor){
try{
    if($tabla>0){			//QUE ALMACEN MOSTRAR ENTRADAS
      $sql="SELECT h.`numerodocto`,h.`fechaentrada`, sum(h.`cantidad`) AS entro,h.`id_proveedor`,prov.nombre,h.`id_almacen`,alm.nombre as almacen FROM hist_entrada h
      INNER JOIN proveedores prov ON id_proveedor=prov.id
      INNER JOIN almacenes alm ON id_almacen=alm.id 
      WHERE id_almacen=$tabla
      GROUP by `fechaentrada`,`numerodocto`,`id_almacen`,`id_proveedor`";
      
    }else{                  // TODOS LOS ALMACENES

      $sql="SELECT h.`numerodocto`,h.`fechaentrada`, sum(h.`cantidad`) AS entro,h.`id_proveedor`,prov.nombre,h.`id_almacen`,alm.nombre as almacen FROM hist_entrada h
      INNER JOIN proveedores prov ON id_proveedor=prov.id
      INNER JOIN almacenes alm ON id_almacen=alm.id
      GROUP by `numerodocto`,`fechaentrada`,`id_almacen`,`id_proveedor`";
    }
        $stmt=Conexion::conectar()->prepare($sql);
		
        //$stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
        
        if($stmt->execute()){;
        
         return $stmt->fetchAll();
        
         //if ( $stmt->rowCount() > 0 ) { do something here }
        
 	  }else{

			return false;

	   }         
        
        $stmt->close();
        
        $stmt=null;
} catch (Exception $e) {
  echo "Failed: " . $e->getMessage();
}
    
    }	
        
/*
SELECT h.id_proveedor,p.nombre,h.fechadocto,h.numerodocto, h.fechaentrada, h.recibio,h.cantidad,h.precio_compra,h.id_producto,a.descripcion,a.codigointerno,h.id_almacen,b.nombre FROM hist_entrada h INNER JOIN proveedores p ON h.id_proveedor=p.id 
INNER JOIN productos a ON h.id_producto=a.id
INNER JOIN almacenes b ON h.id_almacen=b.id
WHERE h.numerodocto=8
*/
    
    

}       //fin de la clase