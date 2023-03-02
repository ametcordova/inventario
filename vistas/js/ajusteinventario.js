var cantSolicitada=0;
var cantexist=0;
var udeMedida="";
var idProducto=0;
var lDuplicado=false;
var duplicado=false;
var count=1;  
var detalles=0;
$("#modalAgregarAjuste").draggable({
    handle: ".modal-header"
});


//Función que se ejecuta al inicio
function init(){

  /*=============================================
  VARIABLE LOCAL STORAGE
  =============================================*/
  if(localStorage.getItem("valorRangoAjusteInv") != null){
    $("#daterange-btn-Ajuste span").html(localStorage.getItem("valorRangoAjusteInv"));
  }else{
    //$("#daterange-btn-Ajuste span").html('<i class="fa fa-calendar"></i> Rango de fecha!')
    fechadehoy();
  }
  
  $("#btnGuardarAjusteInv").hide();
  $("#rowAjusteInv").hide();

  dataAjusteInv();
}

//AL ABRIR EL MODAL TRAER EL ULTIMO NUMERO
  $('#modalAgregarAjuste').on('show.bs.modal', function (event) {
	//console.log("entra al abrir");
	$("#nvoAlmAjuste").val(0);
  $("#form_agregaAjuste")[0].reset();
	$("#tbodyajuste").empty();
	LastId();		//TRAE EL SIGUIENTE NUMERO DE AJUSTE DE INV.
	$("#rowAjusteInv").hide();
})

//TRAER EL ULTIMO ID GUARDADO
async function LastId(){
  var resulta=0;
  let response = await fetch("ajax/ajusteinventario.ajax.php?op=obtenerLastId");
  //console.log(response);
  let result = await response.json();
  //console.log(result.id);
  if(result.id===null){
    $("#nvoNumAjuste").val(1);
  }else{
    resulta=parseInt(result.id)+1;
    $("#nvoNumAjuste").val(resulta);
  }
}

// DESELECCIONA ALMACEN PARA EVITAR CAMBIARLO
$("#nvoAlmAjuste").change(function(){
  $('#nvoAlmAjuste option:not(:selected)').attr('disabled',true);
  $('span#msjNoSeleccionado').html("");
});


// === AGREGA CANT EXISTENTE Y VALIDA LA CANT SALIENTE ===
$("#selecProductoAjuste").change(function(event){
	event.preventDefault();

	// si viene vacio el select2 que regrese en false
	if($(this).val()==""){
		return false;	
  }

  let idalmacen=$("#nvoAlmAjuste").val();
  let motivo=$("#nvoMotivoAjuste").val();
  idalmacen=parseInt(idalmacen);

  if(idalmacen==0 || motivo.length < 1){
    $('span#msjNoSeleccionado').html(`<label style='color:red'>${"¡Seleccione Tipo, Almacen y capture Motivo!!"} </label>`);
    $("#nvoAlmAjuste").focus(function(){
      $("span#msjNoSeleccionado").css("display", "inline").fadeOut(4000);
    });
    $('#selecProductoAjuste').val(null).trigger('change');
    $("#nvoAlmAjuste").focus();
    return false;	
   }else if(isNaN(idalmacen)) {
    $('span#msjNoSeleccionado').html(`<label style='color:red'>${"¡Seleccione Almacen!!"} </label>`);
    $("#nvoAlmAjuste").focus(function(){
      $("span#msjNoSeleccionado").css("display", "inline").fadeOut(4000);
    });
    $('#selecProductoAjuste').val(null).trigger('change');
    $("#nvoAlmAjuste").focus();
    return false;	
   }
  
  var almacen=$( "#nvoAlmAjuste option:selected" ).text();
  var idProducto=$("#selecProductoAjuste").val();
  idProducto=parseInt(idProducto);
  //console.log("entra1", idalmacen, almacen, idProducto);

 //HACER UN FETCH AL CAT DE PROD
 let data = new FormData();
 data.append('almacen', almacen);
 data.append('idProducto', idProducto);

 fetch('ajax/ajusteinventario.ajax.php?op=queryExist', {
    method: 'POST',
    body: data
  })
.then(response => response.json())
.then(data => {
  //console.log(data)
  if(data===false){
    //console.log("No existe Art");
    $("#cantExist").val(0);
    $("#cantAjuste").val("");
    $('#msjerrorajuste').text('Prod. no existe en este Almacen!!');
    $("#msjerrorajuste").removeClass("d-none");
    setTimeout(function(){$("#msjerrorajuste").addClass("d-none")}, 2500);
  }else{
    $("#cantExist").val(data.cant);
    udeMedida=data.medida;
  }
})
.catch(error => console.error(error))

});

//=========  AGREGAR PRODUCTO SELECCIONADO  ==============     
$("#agregarProdAjuste").click(function(event){
  event.preventDefault();
  cantexist=$("#cantExist").val();
  let idProducto=$("#selecProductoAjuste").val();
  
  //CHECA QUE NO SE VUELVA A DUPLICAR PRODUCTO
  idProducto=parseInt(idProducto); 
  let duplicado=findProdDuplicado(idProducto);
  
  if(duplicado){
     $("#cantAjuste").val(0);
     $('#msjerrorajuste').text('Producto ya capturado. Revise!!');
     $("#msjerrorajuste").removeClass("d-none");
     setTimeout(function(){$("#msjerrorajuste").addClass("d-none")}, 2500);
      return true;
  };

    let producto=$("#selecProductoAjuste option:selected" ).text();       //obtener el texto del valor seleccionado
    let cantcap=$("#cantAjuste").val();
  
      cantidad=parseFloat(cantcap);
        
      //Si no selecciona producto retorna
      if(isNaN(idProducto) || isNaN(cantidad) ){
          return true;
      }  
    
      //si la cantidad es cero o menor a 0, retorna
      if(cantidad==0){
          return true;
      }else if(cantidad<0){
          return true;    
      }  
  
      //SEPARA EL CODIGO DEL PROD. SELECCIONADO
      let codigointerno= producto.substr(0, producto.indexOf('-'));
      codigointerno.trim();
  
      //SEPARA LA DESCRIPCION DEL PROD. SELECCIONADO        
      let descripcion= producto.substr(producto.lastIndexOf("-") + 1);
      descripcion.trim();
      
      let udemedida=udeMedida;
  
      console.log("prod:",idProducto, "cant:",cantidad, "medida:",udemedida, "codInt:",codigointerno, "descrip:",descripcion);

      addproducts(idProducto, cantidad, udeMedida, codigointerno, descripcion);

});

//Function para agregar productos a la tabla. con parámetros rest
function addproducts(...argsProductos){
 // console.log("manyMoreArgs", argsProductos);

  var fila='<tr class="filas" id="fila'+count+'">'+
        
  '<td><button type="button" class="botonQuitar" onclick="eliminarLinea('+count+')" title="Quitar Concepto">X</button></td>'+
    
  '<td class="text-center"><input type="hidden" value="'+count+'">'+argsProductos[0]+'</td>'+

  '<td><input type="hidden" name="codigointerno[]" id="codigointerno[]" value="'+argsProductos[3]+'">'+argsProductos[3]+'</td>'+
    
  '<td><input type="hidden" class="prodForAjuste" style="width:15rem" name="idProducto[]" value="'+argsProductos[0]+'">'+argsProductos[4]+'</td>'+

  '<td class="text-center"><input type="hidden" name="udemed[]" id="udemed[]" style="width:3rem" readonly >'+argsProductos[2]+'</td>'+
    
  '<td class="text-center"><input type="hidden" name="cantidad[]" id="cantidad[]" idFila=f'+count+' class="cuantos" value="'+argsProductos[1]+'" style="width:3rem" required readonly dir="rtl">'+argsProductos[1]+'</td>'+

  '</tr>';

  count++;
  detalles=detalles+1;    
  $('#detalleAjusteInv').prepend(fila);
  evaluarAjusteInv(); 
  calculaTotalAjusteInv()

  //DESPUES DE AÑADIR, SE INICIALIZAN SELECT E INPUT
  $('#selecProductoAjuste').val(null).trigger('change');	
  $("#cantAjuste").val("");
  $("#cantExist").val(0);
  $('#selecProductoAjuste').select2('open');

}


//VERIFICA CANT NO SEA MAYOR QUE LA EXISTENCIA
$("#cantAjuste").change(function(event){
  //console.log(cantexist);
  event.preventDefault();
  $("#msjerrorajuste").addClass("d-none");
  let tipoajuste=$("#nvoTipoAjuste").val();
  let tipodeajuste=tipoajuste.charAt(tipoajuste.length-1); //get the last character 
  console.log("tipo de ajuste:",tipodeajuste);
  if(tipodeajuste=="S"){
    cantexist=$("#cantExist").val();
    cantSolicitada=$("#cantAjuste").val();
    if(parseFloat(cantSolicitada)>parseFloat(cantexist)){
      $('#msjerrorajuste').text('Cantidad Solicitada es Mayor a la Existencia!!');
        $("#msjerrorajuste").removeClass("d-none");
        $("#cantAjuste").val(0);
    }else{
        $("#msjerrorajuste").addClass("d-none");
    }
  }
});
  
/* =FUNCION QUE VERIFICA QUE NO SE DUPLIQUE PROD === */
function findProdDuplicado(idProdNvo) {
  lDuplicado=false;
    $(".prodForAjuste").each(
      function(index, value) {
        let idProd=parseFloat($(this).val());
        //console.log(idProd, idProdNvo);
        if(idProd==idProdNvo){
          //console.log("son iguales")
          lDuplicado=true;
        }	
      }
    );
    return lDuplicado	
  }
  /* ==== FIN DE LA FUNCION =========== */

 //QUITA ELEMENTO 
 function eliminarLinea(indice){
  $("#fila" + indice).remove();
  calculaTotalAjusteInv();
  detalles=detalles-1;
  evaluarAjusteInv();
}

//SI NO HAY ELEMENTOS count SE INICIALIZA
function evaluarAjusteInv(){
if (!detalles>0){
    count=0;
  $("#btnGuardarAjusteInv").hide();
  $("#rowAjusteInv").hide();
  }
}

//FUNCION QUE SUMA CANTIDADES DE DEV.
function calculaTotalAjusteInv() {
	let totalAjuste = 0;
	let totalRenglon = 0;
	$(".cuantos").each(
		function(index, value) {
            totalAjuste  = totalAjuste  + eval($(this).val());
            if(parseFloat(totalAjuste)>0){
               totalRenglon=(parseInt(index)+1);
            }
		}
	);
	$("#renglon").html(totalRenglon);
	$("#total").html(totalAjuste );
	if(totalAjuste>0){
	  $("#btnGuardarAjusteInv").show();
	  $("#rowAjusteInv").show();
    }else{
		//$("#btnGuardar").hide();
	}
}

/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR DATOS DE ENTRADA
$("body").on("submit", "#form_agregaAjuste", function( event ) {	
  event.preventDefault();
  event.stopPropagation();	
  //let formData = new FormData($("#form_agregaAjuste")[0]);
  //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}


  swal({
    title: "¿Está seguro de guardar ajuste de Inventario?",
    text: "¡Si no lo está puede cancelar la acción!",
    icon: "warning",
    buttons: ["Cancelar", "Sí, Guardar"],
    dangerMode: false,
  })
    .then((aceptado) => {
        if (aceptado) {
          let formData = new FormData($("#form_agregaAjuste")[0]);
          //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}
 
      fetch('ajax/ajusteinventario.ajax.php?op=guardarAjusteInv', {
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

          $('#selecProductoAjuste').val(null).trigger('change');	
          $("#form_agregaAjuste")[0].reset();
          $("#tbodyajuste").empty();
          $("#renglon").empty();
          $("#total").empty();
          $('#modalAgregarAjuste').modal('hide')
          $('#datatableAI').DataTable().ajax.reload(null, false);

        if(response.ok) {
          response.text().then(showResult);

          swal({
            title: 'Los cambios han sido guardados!',
            text: "Registro Guardado correctamente",
            icon: "success",
            button: "Enterado",
            timer: 3000
          }).then((result) => {
            if (result) {
                window.location.reload();
            }else{
                window.location.reload();
            }
          })

  
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
          })  //fin swal
          //window.location = "inicio";
      }
  /*=============FIN AGREGAR================================ */ 

//AL SALIR DEL SELECT2 FOCUS AL CANT DE SALIDA
$('.selectAjuste').on('select2:close', function (e){
	$("#cantAjuste").css({"background-color": "orange", "color":"black"});
    $('#cantAjuste').focus();
});

$('#cantAjuste').on('blur', function() {
	$("#cantAjuste").css({"background-color": "white", "color":"black"});
});

/*===================================================
ENVIA REPORTE DE AJUSTE DE INVENTARIO DESDE EL DATATABLE
===================================================*/
$("#datatableAI tbody").on("click", ".btnImprimirAjusteInv", function(){
	let idNumAjusteInv = $(this).attr("idNumAjusteInv");
   console.log(idNumAjusteInv);
    if(idNumAjusteInv.length > 0){
      window.open("extensiones/tcpdf/pdf/reporte_ajusteinv.php?codigo="+idNumAjusteInv,"_blank");
    }
})

//Date range as a button
    //$('#daterange-btn1').daterangepicker({ startDate: '04-01-2019', endDate: '04-03-2019' },
 $('#daterange-btn-Ajuste').daterangepicker({
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
        $('#daterange-btn-Ajuste span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
          
        var valorRangoAjusteInv = $("#daterange-btn-Ajuste span").html();
        //console.log("rango de fecha:",valorRangoAjusteInv);
        
         localStorage.setItem("daterange-btn-Ajuste", valorRangoAjusteInv);
          
      }
    )

/*=============================================
ASIGNA FECHA ACTUAL EN DATERANGEPICKER 
=============================================*/    
function fechadehoy(){
   
    var d = new Date();
    //console.log("Fecha de Hoy:",d);
    var dia = d.getDate();
    var mes = d.getMonth()+1;
    var año = d.getFullYear();

    if(mes < 10){

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
        $("#daterange-btn-Ajuste span").html(fechaInicial+' - '+fechaFinal);
        //console.log(fechaInicial+' - '+fechaFinal);
    	  localStorage.setItem("valorRangoAjusteInv", fechaInicial+' - '+fechaFinal);
    	
}    

/*=============================================
CAPTURAR HOY DIA=01-10 MES=01-10
=============================================*/
$(".daterangepicker.opensright .ranges li").on("click", function(){

	//var textoHoy = $(this).attr("data-range-key");
	var textoHoy = $(this).html();
    
	if(textoHoy == "Hoy"){
     
    var d = new Date();
    //console.log("Fecha de Hoy:",d);
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
        $("#daterange-btn-Ajuste span").html(fechaInicial+' - '+fechaFinal);
        //console.log(fechaInicial+' - '+fechaFinal);
    	  localStorage.setItem("valorRangoAjusteInv", fechaInicial+' - '+fechaFinal);
    	
	}

})    
/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/
$('#daterange-btn-Ajuste').on('cancel.daterangepicker', function(ev, picker) {
  localStorage.removeItem("valorRangoAjusteInv");
  localStorage.clear();
  $("#daterange-btn-Ajuste span").html('<i class="fa fa-calendar"></i> Rango de fecha')
});

// ========= LISTAR EN EL DATATABLE REGISTROS DE LA TABLA cajas================
function dataAjusteInv(){
  let rangodeFecha = $("#daterange-btn-Ajuste span").html();
  console.log("Rango de Fecha:",rangodeFecha);
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

  tablaAjusteInv=$('#datatableAI').dataTable(
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
		"ajax":
				{
          url: 'ajax/ajusteinventario.ajax.php?op=listar',
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

$('.selectAjuste').select2({
  placeholder: 'Busca y selecciona un producto',
});


init();
