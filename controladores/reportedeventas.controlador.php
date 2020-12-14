<?php

class ControladorVentas{


/*=============================================
REPORTE DE VENTAS DE ALMACEN
============================================*/

static public function ctrReporteVentas($tabla, $item, $valor, $fechavta1, $fechavta2, $idNumCaja,$idNumCliente, $idNumProds, $idTipoMovs){

	$respuesta = ModeloVentas::MdlReporteVentas($tabla, $item, $valor,$fechavta1, $fechavta2, $idNumCaja, $idNumCliente, $idNumProds, $idTipoMovs);
    
	return $respuesta;
	
}  
		
/*=============================================
LISTAR VENTAS
============================================*/

static public function ctrListarVentas($tabla, $item, $valor, $fechavta1, $fechavta2, $idNumCaja,$idNumCliente, $idNumProds, $idTipoMovs){

	$respuesta = ModeloVentas::MdlListarVentas($tabla, $item, $valor,$fechavta1, $fechavta2, $idNumCaja, $idNumCliente, $idNumProds, $idTipoMovs);
    
	return $respuesta;
	
}  
				
/*=============================================
LISTAR VENTAS POR FAMILIA PARA EL GRAFICO
============================================*/

static public function ctrMostrarTotVentasFam($tablaVtaFam, $fechaInimes, $fechaFinmes, $limite){

	$respuesta = ModeloVentas::MdlMostrarTotVentasFam($tablaVtaFam, $fechaInimes, $fechaFinmes, $limite);
    
	return $respuesta;
	
}  

}	//fin de la clase
