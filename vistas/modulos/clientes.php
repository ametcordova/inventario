<?php
date_default_timezone_set("America/Mexico_City");
error_reporting(E_ALL^E_NOTICE);
?>
 <!-- Content Wrapper. Contains page content  https://www.youtube.com/watch?v=pOOmkg0cklE-->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-0 ml-2 p-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h2>Administrar Clientes:&nbsp; 
                <small><i class="fa fa-address-book"></i></small>
            </h2>
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
           <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarCliente"><i class="fa fa-plus-circle"></i> Agregar Clientes
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
            <div class="card-header p-1">
              <h3 class="card-title">Tabla de Clientes</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered compact table-hover table-striped dt-responsive DTClientes" cellspacing="0" width="100%">
                <thead class="thead-dark">
                <tr style="width:100%;">
                    <th style="width:1em;">#</th>
                    <th style="width:12em;">Nombre</th>
                    <th style="width:1em;">Rfc</th>
                    <th style="width:1em;">Email</th>
                    <th style="width:1em;">Teléfono</th>
                    <th style="width:14em;">Dirección</th>
                    <th style="width:1em;">Update</th>
                    <th style="width:1em;">Accion</th>
                </tr>
                </thead>
                <tbody style="font-size:13px;">
                
  <?php

          $item = null;
          $valor = null;

          $clientes = ControladorClientes::ctrMostrarClientes($item, $valor);

          foreach ($clientes as $key => $value) {
            
            //$fechaNacimiento = date('d-m-Y', strtotime($value["fecha_nacimiento"]));
            $fechaAlta = date('d-m-Y', strtotime($value["fecha"]));
              
            echo '<tr>

                    <td>'.$value["id"].'</td>

                    <td>'.$value["nombre"].'</td>

                    <td>'.$value["rfc"].'</td>

                    <td>'.$value["email"].'</td>

                    <td>'.$value["telefono"].'</td>

                    <td>'.$value["direccion"].'</td>

                    <td>'.$fechaAlta.'</td>

                    <td>

                      <div class="btn-group">
                          
                        <button class="btn btn-warning btn-sm btnEditarCliente" data-toggle="modal" data-target="#modalEditarCliente" idCliente="'.$value["id"].'"><i class="fa fa-pencil"></i></button>';

                      if($_SESSION["perfil"] == "Administrador" ){

                          echo '<button class="btn btn-danger btn-sm btnEliminarCliente" idCliente="'.$value["id"].'"><i class="fa fa-times"></i></button>';

                      }

                      echo '</div>  

                    </td>

                  </tr>';
          
            }

        ?>              
                
                </tbody>
                <tfoot class="thead-dark">
                 <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Rfc</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Update</th>
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
          Footer
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 <!-- === MODAL AGREGAR CLIENTES ==-->
 
  <div class="modal fade" id="modalAgregarCliente" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
   
    <div class="modal-content">
    <form role="form" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal m-2 p-1">
   
            <h5 class="modal-title p-0">Agregar Cliente</h5>
        
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
              <input type="text" class="form-control input-lg" placeholder="Nombre y/o Razón Social" name="nuevoCliente" title="Nombre o Razón social" required>
            </div>

            <div class="form-row">
              <div class="input-group mb-3 col-md-5">
                <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="RFC" name="nuevoRFC" title="RFC del cliente" pattern="[/^[A-ZÑ&]{3,4}\d{6}(?:[A-Z\d]{3})?$/]" required>
              </div>

              <div class="input-group mb-3 col-md-7">
                <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="CURP" name="nuevoCurp" title="CURP">
              </div>
            </div>

            <div class="form-row">
              <div class="input-group mb-3 col-md-8">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="Dirección" name="nuevaDireccion" title="Dirección" required>
            </div>
            <div class="input-group mb-3 col-md-4">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="No. Int. y ext." name="nuevoNumInt" title="Num. Int y Ext." required>
            </div>
           </div>                

           <div class="form-row">
           <div class="input-group mb-3 col-md-5">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="Colonia" name="nuevaColonia" title="Colonia" required>
            </div>

            <div class="input-group mb-3 col-md-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                </div>
                <input type="number" class="form-control input-lg" placeholder="Cod. Postal" name="nuevoCP" title="Código Postal" pattern="[0-9]{5}" required>
            </div>

              <div class="input-group mb-3 col-md-4">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-phone"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="Teléfono" name="nuevoTelefono" data-inputmask='"mask": "(999) 999-9999"' data-mask title="Teléfono" required>
              </div>
            </div>

            <div class="form-row">
            <div class="input-group mb-3 col-md-6">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="Ciudad" name="nuevaCiudad" title="Ciudad" required>
            </div>
                                                                                                          
              <div class="input-group mb-3 col-md-6">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-phone"></i></span>
                </div>
                <select class="form-control" name="nuevoEstado" id="nuevoEstado" data-toggle="tooltip" title="Estado">
                  <option selected>Seleccione Estado</option>
                            <?php
                              $item=null;
                              $valor=null;
                              $estado=ControladorTecnicos::ctrMostrarEstados($item, $valor);
                              foreach($estado as $key=>$value){
                                  echo '<option value="'.$value["idestado"].'">'.$value["nombreestado"].'</option>';
                              }
                            ?>
                </select>			  

              </div>
            </div>

            <div class="form-row">

            <div class="input-group mb-3 col-md-6">
              <div class="input-group-prepend">
                <label class="input-group-text" for="inputGroupSelect01"><i class="fa fa-envelope"></i></label>
              </div>
              <select class="custom-select" name="nuevoRegFiscal" id="nuevoRegFiscal" title="Régimen Fiscal" required>
                <option selected>Seleccione Régimen Fiscal</option>
              </select>
            </div>

              <div class="input-group mb-3 col-md-6">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                <input type="text" class="form-control input-lg" placeholder="Actividad Economica" name="nvaActividadEconomica" id="nvaActividadEconomica" title="Actividad Economica" required>
              </div>

            </div>

            <div class="form-row">

              <div class="input-group mb-1 col-md-4">
                <div class="input-group-prepend">
                  <label class="input-group-text" for="inputGroupSelect02"><i class="fa fa-envelope"></i></label>
                </div>
                <select class="custom-select" name="nuevaFormaPago" id="nuevaFormaPago" title="Forma de pago">
                  <option selected>Seleccione Forma de Pago</option>
                </select>
              </div>

              <div class="input-group mb-1 col-md-5">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                <input type="email" class="form-control input-lg" placeholder="email" name="nuevoEmail" title="correo eléctronico" required>
              </div>

              <div class="input-group mb-1 col-md-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                </div>
                <input type="date" class="form-control input-lg" placeholder="Fecha creación" name="nuevaFechaCreacion" title="Fecha de creación" >
              </div>
              
            </div>

         </div>
        </div>
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal p-1">
       
        <button type="button" class="btn btn-sm btn-primary float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Guardar</button>
      
      </div>
      
      <?php
        
        $crearCliente=new ControladorClientes();
        $crearCliente->ctrCrearCliente();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>    <!-- fin del modal -->


<!-- === =========MODAL EDITAR CLIENTES ================-->
 
  <div class="modal fade" id="modalEditarCliente" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form role="form" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal">
   
            <h4 class="modal-title">Editar Cliente</h4>
        
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
              <input type="text" class="form-control input-lg" placeholder="Nombre completo" name="EditarCliente" id="EditarCliente" required>
              <input type="hidden" id="idCliente" name="idCliente">
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-id-card"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Rfc" name="EditarDocumento" id="EditarDocumento" required>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
              </div>
              <input type="email" class="form-control input-lg" placeholder="email" name="EditarEmail" id="EditarEmail" required>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Teléfono" name="EditarTelefono" id="EditarTelefono"data-inputmask='"mask": "(999) 999-9999"' data-mask required>
            </div>

           <div class="input-group mb-3">
              <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Dirección" name="EditarDireccion" id="EditarDireccion" required>
           </div>
                                                                                                        
           <div class="input-group mb-3">
              <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-calendar"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Fecha Nac. aaaa/mm/dd" name="EditarFechaNacimiento" id="EditarFechaNacimiento" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask required>
           </div>
                                                                                           
         </div>
        </div>
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal">
       
        <button type="button" class="btn btn-primary float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar Cambios</button>
      
      </div>
      
      <?php
        
        $editarCliente = new ControladorClientes();
        $editarCliente -> ctrEditarCliente();      
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>    <!-- fin del modal -->


<?php

  $eliminarCliente = new ControladorClientes();
  $eliminarCliente -> ctrEliminarCliente();

?>
<script defer src="vistas/js/clientes.js?v=02082022"></script>