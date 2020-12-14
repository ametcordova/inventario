<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1>Tablero
            </h1>
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

    <?php
        
        include "dashboard/cajas-superiores.php";
        
    ?>
<!--
    <div class="form-group col-md-4 m-1">
      <div class="input-group input-group-sm">
        <button type="button" class="btn btn-default float-right" id="daterange-btn2">
            <span><i class="fa fa-calendar"></i> Rango de fecha</span><i class="fa fa-caret-down"></i>
        </button>
      </div>
    </div> 
-->


    <?php
        include "dashboard/grafica.php";
        include "dashboard/graficasvtascompras.php";
        include "dashboard/grafico-productos.php";
        include "dashboard/grafico-vtas-categorias.php";        
    ?>
   
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->