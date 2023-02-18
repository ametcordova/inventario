const { ajax } = rxjs.ajax;
//const { fromEvent } = rxjs;
var FechDev;
var FechDev2;
$("#modalAgregarEmpresa").draggable({
    handle: ".modal-header"
});

//Función que se ejecuta al inicio
function init(){
  //document.querySelector('#nvoFormaPago').click();   //simula un click para funcionar bien RXJS
  
  dt_ListarEmpresas();

}

// ========= LISTAR EN EL DATATABLE REGISTROS DE LA TABLA FACTURAINGRESO================
function dt_ListarEmpresas(){
 
// B: Botonera de exportaciones de datos.
// f: Campo de búsqueda.
// i: Información sobre los registros.
// t: Tabla completa.
// r: El preloader de «Cargando…».
// l: Desplegable de mostrar x registros
// p: Paginación de los registros.
  tblEmpresas=$('#dt-empresas').dataTable(
	{
	"aProcessing": true,//Activamos el procesamiento del datatables
	"aServerSide": true,//Paginación y filtrado realizados por el servidor
    "lengthMenu": [ [10, 15, 25, 50,100, -1], [10, 15, 25, 50, 100, "Todos"] ],
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
        },
        ],
        initComplete: function () {
          var btns = $('.dt-button');
          btns.removeClass('dt-button');
          btns.addClass('btn btn-success btn-sm');
        }, 
        "columnDefs": [
          {"width:":"5px", "className": "dt-center", "targets": [0]},
          {"width:":"10px", "className": "dt-left", "targets": [1]},
          {"width:":"04px", "className": "dt-left", "targets": [2]},
          {"width:":"20px", "className": "dt-left", "targets": [3]},
          {"width:":"20px", "className": "dt-left", "targets": [4]},
          {"width:":"20px", "className": "dt-center", "targets": [5]},
          {"width:":"20px","className": "dt-left", "targets": [6]},     //correo
          {"width:":"5px","className": "dt-center", "targets": [7]},   //status
          {"width:":"5px","className": "dt-center", "targets": [8]},   //status
          {"width:":"20px","className": "dt-center", "targets": [9]},	//botones
          ],
		"ajax":
				{
          url: 'ajax/gestion-empresas.ajax.php?op=listarEmpresas',
          data: {"status": 1},     
					type : "POST",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);
					}
				},
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
	}).DataTable();    
} 
/****************************************************************************** */

/**************************************************************** */
//TRAER EL ULTIMO ID GUARDADO
async function UltimoNumIdFactura(){
  let respId=0;
  let respFolio=0;
  let response = await fetch("ajax/facturaingreso.ajax.php?op=obtenerUltimoNumero");
  let result = await response.json();
  console.log(result.id);
  if(result.id===null){
    $("#numidfactura").val(1);
  }else{
    respId=parseInt(result.id)+1;
    $("#numidfactura").val(respId);
  }
  console.log(result.folio);
  if(result.folio===null){
    $("#nvofolio").val(1);
  }else{
    respFolio=parseInt(result.folio)+1;
    $("#nvofolio").val(respFolio);
  }
}

/**************************************************************** */
$('#idEmpresa').on('change', ()=> {
  $("#idEmpresa").css({"background-color": "white", "color":"black"});
  let idempresa=$("#idEmpresa").val();       //obtener el texto del valor seleccionado
  let rfcemisor=$("#idEmpresa option:selected").text();       //obtener el texto del valor seleccionado
  rfcemisor= rfcemisor.substr(0, rfcemisor.indexOf('-'));
  rfcemisor=rfcemisor.trim();
  //console.log(rfcemisor)
  if(rfcemisor==='' || ($('#idEmpresa').val()==='')){
    $("#idEmpresa").css({"background-color": "red", "color":"yellow"});
    $("select#idEmpresa").focus();
    return false;
  }

  (async () => {   
    await axios.get('ajax/facturaingreso.ajax.php?op=getDatosEmpresa', {
      params: {
        idempresa: idempresa,
      }
    })
    .then((res)=>{ 
      if(res.status==200) {
        //console.log(res.data)
        if(res.data===false){
          $("#tasaimpuesto").val('');
          $("#idregimenfiscalemisor").val('');
          $("#codpostal").val('');
          $("#idexportacion").val('');
          $("#btnGuardarFacturar").hide();
        }else{
          $('input[name=rfcemisor]').val(res.data.rfc);
          $('input[name=tasaimpuesto').val(res.data.iva);
          $('input[name=idregimenfiscalemisor').val(res.data.regimenfiscalemisor);
          $('input[name=codpostal').val(res.data.codpostal);
          $('input[name=idexportacion').val(res.data.id_exportacion);
          $('input[name=serie').val(res.data.seriefacturacion);
        }
      }          
    }) 

    .catch((err) => {throw err}); 
  
  })();  //fin del async  
  
});
/**************************************************************** */

/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR DATOS DE FACTURA
/*======================================================================*/
$("body").on("submit", "#formularioFactura", function( event ) {	
  event.preventDefault();
  event.stopPropagation();
  let formData = new FormData($("#formularioFactura")[0]);   
  // for (var pair of formData.entries()){
  //   console.table(pair[0]+ ', ' + pair[1]);
  // } 
  if($("#nvoregimenfiscalreceptor").val().length < 1){
    return true;
  }
  swal({
    title: "¿Está seguro de guardar Factura?",
    text: "¡Si no lo está pulse Cancelar",
    icon: "warning",
    buttons: ["Cancelar", "Sí, Guardar"],
    dangerMode: false,
  })
  .then((aceptado) => {
    if (aceptado) {
          axios({ 
            method  : 'post', 
            url : 'ajax/facturaingreso.ajax.php?op=guardar', 
            data : formData, 
          }) 
          .then((res)=>{ 
            if(res.status===200) {
              //console.log(res.data['status'])
              //console.log(res.data.status, res.data.data, res.data.msg)
              $('#dt-FacturaIngreso').DataTable().ajax.reload(null, false);
              $('#modalCrearFactura').modal('hide')

              swal({
                title: "¡Registro Guardado!",
                text: `${res.data.msg}`,
                icon: "success",
                button: "Cerrar",
                timer:3000
              })  //fin swal
        
            }else{
              console.log(res.data, res.status)
              swal({
                title: "¡Lo siento mucho!!",
                text: `No fue posible Guardar. ${res.data.msg}!!`,
                icon: "error",
                buttons: false,
                timer: 3000
              })  //fin swal
        
            }          

          }) 
          .catch((err) => {throw err}); 

    }else{
      return false;
    }
  }); 

});  
/*======================================================================*/


/*===================================================
CANCELAR FACTURA
===================================================*/
$("#dt-FacturaIngreso tbody").on("click", "button.btnCancelFact", function(){
  
  let dataidfact        = $(this).data("idfact");
  let datarfcemisor     = $(this).data("rfcemisor");
  let datarfcreceptor   = $(this).data("rfcreceptor");
  let datauuid          = $(this).data("uuid");
  let dataimporte       = $(this).data("importe");
  let datafechatimbrado = $(this).data("fechatimbrado");
  let datafechacancelado= $(this).data("fechacancelado");
  console.log(dataidfact, datarfcemisor, datarfcreceptor, datauuid, dataimporte, datafechatimbrado, datafechacancelado);
  if(datafechacancelado.length>0){
    alert("Factura Ya está cancelado")
    return
  }
  $('#container').waitMe({
    effect : 'timer',
    text : 'Espere por favor.',
    bg : 'rgba(255,255,255,0.7)',
    color : '#000',
    maxSize : '50',
    textPos : 'horizontal',
    fontSize: ''    //default, '18px'
   });  
  (async () => {
    await axios.get('ajax/facturaingreso.ajax.php?op=CancelarFact', {
      params: {
        dataidfact: dataidfact,
        datarfcemisor: datarfcemisor,
        datarfcreceptor: datarfcreceptor,
        datauuid: datauuid,
        dataimporte: dataimporte,
        datafechatimbrado: datafechatimbrado
      }
    })

    .then((res)=>{ 
      console.log(res.data.data.code)
      if(res.data.data.code==200 || res.data.data.code==201) {
        $('#dt-FacturaIngreso').DataTable().ajax.reload(null, false);
        $('#container').waitMe("hide");
        swal({
          title: "¡Factura cancelada!",
          text: `Mensaje .${res.data.data.message}!!`,
          icon: "success",
          buttons: false,
          timer: 5000
        })  //fin swal
        
      }else{
          //console.log(res.data)
          $('#container').waitMe("hide");
          swal({
            title: "¡Lo sentimos mucho!",
            text: `Mensaje .${res.data.data.message}!!`,
            icon: "error",
            buttons: false,
            timer: 5000
          })  //fin swal
      }          
    }) 

    .catch((err) => {throw err}); 
  
  })();  //fin del async

})

/**************************************************************** */
//AL ABRIR EL MODAL TRAER EL ULTIMO NUMERO
$('#modalCrearFactura').on('show.bs.modal', function (event) {
  UltimoNumIdFactura();		//TRAE EL SIGUIENTE NUMERO 
  renglonesfacturar=cantidadfacturar=0;
	$("#renglonentradas, #totalitems, #subtotal, #impuesto, #total").html("");
	$("#totalentradasalmacen").html("");
})
/**************************************************************** */
/*================ AL SALIR DEL MODAL RESETEAR FORMULARIO ==================*/
$("#modalCrearFactura").on('hidden.bs.modal', ()=> {
  $('#formularioFactura')[0].reset();                //resetea el formulario
  $("#nvacantidad").val(0);                   //inicializa campo existencia
  $("#nvoFormaPago").val(0);                   //inicializa campo
  $("#nvopreciounitario").val(0);                     //inicializa campo 
  $("#nvoconcepto").val("");                     //inicializa campo 
  $("#tabladedetalles").empty();                   //vacia tbody
  $('#cveprodfactura').val(null).trigger('change');      //inicializa el select de productos
  arrayProductos["length"]=0;                      //inicializa array
  sessionStorage.clear();   // Eliminar todas las claves de sesiones
  $("#renglonentradas, #totalitems, #subtotal, #impuesto, #total").html("");
  renglonesfacturar=cantidadfacturar=cantidadimporte=sumasubtotal=sumatotal=0;
});


init();

