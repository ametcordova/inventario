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

/*=============================================
  MOSTRAR ENTRADAS AL ALMACEN PARA EDITAR 
============================================*/
static public function ctrMostrarEntradasAlmacen($campo, $valor){

	$tabla="tbl_entradas";

	$respuesta = ModeloEntradasAlmacen::mdlMostrarEntradasAlmacen($tabla, $campo, $valor);

return $respuesta;

}  

/*=============================================
  ELIMINA REGISTRO(S) EN LA EDICION DE ENTRADA
============================================*/
static public function ctrEditEliminarRegEA($tabla_almacen, $id_almacen, $key, $value, $id_entrada){

	$tablahist="hist_entrada";
	$tablakardex="kardex_".$tabla_almacen;
	$nombremes_actual = strtolower(date('F'));

	$respuesta = ModeloEntradasAlmacen::mdlEditEliminarRegEA($tabla_almacen, $id_almacen, $tablahist, $tablakardex, $key, $value, $id_entrada, $nombremes_actual);

	return $respuesta;

}  

/*========================================================
GUARDA REGISTRO NUEVO EN LA EDICION DE ENTRADA AL ALMACEN
=========================================================*/
static public function ctrEditAdicionarRegEA($tabla_almacen, $datos){
	$tablahist="hist_entrada";
	$tablakardex="kardex_".$tabla_almacen;
	$nombremes_actual = strtolower(date('F'));

	$respuesta = ModeloEntradasAlmacen::mdlEditAdicionarRegEA($tabla_almacen, $tablahist, $tablakardex, $nombremes_actual, $datos);

	return $respuesta;
	
}      

/*=============================================
  AUMENTA CANT EN LA EDICION DE SALIDA DE ALM
============================================*/
static public function ctrEditAumentarRegEA($tabla_almacen, $id_almacen, $key, $nuevovalor){

	$tablahist="hist_entrada";
	$tablakardex="kardex_".$tabla_almacen;
	$nombremes_actual = strtolower(date('F'));

	$respuesta = ModeloEntradasAlmacen::mdlEditAumentarRegEA($tabla_almacen, $id_almacen, $tablahist, $tablakardex, $key, $nuevovalor, $nombremes_actual);

	return $respuesta;

}  

/*=============================================
  DISMINUYE CANT EN LA EDICION DE SALIDA DE ALM
============================================*/
static public function ctrEditDisminuirRegEA($tabla_almacen, $id_almacen, $key, $nuevovalor){

	$tablahist="hist_entrada";
	$tablakardex="kardex_".$tabla_almacen;
	$nombremes_actual = strtolower(date('F'));

	$respuesta = ModeloEntradasAlmacen::mdlEditDisminuirRegEA($tabla_almacen, $id_almacen, $tablahist, $tablakardex, $key, $nuevovalor, $nombremes_actual);

	return $respuesta;

} 

/*=============================================
  TRAE LOS DATOS PARA ELIMINAR ENTRADA DEL ALMACEN
============================================*/
static public function ctrBorrarEntradaAlmacen($tabla_hist, $idaborrar, $campo){

	$respuesta = ModeloEntradasAlmacen::mdlBorrarEntradaAlmacen($tabla_hist, $idaborrar, $campo);

	return $respuesta;

}  

/*=============================================
  GUARDA CANCELACION DE ENTRADA DE ALMACEN
============================================*/
static public function ctrGuardaCancelacion($tabla_cancela, $idnumcancela, $datos, $idusuario){

	$respuesta = ModeloEntradasAlmacen::mdlGuardaCancelacion($tabla_cancela, $idnumcancela, $datos, $idusuario);

	return $respuesta;

}  

/*=============================================
  ACTUALIZA EXIST POR CANCELACION DE ENTRADA
============================================*/
static public function ctrActualizaExistencia($tabla_almacen, $tabla_kardex, $nombremes_actual, $datos){

	$respuesta = ModeloEntradasAlmacen::mdlActualizaExistencia($tabla_almacen, $tabla_kardex, $nombremes_actual, $datos);

	return $respuesta;

}  

/*=============================================
  ELIMINAR DATOS EN EL HIST_ENTRADA
============================================*/
static public function ctrEliminarDatos($tabla, $idaborrar, $campo){

	$respuesta = ModeloEntradasAlmacen::mdlEliminarDatos($tabla, $idaborrar, $campo);

	return $respuesta;

}  

}   //fin de la clase
?>