$("#cortedecaja").draggable({
    handle: ".modal-header"
});


//Función que se ejecuta al inicio
function init(){

  /*=============================================
  VARIABLE LOCAL STORAGE
  =============================================*/
  if(localStorage.getItem("valorRangoCortes") != null){
    $("#daterange-btn-cortes span").html(localStorage.getItem("valorRangoCortes"));
  }else{
    fechadehoy();
  }
    dataTableCortes();
}


  //$('.btnImprimircorteventa').on('click', function(ev) {
   $("body").on("click", ".btnVerCorteVenta", function( ev ) {	    
    //ev.preventDefault();
    //console.log("entra:",target)
    $('#cortedecaja').modal('show');

    let boxcut = $(this).attr("idCorteVenta");
    let boxpurchase = $(this).attr("data-caja");
    let nombrebox = $(this).attr("data-nomcaja");
    let fechaventa=$(this).attr("data-fechaventa");
    let fechacut=$(this).attr("data-fechacutvta");
    let totaldecajachica=$(this).attr("data-cajachica");
    let totaldevtas=$(this).attr("data-vtas");
    let totaldeenv=$(this).attr("data-env");
    let totaldeserv=$(this).attr("data-serv");
    let totaldeotros=$(this).attr("data-abarrotes");
    let totaldecred=$(this).attr("data-cred");
    let totaldeing=$(this).attr("data-ing");
    let totaldeegr=$(this).attr("data-egr");
    let totaldeefec=(parseFloat(totaldevtas)+parseFloat(totaldeenv)+parseFloat(totaldeserv)+parseFloat(totaldeotros)+parseFloat(totaldeing))-parseFloat(totaldeegr);
    console.log("entra:",boxpurchase)
    $('#fechadeventa').html(fechaventa);
    $('#fechadeventa').attr('data-fechacorte',fechacut);
    $('#boxpurchase').html('Corte No: '+boxcut+'  de Caja No: '+boxpurchase+'-'+nombrebox);
    $('#printCutVta').val(boxcut);
    

    totaldevtas=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format((totaldevtas));
    $('#totaldevta').html(totaldevtas);  
    totaldeenv=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format((totaldeenv));
    $('#totaldeenv').html(totaldeenv);  
    totaldeserv=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format((totaldeserv));
    $('#totaldeserv').html(totaldeserv);  
    totaldeotros=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format((totaldeotros));
    $('#totaldeotros').html(totaldeotros);  
    totaldecred=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format((totaldecred));
    $('#totaldecred').html(totaldecred);  
    totaldeing=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format((totaldeing));
    $('#totaldeing').html(totaldeing);  
    totaldeegr=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format((totaldeegr));
    $('#totaldeegr').html(totaldeegr);  
    totaldeefec=new Intl.NumberFormat('en',{style:'currency',currency: 'USD',currencySign: 'accounting',}).format((totaldeefec));
    $('#totaldeefec').html(totaldeefec);  
    totaldecajachica=new Intl.NumberFormat('en',{style:'currency',currency: 'USD',currencySign: 'accounting',}).format((totaldecajachica));
    $('#totaldecajachica').html(totaldecajachica);  

  });

  $('#printCutVta').click(function () {
      let iddecorte=$('#printCutVta').val(); 
      let fechaventa=$("#fechadeventa").attr("data-fechacorte");
      let creditocut=$("#totaldecred").html();
      let egresocut=$("#totaldeegr").html();
      let ingresocut=$("#totaldeing").html();
      let cajachica=$("#totaldecajachica").html();

      let paso1=replaceAll(creditocut, ",", "" );
      creditocut=replaceAll(paso1, "$", "" );

      paso1=replaceAll(egresocut, ",", "" );
      egresocut=replaceAll(paso1, "$", "" );

      paso1=replaceAll(ingresocut, ",", "" );
      ingresocut=replaceAll(paso1, "$", "" );

      paso1=replaceAll(cajachica, ",", "" );
      cajachica=replaceAll(paso1, "$", "" );

      //console.log("corte # ",iddecorte);
      //console.log("fecha corte ",fechaventa);
      //console.log("egreso: ",egresocut);

      let data = new FormData();
      data.append('idcorte', iddecorte);
      data.append('fechaventa', fechaventa);
      data.append('creditocut', creditocut);
      data.append('egresocut', egresocut);
      data.append('ingresocut', ingresocut);
      data.append('cajachica', cajachica);

      fetch('extensiones/tcpdf/pdf/reimprimir_corte.php', {
        method: 'POST',
        body: data
      })

      .then(function(response) {
          console.log('response =', response);
          return response.text();
      })
      .then(function(data) {
          console.log('data = ', data);
          $('#cortedecaja').modal('hide');
      })
      .catch(function(err) {
          console.error(err);
      });

  })

  function replaceAll( text, busca, reemplaza ){
    while (text.toString().indexOf(busca) != -1)
        text = text.toString().replace(busca,reemplaza);
    return text;
  }

/* =========== CERRAR CAJA DE VENTA =========================== */
$("body").on("click", ".btnMakeCut", function( ev ) {	  
  
  let idcorte = $(this).attr("id_corte");
  let idcaja = $(this).attr("id_caja");
  let idfechaventa = $(this).attr("idFechaCut");
  let datecurrent=moment().format('YYYY-MM-DD');
  console.log("si entra para cerrar corte#", idcorte, idcaja, idfechaventa, datecurrent);
  
  if(idfechaventa==datecurrent){
     //console.log("mismo dia");
     Swal.fire(
      'Corte de venta con fecha de hoy!',
      'Puede hacerlo pulsando el boton CAJA',
      'question'
    )     
      return false;
  }else{
    $.get("ajax/salidas.ajax.php?op=cerrarcajavta", {cierre : "caja", idcorte: idcorte, idcaja: idcaja, idfechaventa: idfechaventa}, function(resp, estado,jqXHR){
         console.log("Respuesta: " + resp + "\nEstado: " + estado +"\njqHXR: " + jqXHR); 
        if(resp=="Hecho"){
          $('#datatablecortes').DataTable().ajax.reload(null, false);
					const Toast = Swal.mixin({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 2500,
						timerProgressBar: true,
						onOpen: (toast) => {
						toast.addEventListener('mouseenter', Swal.stopTimer)
						toast.addEventListener('mouseleave', Swal.resumeTimer)
						}
					})
					
					Toast.fire({
						icon: 'success',
						title: 'Caja Cerrada Exitosamente!. Espere...'
          })

          // .then((result)=>{
					// 	if (result.value) {
					// 		$('#datatablecortes').DataTable().ajax.reload(null, false);
					// 	}
					// })

            // swal.fire("Caja Cerrada Exitosamente!", {
            // icon: "success",
            // timer: 3000
            // })
            //     .then(function(result){
            //         if (result) {
            //           $('#datatablecortes').DataTable().ajax.reload(null, false);
            //         }else{
            //           $('#datatablecortes').DataTable().ajax.reload(null, false);
            //         }
            //       })  //fin .then
            
        }else{
            swal.fire({
            title: "Error!!",
            text: estado,
            icon: "error",
            })  //fin swal

        }
    
    })
  }
});  

//Date range as a button
    //$('#daterange-btn1').daterangepicker({ startDate: '04-01-2019', endDate: '04-03-2019' },
 $('#daterange-btn-cortes').daterangepicker({
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
        $('#daterange-btn-cortes span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
          
        var valorRangoCortes = $("#daterange-btn-cortes span").html();
       
         localStorage.setItem("daterange-btn-cortes", valorRangoCortes);
          
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
        $("#daterange-btn-cortes span").html(fechaInicial+' - '+fechaFinal);
        //console.log(fechaInicial+' - '+fechaFinal);
    	  localStorage.setItem("valorRangoCortes", fechaInicial+' - '+fechaFinal);
    	
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
        $("#daterange-btn-cortes span").html(fechaInicial+' - '+fechaFinal);
        //console.log(fechaInicial+' - '+fechaFinal);
    	  localStorage.setItem("valorRangoCortes", fechaInicial+' - '+fechaFinal);
    	
	}

})    
/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/
$('#daterange-btn-cortes').on('cancel.daterangepicker', function(ev, picker) {
  localStorage.removeItem("valorRangoCortes");
  localStorage.clear();
  $("#daterange-btn-cortes span").html('<i class="fa fa-calendar"></i> Rango de fecha')
});

// ========= LISTAR EN EL DATATABLE REGISTROS DE LA TABLA cajas================
function dataTableCortes(){
  let rangodeFecha = $("#daterange-btn-cortes span").html();
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

  tablaCortes=$('#datatablecortes').dataTable(
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
		"ajax":{
          url: 'ajax/cortesdeventas.ajax.php?op=listarcortes',
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
