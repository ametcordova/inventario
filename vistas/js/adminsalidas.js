

/*===================================================
ENVIA REPORTE DE SALIDA DE ALMACEN DESDE EL DATATABLE
===================================================*/
$("#tablalistaSalida tbody").on("click", "button.btnImprimirNotSal", function(){
	let idNotaSalida = $(this).attr("idNumSalida");
   console.log(idNotaSalida);
    if(idNotaSalida.length > 0){
     window.open("extensiones/tcpdf/pdf/imprimir_salida.php?codigo="+idNotaSalida, "_blank");
    }
})


/*=========================================================
MUESTRA SALIDAS DE ALMACEN EN EL DATATABLE SEGUN CONDICIONES
==========================================================*/
function listarSalida(){
   var almacenSel = $("#almSalida").val();
   var tecnicoSel = $("#tecSalida").val();
    console.log(almacenSel, tecnicoSel);

$(document).ready(function() {

$('#tablalistaSalida').dataTable(
	{
"lengthMenu": [ [10, 25, 50,100, -1], [10, 25, 50, 100, "Todos"] ],
"language": {
    "url": "extensiones/espanol.json",
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
					url: 'ajax/adminsalidas.ajax.php',
					data:{almacenSel: almacenSel, tecnicoSel: tecnicoSel},
					type : "get",
					dataType : "json",	
					error: function(e){
						console.log(e.responseText);
                    }
                },
		"bDestroy": true,
		"iDisplayLength": 8,//Paginación
	    "order": [[ 4, "DESC" ]]//Ordenar (columna,orden)
	}).DataTable();

});         
}
//=========================================================
//FIN DEL DATATABLE
//==========================================================

/*===============================================
ENVIA REPORTE DE SALIDA DE ALMACEN DESDE EL INPUT
===============================================*/
$('.btnImprimirSalida').on('click', function() {
  var idNumeroSalida = $("#numSalida").val();
    //console.log(idNumeroSalida);
    if(idNumeroSalida.length > 0){
     window.open("extensiones/tcpdf/pdf/imprimir_salida.php?codigo="+idNumeroSalida, "_blank");
    }
});
