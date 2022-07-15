<?php

class ControladorFacturas{
    
/*=============================================
	CREAR CAJA
=============================================*/

static public function ctrCrearFactura($tabla, $datos){

		$respuesta = ModeloFacturas::mdlCrearFactura($tabla, $datos);

}

/*=============================================
    LISTAR FACTURAS
============================================*/

static public function ctrMostrarFacturas($item, $valor, $orden, $tipo, $year, $month){

		$tabla = "facturas";


		$respuesta = ModeloFacturas::mdlMostrarFacturas($tabla, $item, $valor, $orden, $tipo, $year, $month);

		return $respuesta;
	
}    
    
/*=============================================
	EDITAR CAJA
=============================================*/

static public function ctrGuardarEditarFactura($tabla, $datos){

        return $respuesta = ModeloFacturas::mdlGuardarEditarFactura($tabla, $datos);

}

/*=============================================
FECHA Y COMPLEMENTO DE PAGO DE FACTURA
=============================================*/

static public function ctrGuardarPagoFactura($tabla, $datos){

        return $respuesta = ModeloFacturas::mdlGuardarPagoFactura($tabla, $datos);

}

/*=============================================
FECHA DE PAGO DE FACTURA
=============================================*/

static public function ctrGuardarFechaPagoFactura($tabla, $datos){

	return $respuesta = ModeloFacturas::mdlGuardarFechaPagoFactura($tabla, $datos);

}

/*=============================================
	BORRAR CAJA
=============================================*/

static public function ctrBorrarFactura($item, $valor, $estado){
     
     $tabla = "facturas";
     $respuesta = ModeloFacturas::mdlBorrarFactura($tabla, $item, $valor, $estado);
}    
    


}   //fin de la clase

