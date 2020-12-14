$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip(); 

	$("#modalAgregarTecnico, #modalVerTecnico, #modalEditarTecnico").draggable({
		  handle: ".modal-header"
	});

});

/*=============================================
AGREGAR PROVEEDOR: ACTIVAR LOS INPUT´s
=============================================*/
$("#modalAgregarTecnico").on('show.bs.modal',function() {
	$(":input").prop("readonly",false);	
    $("#NuevoEstatus").attr("disabled",false);
	$(this).find("[autofocus]:first").focus();
});

/*=============================================
ACTIVAR EL INPUT DE Buscar del DATATABLE
=============================================*/
$("#modalVerTecnico").on('hidden.bs.modal',function() {
    $(':input[type="search"]').prop('readonly',false);
});


/*=============================================
VISUALIZAR TECNICOS
readonly onmousedown="return false;" para que no se pueda copiar selección
=============================================*/
$("#TablaTecnicos").on("click", ".btnVerTecnico", function(){

	var idTecnico = $(this).attr("idTecnico");
	
	//inputs solo lectura 
		$(":input").prop("readonly",true);
	//select solo lectura 
		$("#VerEstado").attr("disabled",true);
		$("#NacimientoEstado").attr("disabled","disabled");
		$("#VerBanco").attr("disabled","disabled");
		$("#VerNacimientoEstado").attr("disabled","disabled");
		$("#VerAlmacen").attr("disabled","disabled");
		$("#VerEstatus").attr("disabled","disabled");

		//$("#OcultarBoton").hide();
		
	var datos = new FormData();
    datos.append("idTecnico", idTecnico);
    //console.log(idTecnico);
    $.ajax({
      url:"ajax/tecnicos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta, status){
      //console.log(respuesta, status);
	        /*$("#idTecnico").val(respuesta["id"]);*/
		   $("#VerNombre").val(respuesta["nombre"]);
		   $("#VerRfc").val(respuesta["rfc"]);
           $("#verCurp").val(respuesta["curp"]);
		   $("#VerDireccion").val(respuesta["direccion"]);
		   $("#VerCp").val(respuesta["cp"]);
           $("#VerCiudad").val(respuesta["ciudad"]);
           $("#VerEstado").val(respuesta["estado"]);
		   $("#VerEmail").val(respuesta["email"]);
		   $("#VerTelefono").val(respuesta["telefonos"]);
		   $("#VerLicencia").val(respuesta["numero_licencia"]);
		   $("#VerSeguro").val(respuesta["numero_imss"]);
		   $("#VerExpediente").val(respuesta["expediente"]);
		   $("#VerUsuario").val(respuesta["usuario"]);
		   $("#VerContrasena").val(respuesta["contrasena"]);
		   $("#VerBanco").val(respuesta["banco"]);
		   $("#VerCuenta").val(respuesta["num_cuenta"]);
		   $("#VerClabe").val(respuesta["clabe"]);
		   $("#VerNacimientoEstado").val(respuesta["edo_nacimiento"]);
		   $("#VerAlmacen").val(respuesta["alm_asignado"]);
		   $("#VerEstatus").val(respuesta["status"]);

	  },
	  error:function(respuesta, status){
		  console.log(respuesta, status);
	  }

  	})
})


/*=============================================
EDITAR TECNICOS
readonly onmousedown="return false;" para que no se pueda copiar selección
=============================================*/
$("#TablaTecnicos").on("click", ".btnEditarTecnico", function(){

	//inputs solo lectura 
		$(":input").prop("readonly",false);
    $("#EditarEstado").attr("disabled",false);
	var idTecnico = $(this).attr("idTecnico");
	
	var datos = new FormData();
    datos.append("idTecnico", idTecnico);
    //console.log(idTecnico);
    $.ajax({
      url:"ajax/tecnicos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta, status){
      //console.log(respuesta);
	       $("#idTecnico").val(respuesta["id"]);
		   $("#EditarNombre").val(respuesta["nombre"]);
		   $("#EditarRfc").val(respuesta["rfc"]);
           $("#EditarCurp").val(respuesta["curp"]);
		   $("#EditarDireccion").val(respuesta["direccion"]);
		   $("#EditarCp").val(respuesta["cp"]);
           $("#EditarCiudad").val(respuesta["ciudad"]);
           $("#EditarEstado").val(respuesta["estado"]);
		   $("#EditarEmail").val(respuesta["email"]);
		   $("#EditarTelefono").val(respuesta["telefonos"]);
		   $("#EditarLicencia").val(respuesta["numero_licencia"]);
		   $("#EditarSeguro").val(respuesta["numero_imss"]);
		   $("#EditarExpediente").val(respuesta["expediente"]);
		   $("#EditarUsuario").val(respuesta["usuario"]);
		   $("#EditarContrasena").val(respuesta["contrasena"]);
		   $("#EditarBanco").val(respuesta["banco"]);
		   $("#EditarCuenta").val(respuesta["num_cuenta"]);
		   $("#EditarClabe").val(respuesta["clabe"]);
		   $("#EditarNacimientoEstado").val(respuesta["edo_nacimiento"]);
		   $("#EditarAlmacen").val(respuesta["alm_asignado"]);
		   $("#EditarEstatus").val(respuesta["status"]);
		   
	  },
	  error:function(respuesta, status){
		  console.log(respuesta, status);
	  }

  	})
})

$('#TablaTecnicos').dataTable( {
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
	 ]
	 
 }).DataTable();
