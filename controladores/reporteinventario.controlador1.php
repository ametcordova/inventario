<?php

class ControladorInventario1{


/*=============================================
DATATABLE DE INVENTARIO
============================================*/

static public function ctrMostrarInventario1($tabla, $item, $valor){

	$respuesta = ModeloInventario1::MdlMostrarInventario1($tabla, $item, $valor);
    
	return $respuesta;
	
}  
		
/*=============================================
REPORTE DE INVENTARIO DE ALMACEN
============================================*/
/*
static public function ctrReporteInventario($tabla, $item, $valor){

	$respuesta = ModeloInventario::MdlReporteInventario($tabla, $item, $valor);
    
	return $respuesta;
	
}  
*/
/*=============================================
REPORTE DE INVENTARIO DE ALMACEN
============================================*/

static public function ctrProductosBajoStock($tabla, $item, $valor){

	$respuesta = ModeloInventario1::MdlReporteInventario1($tabla, $item, $valor);
    
	return $respuesta;
	
}  

				
	
}	//fin de la clase
