//$( "#modalAgregarOS" ).draggable();
$("#modalAgregarObservaOS").draggable({
  handle: ".modal-header"
});

var item;
var data;
var modalEvento='';
var arrayProductosOS=new Array();
var renglonesOS=cantSalidaOS=0;
var numserie=alfanumerico='';
var captRangoFecha;
var idsfacturas=new Array();
var FechDev1;
var FechDev2;

var sig=$('#signature, #signatureContainer').signature({ 
  background: '#ffffff', // Colour of the background 
  color: 'blue', // Colour of the signature 
  thickness: 2, // Thickness of the lines 
  guideline: true, // Add a guide line or not? 
  guidelineColor: 'black', // Guide line colour 
  guidelineOffset: 25, // Guide line offset from the bottom 
  guidelineIndent: 10, // Guide line indent from the edges 
  notAvailable: 'Su browser doesn\'t support signing', // Error message when no canvas 
  scale: 1, // A scaling factor for rendering the signature (only applies to redraws). 
  syncField: null, // Selector for synchronised text field 
  syncFormat: 'JSON', // The output respresentation: 'JSON' (default), 'SVG', 'PNG', 'JPEG' 
  svgStyles: false, // True to use style attribute in SVG 
  change: null // Callback when signature changed 
});

const { fromEvent } = rxjs;

//Rx.Observable.fromEvent(document.getElementById("DatatableOS"), 'click').subscribe(() => console.log('Haz hecho click!'));
// $(function(){
//   $.extend($.kbw.signature.options, {guideline: true}); 
// });

function init(){

  /*=============================================
  VARIABLE LOCAL STORAGE
  =============================================*/
  if(localStorage.getItem("captRangoFecha") != null){
    //$("#daterange-btnOS span").html(localStorage.getItem("captRangoFecha"));
    iniciarangodefecha()
  }else{
    iniciarangodefecha()
  }

    listarOServicios();   //LISTAR OS EN EL DATATABLE

    $("#btnGuardarOS").hide();
    $(".mostrarcantidades").hide();

}

/*================================================================================== */
$('#numeroos').on('blur', ()=> {
  $("#numeroos").css({"background-color": "white", "color":"black"});
  let numos=$('#numeroos').val();
  if(numos<1 || length.numos<1){
    $('#numeroos').focus();
    return
  }
  (async () => { 
    resp=await getUser(numos)
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
    FechDev1=moment().format('YYYY-MM-DD');
    FechDev2=moment().format('YYYY-MM-DD');
  }else{
    let arrayFecha = rangodeFecha.split(" - ", 2);
    FechDev1=moment(arrayFecha[0],'DD-MM-YYYY').format('YYYY-MM-DD');
    FechDev2=moment(arrayFecha[1],'DD-MM-YYYY').format('YYYY-MM-DD');
  }	   

  tblOrdendeServicios=$('#DatatableOS').dataTable(
    {
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      "lengthMenu": [ [15, 25, 50, 100, -1], [15, 25, 50, 100, "Todos"] ],
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
      "sFirst":    "<<",
      "sLast":     ">>",
      "sNext":     ">",
      "sPrevious": "<"}
          },
      "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      },
      scrollY:        "460px",
      scrollX:        true,
      scrollCollapse: true,
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
            text: 'Imprimir selección',
            className: 'btn btn-dark btn-sm',
            action: function ( e, dt, node, config ) {
              printselec();
            }
          },
          {
            text: 'Chek O.S.',
            className: 'btn btn-sm btn-outline-info',
            action: function ( e, dt, node, config ) {
              checkOS();
            }
          }        
          ],
          initComplete: function () {
            var btns = $('.dt-button');
            btns.removeClass('dt-button');
            btns.addClass('btn btn-success btn-sm');
          },  
          "columnDefs": [
            {"className": "dt-center", "targets": [1,6,7,8,9,10,11]},
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
      "iDisplayLength": 15,//Paginación
      "order": [[ 0, 'desc' ]] //Ordenar (columna,orden)
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
  //Hacer busqueda por la columna de No. de Fact. segun el dato del INPUT
  $('#myID').on( 'keyup change clear', function () {
    if(tblOrdendeServicios.column(0)){
      tblOrdendeServicios.column(0).search(this.value).draw();
    };
  } );

  if(title=="Técnico"){
    $(this).html('<input type="text float-left" id="searchTec" style="width:20rem; height:20px;"  placeholder="'+title+'"/>');
    $('#searchTec').on( 'keyup change clear', function () {
      if(tblOrdendeServicios.column(2)){
        tblOrdendeServicios.column(2).search(this.value).draw();
      };
    } );
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
      if(tblOrdendeServicios.column(10)){
         tblOrdendeServicios.column(10).search(this.value).draw();
      };
    } );
    
  }
});

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
      title: "¿Cambiar estatus de Facturado de una OS?",
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
    }else{
      //console.log('no debe entrar',conserie)
    }

    let descripcion=$("#selecProductoOS").text();   //extrae la descripcion del prod
    descripcion=descripcion.trim();

    //TRUE SI EL PRODUCTO YA ESTA CAPTURADO
    let encontrado=arrayProductosOS.includes(id_producto)
  
    
    if(!encontrado){
      arrayProductosOS.push(id_producto);
      addProductosSalida(id_producto, descripcion, cantidad, conserie, numserie, alfanumerico);
    }else{
      inicializa(1);
    }
  
  }); 

/*==================================================================
ADICIONA PRODUCTOS AL TBODY
==================================================================*/
function addProductosSalida(...argsProductos){
  //console.log("manyMoreArgs", argsProductos);
  let contenido=document.querySelector('#tbodyOS');
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
  if($('#signature').signature('isEmpty')){
    var firma='Sin Firma';
  }else{
    var firma=$('#signature').signature('toJSON');
  } 
  
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
          formData.append("firma", firma);
          //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}

           axios({ 
             method  : 'post', 
             url : 'ajax/adminoservicio.ajax.php?op=guardarOS', 
             data : formData, 
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
                tiempo=2.5;
              }
              $('#DatatableOS').DataTable().ajax.reload(null, false);
              $('#modalAgregarOS').modal('hide')
              mensajenotie(tipoerror, `${mensajeaxios}`, 'top', tiempo);

            }else{
              $('#modalAgregarOS').modal('hide')
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
  $("#fila" + indice).remove();
  cantSalidaOS-=restarcantidad;
  renglonesOS--;
  removeItemFromArr(arrayProductosOS, indice)   //funcion esta en funciones.js
  evaluaFilaOS(renglonesOS, cantSalidaOS);
}
/*======================================================================*/

/*===================================================
REPORTE DE OS DESDE EL DATATABLE
===================================================*/
$("#DatatableOS tbody").on("click", ".btnPrintOS", function(){
  let idos = $(this).attr("idos");
    if(idos.length > 0){
     window.open("extensiones/plugins/TCPDF/examples/reporte_os.php?codigo="+idos, "_blank");
    }
})

/***********************************************/
//GENERA REPORTE CON TODAS LAS OS SELECCIONADAS
/************************************************/
function printselec(){
  if (idsfacturas.length === 0) {
     return false;
  }else{
    idsfacturas=idsfacturas.reverse();
    window.open("extensiones/fpdf/reportes/reporte_material.php?idsfacturas="+idsfacturas, "_blank");
  }
}

/* ====================EDITAR OS ====================*/
$('#DatatableOS tbody').on( 'click', '.btnEditarOS', function (event) {
  let elemento = this;
  let id = elemento.getAttribute('data-id');
  //console.log(id);
  obtenerDatosOS(id)
});
/* ================================================*/
function obtenerDatosOS(id){
  (async () => {   
    await axios.get('ajax/adminoservicio.ajax.php?op=getDataOS', {
      params: {
        idos: id
      }
    })

    .then(res => {
      if(res.status==200 ) {

        html=`OS ya esta Facturado, no
          es posible modificarlo.
        Consulte a Soporte Técnico.`;
          if (res.data.factura != null){
            swal({
              title: 'Please atention.',
              text: html,
              icon: "error",
              button: "Entendido",
              timer: 5000
            })  //fin .then
            return;
          }
          //Llenar formulario con los datos para modificarlo
          //console.log(res.data);
          fillform(res.data);

      }else{
        console.log(res);
      }
    
    })
    .catch((err) => {
      console.log(err)
    }); 
      
  })();  //fin del async

}

function fillform(datosOS){
  let json_datos_inst = JSON.parse(datosOS.datos_instalacion);
  $("#editid").val(datosOS.id);
  $("input[name=editAlmacenOS]").val(datosOS.id_almacen);
  $("select[name='edittecnico']").val(datosOS.id_tecnico);
  $("select[name='editAlmacenOS']").val(datosOS.id_almacen);
  $("input[name=editnumtelefono]").val(datosOS.telefono);
  $("input[name=editfechainst]").val(datosOS.fecha_instalacion);
  $("input[name=editnumeroos]").val(datosOS.ordenservicio);
  $("input[name=editnombrecontrato]").val(datosOS.nombrecontrato);
  $("input[name=editobservaos]").val(datosOS.observaciones);

  // Sets data
  if(datosOS.firma==='Sin Firma' || datosOS.firma===null){
    $('#signatureContainer').signature();
    $('#signatureContainer').signature('disable');
  }else{
    if(datosOS.firma.length>15){
      $('#signatureContainer').signature('draw', datosOS.firma);
      $('#signatureContainer').signature('disable');
    }else{
      $('#signatureContainer').signature();
      $('#signatureContainer').signature('disable');
    }
  }

  $("#editAlmacenOS, #edittecnico").prop('disabled',true);
  datosinstalacion(json_datos_inst);
  //let json_datos_mat = JSON.parse(res.data['datos_material']);  
  modalEvento=new bootstrap.Modal(document.getElementById('modalEditarOS'),{ keyboard:false });
  modalEvento.show();

}
  
function datosinstalacion(json_datos_inst){
  $("#editnumpisaplex").val(json_datos_inst[0].numpisaplex);
  $("#editnumtipo").val(json_datos_inst[0].numtipo);
  $("#editdireccionos").val(json_datos_inst[0].direccionos);
  $("#editcoloniaos").val(json_datos_inst[0].coloniaos);
  $("#editdistritoos").val(json_datos_inst[0].distritoos);
  $("#editterminalos").val(json_datos_inst[0].terminalos);
  $("#editpuertoos").val(json_datos_inst[0].puertoos);
  $("#editnombrefirma").val(json_datos_inst[0].nombrefirma);
  $("#editnumeroSerie").val(json_datos_inst[0].numeroserie);
  $("#editalfanumerico").val(json_datos_inst[0].alfanumerico);
}

/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR DATOS DE ORDEN DE SERVICIO
/*======================================================================*/
$("body").on("submit", "#formularioEditarOS", function( event ) {	
  event.preventDefault();
  event.stopPropagation();	
  let mensajeaxios='Registro Actualizado';
  let tipomsg=1;
  let tiempo=2;
  
  if($('#signatureContainer').signature('isEmpty')){
    var firma='Sin Firma';
  }else{
    var firma=$('#signatureContainer').signature('toJSON');
  } 

    swal({
        title: "¿Está seguro de Actualizar OS?",
        text: "¡Si no lo está puede cancelar la acción!",
        icon: "warning",
        buttons: ["Cancelar", "Sí, Actualizar"],
        dangerMode: true,
      })

      .then((aceptado) => {
      if (aceptado) {
          let formData = new FormData($("#formularioEditarOS")[0]);
          // console.log(firma);
          formData.append("firma", firma);
          
          //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}
          //TAMBIEN FUNCIONA CON AJAX
          // $.ajax({
          //   url: "ajax/adminoservicio.ajax.php?op=ActualizarOS",
          //   method: 'POST',
          //   data: formData,
          //   processData: false,
          //   contentType: false,
          //   dataType: 'json',
          //   success: function(data) {
          //       alert(data.msg);
          //       $('#DatatableOS').DataTable().ajax.reload(null, false);
          //       modalEvento.hide();
          //   }
          // });

          axios({ 
            method  : 'post', 
            url : 'ajax/adminoservicio.ajax.php?op=ActualizarOS', 
            data : formData, 
          }) 

          .then((response)=>{  
            //console.log(response.data); 
            if(response.data.status==200) {
                mensajeaxios=response.data.msg
                tipomsg=3;
                tiempo=3;
              
              $('#DatatableOS').DataTable().ajax.reload(null, false);
              modalEvento.hide();
              mensajenotie(tipomsg, `${mensajeaxios}`, 'top', tiempo);
            }else{
              modalEvento.hide();
              mensajenotie('Error', 'Hubo problemas al guardar OS!', 'bottom', 3);
            }          
            //console.log(response); 
          }) 
          .catch((err) => {throw err});   //          .catch(function (error) {console.log(error.toJSON())})

      }else{
         return false;
      }
       }); 
  
   });

/*======================================================================*/
function checkOS(){
  $('#modalCheckOS').modal('show')
}
/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR DATOS DE ORDEN DE SERVICIO
/*======================================================================*/
$("body").on("submit", "#formularioCheckOS", function(event) {	
  event.preventDefault();
  event.stopPropagation();	
  xfilexls=$('#filexls').val() 
  if(xfilexls.length == 0){;
   return
  }else{
    //alert(xfilexls)
    let formData = new FormData($("#formularioCheckOS")[0]);
    for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}

    axios({ 
      method  : 'post', 
      url : 'controladores/procesar.controlador.php', 
      data : formData, 
    }) 
    .then((res)=>{ 
      if(res.status=="200") {
        console.log(res);
      }
      console.log(res.data);
    }) 
    .catch((err) => {
      alert("Algo salio mal!!!.")
      throw err;
    }); 
    

  }
  
});
/*================ AL SALIR DEL MODAL DE AGREGAR OS, RESETEAR FORMULARIO==================*/
$("#modalCheckOS").on('hidden.bs.modal', ()=> {
  $("#formularioCheckOS")[0].reset();
  //$("#tbodyOS").empty();
});
/*==============================================================================*/
/*======================================================================*/

/* *****************AL ABRIR EL MODAL DE AGREGAR************************************** */
$('#modalAgregarOS').on('shown.bs.modal', function () {
  let iduser=$('#iduser').val();
  let idalmacen=$('#id_almacen').val();
  console.log(iduser, idalmacen)
  $("#nvotecnico").val(iduser);
  $("#nuevoAlmacenOS").val(idalmacen);
})
/*================ AL SALIR DEL MODAL DE AGREGAR OS, RESETEAR FORMULARIO==================*/
$("#modalAgregarOS").on('hidden.bs.modal', ()=> {
  $("#formularioAgregaOS")[0].reset();
  $("#tbodyOS").empty();
  renglonesOS=cantSalidaOS=0;
  $("#renglones").html('');
  $("#totalsalidaOS").html('');
  arrayProductosOS=[];
  $("#nuevoAlmacenOS, #nvotecnico").prop('disabled',false);
  $('#signature').signature('enable');
  $('#signature').signature('clear');
  $('.deshabilitar').text('Deshabilitar');
});
/*==============================================================================*/
/*================ AL SALIR DEL MODAL EDITAR OS, RESETEAR FORMULARIO ==================*/
$("#modalEditarOS").on('hidden.bs.modal', ()=> {
  $("#formularioEditarOS")[0].reset();
  $("#editAlmacenOS, #edittecnico").prop('disabled',false);
  $('#signatureContainer').signature('enable');
  $('#signatureContainer').signature('clear');
  $('.habilitar').text('Habilitar');
  });

/**********************************************
*  REPETIR FIRMA DEL CONTRATANTE
**********************************************/
$(".repetirfirma").click(()=>{
  sig.signature('clear');
});
/********************************************************** */
$(".habilitar").on('click',function() {
  habilitar();
});
/********************************************************** */
function habilitar(){
  let enable = $('.habilitar').text() === 'Habilitar'; 
  if(enable){
    $('.habilitar').text('Deshabilitar');
    $('#signatureContainer').signature('enable');
  }else{
    $('.habilitar').text('Habilitar');
    $('#signatureContainer').signature('disable');
  }
  return
}
/********************************************************** */
$('.deshabilitar').click(function() { 
  let enable = $(this).text() === 'Deshabilitar'; 
  $('#signature').signature(enable ? 'disable' : 'enable'); 
  $(this).text(enable ? 'Habilitar' : 'Deshabilitar'); 
});
/********************************************************** */

/************************************************************* */
// MODULO PARA LA CAPTURA DE FECHA DE ENVIO PARA LA ODC
/************************************************************* */
$('#DatatableOS tbody').on( 'dblclick', 'td', function () {
  if(tblOrdendeServicios.cell( this ).index().columnVisible==7){
    let numerodeid=tblOrdendeServicios.row( this ).data()[0];
    let numerodeos=tblOrdendeServicios.row( this ).data()[3];
    let numerodetel=tblOrdendeServicios.row( this ).data()[4];
    $('#numerodeid').html("");
    $('#numerodeid').html('<i class="fa fa-calendar"></i>'+' Capturar fecha de ID: #'+numerodeid);
    $('#modalAgregarObservaOS').modal('show')
    $( "input[name='idregos']").val(numerodeid);
    $( "input[name='iddos']").val(numerodeos);
    $( "input[name='idtel']").val(numerodetel);
    traerdatosid(numerodeid);
  };
});
/*************************************************************** */
function traerdatosid(id){
  tblOs=$('#detalleObserva').dataTable({
          "language": {
            "emptyTable": "Ningún dato disponible en la tabla"
          },
          "paging": false,
          "info": false,
          "searching": false,
          "autoWidth": false,
          "columns": [
            { "width": "09%" },
            { "width": "18%" },
            { "width": "70%" }
          ],            
            "columnDefs": [
              {"className": "dt-center", 
              "targets": [0]
              },
              {"className": "dt-center", 
              "targets": [1]
              },
              {"className": "dt-left", 
              "targets": [2]
              }
            ],
          "ajax":{
              url: 'ajax/adminoservicio.ajax.php?op=traeridos',
              data: {"id": id},     
              type : "GET",
              dataType : "json",						
              error: function(e){
                console.log(e.responseText);
              }
          },
      "bDestroy": true,
      "iDisplayLength": 15,//Paginación
      "order": [[ 0, 'desc' ]] //Ordenar (columna,orden)
    }).DataTable();    
}
/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR FECHA DE ENTREGA PARA PAGOS 
/*======================================================================*/
$("body").on("submit", "#form-AgregaObservaOS", function( event ) {	
  event.preventDefault();
  event.stopPropagation();	
    let formData = new FormData($("#form-AgregaObservaOS")[0]);

    axios({ 
      method  : 'post', 
      url : 'ajax/adminoservicio.ajax.php?op=guardarAgregaOS', 
      data : formData, 
    }) 
    .then((res)=>{ 
      if(res.status=="200") {
        $("input[name=fechaagrega]").val('');
        $("textarea[name=nvaobservaos]").val('');
        $('#DatatableOS').DataTable().ajax.reload(null, false)
        $('#detalleObserva').DataTable().ajax.reload(null, false);
      }

    }) 
    .catch((err) => {
      alert("Registro No Guardado.")
      throw err;
    }); 

});
/**************************************************************/
// TERMINA EL MODULO PARA EL ENVIO DE LA OS PARA ELAB. DE ODC
/**************************************************************/

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