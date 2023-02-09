$("#modalAgregarcuentahabiente,#modalAgregarDeposito").draggable();
var tablaControlDepositos='';
var modalEvento='';
var fechaInicial;
var fechaFinal;
var FechDev1;
var FechDev2;
/**************************************************************/
// VARIABLES PARA OBTENER LA SEMANA ANTERIOR DE MEXPEI
/**************************************************************/
var startFecha;
var endFecha;
const HOY=parseInt(moment().format('d')); //Numero del dia de la semana
                          //V,S,D,L,M,M,J
const NUM_SEMANA= new Array(5,6,0,1,2,3,4)   //array con dias de la semana mexpei
                        //1,2,3,4,5,6,7
const NUM_DIAS= new Array(1,2,3,4,5,6,7)   //array de dias de la semana
const EXTRAE=parseInt(NUM_SEMANA.indexOf(HOY));  
endFecha=NUM_DIAS[EXTRAE];
startFecha=NUM_SEMANA[EXTRAE]+NUM_DIAS[EXTRAE]+2;

/**************************************************************/

$('#edit-benef').hide();

/********  Función que se ejecuta al inicio **************/
function init(){
  semanamexpei();
  dt_crtl_depositos();
}
/********  Fin Función que se ejecuta al inicio ***********/

/*=============================================
Editar Registro de Deposito.
=============================================*/
$("#dt-crtl-depositos tbody").on("click", ".btnEditDeposito", function(){

  let iddep=$(this).parents("tr").find("td").eq(0).html();
  //console.log(iddep);
  let datos = new FormData();
  datos.append("iddep", iddep);

  axios({ 
    method  : 'post', 
    url : 'ajax/control-depositos.ajax.php?op=editardeposito', 
    data : datos
  }) 
  .then((res)=>{ 
    if(res.data.respuesta=='400') {
      //console.log(res.data[0])
      mostrardatosdeposito(res.data[0]);
    }

  }) 
  .catch((err) => {
    alert("ERROR. No se encontro Registro.")
    throw err;
  }); 
  
})

/*==============================================================================*/
function mostrardatosdeposito(datos){

  $('#form-benef').hide();
  $('#edit-benef').show();
  $(".modal-title").text('Editar depósito'); 
  $("#identifica").val(datos.id); 
  $("#beneficiario").val(datos.nombrecuentahab);
  $("#nvoMotivoDeposito").val(datos.motivo);
  $("#nvoImporte").val(parseFloat(datos.monto_transaccion));
  $("#nvoComision").val(parseFloat(datos.monto_comision));
  $('#nvoTotal').val(nvoimporte(parseFloat(datos.monto_transaccion), parseFloat(datos.monto_comision)));
  $("#nvoBanco").val(datos.nombre);
  $("#idBanco").val(datos.id_destino);
  $("#nvoCuenta").val(datos.numerotarjeta);
  $("#nvoFecha").val(datos.fecha_transaccion);
  $("#nvoSucursal").val(datos.sucursal);
  
  modalEvento=new bootstrap.Modal(document.getElementById('modalAgregarDeposito'),{ keyboard:false });
  modalEvento.show();
  
}
/*==============================================================================*/


/*=============================================
ASIGNA FECHAS EN DATERANGEPICKER 
=============================================*/
function semanamexpei(){ 

  if(HOY==5){

    fechaInicial=moment().format('DD-MM-YYYY');
    fechaFinal=moment().format('DD-MM-YYYY');

  }else{

    fechaInicial=moment().subtract(EXTRAE, 'days').format('DD-MM-YYYY');
    fechaFinal=moment().format('DD-MM-YYYY');
  }

  $("#daterange-btn-ctrldepositos span").html(fechaInicial+' - '+fechaFinal);

  
}    
/************************* TERMINA FUNCION *************************** */

// ========= LISTAR EN EL DATATABLE REGISTROS DE LA TABLA ================
function dt_crtl_depositos(){
  let rangodeFecha = $("#daterange-btn-ctrldepositos span").html();

  //console.log("Rango de Fecha:",rangodeFecha);

  if(rangodeFecha==undefined || rangodeFecha==null){
      FechDev1=moment().format('YYYY-MM-DD');
      FechDev2=moment().format('YYYY-MM-DD');
      //console.log('fecha hoy:',FechDev1,FechDev2);
  }else{
	  let arrayFecha = rangodeFecha.split(" - ", 2);
    //console.log('fechas:',arrayFecha, arrayFecha[0], arrayFecha[1]);
	  FechDev1=moment(arrayFecha[0],'DD-MM-YYYY').format('YYYY-MM-DD');
	  FechDev2=moment(arrayFecha[1],'DD-MM-YYYY').format('YYYY-MM-DD');

    //console.log('fechas:',FechDev1, FechDev2);
  }	   
 
  tablaControlDepositos=$('#dt-crtl-depositos').dataTable(
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
    "pagingType": "full_numbers",
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
        },
        {
          text: 'Recargar',
          className: 'orange',
          action: function ( e, dt, node, config ) {
            location.reload();
          }
        }        
        ],
        initComplete: function () {
          var btns = $('.dt-button');
          btns.removeClass('dt-button');
          btns.addClass('btn btn-success btn-sm');

        },  
        "columnDefs": [
          {"className": "dt-center", "targets": [6,7,8,9]},
          {"className": "dt-right", "targets": [3,4]}				//"_all" para todas las columnas
          ],
          "footerCallback": function ( row, data, start, end, display ) {
          var api = this.api();

          // Total over this page subtotal
          var pageSubTot = api.column(3, {page:'current'}).data().sum();
          //pageSubTot=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(pageSubTot);

          // Total over this page comision
          var pageTotCom = api.column(4, {page:'current'}).data().sum();
          //pageTotCom=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(pageTotiva);
          var totPagActual=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format((pageSubTot+pageTotCom));

          // Total over all pages
          let monto_trans = api.column(3).data().sum();
          let comision =api.column(4).data().sum();
          let total=monto_trans+comision;
          subtotal=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(monto_trans);
          comision=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(comision);
          total=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(total);

          //console.log(total);
          $(api.column(2).footer()).html('<p class="text-right" >Total de la semana:</p>');
          $(api.column(3).footer()).html(subtotal);
          $(api.column(4).footer()).html(comision);
          $(api.column(5).footer()).html(total);
          
          $('.dataTables_filter input').bind('keyup', function(e) {
            if (e.keyCode==08 || e.keyCode==27){    //SI ES BACKSPACE O ESC LIMPIA EL IMP. DE LO SELEC.
              $(api.column(1).footer()).html(` `);
            }else{
              $(api.column(1).footer()).html(`Total Datos Selecionados: ${totPagActual}`);
            }
            
          });
        
      },    
      // para q los titulos esten fijos y se quite la paginación
      scrollY: 450,
      scrollX: true,
      scrollCollapse: true,
      paging: false,
      select: true,
      keys: true,    //KeyTable allows you to use keyboard navigation, like an Excel spreadsheet.
		"ajax":
				{
          url: 'ajax/control-depositos.ajax.php?op=listar',
          data: {"FechDev1": FechDev1,  "FechDev2": FechDev2},     
					type : "POST",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);
					}
				},
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	  "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();    
    
  
  $(window).on('resize', function() {
    $('#dt-crtl-depositos').css('width', '100%');
    tablaControlDepositos.draw(true);
  });  
} 

/*==================================================================*/
//hoy=parseInt(moment().format('d'));
//let EXTRAE=parseInt(NUM_SEMANA.indexOf(hoy));
//fechaInicial=moment().subtract(EXTRAE, 'days').format('DD-MM-YYYY');
//fechaFinal=moment().format('DD-MM-YYYY');
//'Ult. Sem. MexPei' : [moment().subtract(7, 'days'), moment().subtract(1, 'days')],
/*==================================================================*/

$('#daterange-btn-ctrldepositos').daterangepicker({
  ranges   : {
    'Hoy'       : [moment(), moment()],
    'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    'Últimos 7 Días' : [moment().subtract(6, 'days'), moment()],
    'Ult. Sem. MexPei' : [moment().subtract(startFecha, 'days'), moment().subtract(endFecha, 'days')],
    'Últimos 30 Días': [moment().subtract(29, 'days'), moment()],
    'Este Mes'  : [moment().startOf('month'), moment().endOf('month')],
    'Último Mes'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
  },

  "locale": {
      "format": "YYYY-MM-DD",
      "separator": " - ",
      "daysOfWeek": [
          "Do",
          "Lu",
          "Ma",
          "Mi",
          "Ju",
          "Vi",
          "Sa"
      ],
      "monthNames": [
          "Enero",
          "Febrero",
          "Marzo",
          "Abril",
          "Mayo",
          "Junio",
          "Julio",
          "Agosto",
          "Septiembre",
          "Octubre",
          "Noviembre",
          "Diciembre"
      ],
      "firstDay": 1
  },          
  startDate: moment(),
  endDate  : moment()
},
 function (start, end) {
  $('#daterange-btn-ctrldepositos span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
  dt_crtl_depositos();
 }
)

/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/
$('#daterange-btn-ctrldepositos').on('cancel.daterangepicker', function(ev, picker) {
  localStorage.removeItem("RangoFechactrldepositos ");
  localStorage.clear();
  $("#daterange-btn-ctrldepositos span").html('<i class="fa fa-calendar"></i> Rango de fecha')
});


/*=============================================
SELECCIONAR BENEFICIARIO POR AJAX. https://code-examples.net/es/q/1805656
=============================================*/
$('#nvoBeneficiario').select2({
  placeholder: '-------------- Seleccione Nombre o Cuenta ---------------',
  theme: "bootstrap4",
  ajax: {
    url: 'ajax/control-depositos.ajax.php?op=ajaxBeneficiario',
    async: true,
    dataType: 'json',
    delay: 250,   //Puede decirle a Select2 q espere hasta q el usuario haya terminado de escribir su término de búsqueda antes de activar la solicitud AJAX. Simplemente use la opción de configuración ajax.delay para decirle a Select2 cuánto tiempo debe esperar después de que un usuario haya dejado de escribir antes de enviar la solicitud:
    type: "POST",
    data: function (params) {
      //console.log(params);
      if ($.trim(params.term) === '') {
        console.log("data:",data, "item;", item, "params:", params.term );
        return data;
      };
      return {
        searchTerm: params.term
       };  
    },
    processResults: function (data) {
      return {
          results: $.map(data, function (item,params) {
              return {
                results: data.items,
                  text: item.nombrecuentahab+' - '+item.numerotarjeta,
                  id: item.id,
                  // nombrecuentahab: item.nombrecuentahab,
              }
              
          }),
          
      };
  },  
    cache: true
  },
  language: {
    inputTooShort: function () {
      return 'Ingrese min. 2 caracteres';
    },
    noResults: function() {
      return "No hay resultado";        
    },
    searching: function() {
      return "Buscando..";
    }
  },
  minimumResultsForSearch: 20,
  minimumInputLength: 2,
  dropdownCssClass: "bigdrop", 
  allowClear: true 
})

/* ************************************************************************* */
// Obtiene datos del cuentahabiente segun su ID, por ajax
/* ************************************************************************* */
$('#nvoBeneficiario').on('keyup blur change', function(ev) {
var datos = new FormData();
nNvoId=parseInt($('#nvoBeneficiario').val());
datos.append("numero_id", nNvoId);
//console.log(nNvoId)
if(nNvoId>0)
axios({ 
  method  : 'post', 
  url: 'ajax/control-depositos.ajax.php?op=datosBeneficiario',
  data : datos, 
}) 
  .then((res)=>{ 
    if(res.status==200) {
      //console.log(res.data['nombrecuentahab'], res.data['id_destino'], res.data['banco']);
      $("input#nvoBanco").prop('disabled', true);
      $('#nvoMotivoDeposito').val(res.data['usodeposito'])
      $('#idBanco').val(res.data['id_destino'])
      $('input#nvoBanco').val(res.data['banco'])
    }            
  }) 

  .catch((err) => {
    throw err
  }); 
})
/*==============================================================================*/

/* ==================================================
Calcula el importe depositado
===================================================*/
let nNvoComision=nNvoImporte=nvoTotal=0;
$('#nvoImporte, #nvoComision').on('keyup change', function(ev) {
	//console.log("se pulso:",ev.keyCode);
  nNvoImporte=parseFloat($('#nvoImporte').val());
  nNvoComision=parseFloat($('#nvoComision').val());

	if(nNvoImporte>0){
    nNvoComision = nNvoComision == 0 ? 0 :
    (nNvoComision) > 0 ? nNvoComision :0;
    $('#nvoTotal').val(nvoimporte(nNvoImporte,nNvoComision));
	}else{
    $("input#nvoImporte").focus();
    $('#nvoTotal').val("");
    return false;
  }

})

/* ************************************************************************ */
function nvoimporte(nNvoImporte,nNvoComision){
  nvoTotal=0;
  nvoTotal=new Intl.NumberFormat('en', {minimumFractionDigits: 2,}).format(nNvoImporte+nNvoComision);
  return nvoTotal;
}

/************************************************************************* */
$('#nvoBeneficiario').on('change', function(ev){
  let nvoBeneficiario=$("#nvoBeneficiario option:selected" ).text();       //obtener el texto del valor seleccionado
  let numerotarjeta=nvoBeneficiario.substr(nvoBeneficiario.lastIndexOf("-") + 1);
  $('#nvoCuenta').val(numerotarjeta.trim());
  //console.log(ev.currentTarget);
  if(nvoBeneficiario==''){
    return false;
  }
});
/* ************************************************************************ */
$('#nvoMotivoDeposito').on('change', function(ev){
  let nvoBeneficiario=$("#nvoBeneficiario option:selected" ).text();       //obtener el texto del valor seleccionado
  let numerotarjeta=nvoBeneficiario.substr(nvoBeneficiario.lastIndexOf("-") + 1);
  $('#nvoCuenta').val(numerotarjeta.trim());
  //console.log(ev.currentTarget);
  if(nvoBeneficiario==''){
    return false;
  }
});
/*================================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR DATOS DE DEPOSITO
/*===============================================================================*/
$("body").on("submit", "#form-AgregaDeposito", function( event ) {
  event.preventDefault();
  event.stopPropagation();
  let accion='';
  let formData = new FormData($("#form-AgregaDeposito")[0]);
  let identifica=parseInt(document.getElementById('identifica').value);

  accion=identifica==0?'agregar':'actualizar';
  
  axios({ 
    method  : 'post', 
    url : 'ajax/control-depositos.ajax.php?op='+accion, 
    data : formData, 
  }) 
  .then((res)=>{ 
    if(res.data=="ok") {
      if(identifica==0){
        $('#modalAgregarDeposito').modal('hide')
      }else{
        $("#identifica").val(0); 
        modalEvento.hide();
      }
      $('#dt-crtl-depositos').DataTable().ajax.reload(null, false);
      ohSnap('Registro guardado correctamente.', {duration: '2500', color: 'success', icon: 'icon-alert'});
    }
    //console.log(res); 
  }) 
  .catch((err) => {
    alert("Registro No Guardado.")
    throw err;
  }); 
});  
/* ********************************************************************************** */

/*================================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR DATOS DEL CUENTAHABIENTE
/*===============================================================================*/
$("body").on("submit", "#form-Agregarcuentahabiente", function( event ) {	
  event.preventDefault();
  event.stopPropagation();

  let formData = new FormData($("#form-Agregarcuentahabiente")[0]);  

  axios({ 
    method  : 'post', 
    url : 'ajax/control-depositos.ajax.php?op=guardarCuantaHabiente', 
    data : formData, 
  }) 
  .then((res)=>{ 
    if(res.data=="ok") {

      console.log(res.data)

      $('#modalAgregarcuentahabiente').modal('hide')
      alert("Registro de cuenta Guardado.")

    }
    console.log(res); 
  }) 
  .catch((err) => {
    alert("Registro No Guardado.")
    throw err;
  }); 

});  
/* ********************************************************************************** */

/*===========================================================================
    Borrar Registro de Deposito.
===========================================================================*/
$("#dt-crtl-depositos tbody").on("click", ".btnDelDeposito", function(){

  let idDep = $(this).attr("idDeleteDeposito");
  let datos = new FormData();
  datos.append("idDep", idDep);
    
    swal({
      title: "¿Está seguro de Borrar Depósito No. "+idDep+"? ",
      text: "Si no lo esta puede cancelar la acción!",
	    icon: "vistas/img/logoaviso.jpg",
      buttons: {
        cancel: "No, Cancelar",
        defeat: "Sí, Borrar",
      },      
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        axios('ajax/control-depositos.ajax.php?op=borrar', {
          method: 'POST',
          data: datos
         })
         .then((res)=>{ 
          //console.log(res.data.respuesta)
          if(res.data.respuesta=="ok") {
            $('#dt-crtl-depositos').DataTable().ajax.reload(null, false);
            mensajenotie('success', 'Registro borrado correctamete!', 'bottom');
          }
        }) 
        .catch((err) => {
          alert("Error!! Registro No Borrado.")
          throw err;
        }); 
      } else {
        return
      }
    });
})
/*==============================================================================*/

/*================ AL SALIR DEL MODAL AGREGAR DEPOSITOS RESETEAR FORMULARIO==================*/
$("#modalAgregarDeposito").on('hidden.bs.modal', ()=> {
	$('#form-AgregaDeposito')[0].reset();
  $(".modal-title").text('Agregar Depósito');
  $('#form-benef').show();
  $('#edit-benef').hide();
  $('#nvoBeneficiario').val(null).trigger('change'); 
});
/*================ AL SALIR DEL MODAL AGREGAR BENEFICIARIO RESETEAR FORMULARIO==================*/
$("#modalAgregarcuentahabiente").on('hidden.bs.modal', ()=> {
	$('#form-Agregarcuentahabiente')[0].reset();
});
/* *****************AL ABRIR EL MODAL ************************************** */
$('#modalAgregarDeposito').on('shown.bs.modal', function () {
  //console.log('modal abierto');
  if ($('#edit-benef').is(':visible')) {
      $('#nvoBeneficiario').hide();
      $('#nvoBeneficiario').removeAttr("required");
      $('#beneficiario').attr('readonly', true);
  } else {
      $('#nvoBeneficiario').show();
      $('#nvoBeneficiario').attr('required', true);
  }  //end if
})
/*==============================================================================*/

/*==================================================================*/
//DOBLE click para seleccionar REFERENCIA Y COPIAR AL PORTAPAPELES
/*==================================================================*/
$("#dt-crtl-depositos tbody").on("dblclick", "tr",  function(){

  $(this).toggleClass('selected');
    //console.log(tablaControlDepositos.row( this ).data()[6]);
    content=(tablaControlDepositos.row( this ).data()[6]);
    navigator.clipboard.writeText(content)
        .then(() => {
        //console.log("Text copied to clipboard...")
        ohSnap('Referencia ha sido copiado al portapapeles...!', {duration: '2000', color: 'info', icon: 'icon-alert'});
    })
        .catch(err => {
        console.log('Something went wrong', err);
    })
    $(this).toggleClass('selected');
});



init();

/*
                  datos: $.each(data, function(i, item) {
                    myArray.push(item.numerotarjeta);
                    console.log(item.numerotarjeta)
                    llenardatos(item.numerotarjeta)
                  }),

  
  setTimeout(() => {
  }, 2000);
   (async () => {
   })();  //fin del async      
    
    
        
     $("#nvoBeneficiario").select2({
       initSelection : function (element, callback) {
           var data = {id: datos.id_cuentahabiente, text: datos.nombrecuentahab+' - '+datos.numerotarjeta};
           callback(data);
       }
     });    


*/