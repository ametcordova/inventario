<?php

class ControladorSalidasAlmacen{


/*=============================================
	GUARDA SALIDA DEL ALMACEN
=============================================*/
static public function ctrAltaSalidasAlmacen($tabla_almacen, $tabla, $datos){

	$respuesta = ModeloSalidasAlmacen::mdlAltaSalidasAlmacen($tabla_almacen, $tabla, $datos);

	return $respuesta;
	
}      


/*=============================================

============================================*/
static public function ctrConsultaExistenciaProd($tabla, $item, $valor){

		$respuesta = ModeloSalidasAlmacen::mdlConsultaExistenciaProd($tabla, $item, $valor);

		return $respuesta;
	
}  


/*=============================================
LISTAR REGISTROS PARA EL DATATABLE
=============================================*/
static public function ctrListarSalidas($tabla, $fechadev1, $fechadev2){

	$respuesta = ModeloSalidasAlmacen::mdlListarSalidas($tabla, $fechadev1, $fechadev2);

	return $respuesta;

}  

/*=============================================
  MOSTRAR PARA EDITAR SALIDA DEL ALMACEN
============================================*/
static public function ctrMostrarSalidasAlmacen($campo, $valor){

	$tabla="tbl_salidas";

	$respuesta = ModeloSalidasAlmacen::mdlMostrarSalidaAlmacen($tabla, $campo, $valor);

return $respuesta;

}  

/*=============================================
  ELIMINA REGISTRO(S) EN LA EDICION DE SALIDA
============================================*/
static public function ctrEditEliminarRegSA($tabla_almacen, $id_almacen, $key, $value, $id_salida){

	$tablahist="hist_salidas";
	$tablakardex="kardex_".$tabla_almacen;
	$nombremes_actual = strtolower(date('F'));

	$respuesta = ModeloSalidasAlmacen::mdEditEliminarRegSA($tabla_almacen, $id_almacen, $tablahist, $tablakardex, $key, $value, $id_salida, $nombremes_actual);

	return $respuesta;

}  

/*=============================================
	GUARDA EDICION SALIDA DEL ALMACEN
=============================================*/
static public function ctrEditAdicionarRegSA($tabla_almacen, $datos){
	$tablahist="hist_salidas";
	$tablakardex="kardex_".$tabla_almacen;
	$nombremes_actual = strtolower(date('F'));

	$respuesta = ModeloSalidasAlmacen::mdlEditAdicionarRegSA($tabla_almacen, $tablahist, $tablakardex, $nombremes_actual, $datos);

	return $respuesta;
	
}      

/*=============================================
  AUMENTA CANT EN LA EDICION DE SALIDA DE ALM
============================================*/
static public function ctrEditAumentarRegSA($tabla_almacen, $id_almacen, $key, $nuevovalor){

	$tablahist="hist_salidas";
	$tablakardex="kardex_".$tabla_almacen;
	$nombremes_actual = strtolower(date('F'));

	$respuesta = ModeloSalidasAlmacen::mdEditAumentarRegSA($tabla_almacen, $id_almacen, $tablahist, $tablakardex, $key, $nuevovalor, $nombremes_actual);

	return $respuesta;

}  

/*=============================================
  DISMINUYE CANT EN LA EDICION DE SALIDA DE ALM
============================================*/
static public function ctrEditDisminuirRegSA($tabla_almacen, $id_almacen, $key, $nuevovalor){

	$tablahist="hist_salidas";
	$tablakardex="kardex_".$tabla_almacen;
	$nombremes_actual = strtolower(date('F'));

	$respuesta = ModeloSalidasAlmacen::mdEditDisminuirRegSA($tabla_almacen, $id_almacen, $tablahist, $tablakardex, $key, $nuevovalor, $nombremes_actual);

	return $respuesta;

}  

/*=============================================
  TRAE LOS DATOS PARA ELIMINAR SALIDA DEL ALMACEN
============================================*/
static public function ctrBorrarSalidaAlmacen($tabla_hist, $idaborrar, $campo){

	$respuesta = ModeloSalidasAlmacen::mdlBorrarSalidaAlmacen($tabla_hist, $idaborrar, $campo);

	return $respuesta;

}  

/*=============================================
  GUARDA CANCELACION DE SALIDA DE ALMACEN
============================================*/
static public function ctrGuardaCancelacion($tabla_cancela, $idnumcancela, $datos, $idusuario){

	$respuesta = ModeloSalidasAlmacen::mdlGuardaCancelacion($tabla_cancela, $idnumcancela, $datos, $idusuario);

	return $respuesta;

}  

/*=============================================
  ACTUALIZA EXIST POR CANCELACION DE SALIDA
============================================*/
static public function ctrActualizaExistencia($tabla_almacen, $tabla_kardex, $nombremes_actual, $datos){

	$respuesta = ModeloSalidasAlmacen::mdlActualizaExistencia($tabla_almacen, $tabla_kardex, $nombremes_actual, $datos);

	return $respuesta;

}  

/*=============================================
  ELIMINAR DATOS EN EL HIST_SALIDAS
============================================*/
static public function ctrEliminarDatos($tabla, $idaborrar, $campo){

	$respuesta = ModeloSalidasAlmacen::mdlEliminarDatos($tabla, $idaborrar, $campo);

	return $respuesta;

}  

/*=============================================
  REPORTE SALIDA DEL ALMACEN
============================================*/
static public function ctrPrintSalidaAlmacen($campo, $valor){

	$tabla="tbl_salidas";

	$respuesta = ModeloSalidasAlmacen::mdlPrintSalidaAlmacen($tabla, $campo, $valor);

	return $respuesta;

}  

/*=============================================
TRAER EL ÚLTIMO ID GUARDADO
=============================================*/
static public function ctrObtenerUltimoId($tabla, $campo){

	return $respuesta = ModeloSalidasAlmacen::mdlObtenerUltimoId($tabla, $campo);

}


}   //fin de la clase
?>