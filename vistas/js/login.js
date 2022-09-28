/**
 * https://codehunter.cc/a/javascript/jquery-ajax-file-upload
 * https://qastack.mx/programming/2320069/jquery-ajax-file-upload
 * https://gist.github.com/optikalefx/4504947
 * https://brocante.dev/es/questions/2320069?page=3
 */

 $("#modalViewGuia").draggable({
   handle: ".modal-header"
 });


// $(document).ready(function() {
//     $(".btnViewGuia").on("click",function() {
//         alert("Modal Mostrada");
//     });
    
//     $('#modalViewGuia').on('show.bs.modal', function (e) {
//         alert("Modal Mostrada con Evento de Boostrap");
//       })
//       $('#modalViewGuia').on('hidden.bs.modal', function (e) {
//         alert("Modal Cerrada");
//       })
// } );

  

/*======================================================================*/
// MODAL PARA VISUALIZAR IMAGEN o archivo PDF
/*======================================================================*/
$("body").on("click", ".btnViewGuia", function(){
    $('#modalViewGuia').modal('handleUpdate')
    let html=document.querySelector('.ForViewGuia');
    $('.ForViewGuia').empty();

  let dataviewfile="archivos/MIGRACION_FTTH.pdf";
  //console.log(dataviewfile)
  
  $('#CenterTitleGuia').html("");
  $('#CenterTitleGuia').html('GUIA DE: OCUPACION DE TERMINAL - TIPO DE TAREA - ETAPAS DE TAREA - LIMP. DE PUERTO');

    html.innerHTML+=`
      <object type="application/pdf" data='${dataviewfile}' width="100%" height="465px" style="height: 62vh;"></object>
    `;    
  })
/*======================================================================*/
