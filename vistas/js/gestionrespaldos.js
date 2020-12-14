var tablaRespaldo;


//Función que se ejecuta al inicio
function init(){

	listarRespaldos();
    
}

$(document).ready(function() {

/*
  $('input[type=checkbox]:checked').each(function() {
  console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") Seleccionado");
  });

*/

});

$("#botondeeliminar").click(function(event){
 myArray=[];
 $('#tablebackup input[type=checkbox]:checked').each(function() {
  //console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") Seleccionado "+$(this).attr("namefile"));
  myArray.push($(this).attr("namefile"));
  });
  
  var datos = new FormData();
  datos.append("nameFiles", myArray);
  //for (var pair of datos.entries()){console.log(pair[0]+ ', ' + pair[1]);}
    
  Swal.fire({
    title: "¿Está seguro de Eliminar "+myArray.length+" Archivos? ",
    text: "¡Si no lo está puede cancelar la acción!",
    icon: "warning",
    allowOutsideClick:false,
    allowEscapeKey:true,
    allowEnterKey: true,
    reverseButtons: true,			//invertir botones
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Eliminar!',
    cancelButtonText: 'No, cancelar',
  })
  
    .then((willDelete) => {
      if (willDelete.value) {
          fetch('ajax/gestionrespaldos.ajax.php?op=eliminararchivos', {
            method: 'POST',
            body: datos
          })
            .then(respuestaEpositiva)
            .catch(showErrorRespaldo); 
        } 
    });  //fin de la funcion    

})	

/*==============  click a un checkbox ===================*/
$('#TablaRespaldos').on('ifChanged', '.case', function(event) {
  //console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") lo has Seleccionado");
  let checked = $(".case:checked").length;
  //console.log("Checkbox " + $(this).prop("id") +  " tienes (" + checked + ") Seleccionados");
  if(parseInt(checked)>1){
    $("#botondeeliminar").removeClass("d-none");
  }else{
    if ($('#botondeeliminar').is(':visible')){
      //console.log("entra1");
        $("#botondeeliminar").addClass("d-none");
    }else{
      //console.log("entra2");
      //$("#botondeeliminar").removeClass("d-none");
    }    
  }    

});

$("#TablaRespaldos").on("click", ".case", function(){
  //console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") lo has Seleccionado");
   //$('input[type=checkbox]:checked').each(function() {
    let checked = $(".case:checked").length;
    //console.log("Checkbox " + $(this).prop("id") +  " tienes (" + checked + ") Seleccionados");
    if(parseInt(checked)>1){
      $("#botondeeliminar").removeClass("d-none");
    }else{
      if ($('#botondeeliminar').is(':visible')){
        //console.log("entra1");
          $("#botondeeliminar").addClass("d-none");
          //$('#checkTodos').iCheck('uncheck');
      }else{
        //console.log("entra2");
        //$("#botondeeliminar").removeClass("d-none");
      }    
   }
   //});
  
});


/* =========================================================== */
$('#checkTodos').on('ifChanged', function(event) {
event.preventDefault();
//console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") Seleccionado!!");

   if ($(this).is(':checked') ) {
      //console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") => Seleccionado");
      $("#botondeeliminar").removeClass("d-none");
      //Checkea o deschekea todos los chekbox segun condicion
      $('input:checkbox').iCheck('check', $(this).prop("checked"));
      $("input:checkbox").prop('checked', $(this).prop("checked"));
  } else {
      //console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") => Deseleccionado");
      $("#botondeeliminar").addClass("d-none");
      //Checkea o deschekea todos los chekbox segun condicion
      $('input:checkbox').iCheck('uncheck', $(this).prop("checked"));
      $("input:checkbox").prop('unchecked', $(this).prop("checked"));
  }
  
});



// LISTAR EN EL DATATABLE REGISTROS DE LA TABLA cajas
function listarRespaldos(){
  tablaRespaldo=$('#TablaRespaldos').dataTable(
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
      "ajax":
				{
					url: 'ajax/gestionrespaldos.ajax.php?op=listarrespaldos',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);
					}
				},
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	  "order": [[ 2, "desc" ]]//Ordenar por nombre(columna,orden)
	}).DataTable();    
    
} 

/*=============================================
ELIMINAR ARCHIVO DE RESPALDO
=============================================*/
$("#TablaRespaldos").on("click", ".btnEliminarRespaldo", function(){
var idFile = $(this).attr("idFile");    
var datos = new FormData();
datos.append("idFile", idFile);
console.log(idFile);

swal.fire({
  title: "¿Está seguro de Eliminar: \n"+idFile.substring(0,30)+"? ",
  text: "¡Si no lo está puede cancelar la acción!",
  icon: "warning",
  allowOutsideClick:false,
  allowEscapeKey:true,
  allowEnterKey: true,
  reverseButtons: true,			//invertir botones
  showCancelButton: true,
  confirmButtonColor: '#03AA45 ',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Si, Eliminar!',
  cancelButtonText: 'No, cancelar',
})

  .then((willDelete) => {
    if (willDelete.value) {
        fetch('ajax/gestionrespaldos.ajax.php?op=eliminarrespaldo', {
          method: 'POST',
          body: datos
        })
          .then(respuestaEpositiva)
          .catch(showErrorRespaldo); 
      } 
  });  //fin de la funcion    

});  //fin de la funcion    

function respuestaEpositiva(response) {

      console.log('response.ok: ', response.ok);
      if(response.ok) {
        response.text().then(showResultRespaldo);
      } else {
        showError('status code: ' + response.status);
      }
}


function showResultRespaldo(txt) {
    console.log('muestro respuesta: ', txt);
    if(txt=="false" || txt!="true" ){
      showErrorRespaldo("No fue posible eliminar archivo!!");
    }else{
      $('#TablaRespaldos').DataTable().ajax.reload(null, false);

      $("#msj-success").removeClass("d-none");
      $("#botondeeliminar").addClass("d-none")
      $('#checkTodos').iCheck('uncheck');

      setTimeout(function(){
        $("#msj-success").addClass("d-none")
      }, 3000);   //
      
    }
}

function showErrorRespaldo(err) { 
    console.log('muestra error', err);
    swal.fire({
        title: "Error!!",
        text: err,
        icon: "error",
        })  //fin swal
      //window.location = "inicio";
  }

init();

