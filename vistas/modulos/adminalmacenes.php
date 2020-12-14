<?php
//Activamos el almacenamiento en el buffer
ob_start();
$tabla="usuarios";
$module="rentradas";
$campo="reportes";
$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);    
?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h3>Reporte de Entradas</h3>
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
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-tool" onclick="regresar()" title="a Inicio">
              <i class="fa fa-times"></i></button>			  
          </div>
           
          <div class="row">
           
            <div class="form-group col-md-3">
                <select id="almEntrada" class="form-control form-control-sm" name="almEntrada" tabindex="1" required>
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

          <?php if(getAccess($acceso, ACCESS_VIEW)){?>
            <div class="form-group col-md-2"><button class="btn btn-success btn-sm" onclick="listarEntrada()" ><i class="fa fa-eye"></i>  Mostrar</button>
            </div>
          <?php } ?>    
          
          <?php if(getAccess($acceso, ACCESS_PRINTER)){?>
            <div class="form-group col-md-2">
               <input type="text" class="form-control form-control-sm" name="numeroDocto2" id="numeroDocto2" value="" placeholder="" tabindex="2" required>               
            </div>
            <div><button class="btn btn-default btn-sm btnImprimirEntra"><i class="fa fa-print"></i>  Imprimir</button>
            </div>
          <?php } ?>                  
          </div>  
            
               
        </div>
        <div class="card-body">

	<table id="tablalistado" class="table table-bordered table-striped dt-responsive" width="100%">
               <thead>
			   
                 <th style="width:10px;">#</th>
                 <th style="width:10px;">id_prov</th>
                 <th style="width:390px;">Proveedor</th>
                 <th style="width:110px;"># Docto</th>
                 <th style="width:130px;">F. Entrada</th>
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
