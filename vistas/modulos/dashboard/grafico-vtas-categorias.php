<?php
date_default_timezone_set('America/Mexico_City');

$fecha_actual = date("Y-m-d");
//echo $fecha_actual;
//echo "</br>";

if(isset($_GET["fechaIniMes"])){
  //echo "entra1".'<br>';
      $fechaInimes = $_GET["fechaIniMes"];
      $fechaFinmes = $_GET["fechaFinMes"];
  
  }else{
      // si no trae fecha
      $fechaInimes=date("Y-m-d",strtotime($fecha_actual."- 30 days"));
      $fechaFinmes=date("Y-m-d",strtotime($fecha_actual));   
  }
  

$color = array("success","warning","info","primary","secondary","info","dark","muted","warning","danger");
$colores = array("green","yellow","aqua","salmon","blue","purple","magenta","orange","gold","red");


$tablaVtaFam="hist_salidas";
$id_almacen=null;
$limite=10;
$totalVentasxFamilia = ControladorVentas::ctrMostrarTotVentasFam($tablaVtaFam, $fechaInimes, $fechaFinmes, $limite);
//TOTAL DE VENTAS EN EL RANGO SELECCIONADO DE TODAS LAS FAMILIAS
$totalVentas=array_sum(array_column($totalVentasxFamilia, 'venta'));  //saca la suma en una columna de una array aunque sea string
//var_dump($totalVentasxFamilia);
//echo "</br>";
//echo "sum(a) = " . array_sum(array_column($totalVentasxFamilia, 'venta'));
$totitems=count($totalVentasxFamilia)>$limite?$limite:count($totalVentasxFamilia);

?>
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">            
    <div class="card">
              <div class="card-header bg-info">

              <div class="form-group col-md-8 m-1">
                <div class="input-group input-group-sm">
                  <button type="button" class="btn btn-default float-right" id="daterange-btn-grafico">
                      <span><i class="fa fa-calendar"></i> Rango de fecha</span><i class="fa fa-caret-down"></i>
                  </button>

                  <div class="float-right">
                    <h4>&nbsp&nbsp&nbsp&nbsp Gráfico de Ventas por Familias.</h4>  
                  </div>
                  
                </div>
              </div> 


                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-times"></i>
                  </button>
                </div>


              </div>


              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">

                 <div class="col-md-7">
                   
                    <div class="chart-responsive">
                      <canvas id="myChart" height="340"></canvas>
                    </div>
                    
                    <!-- ./chart-responsive -->
                  </div>
                  <!-- /.col -->
                    <div class="col-md-5">
                      <ul class="chart-legend clearfix">
                      
                        <ul class="nav nav-pills flex-column">
                          <?php
                          for($i = 0; $i < $totitems; $i++){
                              echo '<li class="p-1"> <i class="fa fa-circle-o text-'.$color[$i].'"></i>
                                '.$totalVentasxFamilia[$i]["familia"].'
                                <span class="float-right text-'.$color[$i].'">$  
                                '.number_format($totalVentasxFamilia[$i]["venta"],2).' =
                                '.number_format($totalVentasxFamilia[$i]["venta"]*100/$totalVentas,2).'%
                                </span>
                              </li>';
                          }
                          ?>
                      </ul>                      
                    </ul>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-body -->
              <div class="clearfix"></div>

     </div>
            <!-- /.card -->
</div>
<div class="clearfix"></div>

<!--CORREGIR ENLACE -->
<script src="extensiones/plugins/jquery/jquery.min.js"></script>
<!-- ChartJS 1.0.2 -->
<script src="extensiones/chartjs-old/Chart.min.js"></script>


<script>
//-------------
  //- PIE CHART -
  //-------------

  var color = ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'];
  var bordercolor = ['rgba(255,99,132,1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'];
  
  // Get context with jQuery - using jQuery's .get() method.
  var pieChartCanvas = $('#myChart').get(0).getContext('2d')
  var pieChart       = new Chart(pieChartCanvas)
  var PieData        = [
   <?php

  for($i = 0; $i < $totitems; $i++){

  	echo "{
      value    : ".$totalVentasxFamilia[$i]["venta"].",
      color    : '".$colores[$i]."',
      highlight: '".$colores[$i]."',
      label    : '".$totalVentasxFamilia[$i]["familia"]."'
    },";

  }
    
   ?>
  ]

  
  var pieOptions     = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke    : true,
    //String - The colour of each segment stroke
    segmentStrokeColor   : '#fff',
    //Number - The width of each segment stroke Separacion entre cada segmento
    segmentStrokeWidth   : 2,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 0, // This is 0 for Pie charts
    //Number - Amount of animation steps
    animationSteps       : 100,
    //String - Animation easing effect
    animationEasing      : 'easeOutBounce',
    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate        : true,
    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale         : false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive           : true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio  : false,

    //String - A legend template
    legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
    //String - A tooltip template
    tooltipTemplate      : '<%=label %> <%=number_format(value,2) %>'
  }
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  pieChart.Doughnut(PieData, pieOptions)
  //-----------------
  //- END PIE CHART -
  //-----------------    
  
</script>

