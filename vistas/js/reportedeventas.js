
/*$('document').ready(function(){
    
})*/

function formatState (state) {
    if(!state.element) return;
    var os = $(state.element).attr('onlyslave');
    return $('<span onlyslave="' + os + '">' + state.text + '</span>');
 }

 
   $(document).ready(function() {
        $("#selFamilia").select2({
          placeholder: 'Filtro por familia',
          escapeMarkup: function(m) { 
             return m; 
          },
          allowClear: true,
          templateResult: formatState
      });
  });

//$('#promocion').on('ifChanged', function(event) {
$("#SelectTodos").on('ifToggled', function(event) {
    if( $(this).is(':checked') ){
        console.log("entra1")
        $("#selFamilia > option").prop("selected","selected");// Select All Options
        $("#selFamilia").trigger("change");// Trigger change to select 2
    }else{
        console.log("entra2")
        $("#selFamilia").find('option').prop("selected",false);
        $("#selFamilia").trigger('change');		
     }
});



$('.clienteSel').select2({
  placeholder: 'Cliente',
  ajax: {
    url: 'ajax/reportedeventas.ajax.php?op=mostrarClie',
    dataType: 'json',
    type: 'POST',
    delay: 250,
    data: function (params) {
      return {
        searchTerm: params.term };    // search term
     },
     processResults: function (response) {
       return {
          results: response
       };
     },
     cache: true
    },
    minimumInputLength: 1
});

$('.selProdRepVta').select2({
  placeholder: 'Seleccione hasta 5 prod.',
  maximumSelectionLength: 5,            //maximo de items para seleccionar
  allowClear: true,
 });

 $('#TipoMovSal').select2({
  placeholder: 'Sel hasta 5 Mov',
  maximumSelectionLength: 5,            //maximo de items para seleccionar
  allowClear: true,
 });

/*=============================================
VARIABLE LOCAL STORAGE
=============================================*/

if(localStorage.getItem("capturarRango") != null){

	$("#daterange-btn span").html(localStorage.getItem("capturarRango"));

}else{

	$("#daterange-btn span").html('<i class="fa fa-calendar"></i> Rango de fecha')

}


//=======  GENERAR REPORTE EN PDF  =======
$('.btnImprimirVta').on('click', function() {

  let idNumAlma=$("#almaDeVenta").val();      //id de Almacen Seleccionado
  let idNomAlma=$("#almaDeVenta option:selected" ).text();    //Nombre del almacen Seleccionado
  let idNumFam =$("#selFamilia").val();     //id de Familia Seleccionado
  let idNumCaja=$("#selCaja").val();     //id de Familia Seleccionado
  let idNumCliente=$("#selCliente").val();     //id de CLIENTE Seleccionado
  let idNumProds=$("#selProdRepInv").val();     //id de PRODUCTOS Seleccionados
  let idTipoMovs=$("#TipoMovSal").val();     //id de TIPOMOV Seleccionados
  console.log("TipMov.",idTipoMovs);
    
    let rangodeFecha = $("#daterange-btn span").html();
    let arrayFecha = rangodeFecha.split(" ", 3);
    let f1=arrayFecha[0].split("-");
    let f2=arrayFecha[2].split("-");
    //console.log("capt rango: ",rangodeFecha, "FecIni:",arrayFecha[0], "fecFin:",arrayFecha[2], "F1:",f1, "f2:",f2);
    
    let idFechVta1=f1[2].concat("-").concat(f1[1]).concat("-").concat(f1[0]); //armar la fecha año-mes-dia
    //console.log("fecha1 ",idFechVta1);
    let idFechVta2=f2[2].concat("-").concat(f2[1]).concat("-").concat(f2[0]);
    //console.log("fecha2 ",idFechVta2);
    
    idNumAlma=parseInt(idNumAlma);
    
    if(idNumAlma>0 && idNomAlma.length > 0){
       window.open("extensiones/tcpdf/pdf/reporte_ventas.php?idNumAlma="+idNumAlma+"&idNomAlma="+idNomAlma+"&idNumFam="+idNumFam+"&idFechVta1="+idFechVta1+"&idFechVta2="+idFechVta2+"&idNumCaja="+idNumCaja+"&idNumCliente="+idNumCliente+"&idNumProds="+idNumProds+"&idTipoMovs="+idTipoMovs, "_blank");
    }
});

/*
async function mostrarpagina(){
  $("#target object").attr("data"," ");
  let element = document.getElementById("cover");
  element.classList.remove("d-none");
  //$("#cover").removeClass("d-none");  //igual funciona
  let idNumAlma=$("#almaDeVenta").val();      //id de Almacen Seleccionado
  let idNomAlma=$("#almaDeVenta option:selected" ).text();    //Nombre del almacen Seleccionado
  let idNumFam = $("#selFamilia").val();     //id de Familia Seleccionado
  let idNumCaja=$("#selCaja").val();     //id de Familia Seleccionado
  let idNumCliente=$("#selCliente").val();     //id de cliente Seleccionado
  let idNumProds=$("#selProdRepInv").val();     //id de PRODUCTOS Seleccionados
  console.log(idNumCaja, idNumCliente, idNumProds)

  let rangodeFecha = $("#daterange-btn span").html();
  let arrayFecha = rangodeFecha.split(" ", 3);
  let f1=arrayFecha[0].split("-");
  let f2=arrayFecha[2].split("-");
  
  let idFechVta1=f1[2].concat("-").concat(f1[1]).concat("-").concat(f1[0]); //armar la fecha año-mes-dia

  let idFechVta2=f2[2].concat("-").concat(f2[1]).concat("-").concat(f2[0]);
  
  idNumAlma=parseInt(idNumAlma);
  
  if(idNumAlma>0 && idNomAlma.length > 0){
  let idNumCaja=$("#selCaja").val();     //id de Familia Seleccionado
  let response = await fetch("extensiones/tcpdf/pdf/imprimir_ventaxdia.php?idNumAlma="+idNumAlma+"&idNomAlma="+idNomAlma+"&idNumFam="+idNumFam+"&idFechVta1="+idFechVta1+"&idFechVta2="+idFechVta2+"&idNumCaja="+idNumCaja+"&idNumCliente="+idNumCliente+"&idNumProds="+idNumProds+"&interno");   //interno es solo una variable sin dato
    
    console.log(response);
    let leerPDF=await $("#target object").attr("data","extensiones/tcpdf/pdf/ventadia.pdf");  
    element.classList.add("d-none");
  }
  
  }    //FIN DE FUNCION 
*/


   //Date range as a button
    //$('#daterange-btn').daterangepicker({ startDate: '04/01/2019', endDate: '04/03/2019' },
    $('#daterange-btn').daterangepicker(
        
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
        $('#daterange-btn span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
          
        var fechaInicial=start.format('YYYY-M-D');
        var fechaFinal=end.format('YYYY-M-D');
        //console.log(fechaInicial, fechaFinal);
        var capturarRango = $("#daterange-btn span").html();
        //console.log("rango de fecha:",capturarRango);
        
          localStorage.setItem("capturarRango", capturarRango);
          
          //window.location = "index.php?ruta=reportes&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;
      }
    )

/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/
$(".daterangepicker.opensright .range_inputs .cancelBtn").on("click", function(){
	localStorage.removeItem("capturarRango");
	localStorage.clear();
	window.location = "reportedeventas";
})

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
        $("#daterange-btn span").html(fechaInicial+' - '+fechaFinal);
        console.log(fechaInicial+' - '+fechaFinal);
    	localStorage.setItem("capturarRango", fechaInicial+' - '+fechaFinal);

    	//window.location = "index.php?ruta=reportes&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;

	}
})

function ListarVentas(){
  let idNumAlma=$("#almaDeVenta").val();      //id de Almacen Seleccionado
  let almacenInv=$("#almaDeVenta option:selected" ).text();
  let idNumFam =$("#selFamilia").val();     //id de Familia Seleccionado
  let idNumCaja=$("#selCaja").val();     //id de Familia Seleccionado
  let idNumCliente=$("#selCliente").val();     //id de CLIENTE Seleccionado
  let idNumProds=$("#selProdRepInv").val();     //id de PRODUCTOS Seleccionados
  let idTipoMovs=$("#TipoMovSal").val();     //id de TIPOMOV Seleccionados
  //console.log(idNumCaja,idNumCliente)
    
    let rangodeFecha = $("#daterange-btn span").html();
    let arrayFecha = rangodeFecha.split(" ", 3);
    let f1=arrayFecha[0].split("-");
    let f2=arrayFecha[2].split("-");
    //console.log("capt rango: ",rangodeFecha, "FecIni:",arrayFecha[0], "fecFin:",arrayFecha[2], "F1:",f1, "f2:",f2);
    
    let idFechVta1=f1[2].concat("-").concat(f1[1]).concat("-").concat(f1[0]); //armar la fecha año-mes-dia
    //console.log("fecha1 ",idFechVta1);
    let idFechVta2=f2[2].concat("-").concat(f2[1]).concat("-").concat(f2[0]);
    //console.log("fecha2 ",idFechVta2);
    
  idAlma=parseInt(idNumAlma);
  
  if(idAlma>0 && almacenInv.length > 0 ){

   $(document).ready(function() {
   $('#tabladeventas').dataTable(
       {
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
         'copyHtml5',
         'csvHtml5',
         'pdfHtml5',
         {
         extend: 'excel',
         title:"REPORTE DE VENTAS.",
         messageTop: 'The information in this table is copyright to @Kórdova Corp.',
         messageBottom: null,
   
         text: 'E<u>X</u>port Excel',
         className: 'exportExcel',
         filename: 'reporteventas',
           exportOptions: {
             modifier: {
             page: 'all'			//todas las columnas
             }
           },	  
           key: {
             key: 'x',				//atajo para generar rep en excel
             altKey: true
           }			  
         }, 			
       {
         extend: 'print',
         text: 'Imprimir',
         titleAttr: 'Imprimir',			
         className: 'imprimir',			
         autoPrint: false            //TRUE para abrir la impresora
       }],    
       initComplete: function () {			//botones pequeños y color verde
       var btns = $('.dt-button');
       btns.removeClass('dt-button');
       btns.addClass('btn btn-success btn-sm');
       },  
       "columnDefs": [
         {"className": "dt-center", "targets": [3]},				//la columna de cant
         {"className": "dt-center", "targets": [4]},				//la columna de cant
         {"className": "dt-right", "targets": [5]},				//la columna de Exist
         {"className": "dt-right", "targets": [6]},				//la columna de Exist
         {"className": "dt-right", "targets": [7]},				//la columna de Stock
         {"className": "dt-right", "targets": [8]},				
         {"className": "dt-right", "targets": [9]},				
         {"className": "dt-right", "targets": [10]}				//Alinear columnas "_all" para todas las columnas
       ],
       "footerCallback": function ( row, data, start, end, display ) {
          var api = this.api();
        //console.log(api,data);
     
          // cant Total over all pages
          var totalcant = api.column(4).data().sum();
          totalcant=new Intl.NumberFormat('en', {notation: 'standard',}).format(totalcant);
          //console.log(totalcant);
      
          // Totalcosto over all pages
          var totalcosto = api.column(6).data().sum();
          totalcosto=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(totalcosto);
          
          // Totalventa over all pages
          var totalventa = api.column(7).data().sum();
          totalventa=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(totalventa);

          // Totalpromo over all pages
          var totalpromo = api.column(8).data().sum();
          totalpromo=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(totalpromo);

          // GranTotal over all pages
          var grantotal = api.column(9).data().sum();
          grantotal=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(grantotal);

          // GranTotal over all pages
          var dif = api.column(10).data().sum();
          dif=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(dif);

          $(api.column(3).footer()).html('Totales:');
          $(api.column(4).footer()).html(totalcant);
          $(api.column(6).footer()).html(totalcosto);
          $(api.column(7).footer()).html(totalventa);
          $(api.column(8).footer()).html(totalpromo);
          $(api.column(9).footer()).html(grantotal);
          $(api.column(10).footer()).html(dif);
       },			
         "ajax":
             {
               url: 'ajax/reportedeventas.ajax.php?op=listarVentas',
               data:{idNumAlma: idNumAlma, idNumFam: idNumFam, idNumCaja: idNumCaja, idNumCliente: idNumCliente, idNumProds: idNumProds, idTipoMovs:idTipoMovs, idFechVta1: idFechVta1, idFechVta2: idFechVta2 },
               type : "get",
               dataType : "json",	
               error: function(e){
                 console.log(e.responseText);
               }
             },
         "bDestroy": true,
         "iDisplayLength": 15,//Paginación
         "order": [[ 2, "asc" ]]//Ordenar (columna,orden)
       }).DataTable();

   });    
 }else{
   $('#mensajederror').text('Seleccione Almacen!');
   $("#mensajederror").removeClass("d-none");
   setTimeout(function(){$("#mensajederror").addClass("d-none")}, 3000);   //1000 ms= 1segundo
 }     
}    //FIN DE FUNCION ListarVentas()

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

