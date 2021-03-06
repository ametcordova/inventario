<?php

class ControladorProductos{

	/*=============================================
	MOSTRAR PRODUCTOS
	=============================================*/

	static public function ctrMostrarProductos($item, $valor, $orden){

		$tabla = "productos";

		$respuesta = ModeloProductos::mdlMostrarProductos($tabla, $item, $valor, $orden);

		return $respuesta;

	}


	/*=============================================
	CREAR PRODUCTO
	=============================================*/

	static public function ctrCrearProducto(){

		if(isset($_POST["nuevaDescripcion"])){

			if(preg_match('/^[_\#\.\,\-\(\)\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaDescripcion"]) &&
			   preg_match('/^[0-9]+$/', $_POST["nuevoStock"]) &&	
			   preg_match('/^[0-9]+$/', $_POST["nuevoMinimo"]) &&	
			   preg_match('/^[0-9.]+$/', $_POST["nuevoPrecioCompra"]) &&
			   preg_match('/^[0-9.]+$/', $_POST["nuevoPrecioVenta"])){     //que acepte numero y . decimal

				$conseries=0;
				if(isset($_POST['nvoconseries'])){
					$conseries=1;
				};

		   		/*=============================================
				VALIDAR IMAGEN
				=============================================*/

			   	$ruta = "vistas/img/productos/default/default.jpg";

/*				
				//$target_file =basename($_FILES["nuevaImagen"]["name"]);
				if (!file_exists($_FILES['nuevaImagen']['tmp_name'])) {
					echo "File upload failed. ";
					$target_file =basename($_FILES["nuevaImagen"]["name"]);
					echo "File upload. ".$target_file;

					if (isset($_FILES['nuevaImagen']['error'])) {
						 echo "Error code: ".$_FILES['nuevaImagen']['error'];
						 //print_r($_FILES);
					}
					exit;
				}

*/
			
				
			   	if(isset($_FILES["nuevaImagen"]["tmp_name"]) && !empty($_FILES["nuevaImagen"]["tmp_name"])){
					//echo "File upload. ".$target_file;
					list($ancho, $alto) = getimagesize($_FILES["nuevaImagen"]["tmp_name"]);

					$nuevoAncho = 500;
					$nuevoAlto = 500;

					/*=============================================
					CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
					=============================================*/

					$directorio = "vistas/img/productos/".$_POST["nuevoCodigo"];
					
					if (!file_exists($directorio)) {
							mkdir($directorio, 0755, true);
					}
					//mkdir($directorio, 0755);

					/*=============================================
					DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
					=============================================*/

					if($_FILES["nuevaImagen"]["type"] == "image/jpeg"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["nuevoCodigo"]."/".$aleatorio.".jpg";

						$origen = imagecreatefromjpeg($_FILES["nuevaImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagejpeg($destino, $ruta);

					}

					if($_FILES["nuevaImagen"]["type"] == "image/png"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["nuevoCodigo"]."/".$aleatorio.".png";

						$origen = imagecreatefrompng($_FILES["nuevaImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagepng($destino, $ruta);

					}

				}  //fin de subir imagen

				$tabla = "productos";
				/*=============================================
				ARRAY PARA GUARDAR PRODUCTO
				=============================================*/
				$datos = array("id_categoria" 	=> $_POST["nuevaCategoria"],
							   "id_medida"		=> $_POST["nuevaMedida"],
							   "codigo" 		=> $_POST["nuevoCodigo"],
							   "ultusuario" 	=> $_POST["idDeUsuario"],
							   "codigointerno" 	=> $_POST["nuevoCodInterno"],
							   "descripcion" 	=> strtoupper($_POST["nuevaDescripcion"]),
							   "stock" 			=> $_POST["nuevoStock"],
							   "minimo" 		=> $_POST["nuevoMinimo"],
							   "sku" 			=> $_POST["nuevosku"],
							   "precio_compra" 	=> $_POST["nuevoPrecioCompra"],
							   "precio_venta" 	=> $_POST["nuevoPrecioVenta"],
							   "conseries" 		=> $conseries,
							   "imagen" 		=> $ruta);

				$respuesta = ModeloProductos::mdlIngresarProducto($tabla, $datos);
				
				if($respuesta == "ok"){
					
					echo'<script>
						
						swal({
							  icon: "success",
							  title: "El producto ha sido guardado correctamente",
							  button: "Cerrar"
							  }).then(function(result){
										if (result) {

										window.location = "productos";

										}
									})

						</script>';

				}else{
					echo'<script>
						var varjs="'.$respuesta.'";		//convierte variable PHP a JS
						swal({
							  icon: "warning",
							  title: "El producto NO ha sido guardado!!"+varjs,
							  button: "Cerrar",
							  timer: 4000
							  }).then(function(result){
										if (result) {
											window.location = "productos";
										}else{
											window.location = "productos";
										}
									})

						</script>';                    
                }


			}else{

				echo'<script>

					swal({
						  icon: "error",
						  title: "¡El producto no puede ir con los campos vacíos o llevar caracteres especiales!",
						  button: "Cerrar"
						  }).then(function(result){
							if (result) {

							window.location = "productos";

							}
						})

			  	</script>';
			}
		}

	}    
    
    
/*=============================================
	EDITAR PRODUCTO
	=============================================*/

	static public function ctrEditarProducto(){

		if(isset($_POST["editarDescripcion"])){

			if(preg_match('/^[_\#\.\,\-\(\)\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarDescripcion"]) &&
			   preg_match('/^[0-9.]+$/', $_POST["editarStock"]) &&
			   preg_match('/^[0-9.]+$/', $_POST["editarMinimo"]) &&
			   preg_match('/^[0-9.]+$/', $_POST["editarPrecioCompra"]) &&
			   preg_match('/^[0-9.]+$/', $_POST["editarPrecioVenta"])){

				$conseries=0;
				if(isset($_POST['editconseries'])){
					$conseries=1;
				};


		   		/*=============================================
				VALIDAR IMAGEN
				=============================================*/

			   	$ruta = $_POST["imagenActual"];

			   	if(isset($_FILES["editarImagen"]["tmp_name"]) && !empty($_FILES["editarImagen"]["tmp_name"])){

					list($ancho, $alto) = getimagesize($_FILES["editarImagen"]["tmp_name"]);

					$nuevoAncho = 500;
					$nuevoAlto = 500;

					/*=============================================
					CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
					=============================================*/

					$directorio = "vistas/img/productos/".$_POST["editarCodigo"];

					/*=============================================
					PRIMERO PREGUNTAMOS SI EXISTE OTRA IMAGEN EN LA BD
					=============================================*/

					if(!empty($_POST["imagenActual"]) && $_POST["imagenActual"] != "vistas/img/productos/default/default.png"){

						unlink($_POST["imagenActual"]);

					}else{

						mkdir($directorio, 0755);	
					
					}
					
					/*=============================================
					DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
					=============================================*/

					if($_FILES["editarImagen"]["type"] == "image/jpeg"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["editarCodigo"]."/".$aleatorio.".jpg";

						$origen = imagecreatefromjpeg($_FILES["editarImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagejpeg($destino, $ruta);

					}

					if($_FILES["editarImagen"]["type"] == "image/png"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["editarCodigo"]."/".$aleatorio.".png";

						$origen = imagecreatefrompng($_FILES["editarImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagepng($destino, $ruta);

					}

				}else{
					$ruta = "sinimagen";
				}

				$tabla = "productos";

				$datos = array("id_categoria" 	=> $_POST["editarCategoria"],
							   "id" 			=> $_POST["editarIdProducto"],
							   "id_medida" 		=> $_POST["editarMedida"],
							   "codigo" 		=> $_POST["editarCodigo"],
							   "ultusuario" 	=> $_POST["idDeUsuario"],
							   "codigointerno"	=> $_POST["editarCodInterno"],
							   "descripcion" 	=> strtoupper($_POST["editarDescripcion"]),
							   "stock" 			=> $_POST["editarStock"],
							   "minimo" 		=> $_POST["editarMinimo"],
							   "sku" 			=> $_POST["editarsku"],
							   "precio_compra" 	=> $_POST["editarPrecioCompra"],
							   "precio_venta" 	=> $_POST["editarPrecioVenta"],
							   "conseries" 		=> $conseries,
							   "imagen" 		=> $ruta);

				$respuesta = ModeloProductos::mdlEditarProducto($tabla, $datos);

				if($respuesta == "ok"){
					echo'<script>

						swal({
							  icon: "success",
							  title: "El producto ha sido editado correctamente",
							  button: "Cerrar",
							  timer: 4000
							  }).then((result)=>{
										if (result) {
											window.location = "productos";
										}else{
											window.location = "productos";
										}	
									})

						</script>';

				}


			}else{

				echo'<script>

					swal({
						  icon: "error",
						  title: "¡El producto no puede ir con los campos vacíos o llevar caracteres especiales!",
						  button: "Cerrar"
						  }).then((result)=>{
							if (result) {
							window.location = "productos";
							}
						})

			  	</script>';
			}
		}

	}    

    
/*=============================================
	BORRAR PRODUCTO
	=============================================*/
	static public function ctrEliminarProducto(){

		if(isset($_GET["idProducto"])){

			$tabla ="productos";
			$datos = $_GET["idProducto"];
            
            /*
            //DESCOMENTAR SI SE NECESITA BORRAR EL DIRECTORIO DE LA IMAGEN
			if($_GET["imagen"] != "" && $_GET["imagen"] != "vistas/img/productos/default/default.png"){

				unlink($_GET["imagen"]);
				rmdir('vistas/img/productos/'.$_GET["codigo"]);

			}
            */
            
			$respuesta = ModeloProductos::mdlEliminarProducto($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  icon: "success",
					  title: "El producto ha sido borrado correctamente",
					  button: "Cerrar"
					  }).then((result)=>{
								if (result) {

								window.location = "productos";

								}
							})

				</script>';

			}		
		}


	}    
    
	/*=============================================
	MOSTRAR SUMA VENTAS
	=============================================*/
/*
	static public function ctrMostrarSumaVentas(){

		$tabla = "productos";

		$respuesta = ModeloProductos::mdlMostrarSumaVentas($tabla);

		return $respuesta;

	}

*/
}