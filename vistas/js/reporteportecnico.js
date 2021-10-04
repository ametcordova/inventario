var idNumAlma;
var idNumTec;
var idNomAlma;

$('.btnReporteporTecnico').on('click', ()=> {

    if(checarinputs()){	
	   /*window.open("extensiones/tcpdf/pdf/reporte_inv_tecnico.php?idNomAlma="+idNomAlma+"&idNumTec="+idNumTec+"&idNumAlma="+idNumAlma, "_blank");*/
	   window.open("extensiones/fpdf/reportes/reporte.php?idNomAlma="+idNomAlma+"&idNumTec="+idNumTec+"&idNumAlma="+idNumAlma,"_blank");
	}
	
});


function checarinputs(){
    idNumAlma=$("#noalmacen").val();      //id de Almacen Seleccionado
    idNumTec=$("#idTecnico").val();      //id tecnico Seleccionado
    idNomAlma=$("#noalmacen option:selected" ).text();
  
  	idNomAlma = idNomAlma.toLowerCase();
	idNumAlma=parseInt(idNumAlma);
    idNumTec=parseInt(idNumTec);
    console.log(idNomAlma, idNumAlma, idNumTec);
	if(idNumAlma>0 && idNomAlma.length > 0 && idNumTec){
		return true;
	}else{
		console.log("No se selecciono almacen");
		$('.mensajedeerror').text('Seleccione un almacen');
		$(".mensajedeerror").removeClass("d-none");
		setTimeout(function(){$(".mensajedeerror").addClass("d-none")}, 3500);
		return false;
}


function listarInventario(){
  //var almacenSel = $("#almInventario").val();
   let almacenInv=$("#almInventario option:selected" ).text();
   let valorradio=($('input:radio[name=radio1]:checked').val());
   console.log(valorradio, almacenInv);


	$('#tablalistado').dataTable({
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
			initComplete: function () {
			  var btns = $('.dt-button');
			  btns.removeClass('dt-button');
			  btns.addClass('btn btn-success btn-sm');
			},     
			"ajax":
					{
						url: 'ajax/reporteinventario.ajax.php',
						data:{almacenSel: almacenInv, tiporeporte: valorradio},
						type : "get",
						dataType : "json",
						error: function(e){
							console.log(e.responseText);
						}
					},
			"bDestroy": true,
			"iDisplayLength": 10,//Paginación
			"order": [[ 7, "asc" ]]//Ordenar (columna,orden)
		}).DataTable();

}
}

