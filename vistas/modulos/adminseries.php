<style>
	.select2-results__options{
        font-size:13px !important;
	}	
</style>
<script>
    document.addEventListener("DOMContentLoaded", ()=>{
      // Invocamos cada 5 minutos ;
      const milisegundos = 500*1000;
      setInterval(()=>{
      // No esperamos la respuesta de la petición porque no nos importa
      //console.log("500 segundos.. refrescado")
      fetch("vistas/modulos/refrescar.php");
      },milisegundos);
    });
  </script>
<?php
date_default_timezone_set("America/Mexico_City");
//error_reporting(E_ALL^E_NOTICE);
require_once 'controladores/adminseries.controlador.php';
require_once 'modelos/adminseries.modelo.php';
$fechaHoy=date("d-m-Y");
$yearHoy=date("Y");
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
          <div class="col-md-2 d-sm-inline-block p-0">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarSeries"><i class="fa fa-plus-circle"></i> Agregar Series </button>   
            <?php if(getAccess($acceso, ACCESS_ADD)){?>
              <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>          
            <?php } ?>
          </div>
			
                <!-- Date range -->
          <?php if(getAccess($acceso, ACCESS_VIEW)){?>
                <div class="col-md-4 d-sm-inline-block p-0" style="width:fit-content;">	
                    <input type="radio" name="statuseries" value="99">
                      <label class="mr-2">Todos</label>
                    <input type="radio" class="ml-1" name="statuseries" value="1" checked>
                      <label class="mr-2">Disponible</label>
                    <input type="radio" class="ml-1" name="statuseries" value="0">
                      <label class="mr-2">Tránsito</label>
                    <input type="radio" class="ml-1" name="statuseries" value="2">
                      <label class="mr-2">Asignados</label>
                </div>		

                <div class="col-md-1 text-center mb-1 d-sm-inline-block">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                     <input type="text" class="form-control form-control-sm" placeholder="" name="filterdate" id="filterdate" value="<?= $yearHoy?>" data-toggle="tooltip" title="Año" >
                  </div>
                </div>

                <div class="col-md-3 text-center mb-1 d-sm-inline-block">
                  <button type="button" class="btn btn-default btn-sm ml-1 mr-2 " id="daterange-btnSeries">
                        <span><i class="fa fa-calendar"></i> Rango de fecha</span>
                          <i class="fa fa-caret-down"></i>
                      </button> 
                  <button class="btn btn-success btn-sm" onclick="listarSeries()"><i class="fa fa-eye"></i> Mostrar</button>
                </div>            
                        
            <?php } ?>
			      
            <div class="card-tools">
              <button type="button" class="btn btn-sm btn-tool" title="Refresh" onclick="location.reload()">
                <i class="fa fa-refresh"></i>
              </button>
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
              <thead class="thead-dark">
                <tr>
                    <th style="width:9px;">#</th>
                    <th >Id-Almacen</th>
                    <th >Id_Prod</th>
                    <th >Producto</th>
                    <th>Docto</th>
                    <th># Serie</th>
                    <th>Alfanúmerico</th>
                    <th>#Tec</th>
                    <th>#Salida</th>
                    <th>#Os</th>
                    <th>Ult. Mod.</th>
                    <th>status</th>
                    <th>Accion</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot class="thead-dark">
                <tr>
                    <th style="width:9px;">#</th>
                    <th >Id-Almacen</th>
                    <th >Id_Prod</th>
                    <th >Producto</th>
                    <th>Docto</th>
                    <th># Serie</th>
                    <th>Alfanúmerico</th>
                    <th>#Tec</th>
                    <th>#Salida</th>
                    <th>#Os</th>
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
  
<!--**********************************************************************************
=== MODAL AGREGAR NUMEROS DE SERIES Y ALFANUMERICOS ==-->
<!--**********************************************************************************==-->
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
                     <input type="hidden" class="form-control form-control-sm" name="nombreRecibe" id="nombreRecibe" value="<?php echo $_SESSION['nombre'];?>" placeholder="" readonly title="Nombre Completo quien recibe Material">
                
                    <div class="form-group col-md-3">
                      <label for="inputAlmacen"><i class="fa fa-hospital-o"></i> Almacen Ent.</label>
                      <select class="form-control form-control-sm" name="nuevoAlmacenSerie" id="nuevoAlmacenSerie" required tabindex="1">
                      <option value="" selected>Seleccione Almacen</option>
                              <?php
                                //crear-almacen.controlador
                                  $item=null;
                                  $valor=null;
                                  $estado=1;
                                  $almacenes=ControladorAlmacenes::ctrMostrarAlmacenes($item, $valor, $estado);
                                  foreach($almacenes as $key=>$value){
                                      echo '<option value="'.$value["id"].'-'.$value["nombre"].'">'.$value["nombre"].'</option>';
                                  }
                                ?>								
                      </select>			  
                    </div>

                    <div class="form-group col-md-2">
                        <label class="control-label" for="inputError"><i class="fas fa-file-code"></i> No. Docto:</label>
                        <input type="text" class="form-control form-control-sm" name="numdocto" id="numdocto" value="" placeholder="#Docto" required tabindex="2" title="Numero de docto de entrada">
                      </div>


                        <div class="form-group col-md-7">
                        <label class="control-label" for="inputError"><i class="fa fa-check"></i>Producto:</label>
                            <select class="selProdDev js-states form-control" name="selecProductoSerie" id="selecProductoSerie" style="width: 100%;" tabindex="3">
                            <option selected value=""></option>
                              <?php
                                        $item="conseries";
                                        $valor=1;
                                        $estado=1;
                                        $productos=ControladorSeries::ctrMostrarONT($item, $valor, $estado);
                                        foreach($productos as $key=>$value){
                                            echo '<option value="'.$value["id"].'">'.$value["codigointerno"]." - ".$value["descripcion"].'</option>';
                                        }
                                ?>		
                                  </select>
                          </div>

                          <div class=" col-md-12 text-center rounded d-none" id="msgerror" style="background-color:red; color:aliceblue;"></div>

                </div>  <!-- FIN DEL FORM-ROW -->		 
            
                <div class="dropdown-divider"></div>
          
                <div class="form-row " id="agregarProdDev">        <!--tmb invisible -->

                  <div class="col-md-5">
                    <input type="text" class="form-control form-control-sm mb-1" name="numeroSeries" id="numeroSeries" value="" placeholder="No. de Serie" title="No. de serie" tabindex="4">
                  </div>

                  <div class="col-md-5">
                    <input type="text" class="form-control form-control-sm mb-1" name="alfanumerico" id="alfanumerico" value="" placeholder="Alfanùmerico" step="any" min="0"  title="Alfanùmerico" tabindex="5">
                  </div>

                  <div class="col-md-1">
                    <button class="btn btn-primary btn-sm mb-1" data-toggle="modal" id="agregarProductosSeries" tabindex="6"><i class="fa fa-plus-circle"></i> Agregar</button>
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

<!--**********************************************************************************
=== MODAL EDITAR NUMEROS DE SERIES Y ALFANUMERICOS ==-->
<!--**********************************************************************************==-->
<div class="modal fade" id="modalEditarSerie" data-backdrop="static" data-keyboard="false" >

  <div class="modal-dialog modal-lg">
   
   <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header p-2 colorbackModal">
          <h4 class="modal-title">Editar Datos de ONT</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form role="form" method="POST" id="formEditaSeries">

        <!-- Modal body -->
        <div class="modal-body">
            
          <div class="card card-info">

            <div class="card-body">

                <div class="form-row">
             
                    <!-- data-date-end-date=no puede seleccionar una fecha posterior a la actual -->
              
                     <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
                     <input type="hidden" class="form-control form-control-sm" name="numeroid" id="numeroid" required>
                                    
                    <div class="form-group col-md-3">
                      <label for="inputAlmacen"><i class="fa fa-hospital-o"></i> Almacen de Sal.</label>
                      <select class="form-control form-control-sm" name="idalmacen" id="idalmacen" required tabindex="1">
                      <option value="">Seleccione Almacen</option>
                              <?php
                                //crear-almacen.controlador
                                  $item=null;
                                  $valor=null;
                                  $estado=1;
                                  $almacenes=ControladorAlmacenes::ctrMostrarAlmacenes($item, $valor, $estado);
                                  foreach($almacenes as $key=>$value){
                                      echo '<option value="'.$value["id"].'-'.$value["nombre"].'">'.$value["nombre"].'</option>';
                                  }
                                ?>								
                      </select>			  
                    </div>

                    <div class="form-group col-md-2">
                        <label class="control-label" for="inputError"><i class="fa fa-hashtag"></i> Id Prod.:</label>
                        <input type="text" class="form-control form-control-sm" name="idproduct" id="idproduct" required readonly title="Numero de producto">
                      </div>


                        <div class="form-group col-md-7">
                          <label class="control-label" for="inputError"><i class="fa fa-check"></i>Producto:</label>
                          <input type="text" class="form-control form-control-sm" name="idnombreont" id="idnombreont" value="" readonly title="Nombre Producto ">

                        </div>

  
                </div>  <!-- FIN DEL FORM-ROW -->		 
            
                <div class="dropdown-divider"></div>
          
                <div class="form-row ">        <!--tmb invisible -->

                  <div class="form-group col-md-3">
                  <label class="control-label p-0"> <i class="fa fa-check-square-o"></i> Serie</label>
                    <input type="text" class="form-control form-control-sm mb-1" name="idnumeroserie" id="idnumeroserie" value="" placeholder="No. de Serie" title="No. de serie" tabindex="2">
                  </div>

                  <div class="form-group col-md-3">
                  <label class="control-label p-0"> <i class="fa fa-plus-square-o"></i> Alfanumerico</label>
                    <input type="text" class="form-control form-control-sm mb-1" name="idalfanumerico" id="idalfanumerico" value="" placeholder="Alfanùmerico" step="any" min="0"  title="Alfanùmerico" tabindex="3">
                  </div>

                  <div class="form-group col-md-6">
                    <label class="control-label p-0"> <i class="fa fa-universal-access"></i> Técnico</label>
                    <select class="form-control form-control-sm" name="idasignado" id="idasignado" style="width: 100%;" tabindex="4">
                      <option value="0">Selecione Técnico</option>
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
                                  
                </div>

                <div class="form-row ">        <!--tmb invisible -->

                  <div class="form-group col-md-4">
                  <label class="control-label p-0"> <i class="fa fa-file-word-o"></i> #Docto.</label>
                    <input type="text" class="form-control form-control-sm mb-1" name="iddoctoserie" id="iddoctoserie" value="" placeholder="Num. docto" title="No. de Docto." tabindex="5">
                  </div>

                  <div class="form-group col-md-4">
                  <label class="control-label p-0"> <i class="fa fa-tty"></i> O.S.</label>
                    <input type="text" class="form-control form-control-sm mb-1" name="idos" id="idos" value="" placeholder="No. de O.S." title="No. de O.S." tabindex="6">
                  </div>

                  <div class="form-group col-md-4">
                    <label class="control-label p-0"> <i class="fa fa-stop-circle-o"></i> Estatus</label>
                    <select class="form-control form-control-sm" name="idestado" id="idestado" style="width: 100%;" tabindex="7" required>
                      <option value="">Selecione</option>
                          <option value=1>Disponible</option>
                          <option value=0>Transito</option>
                          <option value=2>Asignado</option>
                    </select>
                  </div>
                                  
                </div>                

          
            </div>  <!--Fin del card-body -->
          </div>   <!--Fin del card card-info -->
        
        </div> <!--fin del modal-body -->

        <!-- Modal footer -->
        <div class="modal-footer p-2 colorbackModal">
          <button type="button" class="btn btn-primary btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
          <button type="submit" class="btn btn-success btn-sm" id="btnGuardaEditaSerie"><i class="fa fa-save"></i> Guardar</button>
        </div> <!-- fin Modal footer -->
        
    </form>  <!-- fin del form -->
   </div> <!-- fin del modal-content -->
  </div>   <!-- fin del modal-lg -->
</div>    <!-- fin del modal -->
<script defer src="vistas/js/adminseries.js?v=080720231206"></script>