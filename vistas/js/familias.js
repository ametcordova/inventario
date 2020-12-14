$("#modalAgregarFamilia, #modalEditarFamilia").draggable({
	 handle: ".modal-header"
});


$('#modalAgregarFamilia, #modalEditarFamilia').on('show.bs.modal', function (e) {
  //$(':input:visible:enabled:first').focus();
  $(':input:text:visible:first').focus();
})


$('.TablaFamilias').dataTable( {
	   "lengthMenu": [ [10, 25, 50,100, -1], [10, 25, 50, 100, "Todos"] ],
       "language": {
		"sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros &nbsp",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrar registros del _START_ al _END_ de un total de _TOTAL_",
		"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
		"sInfoPostFix":    "",           
        "sSearch":         "Buscar:",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
		"sFirst":    "Primero",
		"sLast":     "Último",
		"sNext":     "Siguiente",
		"sPrevious": "Anterior"}
        },
		"oAria": {
			"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
			"sSortDescending": ": Activar para ordenar la columna de manera descendente"
		},
        dom: '<clear>Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                title: "AdminLTE",
                customize: function ( doc ) {
                    pdfMake.createPdf(doc).open();
                },
            },
            
       {
            extend: 'print',
            text: 'Imprimir',
            autoPrint: false            //TRUE para abrir la impresora
        }
        ],initComplete: function () {			//botones pequeños y color verde
          var btns = $('.dt-button');
          btns.removeClass('dt-button');
          btns.addClass('btn btn-success btn-sm');
        },
    } ).DataTable();

/*=============================================
EDITAR FAMILIAS
=============================================*/
$(".TablaFamilias").on("click", ".btnEditarFamilia", function(){

	var idFamilia = $(this).attr("idFamilia");
  console.log("datos Fam:",idFamilia);
	var datos = new FormData();
	datos.append("idFamilia", idFamilia);
        // Display the key/value pairs
        //for (var pair of datos.entries()){console.log(pair[0]+ ', ' + pair[1]);}
        
	$.ajax({
		url: "ajax/familias.ajax.php",
		    method: "POST",
      	data: datos,
      	cache: false,
     	contentType: false,
     	processData: false,
     	dataType:"json",
     	success: function(respuesta){
            //console.log(respuesta);
     		$("#editarFamilia").val(respuesta["familia"]);
     		$("#idFamilia").val(respuesta["id"]);

     	}

	})


})

/*=============================================
ELIMINAR FAMILIAS
=============================================*/
$(".TablaFamilias").on("click", ".btnEliminarFamilia", function(){

	 var idFamilia = $(this).attr("idFamilia");

   Swal.fire({
    title: '¿Está seguro de borrar la categoría?',
    text: "¡Si no lo está puede cancelar la acción!",
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, Borrarlo!',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=familias&idFamilia="+idFamilia;
    } else {
      Swal.fire('Acción Cancelada!');
    }
  })
    
})

$(document).ready(function (){ 
  $('.TablaFamilias tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );
});


/*$('#btnVerFamilia').click(function(){
familia=1;
var URLactual = $(location).attr('href');
console.log(URLactual);
 window.location = "index.php?ruta=familias&familia="+familia;
})
*/