<style>
	.select2-results__options{
        font-size:14px !important;
	}	
</style>
<?php
$tabla="usuarios";
$module="rcortes";
$campo="reportes";
$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);
$fechaHoy=date("Y-m-d");
?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-0 ml-2 p-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h3>Cortes de Ventas</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
              <li class="breadcrumb-item active">Tablero</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        
        <div class="card-header">
          <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>

          <!-- Date range -->
          <button type="button" class="btn btn-default btn-sm ml-3 mr-2 " id="daterange-btn-cortes" value="01/01/2018 - 01/15/2018">
           <span>
            <i class="fa fa-calendar"></i> Rango de fecha
           </span>
              <i class="fa fa-caret-down"></i>                     
          </button>

          <?php if(getAccess($acceso, ACCESS_VIEW)){?>
            <button class="btn btn-success btn-sm" onclick="dataTableCortes()">
            <i class="fa fa-eye"></i>  Mostrar
            </button>
          <?php } ?> 

          <!--<h2 class="card-title">Control de Usuarios</h2> -->
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>

         <div class="row">
         
         </div>


        <div class="card-body">
      <div class="card">
            <div class="card-body">
              <table class="table table-bordered compact table-hover table-striped dt-responsive " id="datatablecortes" width="100%">
                <thead class="thead-dark">
                <tr>
                    <th style="width:3%;">#</th>
                    <th style="width:3%;">Caja</th>
                    <th>Fecha</th>
                    <th>Ventas</th>
                    <th>Promo</th>
                    <th>Envases</th>
                    <th>Servicios</th>
                    <th>Otros</th>
                    <th>A crédito</th>
                    <th>Total Vta</th>
                    <th>Ingreso</th>
                    <th>Egreso</th>
                    <th style="width:5%;">Acción</th>
                </tr>
                </thead>
                <tbody>
                                
                </tbody>
                <tfoot class="thead-dark">
                <tr>
                <th style="width:3%;">#</th>
                <th style="width:3%;">Caja</th>
                <th>Fecha</th>
                <th>Ventas</th>
                <th>Promo</th>
                <th>Envases</th>
                <th>Servicios</th>
                <th>Otros</th>
                <th>A crédito</th>
                <th>Total Vta</th>
                <th>Ingreso</th>
                <th>Egreso</th>
                <th style="width:5%;">Acción</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->            
        </div>
        <!-- /.card-body -->
        
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<!-- ===============================  =========================================================== -->
<div class="modal fade" id="cortedecaja" role="dialog">

<!-- <div class="modal-dialog modal-lg"> -->
<div class="modal-dialog modal-dialog-centered">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header alert alert-primary py-2 m-0">
		        <h4 data-fechacorte="" id="fechadeventa"></h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">

		<div class="col-md-12 mr-2">
            <!-- Widget: user widget style 2 -->
            <div class="card card-widget widget-user-2">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-warning mb-2 p-2">
                <div class="widget-user-image">
                  <!-- <img class="img-circle elevation-4" src="<?php echo $_SESSION['foto']?>" alt="User Avatar"> -->
                </div>
                <!-- /.widget-user-image -->
                  <div class="row">
                    <div class="col-md-12">
                        <h5 class="text-center" id="boxpurchase"></h5>  
                    </div>
                  </div>
              </div>
			  
              <div class="card-footer p-0" id="databox">
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a href="#" class="nav-link py-0 px-3 pt-1">
                      <h5>+ Ventas <span class="float-right badge bg-primary idforsuma" id="totaldevta"></span></h5>
                    </a>
                  </li>
				  
                  <li class="nav-item">
                    <a href="#" class="nav-link text-info  py-0 px-3 pt-1">
                      <h5>+ Envases <span class="float-right badge bg-info idforsuma" id="totaldeenv"></span></h5>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="#" class="nav-link text-warning  py-0 px-3 pt-1">
                      <h5>+ Servicios <span class="float-right badge bg-warning idforsuma" id="totaldeserv"></span></h5>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="#" class="nav-link py-0 px-3 pt-1" style="color:coral;">
                      <h5>+ Otros <span class="float-right badge text-white idforsuma" style="background-color:coral;" id="totaldeotros"></span></h5>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="#" class="nav-link text-body  py-0 px-3 pt-1">
                      <h5>* Ventas a Crédito <span class="float-right badge bg-secondary text-white" id="totaldecred"></span></h5>
                    </a>
                  </li>

                  <li class="nav-item ">
                    <a href="#" class="nav-link text-success  py-0 px-3 pt-1" >
                      <h5>+ Ingreso <span class="float-right badge bg-success idforsuma" id="totaldeing"></span></h5>
                    </a>
                  </li>
                  <li class="nav-item ">
                    <a href="#" class="nav-link text-danger  py-0 px-3 pt-1">
                      <h5>- Egreso <span class="float-right badge bg-danger idforsuma" id="totaldeegr"></span></h5>
                    </a>
                  </li>

				          <li class="nav-item text-dark">
                    <a href="#" class="nav-link text-dark  py-0 px-3 pt-1">
                      <h4>Total Efectivo: <span class="float-right badge bg-dark" id="totaldeefec"></span></h4>
                    </a>
                  </li>

				          <li class="nav-item">
                    <a href="#" class="nav-link py-0 py-0 px-3 pt-1">
                      <h5 style="color:#DF01A5;"><i class="fa fa-cube" aria-hidden="true"></i> Caja Chica:<span class="float-right badge" style="color: white; background-color:#DF01A5;" id="totaldecajachica"></span></h5>
                    </a>
                  </li>

                </ul>
              </div>  <!-- //fin del card-footer -->
            </div>
            <!-- /.widget-user -->
        </div>
          <!-- /.col -->		

        </div>
		
        <div class="modal-footer alert alert-primary py-2 m-0">
          <button type="button" class="btn btn-primary mr-auto" id="printCutVta" title="Imprimir en Ticket">Imprimir</button>
          <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-reply"></i>Salir</button>
        </div>
      </div>
    </div>
</div>
<!---**************** FIN MODAL DE CAJA **********  Corte de Caja X y Z sist de Punto de venta -->    
 
<script defer src="vistas/js/cortesdeventas.js?v=280420"></script>
