//file._verLink = Dropzone.createElement("<a href=\"javascript:onclick=verExt('"+file.name+"');\" title='Visualizar'\";  \"  class=\"buttonDrop blue small radius dz-open fa fa-eye\" data-id="+nomber+" ></a>");
verExt(xFile){
    let extension=obtenerExtension(xFile);    
    fetch('buscarFolder.php')
        .then(function(response) {
            return response.text();
        })
        .then(function(data) {
            console.log('data = ', data);
            let imag=`./file_upload/${data}/${xFile}`;
          if(extension=="pdf"){
                VerPDF(imag,xFile);
            }else{
                verImagen(imag,xFile);
            }
        })
        .catch(function(err) {
            console.error(err);
        });
        
    }
    
    
    //retorna extension del archivo
    function obtenerExtension(filExt) {
      return filExt.split('.').pop();
    }
    
    var ventana
    function verImagen(imag,xfile)	{
        ventana=window.open('','ventana','resizable=yes,scrollbars=yes,width=950,height=650');
        ventana.document.write('<html><head><title>' + imag + '</title></head><body style="overflow-y: auto;" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" scroll="yes" onUnload="opener.cont=0"> <div align="center" style="background-color:lightblue;"> <a href="javascript:this.close()">Cerrar</a> <a href="' + imag + '" download="' + xfile + '" style="color:green;"> - Descargar archivo</a> </div> <br> <img src="' + imag + '" onLoad="opener.redimensionar(this.width, this.height)">')
        ventana.document.close()
    }
    
    function redimensionar(ancho, alto){
        ventana.resizeTo(ancho+12,alto+50)
        ventana.moveTo((screen.width-ancho)/2,(screen.height-alto)/2) //centra la ventana. Eliminar si no se quiere centrar el popup
    }
    
    function VerPDF(imag){
      window.open(imag, 'resizable,scrollbars');
    }
                  