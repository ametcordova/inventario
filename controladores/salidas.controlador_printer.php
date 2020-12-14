<?php

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class ControladorSalidas{

	/*=============================================
	CREAR REGISTRO DE ENTRADAS AL ALMACEN
	=============================================*/

	static public function ctrCrearSalida(){

		if(isset($_POST["idProducto"])){

			if(preg_match('/^[_\#\.\,\-\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["numeroSalidaAlm"])){
                
				$numSalidaAlmacen=trim($_POST["numeroSalidaAlm"]);
				
                //EXTRAE EL NOMBRE DEL ALMACEN
				$tabla =trim(substr($_POST['nuevaSalidaAlmacen'],strpos($_POST['nuevaSalidaAlmacen'].'-','-')+1)); 
                
                //EXTRAE EL NUMERO DE ALMACEN
                $id_almacen=strstr($_POST['nuevaSalidaAlmacen'],'-',true);   
                
				$respuesta = ModeloSalidas::mdlIngresarSalida($tabla,$_POST["nuevoCliente"],$_POST["fechaSalida"],$numSalidaAlmacen,$_POST["idProducto"],$_POST["cantidad"],$_POST["precio_venta"],$id_almacen,$_POST["nuevoTipoSalida"],$_POST["idDeUsuario"] );
                
				if($respuesta == "ok"){
 
//======================= EMPIEZA IMPRESION DE TICKET ===========================    

                  
             
//======================= TERMINA IMPRESION DE TICKET ===========================                    
                    
                    echo '<script>                
                     var varjs="'.$numSalidaAlmacen.'";		//convierte variable PHP a JS
                        swal({
                        title: "Guardado Salida No. "+varjs,
                        text: "Salida a sido guardado correctamente!",
                        icon: "success",
                        button: "Cerrar",
                       }).then(function(result){
                        if(result){
                            window.location = "salidas";
							//window.open("extensiones/tcpdf/pdf/imprimir_salida.php?codigo="+varjs, "_blank");
							window.open("ticket/index.php");
                        }
                        });                    
                    </script>'; 
				}else{
                    
                    echo '<script>                
                        swal({
                        title: "Error",
                        text: "Salida NO a sido guardado!",
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
	OBTENER EL ULTIMO NUMERO DE DOCTO 
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