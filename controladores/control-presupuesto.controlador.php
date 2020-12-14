<?php

class ControladorPresupuesto{
    
/*=============================================
GUARDAR INGRESO
=============================================*/

static public function ctrIngreso($tabla, $datos){

		$respuesta = ModeloIngreso::mdlIngreso($tabla, $datos);

}

    
}   //fin de la clase
