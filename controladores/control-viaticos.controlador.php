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
	$directorio="../archivos/comprobantes/";
	if (!file_exists($directorio)) {
		mkdir($directorio, 0755, true);
	}
	
	$ruta=$directorio.$datos["ruta_factura"]["name"];

	if (file_exists($ruta)) {
		return $respuesta=500;
	}

	//SUBIR ARCHIVO A LA CARPETA ARCHIVOS/COMPROBANTES
	if (move_uploaded_file($_FILES['facturaPdf']['tmp_name'], $ruta)) {
		 $respuesta="El archivo " . basename($_FILES['facturaPdf']['name']) . " se ha subido con éxito";
	} else {
		$respuesta="Hubo un error subiendo el archivo, por favor inténtalo de nuevo!";
	}

    if(ModeloViaticos::mdlGuardarCheckup($tabla, $datos, $ruta)){
		$respuesta = "Registro y archivo guardado correctamente!";
	}else{
		$respuesta = "Registro y archivo NO fue guardado. REVISE!";
	}

	return $respuesta;
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

