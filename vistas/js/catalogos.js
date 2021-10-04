$(document).ready(function(){
    //$('input.rolcliente').attr("disabled", "disabled");    
    //$('input.rolproducto').attr("disabled", "disabled");  
})


$("#guardarPermisoCat").on("click",function(event){
    event.preventDefault();	
    
    let usuarioPermiso=$("#nvoUsuarioPermiso").val();
    var miArray = new Array()

    console.log("entra:",usuarioPermiso)
    $('#checkeadoscata input[type=checkbox]').each(function(){
        if (this.checked) {
            //console.log($(this).attr('id')+', ',$(this).val()+' ');
            miArray.push([$(this).attr('id'),$(this).val()]);
        }
            //miArray=[...nuevoarray];
    });     
    //console.table(miArray);

 //HACER UN FETCH AL CAT DE PROD
 let data = new FormData();
 data.append('miArray', JSON.stringify(miArray));
 data.append('idUsuario', usuarioPermiso);

    fetch('ajax/permiso.ajax.php?op=guardarPermisoCata', {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(data => {
    console.log(data)
    if(data===false){
        console.log(data);
    }else{
        swal({
            title: 'Los cambios han sido guardados!',
            text: "Registro Guardado correctamente",
            icon: "success",
            button: "Enterado",
            timer: 3000
          }).then((result) => {
            if (result) {
                window.location.reload();
            }else{
                $("#accordion").hide();
                window.location.reload();
            }
          })
                    
    }
    })
    .catch(error => console.error(error))

 });        //fin del funcion


function obteneraccesos2(user){
console.log(user);
$('input').iCheck('uncheck');
axios.get('ajax/permiso.ajax.php?op=getPermisos', {
    params: {
      user: user,
      modulo: 'catalogo'
    }
})
  .then((response) => {

    if(response.status==200) {
        //console.table(response.data)
        datas=response.data.catalogo;
        //console.log(datas);
        bigdata=JSON.parse(datas);

        activarcheckbox(bigdata)

    }else{
        console.log(response);    
    }
    
  })
  .catch((error) => {
    console.log(error);
  });
}

// ES6  FUNCIONA IGUAL QUE $.each
//Object.keys(nvo).forEach(llave => console.log(llave, nvo[llave]));


/*****************************************************
            HABILITAR CHECKBOX
*****************************************************/
$('.habilitaPermisoProd').on('ifChecked', function (event) {
    console.log("Checkeado OK")
    $('input.rolproducto').removeAttr("disabled");
    $('input.rolproducto').iCheck('check');
});

$('.habilitaPermisoCategoria').on('ifChecked', function (event) {
    console.log("Checkeado OK")
    $('input.rolcategoria').removeAttr("disabled");
    $('input.rolcategoria').iCheck('check');
});

$('.habilitaPermisoMedida').on('ifChecked', function (event) {
    console.log("Checkeado OK")
    $('input.rolmedida').removeAttr("disabled");
    $('input.rolmedida').iCheck('check');
});

$('.habilitaPermisoProveedor').on('ifChecked', function (event) {
    $('input.rolproveedor').removeAttr("disabled");
    $('input.rolproveedor').iCheck('check');
});

$('.habilitaPermisoCliente').on('ifChecked', function (event) {
    console.log("Checkeado OK")
    $('input.rolcliente').removeAttr("disabled");
    $('input.rolcliente').iCheck('check');
});
    
$('.habilitaPermisoTecnicos').on('ifChecked', function (event) {
    $('input.roltecnicos').removeAttr("disabled");
    $('input.roltecnicos').iCheck('check');
});

$('.habilitaPermisoAlmacen').on('ifChecked', function (event) {
    $('input.rolalmacen').removeAttr("disabled");
    $('input.rolalmacen').iCheck('check');
});

$('.habilitaPermisoTipMov').on('ifChecked', function (event) {
    $('input.roltipomov').removeAttr("disabled");
    $('input.roltipomov').iCheck('check');
});

/*****************************************************
            DESHABILITAR CHECKBOX
*****************************************************/
$('.habilitaPermisoProd').on('ifUnchecked', function (event) {
    console.log("Deschekeado OK")
    $('input.rolproducto').attr("disabled", "disabled");
    $('input.rolproducto').iCheck('uncheck');
});

$('.habilitaPermisoCategoria').on('ifUnchecked', function (event) {
    $('input.rolcategoria').attr("disabled", "disabled");
    $('input.rolcategoria').iCheck('uncheck');
});

$('.habilitaPermisoMedida').on('ifUnchecked', function (event) {
    $('input.rolmedida').attr("disabled", "disabled");
    $('input.rolmedida').iCheck('uncheck');
});

$('.habilitaPermisoProveedor').on('ifUnchecked', function (event) {
    $('input.rolproveedor').attr("disabled", "disabled");
    $('input.rolproveedor').iCheck('uncheck');
});

$('.habilitaPermisoCliente').on('ifUnchecked', function (event) {
    $('input.rolcliente').attr("disabled", "disabled");
    $('input.rolcliente').iCheck('uncheck');
});

$('.habilitaPermisoTecnicos').on('ifUnchecked', function (event) {
    $('input.roltecnicos').attr("disabled", "disabled");
    $('input.roltecnicos').iCheck('uncheck');
});

$('.habilitaPermisoAlmacen').on('ifUnchecked', function (event) {
    $('input.rolalmacen').attr("disabled", "disabled");
    $('input.rolalmacen').iCheck('uncheck');
});

$('.habilitaPermisoTipMov').on('ifUnchecked', function (event) {
    $('input.roltipomov').attr("disabled", "disabled");
    $('input.roltipomov').iCheck('uncheck');
});




/*
    GUARDAR:
    UPDATE `usuarios` SET `permisos`='{"administracion":{"ventas": 63, "compras": 19, "Adminventas":19, "controlefectivo":19, "ajusteinv": 19}, "catalogos":{"productos": 15, "clientes": 159} }' WHERE `id`=1
    UPDATE `usuarios` SET `permisos`='{"ventas": 63, "compras": 19, "Adminventas":19, "controlefectivo":19, "ajusteinv": 19,"productos": 15, "clientes": 159}' WHERE `id`=1
    UPDATE `usuarios` SET `permisos`='{"productos": 14, "categorias": 15, "clientes":127}' WHERE `id`=2

    CONSULTAR
    SELECT permisos->'$.administracion.ventas' AS acceso FROM usuarios WHERE `id`=1
    SELECT JSON_EXTRACT(permisos, '$.administracion.ventas') AS acceso FROM usuarios WHERE `id`=1
    SELECT JSON_EXTRACT(permisos, '$.productos') AS acceso FROM usuarios WHERE `id`=2
    
SELECT `permisos` FROM `usuarios` WHERE permisos->'$.catalogos.clientes'>0
SELECT `permisos` FROM `usuarios` WHERE permisos->'$.administracion.ventas'>0
'{"catalogos" : {"clientes": "159", "productos": "15"}, "Administracion" : {"ventas": "98", "compras": "15"}}');

 ;    
*/