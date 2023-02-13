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
					           "id_empresa"		=>$_POST["id_empresa"],
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
					           "id_usocfdi"		=>$_POST["nuevoUsoCFDI"],
					           "id_metodopago"	=>$_POST["nuevoMetodoPago"],
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

		if(isset($_POST["editaCliente"])){

			if(preg_match('/^[#\.\,\a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editaCliente"]) &&
               preg_match('/^[\/a-zA-Z0-9 ]+$/', $_POST["editaRFC"]) &&
               preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["editaEmail"]) &&                
			   preg_match('/^[()\-0-9 ]+$/', $_POST["editaTelefono"]) &&
			   preg_match('/^[#\.\,\-a-zA-Z0-9 ]+$/', $_POST["editaDireccion"]) ){

			   	$tabla = "clientes";

				$vienede=isset($_POST["scriptSource"])? $_POST["scriptSource"]:"clientes";    //SI VIENE DE CREAR-VENTA O DE CLIENTES
                
                //$nuevaFecha = date("Y-m-d", strtotime($_POST["EditarFechaNacimiento"]));
                //var_dump($nuevaFecha);
                
			   	$datos = array(
								"id"			=>$_POST["idCliente"],
								"id_empresa"	=>$_POST["id_empresa"],
			   				   	"nombre"		=>strtoupper($_POST["editaCliente"]),
					           	"rfc"			=>strtoupper($_POST["editaRFC"]),
					           	"telefono"		=>$_POST["editaTelefono"],
					           	"direccion"		=>strtoupper($_POST["editaDireccion"]),
					           	"curp"			=>strtoupper($_POST["editaCurp"]),
					           	"num_int_ext"	=>$_POST["editaNumInt"],
					          	"colonia"		=>strtoupper($_POST["editaColonia"]),
					           	"codpostal"		=>$_POST["editaCP"],
					           	"ciudad"		=>strtoupper($_POST["editaCiudad"]),
					           	"estado"		=>$_POST["editaEstado"],
					           	"regimenfiscal"	=>$_POST["editaRegFiscal"],
					           	"act_economica"	=>strtoupper($_POST["editaActividadEconomica"]),
					           	"formadepago"	=>$_POST["editaFormaPago"],	
					           	"id_usocfdi"	=>$_POST["editaUsoCFDI"],
					           	"metodopago"	=>$_POST["editaMetodoPago"],
					           	"email"			=>$_POST["editaEmail"],
					           	"fecha_creacion"=>$_POST["editaFechaCreacion"],
					           	"ultusuario"	=>$_POST["ultusuario"]);


			   	$respuesta = ModeloClientes::mdlEditarCliente($tabla, $datos);
				
			   	if($respuesta == "ok"){

					echo'<script>
					var varjs="'.$vienede.'";		//convierte variable PHP a JS
					swal({
						  icon: "success",
						  text: "El cliente ha sido cambiado correctamente",
						  button: "Cerrar",
						  timer: 2500
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

