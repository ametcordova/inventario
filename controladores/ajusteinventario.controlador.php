<?php

class ControladorAjusteInventario{

/*=============================================
GUARDAR REGISTROS POR AJUSTE DE INVENTARIO
=============================================*/
static public function ctrGuardarAjusteInv($tablaAjuste, $tabla,  $datos, $productos, $cantidades, $codigosinternos, $tipomov, $id_tipomov){

	$respuesta = ModeloAjusteInventario::mdlGuardarAjusteInv($tablaAjuste, $tabla, $datos, $productos, $cantidades, $codigosinternos, $tipomov, $id_tipomov);

		if($respuesta == "ok"){
			return true;
		}
}

/*=============================================
TRAER EL ÚLTIMO ID GUARDADO
=============================================*/
static public function ctrObtenerLastId($tabla, $item, $valor){

	return $respuesta = ModeloAjusteInventario::mdlObtenerLastId($tabla, $item, $valor);

}

/*=============================================
    LISTAR AJUSTE DE INVENTARIO
============================================*/
static public function ctrListarAjusteInv($item, $valor, $orden, $fechadev1, $fechadev2){

	$tabla = "ajusteinventario";

	$respuesta = ModeloAjusteInventario::mdlListarAjusteInv($tabla, $item, $valor, $orden, $fechadev1, $fechadev2);

	return $respuesta;

}    

/*=============================================
	REPORTE DE AJUSTE DE INVENTARIO
============================================*/
static public function ctrReporteAjusteInv($item, $valor){

	$tabla = "ajusteinventario";

	$respuesta = ModeloAjusteInventario::MdlReporteAjusteInv($tabla, $item, $valor);

	return $respuesta;
}  

/*=============================================
	MOSTRAR PRODUCTOS
=============================================*/
static public function ctrDatosProducto($item, $valor){

	$tabla = "productos";

	$respuesta = ModeloAjusteInventario::mdlDatosProducto($tabla, $item, $valor);

	return $respuesta;

}

/*=================MOSTRAR TIPO MOV DE SALIDA ================================ */
static public function ctrMostrarTipoMovs($tabla, $item, $valor){

	$respuesta = ModeloAjusteInventario::MdlMostrarTipoMovs($tabla, $item, $valor);
	return $respuesta;
}


}   //fin de la clase
?>