$("#modalAgregarAlmacen, #modalEditarAlmacen").draggable({
      handle: ".modal-header"
});


/*=============================================
EDITAR CLIENTE
=============================================*/
$("#activarDTStore").on("click", ".btnEditarAlmacen", function(){
	let idAlmacen = $(this).attr("idAlmacen");
	let datos = new FormData();
    datos.append("idAlmacen", idAlmacen);

    $.ajax({
      url:"ajax/crear-almacen.ajax.php?op=editStore",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){
        //console.log(respuesta);
        $("#idAlmacen").val(respuesta["id"]);
        $("#editarAlmacen").val(respuesta["nombre"]);
        $("#editarUbicacion").val(respuesta["ubicacion"]);
        $("#editarResponsable").val(respuesta["responsable"]);
        $("#editarEmail").val(respuesta["email"]);
        $("#editarTelefono").val(respuesta["telefono"]);
        $("#editStatusAlmacen").val(respuesta["estado"]);
	    },
      error: function(e){
        console.log(e.responseText,e.status);
      }

  	})

})

/*=============================================
ELIMINAR CLIENTE
=============================================*/
$("#activarDTStore").on("click", ".btnEliminarAlmacen", function(){

	var idAlmacen = $(this).attr("idAlmacen");

swal({
  title: "¿Está seguro de borrar el Almacen?",
  text: "¡Si no lo está puede cancelar la acción!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
      window.location = "index.php?ruta=crear-almacen&idAlmacen="+idAlmacen;
  };
});    
    
})

/*=============================================*/
tblAlmacen=$('#activarDTStore').dataTable(
  {
    "aProcessing": true,//Activamos el procesamiento del datatables
    "aServerSide": true,//Paginación y filtrado realizados por el servidor
    "lengthMenu": [ [10, 15, 25, 50, 100, -1], [10, 15, 25, 50, 100, "Todos"] ],
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
    "sFirst":    "<<",
    "sLast":     ">>",
    "sNext":     ">",
    "sPrevious": "<"}
        },
    "oAria": {
      "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    },
    "pagingType": "full_numbers",
        dom: '<clear>Bfrtip',
        buttons: [
            {
             text: 'Copiar',
             extend: 'copy'
             },
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
            className: 'btn btn-success btn-sm',
            autoPrint: false            //TRUE para abrir la impresora
        },
        ],
        initComplete: function () {
          var btns = $('.dt-button');
          btns.removeClass('dt-button');
          btns.addClass('btn btn-success btn-sm');
        },  
        "columnDefs": [
          {"className": "dt-center", "targets": [0,6,7]},
          //{"className": "dt-right", "targets": [3,4]}				//"_all" para todas las columnas
          ],    
          select: false,     //se puso a false para poder seleccionar varios filas. true=1 fila
          scrollX: true,
        "ajax":
          {
            url: 'ajax/crear-almacen.ajax.php?op=listarAlmacenes',
            type : "POST",
            dataType : "json",						
            error: function(e){
              console.log(e.responseText);
            }
          },
    "bDestroy": true,
    "iDisplayLength": 10,//Paginación
    "order": [[ 0, 'asc' ]] //Ordenar (columna,orden)
  }).DataTable();    


// ========= FIN LISTAR EN EL DATATABLE REGISTROS DE LA TABLA TABLAOS================