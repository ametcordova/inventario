var cantexist = 0;
var lDuplicado = false;
var duplicado = false;
var renglonesSalidas = 0;
var cantSaliente = 0;
var udeMedida = "";
var arrayProductos = new Array();
$("#modalAgregarSalidasAlmacen").draggable({
  handle: ".modal-header"
});
//Durante la inicialización de la página
$('.toast').toast();
$('.toast').toast('hide');

$(document).ready(function () {
  $('.modal').on('keydown', function (event) {
    if (event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
//Función que se ejecuta al inicio
function init() {

  /*=============================================
    VARIABLE LOCAL STORAGE
    =============================================*/
  // if(localStorage.getItem("RangoFechaSalidasAlmacen") != null){
  //   console.log("entra localstorage")
  //   $("#daterange-btn-SalAlmacen span").html(localStorage.getItem("RangoFechaSalidasAlmacen"));
  // }else{
  //   //$("#daterange-btn-Ajuste span").html('<i class="fa fa-calendar"></i> Rango de fecha!')
  //   console.log("no entra localstorage")
  //   fechadehoy();
  // }

  fechadehoy();

  $("#btnGuardarSalidasAlmacen").hide();
  $("#rowSalAlma").hide();

  dt_ListarSalidasAlmacen();

}

// ========= LISTAR EN EL DATATABLE REGISTROS DE LA TABLA cajas================
function dt_ListarSalidasAlmacen() {
  let rangodeFecha = $("#daterange-btn-SalAlmacen span").html();
  //console.log("Rango de Fecha:", rangodeFecha);
  if (rangodeFecha == undefined || rangodeFecha == null) {
    var FechDev1 = moment().format('YYYY-MM-DD');
    var FechDev2 = moment().format('YYYY-MM-DD');
  } else {
    let arrayFecha = rangodeFecha.split(" ", 3);
    let f1 = arrayFecha[0].split("-");
    let f2 = arrayFecha[2].split("-");

    var FechDev1 = f1[2].concat("-").concat(f1[1]).concat("-").concat(f1[0]); //armar la fecha año-mes-dia

    var FechDev2 = f2[2].concat("-").concat(f2[1]).concat("-").concat(f2[0]);
  }

  //console.log(FechDev1, FechDev2);

  tablaSalidasAlmacen = $('#dt-salidasalmacen').dataTable(
    {
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
      "language": {
        "url": "extensiones/espanol.json",
      }, "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      },
      dom: '<clear>Bfrtip',
      buttons: [
        {
          text: 'Copiar',
          extend: 'copy'
        },
        'excelHtml5',
        'csvHtml5',
        {
          extend: 'pdfHtml5',
          orientation: 'landscape',
          title: "AdminLTE",
          customize: function (doc) {
            pdfMake.createPdf(doc).open();
          },
        },

        {
          extend: 'print',
          text: 'Imprimir',
          className: 'btn btn-success btn-sm',
          autoPrint: false            //TRUE para abrir la impresora
        },
        {
          text: 'Reload',
          className: 'orange',
          action: function (e, dt, node, config) {
            dt_ListarSalidasAlmacen();
            //alert( 'Button activated' );
          }
        }
      ],
      initComplete: function () {
        var btns = $('.dt-button');
        btns.removeClass('dt-button');
        btns.addClass('btn btn-success btn-sm');
      },
      "columnDefs": [
        { className: "dt-center", targets: [0, 1, 6] },
        { orderable: false, targets: [6] },    //columna que no debe ordenarse
      ],
      select: false,     //se puso a false para poder seleccionar varios filas. true=1 fila
      scrollX: true,
      "ajax":
      {
        url: 'ajax/salidasalmacen.ajax.php?op=listar',
        data: { "FechDev1": FechDev1, "FechDev2": FechDev2 },
        type: "POST",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        }
      },
      "bDestroy": true,
      "iDisplayLength": 10,//Paginación
      "order": [[0, "desc"]]//Ordenar (columna,orden)
    }).DataTable();

}

/*======================================================================*/
// === AGREGA CANT EXISTENTE Y VALIDA LA CANT SALIENTE ===
/* ======================================================================*/
$("#selProdSalAlm").change(function (event) {
  event.preventDefault();
  // si viene vacio el select2 que regrese en false
  if ($(this).val() == "") {
    return false;
  }
  let idalmacen = $("#idAlmacenSalida").val();
  let idtiposalida = $("#idTipoSalidaAlmacen").val();
  idalmacen = parseInt(idalmacen);
  idtiposalida = parseInt(idtiposalida);
  //console.log(idalmacen, idtiposalida)
  if (idalmacen == 0 || idtiposalida == 0) {
    $('span#msjdeerror').html(`<label style='color:red'>${"¡Seleccione Tipo y Almacen de salida!!"} </label>`);
    $("#idAlmacenSalida").focus(function () {
      $("span#msjdeerror").css("display", "inline").fadeOut(4000);
    });
    $('#selProdSalAlm').val(null).trigger('change');
    $("#idAlmacenSalida").focus();
    return false;
  } else if (isNaN(idalmacen)) {
    $('span#msjNoSeleccionado').html(`<label style='color:red'>${"¡Seleccione Almacen!!"} </label>`);
    $("#idAlmacenSalida").focus(function () {
      $("span#msjNoSeleccionado").css("display", "inline").fadeOut(4000);
    });
    $('#selProdSalAlm').val(null).trigger('change');
    $("#idAlmacenSalida").focus();
    return false;
  }

  let almacen = $("#idAlmacenSalida option:selected").text();
  let idProducto = $("#selProdSalAlm").val();
  idProducto = parseInt(idProducto);
  almacen = almacen.toLowerCase();
  //console.log("entra1: ", idalmacen, almacen, idProducto);

  //HACER UN FETCH SI EXIST PROD Y TRAE EXISTENCIA
  let data = new FormData();
  data.append('almacen', almacen);
  data.append('idProducto', idProducto);

  fetch('ajax/salidasalmacen.ajax.php?op=consultaExistenciaProd', {
    method: 'POST',
    body: data
  })
    .then(response => response.json())
    .then(data => {
      //console.log(data)
      if (data === false) {
        //console.log("No existe Art");
        $("#cantExisteAlmacen").val(0);
        $("#cantSalidaAlmacen").val("");
        $('#mensajerrorsalida').text('Prod. no existe en este Almacen!!');
        $("#mensajerrorsalida").removeClass("d-none");
        setTimeout(function () { $("#mensajerrorsalida").addClass("d-none") }, 2500);
      } else {
        $("#cantExisteAlmacen").val(data.cant);
        udeMedida = data.medida;
        if (data.conseries == 1 && data.cant > 0) {
          $("#agregaSeries").removeClass("d-none");
        }
      }
    })
    .catch(error => console.error(error))
})  //fin del selecProductoSalida


//VERIFICA CANT NO SEA MAYOR QUE LA EXISTENCIA
$("#cantSalidaAlmacen").change(function (event) {
  event.preventDefault();
  $("#mensajerrorsalida").addClass("d-none");
  cantexist = $("#cantExisteAlmacen").val();
  cantSolicitada = $("#cantSalidaAlmacen").val();
  if (parseFloat(cantSolicitada) > parseFloat(cantexist)) {
    $('#mensajerrorsalida').text('Cantidad Solicitada es Mayor a la Existencia!!');
    $("#mensajerrorsalida").removeClass("d-none");
    $("#cantSalidaAlmacen").val(0);
  } else {
    $("#mensajerrorsalida").addClass("d-none");
  }
});

/*============================================================
                AGREGA PRODUCTO SELECCIONADO
============================================================*/
$("#agregaSalidaProd").click(function (event) {
  event.preventDefault();
  cantexist = $("#cantExisteAlmacen").val();
  let idProducto = $("#selProdSalAlm").val();
  idProducto = parseInt(idProducto);

  let producto = $("#selProdSalAlm option:selected").text();       //obtener el texto del valor seleccionado
  let cantcap = $("#cantSalidaAlmacen").val();
  cantidad = parseFloat(cantcap);

  //Si no selecciona producto retorna o cantidad
  if (isNaN(idProducto) || isNaN(cantidad) || cantidad < 1) {
    return true;
  }

  //SEPARA EL CODIGO DEL PROD. SELECCIONADO
  let codigointerno = producto.substr(0, producto.indexOf('-'));
  codigointerno.trim();

  //SEPARA LA DESCRIPCION DEL PROD. SELECCIONADO        
  let descripcion = producto.substr(producto.lastIndexOf("-") + 1);
  descripcion.trim();

  //console.log("prod:",idProducto, "cant:",cantidad, "medida:",udemedida, "codInt:",codigointerno, "descrip:",descripcion);

  let encontrado = arrayProductos.includes(idProducto)
  let mLocalS = "";
  let malfanumericos = [];
  //console.log("encontrado:", encontrado)
  if (!encontrado) {
    arrayProductos.push(idProducto);
    if (localStorage.getItem('alfas')) {
      mLocalS = localStorage.getItem('alfas');
      malfanumericos = JSON.parse(mLocalS);
      //console.log(malfanumericos);
      localStorage.removeItem('alfas');
    }
    //añade material seleccionado
    addProductosSalida(idProducto, cantidad, udeMedida, codigointerno, descripcion, malfanumericos);

  } else {
    mensajedeerror();
  }

});

/*==================================================================
ADICIONA PRODUCTOS AL TBODY
==================================================================*/
function addProductosSalida(...argsProductos) {
  let seriesWithSpaces="";
  let hayalfa=false;
  //console.log("manyMoreArgs", argsProductos);
  var contenido = document.querySelector('#tbodysalidasalmacen');
  console.log("largo alfa", argsProductos[5].length);
  //let sihayalfa = argsProductos[5].length > 0 ? true : false;
  if(argsProductos[5].length > 0){
      //añadir un espacio despues de la coma para que pueda hacer salto de linea en el span
      seriesWithSpaces = argsProductos[5].map(function(value) { 
        return " "+value;
    });
    hayalfa=true;
  }

  contenido.innerHTML += `
<tr class="filas" id="fila${argsProductos[0]}">
  <td><button type="button" class="botonQuitar" onclick="eliminarProducto(${argsProductos[0]}, ${argsProductos[1]})" title="Quitar concepto">X</button></td>
  <td class='text-center'>${argsProductos[0]} <input type="hidden" name="idproducto[]" value="${argsProductos[0]}"</td>
  <td class='text-center'>${argsProductos[3]} <input type="hidden" name="idalfanumericos[]" value="${argsProductos[5]}"</td>
  <td class='text-left'>${argsProductos[4]}
    <span class='text-left spanalfa .overflow-visible'>${hayalfa ? seriesWithSpaces : ''}</span>
  </td>
  <td class='text-center'>${argsProductos[2]}</td>
  <td class='text-center'>${argsProductos[1]} <input type="hidden" name="cantidad[]" value="${argsProductos[1]}"</td>
  </tr>
`;
  renglonesSalidas++;
  cantSaliente += argsProductos[1];
  evaluaFilas(renglonesSalidas, cantSaliente);

  //DESPUES DE AÑADIR, SE INICIALIZAN SELECT E INPUT
  $('#selProdSalAlm').val(null).trigger('change');
  //$("#cantExisteAlmacen").val("");
  $("#cantExisteAlmacen").val(0);
  $("#cantSalidaAlmacen").val(0);
  $("#agregaSeries").addClass("d-none");
  //$('#selecProductoAjuste').select2('open');

}
/*==================================================================*/
function evaluaFilas(totalRenglon, cantSaliente) {
  $("#renglonsalidas").html(totalRenglon);
  $("#totalsalidasalmacen").html(cantSaliente);
  if (totalRenglon > 0) {
    $("#btnGuardarSalidasAlmacen").show();
    $("#rowSalAlma").show();
  } else {
    //$("#btnGuardar").hide();
  }
}
/*==================================================================*/
//QUITA ELEMENTO 
function eliminarProducto(indice, restarcantidad) {
  console.log("entra a eliminar", indice)
  $("#fila" + indice).remove();
  cantSaliente -= restarcantidad;
  renglonesSalidas--;
  removeItemFromArr(arrayProductos, indice)
  evaluaFilas(renglonesSalidas, cantSaliente);
  evaluarElementos();
}
/*==================================================================*/
//SI NO HAY ELEMENTOS count SE INICIALIZA
function evaluarElementos() {
  if (!renglonesSalidas > 0) {
    renglonesSalidas = 0;
    $("#btnGuardarSalidasAlmacen, #btnEditSalidasAlmacen").hide();
    $("#rowSalAlma, #EditrowSalAlma").hide();
  }
}
/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR DATOS DE SALIDA DE ALMACEN
/*======================================================================*/
$("body").on("submit", "#form_salidasalmacen", function (event) {
  event.preventDefault();
  event.stopPropagation();
  let tecnique = $("#idTecnicoRecibe option:selected").text();
  let fechaSalidaAlmacen = $("input[name='nvaFechaSalidaAlmacen']").val();
  let formData = new FormData($("#form_salidasalmacen")[0]);
  // Se listan los pares clave/valor
  //for (let [name, value] of formData) { console.log(`${name} = ${value}`); }

  swal({
    title: `¿Estas SEGURO de guardar salida a nombre de ${tecnique} con fecha ${fechaSalidaAlmacen} ?`,
    text: "¡Si no lo está pulse Cancelar",
    icon: "warning",
    buttons: ["Cancelar", "Sí, Guardar"],
    dangerMode: true,
  })
    .then((aceptado) => {
      if (aceptado) {
        axios({
          method: 'post',
          url: 'ajax/salidasalmacen.ajax.php?op=guardarSalidasAlmacen',
          data: formData,
        })
          .then((res) => {
            if (res.status == 200) {
              console.log(res.data)

              $('#modalAgregarSalidasAlmacen').modal('hide')
              $('#dt-salidasalmacen').DataTable().ajax.reload(null, false);
              $("#alerta").removeClass("d-none");
              $("#alerta").fadeOut(4500, "linear", complete);


            }
            console.log(res);
          })
          .catch((err) => { throw err });

      } else {
        return false;
      }
    });

});

function complete() {
  $("#alerta").addClass("d-none")
}

//AL ABRIR EL MODAL TRAER EL ULTIMO NUMERO
$('#modalAgregarSalidasAlmacen').on('show.bs.modal', function (event) {
  $("#idNumSalAlm").val(0);
  $("#form_salidasalmacen")[0].reset();
  $("#tbodysalidasalmacen").empty();
  $("#btnGuardarSalidasAlmacen").hide();
  $("#rowSalAlma").hide();

  UltimoIdSalidasAlmacen();		//TRAE EL SIGUIENTE NUMERO 

})

/******************************************************************* */
$("#agregaSeries").click(function (event) {
  event.preventDefault();
  let idtecnique = $("#idTecnicoRecibe option:selected").val();
  let nombrealmacen = $("#idAlmacenSalida").val();
  let idalmacen = nombrealmacen.substring(0, nombrealmacen.indexOf("-"));
  let idProducto = $("#selProdSalAlm").val();
  idProducto = parseInt(idProducto);

  let cantcap = $("#cantSalidaAlmacen").val();
  let seriessaliente = parseFloat(cantcap);

  //Si no selecciona producto retorna o cantidad
  if (isNaN(idProducto) || isNaN(seriessaliente) || seriessaliente < 1) {
    return true;
  }

  console.log("idProducto:", idProducto, "cantidad:", seriessaliente, "idTecnico:", idtecnique, "idalmacen:", idalmacen)
  generarInputs(seriessaliente, idProducto);

})
/******************************************************************* */
// FALTA VALIDAR QUE ESTÉ DISPONIBLE EL ALFANUMERICO
function generarInputs(iSeries, idprod) {
  let divInputs = document.getElementById("inputs");
  divInputs.innerHTML = ``;
  //series = ["PING16340DE3","PING16340D52","PING16340D78","PING16340D4F","PING16340D4D","PING16340DA4","PING16340D39","PING16340F3D","PING16340F4C","PING16340D52","ALCLF227568B","ALCLF20A5E29"];${series[i]}
  for (var i = 0; i < iSeries; i++) {
    divInputs.innerHTML += `<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="fa fa-hashtag"></i></span>
    </div>
      <input type="text" class="form-control form-control-sm" placeholder="Número" name="nvoAlfaNum[]" value="" id="serie${i}" required tabindex="1" title="Número" onblur="validalfa('serie${i}',${iSeries},${i})">
      <input type="hidden" name="idprod[]" value="${idprod}">
    </div>
  `
  }
}
/******************************************************************* */
function validalfa(idalfanum, iSeries, i) {
  let value = document.getElementById(idalfanum).value;
  console.log(idalfanum, value);
  (async () => {
    await axios.get('ajax/salidasalmacen.ajax.php?op=validAlfanumerico', {
      params: {
        idalfa: value,
      }
    })
      .then((res) => {
        //console.log(res)
        //console.log(parseInt(iSeries)-1, i)
        // if((parseInt(iSeries)-1)===parseInt(i)){
        //   document.getElementById("adicionaAlfa").classList.remove("d-none");
        // }

        if (res.data === false) {           //&& res.data==value
          let msginputsalfa = document.getElementById("msginputsalfa");

          msginputsalfa.innerHTML = `
              <div class="toast text-center" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="true" data-delay="3000">
              <div class="toast-header text-white bg-warning">
              <div class="rounded mr-2" style="height: 16px;width: 16px;background-color: red;"></div>
              <strong class="mr-auto">Mensaje</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="toast-body text-white bg-dark">
                Alfanúmerico ${value.trim()} no existe o ya fue asignado
              </div>
            </div>
            `;
          $('.toast').toast('show');
          document.getElementById("serie" + i).value = "";
          //document.getElementById("serie"+i).focus();
          return false;
        }

      })
      .catch((err) => { throw err });

  })();  //fin del async  

}
/******************************************************************* */
$("#adicionaAlfa").click(function (ev) {
  ev.preventDefault();
  let formData = new FormData($("#formularioAgregaSeries")[0]);
  for (var pair of formData.entries()) {
    //console.log(pair[0] + ', ' + pair[1]);
    if (pair[1].length === 0) {
      return false;
    }
  }
  const alfas = formData.getAll('nvoAlfaNum[]');
  localStorage.setItem("alfas", JSON.stringify(alfas));

  $("#modalAgregarSeries").modal("hide");

});
/******************************************************************* */
$('#modalAgregarSeries').on('shown.bs.modal', function () {
  $('#serie0').trigger('focus')
})
/*================ AL SALIR DEL MODAL DE SERIES RESETEAR FORMULARIO==================*/
$("#modalAgregarSeries").on('hidden.bs.modal', () => {
  $("#formularioAgregaSeries")[0].reset();
});
/*************************************************************************************/
/*================ AL SALIR DEL MODAL DE EDICION  RESETEAR FORMULARIO==================*/
$("#modalAgregarSalidasAlmacen").on('hidden.bs.modal', () => {
  arrayProductos["length"] = 0;                  //inicializa array
});

//TRAER EL ULTIMO ID GUARDADO
async function UltimoIdSalidasAlmacen() {
  let resulta = 0;
  let response = await fetch("ajax/salidasalmacen.ajax.php?op=obtenerUltimoId");
  let result = await response.json();
  //console.log(result.id);
  if (result.id === null) {
    $("#idNumSalAlm").val(1);
  } else {
    resulta = parseInt(result.id) + 1;
    $("#idNumSalAlm").val(resulta);
  }
}

// DESELECCIONA ALMACEN PARA EVITAR CAMBIARLO
$("#idAlmacenSalida").change(function () {
  $('#idAlmacenSalida option:not(:selected)').attr('disabled', true);
  $('span#msjdeerror').html("");
});
/*************************************************************************************/
//FUNCION PAR MENSAJE DE ERROR
function mensajedeerror() {
  $("#cantSalidaAlmacen, #EditcantSalidaAlmacen").val(0);
  $('#Editmensajerrorsalida, #mensajerrorsalida').text('Producto ya capturado. Revise!!');
  $("#mensajerrorsalida, #Editmensajerrorsalida").removeClass("d-none");
  setTimeout(function () { $("#mensajerrorsalida, #Editmensajerrorsalida").addClass("d-none") }, 2500);
  return true;
}
/*************************************************************************************/
// function mensajedeerror(){
//   $("#cantSalidaAlmacen, #EditcantSalidaAlmacen").val(0);
//   $('#Editmensajerrorsalida, #mensajerrorsalida').text('Producto ya capturado. Revise!!');
//   $("##mensajerrorsalida, #Editmensajerrorsalida").removeClass("d-none");
//   setTimeout(function(){$("#mensajerrorsalida, #Editmensajerrorsalida").addClass("d-none")}, 2500);
//   return true;
// }

$('#daterange-btn-SalAlmacen').daterangepicker({
  ranges: {
    'Hoy': [moment(), moment()],
    'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    'Últimos 7 Días': [moment().subtract(6, 'days'), moment()],
    'Últimos 30 Días': [moment().subtract(29, 'days'), moment()],
    'Este Mes': [moment().startOf('month'), moment().endOf('month')],
    'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
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
      "Setiembre",
      "Octubre",
      "Noviembre",
      "Diciembre"
    ],
    "firstDay": 1
  },
  //start: moment(),
  //end : moment()

  startDate: moment(),
  endDate: moment()
},
  function (start, end) {
    $('#daterange-btn-SalAlmacen span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));

    var RangoFechaSalidasAlmacen = $("#daterange-btn-SalAlmacen span").html();
    //console.log("rango de fecha:",valorRangoAjusteInv);

    localStorage.setItem("daterange-btn-SalAlmacen", RangoFechaSalidasAlmacen);

  }
)

/*=============================================
ASIGNA FECHA ACTUAL EN DATERANGEPICKER 
=============================================*/
function fechadehoy() {

  let fechaInicial = moment().add(-30, 'day').format('DD-MM-YYYY');
  let fechaFinal = moment().format('DD-MM-YYYY');
  $("#daterange-btn-SalAlmacen span").html(fechaInicial + ' - ' + fechaFinal);
  //console.log(fechaInicial+' - '+fechaFinal);
  localStorage.setItem("RangoFechaSalidasAlmacen", fechaInicial + ' - ' + fechaFinal);

}

/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/
$('#daterange-btn-SalAlmacen').on('cancel.daterangepicker', function (ev, picker) {
  localStorage.removeItem("RangoFechaSalidasAlmacen ");
  localStorage.clear();
  $("#daterange-btn-SalAlmacen span").html('<i class="fa fa-calendar"></i> Rango de fecha')
});


/*===================================================
ENVIA REPORTE DE SALIDA DE ALMACEN DESDE EL DATATABLE
===================================================*/
$("#dt-salidasalmacen tbody").on("click", "button.btnPrintSalidaAlmacen", function () {
  let idPrintSalida = $(this).attr("idPrintSalida");
  //console.log(idPrintSalida);
  if (idPrintSalida.length > 0) {
    window.open("extensiones/tcpdf/pdf/reporte_salida.php?codigo=" + idPrintSalida, "_blank");
  }
})

/*===================================================
ELIMINAR SALIDA DE ALMACEN DESDE EL DATATABLE
===================================================*/
$("#dt-salidasalmacen tbody").on("click", "button.btnDelSalidaAlmacen", function (event) {
  event.preventDefault();
  let idaborrar = $(this).attr("idDeleteSalAlm");
  console.log(idaborrar);

  swal({
    title: "¿Está seguro de Eliminar Salida No." + idaborrar + "? ",
    text: "Si no lo esta puede cancelar la acción!",
    icon: "vistas/img/logoaviso.jpg",
    buttons: {
      cancel: "Cancelar",
      defeat: "Borrar",
    },
    dangerMode: true,
  })
    .then((willDelete) => {
      if (willDelete) {
        axios.post('ajax/salidasalmacen.ajax.php?op=deleteIdSalidaAlmacen', { idaborrar: idaborrar })

          .then(res => {
            console.log(res.data);
            if (res.data == "error") {

              swal({
                title: "¡Error!",
                text: 'No fue posible eliminar Salida, revise existencias!!',
                icon: "error",
                button: "Cerrar"
              })  //fin swal

            } else {
              $('#dt-salidasalmacen').DataTable().ajax.reload(null, false);
            }


          })
          .catch((err) => { throw err });
      } else {
        return
      }
    })
})

/**
  // Obtener el valor de localstorage y pasarlo a otro campo de formulario
  //var storedVal = localStorage.getItem("inputVal");
  //document.getElementsByName("otroInputName")[0].value = storedVal;
  //console.log(storedVal)
  // Iterar sobre todos los elementos de localstorage
  for (var key in localStorage) {
    // Comprobar si el elemento es propio de localstorage y no heredado
    if (localStorage.hasOwnProperty(key)) {
      // Mostrar el elemento en la consola
      console.log(key + " = " + localStorage.getItem(key));
    }
  }
 
 */
init();