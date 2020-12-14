<?php
date_default_timezone_set("America/Mexico_City");
error_reporting(E_ALL^E_NOTICE);
?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1>Administrar Ventas:&nbsp; 
                <small><i class="fa fa-address-book"></i></small>
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

      <!-- Default box -->
      <div class="card">
        
         <div class="card-header">
			  <a href="crear-venta">
			   <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Agregar Venta </button>   
			  </a>

			  <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>          

			  <!--<h2 class="card-title">Control de Usuarios</h2> -->
			  <div class="card-tools">
				<button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Ocultar">
				  <i class="fa fa-minus"></i></button>
				<button type="button" class="btn btn-tool" onclick="regresar()" title="a Inicio">
				  <i class="fa fa-times"></i></button>			  
			  </div>
         </div>
        
        <div class="card-body">
        <div class="card">
            <div class="card-header">
              <h3 class="card-title">Tabla con todas las Ventas</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered compact striped hover dt-responsive activarDatatable" width="100%">
                <thead>
                <tr>
                    <th style="width:9px;">#</th>
                    <th>Codigo Fac.</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th>Forma Pago</th>
                    <th>Neto</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                
                  <tr>
                      <td>1</td>
                      <td>1001251</td>
                      <td>AMET CORDOVA</td>
                      <td>JUAN PEREZ J.</td>
                      <td>TC-1515151</td>
                      <td>$100.00</td>
                      <td>$116.00</td>
                      <td>2019-03-10</td>
                      <td>
                          <div class="btn-group">
                            <button class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-info btn-sm"><i class="fa fa-print"></i></button>
                            <button class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                          </div>
                      </td>
                  </tr>              
                
                  <tr>
                      <td>1</td>
                      <td>1281251</td>
                      <td>R. AMET CORDOVA</td>
                      <td>JUAN PEREZ J.</td>
                      <td>TC-1515151</td>
                      <td>$100.00</td>
                      <td>$116.00</td>
                      <td>2019-03-10</td>
                      <td>
                          <div class="btn-group">
						    <button class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-info btn-sm"><i class="fa fa-print"></i></button>
                            <button class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                          </div>
                      </td>
                  </tr>              

                </tbody>
                <tfoot>
                <tr>
                    <th style="width:9px;">#</th>
                    <th>Codigo Fac.</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th>Forma Pago</th>
                    <th>Neto</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->            
        </div>
        <!-- /.card-body -->
        
        <div class="card-footer">
          Footer
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 <script></script>