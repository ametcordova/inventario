<script>
    document.addEventListener("DOMContentLoaded", ()=>{
      // Invocamos cada 50 minutos ;
      const milisegundos = 5000*1000;
      setInterval(()=>{
      // No esperamos la respuesta de la petición porque no nos importa
      //console.log("500 segundos.. refrescado")
      fetch("vistas/modulos/refrescar.php");
      },milisegundos);
    });
  </script>
<?php
$tabla = "usuarios";
$module = "pctfacts";
$campo = "administracion";
$acceso = accesomodulo($tabla, $_SESSION['id'], $module, $campo);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header p-1">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 style="color:lightseagreen;" id="estiloletra">
            <small> <strong> Repositorio de archivos </strong><i class="fa fa-cloud-upload"></i></small>
          </h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
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
      <div class="card-header m-0">

        <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>
        <button class="btn btn-info btn-sm" id="btnregresar" onclick="refresh()" type="button"><i class="fa fa-refresh"></i> Refrescar</button>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
          <input type="hidden" name="idDeUsuario" value="<?php echo $_SESSION['id']; ?>">
        </div>
      </div>

      <div class="card-body mb-0">

        <form enctype="multipart/form-data" id="form_repositorio">
          <div class="form-row align-items-center">
            <div class="col-md-4 col-auto">
              <label class="sr-only" for="inlineFormInput">Descripción:</label>
              <input type="text" class="form-control mb-2" id="descripcion_archivo" name="descripcion_archivo" placeholder="Descripción del archivo" required>
              <input type="hidden" name="idDeUsuario" value="<?php echo $_SESSION['id']; ?>">
            </div>

            <div class="col-md-4 col-auto">
              <label class="sr-only" for="inlineFormInputGroup">Archivo:</label>
              <div class="input-group mb-2">
                <input type="file" name="uploadFile" id="uploadFile" class="form-control" style="background-color:cadetblue; color:cornsilk;" title="imagen pdf xls doc zip xlm" accept="image/*,.pdf, .xlsx,.xls, .docx,.doc, .zip, .xml, .tif, .mp4" required />
                <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />   <!--1024*1024*4 -->
              </div>
            </div>

            <div class="col-md-0.5 col-auto">
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="CheckPublic">
                <label class="form-check-label" for="autoSizingCheck">Público</label>
              </div>
            </div>

            <div class="col-md-2 col-auto" >
              <button type="submit" class="btn btn-sm btn-dark mb-2 mr-1" name="uploadBtn" id="uploadBtn"><i class="fa fa-upload"></i> Subir archivo</button>
              <span class="badge badge-info p-2">Max. 4Mb.</span>
            </div>
          </div>
        </form>

          <div id="progress_bar">
            <div class="percent">0%</div>
          </div>


        <div>

          <div class="clearfix"></div>

            <div class="col-md-12 mt-2" id="preview"></div>

          <div class="clearfix"></div>

          <div class="dropdown-divider mt-2"></div>

          <div class="table-responsive-sm p-1">
            <table class="table table-bordered table-hover table-striped compact" cellspacing="0" id="TablaRepositorio" width="100%">
              <thead class="thead-dark">
                <tr style="font-size:0.80em">
                  <th translate="no" style="width:4%;">#</th>
                  <th translate="no" style="width:34%;">Descripción.</th>
                  <th translate="no" style="width:24%;">Nombre archivo</th>
                  <th translate="no" style="width:9%;">Usuario</th>
                  <th translate="no" style="width:9%;">Stat</th>
                  <th translate="no" style="width:4%;">Tipo</th>
                  <th translate="no" style="width:5%;">Peso</th>
                  <th translate="no" style="width:9%;">Acción</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>

        </div> <!-- /.card-body -->


      </div>
      <!-- /.card -->

  </section>
  <!-- /.content -->
</div>
<!-- Modal para visualizar imagen del producto -->
<div class="modal fade" id="modalViewFile" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="ModalCenterTitle"></h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center ForViewFile">

      </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script defer src="vistas/js/repositorio.js?v=06012022"></script>
<!-- <script defer src="vistas/js/progreso.js?v=05012022"></script> -->
<script defer src="extensiones/upload.js?v=06102020"></script>
