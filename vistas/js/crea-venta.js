//$("#agregarProd").hide();
var cantexiste=0;
var cantSolicitada=0;
var precioVenta=0;
var udeMedida="";
var idProducto=0;
var lDuplicado=false;
let conta=1;
var json;
var result;
var sinpromo=false;
var nPromo=90;
var $eventLog = $("#mensajerror");
var detalles=0;
$("#btnGuardar").hide();
$("#saveRegTick").hide();


$('#cantSalida').on('keyup blur change', function(ev) {

	ev.preventDefault();
	let cantcapturada=$("#cantSalida").val();
	cantcapturada=parseInt(cantcapturada);	
	if(cantcapturada<1){
		//console.log("entra",cantcapturada);
		$("#cantSalida").val(1);
		return false;
	}else if(isNaN(cantcapturada)){
		//console.log("si entra",cantcapturada);
		$("#cantSalida").val(0);
		$("input#cantSalida").focus();
		return false;
	}
})

/* FUNCIONA
$('body').keyup(function(e) {
    if(e.keyCode == 27) {
        alert('Has presionado ESC');
    }
});
*/


$(":input#precioValor").prop('disabled', true);

$("#botonVta").click(function(event) {
	event.preventDefault();	
	var link = $(this).attr('href');	
		notificacion();
	$("#botonVta").unbind( "#botonVta");	
	window.location = link;	
});


function notificacion(){
	Push.create("Mensaje!!", {
		body: "Existen productos con stock bajo!",
		icon: 'config/favicon.png',
		timeout: 4000,
		onClick: function () {
			//window.focus();
			window.location="almacen";
			this.close();
		}
	});
}
	

$('#promocion').on('ifChanged', function(event) {
    if( $(this).is(':checked') ){
        // Hacer algo si el checkbox ha sido seleccionado
		$(":input#precioValor").prop('disabled', false);
        //console.log("El checkbox con valor " + $(this).val() + " ha sido seleccionado");
    } else {
        // Hacer algo si el checkbox ha sido deseleccionado
		$(":input#precioValor").prop('disabled', true);
        //console.log("El checkbox con valor " + $(this).val() + " ha sido deseleccionado");
    }
});


// DESELECCIONA ALMACEN PARA EVITAR CAMBIARLO
$("#nuevaSalidaAlmacen").change(function(){
    $('#nuevaSalidaAlmacen option:not(:selected)').attr('disabled',true);
});

//AGREGA CANT EXISTENTE Y VALIDA LA CANT SALIENTE
$("#selecProductoSal").change(function(event){
	//console.log("entra1");
	event.preventDefault();

	// si viene vacio el select2 que regrese en false
	if($(this).val()==""){
		return false;	
	}

$('input#promocion').iCheck('uncheck');
 var almacen=$( "#nuevaSalidaAlmacen option:selected" ).text();
 idProducto=$("#selecProductoSal").val();
 idProducto=parseInt(idProducto);
 
 
 //HACER UN FETCH AL CAT DE PROD
 let data = new FormData();
 data.append('idProducto', idProducto);
 
	(async () => {
	const rawResponse = await fetch('ajax/productos.ajax.php', {
	  method: 'POST',
	  body: data
	});
	
	result = await rawResponse.json();
	//console.log("entra2",result.datos_promocion);
	if( await result.datos_promocion=='NULL' || result.datos_promocion==null || result.datos_promocion==""){
		 sinpromo=true;		//sin promocion
		 //console.log("entra3: sinpromo",result.datos_promocion);
		 checaSalida(almacen, idProducto);
		 //$("#cantSalida").prop('disabled', false);	
	}else{
		//console.log("entra4: conpromo",result.datos_promocion);
		sinpromo=false;		// es promocion
		var preciodeVenta=result.precio_venta;
		json = JSON.parse(result.datos_promocion);
		let sinexistencia=true;
		json.forEach(obj => {
			//Object.entries(obj).forEach(([key, value]) => {
			//console.log(`${key} ${value}`);
			//});
			let prod=obj.id_producto;
			let canti=obj.cantidad;
			
			$.get('ajax/salidas.ajax.php', {almacen:almacen, idProducto:prod}, function(response,status) {
			
			   var contenido = JSON.parse(response);
				let cantexiste=contenido["cant"];
				let precioVenta=contenido["precventa"];
				let udeMedida=contenido["medida"];
				precioVenta=redondea(precioVenta,2);
				
				//console.log(cantexiste, canti);
				
					if(parseInt(cantexiste)<parseInt(canti) || typeof cantexiste === "undefined"){
						console.log("entra5:");
						$("#cantSalida").val(0);
						$("#cantExiste").val(0);
						$('#selecProductoSal').val(null).trigger('change'); 
						$("#precioValor").val(0);
						//let $e=$('#mensajerror').text('Productos con existencia MENOR en este Almacen!!');
						//$("#mensajerror").removeClass("d-none");
						//setTimeout(function(){$("#mensajerror").addClass("d-none")}, 3000);   //1000 ms= 1segundo
						$("#mensajerror").removeClass("d-none");
						let $e = $("<p>Productos con existencia MENOR en este Almacen!!</p>");
						$eventLog.append($e);
						$e.animate({ opacity: 1 }, 2000, 'linear', function () {
							$e.animate({ opacity: 0 }, 1000, 'linear', function () {
								$e.remove();
								$("#mensajerror").addClass("d-none");
							});
						}); 

						sinpromo=false;			//con promocion
						sinexistencia=false;
						return false;
					}else{
					 if(sinexistencia){
						console.log("entra6:");
						$("#cantExiste").val(1);
						$("#cantSalida").val(1);
						$("#umedida").val(udeMedida);
						$("#precioValor").val(preciodeVenta);
						//$("#cantSalida").focus();
					 }	
					}				
			});	//fin del .get
			
			//checaSalida(almacen,prod,canti,preciodeVenta);
			//console.log(almacen, prod, canti);
			//console.log('-------------------');
		});	//fin de json.each
		
	}	//fin del IF

  })();  //fin del async

})

/* ======= CHECA SI HAY EXISTENCIA DEL PROD SELECCIONADO ====*/
function checaSalida(almacen, idProducto, ...args){
    $.get('ajax/salidas.ajax.php', {almacen:almacen, idProducto:idProducto}, function(response,status) {

	let qtySal=parseInt(args[0]);
	let preciodeVenta=parseFloat(args[1]).toFixed(2);
	//console.log(qtySal);
	
    var contenido = JSON.parse(response);
    let cantexiste=contenido["cant"];
    let precioVenta=contenido["precventa"];
	let udeMedida=contenido["medida"];
	precioVenta=redondea(precioVenta,2);

	if(isNaN(qtySal)){
		$("#cantExiste").val(cantexiste);
		$("#umedida").val(udeMedida);
		$("#precioValor").val(precioVenta);
		$("#cantSalida").val(1);
	}else{
		$("#cantExiste").val(1);
		$("#cantSalida").val(1);
		$("#umedida").val(udeMedida);
		$("#precioValor").val(preciodeVenta);
		$("#cantSalida").focus();
		//console.log("entra aqui")
	}	

		if(parseInt(cantexiste)<qtySal){
			$("#cantSalida").val(0);
			$('#selecProductoSal').val(null).trigger('change'); 
            //$("#selecProductoSal").val("");
			$('#mensajerror').text('Producto con existencia MENOR en este Almacen!!');
            $("#mensajerror").removeClass("d-none");
            setTimeout(function(){$("#mensajerror").addClass("d-none")}, 3000);   //1000 ms= 1segundo
			sinpromo=false;
			return false;
			//}else{
			//	console.log("pasa");
		}

        if(parseFloat(cantexiste)>0){
            //console.log("hay existencia");
            $("#mensajerror").addClass("d-none" );
        }else if(response=="false"){
            //console.log("No existe Art");
			$("#cantSalida").val(0);
			//$('#selecProductoSal').val(null).trigger('');
			$('#selecProductoSal').val(null).trigger('change'); 
			//$("#selecProductoSal").val("0");
			$("#precioValor").val(0);
			$('input#promocion').iCheck('uncheck');
			$('#mensajerror').text('Producto no existe en este Almacen!!');
            $("#mensajerror").removeClass("d-none");
            setTimeout(function(){$("#mensajerror").addClass("d-none")}, 3000);
			sinpromo=false;
        }else{
            console.log("es 0");
			$("#cantSalida").val(0);
			//$("#selecProductoSal").val("0");
			//$('#selecProductoSal').val(null).trigger('');
			$('#selecProductoSal').val(null).trigger('change'); 
			$("#precioValor").val(0);
			$('input#promocion').iCheck('uncheck');
			$('#mensajerror').text('Producto con existencia 0 en este Almacen!!');
            $("#mensajerror").removeClass("d-none");
			sinpromo=false;
            setTimeout(function(){$("#mensajerror").addClass("d-none")}, 3000);   //1000 ms= 1segundo
        }
    });

};

// //=========VERIFICA CANT NO SEA MAYOR QUE LA EXISTENCIA ================== por un momento
// $("#cantSalida").change(function(event){
// event.preventDefault();
// //console.log(sinpromo);
// if(sinpromo){
// 	//console.log("entra verifica cant");
// 	cantexiste=$("#cantExiste").val();
// 	cantSolicitada=$("#cantSalida").val();
// 	 if(parseFloat(cantSolicitada)>parseFloat(cantexiste)){
// 		 $('#mensajerror').text('Cantidad Solicitada es Mayor a la Existencia!!');
// 		 $("#mensajerror").removeClass("d-none");
// 		 $("#cantSalida").val(0);
// 	 }else{
// 		 $("#mensajerror").addClass("d-none");
// 	 }
// }

// });

//=========VERIFICA CANT NO SEA MAYOR QUE LA EXISTENCIA ================== 
function chekaexistencia(){
	//console.log(sinpromo);
	if(sinpromo){
		//console.log("entra verifica cant");
		cantexiste=$("#cantExiste").val();
		cantSolicitada=$("#cantSalida").val();
		 if(parseFloat(cantSolicitada)>parseFloat(cantexiste)){
			 $('#mensajerror').text('Cantidad Solicitada es Mayor a la Existencia!!');
			 $("#mensajerror").removeClass("d-none");
			 $("#cantSalida").val(0);
			 return false;
		 }else{
			 $("#mensajerror").addClass("d-none");
			 return true;
		 }
	}
}

//=========  CLICK PRODUCTO SELECCIONADO Y CHECA CAJA ABIERTA ==============     
  $("#agregarProductos").click(function(event){
	event.preventDefault();
	cantexiste=$("#cantExiste").val();
	//console.log("entra7:",cantexiste);
		if(parseInt(cantexiste)>0){
			if(sinpromo){
				//console.log("entra8: sin promo");
				if(chekaexistencia()){
					añadirProductos();
					sinpromo=false;
				}	
			}else{
				//console.log("entra9: con promo");
					añadirPromocion();
					sinpromo=false;
			}
		}

		$("#cantExiste").val(0);
		$('#selecProductoSal').select2('open');

   })	

// ========= CONSULTA PRODUCTO DE LA PROMOCION =========
async function getProducto(prod){
	const datos = new FormData();
	datos.append('idProducto', prod);	
    try {
		let response = await fetch('ajax/productos.ajax.php', {
			method: 'POST',
			body: datos
		  })
		
		let getjson = await response.json()
		//console.log(getjson);
		return getjson;
    } catch (err) {
        console.log("Error ==> ", err)
    }
}
// =========FIN CONSULTA PRODUCTO DE LA PROMOCION =========

//QUITA PROMOCION	
function eliminarDetalleProm(indice){
	//console.log($(".idPromo"+indice));
	$(".idPromo"+indice).remove();
	calculaTotalesSalida();
	detalles=detalles-1;
	evaluarSalida();
}

/* =========== FUNCTION PARA AGREGAR PRODUCTOS CON PROMOCION ========= */
function añadirPromocion(){
nPromo++;
let productoProm=$("#selecProductoSal option:selected" ).text();       //obtener el texto del valor seleccionado
let codigointer= productoProm.substr(0, productoProm.indexOf('-'));
let cantpromo=$("#cantSalida").val();
codigointer.trim();
//SE SEPERA LA DESCRIPCION DEL PROD. SELECCIONADO        
let descripcionPromo= productoProm.substr(productoProm.lastIndexOf("-") + 1);
descripcionPromo.trim();
var fila='<tr class="filas table-success table-sm idPromo'+nPromo+'" id="fila'+conta+'">'+
		'<td><button type="button" class="botonQuitar" onclick="eliminarDetalleProm('+nPromo+')" title="Quitar Concepto">X</button></td>'+
		'<td> </td>'+
		'<td><input type="hidden" value="'+codigointer+'">'+codigointer+'</td>'+		
		'<td><input type="hidden" class="prodactivo" style="width:16rem" value="">'+descripcionPromo+'</td>'+
		'<td> </td>'+
		'<td> </td>'+
		'<td> </td>'+
		'<td> </td>'+
'</tr>';
$('#detalleSalida').append(fila);
json.forEach(obj => {
	//Object.entries(obj).forEach(([key, value]) => {
		//console.log(`${key} ${value}`);
	//});
	//console.log('-------------------');
	let prod=obj.id_producto;
	let cantidad=obj.cantidad*cantpromo;
	let precioventa=obj.precio*cantpromo;
	var codigointerno;
	var descripcion;
	prod=parseInt(prod);

	(async function(){
		let datosProductos = await getProducto(prod)
		//console.log("log => ", datosProductos.descripcion)
		let udemedida=$("#umedida").val();
		precioventa=precioventa.toFixed(2);
		let subtotal=precioventa;
		let preciovtaunit=obj.precio;
		codigointerno= datosProductos.codigointerno; 
		descripcion= datosProductos.descripcion; 

		var fila='<tr class="filas table-sm idPromo'+nPromo+'" id="fila'+conta+'" idPromo=p'+nPromo+'>'+
				
		'<td><button type="button" class="botonQuitar d-none" onclick="eliminarDetalleSal('+conta+')" title="Quitar Concepto">X</button></td>'+
		
		'<td><input type="hidden" value="'+conta+'">'+prod+'</td>'+
		'<input type="hidden" name="espromo[]" value="1">'+
		
		'<td><input type="hidden" name="codigointerno[]" id="codigointerno[]" value="'+codigointerno+'">'+codigointerno+'</td>'+
		
		'<td><input type="hidden" class="prodactivo" style="width:16rem" name="idProducto[]" value="'+prod+'">'+descripcion+'</td>'+
	
		'<td class="text-center"><input type="hidden" name="udemed[]" id="udemed[]" idFila=f'+conta+' style="width:3rem" readonly >'+udemedida+'</td>'+
		
		'<td class="text-center"><input type="hidden" name="cantidad[]" id="cantidad[]" idFila=f'+conta+' class="cuantos" value='+cantidad+' style="width:3rem" required readonly dir="rtl">'+cantidad+'</td>'+
		
		'<td class="text-center"><input type="hidden" name="precio_venta[]" id="precio_venta[]" class="preciovta" value='+precioventa+' step="any" style="width:5rem" readonly dir="rtl">'+preciovtaunit+'</td>'+
		
		'<td class="text-center disabled"><input type="text" name="subtotal" id="subtotal'+conta+'" class="importe_linea" value="'+subtotal+'" style="width:5rem" step="any" readonly dir="rtl"></td>'+
	
		'</tr>';
		conta++;
		detalles=detalles+1;    
		$('#detalleSalida').append(fila);
		evaluarSalida(); 
		calculaTotalesSalida();
	
	})()


		//DESPUES DE AÑADIR ELEMENTO SE INICIALIZAN
		//$('#selecProductoSal').val(null).trigger('');	
		$('#selecProductoSal').val(null).trigger('change'); 
		$("#cantSalida").val(1);
		$("#cantExiste").val(0);
		$("#precioValor").val(0);
		$('input#promocion').iCheck('uncheck');
		$("#selecProductoSal").val("0");	
		//$("#cantSalida").focus();	
		//console.log("entra aqui2");


	});	

}
//=========  FUNCION AGREGA PRODUCTO SELECCIONADO Y CHECA CAJA ABIERTA ==============     
function añadirProductos(){

	let idcaja = parseInt($("#idcaja").val());
	//console.log("caja",idcaja);
	if(isNaN(idcaja) || idcaja<1 ){
		console.log("vacio",idcaja);
		$("#cantSalida").val(0);
		$('#mensajerror').text('No hay Caja aperturada para este usuario!!');
		$("#mensajerror").removeClass("d-none");
		setTimeout(function(){$("#mensajerror").addClass("d-none")}, 3000);   //1000 ms= 1segundo    
		return true;
	}
		
	var idProducto=$("#selecProductoSal").val();

	//CHECA QUE NO SE VUELVA A DUPLICAR PRODUCTO 
	/*let duplica=buscaProdDuplicado(idProducto);
	if(duplica){
		 $("#cantSalida").val(0);
		 $('input#promocion').iCheck('uncheck');
		 $('#mensajerror').text('Producto ya capturado. Revise!!');
		 $("#mensajerror").removeClass("d-none");
		return true;
	};
	*/

	var producto=$("#selecProductoSal option:selected" ).text();       //obtener el texto del valor seleccionado

	var cantcap=$("#cantSalida").val();
	var precioVenta=$("#precioValor").val();
	let udemedida=$("#umedida").val();

		
		idProducto=parseInt(idProducto);
		cantcap=parseFloat(cantcap);
		//console.log("cantidad",cantcap);
		//console.log(idProducto, producto, typeof idProducto);

		//Si no selecciona producto retorna
		if(isNaN(idProducto) || isNaN(cantcap) ){
			return true;
		}  
		if(cantcap==0){
			return true;
		}else if(cantcap<0){
			return true;    
		}  

		//SE SEPARA EL CODIGO DEL PROD. SELECCIONADO    
		var codigointerno= producto.substr(0, producto.indexOf('-'));
		codigointerno.trim();

		//SE SEPERA LA DESCRIPCION DEL PROD. SELECCIONADO        
		var descripcion= producto.substr(producto.lastIndexOf("-") + 1);
		descripcion.trim();

		//let cantidad=parseFloat(cantSolicitada);
		let cantidad=parseFloat(cantcap);
		let precioventa=parseFloat(precioVenta).toFixed(2);
		 if(precioventa==0){
			 //precioventa=1;
		 }
		let subtotal=(cantidad*precioventa).toFixed(2)

		//var some_number = number_format(subtotal,2, '.',',');
		var fila='<tr class="filas table-sm" id="fila'+conta+'">'+
			
			'<td><button type="button" class="botonQuitar" onclick="eliminarDetalleSal('+conta+')" title="Quitar Concepto">X</button></td>'+
			
			'<td><input type="hidden" value="'+conta+'">'+idProducto+'</td>'+
			'<input type="hidden" name="espromo[]" value="0">'+
			
			'<td><input type="hidden" name="codigointerno[]" id="codigointerno[]" value="'+codigointerno+'">'+codigointerno+'</td>'+
			
			'<td><input type="hidden" class="prodactivo" style="width:16rem" name="idProducto[]" value="'+idProducto+'">'+descripcion+'</td>'+

			'<td class="text-center"><input type="hidden" name="udemed[]" id="udemed[]" idFila=f'+conta+' style="width:3rem" readonly >'+udemedida+'</td>'+
			
			'<td class="text-center"><input type="hidden" name="cantidad[]" id="cantidad[]" idFila=f'+conta+' class="cuantos" value='+cantidad+' style="width:3rem" required readonly dir="rtl">'+cantidad+'</td>'+
			
			'<td class="text-center"><input type="hidden" name="precio_venta[]" id="precio_venta[]" class="preciovta" value='+precioventa+' step="any" style="width:5rem" readonly dir="rtl">'+precioventa+'</td>'+
			
			'<td class="text-center disabled"><input type="text" name="subtotal" id="subtotal'+conta+'" class="importe_linea" value="'+subtotal+'" style="width:5rem" step="any" readonly dir="rtl"></td>'+

			'</tr>';
			conta++;
			detalles=detalles+1;    
			$('#detalleSalida').append(fila);
			evaluarSalida(); 
			calculaTotalesSalida();
		
	   //DESPUES DE AÑADIR ELEMENTO SE INICIALIZAN
		//$('#selecProductoSal').val(null).trigger('');
		$('#selecProductoSal').val(null).trigger('change'); 	
		$("#cantSalida").val(1);
		$("#cantExiste").val(0);
		$("#precioValor").val(0);
		$('input#promocion').iCheck('uncheck');
		$("#selecProductoSal").val("0");		

};


//FUNCION QUE VERIFICA QUE NO SE DUPLIQUE PROD.
function buscaProdDuplicado(idProdNvo) {
lDuplicado=false;
	$(".prodactivo").each(
		function(index, value) {
			let idProd=parseFloat($(this).val());
			//console.log(idProdNvo);
			if(idProd==idProdNvo){
				//console.log("son iguales")
				lDuplicado=true;
			}	
		}
	);
	return lDuplicado	
}


//FUNCION QUE SUMA LOS SUBTOTALES
function calculaTotalesSalida() {
	importe_subtotal = 0
	$(".importe_linea").each(
		function(index, value) {
			importe_subtotal = importe_subtotal + eval($(this).val());
            //console.log("eval: ",eval($(this).val()));
		}
	);
    let impuesto =16;
	let antesdeiva=(impuesto/100)+1;        //1.16
    
	let importe_antes_iva=Number(importe_subtotal / antesdeiva);
	
    let sacar_iva=(Number(importe_subtotal)-importe_antes_iva);
        
    let importe_total=importe_antes_iva+sacar_iva;	
	
	$("#sumasubtotal").val(importe_antes_iva).number(true,2);
	$("#iva").val(sacar_iva).number(true,2);

	let importetotal=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(importe_total);
	$("#total").val(importetotal);
	
	if(importe_total>0){
	  $("#btnGuardar").show();
	  $("#saveRegTick").show();
    }else{
		$("#btnGuardar").hide();
		$("#saveRegTick").hide();
	}
}

//EMVIAR FORMULARIO PARA GUARDAR E IMPRIMIR TICKET
$("body").on("click", "#saveRegTick", function( event ) {	
    event.preventDefault();
    event.stopPropagation();	
	let apagar=$("#total").val();
	apurchase = apagar.split("$");
	apurchase=parseFloat(apurchase[1]);
	//console.log(apurchase[1])
	if(apurchase>0){	
		swal({
		title: "¿Cantidad a Cobrar? "+apagar,
		text: "¡Si no está seguro, puede Cancelar la Venta!",
		icon: "warning",
		allowEnterKey: true, // default value
		buttons: ["Cancelar", "Sí, Guardar"],
		})
		.then((siimprimir) => {
			if (siimprimir) {
				$("#saveRegTick").val(1);
				$("#saveRegTick").hide();
					//var formData = new FormData($("#formularioSalida")[0]);
                    //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}

                        let varjs=1066;
                        let formulario = document.getElementById("formularioSalida");
                     	(async () => {
                        await formulario.submit();
                        console.log("aqui entra");
                        window.open("extensiones/tcpdf/pdf/imprimir_ticket.php?codigo="+varjs,"_blank","top=200,left=450,width=400,height=400");
                        })();  //fin del async	
                        console.log("aqui sale");
                        //window.location = "salidas";

			}else{
				return false;
			}
			}); 
	}else{
		return false;
	}
});

//ENVIAR FORMULARIO PARA GUARDAR SOLO DATOS DE SALIDA
$("body").on("submit", "#formularioSalida", function( event ) {	
event.preventDefault();
event.stopPropagation();	
//var formData = new FormData($("#formularioSalida")[0]);
//for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}

let apagar=$("#total").val();
apurchase = apagar.split("$");
apurchase=parseFloat(apurchase[1]);
//console.log(apurchase[1])
if(apurchase>0){	
	swal({
	title: "¿Cantidad a Cobrar? "+apagar,
	text: "¡Si no está seguro, puede Cancelar la Venta!",
	icon: "warning",
	allowEnterKey: true, // default value
	buttons: ["Cancelar", "Sí, Guardar"],
	})
	/*buttons: {
		cancel: true,
		confirm: "Confirm",
		roll: {
		text: "Do a barrell roll!",
		value: "roll",
		},
	},*/
	
	.then((aceptado) => {
		if (aceptado) {
			//console.log("enviar form");
			event.currentTarget.submit();		//CONTINUA EL ENVIO DEL FORMULARIO
		}else{
			return false;
		}
		}); 
}else{
	return false;
}

 });
//FIN DE ENVIO FORMULARIO PARA GUARDAR DATOS DE ENTRADA


//QUITA ELEMENTO 
 function eliminarDetalleSal(indice){
  	$("#fila" + indice).remove();
  	calculaTotalesSalida();
  	detalles=detalles-1;
  	evaluarSalida();
  }

//SI NO HAY ELEMENTOS cont SE INICIALIZA
function evaluarSalida(){
  if (!detalles>0){
      conta=1;
	  $("#btnGuardar").hide();
	  $("#saveRegTick").hide();
    }
}

let placeholderSalida = "&#xf02a Busque y seleccione producto";
$('.select3').select2({
    placeholder: placeholderSalida,
    escapeMarkup: function(m) { 
       return m; 
    }
});

//AL SALIR DEL SELECT2 FOCUS AL CANT DE SALIDA
$('.select3').on('select2:close', function (e){
	$("#cantSalida").css({"background-color": "orange", "color":"black", "font-size": "100%"});
    $('#cantSalida').focus();
});

$('#cantSalida').on('blur', function() {
	$("#cantSalida").css({"background-color": "white", "color":"black", "font-size": "100%"});
});
	
//VALIDAR QUE NUM DE SALIDA NO SE REPITA. POR AJAX.GET
$('#numeroSalida').on('blur', function() {
    $('span#msjNumeroRepetido').html("");
    let numDocto = $("#numeroSalida").val();
   if(numDocto.length !=0){
        $.get("ajax/salidas.ajax.php", {numDocto : numDocto}, function(resp, estado,jqXHR){
         //console.log("Respuesta: " + resp + "\nEstado: " + estado +"\njqHXR: " + jqXHR);   
	   
		 if(estado="success"){	
			if(!(parseInt(resp)===0)){
			  $('span#msjNumeroRepetido').html(`<label style='color:red'>AVISO!! ${resp} </label>`);
			  $("input#numeroSalida").focus(function(){
			  $("span#msjNumeroRepetido").css("display", "inline").fadeOut(4000);
			});
				 $("#numeroSalida").val('');
				 $("#numeroSalida").focus();
			}  
		}else{
			$('span#msjNumeroRepetido').html(`<label style='color:red'>ERROR!! ${estado} </label>`);
		}
		
      })   
   }	

});


$(document).ready(function(){
    //$('span#msjNumeroRepetido').html(`<label style='color:red'>Entrro!!  </label>`);
	let numConsecutivo=0;
	
	$.get("ajax/salidas.ajax.php", { numConsecutivo: numConsecutivo},function(resp, estado,jqXHR){
         //console.log("Respuesta: " + resp + "\nEstado: " + estado +"\njqHXR: " + jqXHR);   
	     
		 resp=parseInt(resp);
		 
		 if(estado="success"){	
			if(!(parseInt(resp)===0)){
				 $("#numeroSalidaAlm").val(resp);
				 $("#selecProductoSal").focus();
			}else{
				$("#numeroSalidaAlm").val(1);
				$("#numeroSalidaAlm").focus();
			}  
		}else{
				$("#selecProductoSal").focus();
		}
		
	  })   	 
	  
	  
	 document.onkeydown =
      function (e) {
      var current_key1;
      if (document.all){
       current_key1 = event.keyCode;
	  }else{
		current_key1=e.which;
	  } 
     // SON las 10 para las opciones de abajo
     //comprueba las teclas.Estas son del F1 al F12
     var teclas= /^(112|113|114|115|116|117|118|119|120|121|122|123|107|109)$/;	
   
     if (teclas.test(current_key1)) {
       if (document.all){
           event.keyCode = 0;
           event.returnValue = false;
           event.cancelBubble = true;
       }else{
      	 e.which=0;
       }
       if (current_key1==113){		//F2
		 //console.log("open")
		 $("#cantExiste").val(0);
		 $('#selecProductoSal').select2('open');
         return false;
       }else if (current_key1==121){		//F10
         document.getElementById("btnGuardar").focus();
         return false;
       }else if (current_key1==107){		//TECLA + (mas)
		event.preventDefault();
		aumenta=$("#cantSalida").val();
		aumenta=parseInt(aumenta)+1;
		$("#cantSalida").val(aumenta);
		$("#cantSalida").focus();
		//console.log("entra aqui",aumenta)
	   }else if (current_key1==109){		//TECLA - (menos)
		event.preventDefault();
		disminuye=$("#cantSalida").val();
		disminuye=parseInt(disminuye);
		//console.log("entra aqui",disminuye)
		if(disminuye>1){
			disminuye=disminuye-1;
			$("#cantSalida").val(disminuye);
			$("#cantSalida").focus();
		}else{
			$("#cantSalida").val(1);
			$("#cantSalida").focus();
		}
	   }

     }
   }    

}); 



/*
$('#selecProductoSal').val(null).trigger('change'); 
//SABER SI CHECKBOX ESTA SELECCIONADO
if( $('#promocion').prop('checked') ) {
    console.log('Seleccionado');
}else{
	console.log('No Seleccionado');
}
*/
