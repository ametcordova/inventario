<style>
	.select2-results__options{
        font-size:13px !important;
	}	
</style>

<?php
date_default_timezone_set("America/Mexico_City");
error_reporting(E_ALL^E_NOTICE);
$fechaHoy=date("d-m-Y");
$tabla="usuarios";
$module="pdevalm";
$campo="administracion";
$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);
?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h4>Administrar Devolución:&nbsp; 
                <small><i class="fa fa-address-book"></i></small>
            </h4>
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
         <?php if(getAccess($acceso, ACCESS_ADD)){?>
			    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarDevolucion"><i class="fa fa-plus-circle"></i> Agregar Devolución </button>   
         <?php } ?>
        <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>          
        
        <?php if(getAccess($acceso, ACCESS_VIEW)){?>
                <!-- Date range -->
                    <button type="button" class="btn btn-default btn-sm ml-3 mr-2 " id="daterange-btn1">
                     <span>
                      <i class="fa fa-calendar"></i> Rango de fecha
                     </span>
                        <i class="fa fa-caret-down"></i>                     
                    </button>
                  <button class="btn btn-success btn-sm" onclick="listarDevTec()"><i class="fa fa-eye"></i> Mostrar</button>
          <?php } ?>

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
            <!--<div class="card-header">
            </div>-->
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered compact striped hover dt-responsive" id="DatatableDevTec" width="100%">
                <thead>
                <tr>
                    <th style="width:9px;">#</th>
                    <th ># Dev.</th>
                    <th>Tecnico</th>
                    <th>F. Devolución</th>
                    <th># Almacen</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot>
                <tr>
                    <th style="width:9px;">#</th>
                    <th ># Dev.</th>
                    <th>Tecnico</th>
                    <th>F. Devolución</th>
                    <th># Almacen</th>
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
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 <!-- === MODAL AGREGAR DEVOLUCION ==-->
 
<div class="modal fade" id="modalAgregarDevolucion" data-backdrop="static" data-keyboard="false" >

  <div class="modal-dialog modal-lg">
   
   <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header p-2 colorbackModal">
          <h4 class="modal-title">Agregar Devolución</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form role="form" method="POST" id="formularioDevolucion">

        <!-- Modal body -->
        <div class="modal-body">
            
          <div class="card card-info">

            <div class="card-body">

                <div class="form-row">
                    <div class="form-group col-md-4">
                    <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
                      <label><i class="fa fa-tty"></i> Técnico</label>
                      <select class="form-control form-control-sm" name="TecnicoDev" id="TecnicoDev" style="width: 100%;" required>
                      <option selected value="">Selecione Técnico</option>
                      <?php
                        $item=null;
                        $valor=null;
                        $tecnicos=ControladorTecnicos::ctrMostrarTecnicos($item, $valor);
                        foreach($tecnicos as $key=>$value){
                              echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                        }
                      ?>				  
                      </select>
                    </div>
              
                    <!-- data-date-end-date=no puede seleccionar una fecha posterior a la actual -->
              
                      <div class="form-group col-md-2">
                        <label class="control-label" for="inputError"><i class="fa fa-calendar"></i> F. Devol.</label>
                        <input type="text" class="form-control form-control-sm" name="fechaDevolucion" id="datepicker13" data-date-format="dd-mm-yyyy" data-date-end-date="0d" value="<?= $fechaHoy?>" required title="Fecha Devolucion al Almacen">
                      </div>
              
                      <div class="form-group col-md-3">
                        <label class="control-label" for="inputError"><i class="fa fa-check"></i> Recibe:</label>
                        <input type="text" class="form-control form-control-sm" name="nombreRecibe" id="nombreRecibe" value="<?php echo $_SESSION['nombre'];?>" placeholder="" tabindex="3" readonly title="Nombre Completo quien recibe Material">
                      </div>
              
                    <div class="form-group col-md-3">
                      <label for="inputAlmacen"><i class="fa fa-hospital-o"></i> Almacen Ent.</label>
                      <select class="form-control form-control-sm" name="nuevaDevolucionAlmacen" id="nuevaDevolucionAlmacen"  required>
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
                
                </div>  <!-- FIN DEL FORM-ROW -->		 

                <div class="form-row">  
                    <div class="form-group col-md-12">
                        <label class="control-label" for="inputError"><i class="fa fa-book"></i> Motivo devolución:</label>
                        <input type="text" class="form-control form-control-sm" name="nvomotivodevolucion" id="nvomotivodevolucion" value="" placeholder="" tabindex="4" title="Motivo de la devolución">
                      </div>
                </div>  <!-- FIN DEL FORM-ROW -->		 
            
                <div class="dropdown-divider"></div>
          
                <div class="form-row " id="agregarProdDev">        <!--tmb invisible -->
                  <div class="col-md-7">
                  <div class="form-group">
                      <select class="selProdDev js-states form-control" name="selecProductoDev" id="selecProductoDev" style="width: 100%;">
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
                    <input type="number" class="form-control form-control-sm mb-1" name="cantSalidaDev" id="cantSalidaDev" value="" placeholder="" step="any" min="0"  title="cantidad de Salida">
                  </div>
                  <div class="col-md-1">
                    <button class="btn btn-primary btn-sm mb-1" data-toggle="modal" id="agregarProductosDev"><i class="fa fa-plus-circle"></i> Agregar</button>
                  </div>
                                  
                </div>
                  <div class="col-md-6 alert-danger d-none" style="height:25px;" id="mensajerror"></div>   
            
                  <div class="dropdown-divider"></div>		
                  <div class="respuesta"></div>			

              <div class="wrapper">
                <!-- Main content Producto no existe o no tiene existencia que solicita!!-->
                <section class="invoice">
              
                  <!-- Table row -->
                  <div class="row">
                    <div class="col-12 col-sm-12 table-hover table-compact table-responsive">
                    <table class="table table-sm table-bordered table-striped" id="detalleDevolucion" >

                      <thead>
                      <tr class="text-center">
                      <th style="width:3rem">Acción</th>
                      <th>#</th>
                      <th>Código</th>
                      <th>Producto</th>
                      <th>U. Med</th>
                      <th>Cant</th>
                      </tr>
                      </thead>
                      <tbody id="tbodyid">
                              
                        <!--AQUI SE AÑADEN LAS ENTRADAS DE LOS PRODUCTOS  -->
                    
                      </tbody>
                    </table>
                    
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /.row -->

                  <div class="row">
                    <!-- accepted payments column -->
                    <div class="col-lg-8 col-md-6 col-sm-2"></div>
                      <!-- /.col -->
                      <div class="col-lg-4 col-md-6 col-sm-10 text-right" id="calculoDev">
                        <button type="button" class="btn badge-warning btn-sm mb-1 mr-1 p-0">Renglones: &nbsp 
                          <span class="badge badge-warning" id="renglon" style="font-size:1rem;"></span>
                        </button>

                        <button type="button" class="btn badge-warning btn-sm mb-1 mr-1 p-0">Total: &nbsp 
                          <span class="badge badge-warning" id="total" style="font-size:1rem;"></span>
                        </button>

                      </div>
                      <!-- /.col -->
                  </div>
                  <!-- /.row -->
                </section>
              </div>   <!-- ./wrapper -->  
          
            </div>  <!--Fin del card-body -->
          </div>   <!--Fin del card card-info -->
        
        </div> <!--fin del modal-body -->

        <!-- Modal footer -->
        <div class="modal-footer p-2 colorbackModal">
          <button type="button" class="btn btn-primary btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
          <button type="submit" class="btn btn-success btn-sm" id="btnGuardarDev"><i class="fa fa-save"></i> Guardar</button>
        </div> <!-- fin Modal footer -->
        
    </form>  <!-- fin del form -->
   </div> <!-- fin del modal-content -->
  </div>   <!-- fin del modal-lg -->
</div>    <!-- fin del modal -->