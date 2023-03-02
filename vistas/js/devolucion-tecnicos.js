
$("#modalAgregarDevolucion").draggable({
		  handle: ".modal-header"
});


function init(){

/*=============================================
VARIABLE LOCAL STORAGE
=============================================*/
if(localStorage.getItem("capturarRango") != null){

	$("#daterange-btn1 span").html(localStorage.getItem("capturarRango"));

}else{

	$("#daterange-btn1 span").html('<i class="fa fa-calendar"></i> Rango de fecha')

}

  listarDevTec();

  var cantSolicitada=0;
  var udeMedida="";
  var idProducto=0;
  var lDuplicado=false;
  $("#btnGuardarDev").hide();
  $("#calculoDev").hide();

}

/*  =====  QUITAR AL ESTAR EN PRODUCCION  ======*/
//$('#selecProductoDev').val('1').trigger('change.select2');
//$("#nuevaDevolucionAlmacen").prop("selectedIndex", 1); 
//$("#TecnicoDev").prop("selectedIndex", 1); 
//$("#cantSalidaDev").val(1); 
/* =============================================*/

/*  =====  SE DESHABOLITA LA OPCION DESPUES DE SELECCIONAR EL ALMACEN  ======*/
$("#nuevaDevolucionAlmacen").change(function(){
    $("#agregarProdDev").removeClass("d-none" )
    $('#nuevaDevolucionAlmacen option:not(:selected)').attr('disabled',true);
});


$("#selecProductoDev").change(function(event){
let mostrarprod;

event.preventDefault();
  var almacen=$( "#nuevaDevolucionAlmacen option:selected" ).text();
  var idProducto=$("#selecProductoDev").val();
  var idtecnico=$("#TecnicoDev").val();

  if(idProducto==''){
    return
  }
  
    $.get('ajax/devolucion-tecnicos.ajax.php', 
		{op:'mostrarprod', almacen:almacen, idProducto:idProducto, idtecnico:idtecnico}, 
		function(response,status){
		console.log(status,response, almacen, idProducto, idtecnico)
		var contenido = JSON.parse(response);
	
		udeMedida=contenido["medida"];
	
		if(response=="false"){
            //console.log("No existe Art");
            $("#cantSalidaDev").val(0);
            $("#selecProductoDev").val("");
			      $('#mensajerror').text('Producto no existe en este Almacen!!');
            $("#mensajerror").removeClass("d-none");
            setTimeout(function(){$("#mensajerror").addClass("d-none")}, 2500);
        }
		
    });
	
});

//=========  AGREGAR PRODUCTO SELECCIONADO  ==============     
$("#agregarProductosDev").click(function(event){
event.preventDefault();

var idProducto=$("#selecProductoDev").val();

//CHECA QUE NO SE VUELVA A DUPLICAR PRODUCTO
let duplicado=buscaProdDuplicado(idProducto);

if(duplicado){
  $("#cantSalidaDev").val(0);
	$('#mensajerror').text('Producto ya capturado. Revise!!');
  $("#mensajerror").removeClass("d-none");
	setTimeout(function(){$("#mensajerror").addClass("d-none")}, 3500);
	return true;
};

var producto=$("#selecProductoDev option:selected").text();       //obtener el texto del valor seleccionado
var almacen=$( "#nuevaDevolucionAlmacen option:selected").text();
var cantcap=$("#cantSalidaDev").val();

let codigoalma=almacen.substr(0, almacen.indexOf('-'));
//console.log("#Almacen:",codigoalma)
    
    idProducto=parseInt(idProducto);
    cantidad=parseFloat(cantcap);
    //console.log("Almacen:",almacen, cantidad);

    //Si no selecciona producto retorna
    if(isNaN(idProducto) || isNaN(cantidad) ){
        return true;
    }  
	
    if(cantidad==0){
        return true;
    }else if(cantidad<0){
        return true;    
    }  

    //SEPARA EL CODIGO DEL PROD. SELECCIONADO    
    var codigointerno= producto.substr(0, producto.indexOf('-'));
    codigointerno.trim();

    //SEPARA LA DESCRIPCION DEL PROD. SELECCIONADO        
    var descripcion= producto.substr(producto.lastIndexOf("-") + 1);
	
    descripcion.trim();
    
    let udemedida=udeMedida;

	var fila='<tr class="filas" id="fila'+cont+'">'+
        
    	'<td><button type="button" class="botonQuitar" onclick="eliminarDetalleDev('+cont+')" title="Quitar Concepto">X</button></td>'+
        
        '<td><input type="hidden" value="'+cont+'">'+idProducto+'</td>'+
		
    	'<td><input type="hidden" name="codigointerno[]" id="codigointerno[]" value="'+codigointerno+'">'+codigointerno+'</td>'+
        
		'<td><input type="hidden" class="prodactivo" style="width:15rem" name="idProducto[]" value="'+idProducto+'">'+descripcion+'</td>'+

        '<td class="text-center"><input type="hidden" name="udemed[]" id="udemed[]" idFila=f'+cont+' style="width:3rem" readonly >'+udemedida+'</td>'+
        
    	'<td class="text-center"><input type="hidden" name="cantidad[]" id="cantidad[]" idFila=f'+cont+' class="cuantos" value="'+cantidad+'" style="width:3rem" required readonly dir="rtl">'+cantidad+'</td>'+
		
    '</tr>';
    	cont++;
    	detalles=detalles+1;    
		  $('#detalleDevolucion').prepend(fila);
        evaluarDevolucion(); 
        calculaTotalDev()
        //console.log("cont:",cont, "detalles:",detalles);
    
   //DESPUES DE AÑADIR, SE INICIALIZAN SELECT E INPUT
    $('#selecProductoDev').val(null).trigger('change');
    $("#cantSalidaDev").val(0);
})

//FUNCION QUE SUMA CANTIDADES DE DEV.
function calculaTotalDev() {
	let totalDevolucion = 0;
	let totalRenglon = 0;
	$(".cuantos").each(
		function(index, value) {
            totalDevolucion  = totalDevolucion  + eval($(this).val());
            if(parseFloat(totalDevolucion)>0){
               totalRenglon=(parseInt(index)+1);
            }
            //console.log("eval: ",eval($(this).val()), "index:",index);
		}
	);
	//$("#total").val(totalDevolucion ).number(true,2);
	$("#renglon").html(totalRenglon);
	$("#total").html(totalDevolucion );
	if(totalDevolucion>0){
	  $("#btnGuardarDev").show();
	  $("#calculoDev").show();
    }else{
		//$("#btnGuardar").hide();
	}
}

/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR DATOS DE ENTRADA
$("body").on("submit", "#formularioDevolucion", function( event ) {	
event.preventDefault();
event.stopPropagation();	
//console.log("enviar form");

	swal({
      title: "¿Está seguro de Guardar Devolución?",
      text: "¡Si no lo está puede cancelar la acción!",
      icon: "warning",
      buttons: ["Cancelar", "Sí, Guardar"],
      dangerMode: true,
    })
    .then((aceptado) => {
      if (aceptado) {
        let formData = new FormData($("#formularioDevolucion")[0]);
        //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}
        //CODIFICAR INSERTAR EN BD

		fetch('ajax/devolucion-tecnicos.ajax.php?op=guardarDev', {
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
        
				$("#formularioDevolucion")[0].reset();
        $("#tbodyid").empty();
        $("#renglon").empty();
        $("#total").empty();
				$('#modalAgregarDevolucion, #modalEditarOsvilla').modal('hide')
				$('#DatatableDevTec').DataTable().ajax.reload(null, false);
        

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

/* =FUNCION QUE VERIFICA QUE NO SE DUPLIQUE PROD === */
function buscaProdDuplicado(idProdNvo) {
lDuplicado=false;
	$(".prodactivo").each(
		function(index, value) {
			let idProd=parseFloat($(this).val());
			console.log(idProd);
			console.log(idProdNvo);
			if(idProd==idProdNvo){
				console.log("son iguales")
				lDuplicado=true;
			}	
		}
	);
return lDuplicado	
}
/* ==== FIN DE LA FUNCION =========== */
//QUITA ELEMENTO 
 function eliminarDetalleDev(indice){
  	$("#fila" + indice).remove();
  	calculaTotalDev();
  	detalles=detalles-1;
  	evaluarDevolucion();
  }

//SI NO HAY ELEMENTOS cont SE INICIALIZA
function evaluarDevolucion(){
  if (!detalles>0){
      cont=0;
	  $("#btnGuardarDev").hide();
	  $("#calculoDev").hide();
    }
}


/*===================================================================================*/
$('#datepicker13').datepicker({
    autoclose:true,
    todayHighlight:true,
    calendarWeeks:true,
    clearBtn:true,
    language:"es"
});   
/*====================================================================================*/

$(document).ready(function() {	
$(".selProdDev").select2({
    placeholder: "Busque y seleccione un producto",
    allowClear: true
});
});

/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/
$('#daterange-btn1').on('cancel.daterangepicker', function(ev, picker) {
  //do something, like clearing an input
  localStorage.removeItem("capturarRango");
  localStorage.clear();
  $("#daterange-btn1 span").html('<i class="fa fa-calendar"></i> Rango de fecha')
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
        $("#daterange-btn1 span").html(fechaInicial+' - '+fechaFinal);
        //console.log(fechaInicial+' - '+fechaFinal);
    	localStorage.setItem("capturarRango", fechaInicial+' - '+fechaFinal);

    	//window.location = "index.php?ruta=reportes&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;

	}

})
 
//Date range as a button
    //$('#daterange-btn1').daterangepicker({ startDate: '04-01-2019', endDate: '04-03-2019' },
    $('#daterange-btn1').daterangepicker(
        
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
        $('#daterange-btn1 span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
          
        var fechaInicial=start.format('YYYY-M-D');
        var fechaFinal=end.format('YYYY-M-D');
        //console.log(fechaInicial, fechaFinal);
        var capturarRango = $("#daterange-btn1 span").html();
        console.log("rango de fecha:",capturarRango);
        
          localStorage.setItem("capturarRango", capturarRango);
          
          //window.location = "index.php?ruta=reportes&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;
      }
    )
	
// ========= LISTAR EN EL DATATABLE REGISTROS DE LA TABLA cajas================
function listarDevTec(){
  let rangodeFecha = $("#daterange-btn1 span").html();
  //console.log("Rango de Fecha:",rangodeFecha);
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

  tablaDevolucionTec=$('#DatatableDevTec').dataTable(
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
          url: 'ajax/devolucion-tecnicos.ajax.php?op=listar',
          data: {"FechDev1": FechDev1,  "FechDev2": FechDev2},     
					type : "POST",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);
					}
				},
		"bDestroy": true,
		"iDisplayLength": 8,//Paginación
	    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
	}).DataTable();    
    
} 

$('#modalAgregarDevolucion').on('show.bs.modal', function (e) {
	$('#selecProductoDev').val(null).trigger('change');
})

init();