  <?php
  $fechaHoy = date("d/m/Y");
  $yearHoy = date("Y");
  $tabla = "usuarios";
  $module = "pctfacts";
  $campo = "administracion";
  $acceso = accesomodulo($tabla, $_SESSION['id'], $module, $campo);
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background-color:darkslategrey;">
    <!-- Content Header (Page header) -->
    <section class="content-header m-0 p-1">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h5 style="color:floralwhite;">Gestión Empresas:&nbsp;
              <small><i class="fa fa-building"></i></small>
            </h5>
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

        <div class="card-header border-success mb-3 py-1">
          <div class="col-md-12">
            <div class="input-group mb-3 col-md-9">
              <?php if (getAccess($acceso, ACCESS_ADD)) { ?>
                <button class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#modalAgregarEmpresa"><i class="fa fa-plus-circle"></i> Agregar Empresa
                </button>
              <?php } ?>

              <button class="btn btn-danger btn-sm mr-2" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>

            </div> <!-- fin * -->
          </div>

          <div class="card-tools">
            <button class="btn btn-tool" onclick="location.reload()" title="Reset filtros"><i class="fa fa-refresh"></i></button>
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Ocultar">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" onclick="regresar()" data-toggle="tooltip" title="a Inicio">
              <i class="fa fa-times"></i></button>
          </div>


        </div> <!-- fin del card-header -->

        <div class="card-body p-1">

          <div class="card">

            <!-- <div class="card-header p-0">
              <div class="text-center col-md-12 d-none" style="font-family: 'Play', sans-serif; font-size:1.1em; font-weight:bold;" id="sumaseleccionados">Suma selección s/IVA:
                <label class="sumaseleccion text-info" ></label>
              </div>
            </div>  -->

            <div class="card-body table-responsive-sm p-1">

              <table class="table table-bordered compact table-hover table-striped dt-responsive" cellspacing="0" id="dt-empresas" width="100%">
                <thead class="thead-dark" style="font-size:0.8em">
                  <tr>
                    <th translate="no">#</th>
                    <th translate="no">Razon Social</th>
                    <th translate="no">Rfc</th>
                    <th translate="no">Dirección</th>
                    <th translate="no">Colonia</th>
                    <th translate="no">Télefono</th>
                    <th translate="no">Email</th>
                    <th translate="no">Vig. Cert.</th>
                    <th translate="no">Status</th>
                    <th translate="no">Acción</th>
                  </tr>
                </thead>
                <tbody style="font-size:0.85em">

                </tbody>
                <tfoot class="thead-dark">
                  <tr style="font-size:0.8em">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
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

  <!-- ====================================================
            MODAL AGREGAR Factura
 ============================= =======================-->
  <div class="modal fade" id="modalAgregarEmpresa" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xlg">

      <div class="modal-content">
        <form role="form" name="formularioAgregarFactura" id="formularioAgregarFactura" method="POST">
          <!-- Modal Header -->
          <div class="modal-header colorbackModal m-2 p-1">

            <h5 class="modal-title">Agregar Empresa</h5>

            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>


          <!-- Modal body -->
          <div class="modal-body py-1">

            <div class="box-body">
            </div>

            <div class="card card-info">
              <div class="card-body py-1">

                <form>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="inputAddress">Razón Social de la Empresa</label>
                      <input type="text" class="form-control form-control-sm" id="inputAddress" placeholder="">
                    </div>
                    <div class="form-group col-md-2">
                      <label for="inputZip">Rfc</label>
                      <input type="text" class="form-control form-control-sm" id="inputZip">
                    </div>
                    <div class="form-group col-md-4">
                      <label for="inputZip">Slogan</label>
                      <input type="text" class="form-control form-control-sm" id="inputZip">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="inputCity">Dirección</label>
                      <input type="text" class="form-control form-control-sm" id="inputCity">
                    </div>
                    <div class="form-group col-md-2">
                      <label for="inputZip">C.P.</label>
                      <input type="text" class="form-control form-control-sm" id="inputZip">
                    </div>
                    <div class="form-group col-md-2">
                      <label for="inputState">Ciudad</label>
                      <input type="text" class="form-control form-control-sm" id="inputState">
                    </div>
                    <div class="form-group col-md-2">
                      <label for="inputCity">Estado</label>
                      <input type="text" class="form-control form-control-sm" id="inputCity">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-2">
                      <label for="inputZip">Teléfono Empresa</label>
                      <input type="text" class="form-control form-control-sm" id="inputZip">
                    </div>
                    <div class="form-group col-md-3">
                      <label for="inputZip">Email Empresa</label>
                      <input type="email" class="form-control form-control-sm" id="inputZip">
                    </div>
                    <div class="form-group col-md-3">
                      <label for="inputCity">Contacto</label>
                      <input type="text" class="form-control form-control-sm" id="inputCity">
                    </div>
                    <div class="form-group col-md-2">
                      <label for="inputZip">Teléfono Contacto</label>
                      <input type="text" class="form-control form-control-sm" id="inputZip">
                    </div>
                    <div class="form-group col-md-2">
                      <label for="inputZip">Email Contacto</label>
                      <input type="email" class="form-control form-control-sm" id="inputZip">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-2">
                      <label for="inputCity">No.Incial Ticket</label>
                      <input type="text" class="form-control form-control-sm" id="inputCity">
                    </div>
                    <div class="form-group col-md-2">
                      <label for="inputZip">No. Inicial Compras</label>
                      <input type="text" class="form-control form-control-sm" id="inputZip">
                    </div>
                    <div class="form-group col-md-2">
                      <label for="inputZip">% de IVA</label>
                      <input type="email" class="form-control form-control-sm" id="inputZip">
                    </div>
                    <div class="form-group col-md-2">
                      <label for="inputZip">% Margen Ganancia</label>
                      <input type="email" class="form-control form-control-sm" id="inputZip">
                    </div>
                    <div class="form-group col-md-4">
                      <label for="inputZip">Página Web</label>
                      <input type="text" class="form-control form-control-sm" id="inputZip">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label for="exampleInputFile">Subir Certificado. (cer.pem)</label>
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input form-control-sm" id="exampleInputFile">
                          <label class="custom-file-label" for="exampleInputFile">Seleccione Archivo</label>
                        </div>
                      </div>
                    </div>
                  
                    <div class="form-group col-md-4">
                      <label for="exampleInputFile">LLave Primaria (key.pem)</label>
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input form-control-sm" id="exampleInputFile">
                          <label class="custom-file-label" for="exampleInputFile">Seleccione Archivo</label>
                        </div>
                      </div>
                    </div>

                    <div class="form-group col-md-2">
                      <label for="inputPassword4">Contraseña</label>
                      <input type="password" class="form-control" id="inputPassword4" placeholder="Password">
                    </div>                  

                    <div class="form-group col-md-2">
                      <label for="inputZip">Vigencia</label>
                      <input type="date" class="form-control" id="inputZip" readonly>
                    </div>

                  </div>

                  </form>
              </div> <!-- fin del card-body -->
            </div> <!-- fin de card-info -->

          </div> <!-- fin del modal-body -->

          <!-- Modal footer -->
          <div class="modal-footer colorbackModal p-2">

            <button type="button" class="btn btn-primary btn-sm float-left salirfrm" data-dismiss="modal" tabindex="12"><i class="fa fa-reply"></i>
              Salir
            </button>
            <button type="submit" class="btn btn-success btn-sm enviarfrm" tabindex="13"><i class="fa fa-save"></i> Guardar</button>
            <!--<div class="spin">
              <button type="button" class="btn btn-sm btn-warning"> Espere... <i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i></button>
            </div>-->

          </div>

        </form>
      </div> <!-- fin del modal-content -->
    </div>
  </div>




  <script defer src="vistas/js/gestion-empresas.js?v=15122020"></script>
  <!--===========================================================================================-->