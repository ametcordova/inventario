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






}   //fin de la clase
?>