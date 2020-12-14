<?php

error_reporting(0);     //EVITA QUE APAREZCA EL ERROR
$fechadeHoy=date("Y-m-d");          // fecha de hoy
$fechadeAntes = date('Y-m-d', strtotime('-1 year')) ; // fecha de hoy menos 1 año
//echo $fechadeHoy.'<br>';
//echo $fechadeAntes.'<br>';

if(isset($_GET["fechaInicial"])){
//echo "entra1".'<br>';
    $fechaInicial = $_GET["fechaInicial"];
    $fechaFinal = $_GET["fechaFinal"];

}else{
    // si no trae fecha
    $fechaInicial = $fechadeAntes;
    $fechaFinal = $fechadeHoy;

}

$arrayFechas = array();
$arrayVentas = array();
$sumaPagosMes = array();

$respuesta = ControladorSalidas::ctrRangoFechasVentas($fechaInicial, $fechaFinal);

//var_dump($fechaInicial,$fechaFinal,$respuesta);
foreach ($respuesta as $key => $value) {
//var_dump($value["fecha_salida"]);
    
 #Capturamos sólo el año y el mes
 $fecha = substr($value["fecha_salida"],0,7);

#Introducir las fechas en arrayFechas
array_push($arrayFechas, $fecha);

#Capturamos las ventas
//$arrayVentas = array($fecha => $value["totalvta"]);
$arrayVentas = array($fecha => ($value["promo"]+$value["sinpromo"]));
//echo "</br>";
//var_dump($arrayVentas);    
#Sumamos los pagos que ocurrieron el mismo mes
foreach ($arrayVentas as $key => $value) {
	$sumaPagosMes[$key] += $value;
}

}
//var_dump($sumaPagosMes);    
//echo "</br>";

$noRepetirFechas = array_unique($arrayFechas);
//var_dump($noRepetirFechas);    
?>
<div class="row col-md-12">

   <div class="form-group col-md-4 m-1">
      <div class="input-group input-group-sm">
        <button type="button" class="btn btn-default float-right" id="daterange-btn2">
            <span><i class="fa fa-calendar"></i> Rango de fecha</span><i class="fa fa-caret-down"></i>
        </button>
      </div>
    </div> 

    <div class="card bg-info-gradient col-md-12">
      <div class="card-header no-border">
         <h3 class="card-title"><i class="fa fa-th mr-1"></i>
            Gráfico de Ventas
         </h3>

         <div class="card-tools ">
            <button type="button" class="btn bg-info btn-sm" data-toggle="collapse" data-widget="collapse"  id="boxcolapsado"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn bg-info btn-sm" data-widget="remove"><i class="fa fa-times"></i>
            </button>
         </div>
       </div>
          <div class="card-body nuevoGraficoVentas">
            <div class="chart" id="line-chart-ventas" style="height: 250px;"></div>
          </div>
    </div>

</div>




<!--CON LAS VERSIONES INSTALADAS -->
<!--
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.0/morris.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.2.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.0/morris.min.js"></script>
-->


<script src="extensiones/plugins/jquery/jquery.min.js"></script>
<link rel="stylesheet" href="extensiones/morris.js/morris.css">
<script src="extensiones/raphael/raphael.min.js"></script>
<script src="extensiones/morris.js/morris.js"></script>


<script>
var line = new Morris.Line({
    element          : 'line-chart-ventas',
    resize           : true,
    data             : [
        
    <?php

    if($noRepetirFechas != null){

	    foreach($noRepetirFechas as $key){

	    	echo "{ y: '".$key."', ventas: ".$sumaPagosMes[$key]." },";


	    }

	    echo "{y: '".$key."', ventas: ".$sumaPagosMes[$key]." }";

    }else{

       echo "{ y: '0', ventas: '0' }";

    }

    ?>        
    ],
    xkey             : 'y',
    ykeys            : ['ventas'],
    labels           : ['ventas'],
    lineColors       : ['#efefef'],
    lineWidth        : 2,
    hideHover        : 'auto',
    gridTextColor    : '#fff',
    gridStrokeWidth  : 0.4,
    pointSize        : 4,
    pointStrokeColors: ['#efefef'],
    gridLineColor    : '#efefef',
    gridTextFamily   : 'Open Sans',
    preUnits         : '$',
    gridTextSize     : 10
  })
</script>
