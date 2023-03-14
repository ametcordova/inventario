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
$module="pcapseries";
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
            <h4>Administrar Series:&nbsp; 
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

			   <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarSeries"><i class="fa fa-plus-circle"></i> Agregar Series </button>   
         <?php if(getAccess($acceso, ACCESS_ADD)){?>
          <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>          
        <?php } ?>
			
                <!-- Date range -->
          <?php if(getAccess($acceso, ACCESS_VIEW)){?>
                    <button type="button" class="btn btn-default btn-sm ml-3 mr-2 " id="daterange-btnSeries">
                     <span>
                      <i class="fa fa-calendar"></i> Rango de fecha
                     </span>
                        <i class="fa fa-caret-down"></i>                     
                    </button>
                <button class="btn btn-success btn-sm" onclick="listarSeries()"><i class="fa fa-eye"></i>  Mostrar</button>
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
              <table class="table table-bordered compact striped hover dt-responsive" id="DatatableSeries" width="100%">
                <thead>
                <tr>
                    <th style="width:9px;">#</th>
                    <th >Id_Prod</th>
                    <th >Producto</th>
                    <th>Docto</th>
                    <th># Serie</th>
                    <th>Alfanúmerico</th>
                    <th>Ult. Mod.</th>
                    <th>status</th>
                    <th>Accion</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot>
                <tr>
                    <th style="width:9px;">#</th>
                    <th >Id_Prod</th>
                    <th >Producto</th>
                    <th>Docto</th>
                    <th># Serie</th>
                    <th>Alfanúmerico</th>
                    <th>Ult. Mod.</th>
                    <th>status</th>
                    <th>Accion</th>
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
 
<div class="modal fade" id="modalAgregarSeries" data-backdrop="static" data-keyboard="false" >

  <div class="modal-dialog modal-lg">
   
   <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header p-2 colorbackModal">
          <h4 class="modal-title">Agregar No. Series</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form role="form" method="POST" id="formularioSeries">

        <!-- Modal body -->
        <div class="modal-body">
            
          <div class="card card-info">

            <div class="card-body">

                <div class="form-row">
             
                    <!-- data-date-end-date=no puede seleccionar una fecha posterior a la actual -->
              
                     <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
                     <input type="hidden" class="form-control form-control-sm" name="fechaEntAlmacen" id="datepicker3" data-date-format="dd-mm-yyyy" data-date-end-date="0d" value="<?= $fechaHoy?>" required title="Fecha entrada al Almacen">
                     <input type="hidden" class="form-control form-control-sm" name="nombreRecibe" id="nombreRecibe" value="<?php echo $_SESSION['nombre'];?>" placeholder="" tabindex="3" readonly title="Nombre Completo quien recibe Material">
                
                    <div class="form-group col-md-3">
                      <label for="inputAlmacen"><i class="fa fa-hospital-o"></i> Almacen Ent.</label>
                      <select class="form-control form-control-sm" name="nuevoAlmacenSerie" id="nuevoAlmacenSerie"  required>
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

                    <div class="form-group col-md-3">
                        <label class="control-label" for="inputError"><i class="fa fa-check"></i>No. Docto:</label>
                        <input type="text" class="form-control form-control-sm" name="numdocto" id="numdocto" value="" placeholder="#Docto" required tabindex="4" title="Numero de docto de entrada">
                      </div>


                        <div class="form-group col-md-6">
                        <label class="control-label" for="inputError"><i class="fa fa-check"></i>Producto:</label>
                            <select class="selProdDev js-states form-control" name="selecProductoSerie" id="selecProductoSerie" style="width: 100%;">
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


                </div>  <!-- FIN DEL FORM-ROW -->		 
            
                <div class="dropdown-divider"></div>
          
                <div class="form-row " id="agregarProdDev">        <!--tmb invisible -->

                  <div class="col-md-5">
                    <input type="text" class="form-control form-control-sm mb-1" name="numeroSeries" id="numeroSeries" value="" placeholder="Serie No." title="cantidad de Salida">
                  </div>

                  <div class="col-md-5">
                    <input type="text" class="form-control form-control-sm mb-1" name="alfanumerico" id="alfanumerico" value="" placeholder="" step="any" min="0"  title="cantidad de Salida">
                  </div>

                  <div class="col-md-1">
                    <button class="btn btn-primary btn-sm mb-1" data-toggle="modal" id="agregarProductosSeries"><i class="fa fa-plus-circle"></i> Agregar</button>
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
                    <table class="table table-sm table-bordered table-striped" id="detalleDeSeries" >

                      <thead>
                      <tr class="text-center">
                      <th style="width:3rem"><i class="fa fa-cut"></i></th>
                      <th>#</th>
                      <th>Código</th>
                      <th>No. de Serie</th>
                      <th>Alfanúmerico</th>
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
                      <div class="col-lg-4 col-md-6 col-sm-10 text-right" id="calculoSerie">
                        <button type="button" class="btn badge-warning btn-sm mb-1 mr-1 p-0">Renglones: &nbsp 
                          <span class="badge badge-warning" id="renglon" style="font-size:1rem;"></span>
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
          <button type="submit" class="btn btn-success btn-sm" id="btnGuardarSerie"><i class="fa fa-save"></i> Guardar</button>
        </div> <!-- fin Modal footer -->
        
    </form>  <!-- fin del form -->
   </div> <!-- fin del modal-content -->
  </div>   <!-- fin del modal-lg -->
</div>    <!-- fin del modal -->
