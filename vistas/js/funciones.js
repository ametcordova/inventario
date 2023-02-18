
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

//FUNCION PARA MENSAJE DE ERROR
function mensajedeerror(){
  $("#cantEntradaAlmacen, #cantEditarEntradaAlmacen").val(0);
  $('#mensajeEditarerrorentrada, #mensajerrorentrada').text('Producto ya capturado. Revise!!');
  $("#mensajerrorentrada, #mensajeEditarerrorentrada").removeClass("d-none");
  setTimeout(function(){$("#mensajerrorentrada, #mensajeEditarerrorentrada").addClass("d-none")}, 2500);
  return true;
}

function log (name, evt) {
  if (!evt) {
    var args = "{}";
  } else {
    var args = JSON.stringify(evt.params, function (key, value) {
      if (value && value.nodeName) return "[DOM node]";
      if (value instanceof $.Event) return "[$.Event]";
      return value;
    });
  }
  var $e = $("<li>" + name + " -> " + args + "</li>");
  $eventLog.append($e);
  $e.animate({ opacity: 1 }, 10000, 'linear', function () {
    $e.animate({ opacity: 0 }, 2000, 'linear', function () {
      $e.remove();
    });
  });
}

/********************************************************************/
// FUNCION PARA PREVISUALIZAR ARCHIVOS EN FORMATO IMAGEN PARA SUBIR
/********************************************************************/
function previewFile() {
  var preview = document.querySelector(".previewImg");
  var file    = document.querySelector('input[type=file]').files[0];
  var reader  = new FileReader();
  wext=getFileExtension(file.name);
  
    reader.onloadend = function () {
      preview.src = reader.result;
    }

  if (file) {
    if(wext=="xlsx" || wext=="xls" || wext=="zip" || wext=="ZIP" || wext=="xml" || wext=="XML" || wext=="docx" || wext=="doc" || wext=="rar" || wext=="RAR"){
      preview.src = "vistas/img/nose.jpg";
    } else {
      reader.readAsDataURL(file);
    }
  }
}
/********************************************************************/

/******************************************
 * OBTENER LA EXTENSION DE UN ARCHIVO
 *****************************************/
function getFileExtension(filename) {
  return filename.split('.').pop();
}

/*======================================================================*/
function mensajenotie(tipo, text, pos, timer=null){
  notie.alert({ 
    type: `${tipo}`,
    text: `${text}`,
    stay: false, 
    time: timer,
    position: `${pos}` // optional, default = 'top', enum: ['top', 'bottom']
  }) // Hides after 4 seconds
}
/*======================================================================*/
/**
 * Convierte una cadena larga de bytes en un formato legible, por ejemplo, KB, MB, GB, TB, YB
 * 
 * @param {Int} num El número de bytes.
 */
 function formatoBytes(bytes) {
  var i = Math.floor(Math.log(bytes) / Math.log(1024)),
  sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

  return (bytes / Math.pow(1024, i)).toFixed(2) * 1 + '' + sizes[i];
}

/********************************************************************
 * Funcion que retorna la fecha actual
 * @returns fecha actual
 ******************************************************************/
 function getFecha(arrayfecha) {
  if(arrayfecha) {
    if(arrayfecha.length == 0) {
      return { 
        fecha1: moment().format('YYYY-MM-DD'), 
        fecha2: moment().format('YYYY-MM-DD')
      }
    }else{
      return{
        fecha1: moment(arrayfecha[0],'DD-MM-YYYY').format('YYYY-MM-DD'),
        fecha2: moment(arrayfecha[1],'DD-MM-YYYY').format('YYYY-MM-DD')
      }
    }
  }else{
    return { 
      fecha1: moment().format('YYYY-MM-DD'), 
      fecha2: moment().format('YYYY-MM-DD')
    }
  }
};
/*===================================================*/

/*===================================================
ASIGNAR FECHA
/*===================================================*/
function iniciarangodefecha(){
  start=moment().subtract(1, 'months').format('DD-MM-YYYY');   // 1 mes atras. 
  end=moment().format('DD-MM-YYYY')
  $('#daterange-btnOS span').html(start + ' - ' + end);
  //captRangoFecha=$('#daterange-btnOS span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
  captRangoFecha=start+' - '+end;
  
  localStorage.setItem("captRangoFecha", captRangoFecha);
  
}

/*===================================================
ASIGNAR FECHA
/*===================================================*/
function iniciarangodefechaQuejas(){
  start=moment().subtract(1, 'months').format('DD-MM-YYYY');   // 1 mes atras. 
  end=moment().format('DD-MM-YYYY')
  $('#daterange-btnQuejas span').html(start + ' - ' + end);
  //captRangoFecha=$('#daterange-btnOS span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
  rangodeFechaQuejas=start+' - '+end;
  localStorage.setItem("captRangoFechaQuejas", rangodeFechaQuejas);
}

/*==================================================================*/
$('#daterange-btnOS, #daterange-btnQuejas').daterangepicker({
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
          "Septiembre",
          "Octubre",
          "Noviembre",
          "Diciembre"
      ],
      "firstDay": 1
  },          
  startDate: moment(),
  endDate  : moment(),
  start: moment(),
  end  : moment()
},
  function(start, end){
    putRangoFecha(start, end)
  },

)

function putRangoFecha(start, end){
  $('#daterange-btnOS span, #daterange-btnQuejas span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
  captRangoFecha=start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY');
  captRangoFechaQuejas=start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY');
  localStorage.setItem("captRangoFecha", captRangoFecha);
  localStorage.setItem("captRangoFechaQuejas", captRangoFechaQuejas);
}

/*====================================================================================*/
/*  This functionality is not built into JavaScript, so custom code needs to be used. 
/*  The following is one way of adding commas to a number, and returning a string.
/*  ejemplo: var some_number = number_format(42661.55556, 2, ',', ' '); //gives 42 661,56
/*====================================================================================*/
number_format = function (number, decimals, dec_point, thousands_sep) {
  number = number.toFixed(decimals);

  var nstr = number.toString();
  nstr += '';
  x = nstr.split('.');
  x1 = x[0];
  x2 = x.length > 1 ? dec_point + x[1] : '';
  var rgx = /(\d+)(\d{3})/;

  while (rgx.test(x1))
      x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

  return x1 + x2;
}
/*====================================================================================*/
// FUNCION PARA ORDENAR NUMEROS CORRECTAMENTE CON SORT()
// var arr = ['80', '9', '700', 40, 1, 5, 200];
// function comparar1(a, b) {
//   return a - b;
// }
// console.log('original:', arr.join());
// console.log('ordenado sin función:', arr.sort());
// console.log('ordenado con función:', arr.sort(comparar1));
/*====================================================================================*/
function comparar(a, b) {
  return a - b;
}
/*====================================================================================*/

/*************************************************** */
/* FUNCION PARA CHECAR SI UN STRING ES UN NUMERO */
/*************************************************** */
function isNum(val){
  return !isNaN(val)
}
/***************MISMA FUNCION. CHECA QUE UN STRING ES UN NUMERO*********** */
const isNumeric = n => !isNaN(n);
/************************************************************************ */