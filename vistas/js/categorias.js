$("#modalAgregarCategoria, #modalEditarCategoria").draggable({
      handle: ".modal-header"
});

$('#btnVer').click(function(){
categoria=1;
var URLactual = $(location).attr('href');
console.log(URLactual);
 window.location = "index.php?ruta=categorias&categoria="+categoria;
})

$('.TablaCategorias').DataTable( {
	   "lengthMenu": [ [10, 25, 50,100, -1], [10, 25, 50, 100, "Todos"] ],
     "language": {
      "url": "extensiones/espanol.json",
    },
        dom: '<clear>Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                title: "NUNOSCO Conexiones",
                customize: function ( doc ) {
                    pdfMake.createPdf(doc).open();
                },
            },
            
       {
            extend: 'print',
            text: 'Imprimir',
            autoPrint: false            //TRUE para abrir la impresora
        }
        ],
		
    } );

/*=============================================
EDITAR CATEGORIA
=============================================*/
$(".TablaCategorias").on("click", ".btnEditarCategoria", function(){

	var idCategoria = $(this).attr("idCategoria");

	var datos = new FormData();
	datos.append("idCategoria", idCategoria);
        // Display the key/value pairs
        for (var pair of datos.entries()){console.log(pair[0]+ ', ' + pair[1]);}
	$.ajax({
		url: "ajax/categorias.ajax.php",
		method: "POST",
      	data: datos,
      	cache: false,
     	contentType: false,
     	processData: false,
     	dataType:"json",
     	success: function(respuesta){
            console.log(respuesta);
     		$("#editarCategoria").val(respuesta["categoria"]);
     		$("#idCategoria").val(respuesta["id"]);

     	}

	})


})

/*=============================================
ELIMINAR CATEGORIA
=============================================*/
$(".TablaCategorias").on("click", ".btnEliminarCategoria", function(){

	 var idCategoria = $(this).attr("idCategoria");

    swal({
      title: "¿Está seguro de borrar la categoría?",
      text: "¡Si no lo está puede cancelar la acción!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        window.location = "index.php?ruta=categorias&idCategoria="+idCategoria;
      } else {
        swal("Acción Cancelada!");
      }
    });    
    
})

