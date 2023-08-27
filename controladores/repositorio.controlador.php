<?php

class ControladorRepositorio{

/*=============================================
    LISTAR ARCHIVOS
============================================*/

static public function ctrListsFiles($item, $valor, $ispublic){

		$tabla = "repositorio";

		$respuesta = ModeloRepositorio::mdlListsFiles($tabla, $item, $valor, $ispublic);

		return $respuesta;
	
}    

/*=============================================

============================================*/
static public function ctrUpLoadFiles($descripcion, $nombrearchivo, $ruta, $is_public, $ultusuario){
	$tabla="repositorio";

	$is_public=$is_public==1?1:0;
	
	$respuesta = ModeloRepositorio::mdlUpLoadFiles($tabla, $descripcion, $nombrearchivo, $ruta, $is_public, $ultusuario);

	return $respuesta;

}  

/*=============================================
  ELIMINAR ARCHIVOS DEL REPOSITORIO
============================================*/
static public function ctrDelFileRep($tabla, $iddelete, $campo){

	//Borrado del archivo en el Directorio
	$getDataFile=ModeloRepositorio::mdlDelFileRep($tabla, $iddelete, $campo, $file=true);

	if($_SESSION['id']!==$getDataFile["user_id"]){
		$respuesta = array("status" =>400, "tipo"=>"No es posible borrar este registro. No eres propietario de este archivo");
		return $respuesta;
	}

	$namefile=$getDataFile["nombrearchivo"];
	$rootfile=$getDataFile["ruta"];

	$url = "..".$rootfile.$namefile;

	//$rutaactual=getcwd();
	//$respuesta = array("url" =>$url, 'ruta'=>$rutaactual);
	//return $respuesta;

	if (file_exists($url)) {
        unlink($url);
    }else{
		unlink($url);
		$respuesta = "error";
	  	//return $respuesta;
	}
	
	//Borrado del archivo en la tabla repositorio
	$respuesta = ModeloRepositorio::mdlDelFileRep($tabla, $iddelete, $campo, $file=false);

	return $respuesta;

}  

/*=============================================*/



};   //fin de la clase

