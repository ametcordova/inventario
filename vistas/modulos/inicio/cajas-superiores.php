<?php
error_reporting(0);     //EVITA QUE APAREZCA EL ERROR

$fechadeHoy=date("d/m/Y");
echo "entra1".'<br>';
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
              <a href="productos" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
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
              <a href="categorias" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
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
            <a href="familias" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
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
        <a href="clientes" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
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
            <a href="proveedores" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
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
            <a href="crear-almacen" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<!-- **************************************************************************************** -->         
<?php
$item = "fecha_salida";
$valor = $fechadeHoy;
$tabla = "hist_salidas";

$ventas = ControladorSalidas::ctrSumaTotalVentas($tabla, $item, $valor);
$vtaEnv = ControladorSalidas::ctrSumTotVtasEnv($tabla, $item, $valor);
$prodvta = ControladorSalidas::ctrCantTotalVentas($tabla, $item, $valor);
?>
<div class="row">
<div class="col-lg-4 col-4">
    <!-- big box -->
    <div class="small-box bg-info">
      <div class="inner">
         <h3>$<?php echo number_format($ventas["total"],2); ?></h3>
            <p>Total de Ventas en el día</p>
       </div>
        <div class="icon">
          <i class="ion ion-social-usd"></i>
        </div>
        <a href="adminsalidas" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>

 <!-- ./col -->
 <div class="col-lg-4 col-4">
     <!-- big box -->
     <div class="small-box bg-danger">
        <div class="inner">
          <h3>$<?php echo number_format($vtaEnv["total"],2); ?></h3>
            <p>Importe en Envases</p>
        </div>
         <div class="icon">
            <i class="ion ion-beer"></i>
         </div>
          <a href="salidas" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
     </div>
</div>

 <!-- ./col -->
 <div class="col-lg-4 col-4">
     <!-- big box -->
     <div class="small-box bg-success">
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
<!-- **************************************************************************************** -->                 
<!--<div class="row"> -->
<?php


?>
   
    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
        <!-- Date and time range -->
        <div class="form-group col-md-4 m-1">
            <div class="input-group input-group-sm">
                <button type="button" class="btn btn-default float-right" id="daterange-btn2">
                     <span>
                      <i class="fa fa-calendar"></i> Rango de fecha
                     </span>
                        <i class="fa fa-caret-down"></i>                     
                </button>
            </div>
        </div>           <!-- /.form group -->
        
            <!-- solid sales graph -->
        <div class="card bg-info-gradient">
              <div class="card-header no-border">
                <h3 class="card-title">
                  <i class="fa fa-th mr-1"></i>
                  Gráfico de Ventas
                </h3>

                <div class="card-tools ">
                  <button type="button" class="btn bg-info btn-sm" data-toggle="collapse" data-widget="collapse" id="boxcolapsado">

                    <i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn bg-info btn-sm" data-widget="remove">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              
        <?php
        
         include "grafica-ventas.php";
         
            
        ?>
</div>
<!-- </div>-->
<!-- **************************************************************************************** -->                 
<br>
<?php
$item = "id_almacen";
$valor = 1;         //programar que sea segun el almacen designado
$tabla = "hist_salidas";

$ventas7dias = ControladorSalidas::ctrVtaUlt7Dias($tabla, $item, $valor);
$fechasv='';
$totalv='';

?>
<div class="row">

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <div class="card">
        <?php
        $ventas7dias = array_reverse($ventas7dias);     //cambiar el orden del array
        foreach($ventas7dias as $key => $value){
            //echo $value["fecha_salida"]." - ".$value["totalvta"];
            //echo "</br>";
            $fechasv=$fechasv.'"'.$value["fecha_salida"].'",';
            $totalv=$totalv.$value["totalvta"].',';
            //echo $totalv;
            //echo "</br>";
        }
            $fechasv=substr($fechasv,0,-1);
            $totalv=substr($totalv,0,-1);
        ?>
       
        <canvas id="ChartVentas" width="400" height="300"></canvas>
    </div>
</div>   

 <!-- **************************************************************************************** -->                 
<?php
$item = "id_almacen";
$valor = 1;
$tabla = "hist_entrada";

$compras7dias = ControladorSalidas::ctrComprasUlt7Dias($tabla, $item, $valor);
$comprasC='';
$totalc='';

?>
 
 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
     <div class="card">  
	 <?php
       $compras7dias = array_reverse($compras7dias);     //cambiar el orden del array
        foreach($compras7dias as $key => $value){
           //echo $value["fecha_entrada"]." - ".$value["totalcompra"];
           //echo "</br>";

            $comprasC=$comprasC.'"'.$value["fechaentrada"].'",';
            $totalc=$totalc.$value["totalcompra"].',';
        }
            $comprasC=substr($comprasC,0,-1);
            $totalc=substr($totalc,0,-1);
        ?>
       
        <canvas id="ChartCompras" width="400" height="300"></canvas>

    </div>
 </div>   
 
</div>

<!-- **************************************************************************************** -->                 

<!-- **************************************************************************************** -->                 
<!--CORREGIR ENLACE -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>

<script>

var ctx = document.getElementById('ChartVentas').getContext('2d');
var ChartVentas = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo $fechasv; ?>],
        datasets: [{
            label: 'Ventas Últimos 7 Días',
            data: [<?php echo $totalv; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(208, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(225, 158, 64, 0.2)',
                'rgba(255, 198, 192, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(208, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(225, 158, 64, 1)',
                'rgba(255, 198, 192, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>

<script>

var ctx = document.getElementById('ChartCompras').getContext('2d');
var ChartVentas12 = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo $comprasC; ?>],
        datasets: [{
            label: 'Compras Últimos 7 Días',
            data: [<?php echo $totalc; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(208, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(225, 158, 64, 0.2)',
                'rgba(255, 198, 192, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(208, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(225, 158, 64, 1)',
                'rgba(255, 198, 192, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>

