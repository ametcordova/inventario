<?php

class ControladorEmpresas{

/*=============================================
LISTAR REGISTROS PARA EL DATATABLE
=============================================*/
static public function ctrListarEmpresas($tabla, $usuario, $todes, $status){

	$respuesta = ModeloEmpresas::mdlListarEmpresas($tabla, $usuario, $todes, $status);

	return $respuesta;

}  




}   //fin de la clase
?>