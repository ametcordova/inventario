<?php
//Activamos el almacenamiento en el buffer
ob_start();
$tabla="usuarios";
$module="rinventarios";
$campo="reportes";
$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);    
?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h3>Reporte de Existencia por Técnico</h3>
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
      <div class="card mb-0">
        <div class="card-header m-0 p-0 pt-2 pl-2">
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-tool" onclick="regresar()" title="a Inicio">
              <i class="fa fa-times"></i></button>			  
          </div>
           
          <div class="row ">
            <div class="form-group col-md-2">
                <select class="form-control form-control-sm" id="noalmacen" name="noalmacen" tabindex="1" required>
				        <option value=0 selected> <-- Seleccione Almacen --> </option>
                 <?php
                    $item=null;
                    $valor=null;
                    $almacenes=ControladorAlmacenes::ctrMostrarAlmacenes($item, $valor);
                    foreach($almacenes as $key=>$value){
                        echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                    }                    
                  ?>								
				 </select>
            </div>

            <div class="form-group col-md-3">
                <select class="form-control form-control-sm" name="idTecnico" id="idTecnico" style="width: 100%;" required>
                  <option value=0 selected> <-- Selecione Técnico --> </option>
                  <?php
                    $item = "status";
                    $valor = "1";
                    $tecnicos = ControladorTecnicos::ctrMostrarTecnicos($item, $valor);
                    foreach ($tecnicos as $key => $value) {
                      echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                  }
                  ?>
                </select>
              </div>

			<div class="form-group col-md-3" >
				<!-- radio 
				<input type="checkbox" name="radio1" value="todos" >
					<label>Todos</label>-->
                <?php if(getAccess($acceso, ACCESS_VIEW)){?>
                    <button class="btn btn-success btn-sm ml-1" onclick="listar_inventario_por_tecnico()"><i class="fa fa-eye"></i>
                        Mostrar
                    </button>
                <?php } ?>  

                <?php if(getAccess($acceso, ACCESS_PRINTER)){?>
                <button class="btn btn-dark btn-sm btnReporteporTecnico ml-2" title="Reporte en PDF"><i class="fa fa-print"></i> Reporte en PDF</button>
                <?php } ?>            			
            </div>

            <div class="col-md-3 alert-danger d-none float-left p-1 rounded mensajedeerror" style="height:35px;" ></div>
      
     </div>  
            
        </div>
		
        <div class="card-body">

	<table class="table table-bordered compact table-hover table-striped dt-responsive" cellspacing="0" id="tablaportecnico" width="100%">
            <thead class="thead-dark" style="font-size:0.80em">
                 <th style="width:10px;">#</th>
                 <th style="width:10px;">SKU</th>
                 <th style="width:95px;">Código</th>
                 <th style="width:320px;">Descripción</th>
                 <th style="width:110px;">U.de Med</th>
                 <th style="width:50px;">Exist</th>
            </thead>

            <tbody>                            
            </tbody>
                  <tfoot class="thead-dark">
                      <tr style="font-size:0.80em">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                      </tr>
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
  <!-- /.content-wrapper   -->
<?php 
ob_end_flush();
?>
<script defer src="vistas/js/reporteportecnico.js?v=06122021"></script>