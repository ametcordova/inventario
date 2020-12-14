<?php
    $fechaHoy=date("d/m/Y");
?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1><small><i class="fa fa-truck" aria-hidden="true"></i> Salida de Almacen</small></h1>
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

	 <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>          

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" onclick="regresar()" data-widget="remove" data-toggle="tooltip" title="Inicio"><i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <form role="form" method="POST" class="formularioSalida" id="formularioSalida">
        
        <div class="card-body">

           <div class="form-row">
                <div class="form-group col-md-3">
                 <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
                  <label><i class="fa fa-tty"></i> Técnico</label>
                  <select class="form-control form-control-sm" name="nuevoTecnico" id="nuevoTecnico" style="width: 100%;" tabindex="0" required>
                  <option value="">Selecione Técnico</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $tecnicos=ControladorTecnicos::ctrMostrarTecnicos($item, $valor);
                    foreach($tecnicos as $key=>$value){
                        //if($value["id"]=="1"){
                          //echo '<option selected value="'.$value["id"].'">'.$value["nombre"].'</option>';    
                        //}else{
                          echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                        //}
                    }
                  ?>				  
                  </select>
              </div>
           
                <!-- data-date-end-date=no puede seleccionar una fecha posterior a la actual -->
                  <div class="form-group col-md-1">
                    <label class="control-label" for="numeroDocto"><i class="fa fa-file-o"></i> No.</label>
                    <input type="text" class="form-control form-control-sm" name="numeroSalida" id="numeroSalida" value="" placeholder="" tabindex="1" required title="Número de Salida">
                    <span id="msjNumeroRepetido"></span> 
                  </div>
				  
                  <div class="form-group col-md-1">
                    <label class="control-label" for="inputError"><i class="fa fa-calendar"></i> F. Salida</label>
                    <input type="text" class="form-control form-control-sm" name="fechaSalida" id="datepicker2" data-date-format="dd/mm/yyyy" data-date-end-date="0d" value="<?= $fechaHoy?>" tabindex="3" tabindex="2" required title="Fecha Salida de Almacen">
                  </div>
                  <div class="form-group col-md-3">
                    <label class="control-label" for="inputError"><i class="fa fa-check"></i> Responsable:</label>
                    <input type="text" class="form-control form-control-sm" name="nombreSalida" id="nombreSalida" value="<?php echo $_SESSION['nombre'];?>" placeholder="" tabindex="3" readonly title="Nombre Completo quien entrega Material">
                  </div>
				  
				   <div class="form-group col-md-2">
					  <label for="inputAlmacen"><i class="fa fa-hospital-o"></i> Almacen</label>
					  <select class="form-control form-control-sm" name="nuevaSalidaAlmacen" id="nuevaSalidaAlmacen" tabindex="4" required>
						<option value="" selected>Seleccione Almacen</option>
                     <?php
                        $item=null;
                        $valor=null;
                        $almacenes=ControladorAlmacenes::ctrMostrarAlmacenes($item, $valor);
                        foreach($almacenes as $key=>$value){
                            echo '<option value="'.$value["id"].'-'.$value["nombre"].'">'.$value["nombre"].'</option>';
                        }
                      ?>								
					  </select>			  
					</div>
                <div class="form-group col-md-2">
                 <label for="inputTipoMov"><i class="fa fa-bookmark-o"></i> Tipo de Mov.</label>
                  <select class="form-control form-control-sm" name="nuevoTipoSalida" id="nuevoTipoSalida" title="Tipo de Salida" required>
                    <option selected>Seleccione Tipo</option>
                    <option value="1">Recarga a los Técnicos</option>
                    <option value="2">Traspasos entre Almacenes</option>
                    <option value="3">Devolución a Carso</option>
                    <option value="4">Ajustes Inv.</option>
                  </select>			  
                </div>
					  
            </div>  <!-- FIN DEL ROW -->
					
			<div class="dropdown-divider"></div> 
            
           <div class="form-row d-none" id="agregarProd">        <!--tmb invisible -->
            <div class="col-md-5">
                 <div class="form-group">
                  <select class="form-control select3" name="selecProductoSal" id="selecProductoSal" style="width: 100%;" tabindex="5">
				    <option selected value=""></option>
 					<?php
                        $item=null;
                        $valor=null;
                        $orden="id";
                        $productos=ControladorProductos::ctrMostrarProductos($item, $valor,$orden);
                        foreach($productos as $key=>$value){
                            echo '<option value="'.$value["id"].'">'.$value["codigointerno"]." - ".$value["descripcion"].'</option>';
                            //echo '<option value="'.$value["id"].'">'.$value["codigointerno"]." - ".$value["descripcion"]." - ".$value["precio_compra"].'</option>';
                        }
                      ?>		
                  </select>
                </div>
            </div>

            <div class="col-md-1">
            <input type="number" class="form-control form-control-sm text-center mb-1" name="cantExiste" id="cantExiste" value="" step="any" tabindex="6" readonly title="cantidad Existente">
            </div>
            
            <div class="col-md-1">
            <input type="number" class="form-control form-control-sm mb-1" name="cantSalida" id="cantSalida" value="" placeholder="" step="any" min="0" tabindex="7" title="cantidad de Salida">
            </div>
            <div class="col-md-1">
               <button class="btn btn-primary btn-sm mb-1" data-toggle="modal" id="agregarProductos"><i class="fa fa-plus-circle"></i> Agregar</button>
            </div>
            <div class="col-md-4 alert-danger d-none" style="height:30px;" id="mensajerror"></div>
            
         </div>   
         
		<div class="dropdown-divider"></div>		
         <div class="respuesta"></div>
       
		<div class="wrapper">
		  <!-- Main content Producto no existe o no tiene existencia que solicita!!-->
			 <section class="invoice">
		 
				 <!-- Table row -->
				 <div class="row">
				  <div class="col-12 col-sm-12 table-responsive">
					<table class="table table-sm table-bordered table-striped" id="detalleSalida" >

					  <thead>
					  <tr class="text-center">
						<th style="width:3rem">Acción</th>
						<th>#</th>
						<th>Código</th>
						<th>Producto</th>
						<th>U. Med</th>
						<th>Cant</th>
						<th>P.venta</th>
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
				  <div class="col-lg-9 col-md-6 col-sm-2"></div>
				  
				  <!-- /.col -->
				  <div class="col-lg-3 col-md-6 col-sm-10 text-right">
					<p class="lead">Sumas Totales </p>

					<div class="table-responsive ">
					  <table class="table">
						<tr>
						  <th style="width:40%">Subtotal:$</th>
						  <td><input type="text" id="sumasubtotal" readonly style="width:6rem" dir="rtl"></td>
						</tr>
						<tr>
						  <th>IVA $ (16%)</th>
						  <td><input type="text" id="iva" readonly style="width:6rem" dir="rtl"></td>
						</tr>
						<tr>
						  <th>Total: $</th>
						  <td><input type="text" id="total" readonly style="width:6rem" dir="rtl"></td>
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
       
        </div><!-- /.card-body -->
        
         <div class="card-footer text-right">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
            
              <button class="btn btn-primary btn-sm " type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
          
              <button id="btnCancelar" class="btn btn-danger btn-sm" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
              
            </div>
        </div> <!-- /.card-footer-->    
	    </form>  <!-- TERMINA EL FORM  -->
	</div>  <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
   <?php
    $crearSalida=new ControladorSalidas();
    $crearSalida->ctrCrearSalida();
  ?>
