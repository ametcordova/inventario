<?php

class ControladorInventario{


	/*=============================================
	MOSTRAR REPORTE DE INVENTARIO POR PANTALLA
	============================================*/

	static public function ctrMostrarInventario($tabla, $item, $valor){


		$respuesta = ModeloInventario::MdlMostrarInventario($tabla, $item, $valor);

		return $respuesta;
	
	}  
		
	/*=============================================
	REPORTE DE INVENTARIO
	============================================*/

	static public function ctrReporteInventarioAlmacen($idalmacen){


		$respuesta = ModeloInventario::MdlReporteInventarioAlmacen($idalmacen);

		return $respuesta;
	
	}  
				
	/*=============================================
	REPORTE DE INVENTARIO POR TECNICO
	============================================*/

	static public function ctrReporteInventarioPorTecnico($tabla, $campo, $valor, $idalmacen){


		$respuesta = ModeloInventario::MdlReporteInventarioPorTecnico($tabla, $campo, $valor, $idalmacen);

		return $respuesta;
	
	}  
	


}	//fin de la clase
