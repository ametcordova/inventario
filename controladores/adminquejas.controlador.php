<?php

class ControladorAdminQueja{


/*================= GUARDAR QUEJA ================================ */
static public function ctrGuardarQueja($tabla, $datos){
	$respuesta = ModeloAdminQueja::mdlGuardarQueja($tabla, $datos);
    return $respuesta;
}

/*================= ACTUALIZAR QUEJA ================================ */
static public function ctrActualizarQueja($tabla, $datos){
	$respuesta = ModeloAdminQueja::mdlActualizarQueja($tabla, $datos);
    return $respuesta;
}

/*=============================================
    LISTAR QUEJAS PARA EL DATATABLE
============================================*/

static public function ctrListarQuejas($tabla, $fecha1, $fecha2){

	$respuesta = ModeloAdminQueja::mdlListarQuejas($tabla, $fecha1, $fecha2);

	return $respuesta;

}    

/*================= DATOS DEL DEPOSITO ================================ */
static public function ctrVerQueja($tabla, $id, $campo){
	$respuesta = ModeloAdminQueja::mdlVerQueja($tabla, $id, $campo);
    return $respuesta;
}

/*================= BORRAR REGISTRO DE LA QUEJA================================ */
static public function ctrBorrarQueja($tabla, $id, $campo){
	$respuesta = ModeloAdminQueja::mdlBorrarQueja($tabla, $id, $campo);
    return $respuesta;
}

}   //fin de la clase
?>