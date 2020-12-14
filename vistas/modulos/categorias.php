<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1>Administrar categorías:&nbsp; 
                <small><i class="fa fa-th"></i></small>
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
           <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarCategoria"><i class="fa fa-plus-circle"></i> Agregar Categoría
          </button>
        <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>
                
        <button class="btn btn-default btn-sm" id="btnVer" type="button"><i class="fa fa-eye"></i> Visualizar</button>        

          <!--<h2 class="card-title">Control de Usuarios</h2> -->
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Ocultar">
              <i class="fa fa-minus"></i></button>
          </div>
         </div>
        
        <div class="card-body">
        <div class="card">
            <div class="card-header">
              <h3 class="card-title">Tabla con todas los Categorías</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered table-striped dt-responsive TablaCategorias" width="100%">
                <thead>
                <tr>
                    <th style="width:10px;">#</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                
                <?php
                if(isset($_GET["categoria"])){
                  $campo=null;
                  $valor=null;
                  $categorias=ControladorCategorias::ctrMostrarCategorias($campo, $valor);
                
                  foreach($categorias as $key => $value){

                echo '
                <tr>
                  <td>'.($key+1).'</td>
                  <td>'.$value["categoria"].'</td>
                  <td>
                      <div class="btn-group">
                          <button class="btn btn-warning btn-sm btnEditarCategoria" idCategoria="'.$value["id"].'" data-toggle="modal" data-target="#modalEditarCategoria"><i class="fa fa-pencil"></i></button>
                          
                          <button class="btn btn-danger btn-sm btnEliminarCategoria"  idCategoria="'.$value["id"].'"><i class="fa fa-times"></i></button>
                      </div>
                  </td>
                </tr>
                      
                ';
                  }
                }
                ?>
                
                </tbody>
                <tfoot>
                <tr>
                    <th style="width:10px;">#</th>
                    <th>Categoría</th>
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
  
 <!-- === MODAL AGREGAR CATEGORÍA ==-->
 
  <div class="modal fade" id="modalAgregarCategoria" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form role="form" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal">
   
            <h4 class="modal-title">Agregar Categoría</h4>
        
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
              <input type="text" class="form-control input-lg" placeholder="Categoría" name="nuevaCategoria" id="nuevaCategoria" value="" onkeyUp="mayuscula(this);" required >
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
        
        $crearCategoria=new ControladorCategorias();
        $crearCategoria->ctrCrearCategoria();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  


<!-- === MODAL EDITAR CATEGORIA ==-->
  <div class="modal fade" id="modalEditarCategoria" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form role="form" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal">
   
            <h4 class="modal-title">Editar Categoría</h4>
        
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
              <input type="text" class="form-control input-lg" value="" id="editarCategoria" name="editarCategoria" required>
              <input type="hidden" name="idCategoria" id="idCategoria">
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
        
        $editarCategoria=new ControladorCategorias();
        $editarCategoria->ctrEditarCategoria();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  

<?php

  $borrarCategoria = new ControladorCategorias();
  $borrarCategoria -> ctrBorrarCategoria();

?> 
