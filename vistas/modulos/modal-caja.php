<?php

 //require "controladores/control-presupuesto.controlador.php";
 //require "modelos/control-presupuesto.modelo.php";
 
 $fechadeHoy=date("d/m/Y");
?>
<!---********************* MODAL DE CAJA *****************************  -->
<div class="modal fade" id="cajaAbierta" role="dialog">

  <?php
  //$IngCaja=null;
  $item = "fecha_salida";
  $valor = $fechadeHoy;
  $cerrado=0;
  $tabla = "hist_salidas";

  //$ventas = ControladorSalidas::ctrSumaTotalVentas($tabla, $item, $valor, $cerrado);
  //$totalesdeventa=$ventas["sinpromo"]+$ventas["promo"];
  //$vtaEnv = ControladorSalidas::ctrSumTotVtasEnv($tabla, $item, $valor, $cerrado);
  //$vtaServ = ControladorSalidas::ctrSumTotVtasServ($tabla, $item, $valor, $cerrado);
  //$vtaCred = ControladorSalidas::ctrSumTotVtasCred($tabla, $item, $valor, $cerrado);
  //$totventascred=$vtaCred["sinpromo"]+$vtaCred["promo"];
  

  //$granTotal=($totalesdeventa+$vtaEnv["total"]+$vtaServ["total"]);


?>  
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header colorbackModal py-2 m-0">
		<h4><?= $fechadeHoy ?></h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">

		<div class="col-md-12 mr-2">
            <!-- Widget: user widget style 2 -->
            <div class="card card-widget widget-user-2">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-warning mb-2 p-2">
                <div class="widget-user-image">
                  <img class="img-circle elevation-2" src="<?php echo $_SESSION['foto']?>" alt="User Avatar">
                </div>
                <!-- /.widget-user-image -->
                  <div class="row">
                    <div class="col-md-9" >
                        <h3 class="widget-user-username m-0"><?php echo $_SESSION['nombre'];?></h3>
                        <h5 class="widget-user-desc m-0 "><?php echo $_SESSION['perfil'];?></h5>
                    </div>
                    <div class="col-md-3">
                        <h3 class="text-center">Caja No. <?= $_SESSION['caja'];?></h3>  
                    </div>
                  </div>
              </div>
			  
              <div class="card-footer p-0" id="databox">
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a href="reportedeventas" class="nav-link">
                      <h5>+ Ventas <span class="float-right badge bg-primary idforsuma" data-ventas="" id="totaldeventas"></span></h5>
                    </a>
                  </li>
				  
                  <li class="nav-item">
                    <a href="reportedeventas" class="nav-link text-info">
                      <h5>+ Envases <span class="float-right badge bg-info idforsuma" data-envases="" id="totalenvases"></span></h5>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="reportedeventas" class="nav-link text-warning">
                      <h5>+ Servicios <span class="float-right badge bg-warning idforsuma" data-servicios="" id="totalservicios"></span></h5>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="reportedeventas" class="nav-link text-body">
                      <h5>* Ventas a Crédito <span class="float-right badge bg-secondary text-white" id="totalacredito"></span></h5>
                    </a>
                  </li>

                  <?php
                   if(isset($_SESSION["abierta"])){ ?>
                  <li class="nav-item ">
                    <a href="control-presupuesto" class="nav-link text-success" >
                      <h5>+ Ingreso <span class="float-right badge bg-success idforsuma" data-ingreso="" id="totalingreso"></span></h5>
                    </a>
                  </li>
                  <li class="nav-item ">
                    <a href="control-presupuesto" class="nav-link text-danger">
                      <h5>- Egreso <span class="float-right badge bg-danger idforsuma" data-egreso="" id="totalegreso"></span></h5>
                    </a>
                  </li>
                  <?php } ?>

				          <li class="nav-item text-dark">
                    <a href="#" class="nav-link text-dark">
                      <h4>Total Efectivo: <span class="float-right badge bg-dark" id="totalefectivo"></span></h4>
                    </a>
                  </li>

                </ul>
              </div>  <!-- //fin del card-footer -->
            </div>
            <!-- /.widget-user -->
        </div>
          <!-- /.col -->		

        </div>
		
        <div class="modal-footer py-2 m-0">
        <?php
         if(isset($_SESSION["abierta"])){ ?>
              <button type="button" class="btn btn-dark mr-auto" id="cerrarcaja">Cerrar Caja</button>

              <a href="control-presupuesto">
                <button type="button" class="btn btn-success mr-auto"  id="ingreso">Ingreso</button>
              </a>
              <a href="control-presupuesto">
                <button type="button" class="btn btn-danger mr-auto" id="egreso">Egreso</button>
              </a>
              <a href="control-presupuesto">
                <button type="button" class="btn btn-info mr-auto" id="vermov">Ver Movtos</button>
              </a>

              <button type="button" class="btn btn-primary mr-auto" id="imprimirCorteX" title="Imprimir en Ticket">Imprimir</button>

          <?php }else{ ?>
               <button type="button" class="btn btn-dark mr-auto" id="abrircaja">Abrir Caja</button>
          <?php } ?>
          
          <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-reply"></i>Salir</button>
        </div>
      </div>
    </div>
</div>
<!---**************** FIN MODAL DE CAJA **********  Corte de Caja X y Z sist de Punto de venta -->  

<!-- /* ======================= VER MOVTOS DE CAJA ========================== */ -->
<div id="aviso24" class="modal fade" tabindex="-1" data-focus-on="input:first" role="dialog">
 <div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content">
    <div class="modal-header bg-warning py-1 m-0">
      <h3>AVISO!!</h3>
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
		<div class="modal-body text-center">
            <p>POR FAVOR REALICE CIERRE DE CAJA.</p>
            <p>De lo contrario sus ventas entraran para</p>
            <p>el siguiente día y no podrá hacer cierre de caja.</p>
		</div>
		
     <div class="modal-footer py-2 m-0">
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-warning">Entendido</button>
     </div>
	
  </div>
 </div>
</div>

<script defer src="vistas/js/cajas.js"></script>
<script defer src="vistas/js/header.js"></script>
<!-- /* ======================= FIN DE MOVTOS DE EFECTIVO DE CAJA ========================== */ -->