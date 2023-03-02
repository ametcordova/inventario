/*=============================================
CARGAR LA TABLA DINÁMICA DE PRODUCTOS
=============================================*/

 /*$.ajax({

	url: "ajax/datatable-productos.ajax.php",
	success:function(respuesta){
		
 		console.log("respuesta", respuesta);

 	}

 })*/

var perfilOculto = $("#perfilOculto").val();
//tablaSalidasAlmacen=$('#dt-entradasalmacen').dataTable(
tableProduct=$('.tablaProductos').dataTable( {
    "ajax": "ajax/datatable-productos.ajax.php?perfilOculto="+perfilOculto,
    "deferRender": true,
    "retrieve": true,
    "processing": true,
    "stateSave": true,
    "lengthMenu": [ [10, 25, 50,100, -1], [10, 25, 50, 100, "Todos"] ],
    "sPaginationType": "full_numbers",	
    "language": {
      "url": "extensiones/espanol.json",
    },   
        dom: '<clear>Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
       {
            extend: 'print',
            text: 'Imprimir',
            autoPrint: false            //TRUE para abrir la impresora
        }
        ],initComplete: function () {
          var btns = $('.dt-button');
          btns.removeClass('dt-button');
          btns.addClass('btn btn-success btn-sm');
        },
        "columnDefs": [
          {"className": "dt-center", "targets": [1,6,7,8,9]},
          //{"className": "dt-right", "targets": [3,4]}				//"_all" para todas las columnas
          ],         
        "order": [[ 3, 'asc' ]] //Ordenar (columna,orden)
}).DataTable();

/*=============================================
CAPTURANDO LA CATEGORIA PARA ASIGNAR CÓDIGO
=============================================*/
$("#nuevaCategoria").change(function(){

	var idCategoria = $(this).val();

	var datos = new FormData();
  	datos.append("idCategoria", idCategoria);

  	$.ajax({

      url:"ajax/productos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){

      	if(!respuesta){

      		var nuevoCodigo = idCategoria+"00";
      		$("#nuevoCodigo").val(nuevoCodigo);

      	}else{

      		var nuevoCodigo = Number(respuesta["codigo"]) + 1;
          	$("#nuevoCodigo").val(nuevoCodigo);

      	}
                
      }

  	})

})

/*=============================================
AGREGANDO PRECIO DE VENTA
=============================================*/
$("#nuevoPrecioCompra, #editarPrecioCompra").change(function(){

	if($(".porcentaje").prop("checked")){

		var valorPorcentaje = $(".nuevoPorcentaje").val();
		
		var porcentaje = Number(($("#nuevoPrecioCompra").val()*valorPorcentaje/100))+Number($("#nuevoPrecioCompra").val());

		var editarPorcentaje = Number(($("#editarPrecioCompra").val()*valorPorcentaje/100))+Number($("#editarPrecioCompra").val());

		$("#nuevoPrecioVenta").val(porcentaje);
		$("#nuevoPrecioVenta").prop("readonly",true);

		$("#editarPrecioVenta").val(editarPorcentaje);
		$("#editarPrecioVenta").prop("readonly",true);

	}
        var datos = new FormData();
        //datos.append("idProducto", idProducto);
        for (var pair of datos.entries()){console.log(pair[0]+ ', ' + pair[1]);}

})


/*=============================================
CAMBIO DE PORCENTAJE
=============================================*/
$(".nuevoPorcentaje").change(function(){

	if($(".porcentaje").prop("checked")){

		var valorPorcentaje = $(this).val();      //con this obtenemos lo que trae .nuevoPorcentaje
		
		var porcentaje = Number(($("#nuevoPrecioCompra").val()*valorPorcentaje/100))+Number($("#nuevoPrecioCompra").val());

		var editarPorcentaje = Number(($("#editarPrecioCompra").val()*valorPorcentaje/100))+Number($("#editarPrecioCompra").val());

		$("#nuevoPrecioVenta").val(porcentaje);
		$("#nuevoPrecioVenta").prop("readonly",true);

		$("#editarPrecioVenta").val(editarPorcentaje);
		$("#editarPrecioVenta").prop("readonly",true);
        
        console.log("entra cambio de porcentaje");

        //var datos = new FormData();
        var datos = new FormData($("form")[0]);
        //datos.append("idProducto", idProducto);
        for (var pair of datos.entries()){console.log(pair[0]+ ', ' + pair[1]);}

	}


})

$(".porcentaje").on("ifUnchecked",function(){

	// $("#nuevoPrecioVenta").prop("readonly",false);
	// $("#editarPrecioVenta").prop("readonly",false);

})

$(".porcentaje").on("ifChecked",function(){

	// $("#nuevoPrecioVenta").prop("readonly",true);
	// $("#editarPrecioVenta").prop("readonly",true);

})

/*=============================================
SUBIENDO LA FOTO DEL PRODUCTO
=============================================*/

$(".nuevaImagen").change(function(){

	var imagen = this.files[0];
	
	/*=============================================
  	VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
  	=============================================*/

  	if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){

  		$(".nuevaImagen").val("");

  		 swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen debe estar en formato JPG o PNG!",
		      icon: "error",
		      button: "¡Cerrar!"
		    });

  	}else if(imagen["size"] > 2000000){

  		$(".nuevaImagen").val("");

  		 swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen no debe pesar más de 2MB!",
		      icon: "error",
		      button: "¡Cerrar!"
		    });

  	}else{

  		var datosImagen = new FileReader;
  		datosImagen.readAsDataURL(imagen);

  		$(datosImagen).on("load", function(event){

  			var rutaImagen = event.target.result;

  			$(".previsualizar").attr("src", rutaImagen);

  		})

  	}
})


/*=============================================
EDITAR PRODUCTO
=============================================*/

$(".tablaProductos tbody").on("click", "button.btnEditarProducto", function(){

	var idProducto = $(this).attr("idProducto");
	
	var datos = new FormData();
        datos.append("idProducto", idProducto);
        //for (var pair of datos.entries()){console.log(pair[0]+ ', ' + pair[1]);}
    $.ajax({
      url:"ajax/productos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){
          //console.log(respuesta);
          var datosCategoria = new FormData();
          datosCategoria.append("idCategoria",respuesta["id_categoria"]);
          //console.log(respuesta["id_categoria"]);
           $.ajax({
            
              url:"ajax/categorias.ajax.php",
              method: "POST",
              data: datosCategoria,
              cache: false,
              contentType: false,
              processData: false,
              dataType:"json",
              success:function(respuesta0){
                  
                  $("#editarCategoria").val(respuesta0["id"]);
                  $("#editarCategoria").html(respuesta0["categoria"]);

              },
              error:function(response){
                   console.log(response);
               }

          })

          var datosMedida = new FormData();
          datosMedida.append("idMedida",respuesta["id_medida"]);
          //console.log(respuesta["id_medida"]);
           $.ajax({

              url:"ajax/medidas.ajax.php",
              method: "POST",
              data: datosMedida,
              cache: false,
              contentType: false,
              processData: false,
              dataType:"json",
              success:function(respuesta1){
                //console.log(respuesta1);
                  $("#editarMedida").val(respuesta1["id"]);
                  $("#editarMedida").html(respuesta1["medida"]);

              },
               error:function(response, status){
                   console.log(response, status);
               }

          })
          
           $("#editarIdProducto").val(respuesta["id"]);
		   
           $("#editarCodigo").val(respuesta["codigo"]);
		   
           $("#editarCodInterno").val(respuesta["codigointerno"]);

           $("#editarDescripcion").val(respuesta["descripcion"]);

           $("#editarStock").val(respuesta["stock"]);
		   
           $("#editarMinimo").val(respuesta["minimo"]);

           $("#editarsku").val(respuesta["sku"]);

          // SI LLEVA SERIE, LO CHECKEA
          if(respuesta['conseries']==1){
            $('.conseries').iCheck('check');       
          }

          // SI LLEVA SERIE, LO CHECKEA
          if(respuesta['esfo']==1){
            $('.editaFO').iCheck('check');       
          }

          // SI LLEVA SERIE, LO CHECKEA
          if(respuesta['escobre']==1){
            $('.editaCobre').iCheck('check');       
          }

          // SI LLEVA SERIE, LO CHECKEA
          if(respuesta['esconstruccion']==1){
            $('.editaConstruccion').iCheck('check');       
          }

           if(respuesta["imagen"] != ""){

           	$("#imagenActual").val(respuesta["imagen"]);

           	$(".previsualizar").attr("src",  respuesta["imagen"]);

           }
           $("#editarEstatus").val(respuesta["estado"]);
           $("#editarListar").val(respuesta["listar"]);
      
    },
   error:function(response, status){
   console.log(response, status);
   }

  })

})


/*=============================================
ELIMINAR PRODUCTO
=============================================*/

$(".tablaProductos tbody").on("click", "button.btnEliminarProducto", function(){

	var idProducto = $(this).attr("idProducto");
	var codigo = $(this).attr("codigo");
	var imagen = $(this).attr("imagen");

    swal({
      title: "¿Está seguro de borrar El Producto?",
      text: "¡Si no lo está puede cancelar la acción!",
      icon: "warning",
      buttons: ["Cancelar", "Sí, Borrar producto"],
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        window.location = "index.php?ruta=productos&idProducto="+idProducto+"&imagen="+imagen+"&codigo="+codigo;
      }
    });       
    
})

// MODAL PARA VISUALIZAR IMAGEN DEL PRODUCTO -->
$(".tablaProductos tbody").on("click", ".idImagen", function(){
//console.log(this);
    let descripcionProd=($(this).attr("descripcionProd"));
    let codeProd=($(this).attr("codigointerno"));
	let muestra_imagen = ($(this).attr("src"));
	console.log(codeProd);
    $('#ModalCenterTitle').html("");
    $('#ModalCenterTitle').html('Imagen de: '+descripcionProd+' Cod: '+codeProd);
	$('#imagen-modal').attr('src', muestra_imagen);
})

$("#agregarProd").click(function(){
    //$("#imagenActual").val("");
    $(".previsualizar").attr("src","vistas/img/productos/default/default.jpg");
})


// ============ AL SALIR DEL MODAL DE EDICION  ====================
$("#modalEditarProducto").on('hidden.bs.modal', function () {
    //$(".tbodypromo").empty();	//VACIA LOS DATOS DE LA PROMOCION DEL TBODY
     // desactiva el check de promo
      $('.conseries').iCheck('uncheck');
      $('.editaFO').iCheck('uncheck');
      $('.editaCobre').iCheck('uncheck');
      $('.editaConstruccion').iCheck('uncheck');
      $('.listar').iCheck('uncheck');
   });
   
/*
//PARA CHECAR SI COMO VAN LOS DATOS
$("#checar").click(function(){
var datos = new FormData($("#formulario")[0]);
        //datos.append("idProducto", idProducto);
        for (var pair of datos.entries()){
            console.log(pair[0]+ ', ' + pair[1]);
        }
window.setTimeout(function(){
        
                  }, 10600);    
});
*/

