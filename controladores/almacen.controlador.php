<?php

class ControladorAlmacen{


	/*=============================================
	REPORTE DE ENTRADAS
	============================================*/

	static public function ctrMostrarAlmacen($tabla, $item, $valor){


		$respuesta = ModeloAlmacen::MdlMostrarAlmacen($tabla, $item, $valor);

		return $respuesta;
	
	}  
		
	/*=============================================
	MOSTRAR ENTRADAS AL ALMACEN
	============================================*/

	static public function ctrMostrarEntradas($tabla, $item, $valor){


		$respuesta = ModeloAlmacen::MdlMostrarEntradas($tabla, $item, $valor);

		return $respuesta;
	
	}  
			
	
}	//fin de la clase
