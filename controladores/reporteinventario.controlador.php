<?php

class ControladorInventario{


	/*=============================================
	REPORTE DE INVENTARIO
	============================================*/

	static public function ctrMostrarInventario($tabla, $item, $valor){


		$respuesta = ModeloInventario::MdlMostrarInventario($tabla, $item, $valor);

		return $respuesta;
	
	}  
		
				
	
}	//fin de la clase
