<?php

class ControladorFacturas{
    
/*=============================================
	CREAR CAJA
=============================================*/

static public function ctrCrearFactura($tabla, $datos){

		$respuesta = ModeloFacturas::mdlCrearFactura($tabla, $datos);

}

/*=============================================
    MOSTRAR FACTURAS
============================================*/

static public function ctrMostrarFacturas($item, $valor, $orden, $tipo, $year){

		$tabla = "facturas";

		$respuesta = ModeloFacturas::mdlMostrarFacturas($tabla, $item, $valor, $orden, $tipo, $year);

		return $respuesta;
	
}    
    
/*=============================================
	EDITAR CAJA
=============================================*/

static public function ctrGuardarEditarFactura($tabla, $datos){

        return $respuesta = ModeloFacturas::mdlGuardarEditarFactura($tabla, $datos);

}

/*=============================================
FECHA DE PAGO DE FACTURA
=============================================*/

static public function ctrGuardarPagoFactura($tabla, $datos){

        return $respuesta = ModeloFacturas::mdlGuardarPagoFactura($tabla, $datos);

}

/*=============================================
	BORRAR CAJA
=============================================*/

static public function ctrBorrarFactura($item, $valor, $estado){
     
     $tabla = "facturas";
     $respuesta = ModeloFacturas::mdlBorrarFactura($tabla, $item, $valor, $estado);
}    
    


}   //fin de la clase

