    // <input type="file" id="files" name="file" />
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
  if(sDescripcion_Archivo=='') return;


  handleFileSelect()
  

  function uploader(file){
    console.log(file.name);
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
      console.log(percentLoaded);
    }
  }
}

function handleFileSelect(evt) {
  // Reset progress indicator on new file selection.
  let file = document.querySelector('#uploadFile').files[0];
  console.log(file);
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
    document.getElementById('progress_bar').className = 'Cargando...';
  };

  reader.onload = function(e) {
    // Ensure that the progress bar displays 100% at the end.
    subirArchivos(sDescripcion_Archivo, sName_File, lIs_Public);
    progress.style.width = '100%';
    progress.textContent = '100%';
    setTimeout("document.getElementById('progress_bar').className='';", 3000);
  }
  reader.readAsDataURL(file);
  //reader.readAsDataURL(evt.target.files[0]);
  // Read in the image file as a binary string.
  //reader.readAsBinaryString(evt.target.files[0]);
}

});  
    

      
      
      
        //document.getElementById('uploadBtn').addEventListener('click', handleFileSelect, false);
