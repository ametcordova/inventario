<?php

class ControladorOsvilla{


    
/*=============================================
GUARDAR OS
=============================================*/

static public function ctrGuardarOsvilla($tabla, $datos){

		$respuesta = ModeloOsvilla::mdlGuardarOsvilla($tabla, $datos);

}

/*=============================================
EDITAR OS
=============================================*/

static public function ctrEditarOsvilla($tabla, $datos){

		$respuesta = ModeloOsvilla::mdlEditarOsvilla($tabla, $datos);

}

/*=============================================
MOSTRAR OS
=============================================*/

static public function ctrMostrarOsvilla($tabla, $item, $valor){

	$respuesta = ModeloOsvilla::mdlMostrarOsvilla($tabla, $item, $valor);
    
    return $respuesta;
}
    
/*=============================================
ELIMINAR OS
=============================================*/

static public function ctrEliminarOsvilla($tabla, $item, $valor){

	$respuesta = ModeloOsvilla::mdlEliminarrOsvilla($tabla, $item, $valor);
    
    return $respuesta;
}


/*=============================================
    LISTAR OS VILLA
============================================*/

	static public function ctrListarOsvilla($item, $valorFechaIni, $valorFechaFin, $valor){

		$tabla = "osvilla";

		$respuesta = ModeloOsvilla::mdlListarOsvilla($tabla, $item, $valorFechaIni, $valorFechaFin, $valor);

		return $respuesta;
	
	}    
    

    
}   //fin de la clase
