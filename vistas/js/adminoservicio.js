//$( "#modalAgregarOS" ).draggable();
var item;
var data;
var arrayProductosOS=new Array();
var renglonesOS=cantSalidaOS=0;
var numserie=alfanumerico='';
var captRangoFecha;
var idsfacturas=new Array();
const { fromEvent } = rxjs;
//$("#msgsaveok").addClass("d-none");


//Rx.Observable.fromEvent(document.getElementById("DatatableOS"), 'click').subscribe(() => console.log('Haz hecho click!'));

function init(){

  /*=============================================
  VARIABLE LOCAL STORAGE
  =============================================*/
  if(localStorage.getItem("captRangoFecha") != null){
    $("#daterange-btnOS span").html(localStorage.getItem("captRangoFecha"));
  }else{
    //$("#daterange-btnOS span").html('<i class="fa fa-calendar"></i> Rango de fecha')
    iniciarangodefecha()
    console.log("si entra")
  }

    listarOServicios();   //LISTAR OS EN EL DATATABLE

    $("#btnGuardarOS").hide();
    $(".mostrarcantidades").hide();

}

/*================================================================================== */
$('#numeroos').on('blur', ()=> {
  $("#numeroos").css({"background-color": "white", "color":"black"});
  let numos=$('#numeroos').val();
  //console.log("si entra aqui..",numos)
  if(numos<1 || length.numos<1){
    $('#numeroos').focus();
    return
  }
  (async () => { 
    resp=await getUser(numos)
    //console.log(resp)
    if(resp!=undefined){
      $("#numeroos").css({"background-color": "red", "color":"yellow"});
      $('#numeroos').focus();
      return
    }
  })();  //fin del async	 	

});

async function getUser(numeroos) {
  try {
    const response = await axios.get('ajax/adminoservicio.ajax.php?op=getDataNumOS', {
      params: {
        numeroos: numeroos
      }
    })
    //console.log(response.data.ordenservicio);
    return response.data.ordenservicio;

  } catch (error) {
    console.error(error);
  }
}
/*================================================================================== */

// ========= LISTAR EN EL DATATABLE REGISTROS DE LA TABLA TABLAOS ================
function listarOServicios(){
  let rangodeFecha = $("#daterange-btnOS span").html();

  if(rangodeFecha==undefined || rangodeFecha==null){
    var FechDev1=moment().format('YYYY-MM-DD');
    var FechDev2=moment().format('YYYY-MM-DD');
    console.log('fecha hoy:',FechDev1,FechDev2);
  }else{
    let arrayFecha = rangodeFecha.split(" - ", 2);
    //console.log('fechas Mex:',rangodeFecha);
    var FechDev1=moment(arrayFecha[0],'DD-MM-YYYY').format('YYYY-MM-DD');
    var FechDev2=moment(arrayFecha[1],'DD-MM-YYYY').format('YYYY-MM-DD');
    //console.log('fechas US:',FechDev1, FechDev2);
  }	   

  tblOrdendeServicios=$('#DatatableOS').dataTable(
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
            text: 'Imp. Selección',
            className: 'btn btn-dark btn-sm',
            action: function ( e, dt, node, config ) {
              printselec();
            }
          }        
          ],
          initComplete: function () {
            var btns = $('.dt-button');
            btns.removeClass('dt-button');
            btns.addClass('btn btn-success btn-sm');
          },  
          "columnDefs": [
            {"className": "dt-center", "targets": [1,3,4,6,7,8,9,10]},
          
            //{"className": "dt-right", "targets": [3,4]}				//"_all" para todas las columnas
            ],    
            select: false,     //se puso a false para poder seleccionar varios filas. true=1 fila
            scrollX: true,
          "ajax":
            {
              url: 'ajax/adminoservicio.ajax.php?op=listaroservicios',
              data: {"FechDev1": FechDev1,  "FechDev2": FechDev2},     
              type : "POST",
              dataType : "json",						
              error: function(e){
                console.log(e.responseText);
              }
            },
      "bDestroy": true,
      "iDisplayLength": 10,//Paginación
      "order": [[ 6, 'desc' ], [ 0, 'desc' ]] //Ordenar (columna,orden)
    }).DataTable();    
      

}
// ========= FIN LISTAR EN EL DATATABLE REGISTROS DE LA TABLA TABLAOS================

/**************************************************************/
//añadir un INPUT en la columna de No. de Fact
$('#DatatableOS tfoot th').each( function () {
  var title=$(this).text();

  if(title=="ID"){
    $(this).html('<input type="text" id="myID" style="width:25px; height:20px;" placeholder="'+title+'"/>');
  }
  
  if(title=="OS"){
    $(this).html('<input type="text" id="myOS" style="width:50px; height:20px;" placeholder="'+title+'"/>');
    //Hacer busqueda por la columna de No. de Fact. segun el dato del INPUT
    $('#myOS').on( 'keyup change clear', function () {
      if(tblOrdendeServicios.column(3)){
        tblOrdendeServicios.column(3).search(this.value).draw();
      };
    } );
  }
  
  if(title=="Teléfono"){
    $(this).html('<input type="text" id="myTelefono" style="width:60px; height:20px;" placeholder="'+title+'"/>');
      //Hacer busqueda por la columna de No. de Fact. segun el dato del INPUT
      $('#myTelefono').on( 'keyup change clear', function () {
        if(tblOrdendeServicios.column(4)){
          tblOrdendeServicios.column(4).search(this.value).draw();
        };
      } );
  }
  
  if(title=="Fact"){
    $(this).html('<input type="text" id="myFact" style="width:40px; height:20px;" placeholder="'+title+'"/>');
    $('#myFact').on( 'keyup change clear', function () {
      if(tblOrdendeServicios.column(9)){
         tblOrdendeServicios.column(9).search(this.value).draw();
      };
    } );
    
  }
});

//Hacer busqueda por la columna de No. de Fact. segun el dato del INPUT
$('#myID').on( 'keyup change clear', function () {
  if(tblOrdendeServicios.column(0)){
     tblOrdendeServicios.column(0).search(this.value).draw();
  };
} );
/**************************************************************/

/* ====================click para seleccionar OS ====================*/
$('#DatatableOS tbody').on( 'click', 'tr', function () {
  $(this).toggleClass('selected');
  let x=(tblOrdendeServicios.rows('.selected').data().length);
  //console.log(tblOrdendeServicios.rows('.selected').data().length)
  idsfacturas=[];
    for(var i=0; i<x; i++) {
      idsfacturas.push(tblOrdendeServicios.rows('.selected').data()[i][0]);
    };
});
/* ====================Fin para seleccionar OS ====================*/

/* ====================CAMBIAR ESTADO DE OS ====================*/
$('#DatatableOS tbody').on( 'click', 'button.btnEstado', function (event) {
  var dtr=document.getElementById("DatatableOS");
  let elemento = this;
  const $button = document.getElementById('DatatableOS');
  const click$ = fromEvent($button, 'click');
  //console.log(elemento);
  let id = elemento.getAttribute('data-id');
  let st = elemento.getAttribute('data-estado');

  if(st==0){    //si OS esta facturado
      swal({
      title: "¿Cambiar estatus de Facturado a OS?",
      text: "¡Si no está seguro, puede Cancelar la acción!",
      icon: "warning",
      buttons: ["Cancelar", "Sí, Cambiar"],
      dangerMode: true,
    })
    .then((aceptado) => {
      if (aceptado) {

        axios.post('ajax/adminoservicio.ajax.php?op=cambiarEstadoOS', {
          idos: id,
          estado: 1
        })
      
        .then(function (response) {
          //console.log(response.status);
          if (response.status==200) {
            if($('#DatatableOS').DataTable().ajax.reload(null, false)){
              var subscription = click$.subscribe({
                next: (e) => console.log('Event :', e)
              });
              // Rx.Observable.fromEvent(dtr, 'change').subscribe(() => 
              //   console.log('se actualizo registro!!')
              // );
            };
            console.log(subscription);
          } else {
            alert("ERROR!!")
          }
        })
        .catch(function (error) {
          console.log(error);
        });      
      }
    }) 

  }else{  //si OS no esta facturado

    swal({
      text: 'Número de Factura.? ó "ESC" para salir',
      content: "input",
      button: {
        text: "Enviar!",
        closeModal: false,
      },
    })
    .then(name => {
      if (!name) throw null;
        axios.post('ajax/adminoservicio.ajax.php?op=cambiarEstadoOS', {
        idos: id,
        estado: 0,
        factura: name
      })

      .then(function (response) {
        if (response.status==200) {
          $('#DatatableOS').DataTable().ajax.reload(null, false);
          swal.stopLoading();
          swal.close();
        } else {
          alert("ERROR!!")
          swal.close();
        }
      })
  
      .catch(function (error) {
        if (error) {
          swal("Oh no!", "The AJAX request failed!", "error");
        } else {
          //swal.stopLoading();
          //swal.close();
        }
      });
  
    })

  }
});


/****************************************************************** */
// DESELECCIONA ALMACEN PARA EVITAR CAMBIARLO
$("#nuevoAlmacenOS").change(function(){
  $('#nuevoAlmacenOS option:not(:selected)').attr('disabled',true);
  $("#btnGuardarOS").show();
});
/*=============================================
SELECCIONAR PRODUCTO POR AJAX
=============================================*/
var $eventSelect = $('#selecProductoOS').select2({
  placeholder: 'Seleccione producto...',
  theme: "bootstrap4",
  ajax: {
    url: 'ajax/adminoservicio.ajax.php?op=buscarProdx',
    async: true,
    dataType: 'json',
    delay: 250,   //Valor par indicarle al Select2 q espere hasta q el usuario haya terminado de escribir su 'término de búsqueda' antes de activar la solicitud AJAX. Simplemente use la opción de configuración ajax.delay para decirle a Select2 cuánto tiempo debe esperar después de que un usuario haya dejado de escribir antes de enviar la solicitud:
    type: "POST",
    data: function (params) {
      if ($.trim(params.term) === '') {
        return data;
      };
      //console.log("data1:",data);
      return {
        id_almacen: $("#nuevoAlmacenOS").val(),
        id_tecnico: $("#nvotecnico").val(),
        searchTerm: params.term
       };  
    },
    processResults: function (data,params) {
      return {
          results: $.map(data, function (item, params) {
            //console.log("data:",data[3]['existe'], "item:",item, "params:", params );
            if(item.existe>0){
              return {
                  text: item.descripcion+' - '+item.codigointerno,
                  id: item.id_producto+'-'+item.conseries+'-'+item.existe   //id de prod, si tiene serie, existencia
            }
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
      return "No hay resultado.";        
    },
    searching: function() {
      return "Buscando..";
    }
  },
  minimumResultsForSearch: 20,
  minimumInputLength: 2,
  allowClear: true
});

/************************************************************************************* */
 // Bind an event
// $('#selecProductoOS').on('select2:select', function (e) { 
//   console.log('select event');
// });

//SI ABRE EL SELECT, INICIALIZA VALORES DE SALIDA
$eventSelect.on("select2:open", function (e) { 
  inicializa(0)
});

//$eventSelect.on("select2:close", function (e) { console.log("select2:close", e); });
//$eventSelect.on("select2:select", function (e) { console.log("select2:select", e); });
//$eventSelect.on("select2:unselect", function (e) { console.log("select2:unselect", e); });
//$eventSelect.on("change", function (e) { console.log("change"); });
/************************************************************************************ */

/************************************************************************************ */
$("#selecProductoOS").change(function(event){
  //console.log($("#selecProductoOS").val())
  //console.log($("#selecProductoOS").text())
  if($("#selecProductoOS").val()!=null){
    cadena=$("#selecProductoOS").val();
    let descripcion=$("#selecProductoOS").text();
    descripcion=descripcion.trim();

    //SEPARA EL ID DEL PRODUCTO SELECCIONADO
    let idproducto= cadena.substr(0, cadena.indexOf('-'));
    idproducto=idproducto.trim();
    let largo=idproducto.length;
    //console.log('Largo idproducto',largo, idproducto);
    

    //SEPARA EL IDENT PARA SABER SI ES MODEM
    let conserie= cadena.substr(largo+1, cadena.indexOf('-')-1);
    conserie = conserie.replace(/-/g,'');   //si encuentra un guion (-) lo sustituye con vacio
        
    //SEPARA LA EXISTENCIA DEL PROD. SELECCIONADO        
    let stock=parseFloat(cadena.substr(cadena.lastIndexOf("-") + 1));
    //console.log('ID:',idproducto,' EXISTENCIA:',stock, 'CONSERIE:',conserie, 'Prod:',descripcion );

      $('#stocktecnico').val(stock);

      if(conserie>0){
        $("#datosmodem").removeClass("d-none");
      }
      
  }
})  //fin del select2

/*******************************************************
VERIFICA QUE LA CANT SALIENTE NO SEA MAYOR QUE LA EXIST. 
********************************************************/
$("#cantout").change(function(evt){
let sald=$('#cantout').val();
let cant=parseFloat($('#stocktecnico').val());
if(sald>cant || sald<0){
  swal({
    text:'Cantidad saliente es mayor que la Existencia!',
    icon: "warning",
    button: "Entendido",
	  timer: 3000
  })  //fin .then
  $("#cantout").val(0);
  $("#cantout").focus();
}
})  //fin de checar cant de salida
/*======================================================================*/

/*============================================================
                AGREGA PRODUCTO SELECCIONADO
============================================================*/
$("#agregarProductoOS").click(function(event){
  event.preventDefault();
  let cadena=$("#selecProductoOS").val();
  if(cadena==null)
  return;
  let idproducto= cadena.substr(0, cadena.indexOf('-'));  //extrae el Id del prod
  let largo=idproducto.length;
  let id_producto=parseInt(idproducto);
  //SEPARA EL ID PARA SABER SI ES MODEM
  let conserie= cadena.substr(largo+1, cadena.indexOf('-')-1);
  //OBTENER LA CANT DE SALIDA
  let sald=$('#cantout').val();   //obtener la cant de salida
  let cantidad=parseFloat(sald);  
  //Si no selecciona producto retorna o cantidad
  if(isNaN(idproducto) || isNaN(cantidad) || cantidad<0.01){
    return true;
  }  

  if(parseInt(conserie)>0){
    numserie=document.getElementsByName('numeroSerie')[0].value;
    numserie=numserie.trim();
    alfanumerico=document.getElementsByName('alfanumerico')[0].value;
    alfanumerico=alfanumerico.trim();
    //console.log('si debe entrar',numserie)
  }else{
    //console.log('no debe entrar',conserie)
  }

  let descripcion=$("#selecProductoOS").text();   //extrae la descripcion del prod
  descripcion=descripcion.trim();

  //console.log("prod:",id_producto, "cant:",cantidad, "descrip:",descripcion, 'CON SERIE',conserie, 'numserie:',numserie);
  
  let encontrado=arrayProductosOS.includes(id_producto)
  //console.log("encontrado:", encontrado)

    if(!encontrado){
      arrayProductosOS.push(id_producto);
      addProductosSalida(id_producto, descripcion, cantidad, conserie, numserie, alfanumerico);
    }else{
      //mensajedeerror();
      console.log('si encontrado')
      inicializa(1);
    }
  
  }); 


/*==================================================================
ADICIONA PRODUCTOS AL TBODY
==================================================================*/
function addProductosSalida(...argsProductos){
  //console.log("manyMoreArgs", argsProductos);
  let contenido=document.querySelector('#tbodyOS');
  //console.log(argsProductos[4]);
  contenido.innerHTML+=`
  <tr class="filas" id="fila${argsProductos[0]}">
    <td> <button type="button" class="botonQuitar" onclick="eliminarProductoOS(${argsProductos[0]}, ${argsProductos[2]})" title="Quitar concepto">X</button> </td>
    <td class='text-center'>${argsProductos[0]} <input type="hidden" name="idproducto[]" value="${argsProductos[0]}"</td>
    <td class='text-center'>${argsProductos[1]}</td>
    <td class='text-center'>${argsProductos[2]} <input type="hidden" name="cantidad[]" value="${argsProductos[2]}"</td>`;
    if(argsProductos[3]>0){
      contenido.innerHTML+=`
      <input type="hidden" name="nvonumserie" value="${argsProductos[4]}">
      <input type="hidden" name="nvoalfanumerico" value="${argsProductos[5]}"/>`;
    }
    contenido.innerHTML+=`</tr>`;

    renglonesOS++;
    cantSalidaOS+=argsProductos[2];
    evaluaFilaOS(renglonesOS, cantSalidaOS);
    inicializa(argsProductos[3]);
  
  }

 /*==================================================================*/
  function evaluaFilaOS(renglonesOS, cantSalidaOS){
    $("#renglones").html(renglonesOS);
    $("#totalsalidaOS").html(cantSalidaOS.toFixed(2));
    if(renglonesOS>0){
      $("#btnGuardarOS").show();
      $(".mostrarcantidades").show();
    }else{
      $("#btnGuardarOS").hide();
      $(".mostrarcantidades").hide();
    }
  }
/*==================================================================*/
  function inicializa(conserie){
    //DESPUES DE AÑADIR, SE INICIALIZAN SELECT E INPUT
    $('#selecProductoOS').text(null);
    $('#selecProductoOS').val(null).trigger('change');
    $("#stocktecnico").val(0);
    $("#cantout").val(0);
    
    if(conserie>0){
      $("#datosmodem").addClass("d-none");
    }else{
      inputsmodem=document.getElementById("datosmodem");
        if(!inputsmodem.classList.contains("d-none")){
          $("#datosmodem").addClass("d-none");
        }
    }
    
  }

/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR DATOS DE ORDEN DE SERVICIO
/*======================================================================*/
$("body").on("submit", "#formularioAgregaOS", function( event ) {	
  event.preventDefault();
  event.stopPropagation();	
  var mensajeaxios='Registro Guardado';
  var tipoerror=1;
  var tiempo=2;
  
    swal({
        title: "¿Está seguro de Guardar OS?",
        text: "¡Si no lo está puede cancelar la acción!",
        icon: "warning",
        buttons: ["Cancelar", "Sí, Guardar"],
        dangerMode: true,
      })
      .then((aceptado) => {
      if (aceptado) {
          let formData = new FormData($("#formularioAgregaOS")[0]);
  
          //if($("#signatureparent").jSignature("isModified")){
            //let firma = $("#signatureparent").jSignature("getData", "image/svg+xml");
            //let firma = $("#signatureparent").jSignature("getData", "svg");
            //blob = (firma[1], "image/svg+xml");
            //formData.append("firma", blob);

            //CODIFICAR INSERTAR EN BD
            //  for (var i = 0; i < firma.length; i++) {
            //    var file = firma[i];
            //    //console.log("FIRMA: ",file);
            //  }
             //formData.append("firma", file);
  
          //}

          //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}          

           axios({ 
             method  : 'post', 
             url : 'ajax/adminoservicio.ajax.php?op=guardarOS', 
             data : formData, 
             //headers: {'Content-Type': 'image/svg+xml'},
           }) 
        
          .then((response)=>{  
            // console.log(response);
            //console.log(response.status);
            //console.log(response.statusText);
            // console.log(response.headers);
            // console.log(response.config);

            if(response.status==200) {
              if(response.data!=200){
                mensajeaxios=response.data
                tipoerror=3;
                tiempo=4;
              }
              
              $('#DatatableOS').DataTable().ajax.reload(null, false);

              $('#modalAgregarOS').modal('hide')
              mensajenotie(tipoerror, `${mensajeaxios}`, 'top', tiempo);

            }else{
              mensajenotie('Error', 'Hubo problemas al guardar OS!', 'bottom', 2);
            }          
            //console.log(res); 
          }) 
          .catch((err) => {throw err});   //          .catch(function (error) {console.log(error.toJSON())})

      
      }else{
         return false;
      }
       }); 
  
   });

/*======================================================================*/
//QUITA ELEMENTO 
function eliminarProductoOS(indice, restarcantidad){
  console.log("entra a eliminar",indice)
  $("#fila" + indice).remove();
  cantSalidaOS-=restarcantidad;
  renglonesOS--;
  removeItemFromArr(arrayProductosOS, indice)   //funcion esta en funciones.js
  evaluaFilaOS(renglonesOS, cantSalidaOS);
  //evaluarElementos();
}
/*======================================================================*/

/*================ AL SALIR DEL MODAL DE AGREGAR OS, RESETEAR FORMULARIO==================*/
$("#modalAgregarOS").on('hidden.bs.modal', ()=> {
  $("#formularioAgregaOS")[0].reset();
  $("#tbodyOS").empty();
  renglonesOS=cantSalidaOS=0;
  $("#renglones").html('');
  $("#totalsalidaOS").html('');
  arrayProductosOS=[];
  $("#nuevoAlmacenOS, #nvotecnico").prop('disabled',false);
});

/*==================================================================*/
$('#daterange-btnOS').daterangepicker({
  ranges   : {
    'Hoy'       : [moment(), moment()],
    'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    'Últimos 7 Días' : [moment().subtract(6, 'days'), moment()],
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
  endDate  : moment(),
  start: moment(),
  end  : moment()
},
 function (start, end) {
  $('#daterange-btnOS span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
  captRangoFecha=start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY');
  localStorage.setItem("captRangoFecha", captRangoFecha);
 }
)

/*===================================================
REPORTE DE OS DESDE EL DATATABLE
===================================================*/
$("#DatatableOS tbody").on("click", ".btnPrintOS", function(){
  let idos = $(this).attr("idos");
  console.log(idos); 
    if(idos.length > 0){
     //window.open("extensiones/fpdf/reportes/reporte_os.php?codigo="+idos, "_blank");
     window.open("extensiones/plugins/TCPDF/examples/reporte_os.php?codigo="+idos, "_blank");
    }
})

/***********************************************/
//GENERA REPORTE CON TODAS LAS OS SELECCIONADAS
/************************************************/
function printselec(){
  if (idsfacturas.length === 0) {
     //console.log("array Está vacío!")
     return false;
  }else{
    //console.log(idsfacturas);
    idsfacturas=idsfacturas.reverse();
    window.open("extensiones/fpdf/reportes/reporte_material.php?idsfacturas="+idsfacturas, "_blank");
  }
}


/**********************************************
 *  FIRMA DEL CONTRATANTE
 **********************************************/
 $("#signatureparent").jSignature({
   // line color
   color:"black",

   // line width
   lineWidth:3,

  // width/height of signature pad
   width:380,
   height:120,

   // Format bootstrap 4
   cssclass: "bg-light border",
  
 });
/**********************************************
 *  REPETIR FIRMA DEL CONTRATANTE
 **********************************************/
$(".repetirfirma").click(()=>{
  $("#signatureparent").jSignature('reset')
});


/* ====================EDITAR OS ====================*/
$('#DatatableOS tbody').on( 'click', 'a.btnEditarOS', function (event) {
  let elemento = this;
  let id = elemento.getAttribute('data-id');
  //console.log(id,  event.target.attributes[1].value);
  obtenerDatosOS(id)

});
/* ================================================*/
function obtenerDatosOS(id){
  let dataids=dataid=datacant=[];
  let json_datos_mat=[];
  (async () => {   
    await axios.get('ajax/adminoservicio.ajax.php?op=getDataOS', {
      params: {
        idos: id
      }
    })

    .then(res => {
      if(res.status==200 ) {
        let datos_generales=res.data;
        let json_datos_inst = JSON.parse(res.data['datos_instalacion']);
        json_datos_mat = JSON.parse(res.data['datos_material']);
        let i=0;
        dataid=[];
        datacant=[];

         json_datos_mat.forEach((value, index, arreglo) => {
          //console.log(index, value.id_producto);
          dataid.push(value.id_producto);
        });        

        json_datos_mat.forEach((value, index, arreglo) => {
          //console.log(index, value.cantidad);
          datacant.push(value.cantidad);
        });        

        // Object.values(json_datos_mat).forEach((value) => {
        //   console.log(value.id_producto,'**',value.cantidad);
        //   dataid.push(value.id_producto);
        //   datacant.push(value.cantidad);
        //   i++;
        // });
        //convertir array a string
        dataids=dataid.toString();
        
        datosinstalacion(datos_generales, json_datos_inst)

        //var firma=res.data['firma'];
        //$( "#firma1" ).jSignature("getData", "image");
        //$("#signatureparent").jSignature("setData", firma, 'image/svg+xml');
        //$("#signatureparent").jSignature("importData", firma, 'image/svg+xml');
        //let firma = $("#signatureparent").jSignature("getData", "image/svg+xml");
        $("#nuevoAlmacenOS, #nvotecnico").prop('disabled',true);
        //$("#nvotecnico").prop('disabled',true);
      }else{
        console.log(res);
      }
    
    })
    .catch((err) => {
      console.log(err)
    }); 
      console.log('Todos:',json_datos_mat);
      console.log('id:-',dataid)
      console.log('ids:',dataids)
      console.log('cant:-',datacant)

      dataid.sort(comparar);
      datacant.sort(comparar);
      console.log('ordenado con función: ',dataid, '*',datacant)

      await axios.get('ajax/adminoservicio.ajax.php?op=getDataOS', {
        params: {
          dataids: dataids
        }
      })
      .then(res => {
        if(res.status==200 ) {
          console.log(res.data)
          console.log(res.data[0]['descripcion'])

        }
      })
      .catch((err) => {
        console.log(err)
      }); 
      
  })();  //fin del async

}

function datosinstalacion(datos_generales, json_datos_inst){
  let idalmacen=datos_generales['id_almacen']+'-'+datos_generales['nombrealmacen'];
  $("#nuevoAlmacenOS").val(idalmacen);
  $("#nvotecnico").val(datos_generales['id_tecnico']);
  $("#numtelefono").val(datos_generales['telefono']);
  $("#fechainst").val(datos_generales['fecha_instalacion']);
  $("#numeroos").val(datos_generales['ordenservicio']);
  $("#nombrecontrato").val(datos_generales['nombrecontrato']);
  $("#numpisaplex").val(json_datos_inst[0].numpisaplex);
  $("#numtipo").val(json_datos_inst[0].tipo);
  $("#direccionos").val(json_datos_inst[0].direccionos);
  $("#coloniaos").val(json_datos_inst[0].coloniaos);
  $("#distritoos").val(json_datos_inst[0].distritoos);
  $("#terminalos").val(json_datos_inst[0].terminalos);
  $("#puertoos").val(json_datos_inst[0].puertoos);
  $("#nombrefirma").val(json_datos_inst[0].nombrefirma);
  $("#modemretirado").val(json_datos_inst[0].modemretirado);
  $("#modemnumserie").val(json_datos_inst[0].modemnumserie);
  $("#observacionesos").val(datos_generales['observaciones']);  
}

/* *****************AL ABRIR EL MODAL ************************************** */
$('#modalAgregarOS').on('shown.bs.modal', function () {
  let iduser=$('#iduser').val();
  $("#nvotecnico").val(iduser);
})
/*==============================================================================*/

/********************************************************** */
// function interval para recargar el datatable cada 60 seg.
// ver: https://datatables.net/reference/api/ajax.reload()

// API ajax.reload descripción
// ** Recargar datos
//*@param  devolución de llamada La función de devolución de llamada después de volver a dibujar el formulario
//*@param  resetPaging bool type, ya sea para restablecer la información del número de página actual, el valor predeterminado es falso, la información de paginación actual se conservará de forma predeterminada
//function　oTable.ajax.reload( callback, resetPaging )
/*********************************************************** */
setInterval( ()=> {
  tblOrdendeServicios.ajax.reload( null, false ); // user paging is not reset on reload
//console.log('recargo')
}, 180000 );			//recargar cada 60 seg.
/*********************************************************** */




init();