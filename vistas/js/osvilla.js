
	$("#modalAgregarOsvilla, #modalEditarOsvilla, #modalLiquidarOsvilla").draggable({
		  handle: ".modal-header"
	});

//Función que se ejecuta al inicio
function init(){

	iniciaFecha()
	listarOSVilla();
    
    $("#formularioAgregarOSVilla").on("submit",function(e){
            agregarOsVilla(e);	
    })

    $("#formularioEditarOSVilla").on("submit",function(e){
            editarOsVilla(e);
    })
	
}

/*================ AL SALIR DEL MODAL DE EDICION  RESETEAR FORMULARIO==================*/
$("#modalEditarOsvilla").on('hidden.bs.modal', ()=> {
	$('#formularioEditarOSVilla')[0].reset();
});

/*=======================================================================
GUARDAR OS VILLA
========================================================================*/
function agregarOsVilla(e){
	e.preventDefault(); //No se activará la acción predeterminada del evento
	var data = new FormData($("#formularioAgregarOSVilla")[0]);
     //for (var pair of data.entries()){console.log(pair[0]+ ', ' + pair[1]);}
	 //console.log(data.get('nvoContratante'));
	 
	fetch('ajax/osvilla.ajax.php?op=guardarOS', {
	  method: 'POST',
	  body: data
	})
      .then(ajaxPositive)
      .catch(muestroError);    
}

/*=======================================================================
MODIFICAR OS VILLA
========================================================================*/
function editarOsVilla(e){
	e.preventDefault(); //No se activará la acción predeterminada del evento
	var datos = new FormData($("#formularioEditarOSVilla")[0]);
     //for (var pair of data.entries()){console.log(pair[0]+ ', ' + pair[1]);}
	 console.log(datos.get('editarOs'));
	 
	fetch('ajax/osvilla.ajax.php?op=editarOS', {
	  method: 'POST',
	  body: datos
	})
      .then(ajaxPositive)
	  .then(data => console.log('data = ', data))
      .catch(muestroError);    
}

/*======================================================================*/
    function ajaxPositive(response) {
        
      //$('#cajaAbierta').modal('hide');
      console.log('response.ok: ', response.ok, response);
        
        swal({
          title: "Realizado!!",
          text: "Se ha guardado correctamente ",
          icon: "success",
          button: "Cerrar"
          })  //fin swal
          .then(function(result){
            if (result) {
				$('#modalAgregarOsvilla, #modalEditarOsvilla').modal('hide')
				$('#TablaOS').DataTable().ajax.reload(null, false);
            }
        })  //fin .then
               
      if(response.ok) {
        response.text().then(showResult);
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
              button: "Cerrar"
          })  //fin swal
        //window.location = "inicio";
    }
/*=============FIN AGREGAR OS================================ */

/*=============================================
ELIMINAR ORDEN DE SERVICIO
=============================================*/
$("#TablaOS").on("click", ".btnBorrarOs", function(){
let idEstatus = $(this).attr("idEstatus");
let idEstado=parseInt(idEstatus);
    if(idEstado==0){
        var idOs= $(this).attr("idOs");
        console.log("OS:",idOs, "Estatus:",idEstatus);
        var datos = new FormData();
        datos.append("idOs", idOs);
        
    swal({
      title: "¿Está seguro de borrar OS "+idOs+"? ",
      text: "¡Si no lo está puede cancelar la acción!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
        
    .then((aceptaBorrar) => {
      if (aceptaBorrar) {
       fetch('ajax/osvilla.ajax.php?op=eliminarOS', {
          method: 'POST',
          body: datos
       }) 
        .then((res) => res.json())
        .then((data) => 
            swal(data, {
            icon: "success",
            buttons: "Cerrar",
            timer: 3000
            })
             )
        .catch(muestroError);    
        $('#TablaOS').DataTable().ajax.reload(null, false);
      } else {
      swal("Acción Cancelada!", {
            icon: "success",
            buttons: "Cerrar",
            timer: 2000
            })
      }
    });    
        
    }
    
})

/*=============================================
MOSTRAR DATOS DE ORDEN DE SERVICIO PARA EDITAR
=============================================*/
$("#TablaOS").on("click", ".btnEditarOs", function(){

    let mostrarOs= $(this).attr("editarOs");
	$("#editarEstatus").prop('disabled',false);
    console.log("OS:",mostrarOs);
	let datos = new FormData();
	datos.append("mostrarOs", mostrarOs);
     fetch('ajax/osvilla.ajax.php?op=MostrarOS', {
          method: 'POST',
          body: datos
     }) 
    .then((res) => res.json())
    .then(data => {
        
		let datosmemo=(data.datos_memo).length;
		
		//console.log("Data:",data.datos_memo,"data",datosmemo)
		if(datosmemo!=0){
			var json = JSON.parse(data.datos_memo);
			
			$("#tarjlin1a").val(json[0].tarjlin1a);
			$("#telefono1").val(json[0].telefono1);
			$("#distrito1").val(json[0].distrito1);
			$("#terminal1").val(json[0].terminal1);
			$("#st1").val(json[0].st1);
			$("#par1").val(json[0].par1);
			$("#dispositivo1").val(json[0].dispositivo1);
			$("#localiza1").val(json[0].localiza1);
			
			$("#tarjlin2a").val(json[0].tarjlin2a);
			$("#telefono2").val(json[0].telefono2);
			$("#distrito2").val(json[0].distrito2);
			$("#terminal2").val(json[0].terminal2);
			$("#st2").val(json[0].st2);
			$("#par2").val(json[0].par2);
			$("#dispositivo2").val(json[0].dispositivo2);
			$("#localiza2").val(json[0].localiza2);
			
			$("input[name=eqser11]").val(json[0].eqser11);
			$("input[name=eqser12]").val(json[0].eqser12);
			$("input[name=eqser13]").val(json[0].eqser13);
			$("input[name=eqser14]").val(json[0].eqser14);
			$("input[name=eqser15]").val(json[0].eqser15);
			$("input[name=eqser16]").val(json[0].eqser16);
			$("input[name=eqser17]").val(json[0].eqser17);
			
			$("input[name=descripcion21]").val(json[0].descripcion21);
			$("input[name=descripcion22]").val(json[0].descripcion22);
			$("input[name=descripcion23]").val(json[0].descripcion23);
			$("input[name=descripcion24]").val(json[0].descripcion24);
			$("input[name=descripcion25]").val(json[0].descripcion25);
			$("input[name=descripcion26]").val(json[0].descripcion26);
			$("input[name=descripcion27]").val(json[0].descripcion27);
			
			$("input[name=cantidad21]").val(json[0].cantidad21);
			$("input[name=cantidad22]").val(json[0].cantidad22);
			$("input[name=cantidad23]").val(json[0].cantidad23);
			$("input[name=cantidad24]").val(json[0].cantidad24);
			$("input[name=cantidad25]").val(json[0].cantidad25);
			$("input[name=cantidad26]").val(json[0].cantidad26);
			$("input[name=cantidad27]").val(json[0].cantidad27);
					
			$("input[name=eqser21]").val(json[0].eqser21);
			$("input[name=eqser22]").val(json[0].eqser22);
			$("input[name=eqser23]").val(json[0].eqser23);
			$("input[name=eqser24]").val(json[0].eqser24);
			$("input[name=eqser25]").val(json[0].eqser25);
			$("input[name=eqser26]").val(json[0].eqser26);
			$("input[name=eqser27]").val(json[0].eqser27);
			
			$("input[name=descripcion11]").val(json[0].descripcion11);
			$("input[name=descripcion12]").val(json[0].descripcion12);
			$("input[name=descripcion13]").val(json[0].descripcion13);
			$("input[name=descripcion14]").val(json[0].descripcion14);
			$("input[name=descripcion15]").val(json[0].descripcion15);
			$("input[name=descripcion16]").val(json[0].descripcion16);
			$("input[name=descripcion17]").val(json[0].descripcion17);
			
			$("input[name=cantidad11]").val(json[0].cantidad11);
			$("input[name=cantidad12]").val(json[0].cantidad12);
			$("input[name=cantidad13]").val(json[0].cantidad13);
			$("input[name=cantidad141]").val(json[0].cantidad14);
			$("input[name=cantidad15]").val(json[0].cantidad15);
			$("input[name=cantidad16]").val(json[0].cantidad16);
			$("input[name=cantidad17]").val(json[0].cantidad17);

			$("#grupo").val(json[0].grupo);
			$("#dslam").val(json[0].dslam);
			$("#clase").val(json[0].clase);
			$("#rementrada").val(json[0].rementrada);
			$("#dispdigital").val(json[0].dispdigital);
			$("#remsalida").val(json[0].remsalida);

			$("input[name=producto]").val(json[0].producto);
			$("input[name=confvelocidad]").val(json[0].confvelocidad);
			$("input[name=confdescripcion]").val(json[0].confdescripcion);
			$("input[name=contvelocidad]").val(json[0].contvelocidad);
			$("input[name=contdescripcion]").val(json[0].contdescripcion);
			$("input[name=perfilconf]").val(json[0].perfilconf);
		
		}


        $("#editarArea").val(data.area);
        $("#editarOs").val(data.osvilla);
        $("#editarTipoOs").val(data.tipoos);
        $("#editarContratante").val(data.contratante);
        $("#editarFechaCita").val(data.fecha_cita);
        $("#editarDomicilio").val(data.domicilio);
        $("#editarCiudad").val(data.ciudad);
        $("#editarEstado").val(data.id_estado);
        $("#editarFolio").val(data.folio_pisaplex);
        $("#editarPrioridad").val(data.prioridad);
        $("#editarZona").val(data.zona);
        $("#editarTelefono").val(data.telefono_asignado);
        $("#editarTelContacto").val(data.telefono_contacto);
        $("#editarTipoCliente").val(data.tipo_cliente);
        $("#editarTelCelular").val(data.telefono_celular);
        $("#editarEmail").val(data.email);
		    $("#editarFechaAsigna").val(data.fecha_asignada);
        $("#editarObservacion").val(data.observaciones);
        $("#editarTecnico").val(data.id_tecnico);
        $("#editarAlmacen").val(data.id_almacen);
        $("#editarEstatus").val(data.estatus);
		
		if(data.estatus=="1"){
			$("#editarEstatus").prop('disabled',true);
		}
        
    });
})

// ========= LISTAR EN EL DATATABLE REGISTROS DE LA TABLA cajas================
function listarOSVilla(){
let rangodeFecha = $("#daterange-btn2 span").html();

  if(rangodeFecha==undefined || rangodeFecha==null){
      var fechaInicial=moment().format('YYYY-MM-DD');
      var fechaFinal=moment().format('YYYY-MM-DD');
  }else{
	  let arrayFecha = rangodeFecha.split(" ", 3);
	  let f1=arrayFecha[0].split("-");
	  let f2=arrayFecha[2].split("-");
	  var fechaInicial=f1[2].concat("-").concat(f1[1]).concat("-").concat(f1[0]); //armar la fecha año-mes-dia
	  var fechaFinal=f2[2].concat("-").concat(f2[1]).concat("-").concat(f2[0]);
  }	   
  
//console.log(fechaInicial, fechaFinal);

let datos = {"fechaini" : fechaInicial, "fechafin" : fechaFinal};

  tabla=$('#TablaOS').dataTable(
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
		"ajax":
				{
					url: 'ajax/osvilla.ajax.php?op=listar',
					type : "POST",
					data : datos,
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

/* ========== LIMPIA LOS INPUTS DEL FORMULARIO AGREGAR =========*/
$("#modalagrega").on("click",function(){
	//console.log("Ingreso al modal dando click en el modal");
	$('#formularioAgregarOSVilla',)[0].reset();
});


//MENSAJE AL ABRIR EL MODAL DE LIQUIDACION
$("#TablaOS").on("click", 'a[href="#modalLiquidarOsvilla"]', function(){
	console.log("Ingreso al modal modalLiquidarOsvilla dando click en el modal");
	let osvilla=($(this).attr("idOs"));
	$('#ordenServicio').html('Orden de Servicio No.: '+osvilla);
});


/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/
$('#daterange-btn2').on('cancel.daterangepicker', function(ev, picker) {
  iniciaFecha()
});

/*=============================================
FECHA ACTUAL EN EL BOTON DE RANGO DE FECHA
=============================================*/
function iniciaFecha(){

    var start = moment();
    var end = moment();

    function cb(start, end) {
		$('#daterange-btn2 span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
    }

    $('#daterange-btn2').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Hoy': [moment(), moment()],
           'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
           'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
           'Este Mes': [moment().startOf('month'), moment().endOf('month')],
           'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
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
		
    }, cb);

    cb(start, end);

};
	
init();