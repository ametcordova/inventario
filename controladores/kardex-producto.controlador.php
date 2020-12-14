<?php

class ControladorKardex{


/*=============================================
REPORTE KARDEX DE ENTRADA POR COMPRA
============================================*/

static public function ctrEntradaKardex($campo, $idproducto, $idalmacen, $fechainicial){

	$respuesta = ModeloKardex::MdlEntradaKardex($campo, $idproducto, $idalmacen, $fechainicial);
    
	return $respuesta;
	
}  

/*=============================================
REPORTE KARDEX DE MOVIMIENTOS POR AJUSTE
============================================*/
static public function ctrMovtoAjuste($tipomov, $idalmacen, $fechainicial){
	$tabla="ajusteinventario";

	$respuesta = ModeloKardex::MdlMovtoAjuste($tabla, $tipomov, $idalmacen, $fechainicial);
    
	return $respuesta;
	
}  

/*=============================================
REPORTE KARDEX DE SALIDA
============================================*/

static public function ctrSalidaKardex($campo, $idproducto, $idalmacen, $fechainicial){

	$respuesta = ModeloKardex::MdlSalidaKardex($campo, $idproducto, $idalmacen, $fechainicial);
    
	return $respuesta;
	
}  
				
/*=============================================
TRAER DATOS DE PROD
============================================*/

static public function ctrTraerProduct($idproducto){

	$tabla="productos";
	$estado="1";
	$campo="id";

	$respuesta = ModeloKardex::MdlTraerProduct($tabla, $campo, $idproducto, $estado);
    
	return $respuesta;
	
}  

/*=============================================
TRAER EXISTENCIA DE ALMACEN
============================================*/

static public function ctrTraerExist($campo, $idproducto, $nomalmacen){

	$respuesta = ModeloKardex::MdlTraerExist($campo, $idproducto, $nomalmacen);
    
	return $respuesta;
	
}  

}	//fin de la clase
