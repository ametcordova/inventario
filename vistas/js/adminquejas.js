$( "#modalAgregarLORG" ).draggable();
var captRangoFecha;
var modalEvento='';

function init(){
/*=============================================
  VARIABLE LOCAL STORAGE
  =============================================*/
  if(localStorage.getItem("captRangoFecha") != null){
    $("#daterange-btnOS span").html(localStorage.getItem("captRangoFecha"));
  }else{
    iniciarangodefecha()
  }
    listarQuejas();   //LISTAR OS EN EL DATATABLE
}
/*****************************************************/

/*****************************************************/
/* CUANDO YA CARGO EL HTML*/
/*****************************************************/
$(document).ready(function(){
  $('#datetimepicker1, #datetimepicker3').datetimepicker({
      format: 'LT'
  });
});
/*****************************************************/
function listarQuejas(){

  let rangodeFecha = $("#daterange-btnOS span").html();
  if(rangodeFecha==undefined || rangodeFecha==null){
    var {fecha1, fecha2 } = getFecha()
    //console.log('fecha hoy:',fecha1,fecha2);
  }else{
    let arrayFecha = rangodeFecha.split(" - ", 2);
    var {fecha1, fecha2 } = getFecha(arrayFecha);
    //console.log('rango de fecha:',fecha1,fecha2);
  }	   

  tblQuejas=$('#DatatableQuejas').dataTable({
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      "lengthMenu": [ [10, 15, 25, 50,100, -1], [10, 15, 25, 50, 100, "Todos"] ],
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
      "pagingType": "full_numbers",
          dom: '<clear>Bfrtip',
          buttons: [
              {
               text: 'Copiar',
               extend: 'copy'
               },
              'excelHtml5',
        {
              extend: 'print',
              text: 'Imprimir',
              className: 'btn btn-success btn-sm',
              autoPrint: false            //TRUE para abrir la impresora
          },
          {
            text: 'Imp. Selección',
            className: 'btn btn-dark btn-sm',
            action: function ( e, dt, node, config ) {
              //printselec();
            }
          }        
          ],
          initComplete: function () {
            var btns = $('.dt-button');
            btns.removeClass('dt-button');
            btns.addClass('btn btn-success btn-sm');
          },  
          "columnDefs": [
            {"className": "dt-center", "targets": [1,3,4,5,8,9,10]},
            {"className": "dt-left", "targets": [6,7]}				//"_all" para todas las columnas
            ],    
            select: false,     //se puso a false para poder seleccionar varios filas. true=1 fila
            scrollX: true,
          "ajax":
            {
              url: 'ajax/adminquejas.ajax.php?op=listar',
              data: {"fecha1":fecha1,  "fecha2": fecha2},     
              type : "POST",
              dataType : "json",
              // success: function(data){
              //   console.log(data);
              // },					
              error: function(e){
                console.log(e.responseText);
              }
            },
      "bDestroy": true,
      "iDisplayLength": 15,//Paginación
      "order": [[ 0, 'desc' ], [ 8, 'desc' ]] //Ordenar (columna,orden)
    }).DataTable();      

}
/***********************************************************************/
/*==================================================================*/
//DOBLE click para visualizar
/*==================================================================*/
$("#DatatableQuejas tbody").on("dblclick", "tr",  function(){
  $(this).toggleClass('selected');
    let id=(tblQuejas.row( this ).data()[0]);
    $("#btnGuardarLORG").hide();
    verQueja(id, isedit=false);  
});
/*==================================================================*/

/*===================== VISUALIZAR EN PANTALLA ======================*/
$("#DatatableQuejas tbody").on("click", ".btnVerQueja", function(){  
  let id = $(this).attr("idQueja");
  $("#btnGuardarLORG").hide();
  verQueja(id, isedit=false);
})
/*==================================================================*/
/*=  FUNCION PARA TRAER DATOS DE LA QUEJA   */
/*==================================================================*/
function verQueja(id, isedit){
let datos = new FormData();
datos.append("id", id);

    axios({
      method: 'post',
      url: 'ajax/adminquejas.ajax.php?op=verqueja',
      data: {id: id}

    })
    .then(function (response) {
      //console.log(response.data[0]);     // handle success
      tplverqueja(response.data, isedit);
    })
    .catch(function (error) {
      console.log(error);      // handle error
    })
    .then(function () {
      console.log("siempre responde");    // always executed
    });  

    modalEvento=new bootstrap.Modal(document.getElementById('modalAgregarLORG'),{ keyboard:false });
    modalEvento.show();
  
    //$('#modalAgregarLORG').modal({show: true}); 
}
/*======================================================================*/
/*=  FUNCION PARA VISUALIZAR QUEJA EN VENTANA MODAL  */
/*======================================================================*/
function tplverqueja(data, isedit){
  $('.headertitle').html("No. de Queja: "+data[0].id);
  $("#ctrlid").val(data[0].id);
  $("#fechacapt").val(data[0].fecha);
  $("#numeroos").val(data[0].os);
  $("#numtelefono").val(data[0].telefono);
  $("#distritoos").val(data[0].distrito);
  $("#nombrecontrato").val(data[0].cliente);
  $("#motivo").val(data[0].motivo);
  $("#operador").val(data[0].operador);
  $("#foliooci").val(data[0].folio_oci);
  $("#datetimepicker1").val(data[0].inicio_llamada);
  $("#datetimepicker3").val(data[0].fin_llamada);
  $("#totalmin").val(data[0].minutos);
  $("#altaobservaciones").val(data[0].observaciones);

  if(data[0].estatus == "1"){
    $('#llamarop').iCheck('check');
  }else{
    $('#diademaop').iCheck('check');
  }

  let ctrlid=parseInt($('#ctrlid').val());

  if(!isedit && ctrlid>0){   //si no es edicion y el id es mayor a 0
      $('.flag').prop('disabled', true);  
  }else{
      $('.flag').prop('disabled', false);
  }
  
  $("#numeroos").focus();

}
/*========================================================================*/

/*===================== VISUALIZAR DATOS EN PANTALLA PARA EDITAR ======================*/
$("#DatatableQuejas tbody").on("click", ".btnEditQueja", function(){  
  let id = $(this).attr("idQueja");
  $("#bandera").val(id);
  verQueja(id, isedit=true);
})
/*========================================================================*/

/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR o ACTUALIZAR QUEJA
/*======================================================================*/
$("body").on("submit", "#formularioAgregaLORG", function( event ) {	
  event.preventDefault();
  event.stopPropagation();	
  
    let formData = new FormData($("#formularioAgregaLORG")[0]);
    //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);}
    let accion='';
    let bandera=parseInt(document.getElementById('bandera').value);
    accion=bandera==0?'guardarQueja':'actualizarQueja';
        
    //CODIFICAR INSERTAR EN BD
    axios({ 
      method  : 'post', 
      url : 'ajax/adminquejas.ajax.php?op='+accion, 
      data : formData, 
    }) 
    .then((res)=>{ 
      if(res.status==200 && res.data=="ok") {
        //console.log(res.data, res.status, res.statusText)
        if(bandera==0){
          $('#modalAgregarLORG').modal('hide')
          $("#ctrlid").val(0);
        }else{
          $("#bandera").val(0);
          $("#ctrlid").val(0);
          $('#modalAgregarLORG').modal('hide')
          modalEvento.hide();
        }

        $('#DatatableQuejas').DataTable().ajax.reload(null, false);

      }else{
        console.log(res); 
        alert("Error!! Registro duplicado.")  
      }            
      
    }) 

    .catch((err) => {
      alert("Registro No Guardado.")
      throw err
    }); 
 
});

/*===========================================================================
    Borrar Registro.
===========================================================================*/
$("#DatatableQuejas tbody").on("click", ".btnBorraQueja", function(){

  let id = $(this).attr("idQueja");
  let datos = new FormData();
  datos.append("id", id);

    swal({
      title: "¿Está seguro de Borrar Registro #"+id+"? ",
      text: "Si no lo esta puede cancelar la acción!",
	    icon: "config/icon_del.gif",
      className: "buttonstyle",
      buttons: {
        cancel: "No, Cancelar",
        defeat: "Sí, Borrar",
      },      
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        axios('ajax/adminquejas.ajax.php?op=borrar', {
          method: 'POST',
          data: datos
         })
         .then((res)=>{ 
          if(res.data.respuesta=="ok") {
            $('#DatatableQuejas').DataTable().ajax.reload(null, false);
            mensajenotie('success', 'Registro borrado correctamete!', 'bottom');
          }
        }) 
        .catch((err) => {
          alert("Error!! Registro No Borrado.")
          throw err;
        }); 
      } else {
        return
      }
    });
})
/*==============================================================================*/   

/*======================================================================*/
$('#datetimepicker1,#datetimepicker3').on( 'keyup change blur', function () {

  let initialdate=enddate=moment().format('YYYY-MM-DD');
  let start_time = $('#datetimepicker1').val();
  let end_time = $('#datetimepicker3').val();

  let datetimeA = moment(initialdate + ' ' + start_time);
  let datetimeB = moment(enddate + ' ' + end_time);

  let datetimeC = datetimeB.diff(datetimeA, 'minutes');

  $("input#totalmin").val(datetimeC);

});
/***********************************************************************/



/*****************************************************/
$("#modalAgregarLORG").on('hidden.bs.modal', ()=> {
  $('.flag').prop('disabled', false);
  $('.headertitle').html("");
  $("#bandera").val(0); 
  $("#ctrlid").val(0); 
  $("#btnGuardarLORG").show();
  $('input#llamarop').iCheck('uncheck');
  $('input#diademaop').iCheck('uncheck');
  $('#formularioAgregaLORG')[0].reset();
})
/*****************************************************/


init();