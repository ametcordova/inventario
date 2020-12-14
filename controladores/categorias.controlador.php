<?php

class ControladorCategorias{

	/*=============================================
	CREAR CATEGORIAS
	=============================================*/

	public function ctrCrearCategoria(){

		if(isset($_POST["nuevaCategoria"])){

			if(preg_match('/^[#\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaCategoria"])){

				$tabla = "categorias";

				$datos = strtoupper($_POST["nuevaCategoria"]);

				$respuesta = ModeloCategorias::mdlIngresarCategoria($tabla, $datos);

				if($respuesta == "ok"){

                    echo '<script>                
                        swal({
                        title: "¡La categoría a sido guardado correctamente!",
                        icon: "success",
                        button: "Cerrar",
                       }).then(function(result){
                        if(result){
                            window.location = "categorias";
                        }
                        });                    
                    </script>'; 
				}


			}else{

				echo'<script>

					swal({
						  title: "error",
						  text: "¡La categoría no puede ir vacía o llevar caracteres especiales!",
						  icon:"error",
						  button: "Cerrar"
						  }).then(function(result){
							if (result) {
                                //window.location = "categorias";
							}
						})
			  	</script>';

			}

        }

	}

    
/*=============================================
MOSTRAR CATEGORIAS
============================================*/

	static public function ctrMostrarCategorias($item, $valor){

		$tabla = "categorias";

		$respuesta = ModeloCategorias::mdlMostrarCategorias($tabla, $item, $valor);

		return $respuesta;
	
	}    
    
/*=============================================
	EDITAR CATEGORIA
=============================================*/

	public function ctrEditarCategoria(){

		if(isset($_POST["editarCategoria"])){

			if(preg_match('/^[#\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarCategoria"])){

				$tabla = "categorias";

				$datos = array("categoria"=>strtoupper($_POST["editarCategoria"]),
							   "id"=>$_POST["idCategoria"]);

				$respuesta = ModeloCategorias::mdlEditarCategoria($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>
					
					swal({
						  title: "Realizado",
						  text: "La categoría ha sido cambiada correctamente",
						  icon: "success",
						  button: "Cerrar"
						  }).then(function(result){
									if (result) {
									   window.location = "categorias";
									}
								})

					</script>';

				}


			}else{

				echo'<script>

					swal({
						  title: "error",
						  text: "¡La categoría no puede ir vacía o llevar caracteres especiales!",
						  icon: "warning",
						  button: "Cerrar"
						  }).then(function(result){
							if (result) {

							window.location = "categorias";

							}
						})

			  	</script>';

			}

		}

	}

	/*=============================================
	BORRAR CATEGORIA
	=============================================*/

	 public function ctrBorrarCategoria(){

		if(isset($_GET["idCategoria"])){

			$tabla ="Categorias";
			$datos = $_GET["idCategoria"];

			$respuesta = ModeloCategorias::mdlBorrarCategoria($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

					swal({
						  title: "Realizado!",
						  text: "La categoría ha sido borrada correctamente",
						  icon: "success",
						  button: "Cerrar"
						  }).then(function(result){
									if (result) {

									window.location = "categorias";

									}
								})

					</script>';
			}
		}
		
	}    
    
    
}   //fin de la clase