<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-1">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6 p-0">
            <h1><small>Permisos a usuarios</small></h1>
          </div>
          <div class="col-sm-6 p-0">
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
        <div class="card-header">

    	 <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>          
    
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">

        <div class="col-sm-5 col-xs-5 input-group mb-2">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-user fa-lg"></i></span>
              </div>
                  <select class="form-control bg-info" name="nvoUsuarioPermiso" id="nvoUsuarioPermiso" title="Usuario" tabindex="" required>
                    <option value="0">Seleccione Usuario</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $idUsuarioCaja=ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
                    foreach($idUsuarioCaja as $key=>$value){
                          echo '<option value="'.$value["id"].'">'.$value["usuario"]." - ".$value["nombre"].'</option>';
                    }
                  ?>				  
                  </select>			  
            </div>
            <hr>

            <?php 
                include "administracion.php";  

                include "catalogos.php";  

                include "reportes.php";  

                include "configura.php";  

            ?>

            </div>          
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          Por cada módulo pulse Guardar si hace cambios en los permisos.
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  
  <!-- /.content-wrapper 
SELECT prod.id, prod.id_categoria, cat. categoria, prod.id_medida, med.medida, prod.codigointerno, prod.descripcion, prod.minimo,alm.cant, (alm.cant-prod.minimo) AS surtir, alm.precio_compra FROM productos prod
INNER JOIN categorias cat ON prod.id_categoria=cat.id
INNER JOIN medidas med ON prod.id_medida=med.id
LEFT JOIN alm_villah alm ON prod.id=alm.id_producto  
ORDER BY `surtir`
  -->
