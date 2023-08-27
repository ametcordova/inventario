$("#modalEditarOS").draggable({
    handle: ".modal-header"
});
var tbodyOS = document.querySelector('#editaTbodyOS');
var arrayitems = new Array();
localStorage.removeItem("numeroserie");
localStorage.removeItem("alfanumerico");

/* ====================EDITAR OS ====================*/
$('#DatatableOS tbody').on('click', '.btnEditarOS', function (event) {
    let elemento = this;
    let id = elemento.getAttribute('data-id');
    //console.log(id);
    obtenerDatosOS(id)
});
/* ================================================*/
function obtenerDatosOS(id) {
    (async () => {
        await axios.get('ajax/adminoservicio.ajax.php?op=getDataOS', {
            params: {
                idos: id
            }
        })

            .then(res => {
                if (res.status == 200) {

                    html = `OS ya esta Facturado, no
            es posible modificarlo.
          Consulte a Soporte Técnico.`;
                    if (res.data.factura != null) {
                        swal({
                            title: 'Please atention.',
                            text: html,
                            icon: "error",
                            button: "Entendido",
                            timer: 5000
                        })  //fin .then
                        return;
                    }
                    //Llenar formulario con los datos para modificarlo
                    //console.log(res.data);
                    fillform(res.data);

                } else {
                    console.log(res);
                }

            })
            .catch((err) => {
                console.log(err)
            });

    })();  //fin del async

}

function fillform(datosOS) {
    let json_datos_inst = JSON.parse(datosOS.datos_instalacion);
    let json_datos_mat = JSON.parse(datosOS.datos_material);
    //console.log(json_datos_mat)
    $("#editid").val(datosOS.id);
    $("input[name=editAlmacenOS]").val(datosOS.id_almacen);
    $("select[name='edittecnico']").val(datosOS.id_tecnico);
    $("select[name='editAlmacenOS']").val(datosOS.id_almacen);
    $("input[name=editnumtelefono]").val(datosOS.telefono);
    $("input[name=editfechainst]").val(datosOS.fecha_instalacion);
    $("input[name=editnumeroos]").val(datosOS.ordenservicio);
    $("input[name=editnombrecontrato]").val(datosOS.nombrecontrato);
    $("input[name=editobservaos]").val(datosOS.observaciones);

    // Sets data
    if (datosOS.firma === 'Sin Firma' || datosOS.firma === null) {
        $('#signatureContainer').signature();
        $('#signatureContainer').signature('disable');
    } else {
        if (datosOS.firma.length > 15) {
            $('#signatureContainer').signature('draw', datosOS.firma);
            $('#signatureContainer').signature('disable');
        } else {
            $('#signatureContainer').signature();
            $('#signatureContainer').signature('disable');
        }
    }

    //$("#editAlmacenOS, #edittecnico").prop('disabled', true);
    datosinstalacion(json_datos_inst);
    datosMaterialOS(json_datos_mat);

    modalEvento = new bootstrap.Modal(document.getElementById('modalEditarOS'), { keyboard: false });
    modalEvento.show();

}

function datosinstalacion(json_datos_inst) {
    $("#editnumpisaplex").val(json_datos_inst[0].numpisaplex);
    $("#editnumtipo").val(json_datos_inst[0].numtipo);
    $("#editdireccionos").val(json_datos_inst[0].direccionos);
    $("#editcoloniaos").val(json_datos_inst[0].coloniaos);
    $("#editdistritoos").val(json_datos_inst[0].distritoos);
    $("#editterminalos").val(json_datos_inst[0].terminalos);
    $("#editpuertoos").val(json_datos_inst[0].puertoos);
    $("#editnombrefirma").val(json_datos_inst[0].nombrefirma);
    let editnumeroSerie = json_datos_inst[0].numeroserie;
    let editalfanumerico = json_datos_inst[0].alfanumerico;
    localStorage.setItem("numeroserie", editnumeroSerie);
    localStorage.setItem("alfanumerico", editalfanumerico);
}

function datosMaterialOS(json_datos_mat) {
    tbodyOS.innerHTML = ` `;
    for (var i in json_datos_mat) {
        (async () => {
            id_producto = json_datos_mat[i].id_producto;
            cant = json_datos_mat[i].cantidad;
            //console.log(id_producto, cant)
            resp = await getDescripcion(id_producto, cant, i)

        })();  //fin del async	 	
    }
};

async function getDescripcion(id_producto, cant, i) {
    try {
        const response = await axios.get('ajax/adminoservicio.ajax.php?op=getDataOS', {
            params: {
                dataids: id_producto
            }
        })
        //console.log(response.data[0].descripcion)
        descripcion = response.data[0].descripcion
        codigointerno = response.data[0].codigointerno
        conserial = parseInt(response.data[0].conseries)
        let amount = parseFloat(cant);
        itemsbefore(id_producto, amount);   //array de productos de la salida a editar
        if (conserial > 0) {
            let serial = localStorage.getItem("numeroserie");
            let alfa = localStorage.getItem("alfanumerico");
            ser_alfa = `<span><p class="mb-0">Serie: <b>${serial}</b> y Alfanumerico: <b>${alfa}</b></p></span>`;
        }
        i++;
        tbodyOS.innerHTML += `
        <tr class="filas" id="fila${i}">
            <td> <button type="button" class="botonQuitar" onclick="eliminarProductoOS(${i}, ${cant})" title="Quitar concepto">X</button> </td>
            <td class='text-center'>${id_producto} <input type="hidden" name="editaidproducto[]" value="${id_producto}"</td>
            <td class='text-left'>${descripcion} - ${codigointerno} ${conserial > 0 ? ser_alfa : ''}</td>
        <td class='text-center'>${cant} <input type="hidden" name="editacantidad[]" value="${cant}"</td>`;
        tbodyOS.innerHTML += `</tr > `;
    } catch (error) {
        console.error(error);
    }
}

/**================================================================= */
// CREA INPUTs CON LOS DATOS IDs y CANT ORIGINALES P/DESPUES COMPARAR
/*===================================================================*/
function itemsbefore(itemproduct, itemcant) {
    tbodyOS.innerHTML += `<div class="d-none">
        <input type="hidden" name="oldproducto[]" value="${itemproduct}">
            <input type="hidden" name="oldcantidad[]" value="${itemcant}">
            </div>`;
    arrayitems.push([itemproduct, itemcant]);
}
/*==============================================================*/
/*AGREGA PRODUCTO SELECCIONADO
/*============================================================*/
$("#addProductOS").click(function (event) {
    event.preventDefault();
    let encontrado = false;
    let cadena = $("#selectProdOS").val();
    if (cadena == null)
        return;

    let idproducto = cadena.substr(0, cadena.indexOf('-'));  //extrae el Id del prod
    let largo = idproducto.length;
    let id_producto = parseInt(idproducto);
    //SEPARA EL ID PARA SABER SI ES MODEM
    let conserie = cadena.substr(largo + 1, cadena.indexOf('-') - 1);
    //OBTENER LA CANT DE SALIDA
    let sald = $('#cantsaliente').val();   //obtener la cant de salida
    let cantidad = parseFloat(sald);
    //Si no selecciona producto retorna o cantidad
    if (isNaN(idproducto) || isNaN(cantidad) || cantidad < 0.01) {
        return true;
    }

    // CHECA QUE NO LA CANT NO SEA MAYOR QUE LA EXIST.
    if (!checkSalida()) {
        return
    }

    if (parseInt(conserie) > 0) {
        numserie = document.getElementsByName('editnumeroSerie')[0].value;
        numserie = numserie.trim();
        alfanumerico = document.getElementsByName('editalfanumerico')[0].value;
        alfanumerico = alfanumerico.trim();
    } else {
        //console.log('no debe entrar', conserie)
    }

    let descripcion = $("#selectProdOS").text();   //extrae la descripcion del prod
    str = descripcion.trim();
    descripcion = str.replace('null', '');

    //TRUE SI EL PRODUCTO YA ESTA CAPTURADO
    for (item of arrayitems) { // recorro cada array dentro del array padre
        if (item[0] === idproducto) { // en cada array ve si en indice 0 o sea el id es igual id
            encontrado = true
            break;
        }
    }
    console.log("entra aqui", cadena, idproducto, conserie, cantidad, descripcion, arrayitems, encontrado)
    if (!encontrado) {
        arrayitems.push([idproducto, cantidad]);
        addProdOS(id_producto, descripcion, cantidad, conserie, numserie, alfanumerico);
    } else {
        inicializa(1);
    }

});
/*======================================================================*/
/*  ADICIONA PRODUCTOS AL TBODY
==================================================================*/
function addProdOS(...argsProductos) {
    tbodyOS.innerHTML += `
  <tr class="filas" id="fila${argsProductos[0]}">
    <td> <button type="button" class="botonQuitar" onclick="eliminarProductoOS(${argsProductos[0]}, ${argsProductos[2]})" title="Quitar concepto">X</button> </td>
    <td class='text-center'>${argsProductos[0]} <input type="hidden" name="editaidproducto[]" value="${argsProductos[0]}"</td>
    <td class='text-left'>${argsProductos[1]}</td>
    <td class='text-center'>${argsProductos[2]} <input type="hidden" name="editacantidad[]" value="${argsProductos[2]}"</td>`;
    if (argsProductos[3] > 0) {
        tbodyOS.innerHTML += `
      <input type="hidden" name="nvonumserie" value="${argsProductos[4]}">
      <input type="hidden" name="nvoalfanumerico" value="${argsProductos[5]}"/>`;
    }
    tbodyOS.innerHTML += `</tr>`;

    // renglonesOS++;
    // cantSalidaOS += argsProductos[2];
    // evaluaFilaOS(renglonesOS, cantSalidaOS);
    inicialize(argsProductos[3]);
}

/*======================================================================*/
//ENVIAR FORMULARIO PARA GUARDAR EDICION DE DATOS DE ORDEN DE SERVICIO
/*======================================================================*/
$("body").on("submit", "#formularioEditarOS", function (event) {
    event.preventDefault();
    event.stopPropagation();
    let mensajeaxios = 'Registro Actualizado';
    let tipomsg = 1;
    let tiempo = 2;

    if ($('#signatureContainer').signature('isEmpty')) {
        var firma = 'Sin Firma';
    } else {
        var firma = $('#signatureContainer').signature('toJSON');
    }
    if (checkSalida()) {        //checa que la salida no se mayor que la exisrencia

        swal({
            title: "¿Está seguro de Actualizar OS?",
            text: "¡Si no lo está puede cancelar la acción!",
            icon: "warning",
            buttons: ["Cancelar", "Sí, Actualizar"],
            dangerMode: true,
        })

            .then((aceptado) => {
                if (aceptado) {
                    let formData = new FormData($("#formularioEditarOS")[0]);
                    // console.log(firma);
                    for (var pair of formData.entries()) { console.log(pair[0] + ', ' + pair[1]); }
                    formData.append("firma", firma);
                    axios({
                        method: 'post',
                        url: 'ajax/adminoservicio.ajax.php?op=ActualizarOS',
                        data: formData,
                    })

                        .then((response) => {
                            console.log(response.data);
                            if (response.data.status == 200) {
                                mensajeaxios = response.data.msg
                                tipomsg = 3;
                                tiempo = 3;

                                $('#DatatableOS').DataTable().ajax.reload(null, false);
                                modalEvento.hide();
                                mensajenotie(tipomsg, `${mensajeaxios} `, 'top', tiempo);
                            } else {
                                modalEvento.hide();
                                mensajenotie('Error', 'Hubo problemas al guardar OS!', 'bottom', 3);
                            }
                            //console.log(response);
                        })
                        .catch((err) => { throw err });   //          .catch(function (error) {console.log(error.toJSON())})

                } else {
                    return false;
                }
            });
    }

});

/*=============================================
SELECCIONAR PRODUCTO POR AJAX
=============================================*/
var $eventoSelect = $('#selectProdOS').select2({
    placeholder: 'Seleccione producto....',
    theme: "bootstrap4",
    ajax: {
        url: 'ajax/adminoservicio.ajax.php?op=buscarProdx',
        async: true,
        dataType: 'json',
        delay: 250,   //Valor par indicarle al Select2 q espere hasta q el usuario haya terminado de escribir su 'término de búsqueda' antes de activar la solicitud AJAX. Simplemente use la opción de configuración ajax.delay para decirle a Select2 cuánto tiempo debe esperar después de que un usuario haya dejado de escribir antes de enviar la solicitud:
        type: "POST",
        data: function (params) {
            if ($.trim(params.term) === '') {
                return data;
            };
            //console.log("data1:",data);
            return {
                id_almacen: $("#editAlmacenOS").val(),
                id_tecnico: $("#edittecnico").val(),
                searchTerm: params.term
            };
        },
        processResults: function (data, params) {
            return {
                results: $.map(data, function (item, params) {
                    //console.log("data:",data[3]['existe'], "item:",item, "params:", params );
                    if (item.existe > 0) {
                        return {
                            "id": item.id_producto + '-' + item.conseries + '-' + item.existe,   //id de prod, si tiene serie, existencia
                            "text": item.descripcion + ' - ' + item.codigointerno
                        }
                    }
                }),
            };
        },
        cache: true
    },
    language: {
        inputTooShort: function () {
            return 'Ingrese min. 2 caracteres';
        },
        noResults: function () {
            return "No hay resultado.";
        },
        searching: function () {
            return "Buscando..";
        }
    },
    minimumResultsForSearch: 20,
    minimumInputLength: 2,
    allowClear: true
});
/************************************************************************************* */
//SI ABRE EL SELECT, INICIALIZA VALORES DE SALIDA|
$eventoSelect.on("select2:open", function (e) {
    inicialize(0)
});
/************************************************************************************ */
/*==================================================================*/
function inicialize(conserie) {
    //DESPUES DE AÑADIR, SE INICIALIZAN SELECT E INPUT
    $('#selectProdOS').text(null);
    $('#selectProdOS').val(null).trigger('change');
    $("#existecnico").val(0);
    $("#cantsaliente").val(0);
    //console.log("entra...")
    if (conserie > 0) {
        $("#editdatosmodem").addClass("d-none");
    } else {
        inputsmodem = document.getElementById("editdatosmodem");
        if (!inputsmodem.classList.contains("d-none")) {
            $("#editdatosmodem").addClass("d-none");
        }
    }
}
/************************************************************************************ */
/*  */
/************************************************************************************ */
$("#selectProdOS").change(function (event) {
    //console.log($("#selectProdOS").val())

    if ($("#selectProdOS").val() != null) {
        cadena = $("#selectProdOS").val();
        //console.log($("#selectProdOS").text(), cadena)
        let descripcion = $("#selectProdOS").text();
        descripcion = descripcion.trim();


        //SEPARA EL ID DEL PRODUCTO SELECCIONADO
        let idproducto = cadena.substr(0, cadena.indexOf('-'));
        idproducto = idproducto.trim();
        let largo = idproducto.length;
        //console.log('Largo idproducto',largo, idproducto);

        //SEPARA EL IDENT PARA SABER SI ES MODEM
        let conserie = cadena.substr(largo + 1, cadena.indexOf('-') - 1);
        conserie = conserie.replace(/-/g, '');   //si encuentra un guion (-) lo sustituye con vacio

        //SEPARA LA EXISTENCIA DEL PROD. SELECCIONADO
        let stock = parseFloat(cadena.substr(cadena.lastIndexOf("-") + 1));
        //console.log('ID:',idproducto,' EXISTENCIA:',stock, 'CONSERIE:',conserie, 'Prod:',descripcion );

        $('#existecnico').val(stock);

        if (conserie > 0) {
            $("#editdatosmodem").removeClass("d-none");
        }
    }
})  //fin del select2
/*******************************************************
VERIFICA QUE LA CANT SALIENTE NO SEA MAYOR QUE LA EXIST.
********************************************************/
function checkSalida() {
    check = true;
    let sald = $('#cantsaliente').val();
    let cant = parseFloat($('#existecnico').val());
    console.log(sald, cant)
    if (sald > cant || sald < 0) {
        swal({
            text: 'Cantidad saliente es mayor que la Existencia!',
            icon: "warning",
            button: "Entendido",
            timer: 3000
        })  //fin .then
        $("#cantsaliente").val(0);
        $("#cantsaliente").focus();
        check = false;
    }
    return check;
}
/*======================================================================*/

/*================ AL SALIR DEL MODAL EDITAR OS, RESETEAR FORMULARIO ==================*/
$("#modalEditarOS").on('hidden.bs.modal', () => {
    $("#formularioEditarOS")[0].reset();
    $("#editAlmacenOS, #edittecnico").prop('disabled', false);
    $('#signatureContainer').signature('enable');
    $('#signatureContainer').signature('clear');
    $('.habilitar').text('Habilitar');
    arrayitems["length"] = 0;                  //inicializa array
    localStorage.removeItem("numeroserie");
    localStorage.removeItem("alfanumerico");
});
/*****************************************************************************************/