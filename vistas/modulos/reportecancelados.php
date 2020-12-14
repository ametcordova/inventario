<style>
	.select2-results__options{
        font-size:14px !important;
	}	
</style>
<?php
    $fechaHoy=date("Y-m-d");
    $tabla="usuarios";
    $module="rcancela";
    $campo="reportes";
    $acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);
    
?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-0 ml-2 p-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h3>Rep. de Vtas. Canceladas</h3>
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
          <button type="button" class="btn btn-default btn-sm ml-3 mr-2 " id="daterange-btn-cancela" value="">
           <span>
            <i class="fa fa-calendar"></i> Rango de fecha
           </span>
              <i class="fa fa-caret-down"></i>                     
          </button>

          <?php if(getAccess($acceso, ACCESS_VIEW)){?>
            <button class="btn btn-success btn-sm" onclick="dataTableCancela()">
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
              <table class="table table-bordered compact table-hover table-striped dt-responsive " id="datatablecancela" width="100%">
                <thead class="thead-dark">
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:5%;">id clie</th>
                    <th style="width:25%;">Cliente</th>
                    <th># Ticket</th>
                    <th>Fecha Vta</th>
                    <th>cant</th>
                    <th>Total $</th>
                    <th>Fecha Cancelado</th>
                    <th style="width:5%;">Acción</th>
                </tr>
                </thead>
                <tbody>
                                
                </tbody>
                <tfoot class="thead-dark">
                <tr>
                  <th style="width:5%;">#</th>
                  <th style="width:5%;">id clie</th>
                  <th style="width:25%;">Cliente</th>
                  <th># Ticket</th>
                  <th>Fecha Vta</th>
                  <th>cant</th>
                  <th>Total $</th>
                  <th>Fecha Cancelado</th>
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
<script defer src="vistas/js/reportecancelados.js"></script>
