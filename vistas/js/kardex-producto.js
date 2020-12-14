
/*=========================================================
MUESTRA SALIDAS DE ALMACEN EN EL DATATABLE SEGUN CONDICIONES
==========================================================*/
function listarkardex(){
   let almacensel = $("#almacenKardex").val();
   let nomalmacen=$( "#almacenKardex option:selected" ).text();
   let productosel = $("#selProductoKardex").val();
   //let nomproducto=$("#selProductoKardex option:selected" ).text();
   let fechaselIni = $("#fechaIniKardex").val();
   let fechaselFin = $("#fechaFinKardex").val();
   almacensel=parseInt(almacensel);
    //console.log(almacensel, nomalmacen, productosel, nomproducto, fechaselIni, fechaselFin);

    let year=moment().format('YYYY');
    let fechaanterior=year+'-01-01';
    let isvalidDate=moment(fechaselIni).isBefore(fechaanterior);
    
    if(isvalidDate){
        Swal.fire(
        'Atención',
        'Fecha inicial no puede ser anterior al 01 de Enero del '+year,
        'warning'
        )
      return false;
    }
  
    // Consulta si la fecha inicial es anterior a la fecha final
    let i=moment(fechaselFin).isBefore(fechaselIni);
    if(i){
        Swal.fire(
        'Atención',
        'Fecha inicial no puede ser mayor a fecha Final.',
        'warning'
      )
      return false;
    }

    if(almacensel>0 && productosel.length > 0 && fechaselIni.length>0 && fechaselFin.length>0){
        window.open("extensiones/tcpdf/pdf/kardex-producto.php?almacensel="+almacensel+"&nomalmacen="+nomalmacen+"&productosel="+productosel+"&fechaselIni="+fechaselIni+"&fechaselFin="+fechaselFin, "_blank");
    }else{
        console.log("Sin info");
        Swal.fire(
            'Revise sus datos!',
            'Almacen, Artículo y Fechas que sean correctas.',
            'error'
          );
        return
    }

}
//=========================================================
let placeholderkardex = "&#xf02a Busque y seleccione producto";
$('.selectKardex').select2({
    placeholder: placeholderkardex,
    escapeMarkup: function(m) { 
       return m; 
    }
});
//==========================================================

$(document).ready(function (){ 
});
