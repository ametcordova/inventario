<?php
//Activamos el almacenamiento en el buffer
ob_start();
$fechaHoy=date("Y-m-d");
?>
<style>
.select2 {
  font-family: 'FontAwesome';
}
</style>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid">
        <div class="row p-0 m-0">
          <div class="col-sm-6">
            <h3>Kardex de Producto</h3>
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
        <div class="card-header m-1 p-0">
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-tool" onclick="regresar()" title="a Inicio">
              <i class="fa fa-times"></i></button>			  
          </div>
           
          <div class="row">
           
            <div class="form-group col-md-2">
                <select id="almacenKardex" class="form-control form-control-sm" name="almacenKardex" tabindex="1" required>
				<option value="Todos" selected>Todos</option>
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
              <select class="form-control selectKardex" name="selProductoKardex" id="selProductoKardex" style="width: 100%;" autofocus tabindex="2">
				            <option selected value=""></option>
 				  	          <?php
                        $item=null;
                        $valor=null;
                        $orden="id";
                        $estado=1;
                        $productos=ControladorProductos::ctrMostrarProductos($item, $valor, $orden, $estado);
                        foreach($productos as $key=>$value){
                          if(is_null($value["datos_promocion"])){	//VALIDA QUE PRODUCTO NO SEA PROMOCION
                            echo '<option value="'.$value["id"].'">'.$value["codigointerno"]." - ".$value["descripcion"].'</option>';
                          }  
                        }
                      ?>		
              </select>
            </div>

             <div class="form-group col-md-1.5">
              <input type="date" class="form-control form-control-sm" name="fechaIiKardex" id="fechaIniKardex" value="<?= $fechaHoy ?>" tabindex="3" title="Fecha Inicial">
            </div>

            
		  <div class="form-group col-md-1"><button class="btn btn-success btn-sm" onclick="listarkardex()" ><i class="fa fa-eye"></i>  Mostrar</button></div>
      </div>  
            
               
        </div>
        <div class="card-body">

	<table id="tablalistaSalida" class="table table-bordered compact table-hover table-striped dt-responsive" width="100%">
               <thead class="thead-dark">
			   
                 <th style="width:10px;">#</th>
                 <th style="width:10px;">Cte</th>
                 <th style="width:390px;">Nombre Cliente</th>
                 <th style="width:110px;"># Salida</th>
                 <th style="width:130px;">F. Salida</th>
                 <th style="width:70px;">Cant.</th>
                 <th style="width:70px;">Tot Vta.</th>
                 <th style="width:130px;">Almacen</th>
                 <th style="width:70px;">Acción</th>
                 

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
  <script defer src="vistas/js/historia-producto.js"></script> 
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
