var arrayProductos=new Array();
var renglonesfacturar=cantidadfacturar=cantidadimporte=sumasubtotal=sumatotal=0;
const { ajax } = rxjs.ajax;
const { fromEvent } = rxjs;
$("#modalCrearFactura").draggable({
    handle: ".modal-header"
});

//Función que se ejecuta al inicio
function init(){
  
/*=============================================
  VARIABLE LOCAL STORAGE
  =============================================*/
  //console.log(localStorage.getItem("daterange-btn-factingreso"))
  if (localStorage.getItem("daterange-btn-factingreso") === null) {
    fechaactual();
  }
  
  $("#btnGuardarFactura").hide();

  dt_ListarFacturasIngreso();

}

// ========= LISTAR EN EL DATATABLE REGISTROS DE LA TABLA cajas================
function dt_ListarFacturasIngreso(){
  let rangodeFecha = (localStorage.getItem("daterange-btn-factingreso"));
  $('#daterange-btn-factingreso span').html(rangodeFecha);

   if(rangodeFecha==undefined || rangodeFecha==null){
      //  var FechDev1=moment().format('YYYY-MM-DD');
      //  var FechDev2=moment().format('YYYY-MM-DD');
       var FechDev1=moment().format('YYYY-MM-DD HH:mm:ss');
       var FechDev2=moment().format('YYYY-MM-DD HH:mm:ss');
   }else{
	   let arrayFecha = rangodeFecha.split(" ", 3);
	   let f1=arrayFecha[0].split("-");
	   let f2=arrayFecha[2].split("-");

	   var FechDev1=f1[2].concat("-").concat(f1[1]).concat("-").concat(f1[0].concat(" 00:00:00")); //armar la fecha año-mes-dia
	   var FechDev2=f2[2].concat("-").concat(f2[1]).concat("-").concat(f2[0].concat(" 23:59:59"));
     
   }	   
 
  console.log(FechDev1, FechDev2);

  tblFacturaIngreso=$('#dt-FacturaIngreso').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	  "aServerSide": true,//Paginación y filtrado realizados por el servidor
    "lengthMenu": [ [10, 25, 50,100, -1], [10, 25, 50, 100, "Todos"] ],
    "language": {
      "url": "extensiones/espanol.json",
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
          {"width:":"10px", "className": "dt-center", "targets": [0]},
          {"width:":"10px", "className": "dt-center", "targets": [1]},
          {"width:":"12px", "className": "dt-center", "targets": [2]},
          {"width:":"20px", "className": "dt-center", "targets": [3]},
          {"width:":"20px", "className": "dt-center", "targets": [4]},
          {"width:":"100px", "className": "dt-left", "targets": [5]},
          {"width:":"10px", "className": "dt-center", "targets": [6]},
          {"className": "dt-center", "targets": [7]},
          {"className": "dt-center", "targets": [8]},				//"_all" para todas las columnas
          {"className": "dt-center", "targets": [9]}				//"_all" para todas las columnas
          ],
		"ajax":
				{
          url: 'ajax/facturaingreso.ajax.php?op=listar',
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
    
} 

 $('#daterange-btn-factingreso').daterangepicker({
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
  //start: moment(),
  //end : moment()
  startDate: moment(),
  endDate  : moment()
},
  function (start, end) {
    $('#daterange-btn-factingreso span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
    var Rangofechafactingreso = $("#daterange-btn-factingreso span").html();
    //console.log("rango de fecha:",valorRangoAjusteInv);
    localStorage.setItem("daterange-btn-factingreso", Rangofechafactingreso);
  }
)

/*=============================================
ASIGNA FECHA ACTUAL EN DATERANGEPICKER 
=============================================*/    
function fechaactual(){
    let fechaInicial=moment().add(-30, 'day').format('DD-MM-YYYY');
    let fechaFinal=moment().format('DD-MM-YYYY');
    $("#daterange-btn-factingreso span").html(fechaInicial+' - '+fechaFinal);
    localStorage.setItem("Rangofechafactingreso", fechaInicial+' - '+fechaFinal);
}    

/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/
$('#daterange-btn-factingreso').on('cancel.daterangepicker', function(ev, picker) {
  localStorage.removeItem("Rangofechafactingreso ");
  localStorage.clear();
  $("#daterange-btn-factingreso span").html('<i class="fa fa-calendar"></i> Rango de fecha')
});


/**************************************************************** */
//AL ABRIR EL MODAL TRAER EL ULTIMO NUMERO
$('#modalCrearFactura').on('show.bs.modal', function (event) {
  UltimoNumIdFactura();		//TRAE EL SIGUIENTE NUMERO 
  renglonesfacturar=cantidadfacturar=0;
	$("#renglonentradas").html("");
	$("#totalentradasalmacen").html("");
})

//TRAER EL ULTIMO ID GUARDADO
async function UltimoNumIdFactura(){
  let respId=respFolio=0;
  let response = await fetch("ajax/facturaingreso.ajax.php?op=obtenerUltimoNumero");
  let result = await response.json();
  //console.log(result.id);
  if(result.id===null){
    $("#numidfactura").val(1);
  }else{
    respId=parseInt(result.id)+1;
    $("#numidfactura").val(respId);
  }
  if(result.folio===null){
    $("#nvofolio").val(1);
  }else{
    respFolio=parseInt(result.folio)+1;
    $("#nvofolio").val(respFolio);
  }

}

// DESELECCIONA ALMACEN PARA EVITAR CAMBIARLO
$("#nvoClienteReceptor").change(function(){
  //$('#nvoClienteReceptor option:not(:selected)').attr('disabled',true);
  let id=$("#nvoClienteReceptor").val();
 
  idcliente=parseInt(id); 
     
  //SEPARA EL RFC del ID
  let rfc=$("#nvoClienteReceptor option:selected" ).text();       //obtener el texto del valor seleccionado
  rfccliente= rfc.substr(0, rfc.indexOf('-'));
  rfccliente=rfccliente.trim();
  //console.log(rfccliente)

  $('input[name=rfcreceptor]').val(rfccliente);

  (async () => {   
    await axios.get('ajax/facturaingreso.ajax.php?op=datosreceptor', {
      params: {
        idreceptor: idcliente,
      }
    })
    .then((res)=>{ 
      if(res.status==200) {
        //console.log(res.data)
        if(res.data==false){
          $("#nvoregimenfiscalreceptor").val(0);
          $("#nvoFormaPago").val('');
          $("#nvoemail").val('');
          $("#btnGuardarFacturar").hide();
        }else{
          $("#nvoregimenfiscalreceptor").val(res.data.regimenfiscal);
          $("#nvoFormaPago").val(res.data.formadepago);
          $("#nvoemail").val(res.data.email);
        }
      }          
    }) 

    .catch((err) => {throw err}); 
  
  })();  //fin del async

  $("#agregarProdFactura").removeClass("d-none");
  $("#btnGuardarFactura").show();
});

/*********************CON RxJS************************************ */
const catFormaPago = `config/catalogosat/c_FormaPago.json`;
const users = ajax(catFormaPago);
const searchBtnElement = document.getElementById('nvoFormaPago');  
// Search button observable
const click$ = fromEvent(searchBtnElement,  'click');
click$.subscribe({
  next: (e) => formasdepago()
});

function formasdepago(){
  const subscribe = users.subscribe(
    //res => console.log(res.response),
    res => recorrerjson(res.response),
    err => console.error(err),
    complete => console.log("We have lift off"),
  );
}

function recorrerjson(data){
    let $nuevaformapago = $('#nvoFormaPago');
    $.each(data , function(i, val) {
      $nuevaformapago.append('<option value='+data[i].id + '>' + data[i].id+'-'+data[i].descripcion + '</option>');
    })
}

/************************************************************** */
/* FUNCION PARA MOSTRAR Y SELECCIONAR LA CLAVE DE PROD/SERVICIO*/
/***************************************************************/
$('#cveprodfactura').select2({
  placeholder: 'Selecciona.......',
  ajax: {
    url: 'ajax/facturaingreso.ajax.php?op=getClavesFact',
    async: true,
    dataType: 'json',
    delay: 250,
    type: "POST",
    data: function (params) {
      return {
        searchTerm: params.term
       };  
    },
    processResults: function (data) {
      return {
          results: $.map(data, function (item) {
              return {
                  text: item.idprodservicio+' - '+item.concepto,
                  id: item.idprodservicio,
                  concepto:item.concepto,
                  cantidad:item.cantidad,
                  preciounitario:item.preciounitario
              }
          })
      };
  },    
    cache: true
  },
  minimumInputLength: 1
});
/************************************************************ */

//CONSULTA EXISTENCIA DE PRODUCTO SELECCIONADO
$("#cveprodfactura").change(function(event){
  event.preventDefault();

  let idprodserv=$("#cveprodfactura").val();
  // si viene vacio el select2 que regrese en false   |=124
  if($(this).val()=="" || $(this).val()==null || idprodserv==null){       
      return false;	
  }

  //console.log(idprod, idalmacen, tbl_almacen)
  //$('#servicioSelecionado').html($("#selProdEntAlm option:selected").text());
  (async () => {   
    await axios.get('ajax/facturaingreso.ajax.php?op=getDataClavesFact', {
      params: {
        idprod: idprodserv,
      }
    })
    .then((res)=>{ 
      if(res.status==200) {
        //console.log(res.data)
        if(res.data==false){
          $("#nvacantidad").val(0);
          $("#unidaddemedida").val('');
        }else{
          $("#nvoconcepto").val(res.data[0].concepto);
          $("#nvoobjetoimp").val(res.data[0].objimpuesto);
          $("#unidaddemedida").val(res.data[0].unidadmedida);
          $("#nvonombreudemed").val(res.data[0].nombre);
          $("#nvacantidad").val(res.data[0].cantidad);
          $("#nvovalorunitario").val(res.data[0].preciounitario);
        }
      }          
    }) 

    .catch((err) => {throw err}); 
  
  })();  //fin del async

});

/*============================================================
                AGREGA PRODUCTO SELECCIONADO
============================================================*/
$("#agregaProdFactura").click(function(event){
    event.preventDefault();
    let cantidad=$("#nvacantidad").val();
    let idProducto=$("#cveprodfactura").val();
    let unidaddemedida=$("#unidaddemedida").val();
    let nombreudemed=$("#nvonombreudemed").val();
    let producto=$("#nvoconcepto").val();
    let objimpuesto=$("#nvoobjetoimp").val();
    let valorunitario=$("#nvovalorunitario" ).val();
    idProducto=parseInt(idProducto); 
    cantidad=parseFloat(cantidad);
    preciototal=cantidad*valorunitario;
       
    //Si no selecciona producto retorna o cantidad
      if(isNaN(idProducto) || isNaN(cantidad) || cantidad<1 ){
        return true;
      }  
    
    //console.log("prod:",idProducto, "cant:",cantidad, "medida:",unidaddemedida, "nombre med:",nombreudemed, "concepto:",producto,  'PU:',valorunitario, 'preciototal:',preciototal, "obj_imp:", objimpuesto);
    
    //GUARDA Y AGREGA PRODUCTOS A FACTURAR AL TBODY
    arrayProductos.push(idProducto);
    addProductofactura(idProducto, cantidad, unidaddemedida, nombreudemed, producto, valorunitario, preciototal, objimpuesto);
  //addProductofactura(     0,         1,           2,           3,           4,            5           6             7);

});  

/*==================================================================
ADICIONA PRODUCTOS AL TBODY
==================================================================*/
function addProductofactura(...argsProductos){
  //console.log("manyMoreArgs", argsProductos);
  var contenido=document.querySelector('#tabladedetalles');
  renglonesfacturar++;
  contenido.innerHTML+=`
  <tr class="filas" id="fila${renglonesfacturar}">
    <td><button type="button" class="btn btn-sm text-danger px-0 py-1 m-0" onclick="eliminarProducto(${renglonesfacturar}, ${argsProductos[1]}, ${argsProductos[6]})" title="Quitar concepto"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
    <button type="button" class="btn btn-sm text-info px-0 py-1 m-0" onclick="duplicarconcepto(${argsProductos[0]}, ${argsProductos[1]}, '${argsProductos[4]}', )" title="Duplicar concepto"><i class="fa fa-clone" aria-hidden="true"></i></button>
    <input type="hidden" name="claveunidad[]" value="${argsProductos[2]}-${argsProductos[3]}"
    </td>
    <td class='text-center'>${renglonesfacturar} <input type="hidden" name="objetodeimpuesto[]" value="${argsProductos[7]}" </td>
    <td class='text-center'>${argsProductos[0]} <input type="hidden" name="idproducto[]" value="${argsProductos[0]}" </td>
    <td class='text-left'><input class="form-control form-control-sm" type="text" name="descripcion[]" value="${argsProductos[4]}"</td>  
    <td class='text-center'>${argsProductos[1]} <input type="hidden" name="cantidad[]" value="${argsProductos[1]}"</td>
    <td class='text-right'>${argsProductos[5]} <input type="hidden" name="preciounitario[]" value="${argsProductos[5]}"</td>
    <td class='text-right'>${argsProductos[6]} <input type="hidden" name="importe[]" value="${argsProductos[6]}"</td>
    </tr>
  `;
    cantidadfacturar+=argsProductos[1];
    evaluaFilas(renglonesfacturar, cantidadfacturar, argsProductos[6],0);
  
    //DESPUES DE AÑADIR, SE INICIALIZAN SELECT E INPUT
    $("#nvoconcepto").val('');
    $("#unidaddemedida").val('');
    $("#nvacantidad").val(0);
    $("#nvovalorunitario").val(0);
    $('#cveprodfactura').val(null).trigger('change');
    $("#cveprodfactura").val("0");	
}

function duplicarconcepto(cve, canti, descripcion){
  $("#cveprodfactura").select2().val(cve).trigger('change');
  setTimeout(function() { 
    $("#nvacantidad").val(canti);
    $("#nvoconcepto").val(descripcion);
  }, 1000);
}

//QUITA ELEMENTO 
function eliminarProducto(indice, restarcantidad, restarimporte){
  //console.log("entra a eliminar",indice)
  $("#fila" + indice).remove();
  cantidadfacturar-=restarcantidad;
  cantidadimporte-=restarimporte;
  renglonesfacturar--;
  removeItemFromArr(arrayProductos, indice)
  //console.log(renglonesfacturar, cantidadfacturar, cantidadimporte,1);
  evaluaFilas(renglonesfacturar, cantidadfacturar, cantidadimporte,1);
  evaluarElementos();
}

function evaluaFilas(totalRenglon, cantEntrante, subtotal, flag){
  flag==0?sumasubtotal+=subtotal:sumasubtotal=subtotal;
	$("#subtotal").html(sumasubtotal);
  cantidadimporte=sumasubtotal;   //para llevar el total en la variable global
  impuesto=(sumasubtotal*16)/100;
  sumatotal=sumasubtotal+impuesto;

  let sumasubtotalTot=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(sumasubtotal);
  $("#subtotal").html(sumasubtotalTot);
  let impuestoTot=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(impuesto);
	$("#impuesto").html(impuestoTot);
  let sumatotalTot=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(sumatotal);
	$("#total").html(sumatotalTot);

	$("#renglonentradas").html(totalRenglon);
	$("#totalentradasalmacen").html(cantEntrante);
	if(totalRenglon>0){
	  $("#btnGuardarFactura").show();
	  $("#count-row").show();
  }else{
		$("#btnGuardarFactura").hide();
	}
}

//SI NO HAY ELEMENTOS count SE INICIALIZA
function evaluarElementos(){
  if (!renglonesfacturar>0){
    renglonesfacturar=0;
    $("#btnGuardarFactura, #btnEditEntradasAlmacen").hide();
    $("#count-row, #Editcount-row").hide();
  }
}


/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR DATOS DE ENTRADA
/*======================================================================*/
$("body").on("submit", "#formularioFactura", function( event ) {	
  event.preventDefault();
  event.stopPropagation();
  let formData = new FormData($("#formularioFactura")[0]);   
  for (var pair of formData.entries()){
    console.table(pair[0]+ ', ' + pair[1]);
      if($("#nvoregimenfiscalreceptor").val().length < 1){
        return true;
      } 
  } 
  
  swal({
    title: "¿Está seguro de guardar Entrada?",
    text: "¡Si no lo está pulse Cancelar",
    icon: "warning",
    buttons: ["Cancelar", "Sí, Guardar"],
    dangerMode: false,
  })
  .then((aceptado) => {
    if (aceptado) {
          axios({ 
            method  : 'post', 
            url : 'ajax/facturaingreso.ajax.php?op=guardar', 
            data : formData, 
          }) 
          .then((res)=>{ 
            if(res.status===200) {
              console.log(res.data['status'])
              //console.log(res.data.status, res.data.data, res.data.msg)

              $('#dt-FacturaIngreso').DataTable().ajax.reload(null, false);
              $('#modalCrearFactura').modal('hide')

            }else{
              console.log(res.data, res.status)
            }          

          }) 
          .catch((err) => {throw err}); 

    }else{
      return false;
    }
  }); 

});  

function completa() {
  $("#alert1").addClass("d-none")
}

/*======================================================================*/
function mostrarRespuesta(mensaje, ok){

  //$("#respuesta, #response").removeClass('alert-success').removeClass('alert-danger').removeClass('d-none').html(mensaje);
  if(ok){
      $("#respuesta, #response").removeClass('d-none').html(mensaje);
      $("#respuesta, #response").addClass('alert-success');
      setTimeout(() => {
        $("#respuesta, #response").addClass('d-none').removeClass('alert-success');
      }, 3000);
  }else{
      $("#respuesta, #response").removeClass('d-none').html(mensaje);
      $("#respuesta, #response").addClass('alert-danger').addClass('d-block');
      setTimeout(() => {
        $("#respuesta, #response").addClass('d-none').removeClass('alert-danger');
      }, 3000);

  }
}
/*======================================================================*/
/*=============================================

=============================================*/
function getIdFactura(elem){
  let dataid = elem.dataset.idfactura;
  let datafecha = elem.dataset.fechaelabora;
  //console.log('id:',dataid, datafecha);
  (async () => {
    await axios.get('ajax/facturaingreso.ajax.php?op=TimbrarFact', {
      params: {
        dataid: dataid,
        datafecha: datafecha
      }
    })

    .then((res)=>{ 
      if(res.status==200) {
        console.log(res.data)


        if(res.data==false){
          console.log(res.data)
        }
      }          
    }) 

    .catch((err) => {throw err}); 
  
  })();  //fin del async

}
/************************************************************/

/*===================================================
ENVIA REPORTE DE ENTRADA AL ALMACEN DESDE EL DATATABLE
===================================================*/
$("#dt-FacturaIngreso tbody").on("click", "button.btnPrintPdf", function(){
  let idPrintPdf = $(this).attr("data-id");
  console.log(idPrintPdf); 
    if(idPrintPdf.length > 0){
     window.open("extensiones/fpdf/reportes/facturatimbrada.php?codigo="+idPrintPdf, "_blank");
    }
})
/*===================================================

/*================ AL SALIR DEL MODAL RESETEAR FORMULARIO ==================*/
$("#modalCrearFactura").on('hidden.bs.modal', ()=> {
  //$("#agregaProdFactura").addClass("d-none");
  $('#formularioFactura')[0].reset();                //resetea el formulario
  $("#nvacantidad").val(0);                   //inicializa campo existencia
  $("#nvopreciounitario").val(0);                     //inicializa campo salida
  $("#nvoconcepto").val("");                     //inicializa campo salida
  $("#tabladedetalles").empty();                   //vacia tbody
  $('#cveprodfactura').val(null).trigger('change');      //inicializa el select de productos
  arrayProductos["length"]=0;                      //inicializa array
});

/*=============================================================*/

init();

