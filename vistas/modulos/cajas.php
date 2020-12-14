<?php
date_default_timezone_set("America/Mexico_City");
error_reporting(E_ALL^E_NOTICE);
$tabla="usuarios";
$module="cajaventas";
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
            <h4>Administrar Cajas:&nbsp; 
                <small><i class="fa fa-th"></i></small>
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
           <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarCaja"><i class="fa fa-plus-circle"></i> Agregar Caja
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

            <div class="card-body">
             <!-- <table id="example" class="display" width="100%"></table> -->
              <table class="table table-bordered compact table-hover table-striped dt-responsive" id="TablaCajas" width="100%">
                <thead class="thead-dark">
                <tr>
                    <th style="width:5%;">#</th>
                    <th>Caja</th>
                    <th>Usuario</th>
                    <th>Descripción</th>
                    <th>Ult. Modif.</th>
                    <th style="width:5%;">Status</th>
                    <th style="width:10%;">Acción</th>
                </tr>
                </thead>
                <tbody>
                                
                </tbody>
                <tfoot class="thead-dark">
                <tr>
                    <th style="width:5%;">#</th>
                    <th>Caja</th>
                    <th>Usuario</th>
                    <th>Descripción</th>
                    <th>Ult. Modif.</th>
                    <th style="width:5%;">Status</th>
                    <th style="width:10%;">Acción</th>
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
 
  <div class="modal fade" id="modalAgregarCaja" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form role="form" name="formularioAgregarCaja" id="formularioAgregarCaja" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal py-1 m-0">
   
            <h4 class="modal-title">Agregar Caja</h4>
        
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
                      <span class="input-group-text"><i class="fa fa fa-money"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Nombre Caja" name="nvaCaja" id="nvaCaja" value="" onkeyUp="mayuscula(this);" autofocus required tabindex="1"  >
              <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-file-text"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Descripción Caja" name="nvadescripcionCaja" id="nvadescripcionCaja" value="" onkeyUp="mayuscula(this);" required tabindex="2" >
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-user"></i></span>
              </div>
                  <select class="form-control" name="nvousuarioCaja" id="nvousuarioCaja" title="Usuario" tabindex="3" required>
                    <option value="">Seleccione Usuario</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $idUsuarioCaja=ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
                    foreach($idUsuarioCaja as $key=>$value){
                          echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                    }
                  ?>				  
                  </select>			  
            </div>
			
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-unlock-alt"></i></span>
              </div>
			  <select class="form-control" name="nvoestadoCaja" id="nvoestadoCaja" required tabindex="4" >
				<option value="" selected>Seleccione</option>
				<option value="1">Activar</option>
				<option value="0">Desactivar</option>
			  </select>	
            </div>
                                                                
         </div>
        </div>
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal py-2 m-2">
       
        <button type="button" class="btn btn-primary btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Guardar</button>
      
      </div>
      
      <?php
        
        //$crearCaja=new ControladorCajas();
        //$crearCaja->ctrCrearCaja();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  


<!-- === MODAL EDITAR CAJA ==-->
  <div class="modal fade" id="modalEditarCaja" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form name="formularioEditCaja" id="formularioEditCaja" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal py-1 m-0">
   
            <h4 class="modal-title">Editar Caja</h4>
        
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
                      <span class="input-group-text"><i class="fa fa-th"></i></span>
              </div>
              <input type="text" class="form-control input-lg" value="" id="editarCaja" name="editarCaja" readonly>
              <input type="hidden" name="idCaja" id="idCaja">
	          <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
            </div>
            
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-file-text"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Descripción Caja" name="editardescripcionCaja" id="editardescripcionCaja" value="" onkeyUp="mayuscula(this);" required >
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-user"></i></span>
              </div>
                  <select class="form-control form-control-sm" name="editarusuarioCaja" title="Usuario" tabindex="4" required>
				          <option id="editarusuarioCaja" value=""></option>
                  <?php
                    $item=null;
                    $valor=null;
                    $idUsuarioCaja=ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
                    foreach($idUsuarioCaja as $key=>$value){
						          echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                    }
                  ?>				  
                  </select>			  
            </div>


            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-unlock-alt"></i></span>
              </div>
			  <select class="form-control" name="editarestadoCaja" id="editarestadoCaja" required>
				<option value=1>Activar</option>
				<option value=0>Desactivar</option>
			  </select>	
            </div>
            
            
         </div> <!-- fin card-body -->

        </div>  <!-- fin card -->
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal py-2 m-2">
       
        <button type="button" class="btn btn-primary btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-sm  btn-success"><i class="fa fa-save"></i> Guardar Cambios</button>
      
      </div>
      
      <?php
        
        //$editarCaja=new ControladorCajas();
        //$editarCaja->ctrEditarCaja();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  

<?php

  //$borrarCaja = new ControladorCajas();
  //$borrarCaja -> ctrBorrarCaja();

?> 
