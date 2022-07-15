<?php
date_default_timezone_set('America/Mexico_City');
require_once "conexion.php";

class ModeloRepositorio{

/*=============================================
	MOSTRAR FACTURAS
=============================================*/
static public function mdlListsFiles($tabla, $item, $valor, $orden, $ispublic){
try{

    //$where='1=1';
    $where=' repo.'.$item.'="'.$valor.'"';
    $where.=' OR repo.is_public="'.$ispublic.'"';

	$stmt = Conexion::conectar()->prepare("SELECT repo.*, usu.usuario FROM $tabla repo 
                                          INNER JOIN usuarios usu ON repo.user_id=usu.id 
                                          WHERE ".$where);
    
    $stmt->bindParam(":".$item, $valor, PDO::PARAM_INT);

	$stmt -> execute();

	return $stmt -> fetchAll();

} catch (Exception $e) {
	echo "Failed: " . $e->getMessage();
}        
	$stmt = null;
}

/*===============================================================================
//SCRIPT PARA SUBIR ARCHIVOS QUE CORRESPONDA A LA ENTRADA AL ALMACEN
===============================================================================*/
static public function mdlUpLoadFiles($tabla, $descripcion, $nombrearchivo, $ruta, $is_public, $ultusuario){
	try {      
            
            $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombrearchivo, descripcion, is_public,  ruta, user_id, ultusuario) VALUES ( :nombrearchivo, :descripcion, :is_public, :ruta, :user_id, :ultusuario)");
                
            $stmt->bindParam(":nombrearchivo",  $nombrearchivo, PDO::PARAM_STR);
            $stmt->bindParam(":descripcion",	$descripcion, PDO::PARAM_STR);
            $stmt->bindParam(":is_public",     	$is_public, PDO::PARAM_INT);
            $stmt->bindParam(":ruta",       	$ruta, PDO::PARAM_STR);
            $stmt->bindParam(":user_id",     	$ultusuario, PDO::PARAM_INT);
            $stmt->bindParam(":ultusuario",     $ultusuario, PDO::PARAM_INT);
            $stmt->execute();

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
ELIMINAR ARCHIVO
=============================================*/
static public function mdlDelFileRep($tabla, $iddelete, $campo, $file){
    try {      
        
        if($file){

            $sql="SELECT * FROM $tabla WHERE $campo=:$campo";
            $stmt=Conexion::conectar()->prepare($sql);
            $stmt->bindParam(":".$campo, $iddelete, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt -> fetch();
            
        }else{

            $sql="DELETE FROM $tabla WHERE $campo=:$campo";
            $stmt=Conexion::conectar()->prepare($sql);
            $stmt->bindParam(":".$campo, $iddelete, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt) {
                return "ok";
            }else{
                $respuesta = array("error" =>'Eliminacion');
                return $respuesta;
            }
        }           
        $stmt = null;

    } catch (Exception $e) {
            echo "Failed: " . $e->getMessage();
    }
   
}
/********************************************************************/

/*=============================================
CAMBIR DE STATUS A ARCHIVO
=============================================*/
static public function mdlChangeStatFile($tabla, $dataidfile, $campo, $dataestado){
    try {      
        
            $sql="UPDATE $tabla SET is_public=:is_public WHERE $campo=:$campo";
            $stmt=Conexion::conectar()->prepare($sql);
            $stmt->bindParam(":".$campo, $dataidfile, PDO::PARAM_INT);
            $stmt->bindParam(":is_public", $dataestado, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt) {
                $respuesta = array("status" =>200, "tipo"=>'Archivo ha cambiado de status');
                return $respuesta;
            }else{
                $respuesta = array("error" =>'Eliminacion');
                return $respuesta;
            }

        $stmt = null;

    } catch (Exception $e) {
            echo "Failed: " . $e->getMessage();
    }
   
}
/********************************************************************/

} //fin de la clase

