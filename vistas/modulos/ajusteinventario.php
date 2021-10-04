<style>
	.select2-results__options{
        font-size:14px !important;
	}	
</style>
<?php
$tabla="usuarios";
$usuario=$_SESSION['id'];
$module="ajusteinv";
$campo="administracion";
$acceso=accesomodulo($tabla, $usuario, $module, $campo);
$fechaHoy=date("Y-m-d");
?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-0 ml-2 p-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h3>Ajuste de Inventario</h3>
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
           <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalAgregarAjuste">
           <span class="fa fa-plus-circle"></span> Agregar Ajuste
          </button>
         <?php } ?>
          <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>

      <!-- Date range -->
        <?php if(getAccess($acceso, ACCESS_VIEW)){?>
          <button type="button" class="btn btn-default btn-sm ml-3 mr-2 " id="daterange-btn-Ajuste" value="01/01/2018 - 01/15/2018">
           <span>
            <i class="fa fa-calendar"></i> Rango de fecha
           </span>
              <i class="fa fa-caret-down"></i>                     
          </button>
          
            <button class="btn btn-success btn-sm" onclick="dataAjusteInv()">
              <i class="fa fa-eye"></i>  Mostrar
            </button>
        <?php } ?>
          <!--<h2 class="card-title">Control de Usuarios</h2> -->
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
         </div>
         <div class="row">
         
         </div>


     <div class="card-body">
      <div class="card">
            <div class="card-body">
              <table class="table table-bordered compact table-hover table-striped dt-responsive " id="datatableAI" cellspacing="0" width="100%">
                <thead class="thead-dark">
                <tr>
                    <th style="width:9px;">#</th>
                    <th>Tipo de Mov.</th>
                    <th>Almacen</th>
                    <th>Motivo</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                                
                </tbody>
                <tfoot class="thead-dark">
                <tr>
                    <th style="width:5%;">#</th>
                    <th>Tipo de Mov.</th>
                    <th>Almacen</th>
                    <th>Motivo</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th style="width:10%;">Acción</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->            
        </div>
        <!-- /.card-body -->
        
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 <!-- ======== MODAL AGREGAR AJUSTE ========-->
 
  <div class="modal fade" id="modalAgregarAjuste" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xlg">
   
    <div class="modal-content">
    <form role="form" id="form_agregaAjuste">
      <!-- Modal Header -->
      <div class="modal-header m-2 p-1" style="background:lightblue">
   
            <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Ajuste de Inventario.</h4>
        
            <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
           
        <div class="box-body">
        </div>   

        <div class="card card-info">
         <div class="card-body m-0">
           
           <div class="form-row m-0">
		   
         
                <!-- data-date-end-date=no puede seleccionar una fecha posterior a la actual -->
                  <div class="form-group col-md-1">
                    <label class="control-label" for="nvoNumAjuste"><i class="fa fa-file-o"></i> No.</label>
                    <input type="text" class="form-control form-control-sm text-center" name="nvoNumAjuste" id="nvoNumAjuste" value=""  readonly title="Número de Ajuste">
                    <span id="msjNumeroRepetido"></span> 
                    <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
                  </div>
				           <?php
                    if(getAccess($acceso, ACCESS_EDIT)){?>
                      <div class="form-group col-md-3">
                        <label class="control-label" for="nvaFechaAjuste"><i class="fa fa-calendar"></i> Fecha</label>
                        <input type="date" class="form-control form-control-sm" name="nvaFechaAjuste" value="<?= $fechaHoy ?>" tabindex="1" required title="Fecha Ajuste">
                      </div>
                    <?php    
                    }else{ ?>
                      <div class="form-group col-md-2">
                        <label class="control-label" for="nvaFechaAjuste"><i class="fa fa-calendar"></i> Fecha</label>
                        <input type="date" class="form-control form-control-sm" name="nvaFechaAjuste" value="<?= $fechaHoy ?>" tabindex="2" readonly title="Fecha Ajuste">
                      </div>
                    <?php } ?>
                  
                  <div class="form-group col-md-2">
                    <label class="control-label" for="nvoNombreAjuste"><i class="fa fa-check"></i> Resp.</label>
                    <input type="text" class="form-control form-control-sm" name="nvoNombreAjuste" id="nvoNombreAjuste" value="<?php echo $_SESSION['nombre'];?>" placeholder="" tabindex="3" readonly title="Nombre Usuario ">
                  </div>
				  
                <div class="form-group col-md-3">
                 <label for="inputTipoMov"><i class="fa fa-bookmark-o"></i> Tipo de Mov.</label>
                  <select class="form-control form-control-sm" name="nvoTipoAjuste" id="nvoTipoAjuste" title="Tipo de Ajuste" tabindex="3" required>
                    <option value="" selected>Seleccione Tipo</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $tabla="tipomovimiento";
                    $tipomov=ControladorAjusteInventario::ctrMostrarTipoMovs($tabla, $item, $valor);
                      foreach($tipomov as $key=>$value){
                           echo '<option value="'.$value["id"].'-'.$value["clase"].'">'.$value["nombre_tipo"].' - '.$value["clase"] . '</option>';
                      }
                  ?>				  
                  </select>			  
                </div>

              <div class="form-group col-md-3">
                  <label for="inputAlmacen"><i class="fa fa-hospital-o"></i> Almacen</label>
                  <select class="form-control form-control-sm" name="nvoAlmAjuste" id="nvoAlmAjuste" tabindex="4" required>
                  <option value=0 selected>Seleccione Almacen</option>
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
                <span  id="msjNoSeleccionado"></span>
            </div>
			
        			  <div class="form-row m-0">
                  <div class="form-group col-md-12">
                    <input type="text" class="form-control form-control-sm" name="nvoMotivoAjuste" id="nvoMotivoAjuste" value="" placeholder="Motivo del Ajuste *" title="Motivo del Ajuste" tabindex="5" required>
                  </div>
			          </div>
                    
			        <div class="dropdown-divider"></div> 

          <div class="form-row" id="agregarProd">        <!--tmb invisible -->
            <div class="col-md-5">
              <div class="form-group">
				        <label class="control-label" for="selecProductoAjuste"><i class="fa fa-file-o"></i> Producto:</label>
                <select class="form-control selectAjuste" name="selecProductoAjuste" id="selecProductoAjuste" style="width: 100%;" autofocus tabindex="6">
				            <option selected value=""></option>
 				  	          <?php
                        $item=null;
                        $valor=null;
                        $orden="id";
                        $estado=1;
                        $productos=ControladorProductos::ctrMostrarProductos($item, $valor,$orden, $estado);
                        foreach($productos as $key=>$value){
                          //if(is_null($value["datos_promocion"])){	//VALIDA QUE PRODUCTO NO SEA PROMOCION
                                echo '<option value="'.$value["id"].'">'.$value["codigointerno"]." - ".$value['descripcion'].'</option>';
                          //}
                        }
                      ?>		
                  </select>
              </div>
            </div>

              <div class="col-md-1 col-sm-1">
                <label class="control-label" for="cantExist">Exist</label>
                <input type="number" class="form-control form-control-sm text-center font-weight-bold inputExiste" name="cantExist" id="cantExist" value="" step="any" readonly title="cantidad Existente" >
              </div>

              <div class="col-md-1 mx-2 col-sm-1">
                <label class="control-label" for="cantAjuste"> Cant.</label>
                <input type="number" class="form-control form-control-sm font-weight-bold text-center inputVta" name="cantAjuste" id="cantAjuste" value="" placeholder="" step="any" min="0" title="cantidad Ajuste" tabindex="7">
              </div>
        
              <div class="col-md-1 mr-0 col-sm-1">
                <label class="control-label" for="" style="color:white;" >Acción</label>
                <button class="btn btn-primary btn-sm" id="agregarProdAjuste" tabindex="8"><i class="fa fa-plus-circle"></i> Agregar</button>
              </div>

            <div class="js-event-log"></div> 

            <div class="col- ml-2 alert-danger rounded d-none" id="msjerrorajuste">
						<!-- PARA MENSAJES DE ADVERTENCIA -->
            </div>
			
          </div>   <!--FIN DE AGREGAPROD -->

		    <div class="dropdown-divider"></div> 

        <div class="wrapper">
          <section class="invoice">
                  <!-- Table row -->
                  <div class="row">
                    <div class="col-12 col-sm-12 table-hover table-compact table-responsive">
                    <table class="table table-sm table-bordered table-striped" id="detalleAjusteInv" >

                      <thead class="thead-dark">
                      <tr class="text-center">
                      <th style="width:3rem">Acción</th>
                      <th>#</th>
                      <th>Código</th>
                      <th>Producto</th>
                      <th>U. Med</th>
                      <th>Cant</th>
                      </tr>
                      </thead>
                      <tbody id="tbodyajuste">
                              
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
                      <div class="col-lg-4 col-md-6 col-sm-10 text-right" id="rowAjusteInv">
                        <button type="button" class="btn badge-warning btn-sm mb-1 mr-1 p-0">Fila(s): &nbsp 
                          <span class="badge badge-warning" id="renglon" style="font-size:1rem;"></span>
                        </button>

                        <button type="button" class="btn badge-warning btn-sm mb-1 mr-1 p-0">Cant: &nbsp 
                          <span class="badge badge-warning" id="total" style="font-size:1rem;"></span>
                        </button>
                      </div>
                      <!-- /.col -->
                  </div>
                  <!-- /.row -->
          </section>
        </div>   <!-- ./wrapper -->  

		 </div>
     </div>
      
    </div>

      <!-- Modal footer -->
      <div class="modal-footer m-1 p-2" style="background:lightblue">
        <button type="button" class="btn btn-dark btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-success btn-sm" id="btnGuardarAjusteInv"><i class="fa fa-save"></i> Guardar</button>
      </div>
      
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  
<script defer src="vistas/js/ajusteinventario.js?v=010920"></script>