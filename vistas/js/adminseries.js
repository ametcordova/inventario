
$("#modalAgregarSeries").draggable({
		  handle: ".modal-header"
});


function init(){

/*=============================================
VARIABLE LOCAL STORAGE
=============================================*/
if(localStorage.getItem("captRangoFecha") != null){

	$("#daterange-btnSeries span").html(localStorage.getItem("captRangoFecha"));

}else{

	$("#daterange-btnSeries span").html('<i class="fa fa-calendar"></i> Rango de fecha')

}


  listarSeries();

  var cantSolicitada=0;
  var idProducto=0;
    $("#btnGuardarSerie").hide();
  $("#calculoSerie").hide();

}


/*  =====  QUITAR AL ESTAR EN PRODUCCION  ======*/

$('#selecProductoDev').val('1').trigger('change.select2');
$("#nuevaDevolucionAlmacen").prop("selectedIndex", 1); 
$("#TecnicoDev").prop("selectedIndex", 1); 
$("#cantSalidaDev").val(1); 
/* =============================================*/

/*  =====  SE DESHABILITA LA OPCION DESPUES DE SELECCIONAR EL ALMACEN  ======*/
$("#nuevoAlmacenSerie").change(function(){
    $("#agregarProdDev").removeClass("d-none" )
    $('#nuevoAlmacenSerie option:not(:selected)').attr('disabled',true);
});


$("#selecProductoSerie").change(function(event){
let mostrarprod;

event.preventDefault();
  let almacen=$( "#nuevoAlmacenSerie option:selected" ).text();
  let idProducto=$("#selecProductoSerie").val();
  
    $.get('ajax/devolucion-tecnicos.ajax.php', 
		{op:'mostrarprod', almacen:almacen, idProducto:idProducto}, 
		function(response,status){
		//console.log(status,response)
		//var contenido = JSON.parse(response);
	
		if(response=="false"){
            //console.log("No existe Art");
            $("#selecProductoDev").val("");
			      $('#mensajerror').text('Producto no existe en este Almacen!!');
            $("#mensajerror").removeClass("d-none");
            setTimeout(function(){$("#mensajerror").addClass("d-none")}, 2500);
        }
		
    });
	
});

//=========  AGREGAR PRODUCTO SELECCIONADO  ==============     
$("#agregarProductosSeries").click(function(event){
event.preventDefault();

var idProducto=$("#selecProductoSerie").val();

var producto=$("#selecProductoSerie option:selected" ).text();       //obtener el texto del valor seleccionado
var almacen=$( "#nuevoAlmacenSerie option:selected" ).text();

let nNumeroSerie=$("#numeroSeries").val();
let nAlfanumerico=$("#alfanumerico").val().toUpperCase();

var codigoalma= almacen.substr(0, almacen.indexOf('-'));
//console.log("# Alma",codigoalma)
    
    idProducto=parseInt(idProducto);

    //Si no selecciona producto retorna
    if(isNaN(idProducto) || nNumeroSerie=="" ){
        return true;
    }  
	
    //SEPARA EL CODIGO DEL PROD. SELECCIONADO    
    var codigointerno= producto.substr(0, producto.indexOf('-'));
    codigointerno.trim();

    //SEPARA LA DESCRIPCION DEL PROD. SELECCIONADO        
    //var descripcion= producto.substr(producto.lastIndexOf("-") + 1);
    //descripcion.trim();
    

	var fila='<tr class="filas" id="fila'+cont+'">'+
        
    	'<td><button type="button" class="botonQuitar" onclick="eliminarDetalleDev('+cont+')" title="Quitar Concepto">X</button></td>'+
        
      '<td><input type="hidden" class="prodseries" name="idProducto[]" value="'+idProducto+'">'+idProducto+'</td>'+
		
    	'<td><input type="hidden" name="codigointerno[]" id="codigointerno[]" value="'+codigointerno+'">'+codigointerno+'</td>'+
        
		  '<td><input type="hidden" style="width:15rem" name="serienumerico[]" value="'+nNumeroSerie+'">'+nNumeroSerie+'</td>'+

      '<td class="text-center"><input type="hidden" name="seriealfanumerico[]" value="'+nAlfanumerico+'" idFila=f'+cont+' style="width:3rem" readonly >'+nAlfanumerico+'</td>'+
        
    '</tr>';
    	cont++;
    	detalles=detalles+1;    
      $('#detalleDeSeries').prepend(fila);
      inicializacampos();  
      //evaluarDevolucion(); 
      calculaTotalItems()
      getFocus()
})

function inicializacampos(){
  $("#numeroSeries").val("");
  $("#alfanumerico").val("");
}

//FUNCION QUE SUMA CANTIDADES
function calculaTotalItems() {
	let totalDevolucion = 0;
	let totalRenglon = 0;
	$(".prodseries").each(
		function(index, value) {
            totalDevolucion  = totalDevolucion  + eval($(this).val());
            if(parseFloat(totalDevolucion)>0){
               totalRenglon=(parseInt(index)+1);
            }
            //console.log("eval: ",eval($(this).val()), "index:",index);
		}
	);
	$("#renglon").html(totalRenglon);
	if(totalDevolucion>0){
	  $("#btnGuardarSerie").show();
	  $("#calculoSerie").show();
	}

}

function getFocus() {
  document.getElementById("numeroSeries").focus();
}
/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR DATOS DE ENTRADA
$("body").on("submit", "#formularioSeries", function( event ) {	
event.preventDefault();
event.stopPropagation();	
//console.log("enviar form");

	swal({
      title: "¿Está seguro de Guardar Series?",
      text: "¡Si no lo está puede cancelar la acción!",
      icon: "warning",
      buttons: ["Cancelar", "Sí, Guardar"],
      dangerMode: true,
    })
    .then((aceptado) => {
      if (aceptado) {
        let formData = new FormData($("#formularioSeries")[0]);
        for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}
        //CODIFICAR INSERTAR EN BD

		fetch('ajax/adminseries.ajax.php?op=guardarSeries', {
		  method: 'POST',
		  body: formData
		})
		  .then(ajaxRespPositiva)
		  .catch(muestroError); 		
		
      }else{
		  return false;
	  }
    }); 

 });
 
 
/*=================== funciones de respuesta =================================*/
    function ajaxRespPositiva(response) {
        
      console.log('response.ok: ', response.ok);
        
/*        swal({
          title: "Realizado!!",
          text: "Se ha guardado correctamente ",
          icon: "success",
          button: "Cerrar"
          })  //fin swal
          .then(function(result){
            if (result) {
            }
        })  //fin .then
*/
				$("#formularioSeries")[0].reset();
        $("#tbodyid").empty();
        $("#renglon").empty();
        $('#selecProductoSerie').val(null).trigger('change'); 
				$('#modalAgregarSeries, #modalEditarOsvilla').modal('hide')
				$('#DatatableSeries').DataTable().ajax.reload(null, false);
        

      if(response.ok) {
        response.text().then(showResult);
      } else {
        showError('status code: ' + response.status);
      }
    }

    function showResult(txt) {
      console.log('muestro respuesta: ', txt);
    }

    function muestroError(err) { 
      console.log('muestra error', err);
      swal({
          title: "Error!!",
          text: err,
              icon: "error",
              button: "Cerrar"
          })  //fin swal
        //window.location = "inicio";
    }
/*=============FIN AGREGAR================================ */ 

//QUITA ELEMENTO 
 function eliminarDetalleDev(indice){
  	$("#fila" + indice).remove();
  	calculaTotalItems();
  	detalles=detalles-1;
  	evaluarDevolucion();
  }

//SI NO HAY ELEMENTOS cont SE INICIALIZA
function evaluarDevolucion(){
  if (!detalles>0){
      cont=0;
	  $("#btnGuardarSerie").hide();
	  $("#calculoSerie").hide();
    }
}

number_format = function (number, decimals, dec_point, thousands_sep) {
        number = number.toFixed(decimals);

        var nstr = number.toString();
        nstr += '';
        x = nstr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? dec_point + x[1] : '';
        var rgx = /(\d+)(\d{3})/;

        while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

        return x1 + x2;
    }
	
$('#datepicker3').datepicker({
    autoclose:true,
    todayHighlight:true,
    calendarWeeks:true,
    clearBtn:true,
    language:"es"
});   
    

$(document).ready(function() {	
$(".selProdDev").select2({
    placeholder: "Busque y seleccione un producto",
    allowClear: true
});
});

/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/
$('#daterange-btnSeries').on('cancel.daterangepicker', function(ev, picker) {
  //do something, like clearing an input
  localStorage.removeItem("captRangoFecha");
  localStorage.clear();
  $("#daterange-btnSeries span").html('<i class="fa fa-calendar"></i> Rango de fecha')
});

/*=============================================
CAPTURAR HOY DIA=01-10 MES=01-10
=============================================*/
$(".daterangepicker.opensright .ranges li").on("click", function(){

	//var textoHoy = $(this).attr("data-range-key");
	var textoHoy = $(this).html();
    

	if(textoHoy == "Hoy"){
     
    var d = new Date();
    console.log("Fecha de Hoy:",d);
    var dia = d.getDate();
    var mes = d.getMonth()+1;
    var año = d.getFullYear();

    if(mes < 10){

      //var fechaInicial = año+"-0"+mes+"-"+dia;
      //var fechaInicial = año+"-0"+mes+"-"+dia;
      var fechaInicial = dia+"-0"+mes+"-"+año;
      var fechaFinal = dia+"-0"+mes+"-"+año;

    }else if(dia < 10){

      var fechaInicial = "0"+dia+"-"+mes+"-"+año;
      var fechaFinal =   "0"+dia+"-"+mes+"-"+año;

    }else if(mes < 10 && dia < 10){

      var fechaInicial = "0"+dia+"-0"+mes+"-"+año;
      var fechaFinal =   "0"+dia+"-0"+mes+"-"+año;

    }else{

      var fechaInicial = dia+"-"+mes+"-"+año;
      var fechaFinal =   dia+"-"+mes+"-"+año;

    } 
        $("#daterange-btnSeries span").html(fechaInicial+' - '+fechaFinal);
        console.log(fechaInicial+' - '+fechaFinal);
    	localStorage.setItem("captRangoFecha", fechaInicial+' - '+fechaFinal);

    	//window.location = "index.php?ruta=reportes&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;

	}

})
 
//Date range as a button
    //$('#daterange-btnSeries').daterangepicker({ startDate: '04-01-2019', endDate: '04-03-2019' },
    $('#daterange-btnSeries').daterangepicker(
        
      {
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
        startDate: moment(),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btnSeries span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
          
        var fechaInicial=start.format('YYYY-M-D');
        var fechaFinal=end.format('YYYY-M-D');
        //console.log(fechaInicial, fechaFinal);
        var captRangoFecha = $("#daterange-btnSeries span").html();
        console.log("rango de fecha:",captRangoFecha);
        
          localStorage.setItem("captRangoFecha", captRangoFecha);
          
          //window.location = "index.php?ruta=reportes&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;
      }
    )
	
// ========= LISTAR EN EL DATATABLE REGISTROS DE LA TABLA cajas================
function listarSeries(){
  let rangodeFecha = $("#daterange-btnSeries span").html();
 // console.log("Rango de Fecha:",rangodeFecha);
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
 
  //console.log(FechDev1, FechDev2);

  tabladeSeries=$('#DatatableSeries').dataTable(
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
                title: "Nunosco",
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
          url: 'ajax/adminseries.ajax.php?op=listarseries',
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


init();