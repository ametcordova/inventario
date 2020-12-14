<?php
class ControladorSalidas{
	
//echo 'si entra';
	/*=============================================
	CREAR REGISTRO DE ENTRADAS AL ALMACEN
	=============================================*/
	
	static public function ctrCrearSalida(){
	
		//echo 'si entra';

		if(isset($_POST["idProducto"])){

			if(preg_match('/^[_\#\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["numeroSalida"])){
                
				$numSalidaAlmacen=trim($_POST["numeroSalida"]);
				
                //EXTRAE EL NOMBRE DEL ALMACEN
				$tabla =trim(substr($_POST['nuevaSalidaAlmacen'],strpos($_POST['nuevaSalidaAlmacen'].'-','-')+1)); 
				$tabla=strtolower($tabla);
                //EXTRAE EL NUMERO DE ALMACEN
                $id_almacen=strstr($_POST['nuevaSalidaAlmacen'],'-',true);   
                
                //extrae el numero de almacen
                //$id_almacen=substr($_POST['nuevaSalidaAlmacen'],0, strpos($_POST['nuevaSalidaAlmacen'], '-',1));
				
				$respuesta = ModeloSalidas::mdlIngresarSalida($tabla,$_POST["nuevoTecnico"],$_POST["fechaSalida"],$_POST["numeroSalida"],$_POST["idProducto"],$_POST["cantidad"],$_POST["precio_venta"],$id_almacen,$_POST["nuevoTipoSalida"],$_POST["idDeUsuario"] );
                
				if($respuesta == "ok"){
                    
                    echo '<script>                
                     var varjs="'.$numSalidaAlmacen.'";		//convierte variable PHP a JS
                        swal({
                        title: "Guardado Salida No. "+varjs,
                        text: "Salida a sido guardado correctamente!",
                        icon: "success",
                        button: "Cerrar",
                       }).then(function(result){
                        if(result){
                            window.location = "inicio";
							window.open("extensiones/tcpdf/pdf/imprimir_salida.php?codigo="+varjs, "_blank");
                        }
                        });                    
                    </script>'; 
				}else{
                    
                    echo '<script>                
                        swal({
                        title: "Error",
                        text: "Salida no a sido guardado!",
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
						  text: "¡No. de Salida no puede ir vacío o llevar caracteres especiales!",
						  icon:"error",
						  button: "Cerrar"
						  }).then(result)=>{
							if (result) {
                                //window.location = "inicio";
							}
						})
			  	</script>';

			}

		}

	}
    
    
	/*=============================================
	VALIDA QUE NUMERO DE DOCTO NO SE REPITA
	=============================================*/

	static public function ctrValidarNumSalida($item, $valor){

		$tabla = "hist_salidas";

		$respuesta = ModeloSalidas::MdlValidarNumSalida($tabla, $item, $valor);

		return $respuesta;
	
	}      

	/*=============================================
	OBTENER EL ULTIMO NUMERO DE SALIDA
	=============================================*/

	static public function ctrAsignarNumSalida($item, $valor){

		$tabla = "hist_salidas";

		$respuesta = ModeloSalidas::MdlAsignarNumSalida($tabla, $item, $valor);

		return $respuesta;
	
	}      
	
	
	/*=============================================
	REPORTE NOTA DE SALIDAS
	============================================*/

	static public function ctrSalidaAlm($item, $valor){

		$tabla = "hist_salidas";

		$respuesta = ModeloSalidas::MdlSalidaAlm($tabla, $item, $valor);

		return $respuesta;
	
	}  	
	
	/*=============================================
	MOSTRAR SALIDAS AL ALMACEN
	============================================*/

	static public function ctrMostrarSalidas($tabla, $item, $valor){


		$respuesta = ModeloSalidas::MdlMostrarSalidas($tabla, $item, $valor);

		return $respuesta;
	
	}  	
	
	
}	//fin de la clase	