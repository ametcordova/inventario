
axios.defaults.xsrfCookieName = 'csrftoken';
axios.defaults.xsrfHeaderName = 'X-CSRFToken';

$("#modalIngresos, #modalEgresos, #update_ingresos, #update_egresos").draggable({
    handle: ".modal-header"
});

//Función que se ejecuta al inicio
function init(){

    $("#form_ingreso").on("submit",function(e){
        agregarIngreso(e);	
    });

    $("#form_update_ingreso").on("submit",function(e){
        modificarIngreso(e);	
    });


    $("#form_egreso").on("submit",function(e){
        agregarEgreso(e);	
    });

    $("#form_update_egreso").on("submit",function(e){
      modificarEgreso(e);	
    });
  
}


$('#fechaListar').on('change', function() {
  let fechaListar=$("input[name='fechaListar']").val();
  console.log(fechaListar)
  var tablaIngresos='ingresos';
  var tablaEgresos='egresos';
    
  getDataI(tablaIngresos,fechaListar);
  getDataE(tablaEgresos,fechaListar);  

});

/*=============================================
TRAER LOS DATOS DE INGRESOS 
=============================================*/
function getDataI(tablaIngresos,fechaListar=null){
//console.log(fechaListar)

//const datas = new FormData();
//datas.append('fechalistar', fechaListar);
//datas.append('tabla', tablaIngresos);	
	
//for (var pair of datas.entries()){console.log(pair[0]+ ', ' + pair[1]);}
// (async () => { 
//   await fetch('ajax/control-presupuesto.ajax.php?op=obtenerRegistros', {
//      method: 'GET',
//      body: datas
//   })
//   .then(res=> res.json())
// 	.then(datos=>{
// 		console.log(datos);
// 		table_ingreso(datos);
// 	})
// })();  //fin del async
(async () => { 
  await axios.get('ajax/control-presupuesto.ajax.php?op=obtenerRegistros', {
    params: {
      fechalistar: fechaListar,
      tabla: tablaIngresos
    }  
  })
  .then(response => {
    console.log(response.data);
    table_ingreso(response.data);
  }).catch(e => {
    console.log(e);
  });
})();


} //fin de la funcion

function table_ingreso(datos){
//console.log(datos)
var contenido=document.querySelector('.items_ingresos');
let ctrlefeview=$('#ctrlefeview').val(); 
let ctrlefedelete=$('#ctrlefedelete').val(); 
//contenido.innerHTML='';	    

var importetotal=0;
var pageTotalIng=0;
$('#ingresototaldia').empty();
$('#items_ingresos').empty();
if(datos!==null){
  for(let valor of datos){
    if(ctrlefeview==1){
      efeedit=`<a href="#"  data-target="#update_ingresos" class="edit" data-toggle="modal" data-id='${valor.id}' data-fecha='${valor.fecha_ingreso}' data-descripcion='${valor.descripcion_ingreso}' data-concepto='${valor.concepto_ingreso}' data-importe='${valor.importe_ingreso}'><i class="fa fa-pencil" data-toggle="tooltip" title="Editar"></i></a>&nbsp&nbsp`;
    }else{
      efeedit=``;
    }    

    efedelete=(ctrlefedelete==1)?`<a href="#" onclick="eliminar_ingreso(${valor.id})"><i class="fa fa-trash" data-toggle="tooltip" title="Eliminar" style="color:#ff3300"></i></a>`:efedelete=``;

    //var varjs="'.$numSalidaAlmacen.'";		//convierte variable PHP a JS
    importetotal+=parseFloat(valor.importe_ingreso);
    contenido.innerHTML+=`
    <tr>
		<td class='text-center'>${valor.id}</td>
		<td class='text-center'>${valor.concepto_ingreso}</td>
    <td class='text-center'>${valor.id_caja}</td>
    <td class='text-right'>${valor.importe_ingreso}</td>
    <td class='text-center'>      
			${efeedit} ${efedelete}
		</td>
    </tr>`;
  }  //fin del for
 } 
    pageTotalIng=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(importetotal);
    $('#ingresototaldia').append(pageTotalIng);
  }

/*=============================================
TRAER LOS DATOS DE EGRESOS
=============================================*/
function getDataE(tablaEgresos,fechaListar=null){

  // const datas = new FormData();
  // datas.append('fechalistar', fechaListar);
  // datas.append('tabla', tablaEgresos);	

  //for (var pair of datas.entries()){console.log(pair[0]+ ', ' + pair[1]);}
  axios.get('ajax/control-presupuesto.ajax.php?op=obtenerRegistros', {
    params: {
      fechalistar: fechaListar,
      tabla: tablaEgresos
    }
  })
.then(response => {
    //this.user = response.data;
    console.log(response.data);
    table_egreso(response.data);
}).catch(e => {
    console.log(e);
});
  
  //   fetch('ajax/control-presupuesto.ajax.php?op=obtenerRegistros', {
  //      method: 'GET',
  //      body: datas
  //   })
  //   .then(res=> res.json())
  //   .then(datos=>{
  //     console.log(datos);
  //     table_egreso(datos);
  //   })
    
}

  function table_egreso(datos){
  //console.log(datos)
  var importetotal=0;
  var pageTotalEgr=0;
  var contenido=document.querySelector('.items_egresos');
  let ctrlefeview=$('#ctrlefeview').val(); 
  let ctrlefedelete=$('#ctrlefedelete').val(); 
  //contenido.innerHTML='';	

  $('#egresototaldia').empty();
  $('#items_egresos').empty();
  if(datos!==null){
  
    contenido.innerHTML='';	
    for(let valor of datos){
    //console.log(valor.id)
    efeedit=(ctrlefeview==1)?`<a href="#"  data-target="#update_egresos" class="edit" data-toggle="modal" data-id='${valor.id}' data-fecha='${valor.fecha_egreso}' data-descripcion='${valor.descripcion_egreso}' data-concepto='${valor.concepto_egreso}' data-importe='${valor.importe_egreso}'><i class="fa fa-pencil" data-toggle="tooltip" title="Editar"></i></a>&nbsp&nbsp`:efeedit=``;
    efedelete=(ctrlefedelete==1)?`<a href="#" onclick="eliminar_egreso(${valor.id})"> <i class="fa fa-trash" data-toggle="tooltip" title="Eliminar" style="color:#ff3300"></i></a>`:``;

    importetotal+=parseFloat(valor.importe_egreso);
     contenido.innerHTML+=`
      <tr>
      <td class='text-center'>${valor.id}</td>
      <td class='text-center'>${valor.concepto_egreso}</td>
      <td class='text-center'>${valor.id_caja}</td>
      <td class='text-right'>${valor.importe_egreso}</td>
      <td class='text-center'>
      ${efeedit} ${efedelete}
      </td>
      </tr>`;
    }
  }
    //$('#egresototaldia').append('$'+(parseFloat(importetotal).toFixed(2)));
	pageTotalEgr=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(importetotal);
	$('#egresototaldia').append(pageTotalEgr);
}

/*=============================================
AGREGAR INGRESO
=============================================*/
function agregarIngreso(e){
  e.preventDefault(); //No se activará la acción predeterminada del evento
    const data = new FormData($("#form_ingreso")[0]);

     //for (var pair of data.entries()){console.log(pair[0]+ ', ' + pair[1]);}

     fetch('ajax/control-presupuesto.ajax.php?op=guardarIngreso', {
        method: 'POST',
        body: data
     })
     .then(respuestapositivacrlefe)
     .catch(showError);   
     
}     

/*=============================================
MODIFICAR INGRESO
=============================================*/
function modificarIngreso(e){
  e.preventDefault(); //No se activará la acción predeterminada del evento
    const data = new FormData($("#form_update_ingreso")[0]);

     //for (var pair of data.entries()){console.log(pair[0]+ ', ' + pair[1]);}

     fetch('ajax/control-presupuesto.ajax.php?op=modificarIngreso', {
        method: 'POST',
        body: data
     })
     .then(respuestaModificacion)
     .catch(showError);   
     
}     

/*=============================================
AGREGAR EGRESO
=============================================*/
function agregarEgreso(e){
	e.preventDefault(); //No se activará la acción predeterminada del evento
    const data = new FormData($("#form_egreso")[0]);

     //for (var pair of data.entries()){console.log(pair[0]+ ', ' + pair[1]);}

     fetch('ajax/control-presupuesto.ajax.php?op=guardarEgreso', {
        method: 'POST',
        body: data
     })
     .then(respuestapositivacrlefe)
     .catch(showError);   
     
}     

function respuestapositivacrlefe(response) {
      //console.log('response.ok: ', response.ok);
      let fechaListar=$("input[name='fechaListar']").val();
      getDataI(tabla='ingresos',fechaListar);
      getDataE(tabla='egresos',fechaListar);
  
      $('#modalIngresos').modal('hide');
      $('#modalEgresos').modal('hide');

        Swal.fire({
          title: "Realizado!!",
          text: "Ingreso/Egreso Guardado",
          imageUrl: "config/money.png",
          allowOutsideClick:false,
          allowEscapeKey:true,
          timer: 3000,
          showClass: {
            popup: 'animate__animated animate__fadeInDown'
          },
          hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
          }          
          })           //fin swal

          .then(function(result){
            if (result.value) {
            }
        }); //fin .then
               
      if(response.ok) {
        response.text().then(showResultCP);
      } else {
        showError('status code: ' + response.status);
      }
}

/*=============================================
MODIFICAR EGRESO
=============================================*/
function modificarEgreso(e){
  e.preventDefault(); //No se activará la acción predeterminada del evento
    const data = new FormData($("#form_update_egreso")[0]);

     //for (var pair of data.entries()){console.log(pair[0]+ ', ' + pair[1]);}

     fetch('ajax/control-presupuesto.ajax.php?op=modificarEgreso', {
        method: 'POST',
        body: data
     })
     .then(respuestaModificacion)
     .catch(showError);   
     
}

function respuestaModificacion(response) {
    //console.log('response.ok: ', response.ok);
    let fechaListar=$("input[name='fechaListar']").val();
    getDataI(tabla='ingresos',fechaListar);
    getDataE(tabla='egresos',fechaListar);
    $('#update_ingresos').modal('hide');
    $('#update_egresos').modal('hide');

      Swal.fire({
        title: "Realizado!!",
        text: "Ingreso/Egreso Actualizado",
        imageUrl: "config/money.png",
        allowOutsideClick:false,
        allowEscapeKey:true,
        timer: 3000,
        showClass: {
          popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
          popup: 'animate__animated animate__fadeOutUp'
        }          
        })           //fin swal

        .then((result)=>{
          if (result.value) {
      
          }
      })  //fin .then
             
    if(response.ok) {
      response.text().then(showResultCP);
    } else {
      showError('status code: ' + response.status);
    }
}


$('#update_ingresos').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var id = button.data('id') 
  $('#edit_id').val(id)
  var fecha = button.data('fecha') 
  $('#editfechaIngreso').val(fecha)
  var descripcion = button.data('descripcion') 
  $('#editdescripcionIngreso').val(descripcion)
  var concepto = button.data('concepto') 
  $('#editconceptoIngreso').val(concepto)
  var importe = button.data('importe') 
  $('#editimporteIngreso').val(importe)
  let modal=$(this);
  modal.find('#title_ingreso').text('Modificar Ingreso');
})

$('#update_egresos').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // boton que accionó el modal
  var id = button.data('id')          //info de atributos de datos
  $('#editid').val(id)
  var fecha = button.data('fecha') 
  $('#editfechaEgreso').val(fecha)
  var descripcion = button.data('descripcion') 
  $('#editdescripcionEgreso').val(descripcion)
  var concepto = button.data('concepto') 
  $('#editconceptoEgreso').val(concepto)
  var importe = button.data('importe') 
  $('#editimporteEgreso').val(importe)
  let modal=$(this);
  modal.find('#title_egreso').text('Modificar Egreso');
})

function eliminar_ingreso(id){
 const datas = new FormData();
  datas.append('id', id);	
  datas.append('tabla', 'ingresos');	
  fetch('ajax/control-presupuesto.ajax.php?op=eliminar', {
    method: 'POST',
    body: datas
 })

 .then(resPositivaEliminaIng)
 .catch(showError);   

}

function eliminar_egreso(id){
  const datas = new FormData();
   datas.append('id', id);	
   datas.append('tabla', 'egresos');	
   fetch('ajax/control-presupuesto.ajax.php?op=eliminar', {
     method: 'POST',
     body: datas
  })
 
  .then(resPositivaEliminaIng)
  .catch(showError);   
 
 }
 
function resPositivaEliminaIng(response) {
      //console.log('response.ok: ', response.ok);
      let fechaListar=$("input[name='fechaListar']").val();
      getDataI(tabla='ingresos',fechaListar);
      getDataE(tabla='egresos',fechaListar);
      Swal.fire({
        title: "Realizado!!",
        text: "Registro Eliminado!!",
        icon: "success",
        allowOutsideClick:false,
        allowEscapeKey:false,
        timer: 3000,
        confirmButtonText: 'Entendido',
        })  //fin swal
        .then((result)=>{
          if (result.value) {
          }
      })  //fin .then
             
    if(response.ok) {
      response.text().then(showResultCP);
    } else {
      showErrorCP('status code: ' + response.status);
    }
}

function showResultCP(txt) {
  console.log('muestro respuesta: ', txt);
}

function showErrorCP(err) { 
  console.log('muestra error', err);
  Swal.fire({
      title: "Error!!",
      text: err,
          icon: "error",
      })  //fin swal
    window.location = "inicio";
}


$(document).ready(function (){ 
  let fechaListar=$("input[name='fechaListar']").val();
  //console.log(fechaListar)
  var tablaIngresos='ingresos';
  var tablaEgresos='egresos';
    
  getDataI(tablaIngresos,fechaListar);
  getDataE(tablaEgresos,fechaListar);
  
});

/*================ AL SALIR DEL MODAL DE INGRESOS Y EDICION RESETEAR FORMULARIO==================*/
$("#modalIngresos, #modalEgresos, #update_ingresos, #update_egresos").on('hidden.bs.modal', ()=> {
	$('#form_ingreso, #form_egreso, #form_update_ingreso, #form_update_egreso')[0].reset();
});

$('#modalEgresos').on('show.bs.modal', function (e) {
	$('#form_egreso')[0].reset();
})

init();

/*
    <tr>
		<td class='text-center'>${valor.id}</td>
		<td class='text-center'>${valor.concepto_ingreso}</td>
    <td class='text-center'>${valor.id_caja}</td>
    <td class='text-right'>${valor.importe_ingreso}</td>
    <td class='text-center'>      
			<a href="#"  data-target="#update_ingresos" class="edit" data-toggle="modal" data-id='${valor.id}' data-fecha='${valor.fecha_ingreso}' data-descripcion='${valor.descripcion_ingreso}' data-concepto='${valor.concepto_ingreso}' data-importe='${valor.importe_ingreso}'><i class="fa fa-pencil" data-toggle="tooltip" title="Editar"></i></a>&nbsp&nbsp
  		<a href="#" onclick="eliminar_ingreso(${valor.id})"><i class="fa fa-trash" data-toggle="tooltip" title="Eliminar" style="color:#ff3300"></i></a>
		</td>
    </tr>

axios.get('ajax/control-presupuesto.ajax.php?op=obtenerRegistros', {
    params: {
      fechalistar: fechaListar,
      tabla: tablaEgresos
    },
    headers: {
      'Access-Control-Allow-Origin': '*',
      'Access-Control-Allow-Methods': '*'
  }
  })

*/