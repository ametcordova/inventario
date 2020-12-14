<?php
if(isset( $_GET["almacen"])){
    
require_once "../controladores/almacen.controlador.php";
require_once "../modelos/almacen.modelo.php";

    $campo = "id_producto";
	$valor =$_GET['idProducto'] ;
	$tabla = trim(strtolower($_GET['almacen']));

    $respuesta = ControladorAlmacen::ctrMostrarAlmacen($tabla, $campo, $valor);
    
    //var_dump($respuesta);
    
	echo json_encode($respuesta);
    
}

/*=============================================
	VALIDA QUE NUMERO DE SALIDA NO SE REPITA
=============================================*/	 
if(isset( $_GET["numDocto"])){

require_once "../controladores/salidas.controlador.php";
require_once "../modelos/salidas.modelo.php";

    $numeroDocto=$_GET['numDocto'];
    
    $item = "num_salida";
	$valor = $_GET['numDocto'];

    $respuesta = ControladorSalidas::ctrValidarNumSalida($item, $valor);

    echo $respuesta ? "Núm. ya Existe!" : 0;

}

/*=============================================
	ASIGNAR NUMERO DE SALIDA
=============================================*/	 
if(isset( $_GET["numConsecutivo"])){

require_once "../controladores/salidas.controlador.php";
require_once "../modelos/salidas.modelo.php";

   
    $item = "num_salida";
	$valor = "";

    $respuesta = ControladorSalidas::ctrAsignarNumSalida($item, $valor);

	//OBTENEMOS EL EL NUMERO DEL 3ER CAMPO DE LA TABLA. num_salida O 0 SI NO HAY REGISTROS
    echo $respuesta ? $respuesta[2] : 0;			

}