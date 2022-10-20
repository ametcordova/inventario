<style>
  .select2-results__options {
    font-size: 12px !important;
  }

table.dataTable.dataTable_width_auto {
  width: auto;
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
$tabla = "usuarios";
$usuario = $_SESSION['id'];
$module = "pentradas";
$campo = "administracion";
$acceso = accesomodulo($tabla, $usuario, $module, $campo);
$fechaHoy = date("Y-m-d");
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="background-color:darkslategrey">
  <!-- Content Header (Page header) -->
  <section class="content-header m-0 ml-2 p-1">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h4 class="titleout">Entradas al Almacén</h4>
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
        <?php if (getAccess($acceso, ACCESS_ADD)) { ?>
          <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalAgregarEntradasAlmacen">
            <span class="fa fa-plus-circle"></span> Agregar Entrada
          </button>
        <?php } ?>
        <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>

        <!-- Date range -->
        <?php if (getAccess($acceso, ACCESS_VIEW)) { ?>
          <button type="button" class="btn btn-default btn-sm ml-3 mr-2 " id="daterange-btn-EntAlmacen" value="">
            <span>
              <i class="fa fa-calendar"></i> Rango de fecha
            </span>
            <i class="fa fa-caret-down"></i>
          </button>

          <button class="btn btn-success btn-sm" onclick="dt_ListarEntradasAlmacen()">
            <i class="fa fa-eye"></i> Mostrar
          </button>
        <?php } ?>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div>
      </div>

     <div class="row text-center">

      </div>


      <div class="card-body">

          <div class="alert alert-warning alert-dismissible fade show p-2 d-none" id="alert1" role="alert">
            <strong>Hecho!!</strong> Registro guardado correctamente.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

        <div class="card">
          <div class="card-body">
            <table class="table table-bordered table-hover compact table-sm table-striped dt-responsive" id="dt-entradasalmacen" cellspacing="0" width="100%">
              <thead class="thead-dark" style="font-size:.8rem; height:1px">
                <tr style="font-size:0.90em">
                  <th style="width:1em;">#</th>
                  <th style="width:1em;">Fecha</th>
                  <th style="width:4em;">Almacen</th>
                  <th style="width:14em;">Proveedor</th>
                  <th style="width:6em;">Tipo Mov.</th>
                  <th style="width:1em;">Usuario</th>
                  <th style="width:2em;">Acción</th>
                </tr>
              </thead>

              <tbody style="font-size:0.90em">
              </tbody>

            </table>
          </div>  <!-- /.card-body  -->
        </div>   <!-- /.card -->
      </div>   <!-- /.card-body -->

    </div>   <!-- /.card -->

  </section>  <!-- /.content -->
</div> <!-- /.content-wrapper -->

<!-- ==============================================================================
              MODAL PARA CAPTURAR LAS ENTRADAS AL ALMACEN
==================================================================================== -->
<div class="modal fade" id="modalAgregarEntradasAlmacen" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xlg">
    <div class="modal-content">
      <form role="form" id="form_entradasalmacen">
        <!-- Modal Header -->
        <div class="modal-header m-2 p-1" style="background-color:darkslategrey; color:floralwhite;">
          <h5 class="modal-title"><i class="fa fa-plus-circle"></i> Entradas al Almacén</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body m-0">

          <div class="card-body m-0 p-0">

            <div class="form-row m-0">

              <!-- data-date-end-date=no puede seleccionar una fecha posterior a la actual -->
              <div class="form-group col-md-1 pt-0 text-center">
                <label class="control-label p-0"><i class="fa fa-file-o"></i> No.</label>
                <input type="text" class="form-control form-control-sm text-center" name="numEntradaAlmacen" id="numEntradaAlmacen" value="1" readonly title="Número de salida">
                <input type="hidden" name="idDeUsuario" value="<?php echo $_SESSION['id']; ?>">
              </div>

              <div class="form-group col-md-3">
              <label><i class="fa fa-male"></i> Proveedor</label>
                  <select class="form-control form-control-sm" name="nvoProveedorEntrada" id="nvoProveedorEntrada" style="width: 100%;" tabindex="0" required>
                  <option value="">Selecione Proveedor</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $proveedores=ControladorProveedores::ctrMostrarProveedores($item, $valor);
                    foreach($proveedores as $key=>$value){
                        if($value["id"]=="1"){
                          echo '<option selected value="'.$value["id"].'">'.$value["nombre"].'</option>';    
                        }else{
                          echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                        }
                    }
                  ?>				  
                  </select>
              </div>

              <?php
              if (getAccess($acceso, ACCESS_EDIT)) { ?>
                <div class="form-group col-md-2">
                  <label class="control-label" for="nvaFechaAjuste"><i class="fa fa-calendar"></i> Fecha</label>
                  <input type="date" class="form-control form-control-sm" name="nvaFechaEntradaAlmacen" value="<?= $fechaHoy ?>" tabindex="1" required title="Fecha Ajuste">
                </div>
              <?php
              } else { ?>
                <div class="form-group col-md-2">
                  <label class="control-label" for="nvaFechaAjuste"><i class="fa fa-calendar"></i> Fecha</label>
                  <input type="date" class="form-control form-control-sm" name="nvaFechaEntradaAlmacen" value="<?= $fechaHoy ?>" tabindex="2" readonly title="Fecha Ajuste">
                </div>
              <?php } ?>

              <div class="form-group col-md-2">
                <label class="control-label" for="nvoNombreAjuste"><i class="fa fa-check"></i> Captura</label>
                <input type="text" class="form-control form-control-sm" name="idRespEntradaAlmacen" id="idRespEntradaAlmacen" value="<?php echo $_SESSION['nombre']; ?>" placeholder="" tabindex="3" readonly title="Nombre Usuario ">
              </div>

              <div class="form-group col-md-2">
              <label for="inputTipoMov"><i class="fa fa-bookmark-o"></i> Tipo de Entrada</label>
                  <select class="form-control form-control-sm" name="nvoTipoEntradaAlmacen" id="nvoTipoEntradaAlmacen" title="Tipo de Entrada" required>
                    <option value="" selected>Seleccione Tipo</option>
                    <?php
                    $item="clase";
                    $valor="E";
                    $tipomov=ControladorEntradasAlmacen::ctrMostrarTipoMov($item, $valor);
                    foreach($tipomov as $key=>$value){
                          echo '<option value="'.$value["id"].'">'.$value["nombre_tipo"].'</option>';    
                    }
                  ?>				  
                  </select>			  
              </div>

              <div class="form-group col-md-2">
                <label for="inputAlmacen"><i class="fa fa-hospital-o"></i> Almacen</label>
                <select class="form-control form-control-sm" name="idAlmacenEntrada" id="idAlmacenEntrada" tabindex="4" required>
                  <option value="">Seleccione Almacen</option>
                  <?php
                  $item = null;
                  $valor = null;
                  $almacenes = ControladorAlmacenes::ctrMostrarAlmacenes($item, $valor);
                  foreach ($almacenes as $key => $value) {
                    echo '<option value="' . $value["id"] . '-' . $value["nombre"] . '">' . $value["nombre"] . '</option>';
                  }
                  ?>
                </select>
              </div>

            </div>

            <div class="form-row col-md-12 p-1"> <span id="msjdeerrorentrada"></span></div>

            <div class="form-row">
              <div class="form-group col-md-12">
                <input type="text" class="form-control form-control-sm" name="nvaObservacion" id="nvaObservacion" value="" placeholder="Observación" title="Observación:" tabindex="5">
              </div>
            </div>

            <div class="dropdown-divider p-1 mb-1 mt-0"></div>


            <div class="form-row d-none" id="agregarProdEntrada">        

              <!--CONSULTA DE PRODUCTOS POR AJAX REMOTE DATA-->
              <div class="col-md-4">
                 <div class="form-group">
                    <select class="form-control " name="selProdEntAlm" id="selProdEntAlm" style="width:100%; font-size:12px !important;" tabindex="5">
                    </select>
                  </div>
              </div>
                            
              <div class="col-md-1">
                <input type="text" class="form-control form-control-sm text-center mb-1" name="unidaddemedida" id="unidaddemedida" value="" readonly title="Unidad">
              </div>

              <div class="col-md-1">
                <input type="number" class="form-control form-control-sm text-center mb-1" name="cantExistenciaAlmacen" id="cantExistenciaAlmacen" value="" step="any" tabindex="6" readonly title="cantidad Existente">
              </div>

              <div class="col-md-1 text-center">
                <input type="number" class="form-control form-control-sm mb-1 text-center" name="cantEntradaAlmacen" id="cantEntradaAlmacen" value="0" step="any" min="0" tabindex="7" title="cantidad de Salida">
              </div>

              <div class="col-md-1">
                 <button class="btn btn-primary btn-sm mb-1" data-toggle="modal" id="agregaEntradaProd"><i class="fa fa-plus-circle"></i> Agregar</button>
              </div>
              
              <div class="col-md-4 alert-danger rounded d-none" style="height:30px;" id="mensajerrorentrada"></div>

            </div><!-- fin del form-row -->

            <!-- <div class="col-md-4 rounded" style="height:30px;" id="servicioSelecionado"></div> -->

            <div class="wrapper">
              <section class="invoice">
                <div class="row">
                  <div class="col-12 col-sm-12 table-hover table-compact table-responsive-sm">
                    <table class="table table-sm compact table-bordered table-striped" id="detalleEntradasAlmacen" width="100%">

                      <thead class="thead-dark" style="font-size:.8rem; height:1px">
                        <tr translate="no" class="text-center">
                        <th translate="no" style="width:2.5rem">Acción</th>
                        <th translate="no" style="width:2.5rem">#</th>
                        <th translate="no" style="width:6rem">SKU</th>
                        <th translate="no" style="width:09rem">Código</th>
                        <th translate="no">Producto</th>
                        <th translate="no" style="width:10rem">U. Med</th>
                        <th translate="no" style="width:5rem">Cant</th>
                        </tr>
                      </thead>
                        <tbody id="tbodyentradasalmacen">
                          <!--AQUI SE AÑADEN LAS ENTRADAS DE LOS PRODUCTOS  -->
                        </tbody>

                    </table>
                   </div>   <!-- /.col -->
                </div>  <!-- /.row -->

                  <div class="row">
                    <div class="col-lg-8 col-md-6 col-sm-2"></div>
                      <div class="col-lg-4 col-md-6 col-sm-10 text-right" id="rowEntradaAlma">
                        <button type="button" class="btn badge-warning btn-sm mb-1 mr-1 p-0">Fila(s): &nbsp 
                          <span class="badge badge-warning" id="renglonentradas" style="font-size:1rem;"></span>
                        </button>

                        <button type="button" class="btn badge-warning btn-sm mb-1 mr-1 p-0">Cant: &nbsp 
                          <span class="badge badge-warning" id="totalentradasalmacen" style="font-size:1rem;"></span>
                        </button>
                      </div>   <!-- /.col -->
                  </div>

              </section>
            </div>   <!-- ./wrapper -->             

          </div> <!-- fin card-body -->

        </div> <!-- fin Modal body -->

        <!-- Modal footer -->
        <div class="modal-footer m-1 p-1" style="background-color:darkslategrey; color:floralwhite;">
          <button type="button" class="btn btn-dark btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
          <button type="submit" class="btn btn-success btn-sm" id="btnGuardarEntradasAlmacen"><i class="fa fa-save"></i> Guardar</button>
        </div>

      </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>
<!-- ==============================================================================
                FIN MODAL PARA CAPTURAR ENTRADAS AL ALMACEN
==================================================================================== -->

<!-- ==============================================================================
              MODAL PARA EDITAR ENTRADAS AL ALMACEN
==================================================================================== -->
<div class="modal fade" id="modalEditarEntradasAlmacen" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xlg">
    <div class="modal-content">
      <form role="form" id="form_Editentradasalmacen">
        <!-- Modal Header -->
        <div class="modal-header m-2 p-1" style="background-color:darkslategrey; color:floralwhite;">
          <h5 class="modal-title"><i class="fa fa-plus-circle"></i> Editar Entradas al Almacén</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body m-0">

          <div class="card-body m-0 p-0">

            <div class="form-row m-0">

              <!-- data-date-end-date=no puede seleccionar una fecha posterior a la actual -->
              <div class="form-group col-md-1 pt-0 text-center">
                <label class="control-label p-0"><i class="fa fa-file-o"></i> No.</label>
                <input type="text" class="form-control form-control-sm text-center" name="numEditarEntradaAlmacen" id="numEditarEntradaAlmacen" value="" readonly title="Número de salida">
                <input type="hidden" name="idDeUsuario" value="<?php echo $_SESSION['id']; ?>">
              </div>

              <div class="form-group col-md-3">
              <label><i class="fa fa-male"></i> Proveedor</label>
                  <select class="form-control form-control-sm" name="EditarProveedorEntrada" id="EditarProveedorEntrada" style="width: 100%;" tabindex="0" readonly>
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

                <div class="form-group col-md-2">
                  <label class="control-label" for="nvaFechaAjuste"><i class="fa fa-calendar"></i> Fecha</label>
                  <input type="date" class="form-control form-control-sm" name="EditarFechaEntradaAlmacen" id="EditarFechaEntradaAlmacen" value="" tabindex="1" title="Fecha Ajuste" readonly>
                </div>

              <div class="form-group col-md-2">
                <label class="control-label" for="nvoNombreAjuste"><i class="fa fa-check"></i> Captura</label>
                <input type="text" class="form-control form-control-sm" name="EditarRespEntradaAlmacen" id="EditarRespEntradaAlmacen" value="" placeholder="" tabindex="3" readonly title="Nombre Usuario ">
              </div>

              <div class="form-group col-md-2">
              <label for="inputTipoMov"><i class="fa fa-bookmark-o"></i> Tipo de Entrada</label>
                  <select class="form-control form-control-sm" name="EditarTipoEntradaAlmacen" id="EditarTipoEntradaAlmacen" title="Tipo de Entrada" readonly>
                    <?php
                    $item="clase";
                    $valor="E";
                    $tipomov=ControladorEntradasAlmacen::ctrMostrarTipoMov($item, $valor);
                    foreach($tipomov as $key=>$value){
                          echo '<option value="'.$value["id"].'">'.$value["nombre_tipo"].'</option>';    
                    }
                  ?>				  
                  </select>			  
              </div>

              <div class="form-group col-md-2">
                <label for="inputAlmacen"><i class="fa fa-hospital-o"></i> Almacen</label>
                <select class="form-control form-control-sm" name="idEditarAlmacenEntrada" id="idEditarAlmacenEntrada" tabindex="4" readonly>
                  <?php
                  $item = null;
                  $valor = null;
                  $almacenes = ControladorAlmacenes::ctrMostrarAlmacenes($item, $valor);
                  foreach ($almacenes as $key => $value) {
                    echo '<option value="' . $value["id"] . '-' . $value["nombre"] . '">' . $value["nombre"] . '</option>';
                  }
                  ?>
                </select>
              </div>

            </div>

            <div class="form-row col-md-12 p-1"> <span id="msjEditerrorentrada"></span></div>

            <div class="form-row">
              <div class="form-group col-md-12">
                <input type="text" class="form-control form-control-sm" name="EditarObservacion" id="EditarObservacion" value="" placeholder="Observación" title="Observación:" tabindex="5" readonly>
              </div>
            </div>

            <div class="dropdown-divider p-1 mb-1 mt-0"></div>


            <div class="form-row " id="EditarProdEntrada">

              <!--CONSULTA DE PRODUCTOS POR AJAX REMOTE DATA-->
              <div class="col-md-4">
                 <div class="form-group">
                    <select class="form-control " name="selEditarProdEntAlm" id="selEditarProdEntAlm" style="width:100%;" tabindex="">
                    </select>
                  </div>
              </div>
                    
              <div class="col-md-1">
                <input type="text" class="form-control form-control-sm text-center mb-1" name="editunidaddemedida" id="editunidaddemedida" value="" readonly title="Unidad">
              </div>
              
              <div class="col-md-1">
                <input type="number" class="form-control form-control-sm text-center mb-1" name="cantEditarExistenciaAlmacen" id="cantEditarExistenciaAlmacen" value="" tabindex="6" readonly title="cantidad Existente">
              </div>

              <div class="col-md-1 text-center">
                <input type="number" class="form-control form-control-sm mb-1 text-center" name="cantEditarEntradaAlmacen" id="cantEditarEntradaAlmacen" value="0" step="any" min="0" tabindex="7" title="cantidad de Salida">
              </div>

              <div class="col-md-1">
                 <button class="btn btn-primary btn-sm mb-1" data-toggle="modal" id="EditarEntradaProd"><i class="fa fa-plus-circle"></i> Agregar</button>
              </div>
              
              <div class="col-md-4 alert-danger rounded d-none" style="height:30px;" id="mensajeEditarerrorentrada"></div>

            </div><!-- fin del form-row -->

            <!--<div class="col-md-4 rounded" style="height:30px;" id="servicioEditarSelecionado"></div> -->

            <div class="wrapper">
              <section class="invoice">
                <div class="row">
                  <div class="col-12 col-sm-12 table-hover table-compact table-responsive-sm">
                    <table class="table table-sm table-compact table-bordered table-striped" id="detEditarEntradasAlmacen" >

                      <thead class="thead-dark">
                        <tr translate="no" class="text-center">
                        <th translate="no" style="width:2.5rem">Acción</th>
                        <th translate="no" style="width:2.5rem">#</th>
                        <th translate="no" style="width:6rem">SKU</th>
                        <th translate="no" style="width:09rem">Código</th>
                        <th translate="no">Producto</th>
                        <th translate="no" style="width:10rem">U. Med</th>
                        <th translate="no" style="width:5rem">Cant</th>
                        </tr>
                      </thead>
                        <tbody id="tbodyEditarentradasalmacen">
                          <!--AQUI SE AÑADEN LAS ENTRADAS DE LOS PRODUCTOS  -->
                        </tbody>

                    </table>
                   </div>   <!-- /.col -->
                </div>  <!-- /.row -->

                  <div class="row">
                    <div class="col-lg-8 col-md-6 col-sm-2"></div>
                      <div class="col-lg-4 col-md-6 col-sm-10 text-right" id="rowEditarEntradaAlma">
                        <button type="button" class="btn badge-warning btn-sm mb-1 mr-1 p-0">Fila(s): &nbsp 
                          <span class="badge badge-warning" id="renglonEditarentradas" style="font-size:1rem;"></span>
                        </button>

                        <button type="button" class="btn badge-warning btn-sm mb-1 mr-1 p-0">Cant: &nbsp 
                          <span class="badge badge-warning" id="totalEditarentradasalmacen" style="font-size:1rem;"></span>
                        </button>
                      </div>   <!-- /.col -->
                  </div>

              </section>
            </div>   <!-- ./wrapper -->             

          </div> <!-- fin card-body -->

        </div> <!-- fin Modal body -->

        <!-- Modal footer -->
        <div class="modal-footer m-1 p-1" style="background-color:darkslategrey; color:floralwhite;">
          <button type="button" class="btn btn-dark btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
          <button type="submit" class="btn btn-success btn-sm" id="btnEditarEntradasAlmacen"><i class="fa fa-save"></i> Guardar</button>
        </div>

      </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>
<!-- ==============================================================================
                FIN MODAL PARA CAPTURAR LAS SALIDAS DEL ALMACEN
==================================================================================== -->

<!-- ==============================================================================
              MODAL PARA SUBIR ARCHIVOS DE ENTRADA CARSO AL ALMACEN 
==================================================================================== -->
<div class="modal fade" id="modalUpEntradasAlmacen" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xlg">
    <div class="modal-content">  <!-- modal-content -->
      <form role="form" name="form_upfile" id="form_upfile" enctype="multipart/form-data" action="javascript:void(0);" >
        <!-- Modal Header -->
        <div class="modal-header m-2 p-1" style="background-color:darkslategrey; color:floralwhite;">
          <h5 class="modal-title"><i class="fa fa-plus-circle"></i> Administración de archivos de Entrada al Almacén</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body m-0">

          <div class="card-body m-0 p-0">

            <div class="form-row m-0">

              <!-- data-date-end-date=no puede seleccionar una fecha posterior a la actual -->
              <div class="form-group col-md-2 pt-0 text-center">
                <label class="control-label p-0"><i class="fa fa-file-o"></i> No.</label>
                <input type="text" class="form-control form-control-sm text-center" name="numIdEntradaAlmacen" id="numIdEntradaAlmacen" readonly title="Número de Entrada">
                <input type="hidden" name="idDeUsuario" value="<?php echo $_SESSION['id']; ?>">
              </div>

              <div class="form-group col-md-5 pt-0">
                  <label class="control-label p-0 text-left"><i class="fa fa-file-text"></i> Descrip. del archivo</label>
                  <input type="text" class="form-control form-control-sm" id="nombre_archivo" name="nombre_archivo" required/>
              </div>

              <div class="form-group col-md-5 pt-0">
                  <label class="control-label p-0 mr-2 text-left"><i class="fa fa-file-o"></i>  Archivo a subir:</label>
                  <input type="file" class="col-md-10" name="uploadedFile" id="uploadedFile" onchange="previewFile1()" accept="image/*,.pdf, .xlsx,.xls" required/>
                  <button type="submit" class="btn btn-sm btn-dark" name="uploadBtn"><i class="fa fa-upload"></i> Subir</button>
              </div>

            </div>

            <div>
                <img src="" class="previewImg1" height="200" alt="Image preview...">  
            </div>

            <div class="alert alert-dismissible fade show d-none" id="response" role="alert">
            </div>

            <div class="row justify-content-lg-center">
              <div class="col-lg-12 ">
                <progress id="barra_de_progreso" value="0" max="100" style="height:40px; width:99%" ></progress>
              </div>
            </div>

              <div class="dropdown-divider"></div>

              <div class="wrapper">
                <section class="invoice">
                    <div class="row">
                      <div class="col-12 col-sm-12">
                        <table class="table table-bordered table-hover table-compact table-sm table-striped dt-responsive" id="subirarchivosentradas" cellspacing="0" style="width:100%">

                          <thead class="thead-dark">
                            <tr translate="no" class="text-center">
                              <th translate="no" >Acción</th>
                              <th translate="no" >#</th>
                              <th translate="no" >Descripción del archivo</th>
                              <th translate="no" >Nombre archivo</th>
                              <th translate="no" >Tipo</th>
                              <th translate="no" >Peso</th>
                            </tr>
                          </thead>
                            <tbody id="tbodyarchivos">
                            </tbody>
                        </table>
                      </div>   <!-- /.col -->
                    </div>  <!-- /.row -->

                    <div class="row">
                    </div>

                  </section>
              </div>   <!-- ./wrapper -->  



          </div> <!-- fin card-body -->

        </div> <!-- fin Modal body -->

        <!-- Modal footer -->
        <div class="modal-footer m-1 p-1" style="background-color:darkslategrey; color:floralwhite;">
          <button type="button" class="btn btn-warning text-bold btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
          <!--<button type="submit" class="btn btn-success btn-sm" id="btnGuardarEntradasAlmacen"><i class="fa fa-save"></i> Guardar</button> -->
        </div>

      </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>
<!-- ==============================================================================
                FIN MODAL PARA SUBIR ARCHIVOS DE ENTRADA CARSO AL ALMACEN 
==================================================================================== -->

<script defer src="vistas/js/entradasalmacen.js?v=01102022"></script>
<script defer src="vistas/js/entradasalmacenedit.js?v=01102022"></script>
<script defer src="extensiones/upload.js?v=05102020"></script>
