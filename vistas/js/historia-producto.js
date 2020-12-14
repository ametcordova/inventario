
/*=========================================================
MUESTRA SALIDAS DE ALMACEN EN EL DATATABLE SEGUN CONDICIONES
==========================================================*/
function listarkardex(){
   let almacensel = $("#almacenKardex").val();
   let nomalmacen=$( "#almacenKardex option:selected" ).text();
   let productosel = $("#selProductoKardex").val();
   let nomproducto=$("#selProductoKardex option:selected" ).text();
   let fechasel = $("#fechaIniKardex").val();
   almacensel=parseInt(almacensel);
    console.log(almacensel, nomalmacen, productosel, nomproducto, fechasel);
    
    if(almacensel>0 && productosel.length > 0 && fechasel.length>0){
        window.open("extensiones/tcpdf/pdf/kardex-producto.php?almacensel="+almacensel+"&nomalmacen="+nomalmacen+"&productosel="+productosel+"&fechasel="+fechasel, "_blank");
    }else{
        console.log("Sin info");
        return
    }

}
//=========================================================
let placeholderHistSalida = "&#xf02a Busque y seleccione producto";
$('.selectKardex').select2({
    placeholder: placeholderHistSalida,
    escapeMarkup: function(m) { 
       return m; 
    }
});
//==========================================================

$(document).ready(function (){ 
});
