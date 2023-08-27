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

static public function ctrListarSeries($item, $valor, $orden, $fechadev1, $fechadev2, $filtroanual){

	$tabla = "contenedor_series";

	$respuesta = ModeloSeries::mdlListarSeries($tabla, $item, $valor, $orden, $fechadev1, $fechadev2, $filtroanual);

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
	
	/*=============================================
	MOSTRAR ONTS
	=============================================*/

	static public function ctrMostrarONT($item, $valor, $estado){

		$tabla = "productos";

		$respuesta = ModeloSeries::mdlMostrarONT($tabla, $item, $valor, $estado);

		return $respuesta;

	}

	/*=============================================
	VALIDAR DATOS 
	============================================*/

	static public function ctrvalidData($tabla, $campo1, $valor1, $campo2, $valor2, $campo3, $valor3){


		$respuesta = ModeloSeries::MdlValidData($tabla, $campo1, $valor1, $campo2, $valor2, $campo3, $valor3);

		return $respuesta;
	
	}  	

	/*=============================================
	MOSTRAR ONTS
	=============================================*/

	static public function ctrGuardarEditaSerie($tabla, $datos){


		$respuesta = ModeloSeries::mdlGuardarEditaSerie($tabla, $datos);

		return $respuesta;

	}
	
}	//fin de la clase	