var tablaCaja;


$("#modalAgregarCaja, #modalEditarCaja, #cajaAbierta, #aviso24").draggable({
	  handle: ".modal-header"
});

//Función que se ejecuta al inicio
function init(){

	listarCajas();
    
    $("#formularioAgregarCaja").on("submit",function(e){
            agregarCaja(e);	
    })

    $("#formularioEditCaja").on("submit",function(e){
            editarCaja(e);	
    })
	
}



/*=============================================
AGREGAR CAJA
=============================================*/
function agregarCaja(e){
	e.preventDefault(); //No se activará la acción predeterminada del evento
	var formData = new FormData($("#formularioAgregarCaja")[0]);
     //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}
	$.ajax({
		url: "ajax/cajas.ajax.php?op=guardar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,
	    success: function(datos){
         //console.log(datos);
            if(datos==""){
              $('#modalAgregarCaja').modal('hide')
               swal.fire({
                position: "top-end",
                title: "Realizado!!",
                text: "Caja se ha añadido correctamente",
                icon: "success",
                timer: 2500
                })  //fin swal
                  .then((result)=>{
                    if (result) {
						            $('#TablaCajas').DataTable().ajax.reload(null, false);
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
MODIFICAR CAJA
=============================================*/
function editarCaja(e){
	e.preventDefault(); //No se activará la acción predeterminada del evento
	var formData = new FormData($("#formularioEditCaja")[0]);
     //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}
	$.ajax({
		url: "ajax/cajas.ajax.php?op=editar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,
	    success: function(datos){
           console.log(datos);
           if(datos=="1"){ 
            $('#TablaCajas').DataTable().ajax.reload(null, false);
            $('#modalEditarCaja').modal('hide')
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 2000,
              timerProgressBar: true,
              onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
              })

              Toast.fire({
              icon: 'success',
              title: 'Caja ha sido cambiado correctamente!!'
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
MOSTRAR CAJA
=============================================*/
$("#TablaCajas").on("click", ".btnEditarCaja", function(){
	var idCaja = $(this).attr("idCaja");
	var datos = new FormData();
	datos.append("idCaja", idCaja);
        //for (var pair of datos.entries()){console.log(pair[0]+ ', ' + pair[1]);}
	$.ajax({
		url: "ajax/cajas.ajax.php?op=mostrar",
		method: "POST",
      	data: datos,
      	cache: false,
     	contentType: false,
     	processData: false,
     	dataType:"json",
     	success: function(respuesta){
            //console.log(respuesta);
        $("#idCaja").val(respuesta["idcaja"]);
     		$("#editarCaja").val(respuesta["caja"]);
     		$("#editardescripcionCaja").val(respuesta["descripcion"]);
     		$("#editarusuarioCaja").val(respuesta["iduser"]);
        //$("#editarusuarioCaja").val(respuesta["nombre"]);
        $("#editarusuarioCaja").html(respuesta["nombre"]);
     		$("#editarestadoCaja").val(respuesta["estado"]);
     	},
        error:function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR, textStatus, errorThrown);
        }

	})
})

/*=============================================
DES-ACTIVAR CAJA
=============================================*/
$("#TablaCajas").on("click", ".btnBorrarCaja", function(){

    var idCaja = $(this).attr("idCaja");
    var idEstatus = $(this).attr("idEstado");
	var datos = new FormData();
	datos.append("idCaja", idCaja);
    let idEstado=parseInt(idEstatus)==1?0:1;
	datos.append("idEstado", idEstado);
    let estado=parseInt(idEstatus)==1?"Desactivar":"Activar";
    console.log("idcaja:",idCaja, "idEstatus:",idEstatus, idEstado)
    swal.fire({
      title: "¿Está seguro de "+estado+" Caja "+idCaja+"? ",
      text: "¡Si no lo está puede cancelar la acción!",
      icon: "question",
      allowOutsideClick:false,
      allowEscapeKey:true,
      allowEnterKey: true,
      reverseButtons: true,			//invertir botones
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, '+estado,
      cancelButtonText: 'No, cancelar!',
    })
    .then((willDelete) => {
      if (willDelete.value) {
        //dow.location = "index.php?ruta=cajas&idCaja="+idCaja
        $.ajax({
        url: "ajax/cajas.ajax.php?op=des_activar",
          type: "POST",
          data: datos,
          contentType: false,
          processData: false,
          success: function(datos){
                $('#TablaCajas').DataTable().ajax.reload(null, false);
                swal.fire({
                    title: "¡Realizado!",
                    text: "Caja ha sido cambiada correctamente",
                    icon: "success",
                    timer: 3000
                })
            }
        })        
      } else {
     /*swal.fire("Acción Cancelada!", {
            icon: "success",
            buttons: "Cerrar",
            timer: 2000
            })*/
      }
      
    });    
    
})

// LISTAR EN EL DATATABLE REGISTROS DE LA TABLA cajas
function listarCajas(){
  tablaCaja=$('#TablaCajas').dataTable(
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
          }
          ],
          initComplete: function () {
            var btns = $('.dt-button');
            btns.removeClass('dt-button');
            btns.addClass('btn btn-success btn-sm');
          },
          "columnDefs": [
              {"className": "dt-center", "targets": [5]}	//la columna de status
          ],          
      "ajax":
				{
					url: 'ajax/cajas.ajax.php?op=listarcaja',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);
					}
				},
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	  "order": [[ 1, "asc" ]]//Ordenar (columna,orden)
	}).DataTable();    
    
} 
/*
$(document).ready(function (){ 
	$('#TablaCajas tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );
});
*/
$('#modalAgregarCaja #modalEditarCaja').on('show.bs.modal', function (e) {
  //$(':input:visible:enabled:first').focus();
  $(':input:text:visible:first').focus();
})


$('#abrircaja').click(function (e) {
    //e.preventDefault();
    console.log("entra");
    //$("#ingreso, #egreso, #imprimirx").removeClass("d-none");
    //$('#egreso').prop('disabled', true);
    let varcaja=$('#numcaja').val();
    console.log("session:",varcaja);

  (async () => {   
    const { value: importecaja } = await Swal.fire({
      title: 'Importe en caja chica',
			allowOutsideClick:false,
			allowEscapeKey:true,
      input: 'text',
      inputPlaceholder: 'Importe $?'
    })
    
    if (importecaja) {
      importenuevo=parseFloat(importecaja);
      dequetipo=typeof importenuevo;
        if(isNaN(importenuevo)){
          Swal.fire(`Importe mal capturado: ${importecaja}`)
        }else{
          //Swal.fire(`Importe bien capturado: ${importecaja} ${importenuevo}`)
          const data = new FormData();
          data.append('varcaja', varcaja);
          data.append('cajachica', importenuevo);
          fetch('ajax/cajas.ajax.php?op=opencaja', {
            method: 'POST',
            body: data
          })
              .then(ajaxPositive)
              .catch(showError);    
          }
    }else if(typeof importecaja === "undefined") {
        console.log(importecaja)
        return false;
    }else{
      console.log(importecaja)
      const data = new FormData();
      data.append('varcaja', varcaja);
      data.append('cajachica', 0);
      fetch('ajax/cajas.ajax.php?op=opencaja', {
        method: 'POST',
        body: data
      })
          .then(ajaxPositive)
          .catch(showError);    

    }    
  
    // swal("Importe en Caja Chica?", {
    //   buttons: ["Cancelar", "Abrir Caja!"],
    //   closeOnEsc: false,
    //   content: "input",
    // })
    
    // .then((value) => {
    //   //swal(`You typed: ${value}`);
    //   if(value==0 || value>0){
    //     var importecajachica=value;
    //     console.log(importecajachica, value)

    //     const data = new FormData();
    //     data.append('varcaja', varcaja);
    //     data.append('cajachica', importecajachica);
    //     fetch('ajax/cajas.ajax.php?op=opencaja', {
    //       method: 'POST',
    //       body: data
    //     })
    //         .then(ajaxPositive)
    //         .catch(showError);    
            
    //   }else{
    //     console.log(value)
    //   }
    //  });
  })();  //fin del async
});

    function ajaxPositive(response) {
        
      //$('#cajaAbierta').modal('hide');
      console.log('response.ok: ', response.ok);
        
        swal.fire({
          title: "Realizado!!",
          text: "Caja se ha aperturado correctamente",
          icon: "success",
          timer: 2000,
          })  //fin swal
          .then(function(result){
            if (result) {
                window.location = "inicio"; 
            }else{
              window.location = "inicio"; 
            }
        })  //fin .then
               
      if(response.ok) {
        response.text().then(showResult);
      } else {
        showError('status code: ' + response.status);
      }
    }

    function showResult(txt) {
      console.log('muestro respuesta: ', txt);
    }

    function showError(err) { 
      console.log('muestra error', err);
      swal.fire({
        title: "Error!!",
        text: err,
        icon: "error",
       })  //fin swal
        window.location = "inicio";
    }
/* =========== CERRAR CAJA DE VENTA =========================== */
$("#cerrarcaja").click(function(evt){
  //let idcaja = $("#idcaja").val();
    //console.log("si entra para cerrar caja");
    $.get("ajax/salidas.ajax.php?op=cerrarcajavta", {cierre : "caja"}, function(resp, estado,jqXHR){
        //console.log("Respuesta: " + resp + "\nEstado: " + estado +"\njqHXR: " + jqXHR); 
        if(resp=="Hecho"){
          Swal.fire({
            title: 'Caja cerrada correctamente!',
            text: "Hasta pronto",
            icon: "info",
            timer: 2500,
            showClass: {
              popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
              popup: 'animate__animated animate__fadeOutUp'
            }
          })            
                .then(function(result){
                    if (result) {
                        window.location = "inicio";               
                    }else{
                        window.location = "inicio";               
                    }
                  })  //fin .then
            
        }else{
                  swal.fire({
                  title: "Error!!",
                  text: "Cierre sesión, y vuelva a Entrar",
                  icon: "error",
                  })  //fin swal

        }
        /*window.setTimeout(function(){ 
          location.reload();
        } ,8000);*/
	})
});

/* =========== INGRESO DE EFECTIVO EN  CAJA DE VENTA =========================== */
$("#form_ingreso_efectivo").on("submit",function(e){
 e.preventDefault();
 let importeIngreso=$('#importeIngreso').val();
 let motivoIngreso=$('#motivoIngreso').val();
 let numcaja=$('#numcaja').val();
 //console.log("entra ingreso",importeIngreso, motivoIngreso, numcaja);
const data = new FormData();
data.append('importeIngreso', importeIngreso);
data.append('motivoIngreso',motivoIngreso);
data.append('numcaja',numcaja);
    fetch('ajax/cajas.ajax.php?op=ingresocaja', {
     method: 'POST',
     body: data
    })
.then(respuestapositiva)
.catch(showError);    
});

function respuestapositiva(response) {
    //$('#cajaAbierta').modal('hide');
      console.log('response.ok: ', response.ok);
        
        swal.fire({
          title: "Realizado!!",
          text: "Ingreso Guardado",
          icon: "success",
          timer: 2500
          })  //fin swal
          .then(function(result){
            if (result) {
                window.location = "inicio"; 
            }
        })  //fin .then
               
      if(response.ok) {
        response.text().then(showResult);
      } else {
        showError('status code: ' + response.status);
      }
}

/* =========== INGRESO DE EFECTIVO EN  CAJA DE VENTA =========================== */
$("#form_egreso_efectivo").on("submit",function(e){
  e.preventDefault();
  let importeEgreso=$('#importeEgreso').val();
  let motivoEgreso=$('#motivoEgreso').val();
  let numcaja=$('#numcaja').val();
  //console.log("entra Egreso",importeEgreso, motivoEgreso, numcaja);
 const data = new FormData();
 data.append('importeEgreso', importeEgreso);
 data.append('motivoEgreso',motivoEgreso);
 data.append('numcaja',numcaja);
     fetch('ajax/cajas.ajax.php?op=egresocaja', {
      method: 'POST',
      body: data
     })
 .then(respuestaEpositiva)
 .catch(showError);    
 });
 
 function respuestaEpositiva(response) {
     //$('#cajaAbierta').modal('hide');
       console.log('response.ok: ', response.ok);
         
         swal.fire({
           title: "Realizado!!",
           text: "Egreso Guardado",
           icon: "success",
           timer: 2500
           })  //fin swal
           .then(function(result){
             if (result) {
                 window.location = "inicio"; 
             }
         })  //fin .then
                
       if(response.ok) {
         response.text().then(showResult);
       } else {
         showError('status code: ' + response.status);
       }
 }
/*
 $('#imprimirCorteX').click(function () {
  let Imprimircaja=$('#numcaja').val();
  let cajaingreso = $(this).attr("cajaIngreso");
  let cajaegreso = $(this).attr("cajaEgreso");
  //console.log("Caja No.", Imprimircaja, "ingreso:", cajaingreso, "Egreso:", cajaegreso);
  window.open("extensiones/tcpdf/pdf/imprimir_cortex.php?Imprimircaja="+Imprimircaja+"&cajaingreso="+cajaingreso+"&cajaegreso="+cajaegreso, "_blank","top=200,left=500,width=400,height=400");
})
*/

$('#imprimirCorteX').click(function () {
  let Imprimircaja=$('#numcaja').val();
  let cajacredito = $('#totalacredito' ).data( 'creditos');
  let cajaotros = $('#totalotros' ).data( 'otros');
  let cajaservicio = $('#totalservicios' ).data( 'servicios');
  let cajaingreso = $('#totalingreso' ).data( 'ingreso');
  let cajaegreso = $('#totalegreso' ).data( 'egreso');
  

  console.log("servicios:", $('#totalservicios' ).data( 'servicios' ) );
  console.log("ingreso:", $('#totalingreso' ).data( 'ingreso' ) );
  console.log("egreso:", $('#totalegreso' ).data( 'egreso' ) );
  console.log("tot cred:",  cajacredito);


  cajaingreso=isNaN(parseFloat(cajaingreso))?0:parseFloat(cajaingreso);
  cajaegreso=isNaN(parseFloat(cajaegreso))?0:parseFloat(cajaegreso);
  cajaotros=isNaN(parseFloat(cajaotros))?0:parseFloat(cajaotros);
  //cajaservicio=isNaN(parseFloat(cajaservicio))?0:parseFloat(cajaservicio);
  cajaservicio=isNaN(parseFloat(cajaservicio))?0:typeof cajaservicio === "undefined"?0:parseFloat(cajaservicio);
  cajacredito=isNaN(parseFloat(cajacredito))?0:parseFloat(cajacredito);

  //console.log("ing:",cajaingreso, "egr:",cajaegreso, "ser:",cajaservicio)

  $.get("extensiones/tcpdf/pdf/imprimir_cortex.php", {Imprimircaja : Imprimircaja, cajaingreso: cajaingreso, cajaegreso: cajaegreso, cajaotros: cajaotros, cajaservicio: cajaservicio, cajacredito: cajacredito}, function(resp, estado,jqXHR){
  console.log("Respuesta: " + resp + "\nEstado: " + estado +"\njqHXR: " + jqXHR); 
  if(resp==1){

    swal.fire({
      title: "Hecho!",
      text: "Impresión realizada.",
      icon:"info",
      timer: 2000,
    });

    window.setTimeout(function(){ 
      location.reload();
      } ,2000);
  }
  });

})


init();

/*

  let cajaingreso = $(this).attr("cajaIngreso");
  let cajaegreso = $(this).attr("cajaEgreso");
  let cajaservicio = $(this).attr("cajaServicio");

window.setTimeout(function(){ 
  //$('#aviso24').modal('toggle')
  } ,2000);
  */

//swal("Corte Impresa!")
/*     swal({
      title: "Hecho!",
      className: "red-bg",
      text: "Impresión realizada.",
      imageUrl: 'ThumbsUp.jpg'
    });

    swal("This modal will disappear soon!", {
      buttons: false,
      timer: 3000,
    });

    Parse error: syntax error, unexpected end of file in C:\xampp\htdocs\cervecentro\extensiones\tcpdf\pdf\kardex-producto.php on line 487
*/