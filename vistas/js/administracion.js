$(document).ready(function(){
})

/* *************************************************************
 ******** funcion para guardar los accesos ************
 ****************************************************************/
$("#guardarPermisoAdmin").on("click",function(event){
    event.preventDefault();	
    
    let usuarioPermiso=$("#nvoUsuarioPermiso").val();
    var miArray = new Array()

    console.log("entra:",usuarioPermiso)
    $('#checkeadosadmin input[type=checkbox]').each(function(){
        if (this.checked) {
            //console.log([$(this).attr('id'),$(this).val()]);
            miArray.push([$(this).attr('id'),$(this).val()]);
        }
            //miArray=[...nuevoarray];
    });     

console.table(miArray)

let data = new FormData();
 data.append('miArray', JSON.stringify(miArray));
 data.append('idUsuario', usuarioPermiso);

    fetch('ajax/permiso.ajax.php?op=guardarPermisoAdmin', {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(data => {
    console.log(data)
    if(data===false){
        //console.log(data);
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

/***************************************************
*********  funcion para obtener los accesos *****
****************************************************/
 function obteneraccesos1(user){
    console.log(user);
    $('input').iCheck('uncheck');   //deschequea todos
    axios.get('ajax/permiso.ajax.php?op=getPermisos', {
        params: {
          user: user,
          modulo: 'administracion'
        }
    })
      .then((response) => {
    
        if(response.status==200) {
            //console.table(response.data)
            datas=response.data.administracion;

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


/*****************************************************
            HABILITAR CHECKBOX
*****************************************************/
$('.habilitaPermisoSalidas').on('ifChecked', function (event) {
    $('input.rolSalidasAlm').removeAttr("disabled");
    $('input.rolSalidasAlm').iCheck('check');
});

$('.habilitaPermisoEntradas').on('ifChecked', function (event) {
    console.log("Checkeado OK")
    $('input.rolEntradasAlm').removeAttr("disabled");
    $('input.rolEntradasAlm').iCheck('check');
});

$('.habilitaPermisoCapSeries').on('ifChecked', function (event) {
    $('input.rolcapseries').removeAttr("disabled");
    $('input.rolcapseries').iCheck('check');
});

$('.habilitaPermisoAjusteInv').on('ifChecked', function (event) {
    $('input.rolajusteinv').removeAttr("disabled");
    $('input.rolajusteinv').iCheck('check');
});

$('.habilitaPermisoDevAlmacen').on('ifChecked', function (event) {
    $('input.roldevalm').removeAttr("disabled");
    $('input.roldevalm').iCheck('check');
});

$('.habilitaPermisoCtrlFact').on('ifChecked', function (event) {
    $('input.rolctrlfact').removeAttr("disabled");
    $('input.rolctrlfact').iCheck('check');
});

$('.habilitaPermisoCtrlViaticos').on('ifChecked', function (event) {
    $('input.rolctrlviatico').removeAttr("disabled");
    $('input.rolctrlviatico').iCheck('check');
});

$('.habilitaPermisoOsVila').on('ifChecked', function (event) {
    $('input.rolosvilla').removeAttr("disabled");
    $('input.rolosvilla').iCheck('check');
});


/*****************************************************
            DESHABILITAR CHECKBOX
*****************************************************/
$('.habilitaPermisoSalidas').on('ifUnchecked', function (event) {
    console.log("Deschekeado OK")
    $('input.rolSalidasAlm').attr("disabled", "disabled");
    $('input.rolSalidasAlm').iCheck('uncheck');
});

$('.habilitaPermisoEntradas').on('ifUnchecked', function (event) {
    console.log("Deschekeado OK")
    $('input.rolEntradasAlm').attr("disabled", "disabled");
    $('input.rolEntradasAlm').iCheck('uncheck');
});

$('.habilitaPermisoCapSeries').on('ifUnchecked', function (event) {
    $('input.rolcapseries').attr("disabled", "disabled");
    $('input.rolcapseries').iCheck('uncheck');
});

$('.habilitaPermisoAjusteInv').on('ifUnchecked', function (event) {
    $('input.rolajusteinv').attr("disabled", "disabled");
    $('input.rolajusteinv').iCheck('uncheck');
});

$('.habilitaPermisoDevAlmacen').on('ifUnchecked', function (event) {
    $('input.roldevalm').attr("disabled", "disabled");
    $('input.roldevalm').iCheck('uncheck');
});

$('.habilitaPermisoCtrlFact').on('ifUnchecked', function (event) {
    $('input.rolctrlfact').attr("disabled", "disabled");
    $('input.rolctrlfact').iCheck('uncheck');
});

$('.habilitaPermisoCtrlViaticos').on('ifUnchecked', function (event) {
    $('input.rolctrlviatico').attr("disabled", "disabled");
    $('input.rolctrlviatico').iCheck('uncheck');
});

$('.habilitaPermisoOsVila').on('ifUnchecked', function (event) {
    $('input.rolosvilla').attr("disabled", "disabled");
    $('input.rolosvilla').iCheck('uncheck');
});


