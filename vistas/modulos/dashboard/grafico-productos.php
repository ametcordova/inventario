<?php
$item = null;
$valor = null;
$orden = "ventas";
$estado=1;

$productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden, $estado);
//var_dump($productos);
$color = array("success","warning","warning","info","primary","secondary","info","dark","muted","danger");
$colores = array("green","gold","yellow","aqua","salmon","blue","purple","magenta","orange","red");

$totalVentas = ControladorProductos::ctrMostrarSumaVentas();

?>
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">            
    <div class="card md">
              <div class="card-header">
                <h3 class="card-title">Productos más vendidos</h3>

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
                      <canvas id="pieChart" height="180"></canvas>
                    </div>
                    
                    <!-- ./chart-responsive -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-5">
                    <ul class="chart-legend clearfix">
                     
		  	 	<?php

					for($i = 0; $i < 10; $i++){

					echo ' <li><i class="fa fa-circle-o text-'.$color[$i].'"></i> '.substr($productos[$i]["descripcion"],0,25).'</li>';

					}


		  	 	?>                     
                    </ul>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer bg-white p-3">
                <ul class="nav nav-pills flex-column">
                    <?php
					for($i = 0; $i < 10; $i++){
          		echo '<li>
						 
						 <a>
						 <img src="'.$productos[$i]["imagen"].'" class="img-thumbnail" width="50px" style="margin-right:10px"> 
						 '.$productos[$i]["descripcion"].'

						 <span class="float-right text-'.$color[$i].'">   
						 '.ceil($productos[$i]["ventas"]*100/$totalVentas["total"]).'%
						 </span>
							
						 </a>

      				</li>';
                        
                    }
                    ?>

                </ul>
              </div>
              <!-- /.footer -->
    </div>
            <!-- /.card -->
</div>
            
<!-- ****************************CHART 2************************************************* -->
<?php
$item = "null";
$valor = null;
$orden = "ventas";
$estado=1;

$productosMenos = ControladorProductos::ctrMostrarProductosMenosVta($item, $valor, $orden,$estado);
//var_dump($productos);

$color = array("success","warning","warning","info","primary","secondary","info","dark","muted","danger");
$colores = array("green","gold","yellow","aqua","salmon","blue","purple","magenta","orange","red");

$totalMenosVentas = ControladorProductos::ctrMostrarSumaVentas();
?>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">            
    <div class="card">
              <div class="card-header">
                <h3 class="card-title">Productos menos vendidos</h3>

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
                      <canvas id="pieChart1" height="180"></canvas>
                    </div>
                    <!-- ./chart-responsive -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-5">
                    <ul class="chart-legend clearfix">
		  	 	<?php

					for($i = 0; $i < 10; $i++){

					echo ' <li><i class="fa fa-circle-o text-'.$color[$i].'"></i> '.substr($productosMenos[$i]["descripcion"],0,25).'</li>';

					}


		  	 	?>                     
				</ul>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer bg-white p-3">
                <ul class="nav nav-pills flex-column">

                    <?php
					for($i = 0; $i < 10; $i++){
          		echo '<li>
						 
						 <a>
						 <img src="'.$productosMenos[$i]["imagen"].'" class="img-thumbnail" width="50px" style="margin-right:10px"> 
						 '.$productosMenos[$i]["descripcion"].'

						 <span class="float-right text-'.$color[$i].'">   
						 '.ceil($productosMenos[$i]["ventas"]*100/$totalMenosVentas["total"]).'%
						 </span>
							
						 </a>

      				</li>';
                        
                    }
                    ?>

                </ul>
              </div>
              <!-- /.footer -->
    </div>
            <!-- /.card -->
</div>

</div>
<!-- *************************************************************************** -->
<!--CORREGIR ENLACE -->
<script src="extensiones/plugins/jquery/jquery.min.js"></script>
<!-- ChartJS 1.0.2 -->
<script src="extensiones/chartjs-old/Chart.min.js"></script>


<script>
//-------------
  //- PIE CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
  var pieChart       = new Chart(pieChartCanvas)
  var PieData        = [
   <?php

  for($i = 0; $i < 10; $i++){

  	echo "{
      value    : ".$productos[$i]["ventas"].",
      color    : '".$colores[$i]."',
      highlight: '".$colores[$i]."',
      label    : '".$productos[$i]["descripcion"]."'
    },";

  }
    
   ?>
  ]
  var pieOptions     = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke    : true,
    //String - The colour of each segment stroke
    segmentStrokeColor   : '#fff',
    //Number - The width of each segment stroke
    segmentStrokeWidth   : 1,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 50, // This is 0 for Pie charts
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
    tooltipTemplate      : '<%=value %> <%=label%>'
  }
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  pieChart.Doughnut(PieData, pieOptions)
  //-----------------
  //- END PIE CHART -
  //-----------------    
</script>

<!-- ***************************** CHART 2*************************************************************** -->
<script>
//-------------
  //- PIE CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var pieChartCanvas = $('#pieChart1').get(0).getContext('2d')
  var pieChart       = new Chart(pieChartCanvas)
  var PieData        = [
   <?php

  for($i = 0; $i < 10; $i++){

  	echo "{
      value    : ".$productosMenos[$i]["ventas"].",
      color    : '".$colores[$i]."',
      highlight: '".$colores[$i]."',
      label    : '".$productosMenos[$i]["descripcion"]."'
    },";

  }
    
   ?>
  ]
  var pieOptions     = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke    : true,
    //String - The colour of each segment stroke
    segmentStrokeColor   : '#fff',
    //Number - The width of each segment stroke
    segmentStrokeWidth   : 1,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 50, // This is 0 for Pie charts
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
    tooltipTemplate      : '<%=value %> <%=label%>'
  }
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  pieChart.Doughnut(PieData, pieOptions)
  //-----------------
  //- END PIE CHART -
  //-----------------    
</script>
