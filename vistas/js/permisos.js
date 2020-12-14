$(document).ready(function(){
    $('input.rolcliente').attr("disabled", "disabled");    
    $('input.rolproducto').attr("disabled", "disabled");  
})


$("#guardarPermisoCat").on("click",function(event){
    event.preventDefault();	
    
    let usuarioPermiso=$("#nvoUsuarioPermiso").val();
    var miArray = new Array()

    console.log("entra:",usuarioPermiso)
    $('#checkeadoscata input[type=checkbox]').each(function(){
        if (this.checked) {
            //console.log($(this).attr('name')+', ',$(this).val()+' ');
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

    fetch('ajax/permiso.ajax.php?op=guardarPermisoProd', {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(data => {
    console.log(data)
    if(data===false){
        console.log(data);
    }else{
        Swal.fire({
            title: 'Los cambios han sido guardados!',
            confirmButtonText: `Enterado`,
          }).then((result) => {
            if (result.isConfirmed) {
                window.location.reload();
            }else{
                $("#accordion").hide();
                //window.location.reload();
            }
          })
                    
    }
    })
    .catch(error => console.error(error))

 });        //fin del funcion


//SI SELECCIONA UN USUARIO SE ABRE ACORDION
$("#nvoUsuarioPermiso").change(function(){
    let usuarioselec=$("#nvoUsuarioPermiso").val();
    if(usuarioselec=='0'){
        $("#accordion").hide();
    }else{
        $("#accordion").show("slow");
        obteneraccesos(usuarioselec);
    }
});

function obteneraccesos(user){
console.log(user);
axios.get('ajax/permiso.ajax.php?op=getPermisosCat', {
    params: {
      user: user
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
function activarcheckbox(bigdata){
    $.each(bigdata, function(key, item) {
        //if(key=="productos"){
            let inicial=key.substring(0, 4);
            console.log(key, item, inicial);
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

$('.habilitaPermisoFamilia').on('ifChecked', function (event) {
    console.log("Checkeado OK")
    $('input.rolfamilia').removeAttr("disabled");
    $('input.rolfamilia').iCheck('check');
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
    
$('.habilitaPermisoAlmacen').on('ifChecked', function (event) {
    $('input.rolalmacen').removeAttr("disabled");
    $('input.rolalmacen').iCheck('check');
});

$('.habilitaPermisoCajaventa').on('ifChecked', function (event) {
    $('input.rolcajaventa').removeAttr("disabled");
    $('input.rolcajaventa').iCheck('check');
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

$('.habilitaPermisoFamilia').on('ifUnchecked', function (event) {
    $('input.rolfamilia').attr("disabled", "disabled");
    $('input.rolfamilia').iCheck('uncheck');
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

$('.habilitaPermisoAlmacen').on('ifUnchecked', function (event) {
    $('input.rolalmacen').attr("disabled", "disabled");
    $('input.rolalmacen').iCheck('uncheck');
});

$('.habilitaPermisoCajaventa').on('ifUnchecked', function (event) {
    $('input.rolcajaventa').attr("disabled", "disabled");
    $('input.rolcajaventa').iCheck('uncheck');
});

$('.habilitaPermisoTipMov').on('ifUnchecked', function (event) {
    $('input.roltipomov').attr("disabled", "disabled");
    $('input.roltipomov').iCheck('uncheck');
});

