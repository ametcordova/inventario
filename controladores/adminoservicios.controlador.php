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


}	//fin de la clase	