<?php

class ControladorFacturaIngreso{

/*=============================================
LISTAR REGISTROS PARA EL DATATABLE
=============================================*/
static public function ctrListarFacturas($tabla, $fechadev1, $fechadev2, $usuario, $todes){

	$respuesta = ModeloFacturaIngreso::mdlListarFacturas($tabla, $fechadev1, $fechadev2, $usuario, $todes);

	return $respuesta;

}  

/*=============================================
TRAER EL ÚLTIMO ID GUARDADO
=============================================*/
static public function ctrObtenerUltimoNumero($tabla, $campo){

	return $respuesta = ModeloFacturaIngreso::mdlObtenerUltimoNumero($tabla, $campo);

}

/*=================MOSTRAR DATOS DE RECEPTOR ================================ */
static public function ctrDatosReceptor($tabla, $campo, $valor){
	$respuesta = ModelofacturaIngreso::mdlDatosReceptor($tabla, $campo, $valor);
    return $respuesta;
}


/*=================MOSTRAR USO cfdi ================================ */
static public function ctrMostrarUsoCFDI($item, $valor){
    $tabla="c_usocfdi";
	$aplica=1;
	$respuesta = ModeloFacturaIngreso::mdlMostrarUsoCFDI($tabla, $item, $valor, $aplica);
    return $respuesta;
}


/*=================MOSTRAR PRODUCTOS ================================ */
static public function ctrgetClavesFact($tabla, $campo, $valor){
	$respuesta = ModeloFacturaIngreso::mdlgetClavesfact($tabla, $campo, $valor);
    return $respuesta;
}
/*====================================================================*/


/*=================GUARDAR DATOS DE FACTURA ================================ */
static public function ctrCrearFacturaIngreso($tabla, $facturaingreso){

	$respuesta = ModeloFacturaIngreso::mdlCrearFacturaIngreso($tabla, $facturaingreso);
	
	return $respuesta;
	
}  

/*=================GUARDAR DATOS DE FACTURA ================================ */
static public function ctrTimbrarFactura($tabla, $campo, $valor){

	$respuesta = ModeloFacturaIngreso::mdlTimbrarFactura($tabla, $campo, $valor);
	
	return $respuesta;
	
}  

/*=================GUARDAR DATOS DE FACTURA ================================ */
static public function ctrObtenerDatosFactura($tabla, $campo, $valor){

	$respuesta = ModeloFacturaIngreso::mdlObtenerDatosFactura($tabla, $campo, $valor);
	
	return $respuesta;
	
}  

/*================= GUARDAR DATOS DE FACTURA TIMBRADA ================================ */
static public function ctrObtenerDatosTimbre($tabla, $campo, $valor){

	$respuesta = ModeloFacturaIngreso::mdlObtenerDatosTimbre($tabla, $campo, $valor);
	
	return $respuesta;
	
}  

/*=============================================
MOSTRAR DATOS EMPRESA
============================================*/

static public function ctrGetDatosEmpresa($item, $valor){

	$tabla = "empresa";

	$respuesta = ModeloFacturaIngreso::mdlGetDatosEmpresa($tabla, $item, $valor);

	return $respuesta;

}  

/*=================MOSTRAR PRODUCTOS ================================ */
static public function ctrGetDatosFact($tabla, $campo, $valor){
	$respuesta = ModeloFacturaIngreso::mdlGetDatosFact($tabla, $campo, $valor);
    return $respuesta;
    
}
/*====================================================================*/

/*=================MOSTRAR FORMA DE PAGO ================================ */
static public function ctrGetFormaPago(){
	$tabla='c_formapago';
	$respuesta = ModeloFacturaIngreso::mdlGetFormaPago($tabla);
    return $respuesta;
}
/*====================================================================*/

/*=================MOSTRAR OBJETO DE IMPUESTOS ================================ */
static public function ctrGetObjetoImpuesto(){
	$tabla='c_objetoimp';
	$respuesta = ModeloFacturaIngreso::mdlGetObjetoImpuesto($tabla);
    return $respuesta;
}
/*====================================================================*/

/*=================MOSTRAR IMPUESTOS ================================ */
static public function ctrGetTasaImpuesto(){
	$tabla='c_impuesto';
	$respuesta = ModeloFacturaIngreso::mdlGetTasaImpuesto($tabla);
    return $respuesta;
}
/*====================================================================*/

/*=================GUARDAR DATOS DE COMPLEMENTO DE PAGO ================================ */
static public function ctrGuardarRep($tabla, $complementodepago){
	$respuesta = ModeloFacturaIngreso::mdlGuardarRep($tabla, $complementodepago);
	return $respuesta;
}  
/*=================FIN DE GUARDAR DATOS DE COMPLEMENTO DE PAGO ================================ */


}   //fin de la clase
?>