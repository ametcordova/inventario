var udeMedida;
var renglonesEntradas=cantEntrante=0;
var arrayProductos=new Array();

$("#modalAgregarEntradasAlmacen").draggable({
    handle: ".modal-header"
});

//Función que se ejecuta al inicio
function init(){
/*=============================================
  VARIABLE LOCAL STORAGE
  =============================================*/

  fechaactual();

  $("#btnGuardarEntradasAlmacen").hide();

  dt_ListarEntradasAlmacen();

}

// ========= LISTAR EN EL DATATABLE REGISTROS DE LA TABLA cajas================
function dt_ListarEntradasAlmacen(){
  let rangodeFecha = $("#daterange-btn-EntAlmacen span").html();
  console.log("Rango de Fecha Ent:",rangodeFecha);
  if(rangodeFecha==undefined || rangodeFecha==null){
      var FechDev1=moment().format('YYYY-MM-DD');
      var FechDev2=moment().format('YYYY-MM-DD');
  }else{
	  let arrayFecha = rangodeFecha.split(" ", 3);
	  let f1=arrayFecha[0].split("-");
	  let f2=arrayFecha[2].split("-");

	  var FechDev1=f1[2].concat("-").concat(f1[1]).concat("-").concat(f1[0]); //armar la fecha año-mes-dia
	  var FechDev2=f2[2].concat("-").concat(f2[1]).concat("-").concat(f2[0]);
  }	   
 
  console.log(FechDev1, FechDev2);

  tablaSalidasAlmacen=$('#dt-entradasalmacen').dataTable(
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
		"ajax":
				{
          url: 'ajax/entradasalmacen.ajax.php?op=listar',
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


 $('#daterange-btn-EntAlmacen').daterangepicker({
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
          "Setiembre",
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
  $('#daterange-btn-EntAlmacen span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
    
  var RangoFechaEntradasAlmacen = $("#daterange-btn-EntAlmacen span").html();
  //console.log("rango de fecha:",valorRangoAjusteInv);
  
   localStorage.setItem("daterange-btn-EntAlmacen", RangoFechaEntradasAlmacen);
    
}
)

/*=============================================
ASIGNA FECHA ACTUAL EN DATERANGEPICKER 
=============================================*/    
function fechaactual(){
    let fechaInicial=moment().format('DD-MM-YYYY');
    let fechaFinal=moment().format('DD-MM-YYYY');
    $("#daterange-btn-EntAlmacen span").html(fechaInicial+' - '+fechaFinal);
    localStorage.setItem("RangoFechaEntradasAlmacen", fechaInicial+' - '+fechaFinal);
}    

/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/
$('#daterange-btn-EntAlmacen').on('cancel.daterangepicker', function(ev, picker) {
  localStorage.removeItem("RangoFechaEntradasAlmacen ");
  localStorage.clear();
  $("#daterange-btn-EntAlmacen span").html('<i class="fa fa-calendar"></i> Rango de fecha')
});

/*================ AL SALIR DEL MODAL RESETEAR FORMULARIO ==================*/
$("#modalAgregarEntradasAlmacen").on('hidden.bs.modal', ()=> {
  $("#agregarProdEntrada").addClass("d-none");
  $('#form_entradasalmacen')[0].reset();                //resetea el formulario
  $("#cantExistenciaAlmacen").val(0);                   //inicializa campo existencia
  $("#cantEntradaAlmacen").val("");                     //inicializa campo salida
  $("#tbodyentradasalmacen").empty();                   //vacia tbody
  $('#selProdEntAlm').val(null).trigger('change');      //inicializa el select de productos
  arrayProductos["length"]=0;                           //inicializa array
});

//AL ABRIR EL MODAL TRAER EL ULTIMO NUMERO
$('#modalAgregarEntradasAlmacen').on('show.bs.modal', function (event) {
  UltimoNumEntradaAlmacen();		//TRAE EL SIGUIENTE NUMERO 
  renglonesEntradas=cantEntrante=0;
	$("#renglonentradas").html("");
	$("#totalentradasalmacen").html("");
})

//TRAER EL ULTIMO ID GUARDADO
async function UltimoNumEntradaAlmacen(){
  let resulta=0;
  let response = await fetch("ajax/entradasalmacen.ajax.php?op=obtenerUltimoNumero");
  let result = await response.json();
  console.log(result.id);
  if(result.id===null){
    $("#numEntradaAlmacen").val(1);
  }else{
    resulta=parseInt(result.id)+1;
    $("#numEntradaAlmacen").val(resulta);
  }
}

// DESELECCIONA ALMACEN PARA EVITAR CAMBIARLO
$("#idAlmacenEntrada").change(function(){
  $('#idAlmacenEntrada option:not(:selected)').attr('disabled',true);
  $('span#msjdeerrorentrada').html("");
  $("#agregarProdEntrada").removeClass("d-none");
  $("#btnGuardarEntradasAlmacen").show();
});

$('#selProdEntAlm').select2({
  placeholder: 'Selecciona un producto',
  ajax: {
    url: 'ajax/entradasalmacen.ajax.php?op=ajaxProductos',
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
                  text: item.codigointerno+' - '+item.descripcion,
                  id: item.id,
                  descripcion:item.descripcion
              }
          })
      };
  },    
    cache: true
  },
  minimumInputLength: 1
});

//$(document).on('change', '#selProdEntAlm', function(event) {
$("#selProdEntAlm").change(function(event){
  event.preventDefault();

  let idalmacen=$("#idAlmacenEntrada").val();
  let tbl_almacen=$( "#idAlmacenEntrada option:selected" ).text();
  let idprod=$("#selProdEntAlm").val();
  // si viene vacio el select2 que regrese en false   |=124
  if($(this).val()=="" || $(this).val()==null || idalmacen==null){       
      return false;	
  }

  console.log(idprod, idalmacen, tbl_almacen)
  //$('#servicioSelecionado').html($("#selProdEntAlm option:selected").text());

  axios.get('ajax/entradasalmacen.ajax.php?op=consultaExistenciaProd', {
    params: {
      idprod: idprod,
      almacen: tbl_almacen
    }
  })
  .then((res)=>{ 
    if(res.status==200) {
      console.log(res.data)
      if(res.data==false){
        $("#cantExistenciaAlmacen").val(0);
      }else{
        $("#cantExistenciaAlmacen").val(res.data.cant);
        udeMedida=res.data.medida;
      }
    }          

  }) 
  .catch((err) => {throw err}); 
});

//VERIFICA CANT SALIENTE NO SEA MAYOR QUE LA EXISTENCIA
// $("#cantEntradaAlmacen").change(function(event){
//   event.preventDefault();
//       $("#mensajerrorentrada").addClass("d-none");
//       let cantexist=$("#cantExistenciaAlmacen").val();
//       let cantSolicitada=$("#cantEntradaAlmacen").val();
//       if(parseFloat(cantSolicitada)>parseFloat(cantexist)){
//           $("#cantEntradaAlmacen").val(0);
//           $('#mensajerrorentrada').text('Cantidad Solicitada es Mayor a la Existencia!!');
//           $("#mensajerrorentrada").removeClass("d-none");
//       }else{
//           $("#mensajerrorentrada").addClass("d-none");
//       }
//   });

/*============================================================
                AGREGA PRODUCTO SELECCIONADO
============================================================*/
  $("#agregaEntradaProd").click(function(event){
    event.preventDefault();
    let cantEntrada=$("#cantEntradaAlmacen").val();
    let idProducto=$("#selProdEntAlm").val();
    let producto=$("#selProdEntAlm option:selected" ).text();       //obtener el texto del valor seleccionado
    idProducto=parseInt(idProducto); 
    cantEntrada=parseFloat(cantEntrada);
       
    //Si no selecciona producto retorna o cantidad
      if(isNaN(idProducto) || isNaN(cantEntrada) || cantEntrada<1 ){
        return true;
      }  
    
    //SEPARA EL CODIGO DEL PROD. SELECCIONADO
    let codigointerno= producto.substr(0, producto.indexOf('-'));
    codigointerno.trim();
      
    //SEPARA LA DESCRIPCION DEL PROD. SELECCIONADO        
    let descripcion= producto.substr(producto.lastIndexOf("-") + 1);
    descripcion.trim();
         
    //let udemedida=udeMedida;
      
    console.log("prod:",idProducto, "cant:",cantEntrada, "medida:",udeMedida, "codInt:",codigointerno, "descrip:",descripcion);
    
    let encontrado=arrayProductos.includes(idProducto)
      console.log("encontrado:", encontrado)
      if(!encontrado){
        arrayProductos.push(idProducto);
        addProductoEntrada(idProducto, cantEntrada, udeMedida, codigointerno, descripcion);
      }else{
        mensajedeerror();
      }
    
  });  

/*==================================================================
ADICIONA PRODUCTOS AL TBODY
==================================================================*/
function addProductoEntrada(...argsProductos){
  //console.log("manyMoreArgs", argsProductos);
  var contenido=document.querySelector('#tbodyentradasalmacen');
  
  contenido.innerHTML+=`
  <tr class="filas" id="fila${argsProductos[0]}">
    <td><button type="button" class="botonQuitar" onclick="eliminarProducto(${argsProductos[0]}, ${argsProductos[1]} )" title="Quitar concepto">X</button></td>
    <td class='text-center'>${argsProductos[0]} <input type="hidden" name="idproducto[]" value="${argsProductos[0]}"</td>
    <td class='text-center'>${argsProductos[3]}</td>
    <td class='text-left'>${argsProductos[4]}</td>
    <td class='text-center'>${argsProductos[2]}</td>
    <td class='text-center'>${argsProductos[1]} <input type="hidden" name="cantidad[]" value="${argsProductos[1]}"</td>
    </tr>
  `;
    renglonesEntradas++;
    cantEntrante+=argsProductos[1];
    evaluaFilas(renglonesEntradas, cantEntrante);
  
    //DESPUES DE AÑADIR, SE INICIALIZAN SELECT E INPUT
    $('#selProdEntAlm').val(null).trigger('change');	
    $("#cantExistenciaAlmacen").val(0);
    $("#cantEntradaAlmacen").val(0);
  
}

//QUITA ELEMENTO 
function eliminarProducto(indice, restarcantidad){
  console.log("entra a eliminar",indice)
  $("#fila" + indice).remove();
  cantEntrante-=restarcantidad;
  renglonesEntradas--;
  removeItemFromArr(arrayProductos, indice)
  evaluaFilas(renglonesEntradas, cantEntrante);
  evaluarElementos();
}

function evaluaFilas(totalRenglon, cantEntrante){
	$("#renglonentradas").html(totalRenglon);
	$("#totalentradasalmacen").html(cantEntrante);
	if(totalRenglon>0){
	  $("#btnGuardarEntradasAlmacen").show();
	  $("#rowEntradaAlma").show();
    }else{
		//$("#btnGuardar").hide();
	}
}

//SI NO HAY ELEMENTOS count SE INICIALIZA
function evaluarElementos(){
  if (!renglonesEntradas>0){
    renglonesEntradas=0;
    $("#btnGuardarEntradasAlmacen, #btnEditEntradasAlmacen").hide();
    $("#rowEntradaAlma, #EditrowEntradaAlma").hide();
  }
}

//FUNCION PARA MENSAJE DE ERROR
function mensajedeerror(){
  $("#cantEntradaAlmacen, #EditcantentradaAlmacen").val(0);
  $('#Editmensajerrorsalida, #mensajerrorentrada').text('Producto ya capturado. Revise!!');
  $("#mensajerrorentrada, #Editmensajerrorentrada").removeClass("d-none");
  setTimeout(function(){$("#mensajerrorentrada, #Editmensajerrorentrada").addClass("d-none")}, 2500);
  return true;
}

/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR DATOS DE ENTRADA
/*======================================================================*/
$("body").on("submit", "#form_entradasalmacen", function( event ) {	
  event.preventDefault();
  event.stopPropagation();
  let formData = new FormData($("#form_entradasalmacen")[0]);   
  for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);} 
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
          url : 'ajax/entradasalmacen.ajax.php?op=guardarEntradasAlmacen', 
          data : formData, 
        }) 
        .then((res)=>{ 
          if(res.status==200) {
            console.log(res.data)

            $('#modalAgregarEntradasAlmacen').modal('hide')
            $('#dt-entradasalmacen').DataTable().ajax.reload(null, false);
  
          }            
          console.log(res); 
        }) 
        .catch((err) => {throw err}); 

  }else{
    return false;
}
}); 

});  

/*===================================================
ENVIA REPORTE DE SALIDA DE ALMACEN DESDE EL DATATABLE
===================================================*/
$("#dt-entradasalmacen tbody").on("click", "button.btnPrintEntradaAlmacen", function(){
	let idPrintEntrada = $(this).attr("idPrintEntrada");
   console.log(idPrintEntrada);
    if(idPrintEntrada.length > 0){
     window.open("extensiones/tcpdf/pdf/reporte_entrada.php?codigo="+idPrintEntrada, "_blank");
    }
})




init();