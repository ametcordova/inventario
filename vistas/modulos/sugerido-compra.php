<?php
//Activamos el almacenamiento en el buffer
ob_start();
$tabla="usuarios";
$module="rsugerido";
$campo="reportes";
$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);

?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-3">
            <h4>Sugerido de Compra</h4>
          </div>
          <div class="col-sm-4">
            <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>
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
        <div class="card-header m-1 p-1">
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
			          <button type="button" class="btn btn-tool" onclick="regresar()" title="a Inicio">
              <i class="fa fa-times"></i></button>			  
          </div>
           
          <div class="row ">
           
            <div class="form-group col-md-2">
                <select class="form-control form-control-sm" id="almInventario" name="almInventario" tabindex="1" required>
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

            <div class="form-group col-md-2">
                <select multiple="multiple[]" class="form-control form-control-sm" id="idsfamilySel" name="idsfamilySel" tabindex="2">

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
			
            <div class="col- mr-2 form-check">
              <input class="form-check-input checkbox-inline" type="checkbox" value="1" name="SelFamAll" id="SelFamAll" >
              <label class="form-check-label" for="all" title='Selecciona todas las familias'>
               Todos
              </label>
            </div>	
            
            <?php if(getAccess($acceso, ACCESS_VIEW)){?>
	          <div class="form-group col-md-1 mr-3">
               <button class="btn btn-success btn-sm" onclick="listarSugerido()" title="Ver en Pantalla" ><i class="fa fa-eye"></i>  Mostrar</button>
            </div>
            <?php } ?>             

            <?php if(getAccess($acceso, ACCESS_PRINTER)){?>            
            <div class="form-group col-md-1 mr-3">
              <button class="btn btn-info btn-sm" id="botonRep" title="Reporte en PDF"><i class="fa fa-file-pdf-o"></i> Reporte</button>
            </div>
            <?php } ?> 
 <!--           
            <div class="form-group col-md-1">
              <button class="btn btn-primary btn-sm" id="botonImp" title="Imprimir en Ticket"><i class="fa fa-print"></i> Imprimir</button>
            </div>
-->            
            <span class="clearfix"></span>
            <div class="col-md-3 alert-danger rounded d-none h-25 text-center"  id="mensajerror">
						<!-- PARA MENSAJES DE ADVERTENCIA -->
            </div>            

          </div>  <!-- FIN DEL ROW -->
            
        </div>
        <div class="card-body p-2">

	        <table id="tablalistado" class="table table-bordered compact striped hover dt-responsive" width="100%">
               <thead class="thead-dark">
			   
                 <th style="width:10px;">#</th>
                 <th style="width:10px;">Fam.</th>
                 <th style="width:10px;">Cat.</th>
                 <th style="width:95px;">Código</th>
                 <th style="width:320px;">Descripción</th>
                 <th style="width:125px;">U.de Med</th>
                 <th style="width:50px;">Exist</th>
				         <th style="width:50px;">Máximo</th>
                 <th style="width:70px;">Reorden</th>
                 <th style="width:70px;">P. Costo</th>
                 <th style="width:70px;">Total</th>

            </thead>
            <tbody>                            
            </tbody>
					  <tfoot>
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
                      </tfoot>
            
        </table>    
		
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
  <!-- /.content-wrapper   -->
  <script defer src="vistas/js/sugerido-compra.js"></script>
<?php 
ob_end_flush();
?>
