<?php
require_once './funciones/funciones.php';

$tabla="usuarios";
$moduloadmin="administracion";
$modulocat="catalogo";
$modulorep="reportes";
$moduloconf="configura";

$permisosadmin = ControladorPermisos::ctrGetPermisos($tabla, $_SESSION['id'], $moduloadmin);
$permisoscat = ControladorPermisos::ctrGetPermisos($tabla, $_SESSION['id'], $modulocat);
$permisosrep = ControladorPermisos::ctrGetPermisos($tabla, $_SESSION['id'], $modulorep);
$permisosconf = ControladorPermisos::ctrGetPermisos($tabla, $_SESSION['id'], $moduloconf);

$jsonpermisos1=json_decode($permisosadmin->administracion, TRUE);   //convierte string a array
$jsonpermisos1=($jsonpermisos1==NULL)?$jsonpermisos1=["SINDATO"=>0]:$jsonpermisos1;

$jsonpermisos2=json_decode($permisoscat->catalogo, TRUE);   //convierte string a array
$jsonpermisos2=($jsonpermisos2==NULL)?$jsonpermisos2=["SINDATO"=>0]:$jsonpermisos2;

$jsonpermisos3=json_decode($permisosrep->reportes, TRUE);   //convierte string a array
$jsonpermisos3=($jsonpermisos3==NULL)?$jsonpermisos3=["SINDATO"=>0]:$jsonpermisos3;

$jsonpermisos4=json_decode($permisosconf->configura, TRUE);   //convierte string a array
$jsonpermisos4=($jsonpermisos4==NULL)?$jsonpermisos4=["SINDATO"=>0]:$jsonpermisos4;

//$psalidas=$pentradas=$pcapseries=$pdevalm=$pctfacts=$pctviaticos=$posvilla=$prespaldo=0;
//$jsonpermisos1=json_decode($permisosadmin->administracion, TRUE);   //convierte string a array
// if (array_key_exists('pcapseries', $jsonpermisos1)) {
//   $val=$jsonpermisos1['pcapseries'];
//   echo "&nbsp &nbspnbsp &nbspnbsp &nbsp The 'first' element is in the array $val";
// }

// if(!$jsonpermisos1==NULL){
//   foreach ($jsonpermisos1 as $key => $value) {

//     if($key=="psalidas"){
//       $psalidas=$value;
//     }
//     if($key=="pentradas"){
//       $pentradas=$value;
//     }
//     if($key=="pcapseries"){
//       $pcapseries=$value;
//     }
//     if($key=="pdevalm"){
//       $pdevalm=$value;
//     }
//     if($key=="pctfacts"){
//       $pctfacts=$value;
//     }
//     if($key=="pctviaticos"){
//       $pctviaticos=$value;
//     }
//     if($key=="posvilla"){
//       $posvilla=$value;
//     }
//     if($key=="prespaldo"){
//       $prespaldo=$value;
//     }
//   }
//  }

?>
<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="inicio" class="brand-link">
      <img src="extensiones/dist/img/logo_nunosco_small.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">AdminInv3</span>
    </a>

   
     <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <!-- <img src="vistas/modulos/dist/img/avatar5.png" class="img-circle elevation-2" alt="User Image"> -->
            <?php
              if($_SESSION["foto"]!=""){
                  echo '<img src="'.$_SESSION["foto"].'" alt="Usuario" class="img-circle elevation-2 user-image" width="25px">';
              }else{
                  echo '<img src="vistas/modulos/dist/img/anonymous.png" alt="Usuario" class="user-image" width="25px">';
              }
              
            ?>		  
        </div>
        <div class="info">
          <a href="#" class="d-block"><?= $_SESSION["perfil"] ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               
               
          <li class="nav-item ">  <!-- menu-open si se quiere q este abierto esta opcion -->
            <a href="inicio" class="nav-link active">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
      
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-th"></i>
              <p>
                Administración
				        <i class="fa fa-angle-left right"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">

            <?php
            if(getAccess(array_key_exists("psalidas",$jsonpermisos1)?$jsonpermisos1["psalidas"]:0,ACCESS_ACC)){ ?>
              <li class="nav-item">
                <a href="salidasalmacen" class="nav-link">
				          <i class="fa fa-truck nav-icon"></i>
                  <p>Salidas de Almacén</p>
                </a>
              </li>
            <?php } ?>  
            
            <?php
            if(getAccess(array_key_exists("pentradas",$jsonpermisos1)?$jsonpermisos1["pentradas"]:0,ACCESS_ACC)){ ?>
              <li class="nav-item">
                <a href="entradasalmacen" class="nav-link">
                  <i class="fa fa-shopping-cart nav-icon"></i>
                  <p>Entradas al Almacén </p>
                </a>
              </li>
            <?php } ?>

            <?php if(getAccess(array_key_exists("pcapseries",$jsonpermisos1)?$jsonpermisos1["pcapseries"]:0,ACCESS_ACC)){ ?>
              <li class="nav-item">
                <a href="adminseries" class="nav-link">
                  <i class="fa fa-barcode nav-icon"></i>
                  <p>Capturar Series</p>
                </a>
              </li>
            <?php } ?>  

            <?php if(getAccess(array_key_exists("ajusteinv",$jsonpermisos1)?$jsonpermisos1["ajusteinv"]:0,ACCESS_ACC)){ ?>
              <li class="nav-item">
                <a href="ajusteinventario" class="nav-link">
                  <i class="fa fa-exchange nav-icon"></i>
                  <p>Ajuste de Inventario</p>
                </a>
              </li>
            <?php } ?>

            <?php 
            if(getAccess(array_key_exists("pdevalm",$jsonpermisos1)?$jsonpermisos1["pdevalm"]:0,ACCESS_ACC)){ ?>
              <li class="nav-item">
                <a href="devolucion-tecnicos" class="nav-link">
                  <i class="fa fa-reply-all nav-icon"></i>
                  <p>Devolución al Almacén</p>
                </a>
              </li>
            <?php } ?>  

            <?php 
            if(getAccess(array_key_exists("pctfacts",$jsonpermisos1)?$jsonpermisos1["pctfacts"]:0,ACCESS_ACC)){ ?>
              <li class="nav-item">
                <a href="control-facturas" class="nav-link">
                  <i class="fa fa-th-list nav-icon"></i>
                  <p>Control Facturas</p>
                </a>
              </li>
            <?php } ?>			 
      
            <?php 
            if(getAccess(array_key_exists("pctviaticos",$jsonpermisos1)?$jsonpermisos1["pctviaticos"]:0,ACCESS_ACC)){ ?>
              <li class="nav-item">
                  <a href="control-viaticos" class="nav-link">
                    <i class="fa fa-car nav-icon"></i>
                    <p>Control Viáticos</p>
                  </a>
                </li>
            <?php } ?>  

            <?php 
            if(getAccess(array_key_exists("posvilla",$jsonpermisos1)?$jsonpermisos1["posvilla"]:0,ACCESS_ACC)){ ?>
              <li class="nav-item">
                <a href="osvilla" class="nav-link">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>OS Villa</p>
                </a>
              </li>
            <?php } ?>  

            <?php 
            if(getAccess(array_key_exists("prespaldo",$jsonpermisos1)?$jsonpermisos1["prespaldo"]:0,ACCESS_ACC)){ ?>
              <li class="nav-item">
                <a href="respaldo" class="nav-link">
                  <i class="nav-icon fa fa-database"></i>
                  <p>
                    Respaldo
                  </p>
                </a>
              </li>  
            <?php } ?>

        </ul>
			
        </li>


	      <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-table"></i>
              <p>
                Catalogos
                <i class="fa fa-angle-left right"></i>
              </p>
            </a>
          
            <ul class="nav nav-treeview">

            <?php if(getAccess(array_key_exists("pproductos",$jsonpermisos2)?$jsonpermisos2["pproductos"]:0,ACCESS_ACC)){ ?>
              <li class="nav-item">
                <a href="productos" class="nav-link">
                  <i class="fa fa-tag nav-icon"></i>
                  <p>Productos</p>
                </a>
              </li>
            <?php } ?>

            <?php if(getAccess(array_key_exists("proveedores",$jsonpermisos2)?$jsonpermisos2["proveedores"]:0,ACCESS_ACC)){ ?>
              <li class="nav-item">
                <a href="proveedores" class="nav-link">
                  <i class="fa fa-male nav-icon"></i>
                  <p>Proveedores</p>
                </a>
              </li>
            <?php } ?>

            <?php if(getAccess(array_key_exists("pclientes",$jsonpermisos2)?$jsonpermisos2["pclientes"]:0,ACCESS_ACC)){ ?>              
              <li class="nav-item">
                <a href="clientes" class="nav-link">
                  <i class="fa fa-address-book nav-icon"></i>
                  <p>Clientes</p>
                </a>
              </li>
            <?php } ?>

            <?php if(getAccess(array_key_exists("ptecnicos",$jsonpermisos2)?$jsonpermisos2["ptecnicos"]:0,ACCESS_ACC)){ ?>
              <li class="nav-item">
                <a href="tecnicos" class="nav-link">
                  <i class="fa fa-tty nav-icon"></i>
                  <p>Técnicos</p>
                </a>
              </li>
            <?php } ?>              
        
            <?php if(getAccess(array_key_exists("pcategorias",$jsonpermisos2)?$jsonpermisos2["pcategorias"]:0,ACCESS_ACC)){ ?>              
              <li class="nav-item">
                <a href="categorias" class="nav-link">
                  <i class="fa fa-th nav-icon"></i>
                  <p>Categorias</p>
                </a>
              </li>
            <?php } ?>

            <?php if(getAccess(array_key_exists("pmedidas",$jsonpermisos2)?$jsonpermisos2["pmedidas"]:0,ACCESS_ACC)){ ?>              
              <li class="nav-item">
                <a href="medidas" class="nav-link">
                  <i class="fa fa-tachometer nav-icon"></i>
                  <p>U.de Med.</p>
                </a>
              </li>
            <?php } ?>

            <?php if(getAccess(array_key_exists("palmacenes",$jsonpermisos2)?$jsonpermisos2["palmacenes"]:0,ACCESS_ACC)){ ?>              
              <li class="nav-item">
                <a href="crear-almacen" class="nav-link">
                  <i class="fa fa-building-o nav-icon"></i>
                  <p>Crear Almacen</p>
                </a>
              </li>
            <?php } ?>

            <?php if(getAccess(array_key_exists("ptiposmov",$jsonpermisos2)?$jsonpermisos2["ptiposmov"]:0,ACCESS_ACC)){ ?>
              <li class="nav-item">
                <a href="tipomov" class="nav-link">
                  <i class="fa fa-cc nav-icon"></i>
                  <p>Tipos de Mov.</p>
                </a>
              </li>
            <?php } ?>

    			</ul>
		  	</li>
      
			<li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-file"></i>
              <p>
                Reportes
                <i class="fa fa-angle-left right"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">

            <?php if(getAccess(array_key_exists("rsalidas",$jsonpermisos3)?$jsonpermisos3["rsalidas"]:0,ACCESS_ACC)){ ?>              
              <li class="nav-item">
                <a href="adminsalidas" class="nav-link">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Rep. de Salidas</p>
                </a>
              </li>
            <?php } ?>

            <?php if(getAccess(array_key_exists("rentradas",$jsonpermisos3)?$jsonpermisos3["rentradas"]:0,ACCESS_ACC)){ ?>              
              <li class="nav-item">
                <a href="adminalmacenes" class="nav-link">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Rep. de Entradas</p>
                </a>
              </li>
            <?php } ?>

            <?php if(getAccess(array_key_exists("rinventarios",$jsonpermisos3)?$jsonpermisos3["rinventarios"]:0,ACCESS_ACC)){ ?>                          
              <li class="nav-item">
                <a href="reporteinventario" class="nav-link">
                  <i class="fa fa-book nav-icon"></i>
                  <p>Rep. de Inventario</p>
                </a>
              </li>
            <?php } ?>

			      </ul>
			</li>

      
        <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fa fa-gear"></i>
                <p>Configuración<i class="fa fa-angle-left right"></i></p>
              </a>
              
              <ul class="nav nav-treeview">

              <?php if(getAccess(array_key_exists("usuarios",$jsonpermisos4)?$jsonpermisos4["usuarios"]:0,ACCESS_ACC)){ ?>
                <li class="nav-item">
                  <a href="usuarios" class="nav-link">
                    <i class="nav-icon fa fa-users"></i>
                    <p>
                      Usuarios
                    </p>
                  </a>
                </li>
              <?php } ?>

            <?php if(getAccess(array_key_exists("permisos",$jsonpermisos4)?$jsonpermisos4["permisos"]:0,ACCESS_ACC)){ ?>
              <li class="nav-item">
                <a href="permisos" class="nav-link">
                  <i class="nav-icon fa fa-user-plus"></i>
                  <p>
                    Permisos
                  </p>
                </a>
              </li>
            <?php } ?>            

            <?php if(getAccess(array_key_exists("empresa",$jsonpermisos4)?$jsonpermisos4["empresa"]:0,ACCESS_ACC)){ ?>              
              <li class="nav-item">
                <a href="empresa" class="nav-link">
                  <i class="nav-icon fa fa-building"></i>
                  <p>
                    Empresa
                  </p>
                </a>
              </li>
            <?php } ?>              
            </ul>
        </li>

    </ul>
      </nav>
      <!-- /.sidebar-menu -->
      
    </div>
    <!-- /.sidebar -->
  </aside>

  