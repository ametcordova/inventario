var detalles=0;
var cont=1;
var qty=0;	

$(document).ready(function(){
    $("#btnGuardar").hide();
    $( "#renglones" ).prop( "disabled", true );
	$( "#sumasubtotal" ).prop( "disabled", true );
    $( "#iva" ).prop( "disabled", true );
    $( "#total" ).prop( "disabled", true );
    $( ".importe_linea" ).prop( "disabled", true );
})
    

$("#nuevoAlmacen").change(function(){
    //console.log("entra");
    $('#nuevoAlmacen option:not(:selected)').attr('disabled',true);
});	
	
 //AGREGAR PRODUCTO SELECCIONADO                 
$("#agregarDetalle").click(function(event){
event.preventDefault();
var idProducto=$("#selecProducto").val();

var producto=$("#selecProducto option:selected" ).text();
var cantentrada=$("#cantentrada").val();
cantidadEntra=parseFloat(cantentrada);
idProducto=parseInt(idProducto);

//CHECA QUE NO SE VUELVA A DUPLICAR PRODUCTO
let duplicado=buscaProdDuplicado(idProducto);

if(duplicado){
     $("#cantentrada").val(0);
	 $('#mensajerror').text('Producto ya capturado. Revise!!');
     $("#mensajerror").removeClass("d-none");
	 setTimeout(function(){$("#mensajerror").addClass("d-none")}, 3500);
	return true;
};



//SE SEPERA EL CODIGO DEL PROD. SELECCIONADO    
var codigointerno= producto.substr(0, producto.indexOf('-'));
codigointerno.trim();

//SE SEPERA LA DESCRIPCION DEL PROD. SELECCIONADO        
var descripcion= producto.substr(producto.lastIndexOf("-") + 1);
descripcion.trim();

   
   //Si no selecciona producto retorna
    if(isNaN(idProducto) || isNaN(cantidadEntra) ){
        return true;
    }  
	
    if(cantidadEntra==0){
        return true;
    }else if(cantidadEntra<0){
        return true;    
    }  	
	
    //DESPUES DE AÑADIR ELEMENTO SE INICIALIZA SELECT
    $("#selecProducto").val("");
    $("#selecProducto").change();
 
    
	 let cantidad=null;
	 let subtotal=0;
	 
	var fila='<tr class="filas" id="fila'+cont+'">'+
        
    	'<td><button type="button" class="botonQuitar" onclick="eliminarDetalle('+cont+')" title="Quitar Concepto">X</button></td>'+
        
        '<td><input type="hidden" value="'+cont+'">'+idProducto+'</td>'+
		
    	'<td><input type="hidden" name="codigointerno[]" id="codigointerno[]" value="'+codigointerno+'">'+codigointerno+'</td>'+
        
		'<td><input type="hidden" name="idProducto[]" class="prodactivo" value="'+idProducto+'">'+descripcion+'</td>'+
        
		'<td class="text-center"><input type="hidden" name="cantidad[]" id="cantidad[]" idFila=f'+cont+' class="cuantos" value="'+cantidadEntra+'" style="width:3rem" required readonly dir="rtl">'+cantidadEntra+'</td>'+

    	'</tr>';
    	detalles=detalles+1;    
		$('#detalles').append(fila);
		$("#renglones").val(cont);
		calcularTotales()
		$("#cantentrada").val(0);
		cont++;
		
})

/* =FUNCION QUE VERIFICA QUE NO SE DUPLIQUE PROD === */
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
/* ==== FIN DE LA FUNCION =========== */

//FUNCION QUE SUMA LOS SUBTOTALES
function calcularTotales() {
	cantProd = 0
	$(".cuantos").each(
		function(index, value) {
			cantProd = cantProd + eval($(this).val());
            console.log("eval: ",eval($(this).val()));
		}
	);
	$("#total").val(cantProd).number(true,2);
	if(cantProd>0){
	  $("#btnGuardar").show();
    }else{
		$("#btnGuardar").hide();
	}
}

//QUITA ELEMENTO 
 function eliminarDetalle(indice){
  	$("#fila" + indice).remove();
  	calcularTotales();
  	detalles=detalles-1;
  	evaluar();
	$("#renglones").val(detalles);
  }

 
//SI NO HAY ELEMENTOS cont SE INICIALIZA
function evaluar(){
  if (!detalles>0){
      cont=0;
	  $("#btnGuardar").hide();
    }
	
}


//ENVIAR FORMULARIO PARA GUARDAR DATOS DE ENTRADA
$("body").on("submit", "#formularioEntrada", function( event ) {	
event.preventDefault();
event.stopPropagation();	
console.log("enviar form");

	swal({
      title: "¿Está seguro de Guardar Entrada?",
      text: "¡Si no lo está puede cancelar la acción!",
      icon: "warning",
      buttons: ["Cancelar", "Sí, Guardar"],
      dangerMode: true,
    })
    .then((aceptado) => {
      if (aceptado) {
		  event.currentTarget.submit();		//CONTINUE EL ENVIO DEL FORMULARIO
      }else{
		  return false;
	  }
    }); 

 });


$("#checar").click(function(event){
    event.preventDefault();
    var datos = new FormData($("#formularioEntrada")[0]);
        for (var pair of datos.entries()){
            console.log(pair[0]+ ', ' + pair[1]);
        }
});

$('.select2').select2({
  placeholder: 'Busca y selecciona un producto',
});


/* ================================================================================ */
//VALIDAR QUE NUM DE SALIDA NO SE REPITA POR AJAX.GET
$('#numeroDocto').on('blur', function() {
    $('span#msjDoctoRepetido').html("");
    let numDocto = $("#numeroDocto").val();
   if(numDocto.length !=0){
        $.get("ajax/entradas.ajax.php", {numDocto : numDocto}, function(resp, estado,jqXHR){
         //console.log("Respuesta: " + resp + "\nEstado: " + estado +"\njqHXR: " + jqXHR);   
	   
		 if(estado="success"){	
			if(!(parseInt(resp)===0)){
			  $('span#msjDoctoRepetido').html(`<label style='color:red'>AVISO!! ${resp} </label>`);
			  $("input#numeroDocto").focus(function(){
			  $("span#msjDoctoRepetido").css("display", "inline").fadeOut(4000);
			});
				 $("#numeroDocto").val('');
				 $("#numeroDocto").focus();
			}  
		}else{
			$('span#msjDoctoRepetido').html(`<label style='color:red'>ERROR!! ${estado} </label>`);
		}
		
      })   
   }	

});
/* ================================================================================ */


/*
'<td class="text-center"><input type="number" name="cantidad[]" id="cantidad[]" idFila=f'+cont+' class="cuantos" value='+cantidad+' style="width:4rem" required></td>'+
*/