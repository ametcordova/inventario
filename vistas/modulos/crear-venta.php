<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1>Crear Venta  </h1>
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
		 <!--
            <button class="btn btn-primary"><i class="fa fa-dashboard"></i> Agregar Venta</button>
		 -->
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-tool" onclick="regresar()" title="a Inicio">
              <i class="fa fa-times"></i></button>			  
          </div>
        </div>
        <div class="card-body">
		
<section class="content">
  <div class="row">
  <!-- FORMULARIO  -->
    <div class="col-lg-5 col-xs-12">
       <div class="card card-success card-outline">
          <div class="card-header"></div>
		  
            <form role="form" action="" method="POST" class="formularioVenta">
             
              <div class="card-body">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                  </div>
                    <input type="text" class="form-control" id="nuevoVendedor" name="nuevoVendedor" value="<?=$_SESSION["nombre"];?>" readonly>
                    <input type="hidden" name="idVendedor" value="<?=$_SESSION["id"];?>" >
                </div>
            
                <div class="input-group mb-3">
                 <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-key"></i></span>
                 </div>
                 <?php
                    $item=null;
                    $valor=null;
                    $ventas=ControladorVentas::ctrMostrarVentas($item, $valor);
                    if(!$ventas){
                        echo '<input type="text" class="form-control" id="nuevaVenta" name="nuevaVenta" value="1" readonly>';
                    }else{
                        foreach($ventas as $key => $value){
                            
                        }
                        $codigo=$value["codigo"]+1;
                        echo '<input type="text" class="form-control" id="nuevaVenta" name="nuevaVenta" value="'.$codigo.'" readonly>';                        
                    }
                 ?>
                                 
                </div>
                                
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-users"></i></span>
                  </div>
                    <select class="form-control" id="agregarCliente" name="agregarCliente" placeholder="Agregar Cliente" required>
                       <option>Seleccionar Cliente</option>
                       <?php
                        $item=null;
                        $valor=null;
                        $clientes=ControladorClientes::ctrMostrarClientes($item, $valor);
                        foreach($clientes as $key=>$value){
                            echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                        }
                      ?>
                   
                    </select>
                     <span class="input-group-prepend"><button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalAgregarCliente" data-dismiss="modal"><i class="fa fa-plus-circle"></i> Agregar Cliente</button></span>
                 </div>
                                
                 <!-- ENTRADA PARA AGREGAR PRODUCTOS -->
                                
                 <div class="form-row nuevoProducto">
<!--                  
                    
                    <div class="col-sm-7" style="padding-right:0px;">
                     <div class="input-group mb-2">
                        <span class="input-group-prepend"><button type="button" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button></span>
                          <input type="text" class="form-control form-control-sm" name="agregarProducto" id="agregarProducto" placeholder="" readonly>
                      </div>     
                     </div>     
                                          
                      <div class="col-sm-2 mb-2">
                       <input type="number" class="form-control form-control-sm" name="nuevaCantidadProducto" id="nuevaCantidadProducto" min="1" placeholder="0" required>         
                      </div>
                                          
                      <div class="col-sm-3 mb-2" style="padding-left:0px">
                        <input type="number" class="form-control form-control-sm" name="nuevoPrecioProducto" id="nuevoPrecioProducto" placeholder="00000" readonly required>      
                      </div> -->
                      
                   </div>
                    <!-- FIN ENTRADA PARA AGREGAR PRODUCTOS -->   
                                    
                   <!-- ENTRADA PARA AGREGAR PRODUCTOS EN MOVIL-->
                    <button type="button" class="btn btn-default d-lg-none btnAgregarProducto">Agregar Productos</button>
                   <!-- FIN ENTRADA PARA AGREGAR PRODUCTOS EN MOVIL-->
                   
                   <hr>

                              <div class="form-row" style="background:#E6E6E6;">
                                <div class="col-2 col-lg-4 col-md-4 col-sm-4"></div>
                                <div class="col-5 col-lg-4 col-md-4 col-sm-4">
                                  <label class="" for="inlineFormInputGroup">Impuesto</label>
                                  <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                    </div>
                                    <input type="number" class="form-control" id="nuevoImpuestoVenta" name="nuevoImpuestoVenta" min="0" placeholder="0" required>
                                    
                                    <input type="hidden" id="nuevoPrecioImpuesto" name="nuevoPrecioImpuesto">
                                    <input type="hidden" id="nuevoPrecioNeto" name="nuevoPrecioNeto">
                                    <div class="input-group-text">%</div>
                                  </div>
                                </div>

                                <div class="col-5 col-lg-4 col-md-4 col-sm-4">
                                  <label class="" for="inlineFormInputGroup">Total</label>
                                  <div class="input-group mb-2">
                                   <div class="input-group-text">$</div>
                                    <div class="input-group-prepend">

                                    </div>
                                    <input type="text" class="form-control input-lg" id="nuevoTotalVenta" name="nuevoTotalVenta" total="" placeholder="00000" readonly>
                                    <input type="hidden" name="totalVenta" id="totalVenta">
                                  </div>
                                </div>

                              </div>

                             <hr>

                            <div class="form row">
                                 <div class="col-6">
                                    <select class="form-control" id="nuevometodoPago" name="nuevometodoPago" required>
                                      <option value="">Seleccione Método de pago</option>
                                      <option value="1" selected>Efectivo</option>
                                      <option value="2">Tarjeta de Crédito</option>
                                      <option value="3">Tarjeta de Débito</option>
                                      <option value="4">Donación</option>
                                    </select>
                                 </div>
                                  <div class="col-6">
                                      <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="nuevoCodTran" name="nuevoCodTran" placeholder="Código Transacción">
                                      </div>
                                 </div>
                            </div>  
                            <hr>                        
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary pull-right">Guardar venta <i class="fa fa-save"></i></button>
                            </div>  <!-- /.card-footer-->                  

          </div><!-- /.card-body -->		                		                
        </form>
                        
	</div>
  </div>
               
    <!-- TABLA DE PRODUCTOS  -->
  <div class="col-lg-7 d-none d-lg-block">
      <div class="card card-warning card-outline">
          <div class="card-header">
              <div class="card-body">
                  <table class="table table-bordered table-striped dt-responsive tablaVentas">
                <thead>
                  <tr>
                   <th style="width:9px">#</th>
                   <th style="width:9px">Imagen</th>
                   <th>Código</th>
                   <th>Descripción</th>
                   <th>Stock</th>
                   <th>Acción</th>
                 </tr> 
                </thead>
                
<!--                                
                <tbody>
               <tr>
                  <td>1</td>
                  <td><img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail" width="40px" alt="Foto Usuario"></td>
                  <td>00151</td>
                  <td>Martillo pata de cabra</td>
                  <td>20</td>
                  <td>
                      <div class="btn-group">
                          <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button>
                      </div>
                  </td>
                </tr>
                                
                </tbody>
--> 
             
                <tfoot>
                  <tr>
                   <th style="width:9px">#</th>
                   <th style="width:9px">Imagen</th>
                   <th>Código</th>
                   <th>Descripción</th>
                   <th>Stock</th>
                   <th>Acción</th>
                 </tr> 
               </tfoot>             
              </table>
              </div>
          </div>
                    
      </div>
  </div>
		        
</div>
</section>
  
</div><!-- /.card-body -->

<div class="card-footer">

</div> <!-- /.card-footer-->
</div> <!-- /.card -->
</section> <!-- /.content -->
</div> <!-- /.content-wrapper -->
  
 <!-- === MODAL AGREGAR CLIENTES ==-->
 
  <div class="modal fade" id="modalAgregarCliente" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form role="form" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal">
   
            <h4 class="modal-title">Agregar Cliente</h4>
        
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
              <input type="text" class="form-control input-lg" placeholder="Nombre" name="nuevoCliente" required>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-id-card"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="RFC" name="nuevoDocumento" required>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
              </div>
              <input type="email" class="form-control input-lg" placeholder="email" name="nuevoEmail" required>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Teléfono" name="nuevoTelefono" data-inputmask='"mask": "(999) 999-9999"' data-mask required>
            </div>

           <div class="input-group mb-3">
              <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Dirección" name="nuevaDireccion" required>
           </div>
                                                                                                        
           <div class="input-group mb-3">
              <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-calendar"></i></span>
              </div>
              <input type="text" class="form-control input-lg" placeholder="Fecha Nac. aaaa/mm/dd" name="nuevaFechaNacimiento" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask required>
			  <input type="hidden" name="scriptSource" value="crear-venta">
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
        
        $crearCliente=new ControladorClientes();
        $crearCliente->ctrCrearCliente();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>    <!-- fin del modal -->

