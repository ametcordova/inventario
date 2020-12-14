<script defer src="vistas/js/reportedeventas.js"></script>
<?php
  $fechaHoy=date("d/m/Y");
//Activamos el almacenamiento en el buffer
ob_start();
$tabla="usuarios";
$module="rvtas";
$campo="reportes";
$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);

?>
<style>
.select2 {
  font-family: 'FontAwesome';
}

.select2-selection--multiple{
  font-size: 12px;
}

.select2-results__options{
        font-size:13px !important;
	}	

</style>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header pt-2 pb-0 m-1">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-3">
            <h3>Reporte de Ventas</h3>
          </div>
          <div class="col-sm-4">

          <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>

          <button class="btn btn-primary btn-sm" id="" onclick="location.reload()" type="button" title="Recargar página"><i class="fa fa-refresh"></i></button>

          </div>
		  
          <div class="col-sm-5">
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
        <div class="card-header m-1">
          <!--<div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
		          	<button type="button" class="btn btn-tool" onclick="regresar()" title="a Inicio">
              <i class="fa fa-times"></i></button>			  
          </div>
           -->
          <div class="row">
           
            <div class="form-group col-md-2">
                <select class="form-control form-control-sm" id="almaDeVenta" name="almaDeVenta" tabindex="1" title='Almacen' required>
				          <option value="" selected>Seleccione Almacen</option>
                 <?php
                   $item=null;
                    $valor=null;
                    $almacenes=ControladorAlmacenes::ctrMostrarAlmacenes($item, $valor);
                    foreach($almacenes as $key=>$value){
                      if($value["id"]=="1"){
                        echo '<option selected value="'.$value["id"].'">'.$value["nombre"].'</option>';
                      }else{
                        echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                      }
                    }                    
                  ?>								
				       </select>
            </div>

            <div class="form-group col-md-3">
                <select multiple="multiple[]" class="form-control form-control-sm familyName" id="selFamilia" name="selFamilia" tabindex="2" title='Familias'>
                 <?php
                   $item=null;
                    $valor=null;
                    $familiaInv=ControladorFamilias::ctrMostrarFamilias($item, $valor);
                    foreach($familiaInv as $key=>$value){
                        echo '<option onlyslave="True" value="'.$value["id"].'">'.$value["familia"].'</option>';
                    }                    
                  ?>								
				        </select>
            </div>
			
            <div class="col- mr-2 form-check m-0 p-0">
              <input class="form-check-input checkbox-inline" type="checkbox" value="1" name="SelectTodos" id="SelectTodos">
              <label class="form-check-label" for="all" title='Seleccionar todas las familias'>
               <i class="fa fa-check" aria-hidden="true"></i>
              </label>
            </div>	
			

            <div class="form-group col-md-2">
              <select class="form-control form-control-sm" id="selCaja" name="selCaja" tabindex="3">
                <option value=0 selected>Sel. Caja</option>
                    <?php
                     $item=null;
                      $valor=null;
                      $cajavta=ControladorCajas::ctrMostrarCajas($item, $valor);
                        foreach($cajavta as $key=>$value){
                            echo '<option value="'.$value["idcaja"].'">'.$value["caja"]."-".$value["nombre"].'</option>';
                         }   
                    ?>								
				      </select>
            </div>

            <div class="form-group col-md-2">
              <select class="form-control form-control-sm clienteSel" id="selCliente" name="selCliente" tabindex="4" title="Cliente">
				        <!-- consulta por ajax -->
		          </select>
            </div>

              <div class="form-group col-md-2 " id="">        <!--tmb invisible -->
                  <select class="form-control form-control-sm selProdRepVta" name="selProdRepInv[]" multiple="multiple[]" id="selProdRepInv" style="width: 100%;" tabindex="5">
          					<?php
                        $item=null;
                        $valor=null;
                        $orden="id";
                        $estado=1;
                        $productos=ControladorProductos::ctrMostrarProductos($item, $valor,$orden, $estado);
                        foreach($productos as $key=>$value){
                          if(is_null($value["datos_promocion"])){	//VALIDA QUE PRODUCTO NO SEA PROMOCION
                            echo '<option value="'.$value["id"].'">'.$value["codigointerno"]." - ".$value["descripcion"].'</option>';
                          }
                        }
                      ?>		
                  </select>
              </div>          

            </div>            

          <div class="row">
            <div class="form-group col-md-3">
                  <select class="form-control form-control-sm" name="TipoMovSal[]" multiple="multiple" id="TipoMovSal"  title="Tipo de Movimiento" tabindex="6" required>
                    <option>Tipo Mov</option>
                  <?php
                    $item="clase";
                    $valor="S";
                    $tabla="tipomovimiento";
                    $tipomov=ControladorSalidas::ctrMostrarTipoMovs($tabla, $item, $valor);
                    foreach($tipomov as $key=>$value){
                          echo '<option value="'.$value["id"].'">'.$value["nombre_tipo"].'</option>';
                    }
                  ?>				  
                  </select>			  
           </div>

                  <!-- Date range -->
                <div class="form-group col-md-2">
                  <div class="input-group input-group-sm">
                    <button type="button" class="btn btn-default btn-sm float-right" id="daterange-btn" tabindex="7">
                     <span>
                      <i class="fa fa-calendar"></i> Rango de fecha
                     </span>
                        <i class="fa fa-caret-down"></i>                     
                    </button>
                  </div>
                </div>
                <!-- /.form group -->
            
            
	            <div class="form-group col-md-2 mr-2">
              <?php if(getAccess($acceso, ACCESS_VIEW)){?>
                <button class="btn btn-success btn-sm mr-2" onclick="ListarVentas()" title="Mostrar en Pantalla"  tabindex="6">
                <i class="fa fa-eye"></i> Mostrar</button>
              <?php } 

              if(getAccess($acceso, ACCESS_PRINTER)){?>                
                  <button class="btn btn-primary btn-sm btnImprimirVta" title="Reporte en PDF" tabindex=""><i class="fa fa-file-pdf-o"></i>&nbsp Reporte</button>
                <?php } ?>
             </div>

             <!-- <span class="clearfix"></span>-->

              <div class="col-md-3 alert-danger rounded d-none h-25 text-center"  id="mensajederror">
              <!-- PARA MENSAJES DE ADVERTENCIA -->
              </div>    
          <!-- </div>    -->


<!--            
            <div class="form-group col-md-1">
              <button class="btn btn-primary btn-sm btnImprimirVta" tabindex="7"><i class="fa fa-file-pdf-o"></i>&nbsp Reporte</button>
            </div>
-->            
          </div>  
            
               
        </div>
        
      <div class="card-body">

        <table id="tabladeventas" class="table table-bordered compact striped hover dt-responsive" width="100%">
          <thead class="thead-dark">
            <th style="width:10px;">Fam.</th>
            <th style="width:10px;">Cat.</th>
            <th style="width:95px;">Código</th>
            <th style="width:320px;">Descripción</th>
            <th style="width:50px;">Cant</th>
            <th style="width:50px;">Costo</th>
				    <th style="width:50px;">Tot.Cto</th>
            <th style="width:50px;">Venta</th>
            <th style="width:50px;">Promo</th>
            <th style="width:70px;">Total</th>
            <th style="width:50px;">Dif</th>
            </thead>

          <tbody>                            
          </tbody>

					<tfoot class="thead-dark">
            <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            </tr>
          </tfoot >
            
        </table>    
		
      </div> <!-- /.card-body -->
            
        <div class="card-footer">

        </div> <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper   -->
<?php 
ob_end_flush();
?>


<script>
        window.jQuery || document.write('<script src="assets/dist/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="assets/dist/js/bootstrap.bundle.js">
      </script>
