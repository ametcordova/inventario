<?php

class ControladorEntradas{

	/*=============================================
	CREAR REGISTRO DE ENTRADAS AL ALMACEN
	=============================================*/

	static public function ctrCrearEntrada(){

		if(isset($_POST["idProducto"])){

			if(preg_match('/^[_\#\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["numeroDocto"])){
                
                //EXTRAE EL NOMBRE DEL ALMACEN
				$tabla =trim(substr($_POST['nuevoAlmacen'],strpos($_POST['nuevoAlmacen'].'-','-')+1)); 
                $tabla=strtolower($tabla);
                //EXTRAE EL NUMERO DE ALMACEN
                $id_almacen=strstr($_POST['nuevoAlmacen'],'-',true);   
                
                //extrae el numero de almacen
                //$id_almacen=substr($_POST['nuevoAlmacen'],0, strpos($_POST['nuevoAlmacen'], '-',1));
                
			$respuesta = ModeloEntradas::mdlIngresarEntrada($tabla,$_POST["nuevoProveedor"],$_POST["fechaDocto"],$_POST["numeroDocto"],$_POST["nombreRecibe"], $_POST["NuevoTipoEntrada"], $_POST["idProducto"],$_POST["codigointerno"],$_POST["cantidad"],$_POST["fechaEntrada"],$id_almacen,$_POST["idDeUsuario"] );

				if($respuesta == "ok"){
                    
                    echo '<script>                
                     var varjs="'.$tabla.'";		//convierte variable PHP a JS
                        swal({
                        title: "Guardado en "+varjs,
                        text: "Entrada a sido guardado correctamente!",
                        icon: "success",
                        button: "Cerrar",
                       }).then(function(result){
                        if(result){
                            window.location = "inicio";
                        }
                        });                    
                    </script>'; 
				}else{
                    
                    echo '<script>                
                        swal({
                        title: "Error",
                        text: "Entrada no a sido guardado!",
                        icon: "warning",
                        button: "Cerrar",
                       }).then(function(result){
                        if(result){
                            //window.location = "entradas";
                        }
                        });                    
                    </script>'; 
                    
                }


			}else{

				echo'<script>

					swal({
						  title: "error",
						  text: "¡NO. de Documento no puede ir vacío o llevar caracteres especiales!",
						  icon:"error",
						  button: "Cerrar"
						  }).then(result)=>{
							if (result) {
                                //window.location = "entradas";
							}
						})
			  	</script>';

			}

		}

	}
	
	/*=============================================
	VALIDA QUE NUMERO DE DOCTO NO SE REPITA
	============================================*/

	static public function ctrValidarDocto($item, $valor){

		$tabla = "hist_entrada";

		$respuesta = ModeloEntradas::MdlValidarDocto($tabla, $item, $valor);

		return $respuesta;
	
	}  
	
	
	/*=============================================
	REPORTE DE ENTRADAS
	============================================*/

	static public function ctrEntradaAlm($item, $valor){

		$tabla = "hist_entrada";

		$respuesta = ModeloEntradas::MdlEntradaAlm($tabla, $item, $valor);

		return $respuesta;
	
	}  
		
	/*=============================================
	MOSTRAR ENTRADAS
	============================================*/
	static public function ctrMostrarEntradaAlm(){

		$tabla = "hist_entrada";

		$respuesta = ModeloEntradas::MdlMostrarEntradaAlm($tabla);

		return $respuesta;
	
	}  


	
	
}	//fin de la clase
