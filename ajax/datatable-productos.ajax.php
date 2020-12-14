<?php
session_start();
require_once "../controladores/productos.controlador.php";
require_once "../controladores/categorias.controlador.php";
require_once "../controladores/medidas.controlador.php";

require_once "../modelos/productos.modelo.php";
require_once "../modelos/categorias.modelo.php";
require_once "../modelos/medidas.modelo.php";

require_once "../controladores/permisos.controlador.php";
require_once "../modelos/permisos.modelo.php";
require_once '../funciones/funciones.php';

class TablaProductos{

 	/*=============================================
 	 MOSTRAR LA TABLA DE PRODUCTOS
  	=============================================*/ 

	public function mostrarTablaProductos(){

		$tabla="usuarios";
		$module="pproductos";
		$campo="catalogo";
		$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);    
	
		$item = null;
    	$valor = null;
    	$orden = "id";

  		$productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);	

  		if(count($productos) == 0){

  			echo '{"data": []}';

		  	return;
  		}
		
  		$datosJson = '{
		  "data": [';

		  for($i = 0; $i < count($productos); $i++){

		  	/*=============================================
 	 		TRAEMOS LA IMAGEN
  			=============================================*/ 
			
				$imagen = "<a href='#' data-toggle='modal' data-target='#modal' ><img src='".$productos[$i]["imagen"]."' class='idImagen' width='40px' descripcionProd='".$productos[$i]["descripcion"]."' codigointerno='".$productos[$i]["codigointerno"]."'> </a>";

		  	/*=============================================
 	 		TRAEMOS LA CATEGORÍA
  			=============================================*/ 
		  	$item = "id";
		  	$valor = $productos[$i]["id_categoria"];
		  	$categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);

		  	/*=============================================
 	 		STOCK
  			=============================================*/ 
			$minimo=round($productos[$i]["minimo"],0); 
			$media=($productos[$i]["minimo"]/2);

  			if($productos[$i]["stock"] <= $media){

  				$stock = "<button class='btn btn-danger btn-sm' title='Mínimo $minimo'>".$productos[$i]["stock"]."</button>";

  			}else if($productos[$i]["stock"] > $media && $productos[$i]["stock"] <= $minimo){

  				$stock = "<button class='btn btn-warning btn-sm' title='Mínimo $minimo'>".$productos[$i]["stock"]."</button>";

  			}else{

  				$stock = "<button class='btn btn-success btn-sm' title='Mínimo $minimo'>".$productos[$i]["stock"]."</button>";

  			}

		  	/*=============================================
 	 		TRAEMOS LA UNIDAD DE MEDIDA
  			=============================================*/ 
		  	$item = "id";
		  	$valor = $productos[$i]["id_medida"];
		  	$medidas = ControladorMedidas::ctrMostrarMedidas($item, $valor);      
              
		  	/*=============================================
 	 		TRAEMOS LAS ACCIONES
  			=============================================*/ 

  			// if(isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Especial"){

  			// 	$botones =  "<div class='btn-group'><button class='btn btn-warning btn-sm btnEditarProducto' idProducto='".$productos[$i]["id"]."' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button></div>"; 

  			// }else{

  			// 	 $botones =  "<div class='btn-group'><button class='btn btn-warning btn-sm btnEditarProducto' idProducto='".$productos[$i]["id"]."' data-toggle='modal' data-target='#modalEditarProducto' title='Editar'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btn-sm btnEliminarProducto' idProducto='".$productos[$i]["id"]."' codigo='".$productos[$i]["codigo"]."' imagen='".$productos[$i]["imagen"]."' title='Borrar'><i class='fa fa-trash-o'></i></button></div>"; 

			// }
			  
			$boton1=getAccess($acceso, ACCESS_EDIT)?"<button class='btn btn-warning btn-sm btnEditarProducto' idProducto='".$productos[$i]["id"]."' data-toggle='modal' data-target='#modalEditarProducto' title='Editar'><i class='fa fa-pencil'></i></button> ":"";
			$boton2=getAccess($acceso, ACCESS_DELETE)?"<button class='btn btn-danger btn-sm btnEliminarProducto' idProducto='".$productos[$i]["id"]."' codigo='".$productos[$i]["codigo"]."' imagen='".$productos[$i]["imagen"]."' title='Borrar'><i class='fa fa-trash-o'></i></button>":"";

			$botones=$boton1.$boton2;

		    $fechaAgregado = date('d-m-Y', strtotime($productos[$i]["fecha"]));
            //$compra = "$".number_format($productos[$i]["precio_compra"], 2, '.',',');
            //$venta = "$".number_format($productos[$i]["precio_venta"], 2, '.',',');
		  	$datosJson .='[
			      "'.($i+1).'",
			      "'.$imagen.'",
			      "'.$productos[$i]["codigointerno"].'",
			      "'.$productos[$i]["descripcion"].'",
			      "'.$categorias["categoria"].'",
			      "'.$stock.'",
                  "'.$medidas["medida"].'",
			      "'.$fechaAgregado.'",
			      "'.$botones.'"
			    ],';

		  }

		  $datosJson = substr($datosJson, 0, -1);

		 $datosJson .=   '] 

		 }';
		
		echo $datosJson;


	}


}

/*=============================================
ACTIVAR TABLA DE PRODUCTOS
=============================================*/ 
$activarProductos = new TablaProductos();
$activarProductos -> mostrarTablaProductos();




