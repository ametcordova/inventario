<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">    <!-- Content Header (Page header) -->

    <section class="content-header m-0 p-1">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h5>Gestión de Respaldos&nbsp; 
                <small><i class="fa fa-server"></i></small>
            </h5>
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
          <!--<h2 class="card-title">Control de Usuarios</h2> -->
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
         </div>
        
        <div class="card-body">

         <div class="card">

         <div class="card-header">

         <button class="btn btn-warning d-none" id="botondeeliminar"><i class="fa fa-trash"></i> Eliminar Seleccionados</button>

         <div class="alert alert-success alert-dismissible fade show col-md-5 float-right m-1 d-none" role="alert" id="msj-success">
            Archivo(s) eliminado correctamente!!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>           
         </div>

            <div class="card-body">
              <table class="table table-bordered compact striped hover dt-responsive" id="TablaRespaldos" width="100%">
                <thead class="thead-dark">
                <tr>
                    <th style="width:25px;">#</th>
                    <th style="width:25px;"><input type="checkbox" id="checkTodos"></th>
                    <th>Nombre respaldo</th>
                    <th style="width:150px;">Fecha respaldo</th>
                    <th style="width:120px;">Peso respaldo</th>
                    <th style="width:2em;">Acción</th>
                </tr>
                </thead>
                <tbody id="tablebackup">

                                
                </tbody>
                <tfoot class="thead-dark">
                <tr>
                    <th style="width:25px;">#</th>
                    <th></th>
                    <th>Nombre respaldo</th>
                    <th style="width:150px;">Fecha respaldo</th>
                    <th>Peso respaldo</th>
                    <th>Acción</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->            
        </div>   <!-- /.card-body -->
        
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script defer src="vistas/js/gestionrespaldos.js"></script>