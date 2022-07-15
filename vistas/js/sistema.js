$(document).ready(function(){
})

/* *************************************************************
 ******** funcion para guardar los accesos ************
 ****************************************************************/
$("#guardarPermisoConfig").on("click",function(event){
    event.preventDefault();	
    
    let usuarioPermiso=$("#nvoUsuarioPermiso").val();
    var miArray = new Array()

    console.log("entra:",usuarioPermiso)
    $('#checkeadosconfig input[type=checkbox]').each(function(){
        if (this.checked) {
            miArray.push([$(this).attr('id'),$(this).val()]);
        }
            //miArray=[...nuevoarray];
    });     

let data = new FormData();
 data.append('miArray', JSON.stringify(miArray));
 data.append('idUsuario', usuarioPermiso);

    fetch('ajax/permiso.ajax.php?op=guardarPermisoConfig', {
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

/***************************************************
*********  funcion para obtener los accesos *****
****************************************************/
 function obteneraccesos4(user){
    console.log(user);
    $('input').iCheck('uncheck');   //deschequea todos
    axios.get('ajax/permiso.ajax.php?op=getPermisos', {
        params: {
          user: user,
          modulo: 'configura'
        }
    })
      .then((response) => {
    
        if(response.status==200) {
            //console.table(response.data)
            datas=response.data.configura;
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


/*****************************************************
            HABILITAR CHECKBOX
*****************************************************/
$('.habilitaPermisoUsuarios').on('ifChecked', function (event) {
    console.log("Checkeado OK")
    $('input.rolusuarios').removeAttr("disabled");
    $('input.rolusuarios').iCheck('check');
});

$('.habilitaPermisoPermisos').on('ifChecked', function (event) {
});

$('.habilitaPermisoRepositorio').on('ifChecked', function (event) {
    $('input.rolrepositorio').removeAttr("disabled");
    $('input.rolrepositorio').iCheck('check');
});

$('.habilitaPermisoEmpresa').on('ifChecked', function (event) {
});


/*****************************************************
            DESHABILITAR CHECKBOX
*****************************************************/
$('.habilitaPermisoUsuarios').on('ifUnchecked', function (event) {
    console.log("Deschekeado OK")
    $('input.rolusuarios').attr("disabled", "disabled");
    $('input.rolusuarios').iCheck('uncheck');
});

$('.habilitaPermisoPermisos').on('ifUnchecked', function (event) {
});

$('.habilitaPermisoRepositorio').on('ifUnchecked', function (event) {
    $('input.rolrepositorio').attr("disabled", "disabled");
    $('input.rolrepositorio').iCheck('uncheck');
});

$('.habilitaPermisoEmpresa').on('ifUnchecked', function (event) {
});



