<?php
error_reporting(0);     //EVITA QUE APAREZCA EL ERROR
$tabla="usuarios";
$modulocat="catalogo";
$permisoscat = ControladorPermisos::ctrGetPermisos($tabla, $_SESSION['id'], $modulocat);
$producto=$categoria=$familia=$proveedor=$cliente=$almacen=0;
$jsonpermisos=json_decode($permisoscat->catalogo, TRUE);   //convierte string a array
if(!$jsonpermisos==NULL){
  foreach ($jsonpermisos as $key => $value) {

    if($key=="productos"){
      $producto=$value;
    }

    if($key=="categorias"){
      $categoria=$value;
    }

    if($key=="familias"){
      $familia=$value;
    }

    if($key=="proveedores"){
      $proveedor=$value;
    }

    if($key=="clientes"){
      $cliente=$value;
    }

    if($key=="almacenes"){
      $almacen=$value;
    }

  }
 }
$fechadeHoy=date("d/m/Y");
//echo "entra1".'<br>';
$item = null;
$valor = null;
$categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
$totalCategorias = count($categorias);

$item = null;
$valor = null;
$familias = ControladorFamilias::ctrMostrarFamilias($item, $valor);
$totalFamilias = count($familias);

$item = null;
$valor = null;
$clientes = ControladorClientes::ctrMostrarClientes($item, $valor);
$totalClientes = count($clientes);

$item = null;
$valor = null;
$orden = "id";
$estado=1;
$productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden, $estado);
$totalProductos = count($productos);

$item = null;
$valor = null;
$tabla = "proveedores";
$proveedores = ControladorProveedores::ctrContarProveedores($tabla, $item, $valor);

$item = null;
$valor = null;
$almacenes = ControladorAlmacenes::ctrMostrarAlmacenes($item, $valor);
$totalAlmacenes = count($almacenes);

?>
<div class="row">

    <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo number_format($totalProductos); ?></h3>
                    <p>Productos</p>
              </div>
              <div class="icon">
                <i class="ion ion-pricetags"></i>
              </div>
              <?php if(getAccess($producto, ACCESS_ACC)){ ?>
                  <a href="productos" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
              <?php }else{ ?>
                  <a href="" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
              <?php } ?>
            </div>
    </div>
     <!-- ./col -->

     <!-- ./col -->
    <div class="col-lg-2 col-6">
         <!-- small box -->
         <div class="small-box bg-success">
            <div class="inner">
              <h3><?php echo number_format($totalCategorias); ?></h3>
                <p>Categorías</p>
            </div>
             <div class="icon">
                <i class="ion ion-clipboard"></i>
             </div>
             <?php if(getAccess($categoria, ACCESS_ACC)){  ?>
              <a href="categorias" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
            <?php }else{ ?>              
              <a href="" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
            <?php } ?> 
         </div>
    </div>

    <div class="col-lg-2 col-6">
        <!-- small box -->
        <div class="small-box bg-primary">
          <div class="inner">
             <h3><?php echo number_format($totalFamilias); ?></h3>
                <p>Familias</p>
           </div>
            <div class="icon">
              <i class="icon ion-ios-list-outline"></i>
            </div>
            <?php if(getAccess($familia, ACCESS_ACC)){  ?>
              <a href="familias" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
            <?php }else{ ?>
              <a href="" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
            <?php } ?>  
        </div>
    </div>
                  
    <!-- ./col -->
     <div class="col-lg-2 col-6">
                <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <h3><?php echo number_format($totalClientes); ?></h3>
            <p>Clientes</p>
          </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <?php if(getAccess($cliente, ACCESS_ACC)){  ?>
          <a href="clientes" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
        <?php }else{ ?>
          <a href="" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
        <?php } ?>  
      </div>
    </div>
     <!-- ./col -->

    <div class="col-lg-2 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
             <h3><?php echo number_format($proveedores["totalprov"],0); ?></h3>
                <p>Proveedores</p>
           </div>
            <div class="icon">
              <i class="ion ion-person"></i>
            </div>
            <?php if(getAccess($proveedor, ACCESS_ACC)){  ?>
              <a href="proveedores" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
            <?php }else{ ?>
              <a href="" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
            <?php } ?>  
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <!-- small box -->
        <div class="small-box bg-secondary">
          <div class="inner">
             <h3><?php echo number_format($totalAlmacenes,0); ?></h3>
                <p>Almacenes</p>
           </div>
            <div class="icon">
              <i class="ion ion-cube"></i>
            </div>
            <?php if(getAccess($almacen, ACCESS_ACC)){  ?>
              <a href="crear-almacen" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
            <?php }else{ ?>              
              <a href="" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
            <?php } ?>  
        </div>
    </div>
	
</div>

<!-- **************************************************************************************** -->         
<?php
$item = "fecha_salida";
$valor = $_SESSION['idcaja'];;
$cerrado="0";
$tabla = "hist_salidas";

$ventas = ControladorSalidas::ctrSumaTotalVentas($tabla, $item, $valor, $cerrado, $fechacutvta=null);
$vtaEnv = ControladorSalidas::ctrSumTotVtasEnv($tabla, $item, $valor, $cerrado, $fechacutvta=null);
$vtaServ = ControladorSalidas::ctrSumTotVtasServ($tabla, $item, $valor, $cerrado, $fechacutvta=null);
$prodvta = ControladorSalidas::ctrCantTotalVentas($tabla, $item, $valor);
$totaldeventa=$ventas["sinpromo"]+$ventas["promo"];
?>
<div class="row">

<!-- ===== VENTAS TOTALES EN EL DIA ==============-->
<div class="col-lg-3 col-3">
    <!-- big box -->
    <div class="small-box bg-info">
      <div class="inner">
         <h3>$<?php echo number_format($totaldeventa,2); ?></h3>
            <p>Total de Ventas en el día</p>
       </div>
        <div class="icon">
          <i class="ion ion-beer"></i>
        </div>
        <a href="adminsalidas" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>

<!-- ===== IMPORTES DE ENVASES EN EL DIA ============== -->
 <!-- ./col -->
 <div class="col-lg-3 col-3">
     <!-- big box -->
     <div class="small-box bg-warning">
        <div class="inner">
          <h3>$<?php echo number_format($vtaEnv["total"],2); ?></h3>
            <p>Importe en Envases en el día</p>
        </div>
         <div class="icon">
            <i class="ion ion-social-usd"></i>
         </div>
          <a href="salidas" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
     </div>
</div>

<!-- ===== IMPORTES DE SERVICIOS EN EL DIA ============== -->
 <!-- ./col -->
 <div class="col-lg-3 col-3">
     <!-- big box -->
     <div class="small-box bg-success">
        <div class="inner">
          <h3>$<?php echo number_format($vtaServ["total"],2); ?></h3>
            <p>Ventas de Servicios en el día</p>
        </div>
         <div class="icon">
            <i class="ion ion-ios-albums"></i>
         </div>
          <a href="salidas" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
     </div>
 </div>

 <!-- ./col -->
 <div class="col-lg-3 col-3">
     <!-- big box -->
     <div class="small-box bg-danger">
        <div class="inner">
          <h3><?php echo number_format($prodvta["canttotal"]); ?></h3>
            <p>Productos Vendidos en el día</p>
        </div>
         <div class="icon">
            <i class="ion ion-ios-cart"></i>
         </div>
          <a href="salidas" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
     </div>
</div>
</div>


