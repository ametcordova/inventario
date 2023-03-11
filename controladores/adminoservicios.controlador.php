<?php
class ControladorOServicios{

/*=============================================
GUARDAR REGISTROS DE ORDENES DE SERVICIO
=============================================*/
static public function ctrGuardarOS($tabla, $datos, $productos, $cantidades){

	$respuesta = ModeloOServicios::mdlGuardarOS($tabla, $datos, $productos, $cantidades);

	if($respuesta=='ok'){
		 $tabla='hist_salidas';
		 $respuesta = ModeloOServicios::mdlActualizarTransito($tabla, $datos, $productos, $cantidades);
		return $respuesta;
	}else{
		return array('status' => 400);
	}

}

/*=============================================
ACTUALIZAR REGISTROS DE ORDENES DE SERVICIO
=============================================*/
static public function ctrActualizarOS($tabla, $datos){

	$respuesta = ModeloOServicios::mdlActualizarOS($tabla, $datos);

	if($respuesta=='ok'){
		json_output(json_build(200, 'ok', 'OS Actualizado con éxito'));
	}else{
		json_output(json_build(400, null, 'Registro NO guardado'));
	}

}

/*=============================================
ACTUALIZAR REGISTROS DE ORDENES DE SERVICIO
=============================================*/
static public function ctrActualizarImagen($tabla, $firma, $id){

	$respuesta = ModeloOServicios::mdlActualizarImagen($tabla, $firma, $id);

	if($respuesta=='ok'){
		json_output(json_build(200, 'ok', 'Imagen Actualizado con éxito'));
	}else{
		json_output(json_build(400, null, 'Registro NO guardado'));
	}

}

/*=============================================
    LISTAR SERIES
============================================*/

static public function ctrListarOServicios($item, $valor, $orden, $fechadev1, $fechadev2){

	$tabla = "tabla_os";

	$respuesta = ModeloOServicios::mdlListarOServicios($tabla, $item, $valor, $orden, $fechadev1, $fechadev2);

	return $respuesta;

}    
	
/*=============================================
REPORTE DE INVENTARIO POR TECNICO
============================================*/
static public function ctrReporteExistenciaPorTecnico($tabla, $valor, $idalmacen, $idtecnico, $orden){

	$respuesta = ModeloOServicios::MdlReporteExistenciaPorTecnico($tabla, $valor, $idalmacen, $idtecnico, $orden);

	return $respuesta;

}  

/*=============================================
REPORTE DE INVENTARIO POR TECNICO
============================================*/
static public function ctrObtenerOS($tabla, $campo, $valor){

	$respuesta = ModeloOServicios::mdlObtenerOS($tabla, $campo, $valor);

	return $respuesta;

}  

/*=============================================
MATERIAL CONSUMIDO EN LA(S) OS
============================================*/
static public function ctrObtenerMaterialOS($tabla, $campo, $valor){

	$respuesta = ModeloOServicios::mdlObtenerMaterialOS($tabla, $campo, $valor);

	return $respuesta;

} 

/*=============================================

============================================*/
static public function ctrGetDataOServicios($tabla, $campo, $valor, $status=null){

	$respuesta = ModeloOServicios::mdlGetDataOServicios($tabla, $campo, $valor, $status);

	return $respuesta;

}  

/*=============================================

============================================*/
static public function ctrGetDataNumOS($tabla, $campo, $numos){

	$respuesta = ModeloOServicios::mdlGetDataNumOS($tabla, $campo, $numos);

	return $respuesta;

}  

/*=============================================

============================================*/
static public function ctrTraerIdOs($tabla, $item, $valor){

	$respuesta = ModeloOServicios::mdlTraerIdOS($tabla, $item, $valor);

	return $respuesta;

}  

/*=============================================
GUARDAR ORDENES DE SERVICIO 
=============================================*/
static public function ctrGuardarAgregaOS($tabla, $idregos, $fechaagrega, $nvaobservaos, $ultusuario){

	$respuesta = ModeloOServicios::mdlGuardarAgregaOS($tabla, $idregos, $fechaagrega, $nvaobservaos, $ultusuario);

	if($respuesta=='ok'){
		return array('status' => 200);
	}else{
		return array('status' => 400);
	}

}

	/*=============================================
	MATERIAL CONSUMIDO EN LA(S) OS POR FACTURA
	============================================*/
	static public function ctrGetMaterialOsFactura($tabla, $campo, $valor){

		$respuesta = ModeloOServicios::mdlGetMaterialOsFactura($tabla, $campo, $valor);

		return $respuesta;

	} 
	/*=============================================*/

}	//fin de la clase	