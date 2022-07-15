
var tabla;


$("#modalAgregarViaticos, #modalAgregaViatico, #modalCheckup, #modalVerFactura, #modalReporteViatico").draggable({
	  handle: ".modal-header"
});


//Función que se ejecuta al inicio
function init(){

	listarViaticos();
    
    $("#formularioAgregarViatico").on("submit",function(e){
        agregarViatico(e);	
    })

    $("#formularioAgregaViatico").on("submit",function(e){
        guadarAgregaViatico(e);	
    })

    $("#formularioCheckup").on("submit",function(e){
        guadarCheckup(e);	
    })
	
}

$("#TablaViaticos tbody").on("click", "button.btnPrintViatico", function(){
	let idViatico = $(this).attr("idviatico");
	   //console.log(idViatico);
    if(idViatico.length > 0){
     window.open("extensiones/tcpdf/pdf/imprimir_viatico.php?codigo="+idViatico,"_blank");
    }
})


/*=============================================
AGREGAR VIATICO
=============================================*/
function agregarViatico(e){
	e.preventDefault(); //No se activará la acción predeterminada del evento

	var formData = new FormData($("#formularioAgregarViatico")[0]);
     //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}

     fetch('ajax/control-viaticos.ajax.php?op=guardar', {
      method: 'POST',
      body: formData
     })
       .then(ajaxViaticos)
       .catch(showViaticos);     
}

/*=============================================
GUARDAR Y AGREGAR VIATICOS A UNO YA CAPTURADO
=============================================*/
function guadarAgregaViatico(e){
	e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("[name='editaStatusFactura']").prop('disabled',false);
	var formData = new FormData($("#formularioAgregaViatico")[0]);
    //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}
    
    fetch('ajax/control-viaticos.ajax.php?op=guardarAgregaViatico', {
      method: 'POST',
      body: formData
     })
       .then(ajaxViaticos)
       .catch(showViaticos);     
}

/*=============================================*/
function ajaxViaticos(response) {
  console.log('response.ok: ', response);
  $('#modalAgregarViaticos, #modalAgregaViatico, #modalCheckup').modal('hide')
  
  $('#TablaViaticos').DataTable().ajax.reload(null, false);
    swal({
      title: "Realizado!!",
      text: "Registro Guardado correctamente",
	    icon: "success",
      button: "Cerrar",
	    timer: 2000
      })  //fin swal
      .then(function(result){
        if (result) {
          //$('#TablaFacturas').DataTable().ajax.reload(null, false);
        }
    })  //fin .then
	
  if(response.ok) {
    response.text().then(showResultV);
  } else {
    showViaticos('status code: ' + response.status);
  }
}

function showResultV(txt) {
  console.log('muestro respuesta: ', txt);
}

function showViaticos(err) { 
  console.log('muestra error', err);
  swal({
      title: "Error!!",
      text: err,
      icon: "error",
      button: "Cerrar"
      })  //fin swal
    window.location = "inicio";
}

function showErrorUpSave(err) { 
  console.log('muestra error', err);
  swal({
      title: "Error!!",
      text: err,
      icon: "error",
      button: "Cerrar"
      })  //fin swal
}

function showMsgOk(response) {
  console.log('response.ok: ', response);
  $('#modalCheckup').modal('hide')
  $('#TablaViaticos').DataTable().ajax.reload(null, false);
    swal({
      title: "Realizado!!",
      text: response,
	    icon: "success",
      button: "Cerrar",
	    timer: 3000
      })  //fin swal
}

/*=============================================
Guardar comprobacion de gasto
=============================================*/
function guadarCheckup(e){
	e.preventDefault(); //No se activará la acción predeterminada del evento
  //$("#submitGasto").prop('disabled',true);
  $('#submitGasto').hide(); //ocultar botones 
  $(".spin").show();       //mostrar spinner
  var formData = new FormData($("#formularioCheckup")[0]);
  axios({ 
    method  : 'post', 
    url : 'ajax/control-viaticos.ajax.php?op=checkup', 
    data : formData,
  }) 

  .then((res)=>{ 
    if(res.data.status==400) {
      console.log(res.data.mensaje)
        $(".spin").hide();       //mostrar spinner
        $('#submitGasto').show(); //ocultar botones 
        showErrorUpSave(res.data.mensaje)
        return
    }else if(res.data.status==200){
      showMsgOk(res.data.mensaje)
      console.log(res)
    }else{
      showErrorUpSave(res.data.mensaje)
    }

  })

  .catch(function (error) {
    if (error.response) {
      // La respuesta fue hecha y el servidor respondió con un código de estado
      // que esta fuera del rango de 2xx
      console.log(error.response.data);
      console.log(error.response.status);
      console.log(error.response.headers);
    } else if (error.request) {
      // La petición fue hecha pero no se recibió respuesta
      // `error.request` es una instancia de XMLHttpRequest en el navegador y una instancia de
      // http.ClientRequest en node.js
      console.log(error.request);
    } else {
      // Algo paso al preparar la petición que lanzo un Error
      console.log('Error', error.message);
    }
    console.log(error.config);
  });

}

/*===========================================================
ENCABEZADO DEL MODAL DE CHECKUP
============================================================*/
$("#TablaViaticos").on("click", ".btnCheckup", function(){
    //alert("relatedTarget is: " + event.relatedTarget);
    var idviatico = $(this).attr("idviatico");
    //var numFactura = $(this).attr("numfactura");
    $(".spin").hide();
    $("#submitGasto").show();
        
    $('#ModalCenterTitleCheck').html("");
    $('#ModalCenterTitleCheck').html('<i class="fa fa-money"></i>'+' Comprobar gasto de Viático No.: '+idviatico);
    $( "input[name='registroid']").val(idviatico);
})
/*================ ACTIVAR O DESACTIVAR VIATICO ==============================*/
$("#TablaViaticos").on("click", ".btnEstatus", function(){
  let idviatico = $(this).attr("idviatico");
  let idestado = $(this).attr("idEstado");
  console.log("estatus",idviatico, idestado);
  (async () => { 
    await axios({
      method: 'post',
      url: 'ajax/control-viaticos.ajax.php?op=PutCambiaEstatus',
      data: {
            idviatico: idviatico,
            idestado: idestado,
      }
    })
    .then(function (response) {
      // handle success
      console.log(response.data);
      if(response.data=="ok"){
        $('#TablaViaticos').DataTable().ajax.reload(null, false);
      }
    })
    .catch(function (error) {
      // handle error
      console.log(error);
    })
    .then(function () {
      // always executed
      console.log("siempre responde");
    });
  })();  //fin del async

})
/*===========================================================
ENCABEZADO DEL MODAL DE AGREGA VIATICO
============================================================*/
$("#TablaViaticos").on("click", ".btnAgregaViatico", function(){
  var idviatico = $(this).attr("idviatico");
  $('#ModalCenterTitleFact').html("");
  $('#ModalCenterTitleFact').html('<i class="fa fa-credit-card"></i>'+' Agregar a Viático No.: '+idviatico);
  $( "input[name='registroid']").val(idviatico);
})

/*===================== REPORTE EN PANTALLA ======================================*/
$("#TablaViaticos").on("click", ".btnVerReporte", function(){
  var idviatico = $(this).attr("idviatico");
  $('#ModalCenterTitleReport').html("");
  $('#ModalCenterTitleReport').html('<i class="fa fa-credit-card"></i>'+' Reporte de Viático No.: '+idviatico);
  $('#iddoc').html('VIATICO No.<br>'+idviatico);

(async () => {   
  await axios({
    method: 'post',
    url: 'ajax/control-viaticos.ajax.php?op=getDatosViatico',
    data: {
          idviatico: idviatico,
    }
  })
  .then(function (response) {
    // handle success
    //console.log(response.data);
    template1(response.data);
  })
  .catch(function (error) {
    // handle error
    console.log(error);
  })
  .then(function () {
    // always executed
    //console.log("siempre responde");
  });

  await axios({
    method: 'post',
    url: 'ajax/control-viaticos.ajax.php?op=getProofViatico',
    data: {
          idviatico: idviatico,
    }
  })
  .then(function (response) {
    //console.log(response.data);
    template2(response.data);
  })
  .catch(function (error) {
    // handle error
    console.log(error);
  })
  .then(function () {
    // always executed
    //console.log("siempre responde");
  });

})();  //fin del async
  $('#modalReporteViatico').modal({show: true});  
})

function template1(data){
  let html=document.querySelector('#app');
  let importetotal=0;
  $('#app').empty();
    comisionista=data[0].comisionista;
    fechadispersion=data[0].fecha_dispersion;
    concepto=data[0].comentario;
    descripcion_dispersion=data[0].descripcion_dispersion;
    disperso=data[0].disperso;
    saldoactual=data[0].saldo_actual;
    motivo=data[0].concepto_dispersion=1?"GASTOS A COMPROBAR":"OTRO";

  html.innerHTML+=`
  <div class="container bg-light text-dark">

  <div class="row ">
    <div class="col-md-2 ">Comisionado:</div>
    <div class="col-md ">${comisionista}</div>
    <div class="col-md-2.5">Fecha Captura:</div>
    <div class="col-md-3 ">${fechadispersion}</div>
  </div>

  <div class="row">
    <div class="col-md-2">Disperso:</div>
    <div class="col-md-3">${disperso}</div>
    <div class="col-md-2text-left">Concepto:</div>
    <div class="col-md-5">${motivo}</div>
  </div>

    <div class="row">
        <div class="col-md-3">Motivo de la Comision:</div>
        <div class="col-md-9">${descripcion_dispersion}</div>
    </div>
    <div class="row">
        <div class="col-md-12 bg-warning text-white text-center font-weight-bold">Saldo Actual:$ ${saldoactual}</div>
    </div>

  </div>
  <hr class="m-2">
  <div class="col-md-12 text-white bg-dark text-center">IMPORTES DISPERSADOS</div>
  `;
  if(data[0].importe_liberado!=null){
    for(let valor of data){
      //console.log(valor.comentario);
      importetotal+=parseFloat(valor.importe_liberado);
      html.innerHTML+=`
      <div class="legend1">
      <div class="row" style="font-size:13px;">
        <div class="col-md-2 ">${valor.fecha}</div>
        <div class="col-md-3">${valor.establecimiento}</div>
        <div class="col-md-6">${valor.comentario}</div>
        <div class="col-md-1 text-right pl-1">${valor.importe_liberado}</div>
      </div>
      </div>
    `;  
    }
    importetotal=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(importetotal);
    html.innerHTML+=`
    <div class="row" style="font-size:14px;">
      <div class="col-md-12 bg-primary text-white text-right">Total Dispersado: ${importetotal}</div>
    </div>`;  
  }else{
    html.innerHTML+=`
    <div class="row" style="font-size:14px;">
      <div class="col-md-12 bg-primary text-white text-right">Total Dispersado: $0.00</div>
    </div>`;  
  }
}

/*===========================================================*/
function template2(data){
  let html=document.querySelector('#app');
  let importetotal=0;
  html.innerHTML+=`
  <div class="container">
  <div class="row">
    <div class="col-md-12 text-white bg-dark text-center">COMPROBACION DE GASTOS</div>
    `;
  for(let valor of data){
    importetotal+=parseFloat(valor.importe_gasto);
    html.innerHTML+=`
    <div class="legend2">
    <div class="row" style="font-size:13px;">
      <div class="col-md-2">${valor.fecha_gasto}</div>
      <div class="col-md-3">${valor.numerodocto}</div>
      <div class="col-md-1"><a href=${'vista/'}${valor.ruta_factura} target="_blank" title="Ver comprobante"><i class="fa fa-eye"></i></a></div>
      <div class="col-md-5">${valor.concepto_gasto}</div>
      <div class="col-md-1 text-right pl-1">${valor.importe_gasto}</div>
    </div>
    </div>
  `;  
  }
  importetotal=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(importetotal);
  html.innerHTML+=`
  <div class="row" style="font-size:14px;">
    <div class="col-md-12 bg-primary text-white text-right">Total Ejercido: ${importetotal}</div>
  </div>
  </div>
  </div>
  `;    
}
/*===========================================================*/
// LISTAR EN EL DATATABLE REGISTROS DE LA TABLA VIATICOS
function listarViaticos(){
 let valorradio=($('input:radio[name=radiofactura]:checked').val());
 let valoryear=$("input[name='filterYearViaticos']").val();
 console.log(valoryear);
 if(valorradio!==undefined){ 
  tabla=$('#TablaViaticos').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	  "aServerSide": true,//Paginación y filtrado realizados por el servidor
		"lengthMenu": [ [10, 25, 50,100, -1], [10, 25, 50, 100, "Todos"] ],
    "language": {
		"sProcessing":     "Procesando...",
		"stateSave": true,
    "sLengthMenu":     "Mostrar _MENU_ registros &nbsp",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrar registros del _START_ al _END_ de un total de _TOTAL_",
		"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
		"sInfoPostFix":    "",           
    "sSearch":         "<i class='fa fa-search btn btn-info px-1 py-0 '></i>",
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
			{"className": "dt-left", "targets": [2]},
			{"className": "dt-center", "targets": [1,6,7]},
      {"className": "dt-right", "targets": [4,5]},				//"_all" para todas las columnas
      {"targets": 1, "type":"date-eu"}    //ordenado por fecha
      ],
		"ajax":
				{
					url: 'ajax/control-viaticos.ajax.php?op=listar',
					data:{tiporeporte: valorradio, filterYearViaticos: valoryear},
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);
					}
        },
       
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
    "order": [[ 1, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();    

// $('#TablaViaticos').on('click', 'tr', function () {
//     var name = $('td', this).eq(2).text();
//     $('#DescModal').modal("show");
//     $('.text-center').text(name);
// });
} 
} 

$(document).ready(function (){ 

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


$('#filterYearViaticos').datepicker({
  format: " yyyy",
  viewMode: "years", 
  minViewMode: "years",
  clearBtn:true,
  language:"es",
  todayHighlight:true,
}); 

/*===== AL ENTRAR AL MODAL DE AGREAGR VIATICO RESETEAR FORMULARIO Y OBTENER NUM CONSECUTIVO=====*/
$("#modalAgregarViaticos").on('show.bs.modal', ()=> {
  $('#formularioAgregarViatico')[0].reset();
    (async () => { 
    await axios('ajax/control-viaticos.ajax.php?op=getNumViatico')
    .then(function (response) {   // handle success
      let numeroviatico=parseInt(response.data);
      if(numeroviatico>0){
        numeroviatico+=1;
				 $("#nvoIdViatico").val(numeroviatico);
 				 $("#nvoComisionadoV").first().focus();
      }else{
        $("#nvoIdViatico").val(1);
        $("#nvoComisionadoV").focus();
      }
    })
    .catch(function (error) {   // handle error
      console.log(error);
    })
    .then(function () {   // always executed
      //console.log("siempre responde");
    });
  })();  //fin del async  
});

/*===========================================================
REPORTE DE VIATICOS EN EXCEL
============================================================*/
$("#TablaViaticos").on("click", ".btnExcel", function(){
  var idViatico = $(this).attr("idviatico");
  if(idViatico.length > 0){
    window.open("extensiones/tcpdf/pdf/viatico-excel.php?codigo="+idViatico,"_self");
   }  
})

/*================ AL SALIR DEL MODAL DE EDICION RESETEAR FORMULARIO==================*/
$("#modalEditarViaticos").on('hidden.bs.modal', ()=> {
	$('#formularioEditFactura')[0].reset();
});

/*================ AL SALIR DEL MODAL DE AGREGAR RESETEAR FORMULARIO==================*/
$("#modalAgregaViatico").on('hidden.bs.modal', ()=> {
	$('#formularioAgregaViatico')[0].reset();
});

/*================ AL SALIR DEL MODAL CAPTURA GASTO RESETEAR FORMULARIO==================*/
$("#modalCheckup").on('hidden.bs.modal', ()=> {
  $("#submitGasto").prop('disabled',false);
	$('#formularioCheckup')[0].reset();
});
  
init();


