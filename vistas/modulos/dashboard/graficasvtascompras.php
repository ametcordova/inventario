<!-- ********************************* GRAFICAS ******************************************************* -->                 
<br>
<?php
$item = "id_almacen";
$valor = 1;         //programar que sea segun el almacen designado
$tabla = "hist_salidas";

$ventas7dias = ControladorSalidas::ctrVtaUlt7Dias($tabla, $item, $valor);
$fechasv='';
$totalv='';

?>
<div class="clearfix"></div>
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
<!--<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script> -->

<script src="extensiones/chart/Chart.min.js"></script>

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
            label: 'Últimas 7 Compras',
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