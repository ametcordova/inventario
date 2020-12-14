<?php
class ControladorDevolucion{

	/*=============================================
	GUARDAR DEVOLUCION MCIA DE TECNICOS AL ALMACEN
	=============================================*/

	static public function ctrGuardarDevolucionTecnico($tablaDev, $tabla,  $datos, $productos, $cantidades){

				
				$respuesta = ModeloDevolucion::mdlGuardarDevolucionTecnico($tablaDev, $tabla, $datos, $productos, $cantidades );
                
				if($respuesta == "ok"){
                    
					return true;
					
                }

	}
    
/*=============================================
    LISTAR DEVOLUCIONES DE TECNICOS
============================================*/

static public function ctrListarDevTec($item, $valor, $orden, $fechadev1, $fechadev2){

	$tabla = "devolucion_tecnicos";

	$respuesta = ModeloDevolucion::mdlListarDevTec($tabla, $item, $valor, $orden, $fechadev1, $fechadev2);

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