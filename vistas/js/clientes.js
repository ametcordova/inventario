$("#modalAgregarCliente, #modalEditarCliente").draggable({
      handle: ".modal-header"
});


/*=============================================
EDITAR CLIENTE
=============================================*/
$(".activarDatatable").on("click", ".btnEditarCliente", function(){

	var idCliente = $(this).attr("idCliente");

	var datos = new FormData();
    datos.append("idCliente", idCliente);
    console.log(datos);
    $.ajax({

      url:"ajax/clientes.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta, status){
      console.log(respuesta, status);
          var dateString = respuesta["fecha_nacimiento"];
          console.log(moment(dateString).format('DD/MM/YYYY'));
      	   $("#idCliente").val(respuesta["id"]);
	       $("#EditarCliente").val(respuesta["nombre"]);
	       $("#EditarDocumento").val(respuesta["rfc"]);
	       $("#EditarEmail").val(respuesta["email"]);
	       $("#EditarTelefono").val(respuesta["telefono"]);
	       $("#EditarDireccion").val(respuesta["direccion"]);
           $("#EditarFechaNacimiento").val(respuesta["fecha_nacimiento"]);
	  }

  	})

})

/*=============================================
ELIMINAR CLIENTE
=============================================*/
$(".activarDatatable").on("click", ".btnEliminarCliente", function(){

	var idCliente = $(this).attr("idCliente");

swal({
  title: "¿Está seguro de borrar el cliente?",
  text: "¡Si no lo está puede cancelar la acción!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
      window.location = "index.php?ruta=clientes&idCliente="+idCliente;
  };
});    
    
})