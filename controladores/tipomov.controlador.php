<?php

class ControladorMovto{
    
/*=============================================
	CREAR CAJA
=============================================*/

static public function ctrCrearMovto($tabla, $datos){

		$respuesta = ModeloMovto::mdlCrearMovto($tabla, $datos);

}

/*=============================================
    MOSTRAR CAJA
============================================*/

static public function ctrMostrarMovto($item, $valor, $orden){

		$tabla = "tipomovimiento";

		$respuesta = ModeloMovto::mdlMostrarMovto($tabla, $item, $valor, $orden);

		return $respuesta;
	
}    

/*=================MOSTRAR TIPO MOV DE SALIDA ================================ */
static public function ctrMostrarTipoMov($tabla, $item, $valor){
	$respuesta = ModeloMovto::MdlMostrarTipoMov($tabla, $item, $valor);
	return $respuesta;
}
    
/*=============================================
	EDITAR CAJA
=============================================*/

static public function ctrEditarMovto($tabla, $datos){

        return $respuesta = ModeloMovto::mdlEditarMovto($tabla, $datos);

}

/*=============================================
	BORRAR CAJA
=============================================*/

static public function ctrBorrarMovto($item, $valor, $estado){
     
     $tabla = "tipomovimiento";
     $respuesta = ModeloMovto::mdlBorrarMovto($tabla, $item, $valor, $estado);
}    
    


}   //fin de la clase
