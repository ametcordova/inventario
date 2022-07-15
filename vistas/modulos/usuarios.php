<?php
$tabla="usuarios";
$module="usuarios";
$campo="configura";
$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);    
?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h3>Administrar Usuario:&nbsp; 
                <small><i class="fa fa-user"></i></small>
            </h3>
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
             <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarusuario"><i class="fa fa-plus-circle"></i> Agregar usuario </button>
          <?php } ?>

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
            <div class="card-header p-0 m-0 text-center" style="background-color: lavenderblush; color:#08C49F; font-size:2rem;">
               <label class="text-center p-0 m-0">Modulo Catalogo de Usuarios</label>
            </div>  <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered table-hover table-striped dt-responsive compact usuariosDatatable" width="100%">
                <thead class="thead-dark">
                <tr style="height:10px !important; font-size:0.75em !important;">
                    <th style="width:10px;">#</th>
                    <th>Nombre</th>
                    <th>usuario</th>
                    <th>Foto</th>
                    <th>Perfil</th>
                    <th>Estado</th>
                    <th>Ult. Login</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody style="font-size:0.90em !important;">
                
                <?php
                  
                  $campo=null;
                  $valor=null;
                  $usuarios=ControladorUsuarios::ctrMostrarUsuarios($campo, $valor);
                
                  foreach($usuarios as $key => $value){
                    if($value["ultimo_login"]==''){
                      $fechalogin = 'No ha iniciado sesión';
                    }else{
                      $fechalogin = date('d-m-Y H:i:s', strtotime($value["ultimo_login"]));
                    }
                      echo '
                <tr>
                  <td>'.$value["id"].'</td>
                  <td>'.$value["nombre"].'</td>
                  <td>'.$value["usuario"].'</td>';
                  if($value["foto"]!=""){
                    if(is_file($value["foto"]) && is_readable($value["foto"])) {
                      echo '<td><img src="'.$value["foto"].'" class="img-thumbnail" width="30px" alt="Foto"></td>';
                    }else{
                      echo '<td><img src="vistas/img/usuarios/default/anonymous.png" class="img-thumbnail" width="30px" alt="Foto"></td>';
                    }
                  }else{
                    echo '<td><img src="vistas/img/usuarios/default/anonymous.png" class="img-thumbnail" width="30px" alt="Foto"></td>';
                  }
                  clearstatcache();   //borra cache de las consultas is_file y is_readable
                  echo '<td>'.$value["perfil"].'</td>';

                  if(getAccess($acceso, ACCESS_ACTIVAR)){
                    if($value["estado"]!=0){
                      echo '<td><button class="btn btn-sm btn-success btnActivar" idUsuario="'.$value["id"].'" estadoUsuario="0" title="Click para desactivar" >Activado</button></td>';
                    }else{
                      echo '<td><button class="btn btn-sm btn-danger btnActivar" idUsuario="'.$value["id"].'" estadoUsuario="1" title="Click para activar">Desactivado</button></td>';
                    }
                  }else{
                    echo '<td> </td>';
                  }

                  $boton1=getAccess($acceso, ACCESS_EDIT)?'<button class="btn btn-sm btn-warning btnEditarUsuario mr-1" idUsuario="'.$value["id"].'" data-toggle="modal" data-target="#modalEditarUsuario"><i class="fa fa-pencil"></i></button> ':'';
                  $boton2=getAccess($acceso, ACCESS_DELETE)?' <button class="btn btn-sm btn-danger btnEliminarUsuario ml-1" idUsuario="'.$value["id"].'" fotoUsuario="'.$value["foto"].'" usuario="'.$value["usuario"].'"><i class="fa fa-times"></i></button> ':'';
              
                  echo '<td>'.$fechalogin.'</td>
                  
                  <td>
                      <div class="btn-group">
                          '.$boton1.' '.$boton2.'
                      </div>
                  </td>
                </tr>';
                  }
                    
                ?>
                
                </tbody>
                <tfoot class="thead-dark">
                <tr style="height:10px !important; font-size:0.75em !important;">
                    <th style="width:10px;">#</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Foto</th>
                    <th>Perfil</th>
                    <th>Estado</th>
                    <th>Ult. Login</th>
                    <th>Acción</th>
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
  
 <!-- ============= MODAL AGREGAR USUARIO ===================-->
 
  <div class="modal fade" id="modalAgregarusuario">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form role="form" method="POST" enctype="multipart/form-data">
      <!-- Modal Header -->
      <div class="modal-header py-0" style="background:#F7FE2E;">
   
            <h4 class="modal-title"><i class="fa fa-user"></i> Agregar usuario</h4>
        
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
              <input type="text" class="form-control input-lg" placeholder="Usuario" name="nuevoUsuario" id="nuevoUsuario" value="" required>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-lock"></i></span>
              </div>
              <input type="password" class="form-control input-lg" placeholder="Contraseña" name="nuevoPassword" required>
            </div>

            <div class="form-group mb-3">
              <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-users"></i></span>
                    <select class="form-control input-lg" name="nuevoPerfil">
                      <option value="">Seleccionar perfil</option>
                      <option value="Administrador">Administrador</option>
                      <option value="Especial">Especial</option>
                      <option value="Auxiliar">Auxiliar</option>
                      <option value="Visitante">Visitante</option>
                      <option value="Tecnico">Técnico</option>
                    </select>
              </div>
            </div>

		<div class="form-group">
            <label for="exampleInputFile">Subir Imagen</label>
            <div class="input-group">
                <div class="custom-file">
                        <input type="file" class="custom-file-input nuevaFoto" id="exampleInputFile" name="nuevaFoto">
                        <label class="custom-file-label" for="exampleInputFile">Seleccione Archivo</label>
                </div>
            </div>
			<img src="vistas/img/usuarios/default/anonymous.png" class="img-thumbnail previsualizar" width="80px" alt="">
        </div>

<!--			
            <div class="input-group mb-3">
              <div class="panel">Subir Foto</div>
            </div>
              <input type="file" class="nuevaFoto" placeholder="foto" name="nuevaFoto">
              <p class="help-block">peso Maximo de la foto 2mb.</p>
              <img src="vistas/img/usuarios/default/anonymous.png" class="img-thumbnail previsualizar" width="80px" alt="">
			  -->    
         </div>
        </div>
  
      </div>

      <!-- Modal footer -->
      <div class="modal-footer py-1" style="background:#F7FE2E;">
       
        <button type="button" class="btn btn-sm btn-primary float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Guardar</button>
      
      </div>
      
      <?php
        
        $crearUsuario=new ControladorUsuarios();
        $crearUsuario->ctrCrearusuario();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  



<!--============================= MODAL EDITAR USUARIO =============================
 === MODAL EDITAR USUARIO ======================================================*/ 
==============================================================================-->
  <div class="modal fade" id="modalEditarUsuario">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form role="form" method="POST" enctype="multipart/form-data">
      <!-- Modal Header -->
      <div class="modal-header py-0" style="background:#F7FE2E;">
   
            <h4 class="modal-title">Editar usuario</h4>
        
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
              <input type="text" class="form-control input-lg" value="" id="editarNombre" name="editarNombre" required>
            </div>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-key"></i></span>
              </div>
              <input type="text" class="form-control input-lg" value="" id="editarUsuario" name="editarUsuario" readonly>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-lock"></i></span>
              </div>
              <input type="password" class="form-control input-lg" placeholder="Nueva contraseña ó vacio para mantener la actual" name="editarPassword">
              <input type="hidden" id="passwordActual" name="passwordActual">
            </div>

            <div class="form-group mb-3">
              <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-users"></i></span>
                    <select class="form-control" name="editarPerfil">
                      <option value="" id="editarPerfil"></option>
                      <option value="Administrador">Administrador</option>
                      <option value="Especial">Especial</option>
                      <option value="Auxiliar">Auxiliar</option>
                      <option value="Visitante">Visitante</option>
                      <option value="Tecnico">Técnico</option>
                    </select>
              </div>
            </div>
                                
            <div class="input-group mb-3">
              <div class="panel">Subir Foto</div>
            </div>
              <input type="file" class="nuevaFoto" placeholder="foto" name="editarFoto">
              <p class="help-block">Peso Max. de la foto 2mb.</p>
              <img src="vistas/img/usuarios/default/anonymous.png" class="img-thumbnail previsualizarEditar" width="80px" alt="">
              <input type="hidden" id="fotoActual" name="fotoActual">
         </div>
        </div>
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer py-1" style="background:#F7FE2E;">
       
        <button type="button" class="btn btn-sm btn-primary float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Guardar Cambios</button>
      
      </div>
      
      <?php
        
        $editarUsuario=new ControladorUsuarios();
        $editarUsuario->ctrEditarUsuario();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  

<?php

  $borrarUsuario = new ControladorUsuarios();
  $borrarUsuario -> ctrBorrarUsuario();

?> 
