$( "#modalAgregarusuario, #modalEditarUsuario").draggable();
$('#modalAgregarusuario').on('show.bs.modal', function (e) {
  $("#nuevoUsuario").val("");
  $( "input[name='nuevoPassword']" ).val("");
  $(':input:text:visible:first').focus();
})

/*=============================================
SUBIENDO LA FOTO DEL USUARIO
=============================================*/
$(".nuevaFoto").change(function(){

	var imagen = this.files[0];
	
	/*=============================================
  	VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
  	=============================================*/

  	if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){

  		$(".nuevaFoto").val("");
            swal({
              title: "Error al subir la imagen!",
              text: "¡La imagen debe estar en formato JPG o PNG!",
              icon: "warning",
              button: "Cerrar!",
            });

  	}else if(imagen["size"] > 2000000){

  		$(".nuevaFoto").val("");

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

  			$(".previsualizar, .previsualizarEditar").attr("src", rutaImagen);

  		})

  	}
})


/*=============================================
EDITAR USUARIO
=============================================*/
$(".usuariosDatatable").on("click", ".btnEditarUsuario", function(){

	var idUsuario = $(this).attr("idUsuario");
	
	var datos = new FormData();
	datos.append("idUsuario", idUsuario);
    
    console.log(idUsuario);
	$.ajax({
		url:"ajax/usuarios.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function(respuesta){
			$("#editarNombre").val(respuesta["nombre"]);
			$("#editarUsuario").val(respuesta["usuario"]);
			$("#editarPerfil").html(respuesta["perfil"]);        //va html por que es un option
			$("#editarPerfil").val(respuesta["perfil"]);         //tomar el valor en caso que no modif.
			$("#fotoActual").val(respuesta["foto"]);             //tomar el valor en caso que no modif.

			//$("#passwordActual").val("");
			$("#passwordActual").val(respuesta["password"]);

			if(respuesta["foto"] != ""){

				$(".previsualizarEditar").attr("src", respuesta["foto"]);

			}else{

				$(".previsualizarEditar").attr("src", "vistas/img/usuarios/default/anonymous.png");

			}
            

		},
            error: function(respuesta){
            console.log(respuesta);
        }

	});

})

/*=============================================
ACTIVAR USUARIO
=============================================*/
$(".usuariosDatatable").on("click", ".btnActivar", function(){

	var idUsuario = $(this).attr("idUsuario");
	var estadoUsuario = $(this).attr("estadoUsuario");

    var datos = new FormData();
 	datos.append("activarId", idUsuario);
  	datos.append("activarUsuario", estadoUsuario);

  	$.ajax({
	  url:"ajax/usuarios.ajax.php",
	  method: "POST",
	  data: datos,
	  cache: false,
      contentType: false,
      processData: false,
      success: function(respuesta){
            console.log(respuesta);
      		if(window.matchMedia("(max-width:767px)").matches){
                    swal({
                        title: "OK",
                        text: "¡El usuario a sido actualizado!",
                        icon: "success",
                        button: "Cerrar",
						timer: 2000,
                       }).then(function(result){
                        if(result){
                            window.location = "usuarios";
                        }else{
                            window.location = "usuarios";
							//$('.usuariosDatatable').DataTable().ajax.reload(null, false);
                        }
                    });                
	      	}
      },
            error: function(respuesta){
            console.log(respuesta);
     }

	 
  	})
	  if(estadoUsuario == 0){
		console.log(estadoUsuario);
		  $(this).removeClass('btn-success');
		  $(this).addClass('btn-danger');
		  $(this).html('Desactivado');
		  $(this).attr('estadoUsuario',1);

	  }else{
		console.log(estadoUsuario);
		  $(this).addClass('btn-success');
		  $(this).removeClass('btn-danger');
		  $(this).html('Activado');
		  $(this).attr('estadoUsuario',0);

	  }

})

/*=============================================
REVISAR SI EL USUARIO YA ESTÁ REGISTRADO
=============================================*/
$("#nuevoUsuario").change(function(){

	$(".alert").remove();

	//var usuario = $(this).val();
	var usuario = $('#nuevoUsuario').val();

	var datos = new FormData();
	datos.append("validarUsuario", usuario);
     console.log("antes de ajax",usuario);
	 $.ajax({
	    url:"ajax/usuarios.ajax.php",
	    method:"POST",
	    data: datos,
	    cache: false,
	    contentType: false,
	    processData: false,
	    dataType: "json",
	    success:function(respuesta){
	    	console.log(respuesta);
	    	if(respuesta){
				$("#nuevoUsuario").val("");
	    		//$("#nuevoUsuario").parent().after('<div class="alert alert-warning">Este usuario ya existe en la base de datos</div>');
				$("#nuevoUsuario").parent().after('<div class="alert alert-warning alert-dismissible fade show" role="alert">Este usuario ya existe en la base de datos<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				$("input[name='nuevoPassword']").val("");
	    		$("#nuevoUsuario").val("");
	    	}

	    },
            error: function(respuesta){
            console.log(respuesta);
     }

	})
})


/*=============================================
ELIMINAR USUARIO
=============================================*/
$(".usuariosDatatable").on("click", ".btnEliminarUsuario", function(){

  var idUsuario = $(this).attr("idUsuario");
  var fotoUsuario = $(this).attr("fotoUsuario");
  var usuario = $(this).attr("usuario");
  console.log("antes de ajax",idUsuario);
swal({
  title: "¿Está seguro de borrar el usuario?",
  text: "¡Si no lo está puede cancelar la acción!",
  icon: "warning",
  buttons: {
  cancel: {
    text: "Cancelar",
    value: null,
    visible: true,
    className: "botoncancelar",
    closeModal: true,
  },
  confirm: {
    text: "Borrar",
    value: true,
    visible: true,
    className: "botonaceptar",
    closeModal: true
  }
},
  dangerMode: false,
  timer: 5000,
 
})
.then((willDelete) => {
  if (willDelete) {
    window.location = "index.php?ruta=usuarios&idUsuario="+idUsuario+"&usuario="+usuario+"&fotoUsuario="+fotoUsuario;
  }else{
      swal({
        text: "Acción cancelada!",
      });
  }
});    
    
})

/*===================================================================*/
$('.usuariosDatatable').dataTable({
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
	"sUrl":            "",
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
			title: "NUNOSCO",
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
	initComplete: function () {
		var btns = $('.dt-button');
		btns.removeClass('dt-button');
		btns.addClass('btn btn-success btn-sm');
	  },

columnDefs: [{	
	width: "3%",	
	targets: 0		//id
  },
  {
	width: "25%",
	targets: 1		//nombre
  },
  {
	width: "10%",
	targets: 2		//usuario
  },
  {
	className: "dt-center",
	width: "7%",	//foto
	targets: 3
  },
  {
	width: "21%",	//perfil
	targets: 4
  },
  {
	className: "dt-center",
	width: "10%",
	targets: 5		//estado
  },
  {
	className: "dt-center",
	width: "15%",
	targets: 6		//ultima sesion
  },
  {
	className: "dt-center",
	width: "8%",
	targets: 7,		//acciones

  },
],
"drawCallback": function( settings ) {
	$('ul.pagination').addClass("pagination-sm");
},
"destroy": true,
}).DataTable(); 
