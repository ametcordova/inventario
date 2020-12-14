/*=============================================
CARGAR LA TABLA DINÁMICA DE VENTAS
=============================================*/

// $.ajax({

// 	url: "ajax/datatable-ventas.ajax.php",
// 	success:function(respuesta){
		
// 		console.log("respuesta", respuesta);

// 	}

// })// 

$('.tablaVentas').DataTable( {
    "ajax": "ajax/datatable-ventas.ajax.php",
    "deferRender": true,
	"retrieve": true,
	"processing": true,
	 "language": {

			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrar registros del _START_ al _END_ de un total de _TOTAL_",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
			"sFirst":    "Primero",
			"sLast":     "Último",
			"sNext":     "Siguiente",
			"sPrevious": "Anterior"
			},
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}

	}

} );

/*=============================================
AGREGANDO PRODUCTOS A LA VENTA DESDE LA TABLA
=============================================*/

$(".tablaVentas tbody").on("click", "button.agregarProducto", function(){

	var idProducto = $(this).attr("idProducto");
    console.log("idProducto:", idProducto);
    
	$(this).removeClass("btn-primary agregarProducto");

	$(this).addClass("btn-default");    
    
var datos = new FormData();
    datos.append("idProducto", idProducto);     //datos para enviar por POST con ajax

     $.ajax({

     	url:"ajax/productos.ajax.php",
      	method: "POST",
      	data: datos,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(respuesta){    

        //console.log("Respuesta", respuesta);
            
            var descripcion = respuesta["descripcion"];
          	var stock = respuesta["stock"];
          	var precio = respuesta["precio_venta"];
            
          	/*=============================================
          	EVITAR AGREGAR PRODUCTO CUANDO EL STOCK ESTÁ EN CERO
          	=============================================*/

          	if(stock == 0){

      			swal({
			      title: "No hay stock disponible",
			      icon: "error",
			      button: "¡Cerrar!"
			    });

			    $("button[idProducto='"+idProducto+"']").addClass("btn-primary agregarProducto");

			    return;

          	}    //Fin de evita agregar cuando stock es cero
            
            
            $(".nuevoProducto").append(
                '<div class="row">'+    
                   '<div class="col-sm-7" style="padding-right:0px;">'+
                     '<div class="input-group mb-2">'+
                        '<span class="input-group-prepend"><button type="button" class="btn btn-danger btn-sm quitarProducto" idProducto="'+idProducto+'"><i class="fa fa-times"></i></button></span>'+
                          '<input type="text" class="form-control form-control-sm agregarProducto" name="agregarProducto" value="'+descripcion+'" readonly>'+
                      '</div>'+    
                    '</div>'+     
                                          
                      '<div class="col-sm-2 mb-2">'+
                       '<input type="number" class="form-control form-control-sm nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="1" stock="'+stock+'" nuevoStock="'+Number(stock-1)+'" required>'+         
                      '</div>'+
                                          
                      '<div class="col-sm-3 mb-2 ingresoPrecio" style="padding-left:0px">'+
                        '<input type="text" class="form-control form-control-sm nuevoPrecioProducto" precioReal="'+precio+'" name="nuevoPrecioProducto" value="'+precio+'" readonly required>'+      
                      '</div>'+
                 '</div>'
                
                   )
            
                //SUMAR TOTAL DE PRECIOS
                sumarTotalPrecios()
                // AGREGAR IMPUESTO
                agregarImpuesto()
            
                // PONER FORMATO AL PRECIO DE LOS PRODUCTOS
                $(".nuevoPrecioProducto").number(true, 2);
        },
         
        error:function(data, status){
            //console.log("error", data, status)
        }
            
    })
            
    
});

/*=============================================
CUANDO CARGUE LA TABLA CADA VEZ QUE NAVEGUE EN ELLA
=============================================*/

$(".tablaVentas").on("draw.dt", function(){

	if(localStorage.getItem("quitarProducto") != null){

		var listaIdProductos = JSON.parse(localStorage.getItem("quitarProducto"));

		for(var i = 0; i < listaIdProductos.length; i++){

			$("button.recuperarBoton[idProducto='"+listaIdProductos[i]["idProducto"]+"']").removeClass('btn-default');
			$("button.recuperarBoton[idProducto='"+listaIdProductos[i]["idProducto"]+"']").addClass('btn-primary agregarProducto');

		}
	}
})

/*=============================================
QUITAR PRODUCTOS DE LA VENTA Y RECUPERAR BOTÓN
=============================================*/

var idQuitarProducto = [];

localStorage.removeItem("quitarProducto");  //quitar lo que hay en localstorage cuando cargue la pagina

$(".formularioVenta").on("click", "button.quitarProducto", function(){
    
    console.log($(this))

	$(this).parent().parent().parent().parent().remove();
    
    var idProducto = $(this).attr("idProducto");
    
	/*=============================================
	ALMACENAR EN EL LOCALSTORAGE EL ID DEL PRODUCTO A QUITAR
	=============================================*/

	if(localStorage.getItem("quitarProducto") == null){

		idQuitarProducto = [];
	
	}else{

		idQuitarProducto.concat(localStorage.getItem("quitarProducto"))

	}      

	idQuitarProducto.push({"idProducto":idProducto});

	localStorage.setItem("quitarProducto", JSON.stringify(idQuitarProducto));
    //fin de LOCALSTORAGE
    
    $("button.recuperarBoton[idProducto='"+idProducto+"']").removeClass('btn-default');

	$("button.recuperarBoton[idProducto='"+idProducto+"']").addClass('btn-primary agregarProducto');

    if($(".nuevoProducto").children().length == 0){

		$("#nuevoImpuestoVenta").val(0);
		$("#nuevoTotalVenta").val(0);
		$("#totalVenta").val(0);
		$("#nuevoTotalVenta").attr("total",0);

	}else{
    // SUMAR TOTAL DE PRECIOS
	sumarTotalPrecios()   
    // AGREGAR IMPUESTO
    //agregarImpuesto()
        
    }
})


/*=============================================
AGREGANDO PRODUCTOS DESDE EL BOTÓN PARA DISPOSITIVOS
=============================================*/

var numProducto = 0;        //para q en el select no se duplique los productos

$(".btnAgregarProducto").click(function(){

	numProducto ++;

	var datos = new FormData();
	datos.append("traerProductos", "ok");

	$.ajax({

		url:"ajax/productos.ajax.php",
      	method: "POST",
      	data: datos,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(respuesta){
            console.log("Respuesta", respuesta);
            
            $(".nuevoProducto").append(
                '<div class="row">'+    
                   '<div class="col-sm-7" style="padding-right:0px;">'+
                     '<div class="input-group mb-2">'+
                        '<span class="input-group-prepend"><button type="button" class="btn btn-danger btn-sm quitarProducto" idProducto><i class="fa fa-times"></i></button></span>'+
                
                          '<select class="form-control form-control-sm nuevaDescripcionProducto" id="producto'+numProducto+'" idProducto name="nuevaDescripcionProducto" required >'+
                          '<option>Seleccione Producto</option>'+
                          '</select>'+
                      '</div>'+    
                    '</div>'+     
                                          
                      '<div class="col-sm-2 mb-2 ingresoCantidad">'+
                       '<input type="number" class="form-control form-control-sm nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="1" stock nuevoStock required>'+         
                      '</div>'+
                                          
                      '<div class="col-sm-3 mb-2 ingresoPrecio" style="padding-left:0px">'+
                        '<input type="text" class="form-control form-control-sm nuevoPrecioProducto" precioReal="" name="nuevoPrecioProducto" readonly required>'+      
                      '</div>'+
                 '</div>'
             );
            
	        // AGREGAR LOS PRODUCTOS AL SELECT 
	         respuesta.forEach(funcionForEach);

	         function funcionForEach(item, index){

	         	if(item.stock != 0){         //si tiene exist 0 no se visualice

		         	$("#producto"+numProducto).append(

						'<option idProducto="'+item.id+'" value="'+item.descripcion+'">'+item.descripcion+'</option>'
		         	)
		         
		         }	         

	         }    // FIN AGREGAR LOS PRODUCTOS AL SELECT 
            
            // SUMAR TOTAL DE PRECIOS
            sumarTotalPrecios() 
            // AGREGAR IMPUESTO
            agregarImpuesto()
            
            // PONER FORMATO AL PRECIO DE LOS PRODUCTOS
            $(".nuevoPrecioProducto").number(true, 2);
            
        },
        error:function(data, status){
        console.log("error", data, status)
        }
            
    })
    
});

/*=============================================
SELECCIONAR PRODUCTO
=============================================*/

$(".formularioVenta").on("change", "select.nuevaDescripcionProducto", function(){

	var nombreProducto = $(this).val();

	var nuevaDescripcionProducto = $(this).parent().parent().parent().children().children().children(".nuevaDescripcionProducto");

	var nuevoPrecioProducto = $(this).parent().parent().parent().children(".ingresoPrecio").children(".nuevoPrecioProducto");

	var nuevaCantidadProducto = $(this).parent().parent().parent().children(".ingresoCantidad").children(".nuevaCantidadProducto");

	var datos = new FormData();
    datos.append("nombreProducto", nombreProducto);


	  $.ajax({

     	url:"ajax/productos.ajax.php",
      	method: "POST",
      	data: datos,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(respuesta){
      	    
      	    $(nuevaDescripcionProducto).attr("idProducto", respuesta["id"]);
      	    $(nuevaCantidadProducto).attr("stock", respuesta["stock"]);
      	    $(nuevaCantidadProducto).attr("nuevoStock", Number(respuesta["stock"])-1);
      	    $(nuevoPrecioProducto).val(respuesta["precio_venta"]);
      	    $(nuevoPrecioProducto).attr("precioReal", respuesta["precio_venta"]);

  	      // AGRUPAR PRODUCTOS EN FORMATO JSON

	        //listarProductos()

      	}

      })
})


/*=============================================
MODIFICAR LA CANTIDAD
=============================================*/

$(".formularioVenta").on("change", "input.nuevaCantidadProducto", function(){

	var precio = $(this).parent().parent().children(".ingresoPrecio").children(".nuevoPrecioProducto");

    console.log("Precio:",precio.val());
    
    var precioFinal = $(this).val() * precio.attr("precioReal");
	
	precio.val(precioFinal);

	var nuevoStock = Number($(this).attr("stock")) - $(this).val();

	$(this).attr("nuevoStock", nuevoStock);

	if(Number($(this).val()) > Number($(this).attr("stock"))){

		/*=============================================
		SI LA CANTIDAD ES SUPERIOR AL STOCK REGRESAR VALORES INICIALES
		=============================================*/

		$(this).val(1);

		$(this).attr("nuevoStock", $(this).attr("stock"));

		var precioFinal = $(this).val() * precio.attr("precioReal");

		precio.val(precioFinal);

		sumarTotalPrecios();

		swal({
	      title: "La cantidad supera el Stock",
	      text: "¡Sólo hay "+$(this).attr("stock")+" unidades!",
	      icon: "error",
	      button: "¡Cerrar!"
	    });

	    return;

	}

	// SUMAR TOTAL DE PRECIOS
    sumarTotalPrecios()

	// AGREGAR IMPUESTO
    agregarImpuesto()

    // AGRUPAR PRODUCTOS EN FORMATO JSON

    //listarProductos()
    
})


/*=============================================
SUMAR TODOS LOS PRECIOS
=============================================*/

function sumarTotalPrecios(){

	var precioItem = $(".nuevoPrecioProducto");
	
	var arraySumaPrecio = [];  

	for(var i = 0; i < precioItem.length; i++){

		 arraySumaPrecio.push(Number($(precioItem[i]).val()));
		
		 
	}

    console.log("Array Precios:",arraySumaPrecio)
    
	function sumaArrayPrecios(total, numero){

		return total + numero;

	}

	var sumaTotalPrecio = arraySumaPrecio.reduce(sumaArrayPrecios);
	
	$("#nuevoTotalVenta").val(sumaTotalPrecio);
	$("#totalVenta").val(sumaTotalPrecio);
	$("#nuevoTotalVenta").attr("total",sumaTotalPrecio);


}

/*=============================================
FUNCIÓN AGREGAR IMPUESTO
=============================================*/

function agregarImpuesto(){

	var impuesto = $("#nuevoImpuestoVenta").val();
	var precioTotal = $("#nuevoTotalVenta").attr("total");

	var precioImpuesto = Number(precioTotal * impuesto/100);

	var totalConImpuesto = Number(precioImpuesto) + Number(precioTotal);
	
	$("#nuevoTotalVenta").val(totalConImpuesto);

	$("#totalVenta").val(totalConImpuesto);

	$("#nuevoPrecioImpuesto").val(precioImpuesto);

	$("#nuevoPrecioNeto").val(precioTotal);

}

/*=============================================
CUANDO CAMBIA EL IMPUESTO
=============================================*/

$("#nuevoImpuestoVenta").change(function(){

	agregarImpuesto();

});

// PONER FORMATO AL TOTAL
$("#nuevoTotalVenta").number(true, 2);