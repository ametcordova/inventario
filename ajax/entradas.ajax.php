<?php
require_once "../controladores/entradas.controlador.php";
require_once "../modelos/entradas.modelo.php";

if(isset( $_GET["numDocto"])){

    $numeroDocto=$_GET['numDocto'];
    
    $item = "numerodocto";
	$valor = $_GET['numDocto'];

    $respuesta = ControladorEntradas::ctrValidarDocto($item, $valor);

    echo $respuesta ? "Núm. de Docto. ya Existe" : 0;

}