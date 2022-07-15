<?php

class ControladorCtrolDepositos{

/*=============================================
LISTAR REGISTROS PARA EL DATATABLE
=============================================*/
static public function ctrListarDepositos($tabla, $fechadev1, $fechadev2){
	$respuesta = ModeloCtrolDepositos::mdlListarDepositos($tabla, $fechadev1, $fechadev2);
	return $respuesta;
}  

/*================= MOSTRAR BENEFICIARIOS ================================ */
static public function ctrAjaxBeneficiario($tabla, $campo, $valor){
	$respuesta = ModeloCtrolDepositos::mdlAjaxBeneficiario($tabla, $campo, $valor);
    return $respuesta;
}

/*================= DATOS DEL BENEFICIARIO ================================ */
static public function ctrDatosBeneficiario($tabla, $campo, $valor){
	$respuesta = ModeloCtrolDepositos::mdlDatosBeneficiario($tabla, $campo, $valor);
    return $respuesta;
}

/*================= GUARDAR DATOS DEPOSITO ================================ */
static public function ctrGuardarDeposito($tabla, $datos){
	$respuesta = ModeloCtrolDepositos::mdlGuardarDeposito($tabla, $datos);
    return $respuesta;
}

/*================= GUARDAR DATOS DEPOSITO ================================ */
static public function ctrActualizarDeposito($tabla, $datos){
	$respuesta = ModeloCtrolDepositos::mdlActualizarDeposito($tabla, $datos);
    return $respuesta;
}

/*================= OBTENER DATOS DE BANCOS / EMP ================================ */
static public function ctrMostrarDestinatarios($item, $valor){
	$tabla="destinatarios";
	$respuesta = ModeloCtrolDepositos::mdlMostrarDestinatarios($tabla, $item, $valor);
    return $respuesta;
}

/*================= GUARDAR DATOS CUENTAHABIENTE================================ */
static public function ctrGuardarCuentaHabiente($tabla, $datos){
	$respuesta = ModeloCtrolDepositos::mdlGuardarCuentaHabiente($tabla, $datos);
    return $respuesta;
}

/*================= BORRAR DATOS DE DEPOSITO================================ */
static public function ctrBorrarDeposito($tabla, $idDep, $campo){
	$respuesta = ModeloCtrolDepositos::mdlBorrarDeposito($tabla, $idDep, $campo);
    return $respuesta;
}

/*================= DATOS DEL DEPOSITO ================================ */
static public function ctrDatosDeposito($tabla, $iddep, $campo){
	$respuesta = ModeloCtrolDepositos::mdlDatosDeposito($tabla, $iddep, $campo);
    return $respuesta;
}


}   //fin de la clase
?>