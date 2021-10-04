<?php
date_default_timezone_set("America/Mexico_City");
error_reporting(E_ALL^E_NOTICE);
?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1>Administrar Proveedores:&nbsp; 
                <small><i class="fa fa-male"></i></small>
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
         <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarProveedor"><i class="fa fa-plus-circle"></i> Agregar Proveedor
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
           
           <!--
            <div class="card-header">
              <h3 class="card-title">Tabla con todos los Proveedores</h3>
            </div>
            card-header -->
            
            <div class="card-body">
              <table class="table table-bordered compact table-hover table-striped dt-responsive TablaProveedores" cellspacing="0" width="100%">
                <thead class="thead-dark">
                <tr>
                    <th style="width:9px;">#</th>
                    <th>Razon Social</th>
                    <th>Rfc</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Contacto</th>
                    <th>Tel.Contacto</th>
                    <th>Estado</th>
                    <th>Accion</th>
                </tr>
                </thead>
                <tbody>
                
  <?php

          $item = null;
          $valor = null;

          $proveedores = ControladorProveedores::ctrMostrarProveedores($item, $valor);

          foreach ($proveedores as $key => $value) {
            
            echo '<tr>

                    <td>'.($key+1).'</td>

                    <td>'.$value["nombre"].'</td>

                    <td>'.$value["rfc"].'</td>

                    <td>'.$value["email"].'</td>

                    <td>'.$value["telefono"].'</td>

                    <td>'.$value["contacto"].'</td>

                    <td>'.$value["tel_contacto"].'</td>';
					
					  if($value["estatus"]==1){
						echo '<td><button class="btn btn-success btn-sm" idProveedor="'.$value["id"].'" estadoProveedor="1">Activado</button></td>';
					  }else{
						echo '<td><button class="btn btn-danger btn-sm" idProveedor="'.$value["id"].'" estadoProveedor="0">Desactivado</button></td>';
					  }					

                    echo '<td>

                      <div class="btn-group">

                        <button class="btn btn-info btn-sm btnVerProveedor " title="Visualizar" data-toggle="modal" data-target="#modalEditarProveedor" idProveedor="'.$value["id"].'"><i class="fa fa-eye"></i> </button>

                        <button class="btn btn-warning btn-sm btnEditarProveedor " title="Editar" data-toggle="modal" data-target="#modalEditarProveedor" idProveedor="'.$value["id"].'"><i class="fa fa-pencil"></i></button>';

                      if($_SESSION["perfil"] == "Administrador" ){

                          echo '<button class="btn btn-danger btn-sm btnEliminarProveedor" title="Borrar" idProveedor="'.$value["id"].'"><i class="fa fa-times"></i></button>';

                      }

                      echo '</div>  

                    </td>

                  </tr>';
          
            }

        ?>              
                
                </tbody>
                <tfoot class="thead-dark">
                <tr>
                    <th style="width:9px;">#</th>
                    <th>Razon Social</th>
                    <th>Rfc</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Contacto</th>
                    <th>Tel.Contacto</th>
                    <th>Estado</th>
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
          Mensajes:
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 <!-- === MODAL AGREGAR PROVEEDORES ==-->
 
 <div class="modal fade" id="modalAgregarProveedor" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
   
    <div class="modal-content">
    <form role="form" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal">
   
            <h4 class="modal-title">Agregar Proveedor</h4>
        
            <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      

      <!-- Modal body -->
      <div class="modal-body">
           
        <div class="box-body">
        </div>   

        <div class="card card-info">
         <div class="card-body">

          <div class="form-group">
            <label for="inputNombre">Razón Social</label>
            <input type="text" class="form-control" id="NuevoNombre" name="NuevoNombre" placeholder="" autofocus required>
          </div>

         <div class="form-row">
            <div class="form-group col-md-4">
              <label for="inputRfc">Rfc</label>
              <input type="text" class="form-control" id="NuevoRfc" name="NuevoRfc" required>
            </div>
                      
            <div class="form-group col-md-6">
              <label for="inputDireccion">Dirección</label>
              <input type="text" class="form-control" id="NuevaDireccion" name="NuevaDireccion" required>
            </div>
            <div class="form-group col-md-2">
              <label for="inputCp">C.P.</label>
              <input type="text" class="form-control" id="NuevoCp" name="NuevoCp" >
            </div>
          </div>     
                   
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="inputCiudad">Ciudad y Estado</label>
              <input type="text" class="form-control" id="NuevaCiudad" name="NuevaCiudad" required placeholder="">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail">Email</label>
              <input type="email" class="form-control" id="NuevoEmail" name="NuevoEmail" required placeholder="Email">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="inputTelefono1">Télefono</label>
              <input type="text" class="form-control" id="NuevoTelefono1" name="NuevoTelefono1" required placeholder="">
            </div>
            <div class="form-group col-md-8">
              <label for="inputContacto">Contacto</label>
              <input type="text" class="form-control" id="NuevoContacto"  name="NuevoContacto" required placeholder="Nombre de Contacto">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="inputTelefono2">Tel. de Contacto</label>
              <input type="text" class="form-control" id="NuevoTelefono2" name="NuevoTelefono2" required placeholder="" value="">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail2">Email Contacto</label>
              <input type="email" class="form-control" id="NuevoEmail2" name="NuevoEmail2" placeholder="Email">
            </div>
            <div class="form-group col-md-3">
              <label for="inputEstado">Estatus</label>
			  <select id="inputState" class="form-control" name="NuevoEstado" id="NuevoEstado">
				<option selected>Seleccione</option>
				<option value="1">Activado</option>
				<option value="0">Desactivado</option>
			  </select>			  
            </div>
          </div>                    
         
         </div>
        </div>
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal">
       
        <button type="button" class="btn btn-primary float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
      
      </div>
      
      <?php
        
        $crearProveedor=new ControladorProveedores();
        $crearProveedor->ctrCrearProveedor();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>    <!-- fin del modal -->


<!--================================ 
MODAL EDITAR PROVEEDORES 
===================================-->
 <div class="modal fade" id="modalEditarProveedor" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
   
    <div class="modal-content">
    <form role="form" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal">
   
            <h4 class="modal-title">Ver Proveedor</h4>
        
            <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      

      <!-- Modal body -->
      <div class="modal-body">
           
        <div class="box-body">
        </div>   

        <div class="card card-info">
         <div class="card-body">

          <div class="form-group">
            <label for="inputNombre">Razón Social</label>
            <input type="text" class="form-control" id="EditarNombre" name="EditarNombre" placeholder="Nombre y/o Razón Social" required>
			<input type="hidden" id="idProveedor" name="idProveedor">
          </div>

         <div class="form-row">
            <div class="form-group col-md-4">
              <label for="inputRfc">Rfc</label>
              <input type="text" class="form-control" id="EditarRfc" name="EditarRfc" required>
            </div>
                      
            <div class="form-group col-md-6">
              <label for="inputDireccion">Dirección</label>
              <input type="text" class="form-control" id="EditarDireccion" name="EditarDireccion" required>
            </div>
            <div class="form-group col-md-2">
              <label for="inputCp">C.P.</label>
              <input type="text" class="form-control" id="EditarCp" name="EditarCp" >
            </div>
          </div>     
                   
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="inputCiudad">Ciudad y Estado</label>
              <input type="text" class="form-control" id="EditarCiudad" name="EditarCiudad" required placeholder="">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail">Email</label>
              <input type="email" class="form-control" id="EditarEmail" name="EditarEmail" required placeholder="Email">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="inputTelefono1">Télefono</label>
              <input type="text" class="form-control" id="EditarTelefono1" name="EditarTelefono1" required placeholder="">
            </div>
            <div class="form-group col-md-8">
              <label for="inputContacto">Contacto</label>
              <input type="text" class="form-control" id="EditarContacto"  name="EditarContacto" required placeholder="Nombre de Contacto">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="inputTelefono2">Tel. de Contacto</label>
              <input type="text" class="form-control" id="EditarTelefono2" name="EditarTelefono2" required placeholder="" value="">
            </div>
            <div class="form-group col-md-6">
              <label for="inputEmail2">Email Contacto</label>
              <input type="email" class="form-control" id="EditarEmail2" name="EditarEmail2" placeholder="Email">
            </div>
            <div class="form-group col-md-3">
              <label for="inputEstado">Estatus</label>
			  <select class="form-control" name="EditarEstado" id="EditarEstado">
				<option value=1>Activado</option>
				<option value=0>Desactivado</option>
			  </select>			  
            </div>
          </div>                    
         
         </div>
        </div>
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal">
       
        <button type="button" class="btn btn-primary float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
		<button type="submit" class="btn btn-success" id="OcultarBoton"><i class="fa fa-save"></i> Guardar Cambios</button>
     
      </div>
      <?php
        
        $editarProveedor=new ControladorProveedores();
        $editarProveedor->ctrEditarProveedor();
		
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>    <!-- fin del modal -->


<?php

  $eliminarProveedor = new ControladorProveedores();
  $eliminarProveedor -> ctrEliminarProveedor();

?>