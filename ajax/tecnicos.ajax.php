<?php
require_once "../controladores/tecnicos.controlador.php";
require_once "../modelos/tecnicos.modelo.php";

if(isset($_POST["idTecnico"])){

    $item = "id";
	$valor = $_POST["idTecnico"];

	//echo $respuesta="Existe: $item $valor";
	
    $respuesta = ControladorTecnicos::ctrMostrarTecnicos($item, $valor);
	
	echo json_encode($respuesta);
	
}else{	
    
	echo $respuesta="No Existe:";

}