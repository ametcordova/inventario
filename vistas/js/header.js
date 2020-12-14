axios.defaults.xsrfCookieName = 'csrftoken';
axios.defaults.xsrfHeaderName = 'X-CSRFToken';

var iddecaja = "id_caja";
var numdecaja=$("#numcaja").val();
var campofecha="fecha_salida";
var cerrado=0;
var datecurrent=moment().format('YYYY-MM-DD');

/* ================================================
MODULO PARA MOSTRAR IMPORTES POR CONCEPTOS, EN LA 
VENTANA DE CAJA APERTURADA.
==================================================*/
$("#modalcajaventa").click(function(ev) {

    ev.preventDefault();
    var target = $(this).attr("href");
    console.log("entra:",target)
    //console.log(moment().format('YYYY-MM-DD')); 
    
    //window.location="modal-caja#cajaAbierta";

    //$('#cajaAbierta').on('show.bs.modal', function (e) {})

    //generar(iddecaja,numdecaja,campofecha,cerrado,datecurrent);



/* ======= ENCADENAR SECUENCIALMENTE LLAMADAS A FUNCIONES ========*/
//function generar(iddecaja,numdecaja,campofecha,cerrado,datecurrent){
//    try{    
        //traerventas(iddecaja,numdecaja,campofecha,cerrado,datecurrent);
        //traerenvases(iddecaja,numdecaja,campofecha,cerrado,datecurrent);
        //traerservicios(iddecaja,numdecaja,campofecha,cerrado,datecurrent);
        //traerotros(iddecaja,numdecaja,campofecha,cerrado,datecurrent);
        //traerventacredito(numdecaja,campofecha,cerrado,datecurrent);
        //setTimeout(traeringresosegresos(iddecaja,numdecaja,cerrado),1000);
        //traerimportecajachica(iddecaja,numdecaja,cerrado,datecurrent);
        //setTimeout(sumartotalcaja,1200); 
//sumartotalcaja();
    //} catch(err){
     //   console.log(err);
    //}    
//};


/*==== CONSULTA DE VENTAS ======== */
traerventas(iddecaja,numdecaja,campofecha,cerrado,datecurrent);
async function traerventas(iddecaja,numdecaja,campofecha,cerrado,datecurrent){
    let data1 = new FormData();
    data1.append("fechasalida", campofecha);
    data1.append("fechaactual", datecurrent);
    data1.append("cerrado", cerrado);
    data1.append("numcaja", numdecaja);
    try{
        await fetch('ajax/vercaja.ajax.php?op=traertotalventas', {
            method: 'POST',
            body: data1
        })
         .then(respuesta=>respuesta.json())
         .then(datos=>{
            //console.log(datos);
            totalsinpromo=datos.sinpromo==null?0:parseFloat(datos.sinpromo);
            totalconpromo=datos.promo==null?0:parseFloat(datos.promo);
            totaldeventas=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format((totalsinpromo+totalconpromo));
            $('#totaldeventas').html(totaldeventas);
            $('#totaldeventas').attr('data-ventas',(totalsinpromo+totalconpromo));
        }) 
   }catch(showErrorFetch){    

   }    
}

/*==== CONSULTA VENTAS DE ENVASES ======== */ 
traerenvases(iddecaja,numdecaja,campofecha,cerrado,datecurrent);  
async function traerenvases(iddecaja,numdecaja,campofecha,cerrado,datecurrent){
let data2 = new FormData();
data2.append("fechasalida", campofecha);
data2.append("fechaactual", datecurrent);
data2.append("cerrado", cerrado);
data2.append("numcaja", numdecaja);

try{
    await fetch('ajax/vercaja.ajax.php?op=traertotalenvases', {
      method: 'POST',
      body: data2
    })
    .then(respuesta=>respuesta.json())
    .then(datos=>{
        //console.log(datos);
        totalenvases=datos.total==null?0:datos.total;
        totalenvases=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format((totalenvases));
        $('#totalenvases').html(totalenvases);
        $('#totalenvases').attr('data-envases',(datos.total));
        //console.log("entro2");
    }) 
    }catch(showErrorFetch){    
    }    
}

/*==== CONSULTA VENTAS DE SERVICIOS ======== */   
traerservicios(iddecaja,numdecaja,campofecha,cerrado,datecurrent);
async function traerservicios(iddecaja,numdecaja,campofecha,cerrado,datecurrent){
let data3 = new FormData();
data3.append("fechasalida", campofecha);
data3.append("fechaactual", datecurrent);
data3.append("cerrado", cerrado);
data3.append("numcaja", numdecaja);
    try{
        await fetch('ajax/vercaja.ajax.php?op=traertotalservicios', {
            method: 'POST',
            body: data3
        })
         .then(respuesta=>respuesta.json())
         .then(datos=>{
            console.log(datos);
            totalservicios=datos.total==null?0:datos.total;
            totalservicios=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format((totalservicios));
            $('#totalservicios').html(totalservicios);
            $('#totalservicios').attr('data-servicios',(datos.total));
            //console.log("entro3");
          }) 
    }catch(showErrorFetch){    
    }    
}        

/*==== CONSULTA VENTAS DE ABARROTES ======== */   
traerotros(iddecaja,numdecaja,campofecha,cerrado,datecurrent);
async function traerotros(iddecaja,numdecaja,campofecha,cerrado,datecurrent){
    let data4 = new FormData();
    data4.append("fechasalida", campofecha);
    data4.append("fechaactual", datecurrent);
    data4.append("cerrado", cerrado);
    data4.append("numcaja", numdecaja);
        try{
            await fetch('ajax/vercaja.ajax.php?op=traertotalotros', {
                method: 'POST',
                body: data4
            })
             .then(respuesta=>respuesta.json())
             .then(datos=>{
                console.log(datos);
                totalabasinpromo=datos.sinpromo==null?0:parseFloat(datos.sinpromo);
                totalabaconpromo=datos.promo==null?0:parseFloat(datos.promo);
                totalotros=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format((totalabasinpromo+totalabaconpromo));
                $('#totalotros').html(totalotros);
                $('#totalotros').attr('data-otros',(totalabasinpromo+totalabaconpromo));
                //console.log("entro3");
              }) 
        }catch(showErrorFetch){    
        }    
}        

/* ====================================================== */
// function traerventacredito(iddecaja,numdecaja,campofecha,cerrado,datecurrent){
//     axios.post('ajax/vercaja.ajax.php?op=traertotalacredito', {
//         params: {
//           fechasalida: campofecha,
//           fechaactual: datecurrent,
//           cerrado: cerrado,
//           numcaja: numdecaja
//         }
//     })

//     .then((response) => {
//         if(response.status==200) {
//             console.log(response)
//             console.log(response.data.promo)
//             console.log(response.data.sinpromo)
//             totcredsinpromo=response.data.promo==null?0:parseFloat(response.data.sinpromo);
//             totcredconpromo=response.data.sinpromo==null?0:parseFloat(response.data.promo);
//             totalacredito=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format((totcredsinpromo+totcredconpromo));
//             $('#totalacredito').html(totalacredito);
//             $('#totalacredito').attr('data-creditos',(totcredsinpromo+totcredconpromo));

//         }else{
//             console.log(response);    
//         }
        
//       })
//       .catch((error) => {
//         console.log(error);
//       });

// }


/*==== CONSULTA VENTAS A CREDITO ======== */  
traerventacredito(numdecaja,campofecha,cerrado,datecurrent);
async function traerventacredito(numdecaja,campofecha,cerrado,datecurrent){
let data5 = new FormData();
data5.append("fechasalida", campofecha);
data5.append("fechaactual", datecurrent);
data5.append("cerrado", cerrado);
data5.append("numcaja", numdecaja);
for (var pair of data5.entries()){console.log(pair[0]+ ', ' + pair[1]);}
    try{
       await fetch('ajax/vercaja.ajax.php?op=traertotalacredito',{
            method: 'POST',
            body: data5
        })
         .then(respuesta=>respuesta.json())
         .then(datos=>{
            //console.log(datos);
            //console.log(datos.sinpromo);
            totcredsinpromo=datos.sinpromo==null?0:parseFloat(datos.sinpromo);
            totcredconpromo=datos.promo==null?0:parseFloat(datos.promo);
            totalacredito=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format((totcredsinpromo+totcredconpromo));
            $('#totalacredito').html(totalacredito);
            $('#totalacredito').attr('data-creditos',(totcredsinpromo+totcredconpromo));
          }) 
    }catch(showErrorFetch){    
    }    
}

/*==== CONSULTA DE INGRESOS E EGRESOS ======== */
traeringresosegresos(iddecaja,numdecaja,cerrado);
async function traeringresosegresos(iddecaja,numdecaja,cerrado){
    let datos6 = new FormData();
	datos6.append("item", iddecaja);
    datos6.append("iddeCaja", numdecaja);
    datos6.append("cerrado", cerrado);
    try{
        await fetch('ajax/vercaja.ajax.php?op=ingresoegreso', {
            method: 'POST',
            body: datos6
        })
        .then(respuesta=>respuesta.json())
        .then(datos=>{
            console.log(datos);
            totalingreso=datos==null?0:datos.monto_ingreso;
            totalegreso=datos==null?0:datos.monto_egreso;
            $('#totalingreso').attr('data-ingreso',totalingreso);
            $('#totalegreso').attr('data-egreso',totalegreso);
            totalingreso=new Intl.NumberFormat('en',{style:'currency',currency:'USD',currencySign: 'accounting',}).format(totalingreso);
            totalegreso=new Intl.NumberFormat('en',{style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(totalegreso);
            $('#totalingreso').html(totalingreso);
            $('#totalegreso').html(totalegreso);
            //console.log("ingreso:", $('#totalingreso' ).data( 'ingreso' ) );
            //console.log("egreso:", $('#totalegreso' ).data( 'egreso' ) );
            //console.log("entro5");
        })
    }catch(showErrorFetch){    
    }    
}            

/*==== CONSULTA CAJA CHICA ======== */
function traerimportecajachica(iddecaja,numdecaja,cerrado,datecurrent){
    //console.log("entra cajachica:",iddecaja,numdecaja,cerrado,datecurrent);
    let datos = new FormData();
	datos.append("item", iddecaja);
    datos.append("iddeCaja", numdecaja);
    datos.append("cerrado", cerrado);
    datos.append("fechaactual", datecurrent);
    try{
        fetch('ajax/vercaja.ajax.php?op=importecajachica', {
            method: 'POST',
            body: datos
        })
        .then(respuesta=>respuesta.json())
        .then(datos=>{
            //console.log(datos);
            totalcajachica=(datos==null)?0:datos.cajachica;
            //$('#totalcajachica').attr('data-ingreso',totalcajachica);
            totalcajachica=new Intl.NumberFormat('en',{style:'currency',currency:'USD',currencySign: 'accounting',}).format(totalcajachica);
            $('#totalcajachica').html(totalcajachica);

            //console.log("ingreso:", $('#totalingreso' ).data( 'ingreso' ) );
            //console.log("entro5");
        })
    }catch(showErrorFetch){    
    }    
}            


function sumartotalcaja(){
    totalefectivo = 0
    var data="";
    //console.log("ingreso:", $('#totalingreso' ).data( 'ingreso' ) );
    //console.log("egreso:", $('#totalegreso' ).data( 'egreso' ) );

	$(".idforsuma").each(function(index, value) {
        //console.log(index, value);
        //console.log("evalua: ",$(this).data());
        data = $(this).data();
        for(var i in data){
          //console.log("itera:",i,"imprte:",data[i]);
            if(i==="egreso"){
                if(parseFloat(data[i])>0){
                    totalefectivo-=parseFloat(data[i]);    
                }
            }else{
               totalefectivo+=parseFloat(data[i]) || 0;
            }
                //console.log(data[i])
        }            
    });

    totalefectivo=new Intl.NumberFormat('en', {style: 'currency',currency: 'USD',currencySign: 'accounting',}).format(totalefectivo);
    $('#totalefectivo').html(totalefectivo);
    //console.log("entro final",totalefectivo);
}

function showErrorFetch(err) { 
    console.log('muestra error', err);
    swal.fire({
        title: "Error!!",
        text: err,
        icon: "error",
        })  //fin swal
  }
  

// using jQuery
function getCookie(name) {
    var cookieValue = null;
    if (document.cookie && document.cookie !== '') {
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = jQuery.trim(cookies[i]);
            // Does this cookie string begin with the name we want?
            if (cookie.substring(0, name.length + 1) === (name + '=')) {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    return cookieValue;
}
//var csrftoken = getCookie('csrftoken');
//console.log(csrftoken);

/*
$('#cajaactiva').on('click', function(ev) {
    //window.location = "inicio";
    
     window.setTimeout(function(){ 
        console.log("entra")
        location.reload();
        abrirmodal();
     } ,2000);
});

$('#cajaAbierta').on('show.bs.modal', function (e) {
})

*/
setTimeout(sumartotalcaja,1200); 
});

$("#cajaAbierta").on('hidden.bs.modal', ()=> {
    //console.log("removedata");
    $( "#databox" ).removeData();
    $( "#databox" ).removeAttr();
    //console.log("ingreso:", $('#totalingreso' ).data( 'ingreso' ) );
});
