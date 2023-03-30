$("#modalEditarOS").draggable({
    handle: ".modal-header"
});
var tbodyOS = document.querySelector('#editaTbodyOS');
var arrayitems = new Array();


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

    $("#editAlmacenOS, #edittecnico").prop('disabled', true);
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
    $("#editnumeroSerie").val(json_datos_inst[0].numeroserie);
    $("#editalfanumerico").val(json_datos_inst[0].alfanumerico);
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
        let amount = parseFloat(cant);
        itemsbefore(id_producto, amount);   //array de productos de la salida a editar

        i++;
        tbodyOS.innerHTML += `
        <tr class="filas" id="fila${i}">
            <td> <button type="button" class="botonQuitar" onclick="eliminarProductoOS(${i}, ${cant})" title="Quitar concepto">X</button> </td>
            <td class='text-center'>${id_producto} <input type="hidden" name="editaidproducto[]" value="${id_producto}"</td>
            <td class='text-left'>${descripcion} - ${codigointerno}</td>
            <td class='text-center'>${cant} <input type="hidden" name="editacantidad[]" value="${cant}"</td>`;
        tbodyOS.innerHTML += `</tr>`;
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
                return
                axios({
                    method: 'post',
                    url: 'ajax/adminoservicio.ajax.php?op=ActualizarOS',
                    data: formData,
                })

                    .then((response) => {
                        //console.log(response.data); 
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

});
/*================ AL SALIR DEL MODAL EDITAR OS, RESETEAR FORMULARIO ==================*/
$("#modalEditarOS").on('hidden.bs.modal', () => {
    $("#formularioEditarOS")[0].reset();
    $("#editAlmacenOS, #edittecnico").prop('disabled', false);
    $('#signatureContainer').signature('enable');
    $('#signatureContainer').signature('clear');
    $('.habilitar').text('Habilitar');
    arrayitems["length"] = 0;                  //inicializa array
});
/*****************************************************************************************/
/*=============================================
SELECCIONAR PRODUCTO POR AJAX
=============================================*/
var $eventoSelect = $('#selectProdOS').select2({
    placeholder: 'Seleccione producto...',
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
                            text: item.descripcion + ' - ' + item.codigointerno,
                            id: item.id_producto + '-' + item.conseries + '-' + item.existe   //id de prod, si tiene serie, existencia
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

    // if (conserie > 0) {
    //     $("#editdatosmodem").addClass("d-none");
    // } else {
    //     inputsmodem = document.getElementById("editdatosmodem");
    //     if (!inputsmodem.classList.contains("d-none")) {
    //         $("#editdatosmodem").addClass("d-none");
    //     }
    // }
}
/************************************************************************************ */
$("#selectProdOS").change(function (event) {
    //console.log($("#selecProductoOS").val())
    //console.log($("#selecProductoOS").text())
    if ($("#selectProdOS").val() != null) {
        cadena = $("#selectProdOS").val();
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
            $("#datosmodem").removeClass("d-none");
        }
    }
})  //fin del select2
/*******************************************************
VERIFICA QUE LA CANT SALIENTE NO SEA MAYOR QUE LA EXIST. 
********************************************************/
$("#cantsaliente").change(function (evt) {
    let sald = $('#cantsaliente').val();
    let cant = parseFloat($('#existecnico').val());
    if (sald > cant || sald < 0) {
        swal({
            text: 'Cantidad saliente es mayor que la Existencia!',
            icon: "warning",
            button: "Entendido",
            timer: 3000
        })  //fin .then
        $("#cantsaliente").val(0);
        $("#cantsaliente").focus();
    }
})  //fin de checar cant de salida
  /*======================================================================*/