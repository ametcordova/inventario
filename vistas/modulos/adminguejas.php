<style>
.select2-results__options{
  font-size:13px !important;
}
.modal-body {
  max-height: calc(100vh - 212px);
  overflow-y: auto;
}        
		
</style>
<script>    //evitar que se desconecte.
    document.addEventListener("DOMContentLoaded", ()=>{
      // Invocamos cada 5 minutos ;
      const milisegundos = 500*1000;
      setInterval(()=>{
        //console.log("500 segundos.. refrescado")
        fetch("vistas/modulos/refrescar.php");
        // No esperamos la respuesta de la petición porque no nos importa
      },milisegundos);
    });
  </script>

<?php
date_default_timezone_set("America/Mexico_City");
error_reporting(E_ALL^E_NOTICE);
$fechaHoy=date("Y-m-d");
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
            <h4>Capturar OS:&nbsp; 
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

		<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarOS"><i class="fa fa-plus-circle"></i> Agregar O. Servicio </button>   
    
         <?php if(getAccess($acceso, ACCESS_ADD)){?>
          <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>          
        <?php } ?>
			
                <!-- Date range -->
          <?php if(getAccess($acceso, ACCESS_VIEW)){?>
                    <button type="button" class="btn btn-default btn-sm ml-3 mr-2 " id="daterange-btnOS">
                     <span>
                      <i class="fa fa-calendar"></i> Rango de fecha
                     </span>
                        <i class="fa fa-caret-down"></i>                     
                    </button>
                <button class="btn btn-success btn-sm" onclick="listarOServicios()"><i class="fa fa-eye"></i>  Mostrar</button>
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

          <div class="alert alert-success alert-dismissible fade show p-2" role="alert">
            <p class="h5"><strong>Hecho!!</strong> Registro guardado correctamente!!.</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>


        <div class="card">
            <!--<div class="card-header">
            </div>-->
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered table-hover compact table-striped table-sm " id="DatatableOS" cellspacing="0" style="width:100%;">
                <thead class="thead-dark">
                <tr style="height:10px !important;">
                    <th style="width:1em;">#</th>
                    <th style="width:3em;">Emp.</th>
                    <th>Tecnico</th>
                    <th>OS</th>
                    <th>Teléfono</th>
                    <th>Almacen</th>
                    <th>Fecha</th>
                    <th>status</th>
                    <th>Accion</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot>
                <tr class="thead-dark">
                    <th style="width:1em;">#</th>
                    <th style="width:3em;">Emp.</th>
                    <th>Tecnico</th>
                    <th>OS</th>
                    <th>Teléfono</th>
                    <th>Almacen</th>
                    <th>Fecha</th>
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
 
  <!-- ========================================================================================-->
 <!-- ========================== MODAL AGREGAR DEVOLUCION ====================================-->
 <!-- ========================================================================================-->
 <div class="modal fade" id="modalAgregarOS" data-backdrop="static" data-keyboard="false">

  <div class="modal-dialog modal-lg">
   
   <div class="modal-content ui-widget-content">

      <!-- Modal Header -->
      <div class="modal-header colorbackModal px-2 py-1">
          <h5 class="modal-title ">Capturar O.S.</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form role="form" method="POST" id="formularioAgregaOS">

        <!-- Modal body -->
        <div class="modal-body pb-1">
            
          <div class="card card-info">

            <div class="card-body">

                <div class="form-row pt-0 pb-0">
             
                    <!-- data-date-end-date=no puede seleccionar una fecha posterior a la actual -->
              
                    <div class="form-group col-md-3">
                      <label for=""><i class="fa fa-hospital-o"></i> Almacen</label>
                      <select class="form-control form-control-sm" name="nuevoAlmacenOS" id="nuevoAlmacenOS" tabindex="1" required>
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
                      <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
                    </div>

                    <div class="form-group col-md-4">
                        <label for=""><i class="fa fa-male"></i> Técnico</label>
                        <select id="nvotecnico" class="form-control form-control-sm" name="nvotecnico" tabindex="2" required>
                        <option value="">Selecione Técnico</option>
                        <?php
                            $item='status';
                            $valor=1;
                            $tecnicos=ControladorTecnicos::ctrMostrarTecnicos($item, $valor);
                            foreach($tecnicos as $key=>$value){
                                echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                            }
                        ?>				  
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label class="control-label"><i class="fa fa-phone-square"></i> Teléfono</label>
                        <input type="number" class="form-control form-control-sm" name="numtelefono" id="numtelefono" value="" step="any" placeholder="" tabindex="3" title="Numero de Teléfono" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label class="control-label"><i class="fa fa-calendar"></i> Fecha Inst:</label>
                        <input type="date" class="form-control form-control-sm" name="fechainst" id="fechainst" value="<?= $fechaHoy?>" placeholder="" tabindex="4" title="Fecha Instalación" required>
                      </div>

                </div>  <!-- FIN DEL FORM-ROW -->		 

                <div class="form-row pt-0 pb-0">

                    <div class="form-group col-md-3">
                            <label class="control-label"><i class="fa fa-sort-numeric-asc"></i> No. OS:</label>
                            <input type="number" class="form-control form-control-sm" name="numeroos" id="numeroos" value="" tabindex="5" title="Numero de Orden de Servicio" required>
                    </div>

                    <div class="form-group col-md-2">
                        <label class="control-label"><i class="fa fa-check"></i>Pisaplex</label>
                        <input type="number" class="form-control form-control-sm" name="numpisaplex" id="numpisaplex" value="" step="any" placeholder="" tabindex="6" title="Numero Pisaplex">
                    </div>

                    <div class="form-group col-md-2">
                        <label class="control-label"><i class="fa fa-asterisk"></i> Tipo:</label>
                        <input type="text" class="form-control form-control-sm" name="numtipo" id="numtipo" value="" step="any" placeholder="" tabindex="7" title="Numero Tipo">
                    </div>

                    <div class="form-group col-md-5">
                        <label class="control-label"><i class="fa fa-check-square-o"></i> Nombre contratante:</label>
                        <input type="text" class="form-control form-control-sm" name="nombrecontrato" id="nombrecontrato" value="" tabindex="8" title="Numero Tipo" pattern="^[A-Za-zÑñÁáÉéÍíÓóÚúÜü\s]+$" onblur="document.getElementById('nombrefirma').value=this.value" required>
                    </div>
                </div>  <!-- FIN DEL FORM-ROW -->

                <div class="form-row pt-0 pb-0">
                    <div class="form-group col-md-8">
                        <label class="control-label"><i class="fa fa-home"></i> Dirección:</label>
                        <input type="text" class="form-control form-control-sm" name="direccionos" id="direccionos" value="" step="any" placeholder="" tabindex="9" title="Dirección de la Instalación" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label"><i class="fa fa-road"></i> Colonia:</label>
                        <input type="text" class="form-control form-control-sm" name="coloniaos" id="coloniaos" value="" placeholder="" tabindex="10" title="Colonia" required>
                    </div>
                </div>

                <div class="form-row pt-0 pb-0">
                  <div class="form-group col-md-2">
                        <label class="control-label"><i class="fa fa-map-marker"></i> Distrito:</label>
                        <input type="text" class="form-control form-control-sm" name="distritoos" id="distritoos" value="" placeholder="" tabindex="11" title="Distrito">
                    </div>

                    <div class="form-group col-md-2">
                        <label class="control-label"><i class="fa fa-flag-checkered"></i> Terminal:</label>
                        <input type="text" class="form-control form-control-sm" name="terminalos" id="terminalos" value="" placeholder="" tabindex="12" title="Numero Tipo">
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label"><i class="fa fa-ship"></i> Puerto:</label>
                        <input type="text" class="form-control form-control-sm" name="puertoos" id="puertoos" value="" placeholder="" tabindex="13" title="Numero Tipo">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label"><i class="fa fa-openid"></i> Nombre quien firma:</label>
                        <input type="text" class="form-control form-control-sm" name="nombrefirma" id="nombrefirma" value="" placeholder="" tabindex="14" title="Numero Tipo"  required>
                    </div>
                </div>

                <div class="form-row pt-0 pb-0">
                    <div class="form-group col-md-6">
                        <label class="control-label" for="inputError"><i class="fa fa-barcode"></i> Producto:</label>
                        <select class="selProdOs js-states form-control-sm" name="selecProductoOS" id="selecProductoOS" style="width: 100%;" tabindex="15">
                        </select>
                    </div>

                  <div class="form-group col-md-2">
                  <label class="control-label" for="inputError"><i class="fa fa-cubes"></i> Exist:</label>
                    <input type="number" class="form-control form-control-sm text-center mb-1" name="stocktecnico" id="stocktecnico" value="" step="any" tabindex="16" readonly title="cantidad Existente">
                  </div>

                  <div class="form-group col-md-2">
                  <label class="control-label" for="inputError"><i class="fa fa-check"></i>Salida:</label>
                    <input type="number" class="form-control form-control-sm text-center" name="cantout" id="cantout" value="0" step="any" min="0" tabindex="17" title="cantidad de Salida">
                  </div>

                  <div class="col-md-1 margen-custom text-center">
                    <button class="btn btn-primary btn-lg" id="agregarProductoOS" tabindex="18"><i class="fa fa-plus-circle"></i> Agregar</button>
                  </div>

                </div>  <!-- FIN DEL FORM-ROW -->		 

            
                <div class="form-row pt-0 pb-0 d-none" id="datosmodem">        <!--tmb invisible -->

                  <div class="col-md-5">
                    <input type="text" class="form-control form-control-sm" name="numeroSerie" id="numeroSerie" value="" placeholder="Serie No." title="No. de Serie">
                  </div>

                  <div class="col-md-5">
                    <input type="text" class="form-control form-control-sm" name="alfanumerico" id="alfanumerico" value="" placeholder="alfanumerico" title="">
                  </div>

                </div>  <!-- FIN DEL FORM-ROW -->

                <div class="form-row pt-0 pb-0">
                  <div class="col-md-12 alert-danger d-none" style="height:25px;" id="mensajerror"></div>   
                </div>  <!-- FIN DEL FORM-ROW -->
            
                  <div class="dropdown-divider"></div>		

                  <div class="respuesta"></div>			

              <div class="wrapper" id="agregarProdDev">
                <!-- Main content Producto no existe o no tiene existencia que solicita!!-->
                <section class="invoice">
              
                  <!-- Table row -->
                  <div class="row">
                    <div class="col-12 col-sm-12 table-hover table-compact table-responsive">
                    <table class="table table-sm table-bordered table-striped" id="detalleDeSeries" >

                      <thead class="thead-dark" >
                        <tr class="text-center">
                        <th style="width:3rem"><i class="fa fa-cut"></i></th>
                        <th>#</th>
                        <th>Descripción - Código</th>
                        <th>Cant.</th>
                        </tr>
                      </thead>

                        <tbody id="tbodyOS">
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
                      <div class="col-lg-4 col-md-6 col-sm-10 text-right mostrarcantidades" >
                        <button type="button" class="btn badge-warning btn-sm mb-1 mr-1 p-0">Renglones: &nbsp 
                          <span class="badge badge-warning" id="renglones" style="font-size:1rem;"></span>
                        </button>

                        <button type="button" class="btn badge-warning btn-sm mb-1 mr-1 p-0">Cant: &nbsp 
                          <span class="badge badge-warning" id="totalsalidaOS" style="font-size:1rem;"></span>
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
        <div class="modal-footer p-1 colorbackModal">
          <button type="button" class="btn btn-primary btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
          <button type="submit" class="btn btn-success btn-sm" id="btnGuardarOS" tabindex="25"><i class="fa fa-save"></i> Guardar</button>
        </div> <!-- fin Modal footer -->
        
    </form>  <!-- fin del form -->
   </div> <!-- fin del modal-content -->
  </div>   <!-- fin del modal-lg -->
</div>    <!-- fin del modal  <div class="form-row"></div>   -->

    

<script defer src="vistas/js/adminquejas.js?v=08012022"></script>