<?php
    $fechainicio=date("d/m/Y");
?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h3>Entradas al Almacen:&nbsp; 
                <small><i class="fa fa-shopping-cart"></i></small>
            </h3>
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
        <div class="card-header m-0 p-2">
			<button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>          

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-tool" onclick="regresar()" title="a Inicio">
              <i class="fa fa-times"></i></button>			  
          </div>
        </div> <!-- fin del card-header -->
		
	 <form role="form" method="POST" class="formularioEntrada" id="formularioEntrada">	
     
      <div class="card-body">
		
		
           <div class="form-row">
                <div class="form-group col-md-3">
                  <label><i class="fa fa-male"></i> Proveedor</label>
                  <select class="form-control form-control-sm" name="nuevoProveedor" id="nuevoProveedor" style="width: 100%;" tabindex="0" required>
                  <option value="">Selecione Proveedor</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $proveedores=ControladorProveedores::ctrMostrarProveedores($item, $valor);
                    foreach($proveedores as $key=>$value){
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
                    <label class="control-label" for="inputSuccess"><i class="fa fa-calendar"></i> F. Docto</label>
                    <input type="text" class="form-control form-control-sm" name="fechaDocto" id="datepicker1" data-date-format="dd/mm/yyyy" data-date-end-date="0d" value="" tabindex="1" required>
                    <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
                  </div>
				  
                  <div class="form-group col-md-1">
                    <label class="control-label" for="numeroDocto"><i class="fa fa-file-o"></i> No. Docto.</label>
                    <input type="text" class="form-control form-control-sm" name="numeroDocto" id="numeroDocto" value="" placeholder="" tabindex="2" required title="Fecha del Documento">
                    <span id="msjDoctoRepetido"></span> 
                  </div>
				  
                  <div class="form-group col-md-1">
                    <label class="control-label" for="inputError"><i class="fa fa-calendar"></i> F. Entra.</label>
                    <input type="text" class="form-control form-control-sm" name="fechaEntrada" id="datepicker2" data-date-format="dd/mm/yyyy" value="<?= $fechainicio?>" tabindex="3" tabindex="4" required title="Feha de Entrada al Almacen">
                  </div>
                  <div class="form-group col-md-2">
                    <label class="control-label" for="inputError"><i class="fa fa-check"></i> Recibe:</label>
                    <input type="text" class="form-control form-control-sm" name="nombreRecibe" id="nombreRecibe" value="" placeholder="" tabindex="5" required title="Nombre Completo quien recibe el material">
                  </div>
				  
				   <div class="form-group col-md-2">
					  <label for="inputAlmacen"><i class="fa fa-hospital-o"></i> Almacen</label>
					  <select id="nuevoAlmacen" class="form-control form-control-sm" name="nuevoAlmacen" id="nuevoAlmacen" tabindex="6" required>
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
                 <label for="inputTipoMov"><i class="fa fa-bookmark-o"></i> Tipo de Entrada</label>
                  <select class="form-control form-control-sm" name="NuevoTipoEntrada" id="NuevoTipoEntrada" title="Tipo de Entrada" required>
                    <option selected>Seleccione Tipo</option>
                    <option value="1">Suministro Carso</option>
                    <option value="2">Devolución de los Técnicos</option>
                    <option value="3">Traspasos entre Almacenes</option>
                    <option value="4">Ajustes Inv.</option>
                  </select>			  
              </div>

            </div>  <!-fin del form-row  -->
					
			<div class="dropdown-divider"></div>
			
           <div class="form-row m-0">
            <div class="col-md-5">
                 <div class="form-group">
                  <select class="form-control select2" name="selecProducto" id="selecProducto" style="width: 100%;" tabindex="7">
				    <option selected value=""></option>
 					<?php
                        $item=null;
                        $valor=null;
                        $orden="id";
                        $productos=ControladorProductos::ctrMostrarProductos($item, $valor,$orden);
                        foreach($productos as $key=>$value){
                            echo '<option value="'.$value["id"].'">'.$value["codigointerno"]." - ".$value["descripcion"].'</option>';
                        }
                      ?>		
                  </select>
                </div>
            </div>
                  <div class="col-md-2">
                    <input type="number" class="form-control form-control-sm mb-1" name="cantentrada" id="cantentrada" value="" placeholder="" step="any" min="0"  title="cantidad de Entrada" tabindex="8">
                  </div>
            
            <div class="col-md-1">
               <button class="btn btn-primary btn-sm" data-toggle="modal" id="agregarDetalle" tabindex="9"><i class="fa fa-plus-circle"></i> Agregar</button>
            </div>
			<div class="col-md-4 alert-danger d-none" style="height:25px;" id="mensajerror"></div>
         </div>   <!-fin del form-row  para seleccionar productos-->
         
		<div class="dropdown-divider"></div>		
		
		<div class="wrapper">
		  <!-- Main content -->
			 <section class="invoice">
		 
				 <!-- Table row -->
				 <div class="row">
				  <div class="col-12 col-sm-12 table-responsive">
					<table class="table  table-sm table-bordered table-striped" id="detalles" >

					  <thead>
					  <tr class="text-center">
						<th style="width:3rem">Acción</th>
						<th style="width:3rem">#</th>
						<th style="width:7rem">Código</th>
						<th>Producto</th>
						<th style="width:7rem">Cant</th>
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
				
				<div class="dropdown-divider"></div>
				
				<div class="row">
				  <!-- accepted payments column -->
				  <div class="col-lg-9 col-md-6 col-sm-2"></div>
				  
				  <!-- /.col -->
				  <div class="col-lg-3 col-md-6 col-sm-10 text-right">

					<div class="table-responsive ">
					  <table class="table">
					  
						<tr>
						  <th style="width:40%">No. Renglones</th>
						  <td><input type="text" id="renglones" readonly style="width:6rem" dir="rtl"></td>
						</tr>
					  
						<tr>
						  <th style="width:40%">Total Piezas:</th>
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

        </div>   <!-- /.card-body -->
        
        <div class="card-footer text-right">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
            
            <!-- <button type="button" class="btn btn-primary float-left" id="checar">checar</button>-->
             
              <button class="btn btn-primary btn-sm " type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
          
	<!--		  <button class="btn btn-default btnImprimirEntrada disabled"><i class="fa fa-print"></i> Print</button> -->
             
              <button id="btnCancelar" class="btn btn-danger btn-sm" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
              
            </div>
        </div><!-- /.card-footer-->
	 </form>  <!-- TERMINA EL FORM  -->
    </div> <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
      <?php
        
        $crearEntrada=new ControladorEntradas();
        $crearEntrada->ctrCrearEntrada();
      ?>
