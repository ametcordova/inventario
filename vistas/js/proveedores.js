$("#modalAgregarProveedor, #modalEditarProveedor").draggable({
      handle: ".modal-header"
});


/*=============================================
AGREGAR PROVEEDOR: ACTIVAR LOS INPUT´s
=============================================*/
$("#modalAgregarProveedor").on('show.bs.modal',function() {
	$(":input").prop("readonly",false);	
    $("#NuevoEstado").attr("disabled",false);
	$(this).find("[autofocus]:first").focus();
});

/*=============================================
ACTIVAR EL INPUT DE Buscar del DATATABLE
=============================================*/
$("#modalEditarProveedor").on('hidden.bs.modal',function() {
	//$(":input").prop("readonly",false);	//tambien funciona
    //$("input").attr("readonly",false);	//tambien funciona
    $(':input[type="search"]').prop('readonly',false);
});

/*=============================================
EDITAR PROVEEDOR
=============================================*/
$(".TablaProveedores").on("click", ".btnEditarProveedor", function(){

/*
$(".btn-info").click(function(){
  console.log("entra info");
 $(":input").prop("readonly",true);
});


$(".btn-warning").click(function(){
  console.log("entra warning");
 $(":input").removeAttr('readonly');
 $(":input").prop("readonly",false);
});
*/
    
    $(":input").prop("readonly",false);
    $("#EditarEstado").attr("disabled",false);
    $("#OcultarBoton").show();
    
	var idProveedor = $(this).attr("idProveedor");
	var datos = new FormData();
    datos.append("idProveedor", idProveedor);
    console.log(idProveedor);
    $.ajax({

      url:"ajax/proveedores.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta, status){
      console.log(respuesta, status);
      	   $("#idProveedor").val(respuesta["id"]);
	       $("#EditarNombre").val(respuesta["nombre"]);
	       $("#EditarRfc").val(respuesta["rfc"]);
           $("#EditarDireccion").val(respuesta["direccion"]);
           $("#EditarCp").val(respuesta["codpostal"]);
           $("#EditarCiudad").val(respuesta["ciudad"]);
	       $("#EditarEmail").val(respuesta["email"]);
	       $("#EditarTelefono1").val(respuesta["telefono"]);
	       $("#EditarContacto").val(respuesta["contacto"]);
	       $("#EditarTelefono2").val(respuesta["tel_contacto"]);
           $("#EditarEmail2").val(respuesta["email_contacto"]);
           $("#EditarEstado").val(respuesta["estatus"]);
	  }

  	})
})


/*=============================================
VISUALIZAR PROVEEDOR
readonly onmousedown="return false;" para que no se pueda copiar selección
=============================================*/
$(".TablaProveedores").on("click", ".btnVerProveedor", function(){

	var idProveedor = $(this).attr("idProveedor");
	$(":input").prop("readonly",true);	//inputs solo lectura 
    $("#EditarEstado").attr("disabled",true);
    $("#OcultarBoton").hide();
	var datos = new FormData();
    datos.append("idProveedor", idProveedor);
    console.log(idProveedor);
    $.ajax({

      url:"ajax/proveedores.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta, status){
      console.log(respuesta, status);
      	   $("#idProveedor").val(respuesta["id"]);
	       $("#EditarNombre").val(respuesta["nombre"]);
	       $("#EditarRfc").val(respuesta["rfc"]);
           $("#EditarDireccion").val(respuesta["direccion"]);
           $("#EditarCp").val(respuesta["codpostal"]);
           $("#EditarCiudad").val(respuesta["ciudad"]);
	       $("#EditarEmail").val(respuesta["email"]);
	       $("#EditarTelefono1").val(respuesta["telefono"]);
	       $("#EditarContacto").val(respuesta["contacto"]);
	       $("#EditarTelefono2").val(respuesta["tel_contacto"]);
           $("#EditarEmail2").val(respuesta["email_contacto"]);
           $("#EditarEstado").val(respuesta["estatus"]);
	  }

  	})

})


/*=============================================
ELIMINAR CLIENTE
=============================================*/
$(".TablaProveedores").on("click", ".btnEliminarProveedor", function(){

	var idProveedor = $(this).attr("idProveedor");

swal({
  title: "¿Está seguro de borrar el Proveedor?",
  text: "¡Si no lo está puede cancelar la acción!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
      window.location = "index.php?ruta=proveedores&idProveedor="+idProveedor;
  };
});    
    
})