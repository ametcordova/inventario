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
  <section class="content-header m-0 ml-2 p-0">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h4 class="titleout">Módulo de facturación</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
            <li class="breadcrumb-item active">Facturación</li>
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
          <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCrearFactura">
            <span class="fa fa-plus-circle"></span> Nueva Factura
          </button>
        <?php } ?>
        <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-backward"></i> Regresar</button>

        <!-- Date range -->
        <?php if (getAccess($acceso, ACCESS_VIEW)) { ?>
          <button type="button" class="btn btn-default btn-sm ml-3 mr-2 " id="daterange-btn-factingreso" value="">
            <span>
              <i class="fa fa-calendar"></i> Rango de fecha
            </span>
            <i class="fa fa-caret-down"></i>
          </button>

          <button class="btn btn-success btn-sm" onclick="dt_ListarFacturasIngreso()">
            <i class="fa fa-list"></i> Mostrar
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
            <table class="table table-bordered table-hover compact table-sm table-striped dt-responsive" id="dt-FacturaIngreso" cellspacing="0" width="100%">
              <thead class="thead-dark" style="font-size:.8rem; height:1px">
                <tr style="font-size:0.95em">
                  <th class="text-center" style="width:1em;">#</th>
                  <th class="text-center" style="width:1em;">Folio</th>
                  <th class="text-center" style="width:1em;">F. Timb.</th>
                  <th style="width:14em;">Receptor</th>
                  <th class="text-center" style="width:4em;">Tipo Comp.</th>
                  <th class="text-center" style="width:4em;">Total</th>
                  <th style="width:1em;">Status</th>
                  <th style="width:1em;">Acción</th>
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
<div class="modal fade" id="modalCrearFactura" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xlg">
    <div class="modal-content">
      <form role="form" id="formularioFactura">
        <!-- Modal Header -->
        <div class="modal-header m-2 p-1" style="background-color:darkslategrey; color:floralwhite;">
          <h5 class="modal-title"><i class="fa fa-plus-circle"></i> Factura Ingreso</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body m-0">

          <div class="card-body m-0 p-0">

            <div class="form-row m-0">

              <!-- data-date-end-date=no puede seleccionar una fecha posterior a la actual -->
              <div class="form-group col-md-1 pt-0 my-0 text-center">
                <label class="control-label p-0"><i class="fa fa-file-o"></i> No.</label>
                <input type="text" class="form-control form-control-sm text-center" name="numidfactura" id="numidfactura" value="0" readonly title="Número de ID Factura">
                <input type="hidden" name="idDeUsuario" value="<?php echo $_SESSION['id']; ?>">
                <input type="hidden" name="idtipocomprobante" value='I'>
                <input type="hidden" name="idexportacion" value='01'>
              </div>

              <div class="form-group col-md-1">
                <label class="control-label" for="nvofolio"><i class="fa fa-sort-numeric-asc"></i> Folio</label>
                <input type="number" class="form-control form-control-sm" name="nvofolio" id="nvofolio" tabindex="1" required title="Folio">
              </div>

              <?php
              if (getAccess($acceso, ACCESS_EDIT)) { ?>
                <div class="form-group col-md-2">
                  <label class="control-label" for="nvaFechaAjuste"><i class="fa fa-calendar"></i> Fecha</label>
                  <input type="date" class="form-control form-control-sm" name="nvaFechaElaboracion" value="<?= $fechaHoy ?>" tabindex="2" readonly required title="Fecha elaboración">
                </div>
              <?php
              } else { ?>
                <div class="form-group col-md-2">
                  <label class="control-label" for="nvaFechaAjuste"><i class="fa fa-calendar"></i> Fecha</label>
                  <input type="date" class="form-control form-control-sm" name="nvaFechaElaboracion" value="<?= $fechaHoy ?>" tabindex="2" readonly title="Fecha elaboración">
                </div>
              <?php } ?>

              <div class="form-group col-md-2">
                <label class="control-label" for="nvoNombreAjuste"><i class="fa fa-check"></i> Captura</label>
                <input type="text" class="form-control form-control-sm" name="idCaptura" id="idCaptura" value="<?php echo $_SESSION['nombre']; ?>" placeholder="" readonly title="Nombre Usuario ">
              </div>              

              <div class="form-group col-md-4">
              <label><i class="fa fa-male"></i> Cliente:</label>
                  <select class="form-control form-control-sm" name="nvoClienteReceptor" id="nvoClienteReceptor" style="width: 100%;" tabindex="3" required>
                  <option value="">Selecione Cliente</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $clientes=ControladorClientes::ctrMostrarClientes($item, $valor);
                    foreach($clientes as $key=>$value){
                        // if($value["id"]=="5"){
                        //   echo '<option selected value="'.$value["id"]. '-' . $value["nombre"] .'">'.$value["nombre"].'</option>';    
                        // }else{
                          echo '<option value="'.$value["id"].'">'.$value["rfc"].'-'.$value["nombre"].'</option>';
                        //}
                    }
                  ?>				  
                  </select>
              </div>
              
              <div class="form-group col-md-2">
                <label class="control-label" for="rfcreceptor"><i class="fa fa-check"></i> RFC</label>
                <input type="text" class="form-control form-control-sm" name="rfcreceptor" id="rfcreceptor" value="" placeholder="" tabindex="" readonly title="Nombre Usuario ">
              </div>              

            </div>


              <div class="form-row col-md-12 p-1">

              <div class="form-group col-md-3">
                <label class="control-label" for="nvoregimenfiscalreceptor"><i class="fa fa-check"></i> Régimen Fiscal</label>
                <input type="text" class="form-control form-control-sm" name="nvoregimenfiscalreceptor" id="nvoregimenfiscalreceptor" placeholder="Regimen Fiscal" tabindex="4" readonly title="Régimen Fiscal receptor" required>
              </div>              

                <div class="form-group col-md-2">
                <label for="nvoFormaPago"><i class="fa fa-bookmark-o"></i> Forma de pago</label>
                    <select class="form-control form-control-sm" name="nvoFormaPago" id="nvoFormaPago" title="Forma de Pago" tabindex="5" required>
                      <option value="" selected>Seleccione Tipo</option>
                    </select>			  
                </div>

                <div class="form-group col-md-2">
                  <label for="inputAlmacen"><i class="fa fa-hospital-o"></i> Uso CFDI</label>
                  <select class="form-control form-control-sm" name="nvoUsocfdi" id="nvoUsocfdi" title="Uso del CFDI" tabindex="6" required>
                    <option value="">Seleccione ....</option>
                    <?php
                    $item = null;
                    $valor = null;
                    $usocfdi = ControladorFacturaIngreso::ctrMostrarUsoCFDI($item, $valor);
                    foreach ($usocfdi as $key => $value) {
                      echo '<option value="' . $value["id"] . '-' . $value["id_cfdi"] . '">' . $value["descripcion"] . '</option>';
                    }
                    ?>
                  </select>
                </div>

                <div class="form-group col-md-2">
                <label for="nvoMetodoPago"><i class="fa fa-bookmark-o"></i> Método de pago</label>
                    <select class="form-control form-control-sm" name="nvoMetodoPago" id="nvoMetodoPago" title="Método de pago" tabindex="7" required>
                      <option value="" selected>Seleccione Tipo</option>
                      <option value="PUE">PUE-Pago en una sola exhibición</option>
                      <option value="PPD">PPD-Pago en parcialidades o diferido</option>
                    </select>			  
                </div>

                <div class="form-group col-md-3">
                  <label class="control-label" for="nvoemail"><i class="fa fa-envelope"></i> email</label>
                  <input type="text" class="form-control form-control-sm" name="nvoemail" id="nvoemail" value="" placeholder="email" tabindex="8" title="Correo electrónico">
                </div>              


            </div>

            <div class="form-row col-md-12 p-1"> <span id="msjdeerrorentrada"></span></div>

            <div class="form-row">
              <div class="form-group col-md-12">
                <input type="text" class="form-control form-control-sm" name="nvaObserva" id="nvaObserva" value="" placeholder="Observación" title="Observación:" tabindex="9">
              </div>
            </div>

            <div class="dropdown-divider p-1 mb-1 mt-0 bg-default"></div>


            <div class="form-row d-none" id="agregarProdFactura">        

              <!--CONSULTA DE PRODUCTOS POR AJAX REMOTE DATA-->
              <div class="col-md-2">
                 <div class="form-group">
                    <select class="form-control " name="cveprodfactura" id="cveprodfactura" style="width:100%; font-size:12px !important;" tabindex="10">
                    </select>
                  </div>
              </div>
                            
              <div class="col-md-5">
                <input type="text" class="form-control form-control-sm mb-1" name="nvoconcepto" id="nvoconcepto" title="Concepto" placeholder="Producto o Servicio" tabindex="11" >
              </div>

              <div class="col-md-1">
                <input type="text" class="form-control form-control-sm text-center mb-1" name="unidaddemedida" id="unidaddemedida" readonly title="Unidad">
              </div>

              <div class="col-md-1">
                <input type="number" class="form-control form-control-sm text-center mb-1" name="nvacantidad" id="nvacantidad" value="" step="any" tabindex="12" title="cantidad">
              </div>

              <div class="col-md-2">
                <input type="number" class="form-control form-control-sm mb-1 text-right" name="nvopreciounitario" id="nvopreciounitario" value="0" step="any" min="0" tabindex="13" title="Precio Unitario">
              </div>

              <div class="col-md-1">
                 <button class="btn btn-primary btn-sm mb-1" data-toggle="modal" id="agregaProdFactura"><i class="fa fa-plus-circle"></i> Agregar</button>
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
                          <th translate="no" style="width:6rem">Clave</th>
                          <th translate="no">Descripción Producto</th>
                          <th translate="no" style="width:5rem">Cant</th>
                          <th translate="no" style="width:09rem">P.U.</th>
                          <th translate="no" style="width:10rem">Precio Tot.</th>
                        </tr>
                      </thead>
                        <tbody id="tabladedetalles">
                          <!--AQUI SE AÑADEN LAS ENTRADAS DE LOS PRODUCTOS  -->
                        </tbody>

                    </table>
                   </div>   <!-- /.col -->
                </div>  <!-- /.row -->

                  <div class="row" id="count-row">
                    <div class="col-lg-4 col-md-4 col-sm-10 text-left">
                      <button type="button" class="btn badge-warning btn-sm mb-1 ml-1 mr-1 p-0">Fila(s): &nbsp 
                        <span class="badge badge-warning" id="renglonentradas" style="font-size:1rem;"></span>
                      </button>
                    </div>   <!-- /.col -->
                    <button type="button" class="btn badge-warning btn-sm mb-1 mr-1 p-0">Cant: &nbsp 
                      <span class="badge badge-warning" id="totalentradasalmacen" style="font-size:1rem;"></span>
                    </button>

                    <div class="col-lg-2 col-md-2 col-sm-10 text-right">
                        <button type="button" class="btn badge-warning btn-sm mb-1 mr-1 p-0">Subtotal: &nbsp 
                          <span class="badge badge-warning" id="subtotal" style="font-size:1rem;"></span>
                        </button>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-10 text-right" id="count-row">
                        <button type="button" class="btn badge-warning btn-sm mb-1 mr-1 p-0">Impuesto: &nbsp 
                          <span class="badge badge-warning" id="impuesto" style="font-size:1rem;"></span>
                        </button>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-10 text-right" id="count-row">
                        <button type="button" class="btn badge-warning btn-sm mb-1 mr-1 p-0">Total: &nbsp 
                          <span class="badge badge-warning" id="total" style="font-size:1rem;"></span>
                        </button>
                    </div>


                  </div>

              </section>
            </div>   <!-- ./wrapper -->             

          </div> <!-- fin card-body -->

        </div> <!-- fin Modal body -->

        <!-- Modal footer -->
        <div class="modal-footer m-1 p-1" style="background-color:darkslategrey; color:floralwhite;">
          <button type="button" class="btn btn-dark btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
          <button type="submit" class="btn btn-success btn-sm" id="btnGuardarFactura"><i class="fa fa-save"></i> Guardar</button>
        </div>

      </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>
<!-- ==============================================================================
                FIN MODAL PARA CAPTURAR ENTRADAS AL ALMACEN
==================================================================================== -->


<script defer src="vistas/js/facturaingreso.js?v=01092022"></script>
