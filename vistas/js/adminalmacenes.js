
$('.btnImprimirEntra').on('click', function() {
  var idNumEntrada = $("#numeroDocto2").val();
    if(idNumEntrada.length > 0){
     window.open("extensiones/tcpdf/pdf/imprimir_entrada.php?codigo="+idNumEntrada, "_blank");
    }
});


$("#tablalistado tbody").on("click", "button.btnImprimir", function(){
	var idEntrada = $(this).attr("idNumDocto");
   //console.log(idEntrada);
    if(idEntrada.length > 0){
     window.open("extensiones/tcpdf/pdf/imprimir_entrada.php?codigo="+idEntrada, "_blank");
    }
})


function listarEntrada(){
   var almacenSel = $("#almEntrada").val();
    console.log(almacenSel);

$(document).ready(function() {

$('#tablalistado').dataTable(
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
		"ajax":
				{
					url: 'ajax/adminalmacenes.ajax.php',
					data:{almacenSel: almacenSel},
					type : "get",
					dataType : "json",	
					error: function(e){
						console.log(e.responseText);
                    }
                },
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	    "order": [[ 0, "DESC" ]]//Ordenar (columna,orden)
	}).DataTable();

});         
}

