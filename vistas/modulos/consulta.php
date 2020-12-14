  <?php
  require "controladores/control-presupuesto.controlador.php";
  require "modelos/control-presupuesto.modelo.php";
  
  $fechadeHoy=date("d/m/Y");
  //$IngCaja=null;
  $item = "fecha_salida";
  $valor = $fechadeHoy;
  $cerrado=0;
  $tabla = "hist_salidas";

  $ventas = ControladorSalidas::ctrSumaTotalVentas($tabla, $item, $valor,$cerrado);
  $totalesdeventa=$ventas["sinpromo"]+$ventas["promo"];
  $vtaEnv = ControladorSalidas::ctrSumTotVtasEnv($tabla, $item, $valor,$cerrado);
  $vtaServ = ControladorSalidas::ctrSumTotVtasServ($tabla, $item, $valor,$cerrado);

  
  $tabla = "cortes";
  $item = "id_caja";
  $idDeCaja=$_SESSION['idcaja'];
  //$ImporteEnCaja = ControladorCajas::ctrVerIngresoCaja($tabla, $item, $idDeCaja, $fechadeHoy);

  $ImporteEnCaja = ControladorPresupuesto::ctrVerPresupuesto();

  $granTotal=($totalesdeventa+$vtaEnv["total"]+$vtaServ["total"]+$ImporteEnCaja["monto_ingreso"]-$ImporteEnCaja["monto_egreso"]);


?>  