var arrayDeProductos=new Array();
var arrayitems=new Array();
var nuevoCSS = { "background": '#8EF70F', "font-weight" : 'bold' };

$("#modalEditarSalidasAlmacen").draggable({
    handle: ".modal-header"
});

//AL ABRIR EL MODAL PARA EDITAR SALIDA.
$('#modalEditarSalidasAlmacen').on('show.bs.modal', function (event) {
    $('#EditidTecnicoRecibe').prop('disabled',true);
    $('#EditidTipoSalidaAlmacen').prop('disabled',true);
    $('#EditidAlmacenSalida').prop('disabled',true);
    $('#EditObservacion').prop('disabled',true);
    $("#btnEditSalidasAlmacen").hide();
})

/*======================================================================*/
//= ENVIAR FORMULARIO PARA GUARDAR DATOS DE SALIDA = 
/* ======================================================================*/
$("body").on("submit", "#form_SalAlmaEdit", function( event ) {	
  event.preventDefault();
  event.stopPropagation();
  $('#EditidTecnicoRecibe').prop('disabled',false);
  $('#EditidTipoSalidaAlmacen').prop('disabled',false);
  $('#EditidAlmacenSalida').prop('disabled',false);  
  let formData = new FormData($("#form_SalAlmaEdit")[0]);   
  for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);} 

  axios({ 
    method  : 'post', 
    url : 'ajax/salidasalmacen.ajax.php?op=guardarEditaSalidasAlmacen', 
    data : formData, 
  }) 
  .then((res)=>{ 
    if(res.status==200) {
      console.log(res.data)

      $('#modalEditarSalidasAlmacen').modal('hide')
      $('#dt-salidasalmacen').DataTable().ajax.reload(null, false);

    }            
    console.log(res); 
  }) 
  .catch((err) => {throw err}); 


});  
/* ======================================================================*/

/*======================================================================*/
//==BUSCA PROD SELECCIONADO Y TRAE CANT EXISTENTE =
/* ======================================================================*/
$("#EditselProdSalAlm").change(function(event){
  event.preventDefault();
  // si viene vacio el select2 que regrese en false
  if($(this).val()==""){
      return false;	
  }
  let idalmacen=$("#EditidNumSalAlm").val();
  let idtiposalida=$("#EditidTipoSalidaAlmacen").val();
  idalmacen=parseInt(idalmacen);
  idtiposalida=parseInt(idtiposalida);
  //console.log(idalmacen, idtiposalida)
  if(idalmacen==0 || idtiposalida==0){
      $('span#Editmsjdeerror').html(`<label style='color:red'>${"¡Seleccione Tipo y Almacen de salida!!"} </label>`);
      $("#EditidNumSalAlm").focus(function(){
        $("span#Editmsjdeerror").css("display", "inline").fadeOut(4000);
      });
      $('#EditselProdSalAlm').val(null).trigger('change');
      $("#EditidNumSalAlm").focus();
      return false;	
  }else if(isNaN(idalmacen)) {
      $('span#Editmsjdeerror').html(`<label style='color:red'>${"¡Seleccione Almacen!!"} </label>`);
      $("#EditidNumSalAlm").focus(function(){
        $("span#Editmsjdeerror").css("display", "inline").fadeOut(4000);
      });
      $('#EditselProdSalAlm').val(null).trigger('change');
      $("#EditidNumSalAlm").focus();
      return false;	
  }
  
  let almacen=$("#EditidAlmacenSalida option:selected").text();
  //let almacen=$('select[name="EditidNumSalAlm"] option:selected').text();
  let idProducts=$("#EditselProdSalAlm").val();
  idProducto=parseInt(idProducts);
  almacen=almacen.toLowerCase();
  //console.log("entra1: ", idalmacen, almacen, idProducto, arrayitems);
  
  //HACER UN FETCH SI EXIST PROD Y SU EXISTENCIA
  let data = new FormData();
  data.append('almacen', almacen);
  data.append('idProducto', idProducto);
  
  fetch('ajax/salidasalmacen.ajax.php?op=consultaExistenciaProd', {
     method: 'POST',
     body: data
   })
  .then(response => response.json())
  .then(data => {
   console.log(data)
   
    if(data===false){
      //console.log("No existe Art");
      $("#EditcantExisteAlmacen").val(0);
      $("#EditcantSalidaAlmacen").val("");
      $('#Editmensajerrorsalida').text('Prod. no existe en este Almacen!!');
      $("#Editmensajerrorsalida").removeClass("d-none");
      setTimeout(function(){$("#Editmensajerrorsalida").addClass("d-none")}, 2500);
    }else{
      let approved = arrayitems.filter(items => items[0]===idProducts);
      if(typeof approved !== "undefined" && approved != null && approved.length > 0){
       //console.log(approved[0][1])
       let aumenta=approved[0][1];    //obtiene valor de cant
       $("#EditcantExisteAlmacen").css("background", "#E8EF00");
       $("#EditcantExisteAlmacen").val(parseInt(data.cant)+aumenta);
      }else{
        $("#EditcantExisteAlmacen").val(data.cant);
        $("#EditcantExisteAlmacen").css(nuevoCSS);
      }

        udeMedida=data.medida;

    }
  })
  .catch(error => console.error(error))
  })  //fin del selecProductoSalida
  

//VERIFICA CANT NO SEA MAYOR QUE LA EXISTENCIA
$("#EditcantSalidaAlmacen").change(function(event){
  event.preventDefault();
      $("#Editmensajerrorsalida").addClass("d-none");
      cantexist=$("#EditcantExisteAlmacen").val();
      cantSolicitada=$("#EditcantSalidaAlmacen").val();
      if(parseFloat(cantSolicitada)>parseFloat(cantexist)){
          $('#Editmensajerrorsalida').text('Cantidad Solicitada es Mayor a la Existencia!!');
          $("#Editmensajerrorsalida").removeClass("d-none");
          $("#EditcantSalidaAlmacen").val(0);
      }else{
          $("#Editmensajerrorsalida").addClass("d-none");
      }
  });

/*============================================================
                AGREGA PRODUCTO SELECCIONADO
============================================================*/
$("#EditagregaSalidaProd").click(function(event){
  event.preventDefault();
  cantexist=$("#EditcantExisteAlmacen").val();
  let idProducto=$("#EditselProdSalAlm").val();
  idProducto=parseInt(idProducto); 
  
  let producto=$("#EditselProdSalAlm option:selected" ).text();       //obtener el texto del valor seleccionado
  let cantcap=$("#EditcantSalidaAlmacen").val();
  cantidad=parseFloat(cantcap);
     
  //Si no selecciona producto retorna o cantidad
    if(isNaN(idProducto) || isNaN(cantidad) || cantidad<1 ){
      return true;
    }  
  
  //SEPARA EL CODIGO DEL PROD. SELECCIONADO
  let codigointerno= producto.substr(0, producto.indexOf('-'));
  codigointerno.trim();
    
  //SEPARA LA DESCRIPCION DEL PROD. SELECCIONADO        
  let descripcion= producto.substr(producto.lastIndexOf("-") + 1);
  descripcion.trim();
       
  let udemedida=udeMedida;
    
  console.log("prod:",idProducto, "codInt:",codigointerno, "descrip:",descripcion, "medida:",udemedida,  "cant:",cantidad );
  
  let encontrado=arrayDeProductos.includes(idProducto)
  //console.log("encontrado:", encontrado, arrayDeProductos)
  if(!encontrado){
    arrayDeProductos.push(idProducto);
    addProductsOut(idProducto, cantidad, udeMedida, codigointerno, descripcion);
    $("#btnEditSalidasAlmacen").show();
    //console.log("añadido:", encontrado, arrayDeProductos)
  }else{
    mensajedeerror();
  }
  
});  

/*==================================================================
ADICIONA PRODUCTOS AL TBODY
==================================================================*/
function addProductsOut(...argsProductos){
//console.log("manyMoreArgs", argsProductos);
var contenido=document.querySelector('#Edittbodysalidasalmacen');

contenido.innerHTML+=`
<tr class="filas" id="fila${argsProductos[0]}">
  <td class='text-center'><button type="button" class="botonQuitar" onclick="eliminarProducts(${argsProductos[0]}, ${argsProductos[1]} )" title="Quitar concepto">X</button></td>
  <td class='text-center'>${argsProductos[0]} <input type="hidden" name="idproducto[]" value="${argsProductos[0]}"</td>
  <td class='text-center'>${argsProductos[3]}</td>
  <td class='text-left'>${argsProductos[4]}</td>
  <td class='text-center'>${argsProductos[2]}</td>
  <td class='text-center'>${argsProductos[1]} <input type="hidden" name="cantidad[]" value="${argsProductos[1]}"</td>
  </tr>
`;
  renglonesSalidas++;
  cantSaliente+=argsProductos[1];
  evaluaRow(renglonesSalidas, cantSaliente);

  //DESPUES DE AÑADIR, SE INICIALIZAN SELECT E INPUT
  $('#EditselProdSalAlm').val(null).trigger('change');	
  $("#EditcantExisteAlmacen").val("");
  $("#EditcantExisteAlmacen").val(0);
  $("#EditcantSalidaAlmacen").val(0);

}
/*==================================================================*/

/*==============================================================
TRAER DATOS DE SALIDA DE ALMACEN DESDE EL DATATABLE PARA EDITAR
==============================================================*/
$("#dt-salidasalmacen tbody").on("click", "button.btnEditSalidaAlmacen", function(event){
    event.preventDefault();
	var idEditSalida = $(this).attr("idEditSalida");
    console.log(idEditSalida);
    $("#EditidNumSalAlm").val(idEditSalida);
    obtenerdatos(idEditSalida)
})


function obtenerdatos(idsalida){
    oldproducts=new Array();
    axios.post('ajax/salidasalmacen.ajax.php?op=getDataOutStore', { idsalida: idsalida })
      .then(res => {
        //console.log(res);
        console.log(res.data);
        console.log("id Almacen: ",res.data[0].id_almacen, "nombre Almacen: ",res.data[0].nombrealma);
        $("#EditidTecnicoRecibe").val(res.data[0].id_tecnico);
        $("input[name=EditFechaSalidaAlmacen]").val(res.data[0].fechasalida);
        $("#EditidRespSalidaAlmacen").val(res.data[0].nombreusuario);
        $("#EditidTipoSalidaAlmacen").val(res.data[0].id_tipomov);
        let idalmacensalida=res.data[0].id_almacen+'-'+res.data[0].nombrealma;
        //console.log("resp: ",res.data[0].nombreusuario);
        //$("#EditidAlmacenSalida").val(res.data[0].id_almacen);
        $("#EditidAlmacenSalida").val(idalmacensalida);
        $("input[name=EditObservacion]").val(res.data[0].motivo);
        let contenido=document.querySelector('#Edittbodysalidasalmacen');
        renglonesSalidas=cantSaliente=0;
        contenido.innerHTML="";
        res.data.forEach(i => {
            //console.log(i)
            console.log(i.id_producto, i.cantidad, i.medida, i.codigointerno, i.descripcion,  )
            let amount=parseInt(i.cantidad);

              itemsbefore(i.id_producto, amount);   //array de productos de la salida a editar

            contenido.innerHTML+=`
            <tr class="filas" id="fila${i.id_producto}">
              <td class='text-center'><button type="button" class="botonQuitar" onclick="eliminarProducts(${i.id_producto}, ${amount} )" title="Quitar concepto">X</button></td>
              <td class='text-center'>${i.id_producto} <input type="hidden" name="idproducto[]" value="${i.id_producto}"</td>
              <td class='text-center'>${i.codigointerno}</td>
              <td class='text-left'>${i.descripcion}</td>
              <td class='text-center'>${i.medida}</td>
              <td class='text-center'>${amount} <input type="hidden" name="cantidad[]" value="${i.cantidad}"</td>
              </tr>
              `;
            arrayDeProductos.push(parseInt(i.id_producto));
            renglonesSalidas++;
            cantSaliente+=amount;
            evaluaRow(renglonesSalidas, cantSaliente);
            
        });
        
        //console.log(oldproducts);
      })
    .catch((err) => {throw err}); 
}

function itemsbefore(itemproduct, itemcant){
  let contenido=document.querySelector('#Edittbodysalidasalmacen');
  contenido.innerHTML+=`<div class="d-none">
  <input type="hidden" name="oldproducto[]" value="${itemproduct}">
  <input type="hidden" name="oldcantidad[]" value="${itemcant}">
  </div>`;
  arrayitems.push([itemproduct,itemcant]);
}

function evaluaRow(totalRenglon, cantSaliente){
	$("#Editrenglonsalidas").html(totalRenglon);
	$("#Edittotalsalidasalmacen").html(cantSaliente);
	if(totalRenglon>0){
	  $("#EditrowSalAlma").show();
    }else{
      $("#btnEditSalidasAlmacen").hide();
	}
}

function eliminarProducts(indice, restarcantidad){
  console.log("entra a eliminar",indice)
  $("#fila" + indice).remove();
  $("#btnEditSalidasAlmacen").show(); 
  cantSaliente-=restarcantidad;
  renglonesSalidas--;
  removeItemFromArr(arrayDeProductos, indice)
  evaluaRow(renglonesSalidas, cantSaliente);
  evaluarElementos();
}


/*================ AL SALIR DEL MODAL DE EDICION  RESETEAR FORMULARIO==================*/
$("#modalEditarSalidasAlmacen").on('hidden.bs.modal', ()=> {
  $('#form_SalAlmaEdit')[0].reset();                    //resetea el formulario
  $('#EditselProdSalAlm').val(null).trigger('change');	//inicializa select2
  $("#EditcantExisteAlmacen").val(0);                   //inicializa campo existencia
  $("#EditcantSalidaAlmacen").val("");                  //inicializa campo salida
  arrayDeProductos["length"]=0;                         //inicializa array
});

/*=============================================
CAPTURAR HOY DIA=01-10 MES=01-10
=============================================*/
$(".daterangepicker.opensright .ranges li").on("click", function(){

	//var textoHoy = $(this).attr("data-range-key");
	var textoHoy = $(this).html();
    
	if(textoHoy == "Hoy"){
     
    var d = new Date();
    //console.log("Fecha de Hoy:",d);
    var dia = d.getDate();
    var mes = d.getMonth()+1;
    var año = d.getFullYear();

    if(mes < 10){

      //var fechaInicial = año+"-0"+mes+"-"+dia;
      //var fechaInicial = año+"-0"+mes+"-"+dia;
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
        $("#daterange-btn-SalAlmacen span").html(fechaInicial+' - '+fechaFinal);
        //console.log(fechaInicial+' - '+fechaFinal);
    	  localStorage.setItem("RangoFechaSalidasAlmacen", fechaInicial+' - '+fechaFinal);
    	
	}

})    
/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/
$('#daterange-btn-SalAlmacen').on('cancel.daterangepicker', function(ev, picker) {
  localStorage.removeItem("RangoFechaSalidasAlmacen");
  localStorage.clear();
  $("#daterange-btn-SalAlmacen span").html('<i class="fa fa-calendar"></i> Rango de fecha')
});