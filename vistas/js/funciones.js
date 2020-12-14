/*=============================================
ASIGNA FECHA ACTUAL EN DATERANGEPICKER 
=============================================*/    
function fechadehoy(){
   
    var d = new Date();
    //console.log("Fecha de Hoy:",d);
    var dia = d.getDate();
    var mes = d.getMonth()+1;
    var año = d.getFullYear();

    if(mes < 10){

      var fechaInicial = dia+"-0"+mes+"-"+año;
      var fechaFinal = dia+"-0"+mes+"-"+año;

    }else if(dia < 10){

      var fechaInicial = "0"+dia+"-"+mes+"-"+año;
      var fechaFinal =   "0"+dia+"-"+mes+"-"+año;

    }else if(mes < 10 && dia < 10){

      var fechaInicial = "0"+dia+"-0"+mes+"-"+año;
      var fechaFinal =   "0"+dia+"-0"+mes+"-"+año;

    }else{

      var fechaInicial = dia+"-"+mes+"-"+año;
      var fechaFinal =   dia+"-"+mes+"-"+año;

    } 
         $("#daterange-btn-Ajuste span").html(fechaInicial+' - '+fechaFinal);
        //console.log(fechaInicial+' - '+fechaFinal);
    	  localStorage.setItem("valorRangoAjusteInv", fechaInicial+' - '+fechaFinal);
    	
}   

/********************************************************
* Función para verificar si tiene los permisos bit a bit
********************************************************/
function getAccess(bit1, bit2){
  return ((bit1 & bit2) == 0) ? false : true;     //hay que notar que estamos usando el símbolo & (ampersand). Este es el operador AND a nivel de bit en PHP; el operador AND booleano se representa con dos símbolos ampersand &&
}

/********************************************************
//SI SELECCIONA UN USUARIO SE ABRE ACORDION
********************************************************/
$("#nvoUsuarioPermiso").change(function(){
  let usuarioselec=$("#nvoUsuarioPermiso").val();
  if(usuarioselec=='0'){
      $("#accordion").hide();
  }else{
      $("#accordion").show("slow");
      obteneraccesos1(usuarioselec);
      obteneraccesos2(usuarioselec);
      obteneraccesos3(usuarioselec);
      obteneraccesos4(usuarioselec);
  }
});

/******************************************************************************/
function activarcheckbox(bigdata){
  $.each(bigdata, function(key, item) {
      //if(key=="productos"){
          let inicial=key.substring(0, 4);
          //console.log(key, item, inicial);
            $('input#'+key).iCheck('check');
          if(!getAccess(item, 1)){
              $('#vie'+inicial).iCheck('uncheck')  
          }
          if(!getAccess(item, 2)){
              $('#adi'+inicial).iCheck('uncheck')  
          }
          if(!getAccess(item, 4)){
              $('#edi'+inicial).iCheck('uncheck')  
          }

          if(!getAccess(item, 8)){
              $('#del'+inicial).iCheck('uncheck') 
          }

          if(!getAccess(item, 16)){
              $('#pri'+inicial).iCheck('uncheck')  
          }

          if(!getAccess(item, 32)){
              $('#act'+inicial).iCheck('uncheck')  
          }

          if(!getAccess(item, 64)){
              $('#sel'+inicial).iCheck('uncheck')  
          }

          if(!getAccess(item, 128)){
              $('#pay'+inicial).iCheck('uncheck')  
          }

          if(!getAccess(item, 256)){
              $('#acc'+inicial).iCheck('uncheck')
          }
      //}
  });
}

//REMOVER ITEM DEL ARRAY arrayProductos
function removeItemFromArr ( arr, item ) {
  var i = arr.indexOf( item );
   if ( i !== -1 ) {
      arr.splice( i, 1 );
  }
  //console.log(arrayProductos);
}
