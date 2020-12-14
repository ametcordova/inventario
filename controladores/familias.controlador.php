<?php

class ControladorFamilias{

	/*=============================================
	CREAR FAMILIAS
	=============================================*/

	public function ctrCrearFamilia(){

		if(isset($_POST["nuevaFamilia"])){

			if(preg_match('/^[#\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaFamilia"])){

				$tabla = "familias";

				//$datos = strtoupper($_POST["nuevaFamilia"]);
                
                $datos = array("familia"=>   strtoupper($_POST["nuevaFamilia"]),
                               "ultusuario" =>          $_POST["idDeUsuario"]);

				$respuesta = ModeloFamilias::mdlIngresarFamilia($tabla, $datos);

				if($respuesta == "ok"){

					echo "<script>
					Swal.fire({
						title: 'Guardado!',
						text: '¡Familia a sido guardado correctamente!',
						icon: 'success',
						timer: 3000,
						confirmButtonText: 'Entendido'
					  }).then(result=>{
                        if(result){
                            window.location = 'familias';
                        }
                        });                    	
                    </script>"; 
				}else{
                    
				echo"<script>
					var varjs='".$respuesta."';		//convierte variable PHP a JS

					Swal.fire({
						title: '¡Error!'+varjs,
						text: '¡No se Pudo Guardar la Información!',
						icon: 'error',
						confirmButtonText: 'Entendido'
					  }).then(function(result){
						if (result) {
							window.location = 'familias';
						}
					}) 

			  	</script>";
                    
                }

			}else{

				echo"<script>

				Swal.fire({
					title: '¡Error!',
					text: '¡Familia no puede ir vacía o llevar caracteres especiales!',
					icon: 'error',
					timer: 4000,
					confirmButtonText: 'Entendido'
				  }).then(function(result){
					if (result) {
						window.location = 'familias';
					}
				}) 
				
			  	</script>";

			}

        }

	}

    
/*=============================================
MOSTRAR FAMILIAS
============================================*/

	static public function ctrMostrarFamilias($item, $valor){

		$tabla = "familias";

		$respuesta = ModeloFamilias::mdlMostrarFamilias($tabla, $item, $valor);

		return $respuesta;
	
	}    

/*=============================================
LISTAR FAMILIAS
============================================*/
static public function ctrlistarFamilias($item){

	$tabla = "familias";

	$respuesta = ModeloFamilias::mdllistarFamilias($tabla, $item);

	return $respuesta;

}    

/*=============================================
ELIMINAR FAMILIA
============================================*/
static public function ctrEliminarFamilia($tabla, $item, $valor){

	$respuesta = ModeloFamilias::mdlEliminarFamilia($tabla, $item, $valor);

	return $respuesta;

}    


/*=============================================
	EDITAR CATEGORIA
=============================================*/

	public function ctrEditarFamilia(){

		if(isset($_POST["editarFamilia"])){

			if(preg_match('/^[#\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarFamilia"])){

				$tabla = "familias";

				$datos = array("familia"=>strtoupper($_POST["editarFamilia"]),
						"ultusuario"=>$_POST["idDeUsuario"],
						"id"=>$_POST["idFamilia"]);

				$respuesta = ModeloFamilias::mdlEditarFamilia($tabla, $datos);

				if($respuesta == "ok"){

					echo"<script>

					Swal.fire({
						position: 'top-end',
						icon: 'success',
						title: 'La familia ha sido cambiada correctamente',
						showConfirmButton: false,
						timer: 1500
					  }).then(function(result){
						if (result) {
						   window.location = 'familias';
						}
					});
					</script>";

				}else{
                    
					echo"<script>
						var varjs='".$respuesta."';		//convierte variable PHP a JS
						Swal.fire({
							  title: '¡No se Pudo Guardar la Información!',
							  text: 'Error: '+varjs,
							  icon:'error',
							  showCancelButton: true,
							  cancelButtonColor: '#d33',
							  }).then(function(result){
								if (result) {
									window.location = 'familias';
								}
							})
					  </script>";
						
					}

			}else{

				echo"<script>

				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: '¡Familia no puede ir vacía o llevar caracteres especiales!',
					footer: '<a href=familias>Volver a intentaro?</a>'
				  }).then(function(result){
				 		if (result) {
				 		window.location = 'familias';
					}
				})

			  	</script>";

			}

		}

	}

	/*=============================================
	BORRAR CATEGORIA
	=============================================*/

	 public function ctrBorrarFamilia(){

		if(isset($_GET["idFamilia"])){

			$tabla ="familias";
			$datos = $_GET["idFamilia"];

			$respuesta = ModeloFamilias::mdlBorrarFamilia($tabla, $datos);

			if($respuesta == "ok"){

				echo"<script>

				const Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000,
					timerProgressBar: true,
					onOpen: (toast) => {
					  toast.addEventListener('mouseenter', Swal.stopTimer)
					  toast.addEventListener('mouseleave', Swal.resumeTimer)
					}
				  })
				  
				  Toast.fire({
					icon: 'success',
					title: 'Familia ha sido borrada correctamente. Espere...'
				  }).then(function(result){
					if (result) {

					window.location = 'familias';

					}
				})

					</script>";
			}
		}
		
	}    
    
    
}   //fin de la clase