<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1>Entradas:&nbsp; 
                <small><i class="fa fa-shopping-cart"></i></small>
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
        <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>          


          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-tool" onclick="regresar()" title="a Inicio">
              <i class="fa fa-times"></i></button>			  
          </div>
        </div>
        <div class="card-body">
		
           <div class="form-row">
                <div class="form-group col-md-3">
                  <label><i class="fa fa-male"></i> Proveedor</label>
                  <select class="form-control form-control-sm" name="nuevoProveedor" id="nuevoProveedor" style="width: 100%;">
                  <option value="">Selecione Proveedor</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $proveedores=ControladorProveedores::ctrMostrarProveedores($item, $valor);
                    foreach($proveedores as $key=>$value){
                        echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                    }
                  ?>				  
                  </select>
              </div>
           
                <!-- data-date-end-date=no puede seleccionar una fecha posterior a la actual -->
                  <div class="form-group col-md-1">
                    <label class="control-label" for="inputSuccess"><i class="fa fa-calendar"></i> F. Docto</label>
                    <input type="text" class="form-control form-control-sm" id="datepicker1" data-date-format="dd/mm/yyyy" data-date-end-date="0d" required>
                  </div>
                  <div class="form-group col-md-2">
                    <label class="control-label" for="inputWarning"><i class="fa fa-file-o"></i> No. Documento </label>
                    <input type="text" class="form-control form-control-sm" id="inputWarning" placeholder="" required>
                  </div>
                  <div class="form-group col-md-1">
                    <label class="control-label" for="inputError"><i class="fa fa-calendar"></i> F. Entrada</label>
                    <input type="text" class="form-control form-control-sm" id="datepicker2" data-date-format="dd/mm/yyyy" placeholder="" required>
                  </div>
                  <div class="form-group col-md-3">
                    <label class="control-label" for="inputError"><i class="fa fa-check"></i> Recibe:</label>
                    <input type="text" class="form-control form-control-sm" id="inputError" placeholder="" required>
                  </div>
				  
				   <div class="form-group col-md-2">
					  <label for="inputEstado"><i class="fa fa-hospital-o"></i> Almacen</label>
					  <select id="inputState" class="form-control form-control-sm" name="NuevoEstado" id="NuevoEstado" required>
						<option selected>Seleccione Almacen</option>
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
				  
            </div>
					
		<div class="dropdown-divider"></div>
           <div class="form-row">
            <div class="col-md-4">
                 <div class="form-group">
                  <select class="form-control select2" id="selecProducto" style="width: 100%;" required>
                    <option>Seleccione Producto</option>
                      <?php
                        $item=null;
                        $valor=null;
                        $orden="id";
                        $productos=ControladorProductos::ctrMostrarProductos($item, $valor,$orden);
                        foreach($productos as $key=>$value){
                            echo '<option value="'.$value["id"].'">'.$value["descripcion"].'</option>';
                        }
                      ?>		
                  </select>
                </div>
            </div>
            <div class="col-md-4">
               <button class="btn btn-primary btn-sm" data-toggle="modal" onclick="agregarDetalle();" data-target="#modalAgregarEntrada"><i class="fa fa-plus-circle"></i> Agregar</button>
            </div>
         </div>    
		<div class="dropdown-divider"></div>		
		
<body onload="window.print();">
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
 
     <!-- Table row -->
    <div class="row">
      <div class="col-12 col-sm-12 table-responsive">
        <table class="table table-bordered table-striped" id="detalles" >

          <thead>
          <tr>
            <th>Acción</th>
            <th>Código</th>
            <th>Producto</th>
            <th>Medida</th>
            <th>Cant</th>
            <th>P.Compra</th>
            <th>Subtotal</th>
          </tr>
          </thead>
          <tbody>
                		  
		  <div class="form-row">
             <!--AQUI SE AÑADEN LAS ENTRADAS DE LOS PRODUCTOS  -->
		  </div>
         
          </tbody>
        </table>
        
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-lg-10 col-md-6 col-sm-2">
      </div>
	  
      <!-- /.col -->
      <div class="col-lg-2 col-md-6 col-sm-10 text-center">
        <p class="lead">Sumas Totales </p>

        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Subtotal:</th>
              <td>$250.30</td>
            </tr>
            <tr>
              <th>IVA (16%)</th>
              <td>$10.34</td>
            </tr>
            <tr>
              <th>Total:</th>
              <td>$265.24</td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
	
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>		

		
<!--		
                         <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                    <th style="width:5rem">Opciones</th>
                                    <th>Artículo</th>
                                    <th style="width:2rem">Cantidad</th>
                                    <th style="width:15rem">Precio Compra</th>
                                    <th style="width:8rem">% Margen</th>
                                    <th style="width:15rem">Precio Venta</th>
                                    <th style="width:8rem">Subtotal</th>
                                </thead>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>TOTAL</th>
                                    <th><h4 id="total">$ 0.00</h4><input type="hidden" name="total_compra" id="total_compra"></th> 
                                </tfoot>
                                <tbody>
                                  
                                </tbody>
                            </table>
                          </div>
	-->					  

        </div>
        <!-- /.card-body -->
        <div class="card-footer text-right">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button class="btn btn-primary btn-sm" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
			  <a href="entradas" target="_blank" class="btn btn-default disabled"><i class="fa fa-print"></i> Print</a>
              <button id="btnCancelar" class="btn btn-danger btn-sm" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
            </div>
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
