
/*=============================================
VER EXPEDIENTE DE Factura
=============================================*/
/*$("#TablaFacturas").on("click", ".btnVerFactura", function(){

  var idFactura = $(this).attr("idFactura");
  var numFactura = $(this).attr("numFactura");
  var idEstatus = $(this).attr("idEstado");
  var filePdf=null;
  var fila=null;
  console.log(idFactura, numFactura,idEstatus);
	var datos = new FormData();
	datos.append("idFactura", idFactura);
	datos.append("numFactura", numFactura);
	datos.append("idEstatus", idEstatus);
 
  (async () => { 
   await fetch('ajax/control-facturas.ajax.php?op=mostrar', {
    method: 'POST',
    body: datos
   })
     .then(respuesta=>respuesta.json())
     .then(datos=>{

		console.log(datos.rutaexpediente)
		filePdf=datos.rutaexpediente;
		if(filePdf===null || filePdf==="" || filePdf===undefined){
			
			//fila=$("#target object").attr("data","");
			fila='<object id="pdfdoc" data="" type="application/pdf" width="100%" height="580px" style="height:80vh;">';
			console.log("entra",datos.rutaexpediente, fila)
			$('#seepdf').append(fila);
		}else{
			let element = document.getElementById("cover");
			element.classList.remove("d-none");
			//$("#target object").attr("data",filePdf);  
			fila='<object id="pdfdoc" data="vistas/expedientes/EXPEDIENTE74.pdf" type="application/pdf" width="100%" height="580px" style="height:80vh;">';
			console.log(idFactura,filePdf,fila);
			element.classList.add("d-none");
			$('#seepdf').append(fila);
		}	
     }) 
	 
     .catch(showError);     

	 $("#modalVerFactura").on('show.bs.modal',function() {
			let element = document.getElementById("cover");
			element.classList.remove("d-none");
			//$("#target object").attr("data",filePdf);  
			fila='<object id="pdfdoc" data="vistas/expedientes/EXPEDIENTE74.pdf" type="application/pdf" width="100%" height="580px" style="height:80vh;">';
			console.log(idFactura,filePdf,fila);
			element.classList.add("d-none");
			$('#seepdf').append(fila);
	 });


})();  //fin del async	 
})*/



//============ ENTRAR AL MODAL DE VER FACTURA=================
/*$("#modalVerFactura").on('show.bs.modal',function() {
	$("#target object").attr("data","");  
	
	var idFactura = $(this).attr("idFactura");
	var datos = new FormData();
	datos.append("idFactura", idFactura);
	//datos.append("numFactura", numFactura);
	console.log(idFactura);
(async () => { 
   await fetch('ajax/control-facturas.ajax.php?op=mostrar', {
    method: 'POST',
    body: datos
   })

     .then (respuesta=>respuesta.json())
     .then(datos=>{
		//console.log(datos)
		let filepdf=datos.rutaexpediente;
     }) 
     .catch(showError);     

})();  //fin del async	 	
	
	let element = document.getElementById("cover");
	element.classList.remove("d-none");
	console.log(idFactura);
    $("#target object").attr("data","vistas/expedientes/EXPEDIENTE74.pdf");  
    element.classList.add("d-none");

});*/