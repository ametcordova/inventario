<?php
//Activamos el almacenamiento en el buffer
ob_start();
?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h3>Almacen</h3>
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
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-tool" onclick="regresar()" title="a Inicio">
              <i class="fa fa-times"></i></button>			  
          </div>
           
          <div class="row">
           
            <div class="form-group col-md-4">
                <select class="form-control form-control-sm" name="SelAlmacen" id="SelAlmacen" tabindex="1" required>
						<option value="" selected>Seleccione Almacen</option>
                 <?php
                    $item=null;
                    $valor=null;
                    $almacenes=ControladorAlmacenes::ctrMostrarAlmacenes($item, $valor);
                    foreach($almacenes as $key=>$value){
                        echo '<option value="'.$value["nombre"].'">'.$value["nombre"].'</option>';
                    }
                  ?>								
				 </select>
            </div>
            <div><button class="btn btn-success btn-sm" onclick="listar()">Mostrar</button></div>
          </div>  
            
               
        </div>
        <div class="card-body">
          
      <table id="tbllistado" class="table compact table-bordered table-striped table-hover dt-responsive" width="100%">
            <thead class="table-warning text-center" style="font-size:.8em">
                <th style="width:10px;">#</th>
                <th style="width:10px;">id_prod</th>
                <th style="width:110px;">Código</th>
                <th style="width:410px;">Descripcion</th>
                <th style="width:50px;">U.Med.</th>
                <th style="width:90px;" >Exist</th>
                <th style="width:90px;">Minimo</th>
                <th style="width:80px;">$ Compra</th>
                <th style="width:130px;">F. Entrada</th>
            </thead>
            <tbody class="text-center">                            
            </tbody>
               <tfoot class="table-warning text-center" style="font-size:.8em">
                <th style="width:10px;">#</th>
                <th style="width:10px;">id_prod</th>
                <th style="width:110px;">Código</th>
                <th style="width:410px;">Descripcion</th>
                <th style="width:50px;">U.Med.</th>
                <th style="width:90px;">Exist</th>
                <th style="width:90px;">Minimo</th>
                <th style="width:80px;">$ Compra</th>
                <th style="width:120px;">F. Entrada</th>
                </tfoot>            
        </table>                  
    </div>
        <!-- /.card-body -->
        <div class="card-footer">

        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script src="vistas/js/almacen.js"></script>
<?php 
ob_end_flush();
?>
