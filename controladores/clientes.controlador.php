<?php

class ControladorClientes{

	/*=============================================
	CREAR CLIENTES preg_match('/^[\/a-zA-Z0-9]+$/', $_POST["nuevoRFC"]) &&
	=============================================*/

	   public function ctrCrearCliente(){

		if(isset($_POST["nuevoCliente"])){

			if(preg_match('/^[#\.\,\a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoCliente"]) &&
               preg_match('/^[A-ZÑ&]{3,4}\d{6}(?:[A-Z\d]{3})?$/', $_POST["nuevoRFC"]) &&
               preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["nuevoEmail"]) &&                
			   preg_match('/^[()\-0-9 ]+$/', $_POST["nuevoTelefono"]) &&
			   preg_match('/^[0-9]{5}$/', $_POST["nuevoCP"]) &&
			   preg_match('/^[#\.\,\-a-zA-Z0-9 ]+$/', $_POST["nuevaDireccion"]) ){

			   	$tabla = "clientes";
				
				$vienede=isset($_POST["scriptSource"])? $_POST["scriptSource"]:"clientes";
				
                //$newDate = date("Y-d-m", strtotime($_POST["nuevaFechaNacimiento"]));
			   	$datos = array("nombre"			=>strtoupper($_POST["nuevoCliente"]),
					           "rfc"			=>strtoupper($_POST["nuevoRFC"]),
					           "curp"			=>strtoupper($_POST["nuevoCurp"]),
					           "num_int_ext"	=>$_POST["nuevoNumInt"],
					           "telefono"		=>$_POST["nuevoTelefono"],
					           "direccion"		=>strtoupper($_POST["nuevaDireccion"]),
					           "colonia"		=>strtoupper($_POST["nuevaColonia"]),
					           "codpostal"		=>$_POST["nuevoCP"],
					           "ciudad"			=>strtoupper($_POST["nuevaCiudad"]),
					           "estado"			=>$_POST["nuevoEstado"],
					           "regimenfiscal"	=>$_POST["nuevoRegFiscal"],
					           "act_economica"	=>strtoupper($_POST["nvaActividadEconomica"]),
					           "formadepago"	=>$_POST["nuevaFormaPago"],	
							   "email"			=>$_POST["nuevoEmail"],
					           "fecha_creacion"	=>$_POST["nuevaFechaCreacion"]);
                
			   	$respuesta = ModeloClientes::mdlIngresarCliente($tabla, $datos);

			   	if($respuesta == "ok"){

					echo'<script>
					var varjs="'.$vienede.'";		//convierte variable PHP a JS
					swal({
						  icon: "success",
						  title: "El cliente ha sido guardado correctamente",
						  button: "Cerrar",
						  })
						  .then((result)=>{
								if (result) {
									window.location = varjs;
								}
							})
					</script>';

				}else{
                    echo '<script>                
                        swal({
                        title: "OK",
                        text: "¡Cliente no ha sido guardado correctamente!",
                        icon: "success",
                        button: "Cerrar",
                       }).then(function(result){
							if(result){
								window.location = varjs;
							}
                        });                    
                    </script>'; 
                }

			}else{

				echo'<script>

					swal({
						title: "Algo esta mal!!",
						icon: "error",
						title: "Revise su datos, no capturar caracteres especiales!",
						button: "Cerrar"
						  }).then((result)=>{
							if (result) {
								window.location = varjs;
							}
						})

			  	</script>';
			}
		}
	}

/*=============================================
	MOSTRAR CLIENTES
	=============================================*/

	static public function ctrMostrarClientes($item, $valor){

		$tabla = "clientes";

		$respuesta = ModeloClientes::mdlMostrarClientes($tabla, $item, $valor);

		return $respuesta;

	}	
    
    
    
    /*=============================================
	EDITAR CLIENTE
	=============================================*/

	static public function ctrEditarCliente(){

		if(isset($_POST["EditarCliente"])){

			if(preg_match('/^[#\.\,\a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["EditarCliente"]) &&
               preg_match('/^[\/a-zA-Z0-9 ]+$/', $_POST["EditarDocumento"]) &&
               preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["EditarEmail"]) &&                
			   preg_match('/^[()\-0-9 ]+$/', $_POST["EditarTelefono"]) &&
			   preg_match('/^[#\.\,\-a-zA-Z0-9 ]+$/', $_POST["EditarDireccion"]) ){

			   	$tabla = "clientes";

				$vienede=isset($_POST["scriptSource"])? $_POST["scriptSource"]:"clientes";    //SI VIENE DE CREAR-VENTA O DE CLIENTES
                
                //$nuevaFecha = date("Y-m-d", strtotime($_POST["EditarFechaNacimiento"]));
                //var_dump($nuevaFecha);
                
			   	$datos = array("id"=>$_POST["idCliente"],
			   				   "nombre"=>   strtoupper($_POST["EditarCliente"]),
					           "rfc"=>      strtoupper($_POST["EditarDocumento"]),
					           "email"=>               $_POST["EditarEmail"],
					           "telefono"=>            $_POST["EditarTelefono"],
					           "direccion"=>strtoupper($_POST["EditarDireccion"]),
					           "fecha_nacimiento"=>    $_POST["EditarFechaNacimiento"]);

			   	$respuesta = ModeloClientes::mdlEditarCliente($tabla, $datos);
				
			   	if($respuesta == "ok"){

					echo'<script>
					var varjs="'.$vienede.'";		//convierte variable PHP a JS
					swal({
						  icon: "success",
						  text: "El cliente ha sido cambiado correctamente",
						  button: "Cerrar"
						  }).then((result)=>{
									if (result) {

										window.location = varjs;

									}
								})

					</script>';

				}

			}else{

				echo'<script>

					swal({
						  icon: "error",
						  text: "¡El cliente no puede ir vacío o llevar caracteres especiales!",
						  button: "Cerrar"
						  }).then(function(result){
							if (result) {

								window.location = varjs;

							}
						})

			  	</script>';

			}

		}

	}    

    
/*=============================================
	ELIMINAR CLIENTE
	=============================================*/

	static public function ctrEliminarCliente(){

		if(isset($_GET["idCliente"])){

			$tabla ="clientes";
			$datos = $_GET["idCliente"];

			$respuesta = ModeloClientes::mdlEliminarCliente($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  icon: "success",
					  title: "El cliente ha sido borrado correctamente",
					  button: "Cerrar",
					  }).then(function(result){
								if (result) {

								window.location = "clientes";

								}
							})

				</script>';

			}		

		}

	}    
    
} //fin de la clase

