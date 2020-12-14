<?php

require_once "../controladores/crear-almacen.controlador.php";
require_once "../modelos/crear-almacen.modelo.php";

class AjaxAlmacenes{

	/*=============================================
	EDITAR ALMACENES
	=============================================*/	

	public $idAlmacen;

	public function ajaxEditarAlmacen(){

		$item = "id";
		$valor = $this->idAlmacen;

		$respuesta = ControladorAlmacenes::ctrMostrarAlmacenes($item, $valor);

		echo json_encode($respuesta);


	}

}

/*=============================================
EDITAR CLIENTE
=============================================*/	

if(isset($_POST["idAlmacen"])){

	$cliente = new AjaxAlmacenes();
	$cliente -> idAlmacen = $_POST["idAlmacen"];
	$cliente -> ajaxEditarAlmacen();

}