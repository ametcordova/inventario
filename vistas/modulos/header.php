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
	  <div class="ml-1 mr-1 mt-3 d-none d-sm-inline-block d-md-inline-block text-center" id="datepicker5" style="width:35rem; color:violet; font-weight: bold;" onclick="vercalendario()">
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
        <button type="button" class="btn btn-sm btn-success rounded p-1 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  title="Usuarios conectados"><i class="fa fa-users fa-lg" id="numusers"></i> </button>
        <div class="dropdown-menu" style="background-color:#7EF379;" id="userslog">
        </div>  
    </div>

    <!-- ************************************************************** -->
  <script>
  const dropdownMenu = document.getElementById("userslog");
  if (typeof EventSource !== "undefined") {
      //Server.php es donde está el código php que va a lanzar los eventos
      const source = new EventSource("./modelos/serverside.php");
       source.onopen=(e)=>{
         console.log("connected to the server event")
       }

      document.getElementById("numusers").innerHTML = "";

      source.onmessage = (event)=> {
          //result es el id de un div donde se escribirán los eventos
        //console.log(event)
        const data = JSON.parse(event.data);
        dropdownMenu.innerHTML="";
        //recorre el data de los usuarios conectados
        data.forEach(obj => {
          let dropdownItem = document.createElement("a");
          dropdownItem.classList.add("dropdown-item");
          dropdownItem.classList.add("font-weight-bold");
          dropdownItem.href = "#";
          
          dropdownItem.textContent = `id: ${obj.id} - Usuario: ${obj.usuario}`;

          dropdownMenu.appendChild(dropdownItem);
        });

        document.getElementById("numusers").innerHTML = " "+data[0].logueados;;

        source.addEventListener('message', function(e) {
          var data = JSON.parse(e.data);
          //console.log(data);
          }, false);


          source.addEventListener('update', function(e) {
            var data = JSON.parse(e.data);
            console.log(data.username + ' is now ' + data.emotion);
            }, false);          
        // source.addEventListener('evento',()=>{
        //   //console.log(`Que usuario: ${usuario}`)
        //   console.log(`Que usuario: `)
        // })

         source.onerror=(err)=>{
           if(err){
            //console.error("Event source failed:", err);
             //console.log("error. Se cerró la conexión.", err)
             //source.close()
           }
         }
        
         source.addEventListener('users',function(ev) {
           console.log(ev.data)
         });


        // Habilitar esta funcion, si es necesario.
        // function stopper(){
        //   source.close()
        //   console.log("Evento: se cerró la conexión.")
        // }

      };
  } else {
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
 </script>
<!-- ========================================================================================== -->
<script defer src="vistas/js/consultaclima.js?v=01072023"></script>
