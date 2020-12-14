<?php
date_default_timezone_set("America/Mexico_City");
error_reporting(E_ALL ^ E_NOTICE);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1>Administrar Almacen:&nbsp;
            <small><i class="fa fa-building-o"></i></small>
          </h1>
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
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarAlmacen"><i class="fa fa-plus-circle"></i> Agregar Almacen
        </button>
        <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>

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
          <div class="card-body">
            <table class="table table-bordered table-striped dt-responsive activarDatatable" width="100%">
              <thead>
                <tr>
                  <th style="width:9px;">#</th>
                  <th>Nombre</th>
                  <th>Ubicación</th>
                  <th>Responsable</th>
                  <th>Teléfono</th>
                  <th>Email</th>
                  <th style="width:19px;">Acción</th>
                </tr>
              </thead>
              <tbody>

                <?php

                $item = null;
                $valor = null;

                $almacenes = ControladorAlmacenes::ctrMostrarAlmacenes($item, $valor);
                //if(!empty($almacenes)){

                foreach ($almacenes as $key => $value) {

                  echo '<tr>

                    <td>' . ($key + 1) . '</td>

                    <td>' . $value["nombre"] . '</td>

					<td>' . $value["ubicacion"] . '</td>
					
					<td>' . $value["responsable"] . '</td>
					
					<td>' . $value["telefono"] . '</td>

                    <td>' . $value["email"] . '</td>

                    <td>

                      <div class="btn-group">
                          
                        <button class="btn btn-warning btn-sm btnEditarAlmacen" data-toggle="modal" data-target="#modalEditarAlmacen" idAlmacen="' . $value["id"] . '"><i class="fa fa-pencil"></i></button>';

                  if ($_SESSION["perfil"] == "Administrador") {

                    echo '<button class="btn btn-danger btn-sm btnEliminarAlmacen" idAlmacen="' . $value["id"] . '"><i class="fa fa-times"></i></button>';
                  }

                  echo '</div>  

                    </td>

                  </tr>';
                }
                //}else{

                //}	

                ?>

              </tbody>
              <tfoot>
                <tr>
                  <th style="width:9px;">#</th>
                  <th>Nombre</th>
                  <th>Ubicación</th>
                  <th>Responsable</th>
                  <th>Teléfono</th>
                  <th>Email</th>
                  <th style="width:19px;">Acción</th>
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

<!-- === MODAL AGREGAR ALMACEN ==-->

<div class="modal fade" id="modalAgregarAlmacen" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">

    <div class="modal-content">
      <form role="form" method="POST">
        <!-- Modal Header -->
        <div class="modal-header m-1 p-1 colorbackModal">

          <h4 class="modal-title">Agregar Almacen</h4>

          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


        <!-- Modal body -->
        <div class="modal-body">

          <div class="box-body"></div>

          <div class="card card-info">
            <div class="card-body">
              <span>Sin espacios ni simbolos, min. 8 max. 12 caracteres</span>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-building-o"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="Nombre" name="nuevoAlmacen" title="Sin espacios ni simbolos, min. 8 max. 12 caracteres" maxlength="12" pattern="[A-Za-z0-9_]{8,12}" autofocus onkeyUp="mayuscula(this);" required>

                <input type="hidden" name="idDeUsuario" value="<?php echo $_SESSION['id']; ?>">
              </div>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="Ubicación" name="nuevaUbicacion" required>
              </div>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-male"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="Responsable" name="nuevoResponsable" required>
              </div>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="Teléfono" name="nuevoTelefono" value="" data-inputmask='"mask": "(999) 999-9999"' data-mask required>
              </div>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                <input type="email" class="form-control input-lg" placeholder="Email" name="nuevoEmail" required>
              </div>

            </div>
          </div>

        </div>

        <!-- Modal footer -->
        <div class="modal-footer m-1 p-1 colorbackModal">

          <button type="button" class="btn btn-sm btn-primary float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
          <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Crear Almacen</button>

        </div>

        <?php

        $crearAlmacen = new ControladorAlmacenes();
        $crearAlmacen->ctrCrearAlmacen();
        ?>
      </form>
    </div> <!-- fin del modal-content -->
  </div>
</div> <!-- fin del modal -->


<!-- === MODAL EDITAR ALMACEN ==-->

<div class="modal fade" id="modalEditarAlmacen" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">

    <div class="modal-content">
      <form role="form" method="POST">
        <!-- Modal Header -->
        <div class="modal-header m-1 p-1 colorbackModal">

          <h4 class="modal-title">Editar Almacen</h4>

          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


        <!-- Modal body -->
        <div class="modal-body">

          <div class="box-body"></div>

          <div class="card card-info">
            <div class="card-body">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-building-o"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="Nombre" name="editarAlmacen" id="editarAlmacen" readonly>

                <input type="hidden" id="idAlmacen" name="idAlmacen">
                <input type="hidden" name="idDeUsuario" value="<?php echo $_SESSION['id']; ?>">

              </div>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="Ubicación" name="editarUbicacion" id="editarUbicacion" required>
              </div>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-male"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="Responsable" name="editarResponsable" id="editarResponsable" required>
              </div>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="Teléfono" name="editarTelefono" id="editarTelefono" data-inputmask='"mask": "(999) 999-9999"' data-mask required>
              </div>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                <input type="email" class="form-control input-lg" placeholder="Email" name="editarEmail" id="editarEmail" required>
              </div>

            </div>
          </div>

        </div>

        <!-- Modal footer -->
        <div class="modal-footer m-1 p-1 colorbackModal">

          <button type="button" class="btn btn-sm btn-primary float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
          <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Guardar Cambios</button>

        </div>

        <?php

        $editarAlmacen = new ControladorAlmacenes();
        $editarAlmacen->ctrEditarAlmacen();
        ?>

      </form>
    </div> <!-- fin del modal-content -->
  </div>
</div> <!-- fin del modal -->


<?php

//$eliminarCliente = new ControladorClientes();
//$eliminarCliente -> ctrEliminarCliente();

?>