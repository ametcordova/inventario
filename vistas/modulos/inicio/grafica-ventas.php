<?php

error_reporting(0);     //EVITA QUE APAREZCA EL ERROR

if(isset($_GET["fechaInicial"])){
echo "entra1".'<br>';
    $fechaInicial = $_GET["fechaInicial"];
    $fechaFinal = $_GET["fechaFinal"];
    require_once "../../../controladores/salidas.controlador.php";
    require_once "../../../modelos/salidas.modelo.php";    

}else{
    echo "entra2";
    $fechaInicial = "2019-03-01";
    $fechaFinal = "2019-05-31";

}

$arrayFechas = array();
$arrayVentas = array();
$sumaPagosMes = array();
echo "entra3";
$respuesta = ControladorSalidas::ctrRangoFechasVentas($fechaInicial, $fechaFinal);
echo "entra4".'<br>';
//var_dump($respuesta);
foreach ($respuesta as $key => $value) {
//var_dump($value["fecha_salida"]);
    
 #Capturamos sólo el año y el mes
 $fecha = substr($value["fecha_salida"],0,7);

#Introducir las fechas en arrayFechas
array_push($arrayFechas, $fecha);

#Capturamos las ventas
$arrayVentas = array($fecha => $value["totalvta"]);

#Sumamos los pagos que ocurrieron el mismo mes
foreach ($arrayVentas as $key => $value) {
	$sumaPagosMes[$key] += $value;
}

}
//var_dump($sumaPagosMes);    
echo "</br>";

$noRepetirFechas = array_unique($arrayFechas);
//var_dump($noRepetirFechas);    
?>

 <div class="card-body">
    <div class="chart" id="line-chart" style="height: 250px;"></div>
 </div>

<!--CON LAS VERSIONES INSTALADAS -->

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.0/morris.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.2.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.0/morris.min.js"></script>


<!--
<link rel="stylesheet" href="vistas/modulos/plugins/morris.js/morris.css">
<script src="vistas/modulos/plugins/jquery/jquery.min.js"></script>
<script src="vistas/modulos/plugins/raphael/raphael.min.js"></script>
<script src="vistas/modulos/plugins/morris.js/morris.js"></script>
-->
<!--
<link rel="stylesheet" href="../plugins/morris.js/morris.css">
<script src="../plugins/raphael/raphael.min.js"></script>
<script src="../plugins/morris.js/morris.js"></script>
-->
<script>

var line = new Morris.Line({
    element          : 'line-chart',
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
