var tabla;


$("#modalAgregarMovto, #modalEditarMovto").draggable({
	  handle: ".modal-header"
});

//Función que se ejecuta al inicio
function init(){

	listarMovtos();
    
    $("#formularioAgregarMovto").on("submit",function(e){
            agregarMovto(e);	
    })

    $("#formularioEditMovto").on("submit",function(e){
            editarMovto(e);	
    })
	
}



/*=============================================
AGREGAR Movto
=============================================*/
function agregarMovto(e){
	e.preventDefault(); //No se activará la acción predeterminada del evento
	var formData = new FormData($("#formularioAgregarMovto")[0]);
     for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}
	$.ajax({
		url: "ajax/tipomov.ajax.php?op=guardar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,
	    success: function(datos){
         console.log(datos);
            if(datos==""){
               swal.fire({
                  title: "Realizado!!",
                  text: "Movto se ha añadido correctamente",
                  icon: "success",
                  timer: 2500
                  })  //fin swal
                  .then((result)=>{
                    if (result) {
                        $('#modalAgregarMovto').modal('hide')
						$('#TablaMovtos').DataTable().ajax.reload(null, false);
                    }
                  })  //fin .then
               
               }else{
                  swal.fire({
                  title: "Error!!",
                  text: datos,
                  icon: "error",
                  })  //fin swal

               }
            }
	    })
}


/*=============================================
MODIFICAR Movto
=============================================*/
function editarMovto(e){
	e.preventDefault(); //No se activará la acción predeterminada del evento
	var formData = new FormData($("#formularioEditMovto")[0]);
     //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}
	$.ajax({
		url: "ajax/tipomov.ajax.php?op=editar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,
	    success: function(datos){
            console.log(datos);
           if(datos){ 
            $('#TablaMovtos').DataTable().ajax.reload(null, false);
                swal.fire({
                position: 'top-end',
                title: "Realizado",
                text: "Movto ha sido cambiada correctamente",
                icon: "success",
                timer: 2500
                })
                    .then(function(result){
                    if (result) {
                        $('#modalEditarMovto').modal('hide')
                    }
                })
            }else{
                  swal.fire({
                  title: "Error!!",
                  text: datos,
                  icon: "error",
                  })  //fin swal

            }
        } //fin del success
	 })
}

/*=============================================
MOSTRAR Movto
=============================================*/
$("#TablaMovtos").on("click", ".btnEditarMovto", function(){
	var idMovto = $(this).attr("idMovto");
	var datos = new FormData();
	datos.append("idMovto", idMovto);
        //for (var pair of datos.entries()){console.log(pair[0]+ ', ' + pair[1]);}
	$.ajax({
		url: "ajax/tipomov.ajax.php?op=mostrar",
		method: "POST",
      	data: datos,
      	cache: false,
     	contentType: false,
     	processData: false,
     	dataType:"json",
     	success: function(respuesta){
            //console.log(respuesta);
            if(respuesta["id"]==1 || respuesta["id"]==2 || respuesta["id"]==3 ){
                $("#editNombreMov").val(respuesta["nombre_tipo"]);
                $('#editNombreMov').attr("disabled", true); 
            }else{
                $("#editNombreMov").val(respuesta["nombre_tipo"]);
            }
            $("#idMovto").val(respuesta["id"]);
            $("#editClaseMov").val(respuesta["clase"]);
            $('#editClaseMov').attr("disabled", true); 
     		$("#editEstadoMov").val(respuesta["estado"]);
     	},
        error:function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR, textStatus, errorThrown);
        }

	})
})

/*=============================================
DES-ACTIVAR Movto
=============================================*/

$("#TablaMovtos").on("click", ".btnBorrarMovto", function(){

    var idMovto = $(this).attr("idMovto");
    var idEstatus = $(this).attr("idEstado");
	var datos = new FormData();
	datos.append("idMovto", idMovto);
    let idEstado=parseInt(idEstatus)==1?0:1;
	datos.append("idEstado", idEstado);
    let estado=parseInt(idEstatus)==1?"Desactivar":"Activar";
    console.log("idMovto:",idMovto, "idEstatus:",idEstatus, idEstado)
    swal.fire({
      title: "¿Está seguro de "+estado+" Movto. "+idMovto+"? ",
      text: "¡Si no lo está puede cancelar la acción!",
      icon: "warning",
      allowOutsideClick:false,
      allowEscapeKey:true,
      allowEnterKey: true,
      reverseButtons: true,			//invertir botones
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, '+estado,
      cancelButtonText: 'No, cancelar',
    })
    .then((willDelete) => {
      if (willDelete.value) {
        //dow.location = "index.php?ruta=Movtos&idMovto="+idMovto
        $.ajax({
            url: "ajax/tipomov.ajax.php?op=des_activar",
            type: "POST",
            data: datos,
            contentType: false,
            processData: false,
            success: function(datos){
                $('#TablaMovtos').DataTable().ajax.reload(null, false);
                swal.fire({
                    title: "¡Realizado!",
                    text: "Movto ha sido cambiado correctamente",
                    icon: "success",
                    timer: 2500
                })
            }
        })        
      }
    });    
    
})

// LISTAR EN EL DATATABLE REGISTROS DE LA TABLA Movtos
function listarMovtos(){
  tabla=$('#TablaMovtos').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
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
            {
             text: 'Copiar',
             extend: 'copy'
             },
             {
                extend: 'csvHtml5',
                title: 'infoCsv'
            },            
            {
                extend: 'excelHtml5',
                title: 'infoExcel'
            },            
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
        ],
        initComplete: function () {			//botones pequeños y color verde
          var btns = $('.dt-button');
          btns.removeClass('dt-button');
          btns.addClass('btn btn-success btn-sm');
        }, 
		"ajax":
				{
					url: 'ajax/tipomov.ajax.php?op=listarmov',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);
					}
				},
		"bDestroy": true,
		"iDisplayLength": 10//Paginación
	    //"order": [[ 0, "ASC" ]]//Ordenar (columna,orden)
	}).DataTable();    
    
} 

$(document).ready(function (){ 
	$('#TablaMovtos tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );
});

$('#modalAgregarMovto #modalEditarMovto').on('show.bs.modal', function (e) {
  //$(':input:visible:enabled:first').focus();
  $(':input:text:visible:first').focus();
})

 
/*================ AL SALIR DEL MODAL DE EDICION  RESETEAR FORMULARIO==================*/
$("#modalAgregarMovto").on('hidden.bs.modal', ()=> {
    $('#formularioAgregarMovto')[0].reset();
    
});

$("#modalEditarMovto").on('hidden.bs.modal', ()=> {
    $('#formularioEditMovto')[0].reset();
    $('#editNombreMov').attr("disabled", false); 
});



init();


