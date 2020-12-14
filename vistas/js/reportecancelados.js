//Función que se ejecuta al inicio
function init(){

  /*=============================================
  VARIABLE LOCAL STORAGE
  =============================================*/
  if(localStorage.getItem("valorRangoCancela") != null){
    console.log(localStorage.getItem("valorRangoCancela"))
    $("#daterange-btn-cancela span").html(localStorage.getItem("valorRangoCancela"));
  }else{
    fechadehoy();
  }
    dataTableCancela();
}


  $("body").on("click", ".btnImprimirCancelacion", function( ev ) {	    
    //ev.preventDefault();
    //console.log("entra:",target)
    //let idNotaSalida = $(this).attr("idNumSalida");
    let numcancelacion=$(this).attr("idNumCancela");

    console.log('data = ', numcancelacion);

    window.open("extensiones/tcpdf/pdf/imprimir_cancelacion.php?numcancelacion="+numcancelacion, "_blank");    

  });


//Date range as a button
    //$('#daterange-btn1').daterangepicker({ startDate: '04-01-2019', endDate: '04-03-2019' },
 $('#daterange-btn-cancela').daterangepicker({
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
        $('#daterange-btn-cancela span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
          
        var valorRangoCancela = $("#daterange-btn-cancela span").html();
       
         localStorage.setItem("daterange-btn-cancela", valorRangoCancela);
          
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
        $("#daterange-btn-cancela span").html(fechaInicial+' - '+fechaFinal);
        //console.log(fechaInicial+' - '+fechaFinal);
    	  localStorage.setItem("valorRangoCancela", fechaInicial+' - '+fechaFinal);
    	
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
        $("#daterange-btn-cancela span").html(fechaInicial+' - '+fechaFinal);
        //console.log(fechaInicial+' - '+fechaFinal);
    	  localStorage.setItem("valorRangoCancela", fechaInicial+' - '+fechaFinal);
    	
	}

})    
/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/
$('#daterange-btn-cancela').on('cancel.daterangepicker', function(ev, picker) {
  localStorage.removeItem("valorRangoCancela");
  localStorage.clear();
  $("#daterange-btn-cancela span").html('<i class="fa fa-calendar"></i> Rango de fecha')
});

// ========= LISTAR EN EL DATATABLE REGISTROS DE LA TABLA cajas================
function dataTableCancela(){
  let rangodeFecha = $("#daterange-btn-cancela span").html();
  localStorage.setItem("valorRangoCancela", rangodeFecha);
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

  tablaCancela=$('#datatablecancela').dataTable(
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
             {
              extend: 'excel',
              title:"REPORTE DE CANCELACIONES.",
              messageTop: '',
              messageBottom: null,
        
              text: 'E<u>X</u>port Excel',
              className: 'exportExcel',
              filename: 'reportecanceladas',
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
          {"className": "dt-center", "targets": [0]},				//la columna de 
          {"className": "dt-center", "targets": [1]},				//la columna de 
          {"className": "dt-center", "targets": [5]},				//la columna de cant
          {"className": "dt-right",  "targets": [6]},				//la columna de Exist
          {"className": "dt-center", "targets": [8]},				//la columna de Exist
        ],
		"ajax":{
          url: 'ajax/reportecancelados.ajax.php?op=listarcancelaciones',
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
