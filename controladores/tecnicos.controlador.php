<?php

class ControladorTecnicos{

/*=============================================
CREAR TECNICOS
=============================================*/
    public function ctrCrearTecnico(){

		if(isset($_POST["NuevoNombre"])){

			if(preg_match('/^[#\.\,\a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["NuevoNombre"]) &&
               preg_match('/^[-\a-zA-Z0-9 ]+$/', $_POST["NuevoRfc"]) &&
               preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["NuevoEmail"]) &&
			   preg_match('/^[()\-0-9 ]+$/', $_POST["NuevoTelefono"]) &&
			   preg_match('/^[#\.\,\-a-zA-Z0-9 ]+$/', $_POST["NuevaDireccion"]) ){

			   	$tabla = "tecnicos";
				
				$vienede=isset($_POST["scriptSource"])? $_POST["scriptSource"]:"tecnicos";
                //$newDate = date("Y-d-m", strtotime($_POST["nuevaFechaNacimiento"]));
				
			   	$datos = array("nombre"=>   strtoupper($_POST["NuevoNombre"]),
					           "rfc"=>      strtoupper($_POST["NuevoRfc"]),
					           "curp"=>     		   $_POST["NuevoCurp"],
							   "direccion"=>strtoupper($_POST["NuevaDireccion"]),
							   "cp"=>				   $_POST["NuevoCp"],
							   "ciudad"=>   strtoupper($_POST["NuevaCiudad"]),
							   "estado"=>   		   $_POST["NuevoEstado"],
					           "email"=>               $_POST["NuevoEmail"],
					           "telefonos"=>           $_POST["NuevoTelefono"],
					           "expediente"=>          $_POST["NuevoExpediente"],
					           "usuario"=>             $_POST["NuevoUsuario"],
					           "contrasena"=>          $_POST["NuevaContrasena"],
					           "numero_licencia"=>     $_POST["NuevaLicencia"],
					           "numero_imss"=>         $_POST["NuevoSeguro"],
					           "banco"=>         	   $_POST["NuevoBanco"],
					           "num_cuenta"=>          $_POST["NuevaCuenta"],
					           "clabe"=>               $_POST["NuevaClabe"],
					           "edo_nacimiento"=>      $_POST["NacimientoEstado"],
					           "alm_asignado"=>        $_POST["NuevoAlmacen"],
					           "status"=>              $_POST["NuevoEstatus"],
					           "ultusuario"=>          $_POST["idDeUsuario"]);


			   	$respuesta = ModeloTecnicos::mdlIngresarTecnico($tabla, $datos);

			   	if($respuesta == "ok"){

					echo'<script>
					var varjs="'.$vienede.'";		//convierte variable PHP a JS
					swal({
						  icon: "success",
						  title: "TÉCNICO ha sido guardado correctamente",
						  button: "Cerrar",
						  }).then((result)=>{
									if (result) {

									window.location = varjs;

									}
								})

					</script>';

				}else{
                    echo '<script>                
                        swal({
						type: "warning",
                        title: "error",
                        text: "¡TECNICO no ha sido guardado correctamente!",
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
						  icon: "error",
						  title: "¡Campos no puede ir vacío o llevar caracteres especiales!",
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
EDITAR TECNICOS
=============================================*/
    public function ctrEditarTecnico(){

		if(isset($_POST["EditarNombre"])){

			if(preg_match('/^[#\.\,\a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["EditarNombre"]) &&
               preg_match('/^[-\a-zA-Z0-9 ]+$/', $_POST["EditarRfc"]) &&
               preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["EditarEmail"]) &&
			   preg_match('/^[()\-0-9 ]+$/', $_POST["EditarTelefono"]) &&
			   preg_match('/^[#\.\,\-a-zA-Z0-9 ]+$/', $_POST["EditarDireccion"]) ){

			   	$tabla = "tecnicos";
				
				$vienede=isset($_POST["scriptSource"])? $_POST["scriptSource"]:"tecnicos";

			   	$datos = array("id"=>                  $_POST["idTecnico"],
                               "nombre"=>   strtoupper($_POST["EditarNombre"]),
					           "rfc"=>      strtoupper($_POST["EditarRfc"]),
					           "curp"=>     		   $_POST["EditarCurp"],
							   "direccion"=>strtoupper($_POST["EditarDireccion"]),
							   "cp"=>				   $_POST["EditarCp"],
							   "ciudad"=>   strtoupper($_POST["EditarCiudad"]),
							   "estado"=>   		   $_POST["EditarEstado"],
					           "email"=>               $_POST["EditarEmail"],
					           "telefonos"=>           $_POST["EditarTelefono"],
					           "expediente"=>          $_POST["EditarExpediente"],
					           "usuario"=>             $_POST["EditarUsuario"],
					           "contrasena"=>          $_POST["EditarContrasena"],
					           "numero_licencia"=>     $_POST["EditarLicencia"],
					           "numero_imss"=>         $_POST["EditarSeguro"],
					           "banco"=>         	   $_POST["EditarBanco"],
					           "num_cuenta"=>          $_POST["EditarCuenta"],
					           "clabe"=>               $_POST["EditarClabe"],
					           "edo_nacimiento"=>      $_POST["EditarNacimientoEstado"],
					           "alm_asignado"=>        $_POST["EditarAlmacen"],
					           "status"=>              $_POST["EditarEstatus"],
					           "ultusuario"=>          $_POST["idDeUsuario"]);


			   	$respuesta = ModeloTecnicos::mdlEditarTecnico($tabla, $datos);

			   	if($respuesta == "ok"){

					echo'<script>
					var varjs="'.$vienede.'";		//convierte variable PHP a JS
					swal({
						  icon: "success",
						  title: "TÉCNICO ha sido editado correctamente",
						  button: "Cerrar",
						  }).then((result)=>{
									if (result) {

									window.location = varjs;

									}
								})

					</script>';

				}else{
                    echo '<script>                
                        swal({
                        title: "error",
                        text: "¡TECNICO no ha sido guardado correctamente!",
                        icon: "error",
                        button: "Cerrar",
                       }).then(function(result){
                        if(result){
                            //window.location = varjs;
                        }
                        });                    
                    </script>'; 
                }

			}else{

				echo'<script>

					swal({
						  icon: "error",
						  title: "¡Campos no puede ir vacío o llevar caracteres especiales!",
						  button: "Cerrar"
						  }).then((result)=>{
							if (result) {

							//window.location = varjs;

							}
						})

			  	</script>';



			}

		}
	}
    
    
   /*=============================================
	MOSTRAR TECNICOS
	=============================================*/

	static public function ctrMostrarTecnicos($item, $valor){

		$tabla = "tecnicos";

		$respuesta = ModeloTecnicos::mdlMostrarTecnicos($tabla, $item, $valor);

		return $respuesta;

	}		

/*=============================================
MOSTRAR ESTADOS
=============================================*/

	static public function ctrMostrarEstados($item, $valor){

		$tabla = "catestado";

		$respuesta = ModeloTecnicos::mdlMostrarEstados($tabla, $item, $valor);

		return $respuesta;
	
	}    
    
    
    
}   //FIN DE LA CLASE