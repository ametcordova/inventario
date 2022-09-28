/**
 * https://codehunter.cc/a/javascript/jquery-ajax-file-upload
 * https://qastack.mx/programming/2320069/jquery-ajax-file-upload
 * https://gist.github.com/optikalefx/4504947
 * https://brocante.dev/es/questions/2320069?page=3
 */
var verpdf=document.querySelector('#viewpdf');
var verimg=document.querySelector('#viewimg');
var lIs_Public = 0;
var table;
var pvw = document.querySelector('#preview');

$("#modalViewFile").draggable({
  handle: ".modal-header"
});


$(document).ready(function() {
    var id_user = document.getElementsByName("idDeUsuario")[0].value;
    //console.log(id_user)
    table = $('#TablaRepositorio').dataTable( {
      stataSave: true,
		aProcessing: true,//Activamos el procesamiento del datatables
	    aServerSide: true,//Paginación y filtrado realizados por el servidor
        lengthMenu: [ [10, 25, 50,100, -1], [10, 25, 50, 100, "Todos"] ],
        language: {
        url: "extensiones/espanol.json"},
        scrollY:        "55vh",
        scrollX: true,
        scrollCollapse: true,
        paging:         true,
        bAutoWidth: false, 
        aoColumns : [ 
                        { sWidth: '4%' }, 
                        { sWidth: '25%' }, 
                        { sWidth: '30%' }, 
                        { sWidth: '10%' },
                        { sWidth: '8%' },
                        { sWidth: '6%' },
                        { sWidth: '8%' },
                        { sWidth: '9%' }
                    ],
                    columnDefs: [
                        {"className": "dt-left", "targets": [0,1,2,6,7]},
                        {"className": "dt-center", "targets": [3,4,5]}
                    ],
                
                    "ajax":{
                        url: 'ajax/repositorio.ajax.php?op=listsfiles',
                        data:{id_user: id_user},
                        type : "get",
                        dataType : "json",						
                        error: function(e){
                          console.log(e.responseText);
                        }
                      },

                      "bDestroy": true,
                      "iDisplayLength": 10,//Paginación
                      "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
     }).DataTable();    

     $(window).on('resize', function() {
      $('#TablaRepositorio').css('width', '100%');
      table.draw(true);
    });  
  
                  
} );

/******************************************************************
* Detectar que tipo de archivo es para previsualizar. imagen y PDF
******************************************************************/
document.querySelector('#uploadFile').addEventListener('change', () => {
    let peso_ok=(1024*1024)*4;
    let xfile = document.querySelector('#uploadFile').files[0];
    
    let peso=formatoBytes(xfile.size);          //obtenemos el peso del archivo
    let wext=getFileExtension(xfile.name);      //obtenemos la extensión del archivo
    let wight=(xfile.size>peso_ok)?true:false;  //comparamos peso del archivo con lo permitido

    console.table( xfile.name, xfile.type, xfile.size, wext );
    //console.log(xfile.name, formatoBytes(xfile.size), xfile.type)
    
  if(xfile.type=='application/pdf'){

    pdffile_url=URL.createObjectURL(xfile)+"#toolbar=true&navpanes=true&scrollbar=true";

    pvw.innerHTML=`<div class="col-md-3 float-left mb-2" id="viewimg">
      <object type="application/pdf" data="" width="380" height="300" id="viewer"></object></div>
      <div class="col-md-6 float-left text-left mb-4">
        <p class="text-bold">Nombre archivo: ${xfile.name}</p>
        <p class="text-bold">Peso del Archivo: ${peso} </p>
        <p class="text-bold">El archivo ${wight ? "<label class='text-danger'>es demasiado pesado</label>" : "es óptimo para subirlo"} </p>
      </div>
      <div class="col-md-3 float-right mb-2">.</div>`;
    $('#viewer').attr('data',pdffile_url);
    
  }else{     

    if(wext=="xlsx" || wext=="xls" || wext=="zip" || wext=="ZIP" || wext=="xml" || wext=="XML" || wext=="docx" || wext=="doc" || wext=="rar" || wext=="RAR" || xfile.type=="video/mp4" || wext=="mp4"){
      pvw.innerHTML=`<div class="col-md-3 float-left mb-2" id="viewimg">
      <img src='' class="previewImg" width="380" height="300" alt="Image preview..."></div>
        <div class="col-md-6 float-left text-left mb-4">
        <p class="text-bold">Nombre archivo: ${xfile.name}</p>
        <p class="text-bold">Peso del Archivo: ${peso} </p>
        <p class="text-bold">El archivo ${wight ? "<label class='text-danger'>es demasiado pesado</label>" : "es óptimo para subirlo"} </p>
      </div>
    <div class="col-md-3 float-right mb-2">.</div>`;
    $('.previewImg').attr('src', 'vistas/img/nose.jpg'); // Renderizamos la imagen

    } else {     /* SI ES IMAGEN */ 
      // Creamos el objeto de la clase FileReader
      let reader = new FileReader();
      // Leemos el archivo subido y se lo pasamos a nuestro fileReader
      reader.readAsDataURL(xfile);
      // Le decimos que cuando este listo ejecute el código
      reader.onload = function(e){

        pvw.innerHTML=`<div class="col-md-3 float-left mb-2" id="viewimg">
          <img src='' class="previewImg" width="380" height="300" alt="Image preview..."></div>
            <div class="col-md-6 float-left text-left mb-4">
            <p class="text-bold">Nombre archivo: ${xfile.name}</p>
            <p class="text-bold">Peso del Archivo: ${peso} </p>
            <p class="text-bold">El archivo ${wight ? "<label class='text-danger'>es demasiado pesado</label>" : "es óptimo para subirlo"} </p>
          </div>
        <div class="col-md-3 float-right mb-2">.</div>`;

        $('.previewImg').attr('src', e.target.result); // Renderizamos la imagen
      }
    
  }

  };

})
/********************************************************************/
/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR ARCHIVOS DE ENTRADA
/*======================================================================*/
$("body").on("submit", "#form_repositorio", (event)=>{
  event.preventDefault();
  event.stopPropagation();
  var reader;
  var progress = document.querySelector('.percent');
  let sDescripcion_Archivo = $("#descripcion_archivo").val();   //descripción del archivo a subir
  let sUploadedFile = $("#uploadFile").val();   //nombre del archivo a subir
  let sName_File=sUploadedFile.replace(/^.*\\/,""); //quitar la ruta fake, solo nom de archivo
  let file = document.querySelector('#uploadFile').files[0];

  if(sDescripcion_Archivo=='' || file.size > (1048576*4) ){
    alert("Sin descripción ó Archivo demasiado pesado. Max: 4mb.!!!")
   return
  };

  handleFileSelect(file)
  

  function uploader(file){
    //console.log(file.name);
    const xhr=new XMLHttpRequest(),
    formData=new FormData();

    formData.append("archivo", file);
    
    xhr.addEventListener("readystatechange",e=>{

        if(xhr.readyState!==4) return;

        if(xhr.status>=200 && xhr<300){
           let json=JSON.parse(xhr.responseText);
            console.log(json);
        }else{
            let message=xhr.statusText || 'Ocurrio un error';
            console.error(`Error ${xhr.status}: ${message}`)
        }
    })

    xhr.open("POST", "vistas/modulos/upfile_repository.php");
    
    xhr.setRequestHeader("enc-type","multipart/form-data");

    xhr.send(formData);

}

function errorHandler(evt) {
  switch(evt.target.error.code) {
    case evt.target.error.NOT_FOUND_ERR:
      alert('File Not Found!');
      break;
    case evt.target.error.NOT_READABLE_ERR:
      alert('File is not readable');
      break;
    case evt.target.error.ABORT_ERR:
      break; // noop
    default:
      alert('An error occurred reading this file.');
  };
}

function updateProgress(evt) {
  // evt is an ProgressEvent.
  if (evt.lengthComputable) {
    var percentLoaded = Math.round((evt.loaded / evt.total) * 100);
    //let progress=parseInt((evt.loaded*100)/evt.total)
    console.log(percentLoaded, evt.loaded, evt.total);
    // Increase the progress bar length.
    if (percentLoaded < 100) {
      progress.style.width = percentLoaded + '%';
      progress.textContent = percentLoaded + '%';
    }
  }
}

function handleFileSelect(file) {
  // Reset progress indicator on new file selection.
  //let file = document.querySelector('#uploadFile').files[0];
  //console.log(file);
  progress.style.width = '0%';
  progress.textContent = '0%';

  reader = new FileReader();
  reader.onerror = errorHandler;
  reader.onprogress = updateProgress;
  //reader.onloadend = uploader(evt.target.files[0]);
  reader.onloadend = uploader(file);
  reader.onabort = function(e) {
    alert('File read cancelled');
  };
  reader.onloadstart = function(e) {
    document.getElementById('progress_bar').className = 'loading';      //no cambiar
  };

  reader.onload = function(e) {
    // Ensure that the progress bar displays 100% at the end.
    subirArchivos(sDescripcion_Archivo, sName_File, lIs_Public);
    progress.style.width = '100%';
    progress.textContent = '100%';
    setTimeout("document.getElementById('progress_bar').className='';", 2000);

  }

  reader.readAsDataURL(file);
  // Read in the image file as a binary string.
  //reader.readAsBinaryString(evt.target.files[0]);

  setTimeout(() => {
    $('#TablaRepositorio').DataTable().ajax.reload(null, false);
  }, 3000);
}

});  


/********************************************************************/
function refresh(){
  $('#TablaRepositorio').DataTable().ajax.reload(null, false);
}


/********************************************************************/
/********************************************************************/
// FUNCION PARA GUARDAR DATOS EN LA BD DEL ARCHIVO SUBIDO
/*======================================================================*/
function subirArchivos(sDescripcion_Archivo, sName_File, lIs_Public) {

          var datos = new FormData();
          datos.append("descripcion", sDescripcion_Archivo);
          datos.append("nombrearchivo", sName_File);
          datos.append("is_public", lIs_Public);
          axios({ 
            method  : 'post', 
            url : 'ajax/repositorio.ajax.php?op=UpLoadFiles', 
            data : datos,
          })

          .then((res)=>{ 
            //console.log(res)  
            if(res.data=='ok') {
              //console.log(res.data)
              mensajenotie('success', 'Archivo cargado correctamete!', 'bottom');
              $(".previewImg").attr("src", "");
              pvw.innerHTML='';
                        
            }else{
              $("#descripcion_archivo, #uploadFile").val('');
              mensajenotie('error', 'Archivo no fue cargado!');
            }
  
          })

         .catch((err) => {throw err}); 
          $("#descripcion_archivo, #uploadFile").val('');

}

/*======================================================================*/
$('#CheckPublic').on('ifChecked', function (event) {
  console.log(event.type)
  lIs_Public = 1;
});

$('#CheckPublic').on('ifUnchecked', function (event) {
  lIs_Public = 0;
  console.log(event.type)
});
/*=======================================================*/


/*=======================================================
ELIMINAR ARCHIVO DE ENTRADA DE ALMACEN DESDE EL DATATABLE
========================================================*/
$("#TablaRepositorio tbody").on("click", "button.btnDeleteFile", function(event){
  event.preventDefault();
  let iddelete=$(this).data('deletefile')
  let filedelete=$(this).data('namefile')
  if(filedelete.length>50){
    filedelete = filedelete.substring(0, 50);
  }

  swal({
    title: "¿Está seguro de Eliminar Archivo "+filedelete+"? ",
    text: "Si no lo esta puede cancelar la acción!",
    icon: "vistas/img/logoaviso.jpg",
    buttons: ["Cancelar", "Sí, Eliminar"],
    dangerMode: true
  })
   .then((willDelete) => {
    if (willDelete) {

        axios.get('ajax/repositorio.ajax.php?op=delFileRep', {
          params: {
            iddelete: iddelete
          }
        })
      
       .then(res => {
        //console.log(res.data.status);
        if(res.data=="error"){
          swal({
            title: "¡Error!",
            text: 'No fue posible eliminar archivo, revise!!',
            icon: "warning",
            button: "Cerrar"
          })  //fin swal

        }else if(res.data.status==400){
          mensajenotie('error', `${res.data.tipo}`, 'bottom');
          
        }else{
          $('#TablaRepositorio ').DataTable().ajax.reload(null, false);
          mensajenotie('error', 'Archivo fue eliminado!!', 'bottom');
        } 
      })

      .catch((err) => {throw err}); 
      
    } else {
      return
    }
  }) 
})
/************************************************************/

/*=======================================================
CAMBIAR ESTADO A ARCHIVO DESDE EL DATATABLE
========================================================*/
$("#TablaRepositorio tbody").on("click", "button.btnCambiaStatus", function(event){
  event.preventDefault();
  let dataidfile=$(this).data('id-file')
  let dataestado=$(this).data('estado')

    //console.log(dataidfile, dataestado)

        axios.get('ajax/repositorio.ajax.php?op=ChangeStatFile', {
          params: {
            dataidfile: dataidfile,
            dataestado: dataestado
          }
        })
      
       .then(res => {
        //console.log(res.data);
        if(res.data.status==400){
          mensajenotie('error', `${res.data.tipo}`, 'bottom');
        }else if(res.data.status==401){
          mensajenotie('error', `${res.data.tipo}`, 'bottom');
        }else{
          $('#TablaRepositorio ').DataTable().ajax.reload(null, false);
          mensajenotie('success', `${res.data.tipo}`, 'bottom');
        } 
      })

      .catch((err) => {throw err}); 
})

/*======================================================================*/
// MODAL PARA VISUALIZAR IMAGEN o archivo PDF
/*======================================================================*/
$("#TablaRepositorio tbody").on("click", ".btnViewFile", function(){
  
  let html=document.querySelector('.ForViewFile');
  $('.ForViewFile').empty();

  let dataviewfile=$(this).data('viewfile');
  let dataviewruta=$(this).data('viewruta');
  let datadescription=$(this).data('description')
  dataviewruta=dataviewruta.slice(1);
  //console.log(dataviewfile, datadescription)
  
  wext=getFileExtension(dataviewfile);    //obtener la extension del archivo

  $('#ModalCenterTitle').html("");
  $('#ModalCenterTitle').html('Descripción: '+datadescription);

  if(wext=="PNG" || wext=="png" || wext=="JPG" || wext=="jpg" || wext=="tif" || wext=="TIF" ){
    html.innerHTML+=`
      <img src='${dataviewruta+dataviewfile} ' id="imagen-modal" class="img-fluid imagen" alt=""  width="200" height="230"></img>
    `;
  }else if(wext=="pdf" || wext=="PDF"){
    html.innerHTML+=`
      <object type="application/pdf" data='${dataviewruta+dataviewfile} '" width="100%" height="550" style="height: 62vh;"></object>
    `;    
  }else{
    html.innerHTML+=`
      <img src='vistas/img/No_image_available.png' id="imagen-modal" class="img-fluid imagen" alt=""  width="100%" height="550" style="height: 62vh;"></img>
    `;
  }

  })

/*======================================================================*/

/*======================================================================*/
// MODAL PARA PERMISOS
/*======================================================================*/
$("#TablaRepositorio tbody").on("click", ".btnEditFile", function(){
let file=$(this).data('idfile');
console.log(file);

})
/*======================================================================*/

function formatState (state) {
  if(!state.element) return;
  var os = $(state.element).attr('onlyslave');
  return $('<span onlyslave="' + os + '">' + state.text + '</span>');
}


 $(document).ready(function() {
      $("#selUsuario").select2({
        placeholder: 'Filtro por Usuarios',
        escapeMarkup: function(m) { 
           return m; 
        },
        allowClear: true,
        templateResult: formatState
    });
});
