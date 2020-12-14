<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1>Administrar Usuario
                <small>Usuario</small>
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
           <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgragarusuario">
              Agregar usuario
          </button>

          <!--<h2 class="card-title">Control de Usuarios</h2> -->
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
         </div>
        
        <div class="card-body">
<div class="card">
            <div class="card-header">
              <h3 class="card-title">Data Table With Full Features</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped dt-responsive activarDatatable">
                <thead>
                <tr>
                    <th style="width:10px;">#</th>
                    <th>Nombre</th>
                    <th>usuario</th>
                    <th>Foto</th>
                    <th>Perfil</th>
                    <th>Estado</th>
                    <th>Fecha Login</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>1</td>
                  <td>Amet Córdova</td>
                  <td>Admin</td>
                  <td><img src="vistas/img/usuarios/default/anonymous.png" class="img-thumbnail" width="30px" alt="Foto Usuario"></td>
                  <td>Administrador</td>
                  <td><button class="btn btn-success btn-sm">Activado</button></td>
                  <td>2017-12-11 12:00:00</td>
                  <td>
                      <div class="btn-group">
                          <button class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></button>
                          <button class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                      </div>
                  </td>
                </tr>

                <tr>
                  <td>1</td>
                  <td>Lizzethe margarita</td>
                  <td>Vendedor</td>
                  <td><img src="vistas/img/usuarios/default/anonymous.png" class="img-thumbnail" width="30px" alt="Foto Usuario"></td>
                  <td>Administrador</td>
                  <td><button class="btn btn-success btn-sm">Activado</button></td>
                  <td>2017-12-11 12:00:00</td>
                  <td>
                      <div class="btn-group">
                          <button class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></button>
                          <button class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                      </div>
                  </td>
                </tr>
                                
                </tbody>
                <tfoot>
                <tr>
                    <th style="width:10px;">#</th>
                    <th>Nombre</th>
                    <th>usuario</th>
                    <th>Foto</th>
                    <th>Perfil</th>
                    <th>Estado</th>
                    <th>Fecha Login</th>
                    <th>Acciones</th>
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
          Footer
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 <!-- === MODAL AGRAGAR USUARIO ==-->
 
  <div class="modal fade" id="modalAgragarusuario">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form role="form" method="POST" enctype="multipart/form-data">
      <!-- Modal Header -->
      <div class="modal-header" style="background:#F7FE2E;">
   
            <h4 class="modal-title">Agregar usuario</h4>
        
            <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      

      <!-- Modal body -->
      <div class="modal-body">
           
        <div class="box-body">
        </div>   

        <div class="card card-info">
         <div class="card-body">
           
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-user"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Nombre" name="nuevoNombre" required>
            </div>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-key"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Usuario" name="nuevoUsuario" required>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-lock"></i></span>
              </div>
              <input type="password" class="form-control input-lg" placeholder="Contraseña" name="nuevoPassword" required>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-users"></i></span>
                    <select class="form-control input-lg" name="nuevoPerfil">
                      <option value="">Seleccionar perfil</option>
                      <option value="Administrador">Administrador</option>
                      <option value="Especial">Especial</option>
                      <option value="Vendedor">Vendedor</option>
                    </select>
              </div>
            </div>
                                
            <div class="input-group mb-3">
              <div class="panel">Subir Foto</div>
            </div>
              <input type="file" id="nuevaFoto" class="form-control input-lg" placeholder="foto" name="nuevaFoto">
              <p class="help-block">peso Maximo de la foto 200mb.</p>
              <img src="vistas/img/usuarios/default/anonymous.png" class="img-thumbnail" width="80px" alt="">
         </div>
        </div>
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
       
        <button type="button" class="btn btn-primary float-left" data-dismiss="modal">Salir</button>
        <button type="submit" class="btn btn-success" data-dismiss="modal">Guardar</button>
      
      </div>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  