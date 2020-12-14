<!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="inicio" class="nav-link hover-underline-animation">Inicio</a>
      </li>
      <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil']=="Administrador" || trim($_SESSION["usuario"])=="iaovando" || trim($_SESSION["usuario"])=="Kevin" ){ ?>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="control-viaticos" class="nav-link font-weight-bold" id="">
          <button type="button" class="btn btn-sm btn-warning">Viáticos</button>
        </a>
      </li>
      <?php } ?>

      <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil']=="Administrador" || trim($_SESSION["usuario"])=="ari" || trim($_SESSION["usuario"])=="visitante"){ ?>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="control-facturas" class="nav-link font-weight-bold" id="">
          <button type="button" class="btn btn-sm btn-info">Ctrl de Facturas</button>
        </a>
      </li>
      <?php } ?>
    </ul>

    <!-- SEARCH FORM 
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </form>
    -->
	<!-- HORA Y FECHA -->
	<div class="ml-5 mr-5 d-none d-sm-inline-block d-md-inline-block text-center" style="width:40rem;">
        <p class="h6" id="liveclock" style="margin-top:5px;color:violet;"></p>
    </div>	

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
					Software de Inventario
                      <small>contacto@fipabide.com.mx</small>
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
	
         <!-- salir de la APP 
          <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             <?php
              // if($_SESSION["foto"]!=""){
                  // echo '<img src="'.$_SESSION["foto"].'" alt="Usuario" class="user-image" width="25px">';
              // }else{
                  // echo '<img src="vistas/modulos/dist/img/anonymous.png" alt="Usuario" class="user-image" width="25px">';
              // }
              
             ?>
              <span class="hidden-xa"><?= $_SESSION["usuario"] ?></span>
          </a>
          <ul class="dropdown-menu">
              <li class="user-body">
                  <div class="pull-right">
                      <a href="salir" class="btn btn-default btn-flat">Salir</a>
                  </div>
              </li>
          </ul>
      </li> -->
     
     
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        
         <a class="nav-link" data-toggle="dropdown" href="#" title="Comentarios">
          <i class="fa fa-comments-o"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <!--
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start 
            <div class="media">
              <img src="vistas/modulos/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fa fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End 
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start 
            <div class="media">
              <img src="vistas/modulos/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fa fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End 
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start 
            <div class="media">
              <img src="vistas/modulos/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fa fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End 
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
		-->
      </li>
      
      <!-- Notifications Dropdown Menu 
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" title="Notificaciones">
          <i class="fa fa-bell-o"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fa fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fa fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fa fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
	  -->
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
            class="fa fa-th-large"></i></a>
      </li>
      
    </ul>
  </nav>
  <!-- /.navbar -->
  
 <script>

 
 </script>