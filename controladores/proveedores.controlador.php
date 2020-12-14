<?php

class ControladorProveedores{

	/*=============================================
	CREAR PROVEEDOR
	=============================================*/

	   public function ctrCrearProveedor(){

		if(isset($_POST["NuevoNombre"])){

			if(preg_match('/^[,\a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["NuevoNombre"]) &&
               preg_match('/^[\/a-zA-Z0-9 ]+$/', $_POST["NuevoRfc"]) &&
               preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["NuevoEmail"]) &&                
			   preg_match('/^[()\-0-9 ]+$/', $_POST["NuevoTelefono1"]) &&
			   preg_match('/^[#\.\,\-a-zA-Z0-9 ]+$/', $_POST["NuevaDireccion"]) ){

			   	$tabla = "proveedores";
               
			   	$datos = array("nombre"=>strtoupper($_POST["NuevoNombre"]),
					           "rfc"=>strtoupper($_POST["NuevoRfc"]),
                               "direccion"=>strtoupper($_POST["NuevaDireccion"]),
                               "codpostal"=>$_POST["NuevoCp"],
                               "ciudad"=>strtoupper($_POST["NuevaCiudad"]),
					           "email"=>$_POST["NuevoEmail"],
					           "telefono"=>$_POST["NuevoTelefono1"],
					           "contacto"=>strtoupper($_POST["NuevoContacto"]),
                               "tel_contacto"=>$_POST["NuevoTelefono2"],
					           "email_contacto"=>$_POST["NuevoEmail2"],
					           "estatus"=>$_POST["NuevoEstado"]);
                
                
			   	$respuesta = ModeloProveedores::mdlIngresarProveedor($tabla, $datos);
                
			   	if($respuesta == "ok"){

					echo'<script>

					swal({
						  icon: "success",
						  title: "El proveedor ha sido guardado correctamente",
						  button: "Cerrar",
						  }).then(function(result){
									if (result) {

									window.location = "proveedores";

									}
								})

					</script>';

				}else{
                    echo '<script>                
                        swal({
                        title: "OK",
                        text: "¡Proveedor no ha sido guardado correctamente!",
                        icon: "success",
                        button: "Cerrar",
                       }).then(function(result){
                        if(result){
                            window.location = "proveedores";
                        }
                        });                    
                    </script>'; 
                }

			}else{

				echo'<script>

					swal({
						  icon: "error",
						  title: "¡El Proveedor no puede ir vacío o llevar caracteres especiales!",
						  button: "Cerrar"
						  }).then(function(result){
							if (result) {

							window.location = "proveedores";

							}
						})

			  	</script>';
			}

		}else{
            
        }

	}


    /*=============================================
	MOSTRAR PROVEEDORES
	=============================================*/

	static public function ctrMostrarProveedores($item, $valor){

		$tabla = "proveedores";

		$respuesta = ModeloProveedores::mdlMostrarProveedores($tabla, $item, $valor);

		return $respuesta;

	}	
        
    /*=============================================
	EDITAR PROVEEDOR
	=============================================*/   
    static public function ctrEditarProveedor(){
    
    if(isset($_POST["EditarNombre"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["EditarNombre"]) &&
               preg_match('/^[\/a-zA-Z0-9 ]+$/', $_POST["EditarRfc"]) &&
               preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["EditarEmail"]) &&                
			   preg_match('/^[()\-0-9 ]+$/', $_POST["EditarTelefono1"]) &&
			   preg_match('/^[#\.\,\-a-zA-Z0-9 ]+$/', $_POST["EditarDireccion"]) ){

			   	$tabla = "proveedores";
               
			   	$datos = array("nombre"=>strtoupper($_POST["EditarNombre"]),
					           "rfc"=>strtoupper($_POST["EditarRfc"]),
                               "direccion"=>strtoupper($_POST["EditarDireccion"]),
                               "codpostal"=>$_POST["EditarCp"],
                               "ciudad"=>strtoupper($_POST["EditarCiudad"]),
					           "email"=>$_POST["EditarEmail"],
					           "telefono"=>$_POST["EditarTelefono1"],
					           "contacto"=>strtoupper($_POST["EditarContacto"]),
                               "tel_contacto"=>$_POST["EditarTelefono2"],
					           "email_contacto"=>$_POST["EditarEmail2"],
					           "estatus"=>$_POST["EditarEstado"],
					           "id"=>$_POST["idProveedor"]);
    
                $respuesta = ModeloProveedores::mdlEditarProveedor($tabla, $datos);
                
               
			   	if($respuesta == "ok"){

					echo'<script>

					swal({
						  icon: "success",
						  title: "El Proveedor ha sido cambiado correctamente",
						  button: "Cerrar"
						  }).then(function(result){
									if (result) {

									window.location = "proveedores";

									}
								})

					</script>';

				}elseif ($respuesta=="error"){
					echo'<script>
						swal("Error!", "No fue posible Actualizar Proveedor!");
					</script>';
				}

			}else{

				echo'<script>

					swal({
						  icon: "error",
						  title: "¡El cliente no puede ir vacío o llevar caracteres especiales!",
						  button: "Cerrar"
						  }).then(function(result){
							if (result) {

							window.location = "proveedores";

							}
						})

			  	</script>';

			}

		}
    
     }
    
	/*=============================================
	ELIMINAR PROVEEDOR
	=============================================*/

	static public function ctrEliminarProveedor(){

		if(isset($_GET["idProveedor"])){

			$tabla ="proveedores";
			$datos = $_GET["idProveedor"];

			$respuesta = ModeloProveedores::mdlEliminarProveedor($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  icon: "success",
					  title: "El Proveedor ha sido borrado correctamente",
					  button: "Cerrar",
					  }).then(result)=>{
								if (result) {
									window.location = "proveedores";
								}
							})

				</script>';

			}		

		}

	}   
    
} //fin de la clase

