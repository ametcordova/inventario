var udeMedida="S/I";
var codigosku;
var renglonEditarEntradas=cantEditarEntrante=0;
var arrayEditarProductos=new Array();
var arrayitems=new Array();
var nuevoCSS = { "background": '#8EF70F', "font-weight" : 'bold' };

$("#modalEditarEntradasAlmacen").draggable({
    handle: ".modal-header"
});

//Función que se ejecuta al inicio
function init(){
}

/*==============================================================
TRAER DATOS DE ENTRADA DEL ALMACEN DESDE EL DATATABLE PARA EDITAR
==============================================================*/
$("#dt-entradasalmacen tbody").on("click", "button.btnEditEntradaAlmacen", function(event){
  event.preventDefault();
	var idEditarEntrada = $(this).attr("idEditarEntrada");
  //console.log(idEditarEntrada);
  $("#EditidNumSalAlm").val(idEditarEntrada);
  obtenerdatosEntrada(idEditarEntrada)
})

//TRAER DATOS DE LA BD PARA EDITAR
function obtenerdatosEntrada(idEditarEntrada){
  oldproducts=new Array();
    axios.get('ajax/entradasalmacen.ajax.php?op=getDataInStore', {
      params: {
        idEditarEntrada: idEditarEntrada
      }
    })

    .then(res => {
      if(res.status==200) {
        //console.log(res);
        //console.log(res.data);
        $("#numEditarEntradaAlmacen").val(res.data[0].id);
        $("#EditarProveedorEntrada").val(res.data[0].id_proveedor);
        $("input[name=EditarFechaEntradaAlmacen").val(res.data[0].fechaentrada);
        $("#EditarRespEntradaAlmacen").val(res.data[0].nombreusuario);
        $("#EditarTipoEntradaAlmacen").val(res.data[0].id_tipomov);
        let idalmacenentrada=res.data[0].id_almacen+'-'+res.data[0].nombrealmacen;
        $("#idEditarAlmacenEntrada").val(idalmacenentrada);
        $("input[name=EditarObservacion]").val(res.data[0].observacion);

        showItemsOldProducts(res.data);
        
      }
    
    })
  .catch((err) => {throw err}); 
}

//MUESTRA LOS PRODUCTOS EN EL TBODY DE ENTRADA GUARDADA
function showItemsOldProducts(data){
  let contenido=document.querySelector('#tbodyEditarentradasalmacen');
  renglonesEntradas=cantEntrante=0;
  contenido.innerHTML="";
  data.forEach(i => {
      //console.log(i.id_producto, i.cantidad, i.medida, i.codigointerno, i.descripcion,  )
      let amount=parseInt(i.cantidad);

        itemsbeforeProducts(i.id_producto, amount);   //array de productos de la entrada a editar

      contenido.innerHTML+=`
      <tr class="filas" id="fila${i.id_producto}">
        <td class='text-center'><button type="button" class="botonQuitar" onclick="deleteProducts(${i.id_producto}, ${amount} )" title="Quitar concepto">X</button></td>
        <td class='text-center'>${i.id_producto} <input type="hidden" name="idproducto[]" value="${i.id_producto}"</td>
        <td class='text-center'>${i.sku}</td>
        <td class='text-center'>${i.codigointerno}</td>
        <td class='text-left'>${i.descripcion}</td>
        <td class='text-center'>${i.medida}</td>
        <td class='text-center'>${amount} <input type="hidden" name="cantidad[]" value="${i.cantidad}"</td>
        </tr>
        `;
      arrayEditarProductos.push(parseInt(i.id_producto));
      renglonesEntradas++;
      cantEntrante+=amount;
      evalRowProducts(renglonesEntradas, cantEntrante);
      
  });

}

//MUESTRA LOS PRODUCTOS A EDITAR PERO OCULTOS Y TMB LOS GUARDA EN UN ARRAY
function itemsbeforeProducts(itemproduct, itemcant){
  let contenido=document.querySelector('#tbodyEditarentradasalmacen');
  contenido.innerHTML+=`<div class="d-none">
  <input type="hidden" name="oldproducto[]" value="${itemproduct}">
  <input type="hidden" name="oldcantidad[]" value="${itemcant}">
  </div>`;
  arrayitems.push([itemproduct,itemcant]);
}

function evalRowProducts(totalRenglon, cantSaliente){
	$("#renglonEditarentradas").html(totalRenglon);
	$("#totalEditarentradasalmacen").html(cantSaliente);
	if(totalRenglon>0){
	  $("#rowEditarEntradaAlma").show();
    }else{
      $("#btnEditarEntradasAlmacen").hide();
	}
}

function deleteProducts(indice, restarcantidad){
  console.log("entra a eliminar",indice)
  $("#fila" + indice).remove();
  $("#btnEditarEntradasAlmacen").show(); 
  cantEntrante-=restarcantidad;
  renglonesEntradas--;
  removeItemFromArr(arrayEditarProductos, indice)
  evalRowProducts(renglonesEntradas, cantEntrante);
  evaluarElementos();
}

//SELECT2 DE CAT. DE PRODUCTOS POR AJAX
$('#selEditarProdEntAlm').select2({
  placeholder: 'Selecciona un producto',
  ajax: {
    url: 'ajax/entradasalmacen.ajax.php?op=ajaxProductos',
    async: true,
    dataType: 'json',
    delay: 250,
    type: "POST",
    data: function (params) {
      return {
        searchTerm: params.term
       };  
    },
    processResults: function (data) {
      return {
          results: $.map(data, function (item) {
              return {
                  text: item.sku+' - '+item.descripcion,
                  id: item.id,
                  descripcion:item.descripcion,
              }
          })
      };

  },    
    // success:function(data){
    //   console.log(data[0].medida);
    // },
    cache: true
  },
  minimumInputLength: 1
});


$(document).on('change', '#selEditarProdEntAlm', function(event) {
//$("#selProdEntAlm").change(function(event){
event.preventDefault();

//console.log(this);

  let idalmacen=$("#idEditarAlmacenEntrada").val();
  let tbl_almacen=$( "#idEditarAlmacenEntrada option:selected" ).text();
  let idprod=$("#selEditarProdEntAlm").val();
  // si viene vacio el select2 que regrese en false   |=124
  if($(this).val()=="" || $(this).val()==null || idalmacen==null){       
      return false;	
  }

  axios.get('ajax/entradasalmacen.ajax.php?op=consultaExistenciaProd', {
    params: {
      idprod: idprod,
      almacen: tbl_almacen
    }
  })
  .then((res)=>{ 
    if(res.status==200) {
      //console.log(res.data)
        if(res.data==false){
          $("#cantEditarExistenciaAlmacen").css("background", "#E8EF00");
          $("#cantEditarExistenciaAlmacen").val(0);
          $("#cantEditarEntradaAlmacen").val("");
          $('#mensajeEditarerrorentrada').text('Prod. no existe en este almacén. Se agregará.');
          $("#mensajeEditarerrorentrada").removeClass("d-none");
          setTimeout(function(){$("#mensajeEditarerrorentrada").addClass("d-none")}, 2500);
    
        }else{

          let approved = arrayitems.filter(items => items[0]===idprod);
          if(typeof approved !== "undefined" && approved != null && approved.length > 0){
            //console.log(approved[0][1])
            let disminuye=approved[0][1];    //obtiene valor de cant
            $("#cantEditarExistenciaAlmacen").css("background", "#E8EF00");
            $("#cantEditarExistenciaAlmacen").val(parseInt(res.data.cant)-disminuye);
          }else{
            $("#cantEditarExistenciaAlmacen").val(res.data.cant);
            $("#cantEditarExistenciaAlmacen").css(nuevoCSS);
          }
        }

      if(res.data.medida!=undefined){
        $("#editunidaddemedida").val(res.data.medida);
        udeMedida=res.data.medida;
        codigointerno=res.data.codigointerno;
      }
      
      //$('#servicioEditarSelecionado').html(udeMedida);
    }          

  }) 
  .catch((err) => {throw err}); 
});

/*============================================================
                AGREGA PRODUCTO SELECCIONADO
============================================================*/
$("#EditarEntradaProd").click(function(event){
  event.preventDefault();
  let cantEntrada=$("#cantEditarEntradaAlmacen").val();
  let idProducto=$("#selEditarProdEntAlm").val();
  let producto=$("#selEditarProdEntAlm option:selected" ).text();       //obtener el texto del valor seleccionado
  idProducto=parseInt(idProducto); 
  cantEntrada=parseFloat(cantEntrada);
     
  //Si no selecciona producto retorna o cantidad
    if(isNaN(idProducto) || isNaN(cantEntrada) || cantEntrada<1 ){
      return true;
    }  
  
  //SEPARA EL CODIGO DEL PROD. SELECCIONADO
  codigosku= producto.substr(0, producto.indexOf('-'));
  codigosku=codigosku.trim();
  //codigointerno=codigointerno.trim();
  codigointerno=codigointerno

    
  //SEPARA LA DESCRIPCION DEL PROD. SELECCIONADO        
  let descripcion= producto.substr(producto.lastIndexOf("-") + 1);
  descripcion.trim();
       
  //console.log("prod:",idProducto, "cant:",cantEntrada, "medida:",udeMedida, "codInt:",codigointerno, "descrip:",descripcion);
  
  let encontrado=arrayEditarProductos.includes(idProducto)
    //console.log("encontrado:", encontrado)
    if(!encontrado){
      arrayEditarProductos.push(idProducto);
      addEditarProductoEntrada(idProducto, cantEntrada, udeMedida, codigointerno, descripcion, codigosku);
    }else{
      mensajedeerror();
    }
  
});  

/*==================================================================
ADICIONA PRODUCTOS AL TBODY
==================================================================*/
function addEditarProductoEntrada(...argsProductos){
  //console.log("manyMoreArgs", argsProductos);
  var contenido=document.querySelector('#tbodyEditarentradasalmacen');
  
  contenido.innerHTML+=`
  <tr class="filas" id="fila${argsProductos[0]}">
    <td class='text-center'><button type="button" class="botonQuitar" onclick="deleteProducts(${argsProductos[0]}, ${argsProductos[1]} )" title="Quitar concepto">X</button></td>
    <td class='text-center'>${argsProductos[0]} <input type="hidden" name="idproducto[]" value="${argsProductos[0]}"</td>
    <td class='text-center'>${argsProductos[5]}</td>
    <td class='text-center'>${argsProductos[3]}</td>
    <td class='text-left'>${argsProductos[4]}</td>
    <td class='text-center'>${argsProductos[2]}</td>
    <td class='text-center'>${argsProductos[1]} <input type="hidden" name="cantidad[]" value="${argsProductos[1]}"</td>
    </tr>
  `;

    renglonesEntradas++;
    cantEntrante+=argsProductos[1];
    evalRowProducts(renglonesEntradas, cantEntrante);
  
    //DESPUES DE AÑADIR, SE INICIALIZAN SELECT E INPUT
    $('#selEditarProdEntAlm').val(null).trigger('change');	
    $("#cantEditarExistenciaAlmacen").val(0);
    $("#cantEditarEntradaAlmacen").val(0);
    $("#btnEditarEntradasAlmacen").show(); 
}

/*======================================================================*/
//= ENVIAR FORMULARIO PARA GUARDAR DATOS DE ENTRADA = 
/* ======================================================================*/
$("body").on("submit", "#form_Editentradasalmacen", function( event ) {	
  event.preventDefault();
  event.stopPropagation();
  $('#EditarProveedorEntrada').prop('disabled',false);
  $('#EditarTipoEntradaAlmacen').prop('disabled',false);
  $('#idEditarAlmacenEntrada').prop('disabled',false);
  let formData = new FormData($("#form_Editentradasalmacen")[0]);   
  //for (var pair of formData.entries()){console.log(pair[0]+ ', ' + pair[1]);} 

  axios({ 
    method: 'post', 
    url   : 'ajax/entradasalmacen.ajax.php?op=guardarEditaEntradasAlmacen', 
    data  : formData, 
  }) 
  .then((res)=>{ 
    if(res.status==200) {
      //console.log(res.data)

      $('#modalEditarEntradasAlmacen').modal('hide')
      $('#dt-entradasalmacen').DataTable().ajax.reload(null, false);
      $("#alert1").removeClass("d-none");
      $("#alert1" ).fadeOut( 4500, "linear", complete );

    }            
    //console.log(res); 
  }) 
  .catch((err) => {throw err}); 

});  
/* ======================================================================*/

//AL ABRIR EL MODAL 
$('#modalEditarEntradasAlmacen').on('show.bs.modal', function (event) {
  $('#EditarProveedorEntrada').prop('disabled',true);
  $('#EditarTipoEntradaAlmacen').prop('disabled',true);
  $('#idEditarAlmacenEntrada').prop('disabled',true);
  renglonEditarEntradas=cantEditarEntrante=0;
  $("#renglonEditarentradas").html("");
  $("#totalEditarentradasalmacen").html("");
  $("#btnEditarEntradasAlmacen").hide();
})
  
/*================ AL SALIR DEL MODAL RESETEAR FORMULARIO ==================*/
$("#modalEditarEntradasAlmacen").on('hidden.bs.modal', ()=> {
$("#agregarProdEntrada").addClass("d-none");
$('#form_entradasalmacen')[0].reset();                //resetea el formulario
$("#cantExistenciaAlmacen").val(0);                   //inicializa campo existencia
$("#cantEntradaAlmacen").val("");                     //inicializa campo salida
$("#tbodyentradasalmacen").empty();                   //vacia tbody
$('#selProdEntAlm').val(null).trigger('change');      //inicializa el select de productos
arrayEditarProductos["length"]=0;                           //inicializa array
});


init();