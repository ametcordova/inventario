var idNumAlma;
var idNumTec;
var idNomAlma;
var tableron;
$('.btnReporteporTecnico').on('click', ()=> {

    if(checarinputs()){	
	   /*window.open("extensiones/tcpdf/pdf/reporte_inv_tecnico.php?idNomAlma="+idNomAlma+"&idNumTec="+idNumTec+"&idNumAlma="+idNumAlma, "_blank");*/
	   window.open("extensiones/fpdf/reportes/reporte.php?idNomAlma="+idNomAlma+"&idNumTec="+idNumTec+"&idNumAlma="+idNumAlma,"_blank");
	}
	
});


function listar_inventario_por_tecnico(){
    if(checarinputs()){	
		console.log(idNomAlma, idNumAlma, idNumTec);
   //let valorradio=($('input:radio[name=radio1]:checked').val());

	tableron=$('#tablaportecnico').dataTable({
		   "lengthMenu": [ [10, 15, 25, 50,100, -1], [10, 15, 25, 50, 100, "Todos"] ],
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
			"columns" : [
			  {"data": 0},
			  {"data": 1},
			  {"data": 2},
			  {"data": 3}, 
			  {"data": 4}, 
			  {"data": 5},
			],			
			initComplete: function () {
			  var btns = $('.dt-button');
			  btns.removeClass('dt-button');
			  btns.addClass('btn btn-success btn-sm');
			},    
			"footerCallback": function ( row, data, start, end, display ) {
			  var api = this.api();
  
			// Total over all pages
			var total = api.column(5).data().sum();

			$(api.column(4).footer()).html('Total de Material en Tránsito ->');
			$(api.column(5).footer()).html(total);
		  },
		  "columnDefs": [
			{"className": "dt-center", "targets": [5]}				//"_all" para todas las columnas
			],
			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {		//cambiar el tamaño de la fuente
				if ( true ){ // your logic here
				$(nRow).addClass( 'customFont' );
				}
			},			 
			"ajax":
					{
						url: 'ajax/reporteinventario.ajax.php',
						data:{idNumAlma: idNumAlma, idNumTec: idNumTec},
						type : "get",
						dataType : "json",
						error: function(e){
							console.log(e.responseText);
						}
					},
			"bDestroy": true,
			"iDisplayLength": 15,//Paginación
			"order": [[ 0, "asc" ]]//Ordenar (columna,orden)
		}).DataTable();
	}else{
		return false;
	}
}

/********************************************************** */
// function interval para recargar el datatable cada 30 seg.
// ver: https://datatables.net/reference/api/ajax.reload()
/*********************************************************** */
setInterval( ()=> {
    tableron.ajax.reload( null, false ); // user paging is not reset on reload
	//console.log('recargo')
}, 30000 );			//recargar cada 30 seg.

function checarinputs(){
    idNumAlma=$("#noalmacen").val();      //id de Almacen Seleccionado
    idNumTec=$("#idTecnico").val();      //id tecnico Seleccionado
    idNomAlma=$("#noalmacen option:selected" ).text();
  
  	idNomAlma = idNomAlma.toLowerCase();
	idNumAlma=parseInt(idNumAlma);
    idNumTec=parseInt(idNumTec);
    //console.log(idNomAlma, idNumAlma, idNumTec);
	if(idNumAlma>0 && idNomAlma.length > 0 && idNumTec){
		return true;
	}else{
		$('.mensajedeerror').text('Seleccione un almacen');
		$(".mensajedeerror").removeClass("d-none");
		setTimeout(function(){$(".mensajedeerror").addClass("d-none")}, 3500);
		return false;
	}
}

