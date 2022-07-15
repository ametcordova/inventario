/*************************************************
* document.addEventListener("DOMContentLoaded", funcionInit);
**************************************************/

const resultado=document.querySelector('#clima');
var idWatcher = null;
/*======================================================================*/

function init(){
	if (!"geolocation" in navigator) {
		return alert("Tu navegador no soporta el acceso a la ubicación. Intenta con otro");
	}else{
    geolocalizacion();
  }
};


function geolocalizacion(){

	const onErrorDeUbicacion = err => {
		console.log("Error obteniendo ubicación: " + err.message);
		console.log("Error obteniendo ubicación: ", err);
  }

  const onUbicacionConcedida = ubicacion => {
      const coordenadas = ubicacion.coords;
      let $latitud = coordenadas.latitude;
      let $longitud = coordenadas.longitude;
      //loguear(`${ubicacion.timestamp}: ${coordenadas.latitude},${coordenadas.longitude}`);
      solicitarClima($latitud, $longitud);
      // console.log('Lat:', $latitud )
      // console.log('Long',$longitud)
      // console.log('Ubi:', ubicacion.timestamp )
  }


  const opcionesDeSolicitud = {
    enableHighAccuracy: true, // Alta precisión
    maximumAge: 0, // No queremos caché
    timeout: 5000 // Esperar solo 5 segundos
  };

		detenerWatcher();
		idWatcher = navigator.geolocation.watchPosition(onUbicacionConcedida, onErrorDeUbicacion, opcionesDeSolicitud);
}


const detenerWatcher = () => {
  if (idWatcher) {
    navigator.geolocation.clearWatch(idWatcher);
  }
}


function solicitarClima($lat, $lon){
let $api_key='d6922ec05be6179fddf42ac294fdddcb';
// let $city='Tuxtla';
// let $country='MX';
// let $lon='-93.0923233';
// let $lat='16.7713818';
//let $url=`https://api.openweathermap.org/data/2.5/weather?q=${$city},${$country}&appid=${$api_key}`;
let $url=`https://api.openweathermap.org/data/2.5/weather?lat=${$lat}&lon=${$lon}&appid=${$api_key}`;

      axios($url) 
      .then((resp)=>{ 
        if(resp.status==200) {
          //console.log(resp.data, resp.status, resp.statusText)
            mostrarclima(resp.data);
        }else{
            console.log(resp);                 
        }

      }) 

      .catch((err) => {throw err}); 
}


function mostrarclima(datos){
  limpiarHTML();
  const {main: {temp, temp_max, temp_min}}=datos;
  const centigrados=kelvinaCentigrados(temp);
  //console.log(centigrados);
  const span=document.createElement('span');
  span.classList.add('badge', 'badge-light', 'text-dark', 'ml-1');
  span.innerHTML=`${centigrados} &#8451`
  resultado.appendChild(span);
}


const kelvinaCentigrados= grados => Math.round(grados-273.15);


function limpiarHTML(){
    while(resultado.firstChild){
        resultado.removeChild(resultado.firstChild);
    }
}


init();




