$(".modal-header").css("cursor", "move");

   $('.TablaProveedores').DataTable( {
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
            'excelHtml5',
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                title: "NUNOSCO Conexiones",
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
    } );

$(document).ready(function() {
    $('.activartable').DataTable( {
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',
        buttons: [
        {
            extend: 'print',
            text: 'Print current page',
            autoPrint: false
        }
    ],
        "bDestroy": true,
        "iDisplayLength": 10,//Paginación
        "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
    } );
} );

/***********************************************/
//Función Regresar al inicio
/***********************************************/
function regresar()
{
	location.href = 'inicio';
}
/********************************************* */

$.fn.datepicker.dates['es'] = {
    days: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
    daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
    daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
    months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
    monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
    today: "Hoy",
    clear: "Quitar"
};

//Flat red color scheme for iCheck
$(function () {
 
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass   : 'iradio_square-blue',
      increaseArea : '20%' // optional
    })
	
//Initialize Select2 Elements
    $('.proveedores').select2({
        placeholder: 'Selecciona una categoría',
        ajax: {
          url: 'vistas/modulos/ajax.php',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      }) 	
    
    
   //Date range picker
    $('#datepicker1').datepicker({
    autoclose:true,
    calendarWeeks:true,
    clearBtn:true,
    language:"es",
    todayHighlight:true,
    starView:"months"
    });   
    
    $('#datepicker2').datepicker({
    autoclose:true,
    todayHighlight:true,
    calendarWeeks:true,
    clearBtn:true,
    language:"es"
    });   
    
    
})

/**********************************************/
/* Función para cambiar de minuscula a mayuscula
/*********************************************/
function mayuscula(letra) {
 letra.value=letra.value.toUpperCase(); 
 //console.log(letra);
return true;
}
/********************************************/


/*=============================================
Data Table
=============================================*/

$(".tablas").DataTable({

	"language": {

		"sProcessing":     "Procesando...",
		"sLengthMenu":     "Mostrar _MENU_ registros",
		"sZeroRecords":    "No se encontraron resultados",
		"sEmptyTable":     "Ningún dato disponible en esta tabla",
		"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
		"sInfoEmpty":      "Mostrar registros del 0 al 0 de un total de 0",
		"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
		"sInfoPostFix":    "",
		"sSearch":         "Buscar:",
		"sUrl":            "",
		"sInfoThousands":  ",",
		"sLoadingRecords": "Cargando...",
		"oPaginate": {
		"sFirst":    "Primero",
		"sLast":     "Último",
		"sNext":     "Siguiente",
		"sPrevious": "Anterior"
		},
		"oAria": {
			"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
			"sSortDescending": ": Activar para ordenar la columna de manera descendente"
		}

	}

});


/*=============================================
 //input Mask
=============================================*/
//Datemask dd/mm/yyyy
$('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
//Datemask2 mm/dd/yyyy
$('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
//Money Euro
$('[data-mask]').inputmask()	



 /*=============================================*/
 $('.activarDatatable').DataTable({
  "aProcessing": true,//Activamos el procesamiento del datatables
  "aServerSide": true,//Paginación y filtrado realizados por el servidor
  "paging": true,  //mostrar la paginación con los datos lengthMenu
  "lengthMenu": [ [10, 15, 25, 50,100, -1], [10, 15, 25, 50, 100, "Todos"] ],
  "language": {
  "sProcessing":     "Procesando...",
  "sLengthMenu":     "Mostrar _MENU_ registros &nbsp",
  "sZeroRecords":    "No se encontraron resultados",
  "sEmptyTable":     "Ningún dato disponible en esta tabla",
  "sInfo":           "Mostrar registros del _START_ al _END_ de un total de _TOTAL_",
  "sInfoEmpty":      "Mostrar _START_ de _END_ de un _TOTAL_ entradas",
  "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
  "sInfoPostFix":    "",           
  "sSearch":         "Buscar:",
  "sInfoThousands":  ",",
  "sDecimal":        ".",
  "sLoadingRecords": "Cargando...",
  "sPaginationType": "full_numbers",
  "oPaginate": {
  "sFirst":    "Primero",
  "sLast":     "Último",
  "sNext":     "Sig.",
  "sPrevious": "Ant."}
      },
  "oAria": {
    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
  },
      dom: '<clear>Bfrtip',
      buttons: [
          'copyHtml5',
          'excelHtml5',
          'csvHtml5',
          {
              extend: 'pdfHtml5',
              orientation: 'landscape',
              title: "NUNOSCO",
              customize: function ( doc ) {
                  pdfMake.createPdf(doc).open();
              },

          },
     {
          extend: 'print',
          text: 'Imprimir',
          autoPrint: false            //TRUE para abrir la impresora
      }
      ],
      initComplete: function () {
          var btns = $('.dt-button');
          btns.removeClass('dt-button');
          btns.addClass('btn btn-success btn-sm');
        },

  columnDefs: [{
      width: "10px",
      targets: 0
    },
    {
      width: "150px",
      targets: 1
    },
    {
      width: "80px",
      targets: 2
    },
    {
      width: "20px",
      targets: 3
    },
    {
      width: "70px",
      targets: 4
    },
    {
      width: "180px",
      targets: 5
    },
    {
      width: "60px",
      targets: 7
    },
    {
      width: "70px",
      targets: 8
    },
    {
      width: "60px",
      targets: 9
    },
    {
      width: "50px",
      targets: 10
    },
    {"className": "dt-center", "targets": [10]}
  ],
  "drawCallback": function( settings ) {
      $('ul.pagination').addClass("pagination-sm");
 }        
});

/***********************************************************************/
function fechahora(){
let optionsHora = {hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit'};
let optionsFecha = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
const formatFullDate = new Date().toLocaleDateString("es-MX", optionsFecha);
const semana=moment().isoWeek();

  clockTempo=setInterval(()=>{
    let clockHour=new Date().toLocaleTimeString('es-MX', optionsHora);
    document.getElementById("liveclock").innerHTML=`Fecha y Hora: ${formatFullDate} ${clockHour} Sem. ${semana}`;
    },1000
  )
}
/***********************************************************************/
//DECRAPETED
// HORA Y FECHA EN LA CABECERA 
//
 var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"); 
 var diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"); 
 var f=new Date(); 
 var xfecha=diasSemana[f.getDay()] + " " + f.getDate() + " de " + meses[f.getMonth()] + " del " + f.getFullYear();

function show5(){
if (!document.layers&&!document.all&&!document.getElementById)
return

 let Digital=new Date()
 let hours=Digital.getHours()
 let minutes=Digital.getMinutes()
 let seconds=Digital.getSeconds()

var dn="pm"
if (hours<12)
dn="am"
if (hours>12)
hours=hours-12
if (hours==0)
hours=12

 if (minutes<=9)
 minutes="0"+minutes
 if (seconds<=9)
 seconds="0"+seconds
//change font size here to your desire
//myclock="<font size='3' face='Arial' ><b><font size='2'>Fecha y Hora: "+xfecha+' '+"</font>"+hours+":"+minutes+":"+seconds+" "+dn+"</b></font>"
myclock="Fecha y Hora: "+xfecha+' '+hours+':'+minutes+':'+seconds+' '+dn
if (document.layers){
	document.layers.liveclock.document.write(myclock)
	document.layers.liveclock.document.close()
}
else if (document.all)
 liveclock.innerHTML=myclock
else if (document.getElementById)
 document.getElementById("liveclock").innerHTML=myclock
 setTimeout("show5()",1000)
 
}
/*************************************************************************************************** */ 
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

function complete() {
  $(".alert").addClass("d-none")
}

/*************************************************************/
/* Funcion para saber si hay conexion a internet             */
/************************************************************/
function networkStatus(){
  const d=document, w=window, n=navigator;    

  if(n.onLine){
    d.getElementById('statusnetwork').innerHTML=`<img src='vistas/img/wifi.gif' width='35%' alt='wifi'><span class='badge bg-primary' title='Conexión establecida'>Online</span>`;
  }else{
    d.getElementById('statusnetwork').innerHTML=`<span class='badge bg-danger' title='Sin conexión'>Offline</span>`;
  }
  
   const isOnLine=()=>{
    if(n.onLine){
        d.getElementById('statusnetwork').innerHTML=`<img src='vistas/img/wifi.gif' width='35%' alt='wifi'><span class='badge bg-primary' title='Conexión establecida'>Online</span>`;
     }else{
        d.getElementById('statusnetwork').innerHTML=`<span class='badge bg-danger' title='Sin conexión'>Offline</span>`;
     }
  }
  
  //w.addEventListener("online",(e)=>console.log(e, n.onLine));
  //w.addEventListener("offline",(e)=>console.log(e, n.onLine));

  w.addEventListener("online",(e)=>isOnLine());
  w.addEventListener("offline",(e)=>isOnLine());
}
/*************************************************************************************************** */ 