<?php
date_default_timezone_set('America/Mexico_City');
require_once "conexion.php";

class ModeloEntradas{

	/*=============================================
	REGISTRO DE PRODUCTO
	=============================================*/
	static public function mdlIngresarEntrada($tabla,$nuevoProveedor,$fechaDocto,$numeroDocto,$nombreRecibe, $NuevoTipoEntrada, $id_producto,$codigointerno,$cantidad,$fechaEntrada,$id_almacen,$ultusuario){
        
        //CAMBIAR EL FORMATO DE FECHA A yyyy-mm-dd   
        $fechDocto = explode('/', $fechaDocto); 
        $newfecha = $fechDocto[2].'-'.$fechDocto[1].'-'.$fechDocto[0];

        $fechEntra = explode('/', $fechaEntrada); 
        $newDate = $fechEntra[2].'-'.$fechEntra[1].'-'.$fechEntra[0];
        
        $contador = count($id_producto);    //CUANTO PRODUCTOS VIENEN PARA EL FOR
        
    for($i=0;$i<$contador;$i++) { 
	  $cuantosReg=0;
	  $id_articulo=$id_producto[$i];
	  $consulta = Conexion::conectar()->prepare("SELECT id_producto FROM $tabla WHERE id_producto=:id_producto");
	  $consulta->bindParam(":id_producto", $id_articulo, PDO::PARAM_INT);
	  $consulta->execute();

	 if ($consulta) {
		$cuantosReg = $consulta->fetchAll();
       if (count($cuantosReg) > 0) {

        //$consulta->null;
	  
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET cant=:cant+cant, fecha_entrada=:fecha_entrada, ultusuario=:ultusuario WHERE id_producto = :id_producto");
						
        $stmt->bindParam(":id_producto", $id_producto[$i], PDO::PARAM_INT);
		$stmt->bindParam(":cant", $cantidad[$i], PDO::PARAM_INT);
		$stmt->bindParam(":fecha_entrada", $newDate, PDO::PARAM_STR);
		$stmt->bindParam(":ultusuario", $ultusuario, PDO::PARAM_INT);
        $stmt->execute();
		
	  }else{
		  
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_producto, codigointerno, cant, fecha_entrada, ultusuario) VALUES (:id_producto, :codigointerno, :cant, :fecha_entrada, :ultusuario)");
        $stmt->bindParam(":id_producto", $id_producto[$i], PDO::PARAM_INT);
		$stmt->bindParam(":codigointerno", $codigointerno[$i], PDO::PARAM_STR);
		$stmt->bindParam(":cant", $cantidad[$i], PDO::PARAM_INT);
		$stmt->bindParam(":fecha_entrada", $newDate, PDO::PARAM_STR);
		$stmt->bindParam(":ultusuario", $ultusuario, PDO::PARAM_INT);
        $stmt->execute();
	  }	
	 }else{
	   $stmt=false;
	 }
	 
    }	//TERMINA EL FOR
	
        //SCRIP QUE REGISTRA LA ENTRADA EN HIST_ENTRADA
		if($stmt){

        $stmt = Conexion::conectar()->prepare("INSERT INTO hist_entrada(id_proveedor, fechadocto, numerodocto, fechaentrada, recibio, tipomov, id_producto, cantidad, id_almacen, ultusuario) VALUES (:id_proveedor,:fechadocto, :numerodocto, :fechaentrada, :recibio, :tipomov, :id_producto, :cantidad, :id_almacen, :ultusuario)");
            
        for($i=0;$i<$contador;$i++) { 
            $stmt->bindParam(":id_proveedor", $nuevoProveedor, PDO::PARAM_INT);
            $stmt->bindParam(":fechadocto", $newfecha, PDO::PARAM_STR);
            $stmt->bindParam(":numerodocto", $numeroDocto, PDO::PARAM_STR);
            $stmt->bindParam(":fechaentrada", $newDate, PDO::PARAM_STR);
            $stmt->bindParam(":recibio", $nombreRecibe, PDO::PARAM_STR);
            $stmt->bindParam(":tipomov", $NuevoTipoEntrada, PDO::PARAM_INT);
            $stmt->bindParam(":id_producto", $id_producto[$i], PDO::PARAM_INT);
            $stmt->bindParam(":cantidad", $cantidad[$i], PDO::PARAM_INT);
            $stmt->bindParam(":id_almacen", $id_almacen, PDO::PARAM_INT);
            $stmt->bindParam(":ultusuario", $ultusuario, PDO::PARAM_INT);
            $stmt->execute();
        }
            if($stmt){
                return "ok";
            }else{
    			return "error";
		   }

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

  }
    
/*=============================================
	VALIDA QUE NUMERO DE ENTRADA NO SE REPITA
=============================================*/	    
  static Public function MdlValidarDocto($tabla, $campo, $valor){
     
     if($campo !=null){    
        $stmt=Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $campo=:$campo");
        
        $stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $stmt->fetch();
        
         //if ( $stmt->rowCount() > 0 ) { do something here }
        
 	}else{

			return false;

	 }        
        
        $stmt=null;
    }
	
/*=============================================
	REPORTE DE ENTRADAS
=============================================*/	
static Public function MdlEntradaAlm($tabla, $campo, $valor){
     
     if($campo !=null){    
         
	 $sql="SELECT h.id_proveedor,p.nombre AS nombreprov,h.fechadocto,h.numerodocto, h.fechaentrada, h.recibio,h.cantidad,h.precio_compra,h.id_producto,a.descripcion,a.codigointerno, a.id_medida, m.medida, h.id_almacen,b.nombre FROM $tabla h INNER JOIN proveedores p ON h.id_proveedor=p.id	
	    INNER JOIN productos a ON h.id_producto=a.id
		INNER JOIN almacenes b ON h.id_almacen=b.id
		INNER JOIN medidas m ON a.id_medida=m.id
		WHERE h.$campo=:$campo";
	 
        $stmt=Conexion::conectar()->prepare($sql);
		
        $stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $stmt->fetchAll();
        
         //if ( $stmt->rowCount() > 0 ) { do something here }
        
 	}else{

			return false;

	 }        
        
        
        $stmt=null;
    }
		

/*=============================================
	MOSTRAR ENTRADAS
=============================================*/	
static Public function MdlMostrarEntradaAlm($tabla){
 try {     
        
    $sql="SELECT DISTINCT(he.numerodocto), he.fechadocto, he.fechaentrada, he.id_almacen FROM $tabla he";
    
       $stmt=Conexion::conectar()->prepare($sql);
       
       //$stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
       
       $stmt->execute();
       
       return $stmt->fetchAll();
       
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