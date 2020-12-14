<?php

class ControladorCajas{

/*=============================================
REPORTE 
============================================*/

static public function ctrReporteCortex($tabla, $campo, $valor, $fechaventa){

	$respuesta = ModeloCajas::MdlReporteCortex($tabla, $campo, $valor, $fechaventa);
    
return $respuesta;
	
}  	
	
/*=============================================
	MOSTRAR INGRESO CAJA
=============================================*/
static public function ctrVerIngresoCaja($tabla, $item, $valor1, $valor2){

	return $respuesta = ModeloCajas::mdlVerIngresoCaja($tabla, $item, $valor1, $valor2);

}

/*=============================================
	INGRESO CAJA
=============================================*/

static public function ctrIngresoCaja($tabla, $item, $valor1, $valor2, $valor3){

		return $respuesta = ModeloCajas::mdlIngresoCaja($tabla, $item, $valor1, $valor2, $valor3);

}

/*=============================================
	EGRESO CAJA
=============================================*/

static public function ctrEgresoCaja($tabla, $item, $valor1, $valor2, $valor3){

	return $respuesta = ModeloCajas::mdlEgresoCaja($tabla, $item, $valor1, $valor2, $valor3);

}
/*=============================================
	ACTIVAR CAJA
=============================================*/

static public function ctrActiveCaja($tabla, $item, $valor, $cajachica){

		return $respuesta = ModeloCajas::mdlActiveCaja($tabla, $item, $valor, $cajachica);

}

    
/*=============================================
	CREAR CAJA
=============================================*/

static public function ctrCrearCaja($tabla, $datos){

		$respuesta = ModeloCajas::mdlIngresarCaja($tabla, $datos);

}

/*=============================================
    MOSTRAR CAJA
============================================*/
static public function ctrMostrarCajas($item, $valor){

		$tabla = "cajas";

		$respuesta = ModeloCajas::mdlMostrarCajas($tabla, $item, $valor);

		return $respuesta;
	
}    
    
/*=============================================
	EDITAR CAJA
=============================================*/

static public function ctrEditarCaja($tabla, $datos){

        return $respuesta = ModeloCajas::mdlEditarCaja($tabla, $datos);

}

/*=============================================
	BORRAR CAJA
=============================================*/
static public function ctrBorrarCaja($item, $valor, $estado){
     
     $tabla = "cajas";
     $respuesta = ModeloCajas::mdlBorrarCaja($tabla, $item, $valor, $estado);
}    

/*=============================================
    LISTAR CORTES DE VENTAS
============================================*/
static public function ctrListarCorteVentas($item, $valor, $orden, $fechadev1, $fechadev2){

	$tabla = "cortes";

	$respuesta = ModeloCajas::mdlListarCorteVentas($tabla, $item, $valor, $orden, $fechadev1, $fechadev2);

	return $respuesta;

}    

    
}   //fin de la clase
