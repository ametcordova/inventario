<?php

class ControladorEntradasAlmacen{

/*=============================================
LISTAR REGISTROS PARA EL DATATABLE
=============================================*/
static public function ctrListarEntradas($tabla, $fechadev1, $fechadev2){

	$respuesta = ModeloEntradasAlmacen::mdlListarEntradas($tabla, $fechadev1, $fechadev2);

	return $respuesta;

}  

/*=============================================
TRAER EL ÚLTIMO ID GUARDADO
=============================================*/
static public function ctrObtenerUltimoNumero($tabla, $campo){

	return $respuesta = ModeloEntradasAlmacen::mdlObtenerUltimoNumero($tabla, $campo);

}

/*=================MOSTRAR TIPO MOVs ================================ */
static public function ctrMostrarTipoMov($item, $valor){
    $tabla="tipomovimiento";
	$respuesta = ModeloEntradasAlmacen::MdlMostrarTipoMov($tabla, $item, $valor);
    return $respuesta;
}

/*=================MOSTRAR PRODUCTOS ================================ */
static public function ctrajaxProductos($tabla, $campo, $valor){
	$respuesta = ModeloEntradasAlmacen::MdlajaxProductos($tabla, $campo, $valor);
    return $respuesta;
}
/*============================================*/

static public function ctrConsultaExistenciaProd($tabla, $item, $valor){

	$respuesta = ModeloEntradasAlmacen::mdlConsultaExistenciaProd($tabla, $item, $valor);
	return $respuesta;
	
}  

/*=============================================
	GUARDA ENTRADA AL ALMACEN
=============================================*/
static public function ctrAltaEntradasAlmacen($tabla_almacen, $tabla, $datos){

	$respuesta = ModeloEntradasAlmacen::mdlAltaEntradasAlmacen($tabla_almacen, $tabla, $datos);
	return $respuesta;
	
}      

/*=============================================
REPORTE DE ENTRADAS
============================================*/
static public function ctrReporteEntradaAlmacen($item, $numeroid){

	$tabla="tbl_entradas";
	$tabla_hist="hist_entrada";

	$respuesta = ModeloEntradasAlmacen::mdlReporteEntradaAlmacen($tabla, $tabla_hist, $item, $numeroid);

	return $respuesta;
	
}  


}   //fin de la clase
?>