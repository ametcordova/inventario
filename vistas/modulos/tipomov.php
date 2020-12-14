<?php
$tabla="usuarios";
$module="tiposmov";
$campo="catalogo";
$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);
?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-0 p-1">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h4>Tipos de Movimientos:&nbsp; 
                <small><i class="fa fa-cc"></i></small>
            </h4>
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
           <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarMovto"><i class="fa fa-plus-circle"></i> Agregar Movto.
          </button>
         <?php } ?>
        <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>
                
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Ocultar">
              <i class="fa fa-minus"></i></button>
          </div>
         </div>
        
        <div class="card-body">
        <div class="card">
        <!--
            <div class="card-header">
            </div> -->

            <div class="card-body table-responsive-sm">
             <!-- <table id="example" class="display" width="100%"></table> -->
              <table class="table table-bordered compact table-hover table-striped dt-responsive" id="TablaMovtos" width="100%">
                <thead class="thead-dark">
                <tr>
                    <th style="width:10px;">#</th>
                    <th style="width:550px;">Nombre Movto.</th>
                    <th style="width:300px;">Tipo Movto.</th>
                    <th style="width:35px;">Status</th>
                    <th >Acción</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot class="thead-dark">
                <tr>
                    <th style="width:10px;">#</th>
                    <th style="width:550px;">Nombre Movto.</th>
                    <th style="width:300px;">Tipo Movto.</th>
                    <th style="width:35px;">Status</th>
                    <th >Acción</th>
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
  
 <!-- === MODAL AGREGAR CAJA ==-->
 
  <div class="modal fade" id="modalAgregarMovto" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form role="form" name="formularioAgregarMovto" id="formularioAgregarMovto" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal p-2">
   
            <h4 class="modal-title">Agregar Movto.</h4>
        
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
                      <span class="input-group-text"><i class="fa fa-file-text"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Descripción Movto." name="nvoNombreMov" id="nvoNombreMov" value="" title="Nombre tipo de Movto." onkeyUp="mayuscula(this);" required 
              tabindex="1" >
              <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-cc"></i></span>
              </div>
                  <select class="form-control form-control-sm" name="nvaClaseMov" id="nvaClaseMov" title="Naturaleza del Movto." tabindex="2" required>
                    <option value="" selected>Seleccione Tipo</option>
                    <option value="E">Entrada</option>
                    <option value="S">Salida</option>
                  </select>			  
            </div>
			
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-unlock-alt"></i></span>
              </div>
			  <select class="form-control" name="nvoEstadoMov" id="nvoEstadoMov" title="status" required tabindex="3" >
				<option value="" selected>Seleccione</option>
				<option value="1">Activar</option>
				<option value="0">Desactivar</option>
			  </select>	
            </div>
                                                                
         </div>
        </div>
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal p-2">
       
        <button type="button" class="btn btn-primary float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-success" tabindex="4"><i class="fa fa-save"></i> Guardar</button>
      
      </div>
      
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  


<!-- === MODAL EDITAR CAJA ==-->
  <div class="modal fade" id="modalEditarMovto" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form name="formularioEditMovto" id="formularioEditMovto" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal p-2">
   
            <h4 class="modal-title">Editar Movto.</h4>
        
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
                      <span class="input-group-text"><i class="fa fa-file-text"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Descripción Movto." name="editNombreMov" id="editNombreMov" value="" title="Nombre tipo de Movto." onkeyUp="mayuscula(this);" required tabindex="1" >
              <input type="hidden" name="idMovto" id="idMovto">
              <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-cc"></i></span>
              </div>
                  <select class="form-control form-control-sm" name="editClaseMov" id="editClaseMov" title="Naturaleza del Movto." tabindex="2" readonly>
                    <option value="E">Entrada</option>
                    <option value="S">Salida</option>
                  </select>			  
            </div>
			
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-unlock-alt"></i></span>
              </div>
			  <select class="form-control" name="editEstadoMov" id="editEstadoMov" title="status" required tabindex="3" >
				<option value="" selected>Seleccione</option>
				<option value="1">Activar</option>
				<option value="0">Desactivar</option>
			  </select>	
            </div>
            
         </div> <!-- fin card-body -->

        </div>  <!-- fin card -->
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal p-2">
       
        <button type="button" class="btn btn-primary float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar Cambios</button>
      
      </div>
      
      <?php
        
        //$editarCaja=new ControladorCajas();
        //$editarCaja->ctrEditarCaja();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  
<script defer src="vistas/js/tipomov.js"></script>
<?php

  //$borrarCaja = new ControladorCajas();
  //$borrarCaja -> ctrBorrarCaja();

?> 
