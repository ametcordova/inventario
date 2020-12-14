<?php
    $fechaHoy=date("d/m/Y");
?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-1 p-1">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1>
                <small>Control Presupuesto</small>
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
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
		        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target=""><i class="fa fa-plus-circle"></i> Agregar
          </button>

        	 <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>          

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>

        <div class="card-body">
          
        <div class="row">
			<hr/>

                <div class="col-lg-6 col-md-6 col-sm-6">
                    <h2>Ingresos</h2> 	
                    <div class="table-responsive">
                        <table class="table table-striped compact table-hover dt-responsive">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width:8px;" class='text-left'>Item</th>
									<th class='text-center'>Descripción</th>
                                    <th class='text-center'>Importe</th>
                                    <th class='text-center'>Acción</th>
                                </tr>
                            </thead>
                            <tbody class='items_ingresos'>
                                
    <tr>
		<td class='text-center'>1</td>
		<td class='text-center'>Ingresos para cambios</td>
		<td class='text-center'><?php echo number_format(1200.25,2,'.',',');?></td>
		<td class='text-center'>
			<a href="#update_ingresos"  data-target="#update_ingresos" class="edit" data-toggle="modal" data-id='' data-descripcion="" data-previsto="" data-real=""><i class="fa fa-pencil" data-toggle="tooltip" title="Editar"></i></a>
            &nbsp
			<a href="#" onclick="eliminar_ingreso()"><i class="fa fa-trash" data-toggle="tooltip" title="Eliminar" style="color:#ff3300"></i></a>
		</td>
    </tr>

                            </tbody>
                        </table>
                        <hr/>
                            <tr>
                                <td colspan='6'>
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalIngresos"><span class="fa fa-plus-circle"></span> Agregar Ingresos</button>
                                </td>
                            </tr>
                    </div>
                </div>


                <div class="col-lg-6 col-md-6 col-sm-6">
                <h2>Egresos</h2>
                    <div class="table-responsive">
                        <table class="table table-striped compact table-hover dt-responsive">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width:8px;" class='text-center'>Item</th>
									<th class='text-center'>Descripción</th>
                                    <th class='text-center'>Importe</th>
                                    <th class='text-center'>Acción</th>
                                </tr>
                            </thead>
                            <tbody class='items_egresos'>
    <tr>
		<td class='text-center'>1</td>
		<td class='text-center'>Pago Cerveza</td>
		<td class='text-center'><?php echo number_format(1210.25,2,'.',',');?></td>
		<td class='text-center'>
			<a href="#update_gastos"  data-target="#update_gastos" class="edit" data-toggle="modal" data-id='' data-descripcion="" data-previsto="" data-real=""><i class="fa fa-pencil" data-toggle="tooltip" title="Editar"></i></a>
            &nbsp
			<a href="#" onclick="eliminar_gasto()"><i class="fa fa-trash" data-toggle="tooltip" title="Eliminar" style="color:#ff3300"></i></a>
		</td>
    </tr>


                            </tbody>
                        </table>
                        <hr/>
                            <tr>
                                <td colspan='6'>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalGastos"><span class="fa fa-minus"></span> Agregar Egresos</button>
                                </td>
                            </tr>
                    </div>
                </div>

            </div>  <!-- fin row -->


        </div>
        <!-- /.card-body -->
        
        <div class="card-footer">
          @Kórdova
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  

  <!-- ================================ MODAL INGRESOS ======================== -->
<!-- === MODAL AGREGAR CAJA ==-->
<div class="modal fade" id="modalIngresos" data-backdrop="static" data-keyboard="false" tabindex="-1">

<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content">
    <div class="modal-header bg-success py-1 m-0">
      <h3>Ingreso</h3>
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
    <div class="modal-body py-1 m-0">
		  <form id="form_ingreso" class="py-0 m-0">
		
      <div class="row">
						<div class="col-md-5">
                <label>Fecha:</label>
                  <input type="date" class="form-control" id="fechaIngreso" name="fechaIngreso" value="<?= $fechaHoy ?>" required>
                  <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
						</div>

						<div class="col-md-7">
                <label>Concepto:</label>
                  <input type="text" class="form-control" id="conceptoIngreso" name="conceptoIngreso" placeholder="Max. 25 letras" required>
						</div>
						
			</div>

          <div class="col-md-12">
             <label for="importeIngreso" class="col-form-label">Importe:</label>
                <input type="number" class="form-control" name="importeIngreso" id="importeIngreso" value="" placeholder="Capture Ingreso" title="Ingreso a caja" min="0" step="any" required>
          </div>

          <div class="col-md-12">
            <label for="motivoInlngreso" class="col-form-label">Descripcion:</label>
                <textarea class="form-control" type="text" id="descripcionIngreso" name="descripcionIngreso" title="Breve descripcion del Ingreso" rows="1" placeholder="Descripción detallada"></textarea>
          </div> 

          <div class="modal-footer py-2 m-0">
            <button type="button" data-dismiss="modal" class="btn btn-sm">Cerrar</button>
            <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
          </div>
        </form>

      </div>
  </div>
 </div>
</div>  
  <!-- ================================ FIN MODAL INGRESOS ======================== -->