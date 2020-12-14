<?php
date_default_timezone_set('America/Mexico_City');
require_once "conexion.php";

class ModeloSalidas{

	/*=============================================
	REGISTRO DE PRODUCTO
	=============================================*/
	static public function mdlIngresarSalida($tabla,$nuevoTecnico,$fechaSalida,$numeroSalida,$id_producto,$cantidad,$precio_venta,$id_almacen, $tipo_mov, $ultusuario){
        
        //CAMBIAR EL FORMATO DE FECHA A yyyy-mm-dd   
        $fechSalida = explode('/', $fechaSalida); 
        $newFecha = $fechSalida[2].'-'.$fechSalida[1].'-'.$fechSalida[0];

        $contador = count($id_producto);    //CUANTO PRODUCTOS VIENEN PARA EL FOR
        
         //SCRIP QUE REGISTRA LA SALIDA EN HIST_SALIDA
         $stmt = Conexion::conectar()->prepare("INSERT INTO hist_salidas(id_tecnico, num_salida, fecha_salida, id_producto, cantidad, precio_venta, id_almacen, id_tipomov, id_usuario, ultusuario) VALUES (:id_tecnico, :num_salida, :fecha_salida, :id_producto, :cantidad, :precio_venta, :id_almacen, :id_tipomov, :id_usuario, :ultusuario)");
            
         for($i=0;$i<$contador;$i++) { 
            $stmt->bindParam(":id_tecnico", $nuevoTecnico, PDO::PARAM_INT);
            $stmt->bindParam(":num_salida", $numeroSalida, PDO::PARAM_STR);
            $stmt->bindParam(":fecha_salida", $newFecha, PDO::PARAM_STR);
            $stmt->bindParam(":id_producto", $id_producto[$i], PDO::PARAM_INT);
            $stmt->bindParam(":cantidad", $cantidad[$i], PDO::PARAM_INT);
            $stmt->bindParam(":precio_venta", $precio_venta[$i], PDO::PARAM_STR);
            $stmt->bindParam(":id_almacen", $id_almacen, PDO::PARAM_INT);
            $stmt->bindParam(":id_tipomov", $tipo_mov, PDO::PARAM_INT);
            $stmt->bindParam(":id_usuario", $ultusuario, PDO::PARAM_INT);
            $stmt->bindParam(":ultusuario", $ultusuario, PDO::PARAM_INT);
            $stmt->execute();
         }      //termina ciclo 1er for 
        
            if($stmt){
              //return "ok";
               for($i=0;$i<$contador;$i++) { 
                   
                 $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET cant=cant-(:cant), ultusuario=:ultusuario WHERE id_producto = :id_producto");
                  $stmt->bindParam(":id_producto", $id_producto[$i], PDO::PARAM_INT);
		          $stmt->bindParam(":cant", $cantidad[$i], PDO::PARAM_INT);
                  $stmt->bindParam(":ultusuario", $ultusuario, PDO::PARAM_INT);
                  $stmt->execute();
                }   //termina ciclo 2do for                    
                
                   if($stmt){
                       return "ok";
                    }else{
    			         return "error";
                    }
                
            }else{
          return "error";
          //return print_r(Conexion::conectar()->errorInfo());
          
		    }
        
		$stmt = null;

  }
    
/*=============================================
	REPORTE NOTA DE SALIDAS
=============================================*/	
static Public function MdlSalidaAlm($tabla, $campo, $valor){
     
     if($campo !=null){    
         
	   $sql="SELECT h.id_tecnico,t.nombre AS nombretecnico,h.num_salida, h.fecha_salida, h.cantidad,h.precio_venta,h.id_producto,
				a.descripcion,a.codigointerno, a.id_medida, m.medida, h.id_almacen,b.nombre AS nombrealma,h.id_tipomov,
				s.nombre_tipo,h.id_usuario,u.nombre AS nombreusuario 
				FROM $tabla h INNER JOIN tecnicos t ON h.id_tecnico=t.id	
				INNER JOIN productos a ON h.id_producto=a.id
				INNER JOIN almacenes b ON h.id_almacen=b.id
				INNER JOIN medidas m ON a.id_medida=m.id
				INNER JOIN tipomovsalida s ON h.id_tipomov=s.id
				INNER JOIN usuarios u ON h.id_usuario=u.id
				WHERE h.$campo=:$campo ORDER BY h.id_producto ASC";
	 
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
	VALIDA QUE NUMERO DE SALIDA NO SE REPITA
=============================================*/	    
 static Public function MdlValidarNumSalida($tabla, $campo, $valor){
     
     if($campo !=null){    
        $stmt=Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $campo=:$campo");
        
        $stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $stmt->fetch();
        
 	}else{

			return false;

	 }        
        
        $stmt->close();
        
        $stmt=null;
    }

/*=============================================
OBTENEMOS EL ULTIMO NUMERO CAPTURADO PARA ASIGNAR NUMERO DE SALIDA
=============================================*/	    
 static Public function MdlAsignarNumSalida($tabla, $campo, $valor){
     
     if($campo !=null){    

        $stmt=Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id DESC limit 1");

        $stmt->execute();
        
        return $stmt->fetch();
        
 	}else{

			return false;

	 }        
        
       
        $stmt=null;
    }

	
/*=============================================
	REPORTE DE SALIDAS DEL ALMACEN
=============================================*/			
static Public function MdlMostrarSalidas($tabla, $campo, $valor){
//where='1=1';

$idtec = (int) $valor;
$where=($idtec>0)? ' id_tecnico="'.$valor.'" AND' : "";

$where.=($tabla>0)? ' id_almacen="'.$tabla.'"' : "";

$where.=' GROUP by num_salida,fecha_salida,id_almacen,id_tecnico';

if($tabla>0 || $idtec>0){			//QUE ALMACEN MOSTRAR SALIDAS
	$sql="SELECT `id_tecnico`, t.nombre AS nombretecnico, `num_salida`, `fecha_salida`, SUM(`cantidad`) AS salio, `id_almacen`,a.nombre  AS almacen FROM `hist_salidas` INNER JOIN tecnicos t ON id_tecnico=t.id 
	INNER JOIN almacenes a ON id_almacen=a.id WHERE ".$where;
	
}else{                  // TODOS LOS ALMACENES

	$sql="SELECT `id_tecnico`, t.nombre AS nombretecnico, `num_salida`, `fecha_salida`, SUM(`cantidad`) AS salio, `id_almacen`,a.nombre  AS almacen FROM `hist_salidas` INNER JOIN tecnicos t ON id_tecnico=t.id 
	INNER JOIN almacenes a ON id_almacen=a.id
	GROUP by `num_salida`,`fecha_salida`,`id_almacen`,`id_tecnico`";
}

        $stmt=Conexion::conectar()->prepare($sql);
		
        //$stmt->bindParam(":".$campo, $valor, PDO::PARAM_STR);
        
        if($stmt->execute()){;
        
         return $stmt->fetchAll();
        
         //if ( $stmt->rowCount() > 0 ) { do something here }
        
 	  }else{

			return false;

	   }         
        
        
        $stmt=null;
    }	

		
/*

SELECT tb1.id, tb1.id_tecnico,t.nombre AS nombretecnico, tb1.fechasalida, h.cantidad,h.precio_venta,h.id_producto,
				a.descripcion,a.codigointerno, a.id_medida, m.medida, tb1.id_almacen,b.nombre AS nombrealma,tb1.id_tipomov,
				s.nombre_tipo,tb1.id_usuario,u.nombre AS nombreusuario 
				FROM tbl_salidas tb1
        INNER JOIN hist_salidas h ON tb1.id=h.id_salida
        INNER JOIN tecnicos t ON tb1.id_tecnico=t.id
				INNER JOIN productos a ON h.id_producto=a.id
				INNER JOIN almacenes b ON tb1.id_almacen=b.id
				INNER JOIN medidas m ON a.id_medida=m.id
				INNER JOIN tipomovsalida s ON tb1.id_tipomov=s.id
				INNER JOIN usuarios u ON tb1.id_usuario=u.id
				WHERE h.id_salida=12 ORDER BY h.id_producto ASC

 */
    

}       //fin de la clase