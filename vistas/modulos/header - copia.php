<!-- Navbar -->
<nav class="navbar navbar-expand bg-white navbar-light border-bottom p-1 margenizq">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
      
      <li class="nav-item d-none d-sm-inline-block">
        <a href="inicio" class="nav-link hover-underline-animation text-bold ml-3" style="color:magenta; font-style:oblique; text-decoration-style: wavy;">Inicio</a>
      </li>


      <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil']=="Administrador" || trim($_SESSION["usuario"])=="ari" || trim($_SESSION["usuario"])=="visitante"){ ?>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="control-facturas" class="nav-link font-weight-bold  ml-0 pl-0" title="Gestión de Facturas a Prov.">
          <button type="button" class="btn btn-sm btn-info text-bold">Ctrl. Facturas</button>
        </a>
      </li>
      <?php } ?>

      <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil']=="Administrador" || trim($_SESSION["usuario"])=="super"){ ?>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="control-depositos" class="nav-link font-weight-bold ml-0 pl-0" title="Control de Depósitos Bancarios">
          <button type="button" class="btn btn-sm btn-fipabide text-bold">Ctrl. Depósitos</button>
        </a>
      </li>
      <?php } ?>

      <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil']=="Administrador" || trim($_SESSION["usuario"])=="super"){ ?>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="adminoservicio" class="nav-link font-weight-bold ml-0 pl-0" title="Gestión de Orden de Servicios">
          <button type="button" class="btn btn-sm text-bold" style="background-color:greenyellow; color:forestgreen;">O. S.</button>
        </a>
      </li>
      <?php } ?>

      <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil']=="Administrador" || trim($_SESSION["usuario"])=="super"){ ?>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="facturaingreso" class="nav-link font-weight-bold ml-0 pl-0" title="Nunosco Facturación Ver. 4.0- Alt-Q" accesskey="q">
          <img srcset="vistas/img/nunoscofacturacion40.jpg 1x, vistas/img/nunoscofacturacion40.jpg 2x" class="img-responsive rounded float-right" style="width: 3.45rem;" alt="Img Factura">
        </a>
      </li>
      <?php } ?>
      
    </ul>


    <!-- **************************************************************
                          HORA Y FECHA 
    ******************************************************************-->
	  <div class="ml-1 mr-1 d-none d-sm-inline-block d-md-inline-block text-center" id="datepicker5" style="width:35rem; color:violet; font-weight: bold;" onclick="vercalendario()">
      <a href="#">
         <p id="liveclock"> </p>
      </a>
    </div>	
    <!-- ************************************************************** -->

    <!-- **************************************************************
                          ONLINE
    ******************************************************************-->
      <div id='statusnetwork' class="col-md-1 text-left mr-1"> 
        <!-- Visualiza si esta online u offline -->
      </div>
    <!-- ************************************************************** -->


    <!-- **************************************************************
                          CLIMA
    ******************************************************************-->
    <div class="col-md-1 text-left" style='font-size:.95em;'>
        <button type="button" class="btn btn-sm btn btn-warning rounded p-1">
        <i class="fa fa-thermometer-full" id="clima"></i>
        </button>        
    </div>
    <!-- ************************************************************** -->
    <!-- **************************************************************
                          USUARIOS CONECTADOS
    ******************************************************************-->
    <div class="col-md-1" style='font-size:.40em;'>
        <button type="button" class="btn btn-sm btn-success rounded p-1"><i class="fa fa-users fa-lg" id="numusers"></i> </button>        
    </div>
    <!-- ************************************************************** -->
<script>
if (typeof EventSource !== "undefined") {
    //Server.php es donde está el código php que va a lanzar los eventos
    var source = new EventSource("./modelos/serverside.php");
    source.onmessage = function(event) {
        //result es el id de un div donde se escribirán los eventos
       //console.log(event.data)
        document.getElementById("numusers").innerHTML = " "+event.data;
    };
} else {
    //console.log("no entro")
    //console.log(event.data)
    document.getElementById("numusers").innerHTML = "";
}

</script>
    <!-- ************************************************************** -->
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">

              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo $_SESSION['foto']?>" class="user-image" alt="Image" width="25px">
                  <span class="hidden-xs" id="usuar"><?php echo $_SESSION['usuario'];?></span>
                </a>
                
                <ul class="dropdown-menu"  style="background-color:orange;">

                  <!-- User image -->
                  <li class="user-header text-center">
					          <img src="<?php echo $_SESSION['foto']?>" class="user-image img-size-50 img-circle" alt="User Image">
                  </li>

                  <li class="user-body text-center" >
                    <span class="hidden-xs" id="usuar"><?php echo $_SESSION['nombre'];?></span>
                      <div class="dropdown-divider"></div>
                        Software de Inventario <small>contacto@fipabide.com.mx</small>
                  </li>
				  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="text-center">
                     <div class="dropdown-divider"></div>
                      <a href="salir" class="btn btn-success btn-flat">Salir  &nbsp&nbsp<i class="fa fa-sign-out"></i></a>		<!--	cerrar la aplicacion  -->
                    </div>
                  </li>
                  
                </ul>
              </li>	
	
              <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#" title="Comentarios">
                <i class="fa fa-comments-o"></i><span class="badge badge-danger navbar-badge blink">3</span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i class="fa fa-th-large"></i></a>
            </li> 
      </ul>

</nav>  <!-- /.navbar -->

 <script>
// var inactivityTimeout = 15 * 60 * 1000; // 15 minutos en milisegundos
// var inactivityTimer;

// function resetInactivityTimer() {
//     clearTimeout(inactivityTimer);
//     //inactivityTimer = setTimeout(logout, inactivityTimeout);
//     console.log("entra aquiii....")
// }

// function logout() {
//     // Hacer una llamada AJAX a un script PHP para cerrar la sesión
// }

// // Detectar eventos de interacción del usuario (clics, teclas)
// document.addEventListener("click", resetInactivityTimer);
// document.addEventListener("keydown", resetInactivityTimer);
const { fromEvent, merge, of } = rxjs;
const { take, tap, mergeMap, finalize, debounceTime } = rxjs.operators;
const { clear, filter, map, switchMapTo, distinctUntilChanged } = rxjs.operators;
var inactivityTimeout=10000;

const clicks$ = rxjs.fromEvent(document, "click");
const keydowns$ = rxjs.fromEvent(document, "keydown");
const onmouse$ = rxjs.fromEvent(document, "mousemove");

const activity$ = rxjs.merge(clicks$, keydowns$, onmouse$);

const inactivityTimer$ = activity$.pipe(
  debounceTime(inactivityTimeout),
  switchMapTo(of("logout")) // Emite "logout" después de la inactividad
);

inactivityTimer$.subscribe((action) => {
  console.log("hiciste click")
  if (action === "logout") {
    // Hacer una llamada AJAX a un script PHP para cerrar la sesión
    // Aquí puedes usar fetch o cualquier otra librería de AJAX
  }
});

activity$.subscribe(() => {
  console.log("Se detectó un clic o interacción del usuario.");
  //alert("Tienes tiempo sin hacer nada.")
  inactivityTimeout = 15 * 60 * 1000; // 15 minutos en milisegundos
});
 </script>
<!-- ========================================================================================== -->
<script defer src="vistas/js/consultaclima.js?v=01042022"></script>
