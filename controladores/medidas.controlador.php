<?php

class ControladorMedidas{

	/*=============================================
	CREAR UNIDAD DE MEDIDA
	=============================================*/

	static public function ctrCrearMedida(){

		if(isset($_POST["nuevaMedida"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaMedida"])){

				$tabla = "medidas";

				$datos = strtoupper($_POST["nuevaMedida"]);

				$respuesta = ModeloMedidas::mdlIngresarMedida($tabla, $datos);

				if($respuesta == "ok"){

                    echo '<script>                
                        swal({
                        title: "OK",
                        text: "¡La Unida de Med. a sido guardado correctamente!",
                        icon: "success",
                        button: "Cerrar",
                       }).then(function(result){
                        if(result){
                            //window.location = "medidas";
                        }
                        });                    
                    </script>'; 
				}


			}else{

				echo'<script>

					swal({
						  title: "error",
						  text: "¡La unidad de Med. no puede ir vacía o llevar caracteres especiales!",
						  icon:"error",
						  button: "Cerrar"
						  }).then(function(result){
							if (result) {
                                //window.location = "medidas";
							}
						})
			  	</script>';

			}

		}

	}

    
/*=============================================
MOSTRAR UNIDAD DE MEDIDA
============================================*/

	static public function ctrMostrarMedidas($item, $valor){

		$tabla = "medidas";

		$respuesta = ModeloMedidas::mdlMostrarMedidas($tabla, $item, $valor);

		return $respuesta;
	
	}    
    
/*=============================================
	EDITAR UNIDAD DE MEDIDA
=============================================*/

	static public function ctrEditarMedida(){

		if(isset($_POST["editarMedida"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarMedida"])){

				$tabla = "medidas";

				$datos = array("medida"=>$_POST["editarMedida"],
							   "id"=>$_POST["idMedida"]);

				$respuesta = ModeloMedidas::mdlEditarMedida($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>
					swal({
						  title: "Realizado",
						  text: "La Unidad de Med. ha sido cambiada correctamente",
						  icon: "success",
						  button: "Cerrar"
						  }).then(function(result){
									if (result) {
									   window.location = "medidas";
									}
								})

					</script>';

				}


			}else{

				echo'<script>

					swal({
						  title: "error",
						  text: "¡La Unidad de Med. no puede ir vacía o llevar caracteres especiales!",
						  icon: "warning",
						  button: "Cerrar"
						  }).then(function(result){
							if (result) {

							window.location = "medidas";

							}
						})

			  	</script>';

			}

		}

	}

	/*=============================================
	BORRAR UNIDAD DE MEDIDA
	=============================================*/

	 static public function ctrBorrarMedida(){

		if(isset($_GET["idMedida"])){

			$tabla ="medidas";
			$datos = $_GET["idMedida"];

			$respuesta = ModeloMedidas::mdlBorrarMedida($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

					swal({
						  title: "Realizado!",
						  text: "La Unidad de Med. ha sido borrada correctamente",
						  icon: "success",
						  button: "Cerrar"
						  }).then(function(result){
									if (result) {
									window.location = "medidas";
									
									}
								})

					</script>';
			}
		}
		
	}    
    
    
}   //fin de la clase