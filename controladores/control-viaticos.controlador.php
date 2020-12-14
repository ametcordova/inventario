<?php

class ControladorViaticos{
    

/*=============================================
	GUARDAR VIATICOS
=============================================*/

static public function ctrGuardarViatico($tabla, $datos){

    $respuesta = ModeloViaticos::mdlGuardarViatico($tabla, $datos);

}

/*=============================================
	GUARDAR AGREGA VIATICOS
=============================================*/

static public function ctrGuardarAgregaViatico($tabla, $datos){

    $respuesta = ModeloViaticos::mdlGuardarAgregaViatico($tabla, $datos);

}

/*=============================================
	TRAER VIATICOS
=============================================*/
static public function ctrGetViatico($item1, $idviatico){
	$tabla="tbl_viaticos";
    return $respuesta = ModeloViaticos::mdlGetViatico($tabla, $item1, $idviatico);
}

/*=============================================
	TRAER VIATICOS DETALLE
=============================================*/
static public function ctrGetViaticoDet($item2, $idviatico){
	$tabla="tbl_viaticos_detalle";
    return $respuesta = ModeloViaticos::mdlGetViaticoDet($tabla, $item2, $idviatico);
}

/*=============================================
	TRAER COMPROBACION DE VIATICOS 
=============================================*/
static public function ctrGetViaticoCheck($item2, $idviatico){
	$tabla="tbl_viaticos_checkup";
    return $respuesta = ModeloViaticos::mdlGetViaticoCheck($tabla, $item2, $idviatico);
}

/*=============================================
	GUARDAR COMPROBACION DE VIATICOS
=============================================*/
static public function ctrGuardarCheckup($tabla, $datos){
    $respuesta = ModeloViaticos::mdlGuardarCheckup($tabla, $datos);
}

/*=============================================
	TRAER DATOS DE VIATICO
=============================================*/
static public function ctrGetDatosViatico($tabla, $idviatico){
    return $respuesta = ModeloViaticos::mdlGetDatosViatico($tabla, $idviatico);
}

/*=============================================
	CAMBIAR ESTADO DE VIATICOS
=============================================*/
static public function ctrPutCambiaEstatus($item, $idviatico, $idestado){
	$tabla="tbl_viaticos";
	$swestado=$idestado==1?0:1;
    return $respuesta = ModeloViaticos::mdlPutCambiaEstatus($tabla, $item, $idviatico, $swestado);
}

/*=============================================
	OBTENER NUMERO DE VIATICOS
=============================================*/
static public function ctrgetNumViatico(){
	$tabla="tbl_viaticos";
    return $respuesta = ModeloViaticos::MdlAsignarNumViatico($tabla);
}

/*=============================================
    MOSTRAR
============================================*/
static public function ctrMostrarViaticos($item, $valor, $orden, $tipo, $year){
	$tabla = "tbl_viaticos";
	$respuesta = ModeloViaticos::mdlMostrarViaticos($tabla, $item, $valor, $orden, $tipo, $year);
	return $respuesta;
}    
    


}   //fin de la clase

