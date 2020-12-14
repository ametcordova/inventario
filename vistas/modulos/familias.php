<?php
  $tabla="usuarios";
  $module="familias";
  $campo="catalogo";
  $acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid p-1">
        <div class="row">
          <div class="col-sm-6">
            <h4>Administrar Familias:&nbsp; 
                <small><i class="fa fa-th"></i></small>
            </h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
              <li class="breadcrumb-item active"><a href="tablero">Tablero</a></li>
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
           <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarFamilia"><i class="fa fa-plus-circle"></i> Agregar Familia
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
            <div class="card-body">
              <table class="table table-bordered compact striped hover dt-responsive TablaFamilias" width="100%">
                <thead class="thead-dark">
                <tr>
                    <th style="width:10px;">#</th>
                    <th>Familia</th>
                    <th style="width:250px;">Ult. Mod.</th>
                    <th style="width:50px;">Acción</th>
                </tr>
                </thead>
                <tbody>
                
                <?php

                  $campo=null;
                  $valor=null;
                  $familias=ControladorFamilias::ctrMostrarFamilias($campo, $valor);
                
              foreach($familias as $key => $value){
                    $fechaAlta = date('d-m-Y', strtotime($value["ultmodificacion"]));

                echo '
                <tr>
                  <td>'.$value["id"].'</td>
                  <td>'.$value["familia"].'</td>
                  <td>'.$fechaAlta.'</td>
                  <td>
                      <div class="btn-group">';
                      if(getAccess($acceso, ACCESS_EDIT)){
                      echo '<button class="btn btn-warning btn-sm btnEditarFamilia" idFamilia="'.$value["id"].'" data-toggle="modal" data-target="#modalEditarFamilia"><i class="fa fa-pencil"></i></button>';
                      }
                      if(getAccess($acceso, ACCESS_DELETE)){
                      echo'<button class="btn btn-danger btn-sm btnEliminarFamilia" idFamilia="'.$value["id"].'"><i class="fa fa-times"></i></button>';
                      }
                      echo '</div>
                  </td>
                </tr>';

              }
              ?>
                
                </tbody>
                <tfoot class="thead-dark">
                <tr>
                    <th style="width:10px;">#</th>
                    <th>Familia</th>
                    <th style="width:250px;">Ult. Mod.</th>
                    <th style="width:50px;">Acción</th>
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
  
 <!-- === MODAL AGREGAR FAMILIA ==-->
 
  <div class="modal fade" id="modalAgregarFamilia" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form role="form" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal p-2">
   
            <h4 class="modal-title">Agregar Familia</h4>
        
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
              <input type="text" class="form-control input-lg" placeholder="Familia" name="nuevaFamilia" id="nuevaFamilia" value="" onkeyUp="mayuscula(this);" autofocus required >
              <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
            </div>
                                
         </div>
        </div>
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal p-2">
       
        <button type="button" class="btn btn-sm btn-primary float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Guardar</button>
      
      </div>
      
      <?php
        
        $crearFamilia=new ControladorFamilias();
        $crearFamilia->ctrCrearFamilia();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  


<!-- === MODAL EDITAR CATEGORIA ==-->
  <div class="modal fade" id="modalEditarFamilia" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form role="form" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal p-2">
   
            <h4 class="modal-title">Editar Familia</h4>
        
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
              <input type="text" class="form-control input-lg" value="" id="editarFamilia" name="editarFamilia" onkeyUp="mayuscula(this);" required>
               <input type="hidden" name="idFamilia" id="idFamilia">
	             <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
            </div>
            
         </div>
        </div>
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal p-2">
       
        <button type="button" class="btn btn-sm btn-primary float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Guardar Cambios</button>
      
      </div>
      
      <?php
        
        $editarFamilia=new ControladorFamilias();
        $editarFamilia->ctrEditarFamilia();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  
<script defer src="vistas/js/familias.js"></script>
<?php

  $borrarFamilia = new ControladorFamilias();
  $borrarFamilia -> ctrBorrarFamilia();

?> 
