var tabla;


$("#modalAgregarFactura, #modalEditarFactura, #modalPagarFactura, #modalVerFactura, #modal_fecha_pago").draggable({
	  handle: ".modal-header"
});


//Función que se ejecuta al inicio
function init(){

	listarFacturas();
    
    $("#formularioAgregarFactura").on("submit",function(e){
        agregarFactura(e);	
    })

    $("#formularioEditFactura").on("submit",function(e){
        guadarEditarFactura(e);	
    })

    $("#formularioPagoFactura").on("submit",function(e){
        guadarPagoFactura(e);	
    })
  
    $("#formFechaPagoFactura").on("submit",function(e){
      guadarFechaPagoFactura(e);	
  })
  
}



/*=============================================
VER EXPEDIENTE DE LA FACTURA 
=============================================*/
$("#TablaFacturas").on("click", ".btnVerFactura", function(){
//var element = document.getElementById("cover");
//element.classList.remove("d-none");

  //var idcounter = $('.btnVerFactura').attr("id");
  var idFactura = $(this).attr("idFactura");
  var numFactura = $(this).attr("numFactura");
  var idEstatus = $(this).attr("idEstado");
  var idBorrado = $(this).attr("idBorrado");
  var xfile;
  
  console.log(idFactura, numFactura,idEstatus, idBorrado);
  
	(async () => { 
		xfile=await verpdf(idFactura,numFactura,idEstatus, idBorrado);
    console.log(xfile);
    window.open(xfile, '_blank');
		//await mostrarPDF(xfile,numFactura);
		
	})();  //fin del async	 	

	// setTimeout(()=> { 
	//  element.classList.add("d-none");
	// }, 2000);
	
});

/* ==== FUNCION PARA TRAER EL RUTA DEL PDF DE LA FACTURA  =====*/
async function verpdf(idFactura,numFactura,idEstatus, idBorrado){
 $("#pdfdoc").attr("data","");  
  var filePdf;
      
  console.log(idFactura, numFactura,idEstatus, idBorrado);
	var datos = new FormData();
	datos.append("idFactura", idFactura);
	datos.append("numFactura", numFactura);
	datos.append("idEstatus", idEstatus);
	datos.append("idBorrado", idBorrado);
 
	await fetch('ajax/control-facturas.ajax.php?op=mostrar', {
		method: 'POST',
		body: datos
	})
     .then(respuesta=>respuesta.json())
     .then(datos=>{
		  console.log(datos.rutaexpediente);
		  filePdf=datos.rutaexpediente;
     }) 

     .catch(showError);     
	 
return filePdf;
}
/* ==== FIN DE LA FUNCION PARA TRAER EL RUTA DEL PDF =====*/

/* ==== FUNCION PARA MOSTRAR EL PDF DE LA FACTURA  =====*/
async function mostrarPDF(xfilePdf, noFactura){
		if(xfilePdf===null || xfilePdf==="" || xfilePdf===undefined){
			//console.log("entra",xfilePdf)
			$('#ModalCenterTitle').html("");
			$('#ModalCenterTitle').html('No Existe Expediente para la Factura No.: '+noFactura);
		}else{
			//console.log("entra file",xfilePdf)
			$('#ModalCenterTitle').html('Expediente para la Factura No.: '+noFactura);
			filax='<object id="pdfdoc" data='+xfilePdf+' type="application/pdf" typemustmatch width="100%" height="560px" style="height:74vh;">';
			
			await $("#seepdf").append(filax);  
		}	
}

/*=== CUANDO CIERRA EL MODAL VACIAR EL ELEMENTO OBJECT ===*/
$("#modalVerFactura").on('hidden.bs.modal', ()=> {
	$('#ModalCenterTitle').html("");
	$("#target #seepdf").empty();
})

$('#modalVerFactura').on('show.bs.modal', function (e) {
	
})

$("#modalEditarFactura").on('show.bs.modal', ()=> {
  $("#downfile").removeClass("d-none");
  $(".spin").hide();
});
//============ FIN MODAL DE VER FACTURA=================*/

/*=============================================
SUBIENDO LA FOTO DEL PRODUCTO
=============================================*/

$(".nuevoPdf").change(function(){

	var imagen = this.files[0];
	console.log(imagen);
	
	/*=============================================
  	VALIDAMOS EL FORMATO DE LA IMAGEN SEA PDF
  	=============================================*/
  	if(imagen["type"] != "application/pdf" && imagen["type"] != "image/jpeg"){

  		$(".nuevoPdf").val("");

  		 swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen debe estar en formato PDF!",
		      icon: "error",
		      button: "¡Cerrar!"
		    });

		}else if(imagen["size"] > 4000000){

  		$(".nuevoPdf").val("");

  		 swal({
		      title: "Error al subir la imagen/pdf",
		      text: "¡El archivo no debe pesar más de 2MB!",
		      icon: "error",
		      button: "¡Cerrar!"
		    });

  	}else{
	
	$("#downfile").addClass("d-none");

 	}

})

/*================ ???? =============================*/
function previewFile() {
  var preview = document.getElementById('previewpdf');
  var file    = document.getElementById('verpdf').files[0];
  var reader  = new FileReader();

  reader.onloadend = function () {
    preview.src = reader.result;
  }

  if (file) {
    reader.readAsDataURL(file);
  } else {
    preview.src = "";
  }
}

/*=============================================
Guardar Fecha y numero complemento de Pago de Factura
=============================================*/
function guadarPagoFactura(e){
	e.preventDefault(); //No se activará la acción predeterminada del evento
	var formData = new FormData($("#formularioPagoFactura")[0]);
    // for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}

     fetch('ajax/control-facturas.ajax.php?op=pagarfactura', {
      method: 'POST',
      body: formData
     })
     .then(ajaxPositiva)
     .catch(showError1);     

}

/*=============================================
Guardar Fecha de Pago de Factura
=============================================*/
function guadarFechaPagoFactura(e){
	e.preventDefault(); //No se activará la acción predeterminada del evento
	var formData = new FormData($("#formFechaPagoFactura")[0]);
    for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}

     fetch('ajax/control-facturas.ajax.php?op=fechapagofactura', {
      method: 'POST',
      body: formData
     })
     .then(ajaxPositiva)
     .catch(showError1);     

}

/*=============================================
AGREGAR Factura
=============================================*/
function agregarFactura(e){
  e.preventDefault(); //No se activará la acción predeterminada del evento
  
  $('.enviarfrm, .salirfrm').hide(); //ocultar botones 
  $(".spin").show();                 //mostrar spinner

	var formData = new FormData($("#formularioAgregarFactura")[0]);
     for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}

     fetch('ajax/control-facturas.ajax.php?op=guardar', {
      method: 'POST',
      body: formData
     })
       .then(ajaxPositiva)
       .catch(showError1);     

}

/*=============================================
MODIFICAR Factura
=============================================*/
function guadarEditarFactura(e){
  e.preventDefault(); //No se activará la acción predeterminada del evento
  
  $('.enviarfrm, .salirfrm').hide(); //oculto mediante id
  $(".spin").show();
  $("[name='editaStatusFactura']").prop('disabled',false);
  
	var formData = new FormData($("#formularioEditFactura")[0]);
    //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}
    fetch('ajax/control-facturas.ajax.php?op=guardareditar', {
      method: 'POST',
      body: formData
     })
       .then(ajaxPositiva)
       .catch(showError1);     
}

/*=============================================*/
function ajaxPositiva(response) {
  //console.log('response.ok: ', response.ok);
  $(".spin").hide();
  $('.enviarfrm, .salirfrm').show(); //oculto mediante id
  $('#modalAgregarFactura, #modalEditarFactura, #modalPagarFactura, #modal_fecha_pago').modal('hide')
  $('#TablaFacturas').DataTable().ajax.reload(null, false);
    swal({
      title: "Realizado!!",
      text: "Registro Guardado correctamente",
	  icon: "success",
      button: "Cerrar",
	  timer: 3000
      })  //fin swal
      .then(function(result){
        if (result) {
          //$('#TablaFacturas').DataTable().ajax.reload(null, false);
        }
    })  //fin .then
	
  if(response.ok) {
    response.text().then(showResult);
  } else {
    showError1('status code: ' + response.status);
  }
}

function showResult(txt) {
  console.log('muestro respuesta: ', txt);
}

function showError1(err) { 
  console.log('muestra error', err);
  swal({
      title: "Error!!",
      text: err,
          icon: "error",
          button: "Cerrar"
      })  //fin swal
    window.location = "inicio";
}

/*=============================================
Borrar Factura
=============================================*/
$("#TablaFacturas").on("click", ".btnBorrarFactura", function(){

  var idFactura = $(this).attr("idFactura");
  var numFactura = $(this).attr("numFactura");
  var idEstatus = $(this).attr("idEstado");

  var datos = new FormData();
  let idEstado=parseInt(idEstatus)==1?0:1;
  datos.append("idFactura", idFactura);
  //datos.append("numFactura", numFactura);
  datos.append("idEstado", idEstado);
    
    if(idEstatus==="1"){
      swal({
        title: "No se puede borrar factura ya pagada!",
        icon: "warning",
        dangerMode: true,
      })
      return false;
    }
   
    swal({
      title: "¿Está seguro de borrar la Factura No."+numFactura+"? ",
      text: "Si no lo esta puede cancelar la acción!",
	    icon: "vistas/img/logoaviso.jpg",
      buttons: {
        cancel: "Cancelar",
        defeat: "Borrar",
      },      
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        fetch('ajax/control-facturas.ajax.php?op=borrar', {
          method: 'POST',
          body: datos
         })
           .then(respuestapositiva)
           .catch(showError);     
      } else {
        return
      }
    });
})
/*==============================================================================*/
function respuestapositiva(response) {
  $('#TablaFacturas').DataTable().ajax.reload(null, false);
  swal({
      title: "Realizado!!",
      text: "Factura Borrada!!",
      icon: "success",
      button: "Cerrar"
      })  //fin swal
       .then(function(result){
          if (result) {
            //$('#TablaFacturas').DataTable().ajax.reload(null, false);
          }
        })  //fin .then
               
      if(response.ok) {
        response.text().then(showResult);
      } else {
        showError1('status code: ' + response.status);
  }
}

/*=============================================
MOSTRAR Factura
=============================================*/
$("#TablaFacturas").on("click", ".btnEditarFactura", function(){

  $("[name='editaStatusFactura']").prop('disabled',false);
  var idFactura = $(this).attr("idFactura");
  var numFactura = $(this).attr("numFactura");
  var idEstatus = $(this).attr("idEstado");
  var idBorrado = $(this).attr("idBorrado");
  console.log(idFactura, numFactura,idEstatus);
	var datos = new FormData();
	datos.append("idFactura", idFactura);
	datos.append("numFactura", numFactura);
	datos.append("idEstatus", idEstatus);
	datos.append("idBorrado", idBorrado);
 
  (async () => { 
   await fetch('ajax/control-facturas.ajax.php?op=mostrar', {
    method: 'POST',
    body: datos
   })
     .then(respuesta=>respuesta.json())
     //.then(datos=>console.log(datos)) 
     .then(datos=>{
       	mostrardatos(datos);    
		//console.log(datos)
     }) 
     .catch(showError);     

})();  //fin del async	 
})

function mostrardatos(datos){

  $( "input[name='idregistro']").val(datos.id);
  $( "input[name='editaFactura']").val(datos.numfact);
  $("#editaFactura").val(datos.numfact);
  $( "input[name='editaCliente']").val(datos.cliente);
  $( "input[name='editaFechaFactura']" ).val(datos.fechafactura);
  $( "input[name='editaOrden']" ).val(datos.numorden);
  $( "input[name='editaFechaEntregado']" ).val(datos.fechaentregado);
  $( "textarea[name='editaTipoTrabajo']" ).text(datos.tipotrabajo);		//campo textarea
  $( "input[name='editaSubtotal']" ).val(datos.subtotal);
  $( "input[name='editaRetencion']" ).val(datos.imp_retenido);
  $( "input[name='editaIva']" ).val(datos.iva);
  $( "input[name='editaImporteFactura']" ).val(datos.importe);
  $( "input[name='editaFechaPagado']" ).val(datos.fechapagado);
  $("textarea[name='editaObservacion']" ).text(datos.observaciones);	//campo textarea
  $("[name='editaStatusFactura']").val(datos.status);					//campo checkbox
  //  if(datos.status=="1"){
  //    $("[name='editaStatusFactura']").prop('disabled',true);
  //  }
   $( "input[name='actualPdf']" ).val(datos.rutaexpediente);

}

function showError(err) { 
  console.log('muestra error', err);
  swal({
      title: "Error!!",
      text: err,
          icon: "error",
          button: "Cerrar"
      })  //fin swal
    //window.location = "inicio";
}

/*===========================================================
FECHA PAGO FACTURA
============================================================*/
$("#TablaFacturas").on("click", ".btnPagarFactura", function(){
    //alert("relatedTarget is: " + event.relatedTarget);
    var idFactura = $(this).attr("idFactura");
    var numFactura = $(this).attr("numfactura");
    $('#ModalCenterTitleFact').html("");
    $('#ModalCenterTitleFact').html('<i class="fa fa-calendar"></i>'+' Factura No.: '+numFactura);
    $( "input[name='registroid']").val(idFactura);
})
/*===========================================================*/

// LISTAR EN EL DATATABLE REGISTROS DE LA TABLA Facturas
function listarFacturas(){
 let valorradio=($('input:radio[name=radiofactura]:checked').val());
 let valoryear=$("input[name='filterYear']").val();
 console.log(valoryear, valorradio);
  if(valorradio!==undefined){
    tabla=$('#TablaFacturas').dataTable(
    {
      "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
      "lengthMenu": [ [10, 15, 25, 50,100, -1], [10, 15, 25, 50, 100, "Todos"] ],
        "language": {
      "sProcessing":     "Procesando...",
      "stateSave": true,
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
          initComplete: function () {			//botones pequeños y color verde
            var btns = $('.dt-button');
            btns.removeClass('dt-button');
            btns.addClass('btn btn-success btn-sm');
          },  
      "columnDefs": [
        {"className": "dt-center", "targets": [4,9,10,11,12,13]},
        {"className": "dt-right", "targets": [5,6,7,8]}				//"_all" para todas las columnas
        ],

      "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api();
      //console.log(api,data);

      // Total over this page subtotal
      var pageSubTot = api.column(5, {page:'current'}).data().sum();
      pageSubTot=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(pageSubTot);

      // Total over this page iva
      var pageTotiva = api.column(6, {page:'current'}).data().sum();
      pageTotiva=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(pageTotiva);
      
      // Total over this page total
      var pageTotRet = api.column(7, {page:'current'}).data().sum();
      pageTotRet=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(pageTotRet);
      
    
      // Total over this page total
      var pageTotal = api.column(8, {page:'current'}).data().sum();
      pageTotal=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(pageTotal);
      //console.log(pageTotal);

      // Total over all pages
      var total = api.column(8).data().sum();
      total=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(total);
      //console.log(total);


      $(api.column(4).footer()).html('Totales:');
      $(api.column(5).footer()).html(pageSubTot);
      $(api.column(6).footer()).html(pageTotiva);
      $(api.column(7).footer()).html(pageTotRet);
      $(api.column(8).footer()).html(pageTotal);
      $(api.column(9).footer()).html(total);
    },
    "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {		//cambiar el tamaño de la fuente
      if ( true ){ // your logic here
        $(nRow).addClass( 'customFont' );
      }
      if(valorradio=="cancelado" || aData[3] == "CANCELADO" || aData[3] == "cancelado"){
        $('td', nRow).css('color', 'Red');
      }
  },
      "ajax":
          {
            url: 'ajax/control-facturas.ajax.php?op=listar',
            data:{tiporeporte: valorradio, filteryear: valoryear},
            type : "get",
            dataType : "json",						
            error: function(e){
              console.log(e.responseText);
            }
          },
      "bDestroy": true,
      "iDisplayLength": 15,//Paginación
      "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
    }).DataTable();    
    
  }    
} 


$(document).ready(function (){ 
  $(".spin").hide();
} );

/ /
$('#TablaFacturas tbody').on( 'dblclick', 'td', function () {
  if(tabla.cell( this ).index().columnVisible==10){
    //console.log(tabla.row( this ).data()[0]);
    //console.log(tabla.row(this).data());
    let numerodefactura=tabla.row( this ).data()[0];
    $('#numerodefactura').html("");
    $('#numerodefactura').html('<i class="fa fa-calendar"></i>'+' Capturar fecha pago de factura: #'+numerodefactura);
    $('#modal_fecha_pago').modal('show')
    $( "input[name='registroid']").val(numerodefactura);
  };
});

//click para seleccionar y sumar importes
$('#TablaFacturas tbody').on( 'click', 'tr', function () {
  $(this).toggleClass('selected');
  //$("#sumaseleccionados").addClass("d-none");
  $(".sumaseleccion").html('');
  let price1=price2=sumaselec1=sumaselec2=0;
  let x=(tabla.rows('.selected').data().length);
  //console.log(x);
  for(var i=0; i<x; i++) {
    //console.log( tabla.rows('.selected').data()[i][5]);
    price1=tabla.rows('.selected').data()[i][5];
    price2=tabla.rows('.selected').data()[i][8];
    strEx1 = price1.replace(",","");		//quitar la coma de los miles
    strEx2 = price2.replace(",","");		//quitar la coma de los miles
    price1 = parseFloat(strEx1)		//convierte en numero
    price2 = parseFloat(strEx2)		//convierte en numero
    sumaselec1+=price1;
    sumaselec2+=price2;
    //console.log(pricesell);
    //console.log(sumaselec);
    sumaseleccion1=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(sumaselec1);
    sumaseleccion2=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(sumaselec2);
      //$("#sumaseleccionados").removeClass("d-none");
      $(".sumaseleccion").html(sumaseleccion1 + " -/- "+sumaseleccion2);
  };
 
 });

 // Handle click on "Select all" control
   $('#example-select-all').on('click', function(){
     console.log("entra");
      // Check/uncheck all checkboxes in the table
      var rows = table.rows({ 'search': 'applied' }).nodes();
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
   });
   
// Handle click on checkbox to set state of "Select all" control
   $('#TablaFacturas tbody').on('change', 'input[type="checkbox"]', function(){
   console.log("entra1");
      // If checkbox is not checked
      if(!this.checked){
         var el = $('#example-select-all').get(0);
         // If "Select all" control is checked and has 'indeterminate' property
		 console.log("get:",el);
         if(el && el.checked && ('indeterminate' in el)){
            // Set visual state of "Select all" control 
            // as 'indeterminate'
            el.indeterminate = true;
         }
      }
   });   

jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
    return this.flatten().reduce( function ( a, b ) {
        if ( typeof a === 'string' ) {
            a = a.replace(/[^\d.-]/g, '') * 1;
        }
        if ( typeof b === 'string' ) {
            b = b.replace(/[^\d.-]/g, '') * 1;
        }
        return a + b;
    }, 0 );
} );



$('#filterYear').datepicker({
  format: " yyyy",
  viewMode: "years", 
  minViewMode: "years",
  clearBtn:true,
  language:"es",
  todayHighlight:true,
}); 

/*===========================================================
ENCABEZADO DEL MODAL DE AGREGA GASTOS
============================================================*/
$("#TablaFacturas").on("click", ".btnAgregaGastos", function(){
  let numFactura = $(this).attr("numFactura");
  $('#ModalCenterTitleGastos').html("");
  $('#ModalCenterTitleGastos').html('<i class="fa fa-plus-circle"></i>'+' Gastos de Fact No.: '+numFactura);
  $( "input[name='numfactura']").val(numFactura);
})


/*================ AL SALIR DEL MODAL DE EDICION RESETEAR FORMULARIO==================*/
$("#modalEditarFactura").on('hidden.bs.modal', ()=> {
	$('#formularioEditFactura')[0].reset();
});

/*================ AL SALIR DEL MODAL DE AGREGAR RESETEAR FORMULARIO==================*/
$("#modalAgregarFactura").on('hidden.bs.modal', ()=> {
	$('#formularioAgregarFactura')[0].reset();
});

/*================ AL SALIR DEL MODAL DE FECHA PAGO FACTURA RESETEAR FORMULARIO==================*/
$("#modalPagarFactura").on('hidden.bs.modal', ()=> {
	$('#formularioPagoFactura')[0].reset();
});

/*================ AL SALIR DEL MODAL DE EDICION RESETEAR FORMULARIO==================*/
$("#modal_fecha_pago").on('hidden.bs.modal', ()=> {
	$('#formFechaPagoFactura')[0].reset();
});

init();



/*
swal({
  text: 'Search for a movie. e.g. "La La Land".',
  content: "input",
  button: {
    text: "Search!",
    closeModal: false,
  },
})
.then(name => {
  if (!name) throw null;
 
  return fetch(`https://itunes.apple.com/search?term=${name}&entity=movie`);
})
.then(results => {
  return results.json();
})
.then(json => {
  const movie = json.results[0];
 
  if (!movie) {
    return swal("No movie was found!");
  }
 
  const name = movie.trackName;
  const imageURL = movie.artworkUrl100;
 
  swal({
    title: "Top result:",
    text: name,
    icon: imageURL,
  });
})
.catch(err => {
  if (err) {
    swal("Oh noes!", "The AJAX request failed!", "error");
  } else {
    swal.stopLoading();
    swal.close();
  }
});	
*/