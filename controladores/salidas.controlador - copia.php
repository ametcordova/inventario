<?php
//require __DIR__ . '/autoload.php'; 
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class ControladorSalidas{

    
/*===============================================================
	CREAR REGISTRO DE ENTRADAS AL ALMACEN
===============================================================*/

	static public function ctrCrearSalida(){
		//$var_php = $_POST['printTicket'];
		//if(isset($_POST['printticket'])){
		//$dir = getcwd();
		if($_SERVER["REQUEST_METHOD"]=="POST" AND isset($_POST["idProducto"])){

			if(preg_match('/^[0-9]+$/', $_POST["numeroSalidaAlm"])){
                
				//$numSalidaAlmacen=trim($_POST["numeroSalidaAlm"]);

				// OBTIENE EL ULTIMO NUMERO DE SALIDA
				$tablaSalida = "hist_salidas";
				$item = "num_salida";
				$valor = "";
				$respuesta = ModeloSalidas::MdlAsignarNumSalida($tablaSalida, $item, $valor);
				$numSalidaAlmacen=$respuesta[0];

				if(is_null($numSalidaAlmacen)){
					$numSalidaAlmacen=1;
				}

                //EXTRAE EL NOMBRE DEL ALMACEN
				$tabla =trim(strtolower(substr($_POST['nuevaSalidaAlmacen'],strpos($_POST['nuevaSalidaAlmacen'].'-','-')+1))); 
				$tipodeventa=trim($_POST['tipodeventa']);
                
                //EXTRAE EL NUMERO DE ALMACEN
                $id_almacen=strstr($_POST['nuevaSalidaAlmacen'],'-',true);   
                
				$respuesta = ModeloSalidas::mdlIngresarSalida($tabla,filtrado($_POST["nuevoCliente"]), $_POST["fechaSalida"],$numSalidaAlmacen, $_POST["idcaja"], $_POST["espromo"], $_POST["idProducto"], $_POST["cantidad"], $_POST["precio_venta"], $id_almacen, $_POST["nuevoTipoSalida"],$_POST["nuevotipovta"], $_POST["idDeUsuario"], $_POST["pagocliente"] );

				if($respuesta == "ok"){

				 if(isset($_POST["printicket"]) &&  $_POST["printicket"]=="1"){
					
					//include "config/parametros.php";
					//$itemSalida = "num_salida";
					//$numdeSalida = $_GET["codigo"];
					
					$respuestaAlmacen = ModeloSalidas::MdlSalidaAlm($tablaSalida, $item, $numSalidaAlmacen);
					
					$nomCliente=$respuestaAlmacen[0]["nombrecliente"];
					$usuario=$respuestaAlmacen[0]["nombreusuario"];
					$entregaen=$respuestaAlmacen[0]["id_tipovta"]==0?"Entrega en: Mostrador":"Entrega a: Domicilio";
					$fechaSalida=date("d-m-Y H:i:s", strtotime($respuestaAlmacen[0]["ultmodificacion"]));
					
					if($respuestaAlmacen){
						$pagocon=$_POST["pagocliente"];		//PAGO DEL CLIENTE

						//$impresora = "EC-PM-5890X";		//HERNAN
						//$impresora = "XP-58";				//@kordova

						$impresora=defined('IMPRESORA')?IMPRESORA:'';
					
						$conector = new WindowsPrintConnector($impresora);
						$printer = new Printer($conector);
					
							
						$printer -> setJustification(Printer::JUSTIFY_CENTER);
						/*
							Intentaremos cargar e imprimir
							el logo
						*/
						try{
							$logo = EscposImage::load("config/logoticket.png", false);
							$printer -> bitImage($logo, Printer::IMG_DOUBLE_WIDTH);
							$printer -> feed();
						}catch(Exception $e){/*No hacemos nada si hay error*/}
					
						$printer -> setTextSize(1, 2);
						$printer -> setEmphasis(true);  
							
						$printer -> text(RAZON_SOCIAL."\n");//Nombre de la empresa
							
						$printer -> setEmphasis(false);
						$printer -> setTextSize(1, 1);
							
						$printer -> text(DIRECCION."\n");//Dirección de la empresa
						$printer -> text("RFC:".RFC."\n");//tel de la empresa
						$printer -> text("TEL:".TELEFONO."\n");//tel de la empresa
						if(SLOGAN!=""){
							$printer -> text(SLOGAN."\n");	//
						}
						$printer -> text("F.Venta:".$fechaSalida."\n");//Fecha de venta
					
						$printer -> text("Ticket #".$numSalidaAlmacen."\n");//Número de ticket
					
						$printer -> feed(1); //Alimentamos el papel 1 vez*/
					
						$printer -> text("Cliente: ".$nomCliente."\n");//Nombre del cliente
					
						$printer -> text("Atendio: ".$usuario."\n");//Nombre del vendedor
						$printer->text("-------------------------------\n");
					
						$cantSalida=$granTotal=$sumtotal=0;
						foreach ($respuestaAlmacen as $row) {
						
							//$sumatotal=number_format($row["cantidad"]*$row["precio_venta"],2);
							if($row["es_promo"]==0){
								$sumtotal=($row["cantidad"]*$row["precio_venta"]); 
								$descripcion=trim(substr($row["descripcion"],0,36));
							}else{
								$sumtotal=$row["precio_venta"];   
								$descripcion=trim(substr($row["descripcion"],0,36)."*");
							}
							$printer->setJustification(Printer::JUSTIFY_LEFT);
							$printer->text($descripcion."\n");       //Nombre del producto
							$printer->setJustification(Printer::JUSTIFY_RIGHT);
							$printer->text($row["cantidad"]." x $".$row["precio_venta"]." = $".number_format($sumtotal,2)."\n");
							if(strlen($row["leyenda"])>0){
								$printer->setJustification(Printer::JUSTIFY_LEFT);
								$printer -> setEmphasis(true);
								$printer->text($row["leyenda"]."\n");
								$printer -> setEmphasis(false);
							}
							$granTotal+=$sumtotal;
						}

							$cambiode=$pagocon<>0?($pagocon-$granTotal):0;   //IMPORTE DEL CAMBIO AL CLIENTE
							
							//$printer -> feed(1); //Alimentamos el papel 1 vez	
							//$printer->text("NETO: $ ".number_format($_POST["nuevoPrecioNeto"],2)."\n"); //ahora va el neto
							//$printer->text("IMPUESTO: $ ".number_format($_POST["nuevoPrecioImpuesto"],2)."\n"); //ahora va el impuesto
							$printer->setJustification(Printer::JUSTIFY_RIGHT);
							$printer->text("---------\n");
							$printer->text("TOTAL: $ ".number_format($granTotal,2)."\n");
							$printer->text(" PAGO: $ ".number_format($pagocon,2)." = CAMBIO: $".number_format($cambiode,2)."\n");
							//$printer->text(" PAGO: $ ".number_format($pagocon,2)."\n");
							//$printer->text("CAMBIO:$ ".number_format($cambiode,2)."\n");
														
							$printer -> feed(1); 
							$printer -> setJustification(Printer::JUSTIFY_CENTER);
							$printer->text(FOOTER."\n"); //Podemos poner también un pie de página
						
							$printer -> feed(1); //Alimentamos el papel 1 vez
							$printer->text(LEYENDA."\n"); 
							$printer -> feed(1); 	//Alimentamos el papel 3 veces
							$printer->text($tipodeventa); 
							$printer -> feed(1); 	//Alimentamos el papel 3 veces
							$printer->text($entregaen."\n"); 							
							//$printer -> feed(2); 	//Alimentamos el papel 3 veces
							$printer -> cut(); 
							$printer -> close();

						echo '<script>  
							window.location = "salidas";	
						</script>';
					
					}


				 }else{
					echo '<script>  
						window.location = "salidas";	
					</script>';

				 }

				//   else{
                //     echo '<script>  
                //     	var varjs="'.$numSalidaAlmacen.'";		//convierte variable PHP a JS
                //         swal({
                //         title: "Guardado! Ticket No. "+varjs,
                //         text: "Venta a sido guardado correctamente!",
                //         icon: "vistas/img/logoaviso.png",
				// 		button: "Cerrar",
				// 		timer: 6000,
                //         })
                //         .then((aceptado) => {
                //           if (aceptado) {
                //               window.location = "salidas";
                //           }else{
                //               window.location = "salidas";
                //           }
                //         }); 
                //     </script>';
                //   }  
				}else{
                    
					echo "<script>           
					
					Swal.fire({
						title: '¡Error!',
						text: 'Venta NO ha sido guardada!',
						icon: 'error',
						confirmButtonText: 'Entendido'
					  }).then(function(result){
						if (result) {
							window.location = 'salidas';
						}
					});
					
                    </script>"; 
                    
                }


			}else{

				echo "<script>

				Swal.fire({
					title: '¡Error!',
					text: '¡No. de Salida no puede ir vacío o llevar caracteres especiales!',
					icon: 'error',
					timer: 4000,
					confirmButtonText: 'Entendido'
				}).then(function(result){
						if (result) {
							window.location = 'inicio';
						}
					}) 


			  	</script>";

			}

		}

	} //fin 
        
    
/*=============================================================== */
static public function ctrProdEliminar($tabla, $item, $valor, $idUsuario){

 $respuesta = ModeloSalidas::mdlMostrarProdEliminar($tabla, $item, $valor);
 if($respuesta){
	$getUltNumCancelado=ModeloSalidas::mdlgetUltNumCancelado();
	$num_cancelacion=$getUltNumCancelado[0]["num_cancelacion"];
	if($num_cancelacion==0){
		$num_cancelacion=1;
	}

	$idcliente=$respuesta[0]["id_cliente"];
    $numsalida=$respuesta[0]["num_salida"];
    $fechasalida=$respuesta[0]["fecha_salida"];
	$idalmacen=$respuesta[0]["id_almacen"];
	$espromo=$respuesta[0]["es_promo"];
	$tablasal=trim(strtolower($respuesta[0]["nombre"]));
	$item="id_producto";
	foreach($respuesta as $key => $value){
	   
	   $espromo=$value["es_promo"];
	   $valor1=$value["id_producto"];
	   $valor2=$value["cantidad"];
	   $prevta=$value["precio_venta"];
        
        if($resp=ModeloSalidas::MdlGuardaCancelado($idcliente, $num_cancelacion, $numsalida, $fechasalida, $valor1, $valor2, $prevta, $idalmacen, $espromo, $idUsuario)){     //Guarda los productos cancelados
			$resp1=ModeloSalidas::MdlActualizaAlmacen($tablasal, $item, $valor1, $valor2, $idUsuario);   //actualiza tabla 'almacen'
		   if ($resp1){
			 $tablaprod="productos";
				$resp2=ModeloSalidas::MdlActualizaProductos($tablaprod, $valor1, $valor2, $idUsuario);	//actualiza tabla productos
		   }
			
			if($resp2){
			  $resp3 = ModeloSalidas::mdlProdEliminar($tabla, $numsalida, $valor1 );
			}
		};   
	}  //fin del foreach	
	 return $respuesta;
 }else{	
	return false;
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
	

/*=================MOSTRAR TIPO MOV DE SALIDA ================================ */
static public function ctrMostrarTipoMovs($tabla, $item, $valor){

	$respuesta = ModeloSalidas::MdlMostrarTipoMovs($tabla, $item, $valor);
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
	MOSTRAR CON CUANTO PAGO EL CLIENTE
============================================*/

static public function ctrCobroVenta($valor){

	$tabla = "cobrosdeventas";
	$item = "id_ticket";
	$respuesta = ModeloSalidas::MdlCobroVenta($tabla, $item, $valor);

	return $respuesta;

}  	

/*=============================================
	MOSTRAR SALIDAS AL ALMACEN
============================================*/

	static public function ctrMostrarSalidas($tabla, $item, $valor, $fechaSel){

		$respuesta = ModeloSalidas::MdlMostrarSalidas($tabla, $item, $valor, $fechaSel);

		return $respuesta;
	
	}  	
	
	
/*============================================================
MOSTRAR VENTAS SIN ENVASES Y SIN SERVICIOS
==============================================================*/
static public function ctrSumaTotalVentas($tabla, $item, $valor,$cerrado, $fechacutvta){

	$respuesta = ModeloSalidas::mdlSumaTotalVentas($tabla, $item, $valor, $cerrado, $fechacutvta);

	return $respuesta;

}     
/*=============================================
MOSTRAR VENTAS ENVASES
=============================================*/
static public function ctrSumTotVtasEnv($tabla, $item, $valor,$cerrado, $fechacutvta){

	$respuesta = ModeloSalidas::mdlSumTotVtasEnv($tabla, $item, $valor,$cerrado, $fechacutvta);

	return $respuesta;

}     
/*=============================================
MOSTRAR VENTAS SERVICIOS
=============================================*/
static public function ctrSumTotVtasServ($tabla, $item, $valor,$cerrado, $fechacutvta){

	$respuesta = ModeloSalidas::mdlSumTotVtasServ($tabla, $item, $valor,$cerrado, $fechacutvta);

	return $respuesta;

}     
/*=============================================
MOSTRAR VENTAS DE OTROS
=============================================*/
static public function ctrSumTotVtasOtros($tabla, $item, $valor,$cerrado, $fechacutvta){

	$respuesta = ModeloSalidas::mdlSumTotVtasOtros($tabla, $item, $valor,$cerrado, $fechacutvta);

	return $respuesta;

}     

/*============================================================
MOSTRAR VENTAS A CREDITO
==============================================================*/
static public function ctrSumTotVtasCred($tabla, $item, $valor,$cerrado, $fechacutvta){

	$respuesta = ModeloSalidas::mdlSumTotVtasCred($tabla, $item, $valor, $cerrado, $fechacutvta);

	return $respuesta;

}     

/*=============================================
MOSTRAR CANT TOTAL VENTAS 
=============================================*/
static public function ctrCantTotalVentas($tabla, $item, $valor){

	$respuesta = ModeloSalidas::mdlCantTotalVentas($tabla, $item, $valor);

	return $respuesta;

}     

/*=============================================
VENTAS ULTIMOS 7 DIAS
=============================================*/
static public function ctrVtaUlt7Dias($tabla, $item, $valor){

	$respuesta = ModeloSalidas::mdlVtaUlt7Dias($tabla, $item, $valor);

	return $respuesta;

}     

/*=============================================
COMPRAS ULTIMOS 7 DIAS
=============================================*/
static public function ctrComprasUlt7Dias($tabla, $item, $valor){

	$respuesta = ModeloSalidas::mdlComprasUlt7Dias($tabla, $item, $valor);

	return $respuesta;

}     
/*=============================================

/*=============================================
VENTAS ULTIMOS 12 MESES 
=============================================*/
static public function ctrVtaUlt12Meses($tabla, $item, $valor){

	$respuesta = ModeloSalidas::mdlVtaUlt12Meses($tabla, $item, $valor);

	return $respuesta;

}     

/*=============================================
RANGO FECHAS
=============================================*/	

static public function ctrRangoFechasVentas($fechaInicial, $fechaFinal){

	$tabla = "hist_salidas";

	$respuesta = ModeloSalidas::mdlRangoFechasVentas($tabla, $fechaInicial, $fechaFinal);

	return $respuesta;
		
}
    
/*=============================================
CIERRE DE CAJA DE VENTA DEL DIA
=============================================*/
static public function ctrCierreDia($tabla, $id_caja, $id_corte, $id_fecha){

    $respuesta = ModeloSalidas::mdlCierreDia($tabla, $id_caja, $id_corte, $id_fecha);

	return $respuesta;

}     
   
/*=============================================
CIERRE DE CAJA DE VENTA DEL DIA FORZOSO
=============================================*/
static public function ctrCierreForzoso($tabla, $id_caja, $id_corte, $id_fecha){

    $respuesta = ModeloSalidas::mdlCierreForzoso($tabla, $id_caja, $id_corte, $id_fecha);

	return $respuesta;

}     

/*=============================================
TRAER PRODUCTOS
=============================================*/
static public function ctrQuerydeProductos($tabla, $item, $valor, $estado){

    $respuesta = ModeloSalidas::mdlQuerydeProductos($tabla, $item, $valor, $estado);

	return $respuesta;

}     

/*=============================================
    LISTAR CANCELACIONES
============================================*/
static public function ctrListarCancelaciones($item, $valor, $orden, $fechadev1, $fechadev2){

	$tabla = "cancela_ventas";

	$respuesta = ModeloSalidas::mdlListarCancelaciones($tabla, $item, $valor, $orden, $fechadev1, $fechadev2);

	return $respuesta;

}    
/*=============================================*/

/*=============================================
	REPORTE DE CANCELACIONES DE VENTA
============================================*/

static public function ctrImprimirCancelacion($campo, $valor){

	$tabla = "cancela_venta";

	$respuesta = ModeloSalidas::MdlImprimirCancelacion($tabla, $campo, $valor);

	return $respuesta;

}  	


}	//fin de la clase	


function filtrado($datos){
    $datos = trim($datos); // Elimina espacios antes y después de los datos
    $datos = stripslashes($datos); // Elimina backslashes \
    $datos = htmlspecialchars($datos); // Traduce caracteres especiales en entidades HTML
    return $datos;
}   

/*
var varjs="'.$numSalidaAlmacen.'";		//convierte variable PHP a JS
swal({
title: "Guardado! Ticket No. "+varjs,
text: "Venta a sido guardado correctamente!",
icon: "vistas/img/logoaviso.png",
buttons: ["Cerrar", "Imprimir Ticket"],
timer: 8000,
})
.then((aceptado) => {
if (aceptado) {
window.open("extensiones/tcpdf/pdf/imprimir_ticket.php?codigo="+varjs, "_blank","top=200,left=500,width=400,height=400");
window.location = "salidas";
}else{
window.location = "salidas";
}
}); 
*/