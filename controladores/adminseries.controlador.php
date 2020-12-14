<?php
class ControladorSeries{

	/*=============================================
	GUARDAR SERIES
	=============================================*/

	static public function ctrGuardarNumeroSeries($tabla, $id_almacen, $numerodocto, $datos, $id_usuario, $contador){

				
		$respuesta = ModeloSeries::mdlGuardarNumeroSeries($tabla, $id_almacen, $numerodocto, $datos, $id_usuario, $contador);
                
		if($respuesta == "ok"){
                    
			return true;
				
        }

	}
    
/*=============================================
    LISTAR SERIES
============================================*/

static public function ctrListarSeries($item, $valor, $orden, $fechadev1, $fechadev2){

	$tabla = "contenedor_series";

	$respuesta = ModeloSeries::mdlListarSeries($tabla, $item, $valor, $orden, $fechadev1, $fechadev2);

	return $respuesta;

}    
	
	
	
	/*=============================================
	REPORTE NOTA DE SALIDAS
	============================================*/

	static public function ctrSalidaAlm($item, $valor){

		$tabla = "hist_salidas";

		$respuesta = ModeloSalidas::MdlSalidaAlm($tabla, $item, $valor);

		return $respuesta;
	
	}  	
	
	/*=============================================
	MOSTRAR SALIDAS AL ALMACEN
	============================================*/

	static public function ctrMostrarSalidas($tabla, $item, $valor){


		$respuesta = ModeloSalidas::MdlMostrarSalidas($tabla, $item, $valor);

		return $respuesta;
	
	}  	
	
	
}	//fin de la clase	