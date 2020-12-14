//$("#agregarProd").hide();
var cantexiste=0;
var cantSolicitada=0;
var precioVenta=0;
var udeMedida="";
var idProducto=0;
var lDuplicado=false;
/*
jQuery(document).ready(function(){
    //$('span#msjNumeroRepetido').html(`<label style='color:red'>Entrro!!  </label>`);
	let numConsecutivo=0;
	$.get("ajax/salidas.ajax.php", { numConsecutivo: numConsecutivo},function(resp, estado,jqXHR){
         console.log("Respuesta: " + resp + "\nEstado: " + estado +"\njqHXR: " + jqXHR);   
	   
		 if(estado="success"){	
			if(!(parseInt(resp)===0)){
				 $("#nuevaSalidaAlmacen").val(resp);
				 $("#nuevaSalidaAlmacen").focus();
			}else{
				$("#nuevaSalidaAlmacen").val(1);
				$("#nuevaSalidaAlmacen").focus();
			}  
		}else{
				 $("#nuevaSalidaAlmacen").val('1');
				 $("#nuevaSalidaAlmacen").focus();
		}
		
      })   	
}); 
*/

$("#nuevaSalidaAlmacen").change(function(){
    //console.log("entra");
    $("#agregarProd").removeClass("d-none" )
    //$( "#nuevaSalidaAlmacen" ).prop( "disabled", true ); 
    $('#nuevaSalidaAlmacen option:not(:selected)').attr('disabled',true);
});


$("#selecProductoSal").change(function(event){
event.preventDefault();
 var almacen=$( "#nuevaSalidaAlmacen option:selected" ).text();
  var idProducto=$("#selecProductoSal").val();
  console.log("Prod:",idProducto, "almacen:",almacen);
    $.get('ajax/salidas.ajax.php', {almacen:almacen, idProducto:idProducto}, function(response,status) {
    //$(".respuesta").html(response);
    var contenido = JSON.parse(response);
    cantexiste=contenido["cant"];
    precioVenta=contenido["precio_venta"];
    udeMedida=contenido["medida"];
        
    $("#cantExiste").val(cantexiste);
        if(parseFloat(cantexiste)>0){
            console.log("hay existencia");
            $("#mensajerror").addClass("d-none" );
        }else if(response=="false"){
            console.log("No existe Art");
            $("#cantSalida").val(0);
            $("#selecProductoSal").val("");
			$('#mensajerror').text('Producto no existe en este Almacen!!');
            $("#mensajerror").removeClass("d-none");
            setTimeout(function(){$("#mensajerror").addClass("d-none")}, 3000);
        }else{
            console.log("es 0");
            $("#cantSalida").val(0);
            $("#selecProductoSal").val("");
			$('#mensajerror').text('Producto con existencia 0 en este Almacen!!');
            $("#mensajerror").removeClass("d-none");
            setTimeout(function(){$("#mensajerror").addClass("d-none")}, 3000);   //1000 ms= 1segundo
        }
    });
});

//VERIFICA CANT NO SEA MAYOR QUE LA EXISTENCIA
$("#cantSalida").change(function(event){
console.log(cantexiste);
event.preventDefault();
cantSolicitada=$("#cantSalida").val();
 if(parseFloat(cantSolicitada)>parseFloat(cantexiste)){
	 $('#mensajerror').text('Cantidad Solicitada es Mayor a la Existencia!!');
     $("#mensajerror").removeClass("d-none");
     $("#cantSalida").val(0);
 }else{
     $("#mensajerror").addClass("d-none");
 }
});


//=========  AGREGAR PRODUCTO SELECCIONADO  ==============     
$("#agregarProductos").click(function(event){
event.preventDefault();

var idProducto=$("#selecProductoSal").val();

//CHECA QUE NO SE VUELVA A DUPLICAR PRODUCTO
let duplica=buscaProdDuplicado(idProducto);
if(duplica){
     $("#cantSalida").val(0);
	 $('#mensajerror').text('Producto ya capturado. Revise!!');
     $("#mensajerror").removeClass("d-none");
	return true;
};

var producto=$("#selecProductoSal option:selected" ).text();       //obtener el texto del valor seleccionado
var almacen=$( "#nuevaSalidaAlmacen option:selected" ).text();
var cantcap=$("#cantSalida").val();
    
    idProducto=parseInt(idProducto);
    cantcap=parseFloat(cantcap);
    console.log("Almacen:",almacen, cantcap);
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

    //$.get('ajax/salidas.ajax.php', {almacen:almacen, idProducto:idProducto}, function(response,status) {
      //$(".respuesta").html(response,status);
      //var content = JSON.parse(response);
      //console.log(content);
      //console.log(content.id_producto);
      //console.log(content["id_producto"]);
     //console.log(precioventa);
     //});

    //SEPARA EL CODIGO DEL PROD. SELECCIONADO    
    var codigointerno= producto.substr(0, producto.indexOf('-'));
    codigointerno.trim();

    //SEPARA LA DESCRIPCION DEL PROD. SELECCIONADO        
    var descripcion= producto.substr(producto.lastIndexOf("-") + 1);
    descripcion.trim();

    let cantidad=parseFloat(cantSolicitada);
    let precioventa=parseFloat(precioVenta);
     if(precioventa==0){
         precioventa=1;
     }
	let subtotal=(cantidad*precioventa).toFixed(2)
    let udemedida=udeMedida;
    //var some_number = number_format(subtotal,2, '.',',');
	var fila='<tr class="filas" id="fila'+cont+'">'+
        
    	'<td><button type="button" class="botonQuitar" onclick="eliminarDetalleSal('+cont+')" title="Quitar Concepto">X</button></td>'+
        
        '<td><input type="hidden" value="'+cont+'">'+idProducto+'</td>'+
		
    	'<td><input type="hidden" name="codigointerno[]" id="codigointerno[]" value="'+codigointerno+'">'+codigointerno+'</td>'+
        
		'<td><input type="hidden" class="prodactivo" style="width:15rem" name="idProducto[]" value="'+idProducto+'">'+descripcion+'</td>'+

        '<td class="text-center"><input type="hidden" name="udemed[]" id="udemed[]" idFila=f'+cont+' style="width:3rem" readonly >'+udemedida+'</td>'+
        
    	'<td class="text-center"><input type="hidden" name="cantidad[]" id="cantidad[]" idFila=f'+cont+' class="cuantos" value='+cantidad+' style="width:3rem" required readonly dir="rtl">'+cantidad+'</td>'+
        
    	'<td class="text-center"><input type="hidden" name="precio_venta[]" id="precio_venta[]" class="preciovta" value='+precioventa+' step="any" style="width:5rem" readonly dir="rtl">'+precioventa+'</td>'+
        
    	'<td class="text-center disabled"><input type="text" name="subtotal" id="subtotal'+cont+'" class="importe_linea" value="'+subtotal+'" style="width:6rem" step="any" readonly dir="rtl"></td>'+

    	'</tr>';
    	cont++;
    	detalles=detalles+1;    
		$('#detalleSalida').prepend(fila);
        evaluarSalida(); 
        calculaTotalesSalida();
    
   //DESPUES DE AÑADIR ELEMENTO SE INICIALIZAN
    $("#selecProductoSal").val("");
    //$("#selecProducto").change();    
    $("#cantSalida").val(0);
    $("#cantExiste").val(0);
})


//FUNCION QUE VERIFICA QUE NO SE DUPLIQUE PROD.
function buscaProdDuplicado(idProdNvo) {
lDuplicado=false;
	$(".prodactivo").each(
		function(index, value) {
			let idProd=parseFloat($(this).val());
			console.log(idProd);
			console.log(idProdNvo);
			if(idProd==idProdNvo){
				console.log("son iguales")
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
            console.log("eval: ",eval($(this).val()));
		}
	);
    var impuesto =16;
    
    var importe_iva=Number(importe_subtotal * impuesto/100);
    var importe_total=Number(importe_iva) + Number(importe_subtotal);    
    
	$("#sumasubtotal").val(importe_subtotal).number(true,2);
	$("#iva").val(importe_iva).number(true,2);
	$("#total").val(importe_total).number(true,2);
	if(importe_total>0){
	  $("#btnGuardar").show();
    }else{
		//$("#btnGuardar").hide();
	}
}


//ENVIAR FORMULARIO PARA GUARDAR DATOS DE ENTRADA
$("body").on("submit", "#formularioSalida", function( event ) {	
event.preventDefault();
event.stopPropagation();	
//console.log("enviar form");

	swal({
      title: "¿Está seguro de Guardar Salida?",
      text: "¡Si no lo está puede cancelar la acción!",
      icon: "warning",
      buttons: ["Cancelar", "Sí, Guardar"],
      dangerMode: true,
    })
    .then((aceptado) => {
      if (aceptado) {
		  event.currentTarget.submit();		//CONTINUA EL ENVIO DEL FORMULARIO
      }else{
		  return false;
	  }
    }); 

 });

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
      cont=0;
	  $("#btnGuardar").hide();
    }
}

$('.select3').select2({
  placeholder: 'Busca y selecciona un producto',
});

/* ================================================================================ */
//VALIDAR QUE NUM DE SALIDA NO SE REPITA POR AJAX.GET
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
/* ================================================================================ */
number_format = function (number, decimals, dec_point, thousands_sep) {
        number = number.toFixed(decimals);

        var nstr = number.toString();
        nstr += '';
        x = nstr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? dec_point + x[1] : '';
        var rgx = /(\d+)(\d{3})/;

        while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

        return x1 + x2;
    }