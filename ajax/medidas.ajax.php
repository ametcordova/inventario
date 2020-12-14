<?php

require_once "../controladores/medidas.controlador.php";
require_once "../modelos/medidas.modelo.php";


class AjaxMedidas{

	/*=============================================
	EDITAR UNIDAD DE MEDIDAS
	=============================================*/	

	public $idMedida;

	public function ajaxEditarMedida(){

		$item = "id";
		$valor = $this->idMedida;

		$respuesta = ControladorMedidas::ctrMostrarMedidas($item, $valor);

		echo json_encode($respuesta);

	}
}

/*=============================================
EDITAR UNIDAD DE MEDIDAS
=============================================*/	
if(isset($_POST["idMedida"])){

	$medida = new AjaxMedidas();
	$medida -> idMedida = $_POST["idMedida"];
	$medida -> ajaxEditarMedida();
}
