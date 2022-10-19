<?php

class ControladorFacturas{
    
/*=============================================
	CREAR CAJA
=============================================*/

static public function ctrCrearFactura($tabla, $datos){

		$respuesta = ModeloFacturas::mdlCrearFactura($tabla, $datos);

		if($respuesta=='ok'){
			$tabla="clientes";
			$item="id";
			$valor=5;
			$operacion="resta";
			$respuesta = ModeloFacturas::mdlModificarSaldoDisp($tabla, $item, $valor, $datos, $operacion);
		}
		return $respuesta;

}

/*=============================================
    LISTAR FACTURAS
============================================*/

static public function ctrMostrarFacturas($item, $valor, $orden, $tipo, $year, $monthinicial, $monthfinal, $solopagadas){

		$tabla = "facturas";

		$respuesta = ModeloFacturas::mdlMostrarFacturas($tabla, $item, $valor, $orden, $tipo, $year, $monthinicial, $monthfinal, $solopagadas);

		return $respuesta;
	
}    

/*=============================================
    LISTAR FACTURAS
============================================*/

static public function ctrMostrarSaldoDisponible(){

	$tabla = "clientes";
	$valor=5;
	$item="id";

	$respuesta = ModeloFacturas::mdlMostrarSaldoDisponible($tabla, $item, $valor);

	return $respuesta;

}    

/*=============================================
	EDITAR CAJA
=============================================*/

static public function ctrGuardarEditarFactura($tabla, $datos){

	$respuesta = ModeloFacturas::mdlGuardarEditarFactura($tabla, $datos);

	if($respuesta=='ok'){
		$tabla="clientes";
		$item="id";
		$valor=5;

		$operacion="sumar";
		$respuesta = ModeloFacturas::mdlModificarSaldoDisp($tabla, $item, $valor, $datos, $operacion);

		$operacion="resta";
		$respuesta = ModeloFacturas::mdlModificarSaldoDisp($tabla, $item, $valor, $datos, $operacion);
	}

    return $respuesta;

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

static public function ctrBorrarFactura($item, $valor, $estado, $subtotal){
     
     $tabla = "facturas";

     $respuesta = ModeloFacturas::mdlBorrarFactura($tabla, $item, $valor, $estado);

	 if($respuesta=='ok'){
		$tabla="clientes";
		$item="id";
		$valor=5;

		$operacion="sumar";
		$respuesta = ModeloFacturas::mdlModificarSaldoDisp($tabla, $item, $valor, $subtotal, $operacion);

	}

	return $respuesta;

}    
    


}   //fin de la clase

