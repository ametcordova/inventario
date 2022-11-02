<?php

class ControladorEmpresa{


/*=============================================
	CREAR EMPRESA
=============================================*/

static public function ctrCrearEmpresa($tabla, $datos){

		$respuesta = ModeloEmpresa::mdlIngresarEmpresa($tabla, $datos);

}

/*=============================================
	UPDATE EMPRESA
=============================================*/

static public function ctrUpdateEmpresa($tabla, $datos){

	$respuesta = ModeloEmpresa::mdlUpdateEmpresa($tabla, $datos);

}

/*=============================================
MOSTRAR DATOS EMPRESA
============================================*/

static public function ctrTraerDatosEmpresa($item=NULL, $valor=NULL){

	$tabla = "empresa";

	$respuesta = ModeloEmpresa::mdlTraerDatosEmpresa($tabla, $item=NULL, $valor=NULL);

	return $respuesta;

}  
    
}   //fin de la clase
