<?php
//Activamos el almacenamiento en el buffer
ob_start();
$tabla="usuarios";
$module="rsalidas";
$campo="reportes";
$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);    
?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid">
        <div class="row p-0 m-0">
          <div class="col-sm-6">
            <h3>Reporte de Salidas</h3>
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
        <div class="card-header m-0 p-0 pt-2 pl-2">
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-tool" onclick="regresar()" title="a Inicio">
              <i class="fa fa-times"></i></button>			  
          </div>
           
          <div class="row">
           
            <div class="form-group col-md-2">
                <select id="almSalida" class="form-control form-control-sm" name="almSalida" tabindex="1" required>
				<option value="Todos" selected>Todos</option>
                 <?php
                   $item=null;
                    $valor=null;
                    $almacenes=ControladorAlmacenes::ctrMostrarAlmacenes($item, $valor);
                    foreach($almacenes as $key=>$value){
                        echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                    }                    
                  ?>								
				 </select>
            </div>
            
            <div class="form-group col-md-2">
                <select id="tecSalida" class="form-control form-control-sm" name="tecSalida" tabindex="2" >
                  <option value="all">Selecione Técnico</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $tecnicos=ControladorTecnicos::ctrMostrarTecnicos($item, $valor);
                    foreach($tecnicos as $key=>$value){
                        //if($value["id"]=="1"){
                          //echo '<option selected value="'.$value["id"].'">'.$value["nombre"].'</option>';    
                        //}else{
                          echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                        //}
                    }
                  ?>				  
				 </select>
            </div>
            <?php if(getAccess($acceso, ACCESS_VIEW)){?>
              <div class="form-group col-md-2"><button class="btn btn-success btn-sm" onclick="listarSalida()" ><i class="fa fa-eye"></i>  Mostrar</button>
              </div>
            <?php } ?>    

            <?php if(getAccess($acceso, ACCESS_PRINTER)){?>
              <div class="form-group col-md-1">
                <input type="text" class="form-control form-control-sm" name="numSalida" id="numSalida" placeholder="" required>               
              </div>
              <div><button class="btn btn-default btn-sm btnImprimirSalida"><i class="fa fa-print"></i>  Imprimir</button>
              </div>
            <?php } ?>            
          </div>  
               
        </div>
        <div class="card-body">

      	<table id="tablalistaSalida" class="table table-sm display compact table-bordered table-striped dt-responsive" width="100%">

               <thead class="thead-dark" style="font-size:.8rem; height:1.5px">
			   
                 <th style="width:10px;">#</th>
                 <th style="width:10px;">id_Tec</th>
                 <th style="width:390px;">Técnico</th>
                 <th style="width:110px;"># Salida</th>
                 <th style="width:130px;">F. Salida</th>
                 <th style="width:70px;">Cant.</th>
                 <th style="width:130px;">Almacen</th>
                 <th style="width:80px;">Acción</th>

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
  <!-- /.content-wrapper 
SELECT  hist.id_proveedor, prov.nombre, hist.numerodocto, hist.fechaentrada,sum(cantidad) as entro, alm.nombre as almacen FROM hist_entrada hist inner join proveedores prov ON hist.id_proveedor=prov.id inner join almacenes alm ON hist.id_almacen=alm.id GROUP by hist.numerodocto

SELECT `numerodocto`,`fechaentrada`, sum(`cantidad`),`id_proveedor`,prov.nombre,`id_almacen`,alm.nombre as almacen FROM hist_entrada 
INNER JOIN proveedores prov ON id_proveedor=prov.id
INNER JOIN almacenes alm ON id_almacen=alm.id
GROUP by `numerodocto`,`fechaentrada`,`id_almacen`,`id_proveedor`
  -->
<?php 
ob_end_flush();
?>
