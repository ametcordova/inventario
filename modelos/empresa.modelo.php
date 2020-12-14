<?php
date_default_timezone_set('America/Mexico_City');
require_once dirname( __DIR__ ).'/config/conexion.php';
class ModeloEmpresa{

/*=============================================
	CREAR EMPRESA
=============================================*/
static public function mdlIngresarEmpresa($tabla, $datos){
 try {
        
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(razonsocial, rfc, slogan, direccion, colonia, codpostal, ciudad, estado,telempresa, mailempresa, contacto, telcontacto, mailcontacto, iva, imagen, impresora, msjpieticket, mensajeticket, rutarespaldo, namedatabase, ultusuario)
		VALUES (:razonsocial, :rfc, :slogan, :direccion, :colonia, :codpostal, :ciudad, :estado, :telempresa, :mailempresa, :contacto,:telcontacto, :mailcontacto, :iva, :imagen, :impresora, :msjpieticket, :mensajeticket, :rutarespaldo, :namedatabase, :ultusuario)");

		$stmt->bindParam(":razonsocial", $datos["razonsocial"], PDO::PARAM_STR);
		$stmt->bindParam(":rfc", $datos["rfc"], PDO::PARAM_STR);
		$stmt->bindParam(":slogan", $datos["slogan"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":colonia", $datos["colonia"], PDO::PARAM_STR);
		$stmt->bindParam(":codpostal", $datos["codpostal"], PDO::PARAM_STR);
		$stmt->bindParam(":ciudad", $datos["ciudad"], PDO::PARAM_STR);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
		$stmt->bindParam(":telempresa", $datos["telempresa"], PDO::PARAM_STR);
		$stmt->bindParam(":mailempresa", $datos["mailempresa"], PDO::PARAM_STR);
		$stmt->bindParam(":contacto", $datos["contacto"], PDO::PARAM_STR);
		$stmt->bindParam(":telcontacto",$datos["telcontacto"], PDO::PARAM_STR);
		$stmt->bindParam(":mailcontacto", $datos["mailcontacto"], PDO::PARAM_STR);
		$stmt->bindParam(":iva", $datos["iva"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":impresora", $datos["impresora"], PDO::PARAM_STR);
		$stmt->bindParam(":msjpieticket", $datos["msjpieticket"], PDO::PARAM_STR);
		$stmt->bindParam(":mensajeticket", $datos["mensajeticket"], PDO::PARAM_STR);
		$stmt->bindParam(":rutarespaldo", $datos["rutarespaldo"], PDO::PARAM_STR);
		$stmt->bindParam(":namedatabase", $datos["namedatabase"], PDO::PARAM_STR);
		$stmt->bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);
		//$stmt->bindParam(":imagen", $imagen, PDO::PARAM_STR);
		
		if($stmt->execute()){
            
			 return "ok";
            
        }else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

 } catch (Exception $e) {
  echo "Failed: " . $e->getMessage();
 }
        
}

/*=============================================
	UPDATE EMPRESA
=============================================*/
static public function mdlUpdateEmpresa($tabla, $datos){
	try {
		   
		   $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET razonsocial=:razonsocial, rfc=:rfc, slogan=:slogan, direccion=:direccion, colonia=:colonia, codpostal=:codpostal, ciudad=:ciudad, estado=:estado, telempresa=:telempresa, mailempresa=:mailempresa, contacto=:contacto, telcontacto=:telcontacto, mailcontacto=:mailcontacto, iva=:iva, imagen=:imagen, impresora=:impresora, msjpieticket=:msjpieticket, mensajeticket=:mensajeticket, rutarespaldo=:rutarespaldo, namedatabase=:namedatabase, ultusuario=:ultusuario");
   
		   $stmt->bindParam(":razonsocial", $datos["razonsocial"], PDO::PARAM_STR);
		   $stmt->bindParam(":rfc", $datos["rfc"], PDO::PARAM_STR);
		   $stmt->bindParam(":slogan", $datos["slogan"], PDO::PARAM_STR);
		   $stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		   $stmt->bindParam(":colonia", $datos["colonia"], PDO::PARAM_STR);
		   $stmt->bindParam(":codpostal", $datos["codpostal"], PDO::PARAM_STR);
		   $stmt->bindParam(":ciudad", $datos["ciudad"], PDO::PARAM_STR);
		   $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
		   $stmt->bindParam(":telempresa", $datos["telempresa"], PDO::PARAM_STR);
		   $stmt->bindParam(":mailempresa", $datos["mailempresa"], PDO::PARAM_STR);
		   $stmt->bindParam(":contacto", $datos["contacto"], PDO::PARAM_STR);
		   $stmt->bindParam(":telcontacto",$datos["telcontacto"], PDO::PARAM_STR);
		   $stmt->bindParam(":mailcontacto", $datos["mailcontacto"], PDO::PARAM_STR);
		   $stmt->bindParam(":iva", $datos["iva"], PDO::PARAM_STR);
		   $stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		   $stmt->bindParam(":impresora", $datos["impresora"], PDO::PARAM_STR);
		   $stmt->bindParam(":msjpieticket", $datos["msjpieticket"], PDO::PARAM_STR);
		   $stmt->bindParam(":mensajeticket", $datos["mensajeticket"], PDO::PARAM_STR);
		   $stmt->bindParam(":rutarespaldo", $datos["rutarespaldo"], PDO::PARAM_STR);
		   $stmt->bindParam(":namedatabase", $datos["namedatabase"], PDO::PARAM_STR);
		   $stmt->bindParam(":ultusuario", $datos["ultusuario"], PDO::PARAM_INT);
		   
		   if($stmt->execute()){
			   
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
MOSTRAR EMPRESA
=============================================*/
static public function mdlTraerDatosEmpresa($tabla){
	try{
	
		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
	
		$stmt -> execute();

		return $stmt -> fetch();
	
	}catch(Exception $e) {
		return $e->getMessage() ;
	}

	$stmt = null;
	
}


	
} //fin de la clase

