<!-- Content Wrapper. Contains page content -->

<div id="app" class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid p-1">
        <div class="row">
          <div class="col-sm-6">
            <h5>Administrar Familias:&nbsp; 
                <small><i class="fa fa-th"></i></small>
            </h5>
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
    
    <section  class="content">

      <!-- Default box -->
      <div class="card">
        
         <div class="card-header pb-0">
        <!-- <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button> -->

            <div class="input-group mb-3 col-md-6 ">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-th"></i></span>
                </div>
                    <input type="text" class="form-control input-lg" v-model="name" name="nuevaFamilia" id="nuevaFamilia" value="" placeholder="Nombre familia" onkeyUp="mayuscula(this);" autofocus required >
                    <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
            <button class="btn btn-primary btn-sm ml-3" @click="addFamilia"><i class="fa fa-plus-circle"></i> Agregar Familia
            </button>
  
        </div>


          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Ocultar">
              <i class="fa fa-minus"></i></button>
          </div>
         </div>
        
        <div class="card-body">
        <div class="card">
            <div class="card-body">
              <table id="fam-table" class="table table-bordered compact striped hover dt-responsive" width="100%">
                <thead class="thead-dark">
                <tr>
                    <th style="width:10px;">#</th>
                    <th>Familia</th>
                    <th style="width:350px;">Ult. Mod.</th>
                    <!-- <th style="width:80px;">Acción</th>-->
                </tr>
                </thead>
                <tbody>

              <!-- <tr v-for="fam in familias">
                <td>{{fam.id}}</td>
                <td>{{fam.familia}}</td>
                <td>{{fam.ultmodificacion}}</td>
                
                <td>
                  <a @click="showDrawUser(fam.id)">
                    <i class="btn btn-warning btn-sm" aria-hidden="true" title="Ver Draw"><i class="fa fa-pencil"></i></i></i>
                  </a>
                  <button class="btn btn-danger btn-sm" title="Eliminar" @click="btnBorrar(fam.id,fam.familia)"><i class="fa fa-trash"></i></button>         

                </td>
              </tr>                 -->
<!--              

                <tr v-for="(datos,indice) of familias">                                
                    <td>{{datos.id}}</td>                                
                    <td>{{datos.familia}}</td>
                    <td>{{datos.ultmodificacion}}</td>
                    <td>
                      <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-secondary" title="Editar" @click="btnEditar(datos.id, datos.familia, datos.ultmodificacion)"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-sm btn-danger" title="Eliminar" @click="btnBorrar(datos.id)"><i class="fa fa-trash"></i></button>
                      </div>
                    </td>
                </tr>    
-->
                </tbody>
                <tfoot class="thead-dark">
                <tr>
                    <th style="width:10px;">#</th>
                    <th>Familia</th>
                    <th style="width:350px;">Ult. Mod.</th>
                    <!-- <th style="width:80px;">Acción</th>->
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
  

