var udeMedida;
var renglonesEntradas=cantEntrante=0;
var arrayProductos=new Array();
var ventana;
$("#modalAgregarEntradasAlmacen, #modalUpEntradasAlmacen").draggable({
    handle: ".modal-header"
});

//Función que se ejecuta al inicio
function init(){
  
/*=============================================
  VARIABLE LOCAL STORAGE
  =============================================*/
  //console.log(localStorage.getItem("daterange-btn-EntAlmacen"))
  if (localStorage.getItem("daterange-btn-EntAlmacen") === null) {
    fechaactual();  
  }
  
  $("#btnGuardarEntradasAlmacen").hide();

  dt_ListarEntradasAlmacen();

}

// ========= LISTAR EN EL DATATABLE REGISTROS DE LA TABLA cajas================
function dt_ListarEntradasAlmacen(){
  let rangodeFecha = (localStorage.getItem("daterange-btn-EntAlmacen"));
  $('#daterange-btn-EntAlmacen span').html(rangodeFecha);

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

  tablaSalidasAlmacen=$('#dt-entradasalmacen').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	  "aServerSide": true,//Paginación y filtrado realizados por el servidor
    "lengthMenu": [ [10, 25, 50,100, -1], [10, 25, 50, 100, "Todos"] ],
    "language": {
      "url": "extensiones/espanol.json",
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
		"ajax":
				{
          url: 'ajax/entradasalmacen.ajax.php?op=listar',
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

 $('#daterange-btn-EntAlmacen').daterangepicker({
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
  $('#daterange-btn-EntAlmacen span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
    
  var RangoFechaEntradasAlmacen = $("#daterange-btn-EntAlmacen span").html();
  //console.log("rango de fecha:",valorRangoAjusteInv);
  
   localStorage.setItem("daterange-btn-EntAlmacen", RangoFechaEntradasAlmacen);
    
}
)

/*=============================================
ASIGNA FECHA ACTUAL EN DATERANGEPICKER 
=============================================*/    
function fechaactual(){
    let fechaInicial=moment().add(-30, 'day').format('DD-MM-YYYY');
    let fechaFinal=moment().format('DD-MM-YYYY');
    $("#daterange-btn-EntAlmacen span").html(fechaInicial+' - '+fechaFinal);
    localStorage.setItem("RangoFechaEntradasAlmacen", fechaInicial+' - '+fechaFinal);
}    

/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/
$('#daterange-btn-EntAlmacen').on('cancel.daterangepicker', function(ev, picker) {
  localStorage.removeItem("RangoFechaEntradasAlmacen ");
  localStorage.clear();
  $("#daterange-btn-EntAlmacen span").html('<i class="fa fa-calendar"></i> Rango de fecha')
});

/*================ AL SALIR DEL MODAL RESETEAR FORMULARIO ==================*/
$("#modalAgregarEntradasAlmacen").on('hidden.bs.modal', ()=> {
  $("#agregarProdEntrada").addClass("d-none");
  $('#idAlmacenEntrada option:not(:selected)').attr('disabled',false);
  $('#form_entradasalmacen')[0].reset();                //resetea el formulario
  $("#cantExistenciaAlmacen").val(0);                   //inicializa campo existencia
  $("#cantEntradaAlmacen").val("");                     //inicializa campo salida
  $("#tbodyentradasalmacen").empty();                   //vacia tbody
  $('#selProdEntAlm').val(null).trigger('change');      //inicializa el select de productos
  arrayProductos["length"]=0;                      //inicializa array
});

//AL ABRIR EL MODAL TRAER EL ULTIMO NUMERO
$('#modalAgregarEntradasAlmacen').on('show.bs.modal', function (event) {
  UltimoNumEntradaAlmacen();		//TRAE EL SIGUIENTE NUMERO 
  renglonesEntradas=cantEntrante=0;
	$("#renglonentradas").html("");
	$("#totalentradasalmacen").html("");
})

//TRAER EL ULTIMO ID GUARDADO
async function UltimoNumEntradaAlmacen(){
  let resulta=0;
  let response = await fetch("ajax/entradasalmacen.ajax.php?op=obtenerUltimoNumero");
  let result = await response.json();
  console.log(result.id);
  if(result.id===null){
    $("#numEntradaAlmacen").val(1);
  }else{
    resulta=parseInt(result.id)+1;
    $("#numEntradaAlmacen").val(resulta);
  }
}

// DESELECCIONA ALMACEN PARA EVITAR CAMBIARLO
$("#idAlmacenEntrada").change(function(){
  $('#idAlmacenEntrada option:not(:selected)').attr('disabled',true);
  $('span#msjdeerrorentrada').html("");
  $("#agregarProdEntrada").removeClass("d-none");
  $("#btnGuardarEntradasAlmacen").show();
});

$('#selProdEntAlm').select2({
  placeholder: 'Selecciona un producto',
  ajax: {
    url: 'ajax/entradasalmacen.ajax.php?op=ajaxProductos',
    async: true,
    dataType: 'json',
    delay: 250,
    type: "POST",
    data: function (params) {
      return {
        searchTerm: params.term
       };  
    },
    processResults: function (data) {
      return {
          results: $.map(data, function (item) {
              return {
                  text: item.codigointerno+' - '+item.descripcion,
                  id: item.id,
                  descripcion:item.descripcion
              }
          })
      };
  },    
    cache: true
  },
  minimumInputLength: 1
});

//CONSULTA EXISTENCIA DE PRODUCTO SELECCIONADO
$("#selProdEntAlm").change(function(event){
  event.preventDefault();

  let idalmacen=$("#idAlmacenEntrada").val();
  let tbl_almacen=$( "#idAlmacenEntrada option:selected" ).text();
  let idprod=$("#selProdEntAlm").val();
  // si viene vacio el select2 que regrese en false   |=124
  if($(this).val()=="" || $(this).val()==null || idalmacen==null){       
      return false;	
  }

  //console.log(idprod, idalmacen, tbl_almacen)
  //$('#servicioSelecionado').html($("#selProdEntAlm option:selected").text());
  (async () => {   
    await axios.get('ajax/entradasalmacen.ajax.php?op=consultaExistenciaProd', {
      params: {
        idprod: idprod,
        almacen: tbl_almacen
      }
    })
    .then((res)=>{ 
      if(res.status==200) {
        //console.log(res.data)
        if(res.data==false){
          $("#cantExistenciaAlmacen").val(0);
        }else{
          $("#cantExistenciaAlmacen").val(res.data.cant);
          udeMedida=res.data.medida;
        }
      }          
    }) 

    .catch((err) => {throw err}); 
  
  })();  //fin del async

});

/*============================================================
                AGREGA PRODUCTO SELECCIONADO
============================================================*/
$("#agregaEntradaProd").click(function(event){
    event.preventDefault();
    let cantEntrada=$("#cantEntradaAlmacen").val();
    let idProducto=$("#selProdEntAlm").val();
    let producto=$("#selProdEntAlm option:selected" ).text();       //obtener el texto del valor seleccionado
    idProducto=parseInt(idProducto); 
    cantEntrada=parseFloat(cantEntrada);
       
    //Si no selecciona producto retorna o cantidad
      if(isNaN(idProducto) || isNaN(cantEntrada) || cantEntrada<1 ){
        return true;
      }  
    
    //SEPARA EL CODIGO DEL PROD. SELECCIONADO
    let codigointerno= producto.substr(0, producto.indexOf('-'));
    codigointerno.trim();
      
    //SEPARA LA DESCRIPCION DEL PROD. SELECCIONADO        
    let descripcion= producto.substr(producto.lastIndexOf("-") + 1);
    descripcion.trim();
         
    //let udemedida=udeMedida;
      
    //console.log("prod:",idProducto, "cant:",cantEntrada, "medida:",udeMedida, "codInt:",codigointerno, "descrip:",descripcion);
    
    let encontrado=arrayProductos.includes(idProducto)
      console.log("encontrado:", encontrado)
      if(!encontrado){
        arrayProductos.push(idProducto);
        addProductoEntrada(idProducto, cantEntrada, udeMedida, codigointerno, descripcion);
      }else{
        mensajedeerror();
      }
    
});  

/*==================================================================
ADICIONA PRODUCTOS AL TBODY
==================================================================*/
function addProductoEntrada(...argsProductos){
  //console.log("manyMoreArgs", argsProductos);
  var contenido=document.querySelector('#tbodyentradasalmacen');
  
  contenido.innerHTML+=`
  <tr class="filas" id="fila${argsProductos[0]}">
    <td><button type="button" class="botonQuitar" onclick="eliminarProducto(${argsProductos[0]}, ${argsProductos[1]} )" title="Quitar concepto">X</button></td>
    <td class='text-center'>${argsProductos[0]} <input type="hidden" name="idproducto[]" value="${argsProductos[0]}"</td>
    <td class='text-center'>${argsProductos[3]}</td>
    <td class='text-left'>${argsProductos[4]}</td>
    <td class='text-center'>${argsProductos[2]}</td>
    <td class='text-center'>${argsProductos[1]} <input type="hidden" name="cantidad[]" value="${argsProductos[1]}"</td>
    </tr>
  `;
    renglonesEntradas++;
    cantEntrante+=argsProductos[1];
    evaluaFilas(renglonesEntradas, cantEntrante);
  
    //DESPUES DE AÑADIR, SE INICIALIZAN SELECT E INPUT
    $('#selProdEntAlm').val(null).trigger('change');	
    $("#cantExistenciaAlmacen").val(0);
    $("#cantEntradaAlmacen").val(0);
  
}

//QUITA ELEMENTO 
function eliminarProducto(indice, restarcantidad){
  console.log("entra a eliminar",indice)
  $("#fila" + indice).remove();
  cantEntrante-=restarcantidad;
  renglonesEntradas--;
  removeItemFromArr(arrayProductos, indice)
  evaluaFilas(renglonesEntradas, cantEntrante);
  evaluarElementos();
}

function evaluaFilas(totalRenglon, cantEntrante){
	$("#renglonentradas").html(totalRenglon);
	$("#totalentradasalmacen").html(cantEntrante);
	if(totalRenglon>0){
	  $("#btnGuardarEntradasAlmacen").show();
	  $("#rowEntradaAlma").show();
    }else{
		//$("#btnGuardar").hide();
	}
}

//SI NO HAY ELEMENTOS count SE INICIALIZA
function evaluarElementos(){
  if (!renglonesEntradas>0){
    renglonesEntradas=0;
    $("#btnGuardarEntradasAlmacen, #btnEditEntradasAlmacen").hide();
    $("#rowEntradaAlma, #EditrowEntradaAlma").hide();
  }
}

/*===================================================
ABRIR MODAL PARA SUBIR O VISUALIZAR ARCHIVOS. DESDE EL DATATABLE
===================================================*/
$("#dt-entradasalmacen tbody").on("click", "button.btnUpEntradasAlmacen", function(){
  let idUploadEntradaAlm = $(this).attr("idUploadEntradaAlm");
  let datas=$(this).data("idupentrada")
    
    if(datas > 0){
      //console.log(idUploadEntradaAlm, datas); 
      $('#modalUpEntradasAlmacen').modal('show');
      $("#numIdEntradaAlmacen").val(datas);
      abrirDatatable(datas);
    }
})
/*===================================================*/


/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR ARCHIVOS DE ENTRADA
/*======================================================================*/
$("body").on("submit", "#form_upfile", (event)=>{	
  event.preventDefault();
  event.stopPropagation();
  let sNombreFile = $("#nombre_archivo").val();   //descripción del archivo a subir
  let sUploadedFile = $("#uploadedFile").val();   //nombre del archivo a subir
  let nIdEntrada=$("#numIdEntradaAlmacen").val(); //numero de entrada al almacen
  sUploadedFile=sUploadedFile.replace(/^.*\\/,""); //quitar la ruta, solo nom de archivo
  //console.log(sNombreFile, sUploadedFile, nIdEntrada )
  //validar que no este vacio el input file
  if(sNombreFile==""){
    sNombreFile="SIN DESCRIPCION....";
  }
  subirArchivos(sNombreFile, sUploadedFile, nIdEntrada);

});  
/*======================================================================*/
function subirArchivos(sNombreFile, sUploadedFile, nIdEntrada) {
  $("#uploadedFile").upload('vistas/modulos/subir_archivo.php',{
      descripcion_archivo: sNombreFile,
      nombre_archivo: sUploadedFile,
      numero_entrada: nIdEntrada
  },
  function(respuesta) {
     //console.log(respuesta)
      //Subida finalizada.

        if (respuesta["status"]=== 1) {
          $("#barra_de_progreso").val(0);
          sUploadedFile=respuesta["nombre"];
          let sRutaArchivo=respuesta["rutaarchivo"];
          var datos = new FormData();
          datos.append("descripcion_archivo", sNombreFile);
          datos.append("nombre_archivo", sUploadedFile);
          datos.append("numero_entrada", nIdEntrada);
          datos.append("ruta_archivo", sRutaArchivo);
          axios({ 
            method  : 'post', 
            url : 'ajax/entradasalmacen.ajax.php?op=subirArchivosEntrada', 
            data : datos,
          }) 
          .then((res)=>{ 
            if(res.status==200) {
              console.log(res.data)
  
              $('#subirarchivosentradas').DataTable().ajax.reload(null, false);
              $("#previewImg1").attr("src", "");
               //$("#response").removeClass("d-none");

              //$("#alert1" ).fadeOut( 4500, "linear", completa );
    
            }            
  
          }) 
          .catch((err) => {throw err}); 
  
            mostrarRespuesta(`<strong>Mensaje!</strong> El archivo se ha subido correctamente. <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span></button>`, true);

            $("#nombre_archivo, #uploadedFile").val('');
        } else {
            mostrarRespuesta(`<strong>Mensaje!</strong> El archivo NO se ha podido subir. <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span></button>`, false);

            $("#nombre_archivo, #uploadedFile").val('');
        }
        
  }, function(progreso, valor) {
     console.log(valor)
      //Barra de progreso.
      $("#barra_de_progreso").val(valor);
  });
}
/*======================================================================*/
function mostrarRespuesta(mensaje, ok){

  //$("#respuesta, #response").removeClass('alert-success').removeClass('alert-danger').removeClass('d-none').html(mensaje);
  if(ok){
      $("#respuesta, #response").removeClass('d-none').html(mensaje);
      $("#respuesta, #response").addClass('alert-success');
      setTimeout(() => {
        $("#respuesta, #response").addClass('d-none').removeClass('alert-success');
      }, 3000);
  }else{
      $("#respuesta, #response").removeClass('d-none').html(mensaje);
      $("#respuesta, #response").addClass('alert-danger').addClass('d-block');
      setTimeout(() => {
        $("#respuesta, #response").addClass('d-none').removeClass('alert-danger');
      }, 3000);

  }
}
/*======================================================================*/

/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR DATOS DE ENTRADA
/*======================================================================*/
$("body").on("submit", "#form_entradasalmacen", function( event ) {	
  event.preventDefault();
  event.stopPropagation();
  let formData = new FormData($("#form_entradasalmacen")[0]);   
  //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);} 
  swal({
    title: "¿Está seguro de guardar Entrada?",
    text: "¡Si no lo está pulse Cancelar",
    icon: "warning",
    buttons: ["Cancelar", "Sí, Guardar"],
    dangerMode: false,
  })
  .then((aceptado) => {
    if (aceptado) {
          axios({ 
            method  : 'post', 
            url : 'ajax/entradasalmacen.ajax.php?op=guardarEntradasAlmacen', 
            data : formData, 
          }) 
          .then((res)=>{ 
            if(res.status==200) {
              //console.log(res.data)

              $('#dt-entradasalmacen').DataTable().ajax.reload(null, false);
              $('#modalAgregarEntradasAlmacen').modal('hide')

              $("#alert1").removeClass("d-none");
              $("#alert1" ).fadeOut( 4500, "linear", completa );
    
            }            

          }) 
          .catch((err) => {throw err}); 

    }else{
      return false;
    }
  }); 

});  

function completa() {
  $("#alert1").addClass("d-none")
}

/*===================================================
ENVIA REPORTE DE ENTRADA AL ALMACEN DESDE EL DATATABLE
===================================================*/
$("#dt-entradasalmacen tbody").on("click", "button.btnPrintEntradaAlmacen", function(){
  let idPrintEntrada = $(this).attr("idPrintEntrada");
  console.log(idPrintEntrada); 
    if(idPrintEntrada.length > 0){
     window.open("extensiones/tcpdf/pdf/reporte_entrada.php?codigo="+idPrintEntrada, "_blank");
    }
})


/*===================================================
ELIMINAR SALIDA DE ALMACEN DESDE EL DATATABLE
===================================================*/
$("#dt-entradasalmacen tbody").on("click", "button.btnDelEntradasAlmacen", function(event){
  event.preventDefault();
	let idaborrar = $(this).attr("idDeleteEntradaAlm");
  //console.log(idaborrar);

  swal({
    title: "¿Está seguro de Eliminar Entrada No. "+idaborrar+"? ",
    text: "Si no lo esta puede cancelar la acción!",
    icon: "vistas/img/logoaviso.jpg",
    buttons: ["Cancelar", "Sí, Eliminar"],
    dangerMode: true
  })
   .then((willDelete) => {
    if (willDelete) {

        axios.get('ajax/entradasalmacen.ajax.php?op=deleteIdEntradaAlmacen', {
          params: {
            idaborrar: idaborrar
          }
        })
      
       .then(res => {
        //console.log(res.data);
        if(res.data=="error"){

          swal({
            title: "¡Error!",
            text: 'No fue posible eliminar Entrada, revise existencias!!',
            icon: "error",
            button: "Cerrar"
          })  //fin swal

        }else{
          $('#dt-entradasalmacen').DataTable().ajax.reload(null, false);
          swal({
            title: 'Hecho',
            text: 'Registro fue Eliminado!',
            buttons: false,
            timer: 2000
          })          
        } 
      })

      .catch((err) => {throw err}); 
      
    } else {
      return
    }
  }) 
})

/*************************************************************************** */
function abrirDatatable(identrada){
//console.log(identrada)
tableforFiles=$('#subirarchivosentradas').removeAttr('width').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	  "aServerSide": true,//Paginación y filtrado realizados por el servidor
    "lengthMenu": [ [10, 25, 50,100, -1], [10, 25, 50, 100, "Todos"] ],
    "language": {
      "url": "extensiones/espanol.json",
    },    
    "bAutoWidth": true,
    columnDefs: [
      { width: "75px", targets: 0 },
      { width: "35px", targets: 1 },
      { width: "430px", targets: 2 },
      { width: "100px", targets: 3 },
      { width: "45px", targets: 4 },
      { width: "50px", targets: 5 }
    ],
    //scrollX: true,
    "ajax":
				{
          url: 'ajax/entradasalmacen.ajax.php?op=listarArchivos',
          data: {"idEntrada": identrada},     
					type : "POST",
					dataType : "json",						
          data: function(d){
            d.idEntrada = identrada;
            $('#dt-entradasalmacen').DataTable().ajax.reload(null, false);
          },
					error: function(e){
						console.log(e.responseText);
					}
				},
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	  "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();    
    
}

/*===================================================
ELIMINAR ARCHIVO DE ENTRADA DE ALMACEN DESDE EL DATATABLE
===================================================*/
$("#subirarchivosentradas tbody").on("click", "button.btnEliminaArchivo", function(event){
  event.preventDefault();
	let idaborrar = $(this).data("eliminar");
  console.log(idaborrar);

  swal({
    title: "¿Está seguro de Eliminar Archivo No. "+idaborrar+"? ",
    text: "Si no lo esta puede cancelar la acción!",
    icon: "vistas/img/logoaviso.jpg",
    buttons: ["Cancelar", "Sí, Eliminar"],
    dangerMode: true
  })
   .then((willDelete) => {
    if (willDelete) {

        axios.get('ajax/entradasalmacen.ajax.php?op=deletefile', {
          params: {
            idaborrar: idaborrar
          }
        })
      
       .then(res => {
        console.log(res.data);
        if(res.data=="error"){

          swal({
            title: "¡Error!",
            text: 'No fue posible eliminar archivo, revise!!',
            icon: "error",
            button: "Cerrar"
          })  //fin swal

        }else{
          $('#subirarchivosentradas').DataTable().ajax.reload(null, false);
          swal({
            title: 'Hecho',
            text: 'Registro fue Eliminado!',
            buttons: false,
            timer: 2000
          })          
        } 
      })

      .catch((err) => {throw err}); 
      
    } else {
      return
    }
  }) 
})

/************************************************************/
/*============================================================
DESCARGAR ARCHIVO DE ENTRADA DE ALMACEN DESDE EL DATATABLE
=============================================================*/
$("#subirarchivosentradas tbody").on("click", "button.btnVerArchivo", function(event){
  event.preventDefault();
	let idvisualizar = $(this).data("visualizar");
  console.log(idvisualizar);

  axios.get('ajax/entradasalmacen.ajax.php?op=visualizarArchivo', {
    params: {
      idvisualizar: idvisualizar
    }
  })

 .then(res => {
  if(res.data=="error"){
    //console.log(res.data);
  }else{
    //console.log(res.data);
    let file1 = res.data.nombre_archivo;
    let rutaarchivo=`./vistas/${res.data.ruta_archivo}${file1}`;
    const [ext, ...fileName] = file1.split('.').reverse();
    if(ext=="pdf"){
      VerPDF(rutaarchivo)
    }else if(ext=="PNG" || ext=="png" || ext=="JPEG" ||ext=="jpeg" || ext=="jpg" || ext=="JPG"){
      //console.log(rutaarchivo);
      verImagen(rutaarchivo,file1)
    }else if(ext=="TIF" || ext=="tif"){
      verImgTif(rutaarchivo,file1)
    }else{
      swal({
        title: "¡Lo siento mucho!!",
        text: `No es posible abrir archivo .${ext}!!`,
        icon: "error",
        button: "Cerrar",
        timer:000
      })  //fin swal

    }
  } 
})

})

/********************************************************************************* */
function verImgTif(ruta,file1){
  ventana=window.open('','ventana','resizable=yes,scrollbars=yes,width=1000,height=750');
  ventana.document.write(`<html><head><title> NUNOSCO </title></head> <object width=200 height=200  data='./vistas/entradascarso/118_1643065942_ENTRADA DEVOLUCION CARSO.tif' type="image/tif"><param name="negative" value="yes"></object>`)
} 
function verImagen(ruta,file1)	{

        ventana=window.open('','ventana','resizable=yes,scrollbars=yes,width=1000,height=750');

        ventana.document.write('<html><head><title> NUNOSCO </title></head><body style="overflow-y: auto;" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" scroll="yes" onUnload="opener.cont=0"> <div align="center" style="background-color:lightblue;"> <a href="javascript:this.close()">Cerrar</a> <a href="' + ruta + '" download="' + file1 + '" style="color:blue;"> - Descargar archivo</a> </div> <br> <img src="' + ruta + '" onLoad="opener.redimensionar(this.width, this.height)">')
        ventana.document.close()
}
    
function redimensionar(ancho, alto){
  ventana.resizeTo(ancho+12,alto+50)
  ventana.moveTo((screen.width-ancho)/2,(screen.height-alto)/2) //centra la ventana. quitar si no se quiere centrar el popup
}

function VerPDF(viewfile){
  window.open(viewfile, 'resizable,scrollbars');
}

/********************************************************************/
function previewFile1() {
  var preview = document.querySelector(".previewImg1");
  var file    = document.querySelector('input[type=file]').files[0];
  var reader  = new FileReader();

  reader.onloadend = function () {
    preview.src = reader.result;
  }

  if (file) {
    reader.readAsDataURL(file);
  } else {
    preview.src = "";
  }
}
/********************************************************************/

/*============================================================
EDIT ARCHIVO DE ENTRADA DE ALMACEN DESDE EL DATATABLE
=============================================================*/
$("#subirarchivosentradas tbody").on("click", "button.btnEditArchivo", function(event){
  event.preventDefault();

  swal({
    title: "¡Lo siento!!",
    text: `Aun estamos trabajando en esta opción`,
    icon: "warning",
    button: "Cerrar",
    timer: 4000
  })  //fin swal


})

/*=============================================================*/

init();

