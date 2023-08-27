 <?php
class ControladorUsuarios{
    
	/*================= LOGIN ================================ */
	/* FUNCION PARA INGRESAR AL SISTEMA ADMIN                 */
	/*======================================================= */
    static public function ctrIngresoUsuario(){
		
        if(isset($_POST["ingUsuario"])){
            if(preg_match('/^[a-zA-Z0-9]+$/',$_POST["ingUsuario"]) &&
               preg_match('/^[a-zA-Z0-9]+$/',$_POST["ingPassword"])){
                
              /*$encriptar = crypt($_POST["ingPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');*/
				
				/*SI ESTA CHEQUEADO checkbox RECORDARME*/
				if (isset($_POST["recordarme"]) && !empty($_POST["ingUsuario"])) {
					$user=$_POST["ingUsuario"];
					setcookie("logusuario",$user,time()+30*24*60*60*12);
				}
				
               $tabla="usuarios" ;
               $campo="usuario";
               $valor=$_POST["ingUsuario"];
                $respuesta=ModeloUsuarios::mdlMostrarUsuarios($tabla,$campo,$valor);
                //var_dump($respuesta["usuario"]);
                
				if(!isset($respuesta["usuario"])){
					echo '<br><div class="alert alert-danger text-center"> Usuario No Permitido</div>';  
					
				}
				
                if(isset($respuesta["usuario"]) && $respuesta["usuario"]==$_POST["ingUsuario"] && $respuesta["password"]==$_POST["ingPassword"]){   /*cambiar el $_POST[ingPassword] por $encriptar */
                    
                  if($respuesta["estado"]==1){
                      
                   echo '<br><div class="alert alert-success text-center"> Bienvenido al sistema</div>'; 
                    
                    $_SESSION["iniciarSesion"]="ok";
                    $_SESSION["id"]=$respuesta["id"];
                    $_SESSION["nombre"]=$respuesta["nombre"];
                    $_SESSION["usuario"]=$respuesta["usuario"];
                    $_SESSION["user"]=$respuesta["user"];
                    $_SESSION["foto"]=$respuesta["foto"];
                    $_SESSION["perfil"]=$respuesta["perfil"];
                    $_SESSION["id_almacen"]=$respuesta["alm_asignado"];
                    
                        /*=============================================
						REGISTRAR FECHA PARA SABER EL ÚLTIMO LOGIN
						=============================================*/

						date_default_timezone_set('America/Mexico_City');

						$fecha = date('Y-m-d');
						$hora = date('H:i:s');

						$fechaActual = $fecha.' '.$hora;

						$item1 = "ultimo_login";
						$valor1 = $fechaActual;

						$item2 = "id";
						$valor2 = $respuesta["id"];
						$logueado=1;

						$ultimoLogin = ModeloUsuarios::mdlActualizarUsuario($tabla, $item1, $valor1, $item2, $valor2, $logueado);     
                      
                      if($ultimoLogin == "ok"){
                        echo '<script> window.location="inicio";</script>';                          
                       }

                  }else{
                    echo '<br><div class="alert alert-danger text-center"> Usuario No Permitido</div>';  
                  }
                }else{
                    echo '<br><div class="alert alert-danger text-center"> Error al Ingresar</div>';
                }
            }
        }
		
    }
 /********************************************* DESLOGUEARSE ***************************************************************** */   
 static public function ctrDesloguearse(){
	$tabla="usuarios" ;
	$id=$_SESSION["id"];
	$desLogin = ModeloUsuarios::mdlDesloguearse($tabla, $id);
                      
	if($desLogin == "ok"){
		return true;
	 }else{
		return false;
	 }

 }	
 /************************************************************************************************************** */   
    
    /* registro de usuario */
    
    static public function ctrCrearusuario(){
        if(isset($_POST["nuevoUsuario"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"]) &&
			   preg_match('/^[a-zA-Z0-9]+$/', $_POST["nuevoUsuario"]) &&
			   preg_match('/^[a-zA-Z0-9]+$/', $_POST["nuevoPassword"])){

                /*=============================================
				VALIDAR IMAGEN
				=============================================*/

				$ruta = "";

				if(isset($_FILES["nuevaFoto"]["tmp_name"])){

					list($ancho, $alto) = getimagesize($_FILES["nuevaFoto"]["tmp_name"]);

					$nuevoAncho = 500;
					$nuevoAlto = 500;

					/*=============================================
					CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
					=============================================*/

					$directorio = "vistas/img/usuarios/".$_POST["nuevoUsuario"];

					mkdir($directorio, 0755);

					/*=============================================
					DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
					=============================================*/

					if($_FILES["nuevaFoto"]["type"] == "image/jpeg"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/usuarios/".$_POST["nuevoUsuario"]."/".$aleatorio.".jpg";

						$origen = imagecreatefromjpeg($_FILES["nuevaFoto"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagejpeg($destino, $ruta);

					}

					if($_FILES["nuevaFoto"]["type"] == "image/png"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/usuarios/".$_POST["nuevoUsuario"]."/".$aleatorio.".png";

						$origen = imagecreatefrompng($_FILES["nuevaFoto"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagepng($destino, $ruta);

					}

				}                
                
                $tabla = "usuarios";
                
                /*$encriptar = crypt($_POST["nuevoPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');*/
                
                $datos = array("nombre" 	=> $_POST["nuevoNombre"],
					           "usuario" 	=> $_POST["nuevoUsuario"],
					           "password" 	=> $_POST["nuevoPassword"],  /* $encriptar */
					           "perfil" 	=> $_POST["nuevoPerfil"],
					           "user" 		=> $_POST["nvoNumTecnico"],
					           "foto"		=> $ruta);
                
                $respuesta = ModeloUsuarios::mdlIngresarUsuario($tabla, $datos);

                if($respuesta == "ok"){

                    echo '<script>                
                        swal({
                        title: "OK",
                        text: "¡El usuario a sido guardado correctamente!",
                        icon: "success",
                        button: "Cerrar",
                       }).then(function(result){
                        if(result){
                            window.location = "usuarios";
                        }
                        });                    
                    </script>'; 
					
                    }else{
						echo '<script>
						
							swal({
								title: "error",
								text: "¡No se pudo guardar Usuario!",
								icon: "warning",
								button: "Cerrar",
								dangerMode: true,
								
							   }).then(function(result){

								if(result){
								
									window.location = "usuarios";

								}

							});                    
						</script>';
						
					}
                
        }else{

				echo '<script>
                
                    swal({
						title: "error",
						text: "¡El usuario no puede ir vacío o llevar caracteres especiales!",
                        icon: "warning",
                        button: "Cerrar",
                        dangerMode: true,
                        
					   }).then(function(result){

						if(result){
						
							window.location = "usuarios";

						}

					});                    
				</script>';

            }        
        }
     }

	/*=============================================
	MOSTRAR USUARIO
	=============================================*/

	static public function ctrMostrarUsuarios($campo, $valor){

		$tabla = "usuarios";

		$respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla, $campo, $valor);

		return $respuesta;
	}
    
/*=============================================
	LISTAR USUARIO ACTIVOS
	=============================================*/

	static public function ctrListarUsuariosActivos($tabla, $item, $valor){

		$respuesta = ModeloUsuarios::MdlListarUsuariosActivos($tabla, $item, $valor);

		return $respuesta;
	}
    
/*=============================================
	EDITAR USUARIO
	=============================================*/

	static public function ctrEditarUsuario(){

		if(isset($_POST["editarUsuario"])){
			if(!isset($_POST['editarPassword']) || strlen(trim($_POST['editarPassword']))==0){
				$editarPassword=$_POST["passwordActual"];
			}else{
				$editarPassword=$_POST["editarPassword"];
			}
				
			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"]) &&
			   preg_match('/^[a-zA-Z0-9]+$/',trim($editarPassword)) ){
						  
				/*=============================================
				VALIDAR IMAGEN
				=============================================*/

				$ruta = $_POST["fotoActual"];

				if(isset($_FILES["editarFoto"]["tmp_name"]) && !empty($_FILES["editarFoto"]["tmp_name"])){

					list($ancho, $alto) = getimagesize($_FILES["editarFoto"]["tmp_name"]);

					$nuevoAncho = 500;
					$nuevoAlto = 500;

					/*=============================================
					CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
					=============================================*/

					$directorio = "vistas/img/usuarios/".$_POST["editarUsuario"];

					/*=============================================
					PRIMERO PREGUNTAMOS SI EXISTE OTRA IMAGEN EN LA BD
					=============================================*/

					if(!empty($_POST["fotoActual"])){

						unlink($_POST["fotoActual"]);

					}else{

						mkdir($directorio, 0755);

					}	

					/*=============================================
					DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
					=============================================*/

					if($_FILES["editarFoto"]["type"] == "image/jpeg"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/usuarios/".$_POST["editarUsuario"]."/".$aleatorio.".jpg";

						$origen = imagecreatefromjpeg($_FILES["editarFoto"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagejpeg($destino, $ruta);

					}

					if($_FILES["editarFoto"]["type"] == "image/png"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/usuarios/".$_POST["editarUsuario"]."/".$aleatorio.".png";

						$origen = imagecreatefrompng($_FILES["editarFoto"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagepng($destino, $ruta);

					}

				}

				$tabla = "usuarios";

				if($_POST["editarPassword"] != ""){

					if(preg_match('/^[a-zA-Z0-9]+$/', trim($_POST["editarPassword"]))){

						$password=$_POST["editarPassword"];
						/*$encriptar = crypt($_POST["editarPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');*/

					}else{

                        echo '<script>
                        swal({
                            title: "error",
                            text: "¡La contraseña no puede ir vacía o llevar caracteres especiales!",
                            icon: "warning",
                            button: "Cerrar",
                            dangerMode: true,

                           }).then(function(result){

                            if(result){

                                window.location = "usuarios";

                            }

                        });                    
                        </script>';
                    return;

					}

				}else{
					$password=$_POST["passwordActual"];
					/*$encriptar = $_POST["passwordActual"];*/
				}

				$datos = array("nombre" 	=> $_POST["editarNombre"],
							   "usuario" 	=> $_POST["editarUsuario"],
							   "password" 	=> $password,     /*$encriptar,*/
							   "perfil" 	=> $_POST["editarPerfil"],
							   "user" 		=> $_POST["editarNumTecnico"],
							   "foto" 		=> $ruta);

				$respuesta = ModeloUsuarios::mdlEditarUsuario($tabla, $datos);

				if($respuesta == "ok"){

                        echo '<script>                
                            swal({
                            title: "OK",
                            text: "¡El usuario a sido editado correctamente!",
                            icon: "success",
                            button: "Cerrar",
                           }).then((result)=>{
                                if(result){
                                   console.log(`The returned value is: ${result}`);
                                   window.location = "usuarios";
                                }else{
                                    console.log("no Entra a recargar "+result);
                                    window.location = "usuarios";
                                }
                          });                    
                        </script>';
				}


			}else{

                        echo '<script>
                        swal({
                            title: "error",
                            text: "¡El nombre o contraseña no puede ir vacía o llevar caracteres especiales!!",
                            icon: "warning",
                            button: "Cerrar",
                            dangerMode: true,

                           }).then((result)=>{

                            if(result){

                                window.location = "usuarios";

                            }

                        });                    
                        </script>';

			}
		//  }
		}

	}    
    
    /*=============================================
	BORRAR USUARIO
	=============================================*/

	static public function ctrBorrarUsuario(){

		if(isset($_GET["idUsuario"])){

			$tabla ="usuarios";
			$datos = $_GET["idUsuario"];

			if($_GET["fotoUsuario"] != ""){

				unlink($_GET["fotoUsuario"]);
				rmdir('vistas/img/usuarios/'.$_GET["usuario"]);

			}

			$respuesta = ModeloUsuarios::mdlBorrarUsuario($tabla, $datos);

			if($respuesta == "ok"){

				echo '<script>                
                            swal({
                            title: "OK",
                            text: "¡El usuario a sido borrado correctamente!",
                            icon: "success",
                            button: "Cerrar",
                           }).then(function(result){
                            if(result){
                                window.location = "usuarios";
                            }
                            });                    
                        </script>';

				
			}		

		}

	}
    
} /*Fin de la clase */
