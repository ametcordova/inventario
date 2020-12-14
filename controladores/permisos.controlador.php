<?php

class ControladorPermisos{

/*=============================================
GUARDAR 
=============================================*/
static public function ctrGuardarPermisos($tabla, $datajson, $usuario, $campo){

	$respuesta = ModeloPermisos::mdlGuardarPermisos($tabla, $datajson, $usuario, $campo);

		if($respuesta == "ok"){
			return true;
		}else{
            return false;
        };
}

/*=============================================
OBTENER PERMISOS DE ACCESOS
=============================================*/
static public function ctrGetAccesos($tabla, $usuario, $module, $campo){

	return $respuesta = ModeloPermisos::mdlGetAccesos($tabla, $usuario, $module, $campo);

}

/*=============================================
OBTENER PERMISOS DE USUARIOS 
=============================================*/
static public function ctrGetPermisos($tabla, $usuario, $modulo){

	return $respuesta = ModeloPermisos::mdlGetPermisos($tabla, $usuario, $modulo);

}

/*=============================================
OBTENER PERMISOS DE USUARIOS 
=============================================*/
// static public function ctrGetPermisosCat($tabla, $usuario){

// 	return $respuesta = ModeloPermisos::mdlGetPermisosCat($tabla, $usuario);

// }

}   //fin de la clase
?>