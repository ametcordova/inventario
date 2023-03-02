
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip(); 

	$("#modalAgregarTecnico, #modalVerTecnico, #modalEditarTecnico").draggable({
		  handle: ".modal-header"
	});

});

/*=============================================
AGREGAR TECNICO: ACTIVAR LOS INPUT´s
=============================================*/
$("#modalAgregarTecnico").on('show.bs.modal',function() {
	$(":input").prop("readonly",false);	
    $("#NuevoEstatus").attr("disabled",false);
	$("#NacimientoEstado").attr("disabled",false);
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
	console.log(idTecnico);
	//inputs solo lectura 
		$(":input").prop("readonly",true);
	//select solo lectura 
		$("#VerEstado").attr("disabled","disabled");
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
      //console.log(respuesta[0], status);
	        /*$("#idTecnico").val(respuesta["id"]);*/
		   $("#VerNombre").val(respuesta[0]["nombre"]);
		   $("#VerRfc").val(respuesta[0]["rfc"]);
           $("#VerCurp").val(respuesta[0]["curp"]);
		   $("#VerDireccion").val(respuesta[0]["direccion"]);
		   $("#VerCp").val(respuesta[0]["cp"]);
           $("#VerCiudad").val(respuesta[0]["ciudad"]);
           $("#VerEstado").val(respuesta[0]["estado"]);
		   $("#VerEmail").val(respuesta[0]["email"]);
		   $("#VerTelefono").val(respuesta[0]["telefonos"]);
		   $("#VerLicencia").val(respuesta[0]["numero_licencia"]);
		   $("#VerSeguro").val(respuesta[0]["numero_imss"]);
		   $("#VerExpediente").val(respuesta[0]["expediente"]);
		   $("#VerUsuario").val(respuesta[0]["usuario"]);
		   $("#VerContrasena").val(respuesta[0]["contrasena"]);
		   $("#VerBanco").val(respuesta[0]["banco"]);
		   $("#VerCuenta").val(respuesta[0]["num_cuenta"]);
		   $("#VerClabe").val(respuesta[0]["clabe"]);
		   $("#VerNacimientoEstado").val(respuesta[0]["edo_nacimiento"]);
		   $("#VerAlmacen").val(respuesta[0]["alm_asignado"]);
		   $("#VerEstatus").val(respuesta[0]["status"]);

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
	console.log(idTecnico);

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
	       $("#idTecnico").val(respuesta[0]["id"]);
		   $("#EditarNombre").val(respuesta[0]["nombre"]);
		   $("#EditarRfc").val(respuesta[0]["rfc"]);
           $("#EditarCurp").val(respuesta[0]["curp"]);
		   $("#EditarDireccion").val(respuesta[0]["direccion"]);
		   $("#EditarCp").val(respuesta[0]["cp"]);
           $("#EditarCiudad").val(respuesta[0]["ciudad"]);
           $("#EditarEstado").val(respuesta[0]["estado"]);
		   $("#EditarEmail").val(respuesta[0]["email"]);
		   $("#EditarTelefono").val(respuesta[0]["telefonos"]);
		   $("#EditarLicencia").val(respuesta[0]["numero_licencia"]);
		   $("#EditarSeguro").val(respuesta[0]["numero_imss"]);
		   $("#EditarExpediente").val(respuesta[0]["expediente"]);
		   $("#EditarUsuario").val(respuesta[0]["usuario"]);
		   $("#EditarContrasena").val(respuesta[0]["contrasena"]);
		   $("#EditarBanco").val(respuesta[0]["banco"]);
		   $("#EditarCuenta").val(respuesta[0]["num_cuenta"]);
		   $("#EditarClabe").val(respuesta[0]["clabe"]);
		   $("#EditarNacimientoEstado").val(respuesta[0]["edo_nacimiento"]);
		   $("#EditarAlmacen").val(respuesta[0]["alm_asignado"]);
		   $("#EditarEstatus").val(respuesta[0]["status"]);
		   
	  },
	  error:function(respuesta, status){
		  console.log(respuesta, status);
	  }

  	})
})

$('#TablaTecnicos').dataTable( {
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
	 initComplete: function () {
	   var btns = $('.dt-button');
	   btns.removeClass('dt-button');
	   btns.addClass('btn btn-success btn-sm');
	 }
 }).DataTable();
