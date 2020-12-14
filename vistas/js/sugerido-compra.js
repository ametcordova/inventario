var formatState;


  $(document).ready(function() {
        $("#idsfamilySel").select2({
        templateResult: formatState
      });
  });



//$('#promocion').on('ifChanged', function(event) {
$("#SelFamAll").on('ifToggled', function(event) {
    if( $(this).is(':checked') ){
        console.log("entra1")
        $("#idsfamilySel > option").prop("selected","selected");// Select All Options
        $("#idsfamilySel").trigger("change");// Trigger change to select 2
    }else{
        console.log("entra2")
        $("#idsfamilySel").find('option').prop("selected",false);
        $("#idsfamilySel").trigger('change');		
     }
});


//CONSTRUIR
$('#botonRep, #botonImp').on('click', function() {

  let idNumAlma=$("#almInventario").val();      //id de Almacen Seleccionado
  let idNomAlma=$("#almInventario option:selected" ).text();    //Nombre del almacen Seleccionado
  let idNumFam = $("#idsfamilySel").val();     //id de Familia Seleccionado
  let idBoton=$(this).attr('id');
    
console.log("Has pulsado el elemento: " + $(this).attr('id'));
idNumAlma=parseInt(idNumAlma);
//console.log(idNumAlma, idNomAlma, idNumFam);
    if(idNumAlma>0 && idNomAlma.length > 0){
        if(idBoton=="botonRep"){
             window.open("extensiones/tcpdf/pdf/reporte_sugerido.php?idNomAlma="+idNomAlma+"&idNumFam="+idNumFam, "_blank");
        }else{		//imprimir el inventario en ticket
             window.open("extensiones/tcpdf/pdf/imprimir_inventario.php?idNomAlma="+idNomAlma+"&idNumFam="+idNumFam, "_blank","top=200,left=500,width=400,height=400");
        }    
    }else{
		$('#mensajerror').text('Seleccione Almacen!!');
		$("#mensajerror").removeClass("d-none");
		setTimeout(function(){$("#mensajerror").addClass("d-none")}, 3000);   //1000 ms= 1segundo
	}
});



function listarSugerido(){
   let idNumAlma=$("#almInventario").val();      //id de Almacen Seleccionado
   let almacenInv=$("#almInventario option:selected" ).text();
   let familiaInv=$("#idsfamilySel").val();
   console.log("id familia", familiaInv);
   idNumAlma=parseInt(idNumAlma);
   let idFam=parseInt(familiaInv);
   if(idNumAlma>0 && almacenInv.length > 0 && idFam>0){
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
					'csvHtml5',
					'pdfHtml5',
					{
					extend: 'excel',
					title:"SUGERIDO DE COMPRA.",
					messageTop: 'The information in this table is copyright to @Kórdova Corp.',
					messageBottom: null,
		
					text: 'E<u>X</u>port Excel',
					className: 'exportExcel',
					filename: 'sugeridodecompra',
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
					{"className": "dt-center", "targets": [6]},				//la columna de Exist
					{"className": "dt-center", "targets": [7]},				//la columna de Stock
					{"className": "dt-center", "targets": [8]},				
					{"className": "dt-right", "targets": [9]},				
					{"className": "dt-right", "targets": [10]}				//Alinear columnas "_all" para todas las columnas
				],
				"footerCallback": function ( row, data, start, end, display ) {
				   var api = this.api();
				 //console.log(api,data);
			
				   // Total over all pages
				   let total = api.column(10).data().sum();
				   total=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(total);
				   //console.log(total);
			
				   $(api.column(9).footer()).html('Total:');
				   $(api.column(10).footer()).html(total);	
				 },  						
					"ajax":
							{
								url: 'ajax/sugerido-compra.ajax.php',
								data:{almacenInv: almacenInv,familiaInv: familiaInv },
								type : "get",
								dataType : "json",	
								error: function(e){
									console.log(e.responseText);
								}
							},
					"bDestroy": true,
					"iDisplayLength": 10,//Paginación
					"order": [[ 2, "asc" ]]//Ordenar (columna,orden)
				}).DataTable();

		});    
	}else{
		$('#mensajerror').text('Seleccione Almacen y/o Familia!!');
		$("#mensajerror").removeClass("d-none");
		setTimeout(function(){$("#mensajerror").addClass("d-none")}, 3000);   //1000 ms= 1segundo
	}     
}    //FIN DE FUNCION listarInventario()

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
