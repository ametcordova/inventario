<?php
$tabla="usuarios";
$module="pproductos";
$campo="catalogo";
$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);    
?>
<!DOCTYPE html>
<html lang="en">
<style>
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
  background:lightcoral ;
  color: black!important;
  border-radius: 4px;
  border: 1px solid #828282;
}
 
.dataTables_wrapper .dataTables_paginate .paginate_button:active {
  background: none;
  color: black!important;
}
</style>

<body>
  
</body>
</html>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h3>Administrar Productos:&nbsp; 
                <small><i class="fa fa-tag"></i></small>
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
           <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarProducto" id="agregarProd">
             <i class="fa fa-plus-circle"></i> Agregar Productos
          </button>
        <?php } ?>
            <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Ocultar"><i class="fa fa-minus"></i></button>
                 <button type="button" class="btn btn-tool" onclick="regresar()" title="a Inicio"><i class="fa fa-times"></i></button>
              </div>

        </div>
        
        <div class="card-body">
<div class="card">
            <div class="card-body">
              <table class="table table-bordered table-striped dt-responsive tablaProductos" width="100%">
                <thead>
                  <tr>
                   <th style="width:10px">#</th>
                   <th>SKU</th>
                   <th>Cód.Int.</th>
                   <th>Descripción</th>
                   <th>Categoría</th>
                   <th>Stock</th>
                   <th>Medida</th>
                   <th>Agregado</th>                   
                   <th>Accion</th>
                 </tr> 
                </thead>
                
                <tbody>
                
                </tbody>
 
                <tfoot>
                  <tr>
                   <th style="width:10px">#</th>
                   <th>SKU</th>
                   <th>Cód.Int.</th>
                   <th>Descripción</th>
                   <th>Categoría</th>
                   <th>Stock</th>
                   <th>Medida</th>
                   <th>Agregado</th>
                   <th>Accion</th>
                 </tr> 
               </tfoot>

              </table>
              <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->            
        </div>
        <!-- /.card-body -->
        
        <!--
        <div class="card-footer">
          Footer
        </div>
         /.card-footer-->
        
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 <!-- === MODAL AGREGAR PRODUCTOS ==-->
 
  <div class="modal fade" id="modalAgregarProducto" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg p-0 my-0">
   
    <div class="modal-content">
    <form role="form" method="POST" enctype="multipart/form-data" >
      <!-- Modal Header -->
      <div class="modal-header p-2">
   
        <h5 class="modal-title">Agregar Producto
			    <small><i class="fa fa-tag"></i></small>
			  </h5>
        
            <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      

      <!-- Modal body -->
      <div class="modal-body">
           
        <div class="box-body">
        </div>   

        <div class="card card-info">
         <div class="card-body">

        <!-- ENTRADA PARA SELECCIONAR CATEGORÍA Y UNIDA DE MEDIDA-->
		<div class="form-row">
            <div class="form-group col-sm-5 mb-4">
               <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-th"></i></span> 
                <select class="form-control input-lg" id="nuevaCategoria" name="nuevaCategoria" tabindex="1" required autofocus>
                  <option value="">Seleccionar categoría</option>

                  <?php
                    $item=null;
                    $valor=null;
                    $categorias=ControladorCategorias::ctrMostrarCategorias($item, $valor);
                    foreach($categorias as $key=>$value){
                        echo '<option value="'.$value["id"].'">'.$value["categoria"].'</option>';
                    }
                  ?>
                  
                </select>
              </div>
            </div>     

            <div class="form-group col-sm-4 mb-4">
               <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-tachometer"></i></span> 
                <select class="form-control input-lg" id="nuevaMedida" name="nuevaMedida" tabindex="2" required>
                  <option value="">Selec. U. de Medida</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $medidas=ControladorMedidas::ctrMostrarMedidas($item, $valor);
                    foreach($medidas as $key=>$value){
                        echo '<option value="'.$value["id"].'">'.$value["medida"].'</option>';
                    }
                  ?>
                </select>
              </div>
            </div>    
           <div class="form-group col-sm-3 mb-4">
            <div class="input-group">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-code"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="" name="nuevoCodigo" id="nuevoCodigo" readonly required>
			  <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
            </div>
            </div>
        </div>     <!-- fin de CATEGORIA Y UNIDAD DE MEDIDA -->
                          
		 <div class="form-row">  
		 
			<div class="form-group col-sm-4 mb-4">
            <div class="input-group">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-barcode"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Ingresar Código" name="nuevoCodInterno" id="nuevoCodInterno" tabindex="3" required>
            </div>
            </div>
		 
            <div class="form-group col-sm-8 mb-4">
            <div class="input-group">
              <div class="input-group-prepend">
                 <span class="input-group-text"><i class="fa fa-tag"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Ingresar Descripción" name="nuevaDescripcion" id="nuevaDescripcion" tabindex="4" onkeyUp="mayuscula(this);" required>
            </div>
            </div>
        </div>
            
			
        <div class="form-row">    
            <div class="form-group col-sm-3 mb-4">
            <div class="input-group">
              <div class="input-group-prepend">
                 <span class="input-group-text"><i class="fa fa-check"></i></span>
              </div>
              <input type="number" class="form-control input-lg" placeholder="Stock" name="nuevoStock" id="nuevoStock" min="0" step="any" tabindex="5">
            </div>
            </div>

            <div class="form-group col-sm-3 mb-4">
            <div class="input-group">
              <div class="input-group-prepend">
                 <span class="input-group-text"><i class="fa fa-minus"></i></span>
              </div>
              <input type="number" class="form-control input-lg" placeholder="Mínimo" name="nuevoMinimo" id="nuevoMinimo" min="0" step="any" title="Mínimo" required tabindex="6">
            </div>
            </div>
			
  	        <div class="form-group col-sm-6 col-xs-12 mb-4">
                <div class="input-group">
                  <div class="input-group-prepend">
                     <span class="input-group-text"><i class="fa fa-tags"></i></span>
                  </div>
                  <input type="number" class="form-control input-lg" placeholder="SKU" name="nuevosku" id="nuevosku" min="0" step="any" value="" tabindex="7">
                </div>
            </div>
		
        </div>
        
            <!--Porcentaje no se guarda en la BD -->
             <div class="form-row">
                 <div class="col-sm-3 col-xs-3">
                     <div class="form-group">
                        <div class="checkbox icheck">
                         <label>
                             <input type="checkbox" class="minimal flat-red porcentaje" >
                             utilizar porcentaje
                         </label>
                         </div>
                     </div>
                 </div>

                 <div class="col-sm-3 col-xs-3 mb-4" style="padding-left:4px">
                     <div class="input-group">
                         <input type="number" class="form-control nuevoPorcentaje" min="0" value="40" required tabindex="8">
                         <span class="input-group-text"><i class="fa fa-percent"></i></span>
                     </div>
                 </div>
                 
                <div class="form-group col-sm-3 col-xs-12 mb-4">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-arrow-up"></i></span>
                    </div>
                    <input type="number" class="form-control input-lg" placeholder="Precio de Compra" name="nuevoPrecioCompra" id="nuevoPrecioCompra" min="0" step="any" value=1 required tabindex="" readonly>
                  </div>
                </div>
					 
		 
                <div class="form-group col-sm-3 col-xs-12">
                 <div class="input-group">
                  <div class="input-group-prepend">
                     <span class="input-group-text"><i class="fa fa-arrow-down"></i></span>
                  </div>
                  <input type="number" class="form-control input-lg" placeholder="Precio de Venta" name="nuevoPrecioVenta" id="nuevoPrecioVenta" min="0" value=1 required tabindex="" readonly>
                 </div>
                 </div>
                 
             </div>    <!-- fin del form-row-->

             <div class="form-row">
              <div class="col-sm-4 col-xs-4">
                  <div class="form-group">
                    <div class="checkbox icheck">
                      <label>
                          <input type="checkbox" name="nvoconseries" value="1" class="minimal flat-red conseries" >
                          Capturar No. de series?
                      </label>
                      </div>
                  </div>
              </div>

              <div class="col-md-2 col-sm-2 col-xs-4">
                  <div class="form-group">
                    <div class="checkbox icheck">
                      <label title="Es material para Fibra óptica?">
                          <input type="checkbox" name="nvoFO" value="1" class="minimal flat-red conseries" title="Es material para Fibra óptica?">
                          Para F.O.?
                      </label>
                      </div>
                  </div>
              </div>

              <div class="col-md-3 col-sm-3 col-xs-4">
                  <div class="form-group">
                    <div class="checkbox icheck">
                      <label title="Es material para Cobre?">
                          <input type="checkbox" name="nvoCobre" value="1" class="minimal flat-red conseries" >
                          Para Cobre?
                      </label>
                      </div>
                  </div>
              </div>

              <div class="col-md-3 col-sm-3 col-xs-4">
                  <div class="form-group">
                    <div class="checkbox icheck">
                      <label title="Es material para construcción?">
                          <input type="checkbox" name="nvoConstruccion" value="1" class="minimal flat-red conseries" >
                          Para Construcción?
                      </label>
                      </div>
                  </div>
              </div>

             </div>    <!-- fin del form-row-->        

                                    
            <div class="form-group">
                <label for="exampleInputFile">Subir Imagen</label>
                <p class="help-block">Peso Máximo de la foto 2mb.</p>
                <div class="input-group">
                    <div class="custom-file">
                            <input type="file" class="custom-file-input nuevaImagen" id="exampleInputFile" name="nuevaImagen" tabindex="10">
                            <label class="custom-file-label" for="exampleInputFile">Seleccione Archivo</label>
                    </div>
                </div>
                <img src="vistas/img/productos/default/default.jpg" class="img-thumbnail previsualizar" width="100px" alt="">
            </div>

            <div class="form-row">
              <div class="form-group col-md-6 col-sm-6 col-xs-12 mb-4">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-bell"></i></span>
                    </div>
                      <select class="form-control input-lg"  name="nuevoEstatus" id="nuevoEstatus" title="Activar/Desactivar" required>
                        <option value=0>Activado</option>
                        <option value=1>Desactivado</option>
                      </select>
                  </div>
              </div>
              <div class="form-group col-md-6 col-sm-6 col-xs-12 mb-4">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-key"></i></span>
                    </div>
                      <select class="form-control input-lg"  name="nuevoListar" id="nuevoListar" title="Mostrar en el F200" required>
                        <option value=1>Si</option>
                        <option value=0>No</option>
                      </select>
                  </div>
              </div>

            </div>  <!-- fin del form-row-->                
    
         </div>
        </div>
  
      </div>

      <!-- Modal footer -->
      <div class="modal-footer p-2">
       
        <button type="button" class="btn btn-primary btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <!--<button type="button" class="btn btn-primary float-left" id="checar">checar</button>-->
        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Guardar Producto</button>
      
      </div>
      
      <?php
        $crearProducto=new ControladorProductos();
        $crearProducto->ctrCrearProducto();
      ?>
     </form>
    </div> <!-- fin del modal-content <img src="vistas/img/usuarios/default/default.png" -->
  </div>
</div>  

<!-- ========================================================================-->
<!-- ================== MODAL EDITAR PRODUCTOS ===============================-->
 <!-- ========================================================================-->
  <div class="modal fade" id="modalEditarProducto" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg p-0 my-0">
   
    <div class="modal-content">
    <form role="form" method="POST" enctype="multipart/form-data">
      <!-- Modal Header -->
      <div class="modal-header p-2">
   
        <h5 class="modal-title">Editar Producto
			    <small><i class="fa fa-tag"></i></small>
			  </h5>
        
            <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      

      <!-- Modal body -->
      <div class="modal-body">
           
        <div class="box-body">
        </div>   

        <div class="card card-info">
         <div class="card-body">

        <!-- ENTRADA PARA SELECCIONAR CATEGORÍA Y UNIDAD DE MEDIDA-->
		<div class="form-row">
            <div class="form-group col-sm-5 mb-4">
               <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-th"></i></span> 
                <select class="form-control input-lg"  name="editarCategoria" readonly>
                  <option id="editarCategoria"></option>
                </select>
              </div>
            </div>     

            <div class="form-group col-sm-4 mb-4">
               <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-tachometer"></i></span> 
                <select class="form-control input-lg"  name="editarMedida" required>
                  <option id="editarMedida"></option>
                  <?php
                    $item=null;
                    $valor=null;
                    $medidas=ControladorMedidas::ctrMostrarMedidas($item, $valor);
                    foreach($medidas as $key=>$value){
                        echo '<option value="'.$value["id"].'">'.$value["medida"].'</option>';
                    }
                  ?>
                </select>
              </div>
            </div> 
			<div class="form-group col-sm-3 mb-4">
            <div class="input-group">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-code"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="" name="editarCodigo" id="editarCodigo" readonly>
			  
			  <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
			  <input type="hidden"  name="editarIdProducto" id="editarIdProducto" value="">
			  
            </div>
            </div>
        </div>     <!-- fin de CATEGORIA Y UNIDAD DE MEDIDA -->
                          
		 <div class="form-row"> 

			<div class="form-group col-sm-4 mb-4">
            <div class="input-group">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-barcode"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Ingresar Código" name="editarCodInterno" id="editarCodInterno" title="Codigo Interno" required>
            </div>
            </div>
		 
             <div class="form-group col-sm-8 mb-4">
            <div class="input-group">
              <div class="input-group-prepend">
                 <span class="input-group-text"><i class="fa fa-tag"></i></span>
              </div>
              <input type="text" class="form-control input-lg" name="editarDescripcion" id="editarDescripcion" onkeyUp="mayuscula(this);" title="Descripción del producto" required>
            </div>
            </div>
        </div>
            
			
        <div class="form-row">    
            <div class="form-group col-sm-3 mb-4">
            <div class="input-group">
              <div class="input-group-prepend">
                 <span class="input-group-text"><i class="fa fa-check"></i></span>
              </div>
              <input type="number" class="form-control input-lg" placeholder="Stock" name="editarStock" id="editarStock" min="0" step="any" title="Stock">
            </div>
            </div>

            <div class="form-group col-sm-3 mb-4">
            <div class="input-group">
              <div class="input-group-prepend">
                 <span class="input-group-text"><i class="fa fa-minus"></i></span>
              </div>
              <input type="number" class="form-control input-lg" placeholder="Mínimo" name="editarMinimo" id="editarMinimo" min="0" step="any" title="Mínimo" required>
            </div>
            </div>

  	        <div class="form-group col-sm-6 col-xs-12 mb-4">
                <div class="input-group">
                  <div class="input-group-prepend">
                     <span class="input-group-text"><i class="fa fa-tags"></i></span>
                  </div>
                  <input type="number" class="form-control input-lg" placeholder="SKU" name="editarsku" id="editarsku" min="0" step="any" value="" tabindex="7" title="SKU">
                </div>
            </div>

        </div> <!-- fin del form-row-->        

             <div class="form-row">

                 <div class="col-sm-3 col-xs-3">
                     <div class="form-group">
                        <div class="checkbox icheck">
                         <label>
                             <input type="checkbox" class="minimal flat-red porcentaje" checked>
                             utilizar porcentaje
                         </label>
                         </div>
                     </div>
                 </div>
                
                <!--Porcentaje no se guarda en la BD -->
                 <div class="col-sm-3 col-xs-3 mb-4" style="padding-left:4px">
                     <div class="input-group">
                         <input type="number" class="form-control nuevoPorcentaje" min="0" value="40" required>
                         <span class="input-group-text"><i class="fa fa-percent"></i></span>
                     </div>
                 </div>
     
                <div class="form-group col-sm-3 col-xs-12 mb-4">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-arrow-up"></i></span>
                    </div>
                    <input type="number" class="form-control input-lg" name="editarPrecioCompra" id="editarPrecioCompra" min="0" step="any" readonly>
                  </div>
                </div>
                 
                <div class="form-group col-sm-3 col-xs-12">
                 <div class="input-group">
                  <div class="input-group-prepend">
                     <span class="input-group-text"><i class="fa fa-arrow-down"></i></span>
                  </div>
                  <input type="number" class="form-control input-lg" name="editarPrecioVenta" id="editarPrecioVenta" min="0" step="any" readonly>
                 </div>
                </div>

             </div>    <!-- fin del form-row-->        

             
             <div class="form-row">
              <div class="col-md-4 col-sm-4 col-xs-4">
                  <div class="form-group">
                    <div class="checkbox icheck">
                      <label>
                          <input type="checkbox" name="editconseries" value="1" class="minimal flat-red conseries" >
                          Capturar No. de series?
                      </label>
                      </div>
                  </div>
              </div>
              
              <div class="col-md-2 col-sm-2 col-xs-4">
                  <div class="form-group">
                    <div class="checkbox icheck">
                      <label title="Es material para Fibra óptica?">
                          <input type="checkbox" name="editaFO" value="1" class="minimal flat-red editaFO" title="Es material para Fibra óptica?">
                          Para F.O.?
                      </label>
                      </div>
                  </div>
              </div>

              <div class="col-md-3 col-sm-3 col-xs-4">
                  <div class="form-group">
                    <div class="checkbox icheck">
                      <label title="Es material para Cobre?">
                          <input type="checkbox" name="editaCobre" value="1" class="minimal flat-red editaCobre" >
                          Para Cobre?
                      </label>
                      </div>
                  </div>
              </div>

              <div class="col-md-3 col-sm-3 col-xs-4">
                  <div class="form-group">
                    <div class="checkbox icheck">
                      <label title="Es material para construcción?">
                          <input type="checkbox" name="editaConstruccion" value="1" class="minimal flat-red editaConstruccion" >
                          Para Construcción?
                      </label>
                      </div>
                  </div>
              </div>

             </div>    <!-- fin del form-row-->       
            
                                    
            <div class="form-group">
                <label for="exampleInputFile">Subir Imagen</label>
                <p class="help-block">Peso máximo de la foto 2mb.</p>
                <div class="input-group">
                    <div class="custom-file">
                            <input type="file" class="custom-file-input nuevaImagen" id="ImagenInputFile" name="editarImagen">
                            <label class="custom-file-label" for="ImagenInputFile">Seleccione Archivo</label>
                    </div>
                </div>
                <img src="vistas/img/productos/default/default.png" class="img-thumbnail previsualizar" width="100px" alt="">
                <input type="hidden" name="imagenActual" id="imagenActual">
            </div>
    
            <div class="form-row">
              <div class="form-group col-md-6 col-sm-6 col-xs-12 mb-4">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-bell"></i></span>
                    </div>
                      <select class="form-control input-lg"  name="editarEstatus" id="editarEstatus" title="Activar/Desactivar" required>
                        <option value=0>Activado</option>
                        <option value=1>Desactivado</option>
                      </select>
                  </div>
              </div>
              <div class="form-group col-md-6 col-sm-6 col-xs-12 mb-4">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-key"></i></span>
                    </div>
                      <select class="form-control input-lg"  name="editarListar" id="editarListar" title="Mostrar en el F200" required>
                        <option value=1>Si</option>
                        <option value=0>No</option>
                      </select>
                  </div>
              </div>

            </div>  <!-- fin del form-row-->       

         </div>
        </div>
  
      </div>

      <!-- Modal footer -->
      <div class="modal-footer p-2">
       
        <button type="button" class="btn btn-primary float-left btn-sm" data-dismiss="modal">Salir</button>
        <button type="submit" class="btn btn-success btn-sm">Guardar Cambios</button>
      
      </div>
      
      <?php
        $editarProducto=new ControladorProductos();
        $editarProducto->ctrEditarProducto();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div> 

<?php

  $EliminarProducto = new ControladorProductos();
  $EliminarProducto -> ctrEliminarProducto();

?> 

<!-- Modal para visualizar imagen del producto -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="ModalCenterTitle"></h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
		<img src="" id="imagen-modal" class="img-fluid imagen" alt="">
        <!--<img src="vistas/img/productos/default/default.png" id="imagen-modal">-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>