<style>
  .select2-results__options {
    font-size: 11px !important;
  }

table.dataTable.dataTable_width_auto {
  width: auto;
}

fieldset {
background-color: #f4f1ed !important;
color: firebrick !important;
border-color: darkgoldenrod !important;
border: 9px groove (internal value) !important;
border-radius: 7px !important;
width: 100% !important;
}

legend{
color: darkblue !important;
font-weight: bold !important;
}

</style>
<script>
    document.addEventListener("DOMContentLoaded", ()=>{
      // Invocamos cada 5 minutos ;
      const milisegundos = 500*1000;
      setInterval(()=>{
      // No esperamos la respuesta de la petición porque no nos importa
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
<div class="content-wrapper" id="container" style="background-color:darkslategrey">
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
        <button type="button" class="btn btn-tool" title="Refresh" onclick="location.reload()">
            <i class="fa fa-refresh"></i>
          </button>
          <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div>
      </div>

     <div class="row text-center">

      </div>


      <div class="card-body">

        <div class="card">
          <div class="card-body">
            <table class="table table-bordered table-hover compact table-sm table-striped dt-responsive" id="dt-FacturaIngreso" cellspacing="0" width="100%">
              <thead class="thead-dark" style="font-size:.8rem; height:1px">
                <tr style="font-size:0.90em">
                  <th class="text-center"> &nbsp&nbsp&nbsp<input type="checkbox" name="select_all" value="1" id="select-all"></th>
                  <th class="text-center" title="ID">#</th>
                  <th class="text-center">Serie</th>
                  <th class="text-center">Folio</th>
                  <th>Emisor</th>
                  <th class="text-center">Fech. Elab.</th>
                  <th class="text-center">Fech. Timb.</th>
                  <th class="text-center" title="Últimos 12 Dig. del folio fiscal">UUID</th>
                  <th>Receptor</th>
                  <th class="text-center" title="Tipo de Comprobante">TC</th>
                  <th class="text-center">Total</th>  
                  <th>Status</th>
                  <th>Acción</th>
                </tr>
              </thead>

              <tbody style="font-size:0.85em">
              </tbody>

              <!--<p><b>Selected rows data:</b></p>
              <pre id="example-console-rows"></pre>-->

            </table>
          </div>  <!-- /.card-body  -->
        </div>   <!-- /.card -->
      </div>   <!-- /.card-body -->

    </div>   <!-- /.card -->

  </section>  <!-- /.content -->
</div> <!-- /.content-wrapper -->

<!-- ==============================================================================
              MODAL PARA CAPTURAR FACTURAR DE INGRESO
==================================================================================== -->
<div class="modal fade" id="modalCrearFactura" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xlg">
    <div class="modal-content">
      <form role="form" id="formularioFactura">
        <!-- Modal Header -->
        <div class="modal-header m-2 p-1" style="background-color:darkslategrey; color:floralwhite;">
          <h5 class="modal-title"><i class="fa fa-plus-circle"></i> Factura Ingreso. &nbsp&nbsp  Usuario: <?php echo $_SESSION['nombre']; ?>
          </h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body m-0">

          <div class="card-body m-0 p-0">

            <div class="form-row m-0">
              <!-- data-date-end-date=no puede seleccionar una fecha posterior a la actual -->
              <div class="form-group col-md-1 pt-0 my-0 text-center">
                <label class="control-label p-0"><i class="fa fa-file-o"></i> No.</label>
                <input type="text" class="form-control form-control-sm text-center p-0" name="numidfactura" id="numidfactura" value="0" readonly title="Número de ID Factura">
                <input type="hidden" name="idDeUsuario" value="<?php echo $_SESSION['id']; ?>">
                <input type="hidden" name="idtipocomprobante" value='I'>
              </div>

              <div class="form-group col-md-2">
                <label class="control-label" for=""><i class="fa fa-building"></i> Empresa:</label>
                <select class="form-control form-control-sm" name="idEmpresa" id="idEmpresa" style="width: 100%;" tabindex="1" required>
                  <option value="">Selecione Empresa</option>
                  <?php
                    $item='status';
                    $valor=1;
                    $empresas=ControladorFacturaIngreso::ctrGetDatosEmpresa($item, $valor);
                    foreach($empresas as $key=>$value){
                          // if($value["id"]=="1"){
                          //   echo '<option selected value="'.$value["id"].'">'.$value["rfc"].'-'.$value["razonsocial"].'</option>';
                          // }else{
                            echo '<option value="'.$value["id"].'">'.$value["rfc"].'-'.$value["razonsocial"].'</option>';
                        //}
                    }
                  ?>				  
                  </select>
              </div>              

              <div class="form-group col-md-1">
                <label class="control-label" for="nvofolio"><i class="fa fa-sort-numeric-asc"></i> Folio</label>
                <input type="number" class="form-control form-control-sm " name="nvofolio" id="nvofolio" tabindex="2" required title="Folio">
                <input type="hidden" name="rfcemisor" value=''>
                <input type="hidden" name="tasaimpuesto" value=''>
                <input type="hidden" name="idregimenfiscalemisor" value=''>
                <input type="hidden" name="codpostal" value=''>
                <input type="hidden" name="idexportacion" value=''>
                <input type="hidden" name="serie" value=''>
              </div>

              <?php
              if (getAccess($acceso, ACCESS_EDIT)) { ?>
                <div class="form-group col-md-2">
                  <label class="control-label" for="nvaFechaAjuste"><i class="fa fa-calendar"></i> Fecha</label>
                  <input type="date" class="form-control form-control-sm" name="nvaFechaElaboracion" value="<?= $fechaHoy ?>" tabindex="3" readonly required title="Fecha elaboración">
                </div>
              <?php
              } else { ?>
                <div class="form-group col-md-2">
                  <label class="control-label" for="nvaFechaAjuste"><i class="fa fa-calendar"></i> Fecha</label>
                  <input type="date" class="form-control form-control-sm" name="nvaFechaElaboracion" value="<?= $fechaHoy ?>" tabindex="3" readonly title="Fecha elaboración">
                </div>
              <?php } ?>


              <div class="form-group col-md-4">
                <label><i class="fa fa-male"></i> Cliente:</label>
                  <select class="form-control form-control-sm" name="nvoClienteReceptor" id="nvoClienteReceptor" style="width: 100%;" tabindex="4" required>
                  <option value="">Selecione Cliente</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $clientes=ControladorClientes::ctrMostrarClientes($item, $valor);
                    foreach($clientes as $key=>$value){
                        //  if($value["id"]=="5"){
                        //    echo '<option selected value="'.$value["id"].'">'.$value["rfc"].'-'.$value["nombre"].'</option>';
                        //  }else{
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

            </div> <!-- fin del 1er form-row -->


            <div class="form-row col-md-12 py-0">

              <div class="form-group col-md-3">
                <label class="control-label" for="nvoregimenfiscalreceptor"><i class="fa fa-check"></i> Régimen Fiscal</label>
                <input type="text" class="form-control form-control-sm" name="nvoregimenfiscalreceptor" id="nvoregimenfiscalreceptor" placeholder="Regimen Fiscal" tabindex="4" readonly title="Régimen Fiscal receptor" required>
              </div>              

                <div class="form-group col-md-2">
                  <label for="nvoFormaPago"><i class="fa fa-bookmark-o"></i> Forma de pago</label>
                    <select class="form-control form-control-sm" name="nvoFormaPago" id="nvoFormaPago" title="Forma de Pago" tabindex="5" required>
                      <option value="">Seleccione Tipo</option>
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
                      echo '<option value="'.$value["id"].'">'.$value["id_cfdi"].'-'.$value["descripcion"]. '</option>';
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
                  <input type="text" class="form-control form-control-sm uni-code" name="nvoemail" id="nvoemail" value="" placeholder="&#xf0e0; Email" tabindex="8" title="Correo electrónico" autocomplete="on">
                </div>              

            </div> <!-- fin del 2do form-row -->

            <div class="form-row col-md-12 p-1"> <span id="msjdeerrorentrada"></span></div>

            <div class="form-row">
              <div class="form-group col-md-12">
                <input type="text" class="form-control form-control-sm" name="nvaObserva" id="nvaObserva" value="" placeholder="Condiciones de Pago" title="Observación:" tabindex="9">
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
                <input type="hidden" name="nvonombreudemed" id="nvonombreudemed">
              </div>

              <div class="col-md-1">
                <input type="number" class="form-control form-control-sm text-center mb-1" name="nvacantidad" id="nvacantidad" value="" step="any" tabindex="12" title="cantidad">
                <input type="hidden" name="nvoobjetoimp" id="nvoobjetoimp">
              </div>

              <div class="col-md-2">
                <input type="number" class="form-control form-control-sm mb-1 text-right" name="nvovalorunitario" id="nvovalorunitario" value="0" step="any" min="0" tabindex="13" title="Precio Unitario">
                <input type="hidden" name="ismodifik" id="ismodifik" value=0>
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
                    <table class="table table-sm compact table-bordered table-striped" width="100%">

                      <thead class="thead-dark" style="font-size:.8rem; height:1px">
                        <tr translate="no" class="text-center">
                          <th translate="no" class="text-center" style="width:5.2rem">Acción</th>
                          <th translate="no" style="width:2.5rem">#</th>
                          <th translate="no" style="width:6rem">Clave</th>
                          <th translate="no">Descripción Producto</th>
                          <th translate="no" style="width:4rem">Cant</th>
                          <th translate="no" style="width:6rem">P.U.</th>
                          <th translate="no" style="width:6rem">Precio Tot.</th>
                        </tr>
                      </thead>
                        <tbody id="tabladedetalles">
                          <!--AQUI SE AÑADEN LAS ENTRADAS DE LOS PRODUCTOS  -->
                        </tbody>

                    </table>
                   </div>   <!-- /.col -->
                </div>  <!-- /.row -->

                  <div class="row" id="count-row" style="background-color:bisque;">
                    <div class="col-lg-4 col-md-4 col-sm-10 text-left">
                      <button type="button" class="btn btn-sm mb-1 ml-1 mr-1 p-0">Fila(s): &nbsp 
                        <span class="badge" id="renglonentradas" style="font-size:1rem;"></span>
                      </button>
                    </div>   <!-- /.col -->
                    <button type="button" class="btn btn-sm mb-1 mr-1 p-0">Cant: &nbsp 
                      <span class="badge" id="totalitems" style="font-size:1rem;"></span>
                    </button>

                    <div class="col-lg-2 col-md-2 col-sm-10 text-right">
                        <button type="button" class="btn btn-sm mb-1 mr-1 p-0">Subtotal: &nbsp 
                          <span class="badge" id="subtotal" style="font-size:1rem;"></span>
                        </button>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-10 text-right">
                        <button type="button" class="btn btn-sm mb-1 mr-1 p-0">Impuesto: &nbsp 
                          <span class="badge" id="impuesto" style="font-size:1rem;"></span>
                        </button>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-10 text-right">
                        <button type="button" class="btn btn-sm mb-1 mr-1 p-0">Total: &nbsp 
                          <span class="badge " id="total" style="font-size:1rem;"></span>
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
                FIN MODAL PARA CAPTURAR FACTURAS DE INGRESO
==================================================================================== -->

<!-- ==============================================================================
              MODAL PARA CAPTURAR COMPLEMENTO DE PAGO 2.0
==================================================================================== -->
<div class="modal fade" id="modalCrearComplementoPago" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xlg">
    <div class="modal-content">
      <form role="form" id="formularioComplementoPago">
        <!-- Modal Header -->
        <div class="modal-header m-2 p-1" style="background-color:darkslategrey; color:floralwhite;">
          <h6 class="modal-title"><i class="fa fa-plus-circle"></i> Complemento de Pago Ver. 2.0 &nbsp&nbsp  Usuario: <?php echo $_SESSION['nombre']; ?>
          </h6>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body m-1 pt-1 pb-1" style="background-color:azure;">  <!-- Modal body -->

          <div class="card-body m-0 p-0">  <!-- card-body -->

            <div class="form-row m-0">
              <div class="form-group col-md-2">
                <label class="control-label p-0 mt-0" for="fechaelaboracp"><i class="fa fa-calendar"></i> Fecha Elaboración:</label>
                <input type="date" class="form-control form-control-sm mt-0 text-center" name="fechaelaboracp" value="<?= $fechaHoy ?>" tabindex="1" readonly title="Fecha elaboración">
                <input type="hidden" name="idDeUsuario" value="<?php echo $_SESSION['id']; ?>">
                <input type="hidden" name="idEmpresa" value="">
              </div>

              <div class="form-group col-md-1">
                <label class="control-label p-0 mt-0" for="foliorep"><i class="fa fa-hashtag"></i> REP:</label>
                <input type="text" class="form-control form-control-sm mt-0 text-center" name="foliorep" id="foliorep" readonly title="Folio REP ">
              </div>              

              <div class="form-group col-md-5">
                <label class="control-label p-0 mt-0" for="nombreemisorcp"><i class="fa fa-building"></i> Nombre Emisor:</label>
                <input type="text" class="form-control form-control-sm mt-0" name="nombreemisorcp" id="nombreemisorcp" readonly title="Nombre  ">
                <input type="hidden" name="idemisorrep" value="">
              </div>              

              <div class="form-group col-md-2">
                <label class="control-label p-0 mt-0" for="rfcemisorcp"><i class="fa fa-check"></i> RFC Emisor:</label>
                <input type="text" class="form-control form-control-sm mt-0 text-center" name="rfcemisorcp" id="rfcemisorcp" readonly title="Nombre  ">
              </div>              

              <div class="form-group col-md-2">
                <label class="control-label p-0 mt-0" for="cpemisorcp"><i class="fa fa-check"></i> Cod. Postal:</label>
                <input type="text" class="form-control form-control-sm mt-0 text-center" name="cpemisorcp" id="cpemisorcp" readonly title="Nombre  ">
              </div>              
            </div>

            <div class="form-row m-0">
                <div class="form-group col-md-5">
                  <label class="control-label p-0 mt-0" for="nombrereceptorcp"><i class="fa fa-building"></i> Nombre Receptor:</label>
                  <input type="text" class="form-control form-control-sm mt-0" name="nombrereceptorcp" id="nombrereceptorcp" readonly title="Nombre  ">
                  <input type="hidden" name="idreceptorrep" value="">
                </div>              

                <div class="form-group col-md-2">
                  <label class="control-label p-0 mt-0" for="rfcreceptorcp"><i class="fa fa-check"></i> RFC Receptor:</label>
                  <input type="text" class="form-control form-control-sm mt-0 text-center" name="rfcreceptorcp" id="rfcreceptorcp" readonly title="Nombre  ">
                </div>              

                <div class="form-group col-md-2">
                  <label class="control-label p-0 mt-0" for="monedacp"><i class="fa fa-building"></i> Moneda:</label>
                  <input type="text" class="form-control form-control-sm mt-0" name="monedacp" id="monedacp" readonly title="Tipo Moneda">
                  <input type="hidden" name="idtipomoneda">
                </div>              

                <div class="form-group col-md-3">
                  <label class="control-label p-0 mt-0" for="fechapagocp"><i class="fa fa-calendar"></i> Fecha Pago:</label>
                  <input type="datetime-local" class="form-control form-control-sm mt-0 text-center" name="fechapagocp" tabindex="1" title="Fecha de pago" required>
                </div>

              </div>
              
              <div class="form-row m-0">
                
                <div class="form-group col-md-4">
                  <label class="control-label p-0 mt-0" for="formapagocp"><i class="fa fa-check"></i> Forma de Pago:</label>
                  <select class="form-control form-control-sm" name="formapagocp" id="formapagocp" title="Forma de Pago" tabindex="2" required>
                      <option value="">Seleccione Tipo</option>
                      <?php
                      $formadepago = ControladorFacturaIngreso::ctrGetFormaPago();
                      foreach ($formadepago as $key => $value) {
                        echo '<option value="'.$value["id"].'">'.$value["id"].'-'. $value["descripcionformapago"] . '</option>';
                      }
                      ?>
                  </select>
                </div>              

                <div class="form-group col-md-3">
                  <label class="control-label p-0 mt-0" for="metodopagocp"><i class="fa fa-check"></i> Metodo de pago:</label>
                  <select class="form-control form-control-sm" name="metodopagocp" id="metodopagocp" title="Método de pago" tabindex="3" required>
                      <option value="" selected>Seleccione Tipo</option>
                      <option value='1'>PUE-Pago en una sola exhibición</option>
                      <option value='2'>PPD-Pago en parcialidades o diferido</option>
                    </select>			  
                </div>              

                <div class="form-group col-md-3">
                  <label class="control-label p-0 mt-0" for="objetoimpcp"><i class="fa fa-check"></i> Obj Impuesto:</label>
                  <select class="form-control form-control-sm" name="objetoimpcp" id="objetoimpcp" title="Objeto de Impuesto" tabindex="4" required>
                      <option value="">Seleccione Tipo</option>
                      <?php
                      $objetoimpuesto = ControladorFacturaIngreso::ctrGetObjetoImpuesto();
                      foreach ($objetoimpuesto as $key => $value) {
                        echo '<option value="'.$value["id"].'">'.$value["id"].'-'. $value["descripcion"] . '</option>';
                      }
                      ?>
                  </select>
                </div>              

                <div class="form-group col-md-2">
                  <label class="control-label p-0 mt-0" for="tipoimpuestocp"><i class="fa fa-check"></i> Tipo Imp:</label>
                  <select class="form-control form-control-sm tipoimpuestocp" name="tipoimpuestocp" id="tipoimpuestocp" title="Tasa de Impuesto" tabindex="5" required>
                      <option value="">Seleccione Tipo</option>
                      <?php
                      $tipoimpuesto = ControladorFacturaIngreso::ctrGetTasaImpuesto();
                      foreach ($tipoimpuesto as $key => $value) {
                        if($value["id"]==002){
                          echo '<option selected value="'.$value["id"].'">'.$value["id"].' - '. $value["descripcion"] .' - '. $value["tasa"]. '</option>';
                        }else{
                          echo '<option value="'.$value["id"].'">'.$value["id"].' - '. $value["descripcion"] .' - '. $value["tasa"]. '</option>';
                        }
                      }
                      ?>
                  </select>
                </div>              

            </div>
            
              <div class="form-row m-0">
                <div class="form-group col-md-2">
                  <label class="control-label p-0 mt-0" for="usocfdicp"><i class="fa fa-check"></i> Uso CFDI:</label>
                  <input type="text" class="form-control form-control-sm mt-0" name="usocfdi" title="Uso del CFDI de pago" placeholder="CP01-Pagos" readonly>
                  <input type="hidden" name="idusocfdi">
                </div>              
                
                <div class="form-group col-md-2">
                  <label class="control-label p-0 mt-0" for="numoperacioncp"><i class="fa fa-check"></i> No. Operación:</label>
                  <input type="text" class="form-control form-control-sm mt-0" name="numoperacioncp" id="numoperacioncp" tabindex="6" title="Número de operación">
                </div>              

                <div class="form-group col-md-3">
                  <label class="control-label p-0 mt-0" for="cuentaordenantecp"><i class="fa fa-check"></i> Cuenta Ordenante:</label>
                  <input type="text" class="form-control form-control-sm mt-0" name="cuentaordenantercp" id="cuentaordenantecp" tabindex="7" title="Número de cuenta ordenante">
                </div>              
                
                <div class="form-group col-md-3">
                  <label class="control-label p-0 mt-0" for="cuentabeneficiariocp"><i class="fa fa-calendar"></i> Cuenta Beneficiario:</label>
                  <input type="text" class="form-control form-control-sm mt-0" name="cuentabeneficiariocp" tabindex="8" title="Numero de cuenta beneficiario" >
                </div>

                <div class="form-group col-md-2">
                  <label class="control-label p-0 mt-0" for="totalpagofact"><i class="fa fa-check"></i> Total Pago:</label>
                  <input type="number" class="form-control form-control-sm mt-0 text-center font-weight-bold text-primary" name="totalpagofact" id="totalpagofact" tabindex="9" title="Nombre" style="font-size:1.4em;" step="any" required>
                </div>              
              </div>
              <!-- ==================================================================
              // AQUI TERMINA LA PARTE ESTATICA DEL COMPLEMENTO DE PAGO 2.0
              ================================================================== --> 
            <div class="dropdown-divider p-1 mb-0 mt-0 bg-info"></div>
            
              <div id="doctosrelacionados">

              </div>  <!-- fin de ID doctosrelacionados -->

                  <div class="row" id="datosdecp" style="background-color:bisque;">
                    <div class="col-lg-2 col-md-2 col-sm-10 text-left">
                      <button type="button" class="btn btn-sm mb-1 ml-1 mr-1 p-0"># Factura(s): &nbsp 
                        <span class="badge" id="numdefacts" style="font-size:1rem;"></span>
                      </button>
                    </div>   <!-- /.col -->

                    <div class="col-lg-2 col-md-2 col-sm-10 text-right">
                        <button type="button" class="btn btn-sm mb-1 mr-1 p-0">Subtotal: &nbsp 
                          <span class="badge" id="subtotalcp" style="font-size:1rem;"></span>
                        </button>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-10 text-right">
                        <button type="button" class="btn btn-sm mb-1 mr-1 p-0">Impuesto: &nbsp 
                          <span class="badge" id="impuestocp" style="font-size:1rem;"></span>
                        </button>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-10 text-right">
                        <button type="button" class="btn btn-sm mb-1 mr-1 p-0">Otros: &nbsp 
                          <span class="badge" id="otrosimpcp" style="font-size:1rem;"></span>
                        </button>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-10 text-right">
                        <button type="button" class="btn btn-sm mb-1 mr-1 p-0">Total pago: &nbsp 
                          <span class="badge" id="totalcp" style="font-size:1rem;"></span>
                        </button>
                    </div>
                  </div>


            </div> <!-- fin card-body -->

        </div> <!-- fin Modal body -->

        <!-- Modal footer -->
        <div class="modal-footer m-1 p-1" style="background-color:darkslategrey; color:floralwhite;">
          <button type="button" class="btn btn-dark btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
          <button type="submit" class="btn btn-success btn-sm" id="btnGuardarCP"><i class="fa fa-save" tabindex="12"></i> Guardar</button>
        </div>
      </form>   <!-- Cierra del form -->
    </div> <!-- fin del modal-content -->
  </div>
</div>
<!-- ==============================================================================
                FIN MODAL PARA CAPTURAR FACTURAS DE INGRESO
==================================================================================== -->
<!-- ==============================================================================
              MODAL PARA SUBIR ARCHIVOS DE ENTRADA CARSO AL ALMACEN 
==================================================================================== -->
<div class="modal fade" id="modalGestionREP20" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xlg">
    <div class="modal-content">  <!-- modal-content -->
        <!-- Modal Header -->
        <div class="modal-header m-2 p-1" style="background-color:darkslategrey; color:floralwhite;">
          <h6 class="modal-title"><i class="fa fa-plus-circle"></i> Administración de Complementos de Pago 2.0</h6>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body m-0">

          <div class="card-body m-0 p-0">

            <div class="dropdown-divider"></div>

              <div class="wrapper">
                <section class="invoice">
                      <div class="col-12 col-sm-12">
                        <table class="table table-bordered table-hover table-compact table-sm table-striped dt-responsive" id="tblComplementoPago20" cellspacing="0" width="100%" >
                          <thead class="thead-dark" style="font-size:.8rem; height:1px">
                            <tr translate="no" class="text-center" style="font-size:0.90em">
                              <th translate="no">#</th>
                              <th translate="no">Folio</th>
                              <th translate="no">Fecha Elab.</th>
                              <th translate="no">F. Timbrado</th>
                              <th translate="no">Fecha Pago</th>
                              <th translate="no">RFC Emisor</th>
                              <th translate="no">RFC Receptor</th>
                              <th translate="no" class="text-center">Total Pag.</th>
                              <th translate="no" class="text-center">Acciones</th>
                            </tr>
                          </thead>

                            <tbody id="ListFilesRep20" style="font-size:0.90em">
                            </tbody>

                        </table>
                      </div>   <!-- /.col -->
                  </section>
              </div>   <!-- ./wrapper -->  

          </div> <!-- fin card-body -->

        </div> <!-- fin Modal body -->

        <!-- Modal footer -->
        <div class="modal-footer m-1 p-1" style="background-color:darkslategrey; color:floralwhite;">
          <button type="button" class="btn btn-sm btn-warning text-bold float-left py-0 px-3" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        </div>

    </div> <!-- fin del modal-content -->
  </div>
</div>
<!-- ==============================================================================
                FIN MODAL PARA SUBIR ARCHIVOS DE ENTRADA CARSO AL ALMACEN 
==================================================================================== -->

<script defer src="vistas/js/facturaingreso.js?v=131220221401"></script>
