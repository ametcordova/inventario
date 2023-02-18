$("#modalAgregarCliente, #modalEditarCliente").draggable({
      handle: ".modal-header"
});
//const { fromEvent } = rxjs;
const { map} = rxjs.operators;
const { ajax } = rxjs.ajax;

/*********************CON JS************************************ */
$(document).ready(function() {
  document.getElementById('nuevoRegFiscal').addEventListener('click', getRegFiscal);
  document.getElementById('editaRegFiscal').addEventListener('click', editRegFiscal);
});

function getRegFiscal(){
  let $select = $('#nuevoRegFiscal');
  $select.empty();
  fetch('config/catalogosat/c_RegimenFiscal.json')      //http://jsfiddle.net/MuGj7/
  .then((res) => res.json())
  .then((data) => {
      $.each(data, function(i, val) {
        $select.append('<option value='+data[i].id + '>' + data[i].id+'-'+data[i].descripcion + '</option>');
      });
  })
}
/******************************************************** */
function editRegFiscal(idregfiscal){
  let $select = $('#editaRegFiscal');
  $select.empty();
  fetch('config/catalogosat/c_RegimenFiscal.json')      //http://jsfiddle.net/MuGj7/
  .then((res) => res.json())
  .then((data) => {
      $.each(data, function(i, val) {
        if(data[i].id==idregfiscal){
          $select.append('<option selected value='+data[i].id + '>' + data[i].id+'-'+data[i].descripcion + '</option>');
        }else{
          $select.append('<option value='+data[i].id + '>' + data[i].id+'-'+data[i].descripcion + '</option>');
        }
      });
  })
}
/*********************CON RxJS************************************ */
const catFormaPago = `config/catalogosat/c_FormaPago.json`;
const users = ajax(catFormaPago);
const searchBtnElement = document.getElementById('nuevaFormaPago');  
// Search button observable
const click$ = fromEvent(searchBtnElement,  'click');
click$.subscribe({
  next: (e) => formasdepago()
});

function formasdepago(){
  users.subscribe(
    //res => console.log(res.response),
    res => recorrerjson1(res.response),
    err => console.error(err)
  );
}
/************************************************************ */
function recorrerjson1(data){
    let $nuevaformapago = $('#nuevaFormaPago');
    $nuevaformapago.empty();
    $.each(data , function(i, val) {
      $nuevaformapago.append('<option value='+data[i].id + '>' + data[i].id+'-'+data[i].descripcion + '</option>');
    })
}
/************************************************************ */
function editformapago(idformadepago){
  let $editaformapago = $('#editaFormaPago');
  $editaformapago.empty();
  fetch('config/catalogosat/c_FormaPago.json')      //http://jsfiddle.net/MuGj7/
  .then((res) => res.json())
  .then((data) => {
    $.each(data , function(i, val) {
      if(data[i].id==idformadepago){
        $editaformapago.append('<option selected value='+data[i].id + '>' + data[i].id+'-'+data[i].descripcion + '</option>');
      }else{
        $editaformapago.append('<option value='+data[i].id + '>' + data[i].id+'-'+data[i].descripcion + '</option>');
      }
    });
  })
}
/*********************CON RxJS************************************ */
const catMetodoPago = `config/catalogosat/c_MetodoPago.json`;
const metodopago = ajax(catMetodoPago);
const searchMetodoPago = document.getElementById('nuevoMetodoPago');  
// Search button observable
const clickMP$ = fromEvent(searchMetodoPago,  'click');
clickMP$.subscribe({
  next: (e) => metodosdepago()
});

function metodosdepago(){
  const subscribe = metodopago.subscribe(
    //res => console.log(res.response),
    res => recorrerjson2(res.response),
    err => console.error(err),
    complete => console.log("We have lift off"),
  );
}

function recorrerjson2(data){
    let $nuevometodopago = $('#nuevoMetodoPago');
    $nuevometodopago.empty();
    $.each(data , function(i, val) {
      $nuevometodopago.append('<option value='+data[i].id + '>' + data[i].id+'-'+data[i].descripcion + '</option>');
    })
}
/************************************************************ */
function editmetodopago(idmetodopago){
  let $editametodopago = $('#editaMetodoPago');
  $editametodopago.empty();
  fetch('config/catalogosat/c_MetodoPago.json')
  .then((res) => res.json())
  .then((data) => {
    $.each(data , function(i, val) {
      if(data[i].id==idmetodopago){
        $editametodopago.append('<option selected value='+data[i].id + '>' + data[i].id+'-'+data[i].descripcion + '</option>');
      }else{
        $editametodopago.append('<option value='+data[i].id + '>' + data[i].id+'-'+data[i].descripcion + '</option>');
      }
    });
  })
}
/*=============================================
EDITAR CLIENTE
=============================================*/
$(".DTClientes").on("click", ".btnEditarCliente", function(){

	var idCliente = $(this).attr("idCliente");

	var datos = new FormData();
    datos.append("idCliente", idCliente);
    $.ajax({

      url:"ajax/clientes.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta, status){
      console.table(respuesta);
        //var dateString = respuesta["fecha_nacimiento"];
        //console.log(moment(dateString).format('DD/MM/YYYY'));
      	$("#idCliente").val(respuesta["id"]);
        $("input[name='editaCliente']").val(respuesta["nombre"]);
        $("input[name='editaRFC']").val(respuesta["rfc"]);
        $("input[name='editaCurp']").val(respuesta["curp"]);
	      $("input[name='editaEmail']").val(respuesta["email"]);
	      $("input[name='editaTelefono']").val(respuesta["telefono"]);
	      $("input[name='editaDireccion']").val(respuesta["direccion"]);
	      $("input[name='editaNumInt']").val(respuesta["num_int_ext"]);
        $("input[name='editaColonia']").val(respuesta["colonia"]);
        $("input[name='editaCP']").val(respuesta["codpostal"]);
        $("input[name='editaCiudad']").val(respuesta["ciudad"]);
        $("[name='editaEstado']").val(respuesta["estado"]);   //no funciona escibiendolo con INPUT
        let idregfiscal=respuesta["regimenfiscal"];
        let idformadepago=respuesta["formadepago"];
        let idmetodopago=respuesta["metodopago"];
        editRegFiscal(idregfiscal)
        editformapago(idformadepago)
        editmetodopago(idmetodopago)
	      $("input[name='editaActividadEconomica']").val(respuesta["act_economica"]);
        $("[name='editaUsoCFDI']").val(respuesta["id_usocfdi"]);
	      $("input[name='editaFechaCreacion']").val(respuesta["fecha_creacion"]);
	  }

  	})

})

/*=============================================
ELIMINAR CLIENTE
=============================================*/
$(".DTClientes").on("click", ".btnEliminarCliente", function(){

	var idCliente = $(this).attr("idCliente");

swal({
  title: "¿Está seguro de borrar el cliente?",
  text: "¡Si no lo está puede cancelar la acción!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
      window.location = "index.php?ruta=clientes&idCliente="+idCliente;
  };
});    
    
})

/*=============================================*/
$('.DTClientes').DataTable({
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
        "columnDefs": [
          {"className": "dt-center", "targets": [6,7]},
          ],
  "drawCallback": function( settings ) {
      $('ul.pagination').addClass("pagination-sm");
 }        
});