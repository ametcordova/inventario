<?php

require_once "../controladores/familias.controlador.php";
require_once "../modelos/familias.modelo.php";


class AjaxFamilias{

	/*=============================================
	EDITAR FAMILIA
	=============================================*/	

	public $idFamilia;

	public function ajaxEditarFamilia(){

		$item = "id";
		$valor = $this->idFamilia;

		$respuesta = ControladorFamilias::ctrMostrarFamilias($item, $valor);

		echo json_encode($respuesta);

	}
}

/*=============================================
EDITAR FAMILIA
=============================================*/	
if(isset($_POST["idFamilia"])){

	$familia = new AjaxFamilias();
	$familia -> idFamilia = $_POST["idFamilia"];
	$familia -> ajaxEditarFamilia();
}

/*=============================================
EDITAR CATEGORÍA
=============================================*/	
	
