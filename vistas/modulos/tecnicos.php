<?php
    $tabla="usuarios";
    $module="ptecnicos";
    $campo="catalogo";
    $acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);    
?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1 m-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1><i class="fa fa-tty"></i>
                <small>Admin. Técnicos</small>
            </h1>
          </div>
          <div class="col-sm-6">
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
        <?php if(getAccess($acceso, ACCESS_ADD)){?> 
		      <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarTecnico"><i class="fa fa-tty"></i> Agregar Técnico
          </button>
        <?php } ?>           

	      <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>          

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
		
        <div class="card-body">
              <table id="TablaTecnicos" class="table table-bordered compact table-hover table-striped dt-responsive" cellspacing="0" width="100%">
                <thead class="thead-dark">
                <tr>
                    <th style="width:9px;">#</th>
                    <th>Nombre</th>
                    <th>Expediente</th>
                    <th>Rfc</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>#Cuenta/Tarjeta</th>
                    <th>Asignado</th>
                    <th>Estatus</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody style="font-size:0.90em">
        <?php

          $item = null;
          $valor = null;

          $tecnicos = ControladorTecnicos::ctrMostrarTecnicos($item, $valor);

          foreach ($tecnicos as $key => $value) {
            
            echo '<tr>

                    <td>'.($key+1).'</td>

                    <td>'.$value["nombre"].'</td>
					
                    <td>'.$value["expediente"].'</td>

                    <td>'.$value["rfc"].'</td>

                    <td>'.$value["telefonos"].'</td>

                    <td>'.$value["direccion"].'</td>

                    <td>'.$value["num_cuenta"].'</td>
					
                    <td>'.$value["almacen"].'</td>';
					
					
					  if($value["status"]==1){
						echo '<td><button class="btn btn-success btn-sm" idTecnico="'.$value["id"].'" estadoProveedor="1">Activo</button></td>';
					  }else{
						echo '<td><button class="btn btn-danger btn-sm" idTecnico="'.$value["id"].'" estadoProveedor="0">No activo</button></td>';
            }					

            $boton1=getAccess($acceso, ACCESS_VIEW)?'<button class="btn btn-info btn-sm btnVerTecnico" title="Visualizar" data-toggle="modal" data-target="#modalVerTecnico" idTecnico="'.$value["id"].'"><i class="fa fa-eye"></i> </button>':'';

            $boton2=getAccess($acceso, ACCESS_EDIT)?'<button class="btn btn-warning btn-sm btnEditarTecnico " title="Editar" data-toggle="modal" data-target="#modalEditarTecnico" idTecnico="'.$value["id"].'"><i class="fa fa-pencil"></i></button>':'';

            $botones = $boton1.$boton2;

              echo '<td>
                <div class="btn-group">'.$botones.'
                </div>  
              </td>
            </tr>';

          }

        ?>                    
                  
                </tbody>
                <tfoot>
                <tr>
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
  <!-- /.content-wrapper -->
 <!--================================ -->
 <!-- === MODAL AGREGAR TECNICOS ==-->
 <!--================================ -->
   <div class="modal fade" id="modalAgregarTecnico" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
   
    <div class="modal-content">
    <form role="form" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal py-2">
          <h4 class="modal-title">Agregar Técnico</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <!-- Modal body -->
      <div class="modal-body">
           
        <div class="box-body">
        </div>   

        <div class="card card-info">
         <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-9">
            <input type="text" class="form-control form-control-sm" id="NuevoNombre" name="NuevoNombre" placeholder="Nombre" data-toggle="tooltip" title="Nombre Completo" autofocus required>
			<input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
          </div>

          <div class="form-group col-md-3">
              <input type="text" class="form-control form-control-sm" id="NuevoRfc" name="NuevoRfc" placeholder="Rfc" data-toggle="tooltip" title="RFC"  required>
          </div>
        </div>

         <div class="form-row">
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="NuevoCurp" name="NuevoCurp" placeholder="Curp" data-toggle="tooltip" title="CURP" required>
            </div>
            <div class="form-group col-md-6">
              <input type="text" class="form-control form-control-sm" id="NuevaDireccion" name="NuevaDireccion" placeholder="Dirección" required>
            </div>
            <div class="form-group col-md-2">
              <input type="number" class="form-control form-control-sm" id="NuevoCp" name="NuevoCp" placeholder="C.P." data-toggle="tooltip" title="Cod. Postal">
            </div>
          </div>     
                   
          <div class="form-row">
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="NuevaCiudad" name="NuevaCiudad" required placeholder="Ciudad" data-toggle="tooltip" title="Ciudad de Residencia">
            </div>
            <div class="form-group col-md-4">
			  <select class="form-control form-control-sm" name="NuevoEstado" id="NuevoEstado" data-toggle="tooltip" title="Estado de Residencia">
				<option selected>Seleccione Estado</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $estado=ControladorTecnicos::ctrMostrarEstados($item, $valor);
                    foreach($estado as $key=>$value){
                        echo '<option value="'.$value["idestado"].'">'.$value["nombreestado"].'</option>';
                    }
                  ?>
			  </select>			  
            </div>
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="NuevoTelefono" name="NuevoTelefono" required placeholder="Teléfono(s)" data-toggle="tooltip" title="Telefonos">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <input type="email" class="form-control form-control-sm" id="NuevoEmail" name="NuevoEmail" required placeholder="Email" data-toggle="tooltip" title="Correo Electrónico">
            </div>
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="NuevaLicencia"  name="NuevaLicencia" required placeholder="Num. de Licencia" data-toggle="tooltip" title="Número de licencia de manejo">
            </div>
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="NuevoSeguro"  name="NuevoSeguro" required placeholder="Número de S.S." data-toggle="tooltip" title="Num. de Seguro Social">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="NuevoExpediente" name="NuevoExpediente" required placeholder="Expediente" data-toggle="tooltip" title="No. de Expediente">
            </div>
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="NuevoUsuario"  name="NuevoUsuario" required placeholder="Usuario" data-toggle="tooltip" title="Usuario">
            </div>
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="NuevaContrasena"  name="NuevaContrasena" required placeholder="Contraseña" data-toggle="tooltip" title="Contraseña">
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group col-md-4">
			  <select id="inputState" class="form-control form-control-sm" name="NuevoBanco" id="NuevoBanco" data-toggle="tooltip" title="Institución Bancaria">
				    <option selected>Seleccione Banco</option>
                    <option value="1">Banco Nacional de México (Banamex)</option>
                    <option value="2">Banco Santander (México)</option>
                    <option value="3">HSBC México</option>
                    <option value="4">Scotiabank Inverlat</option>
                    <option value="5">BBVA Bancomer</option>
                    <option value="6">Banco Mercantil del Norte (Banorte)</option>
                    <option value="7">Banco Inbursa</option>
                    <option value="8">Banco Invex</option>
                    <option value="9">Banco del Bajio</option>
                    <option value="10">Bansi</option>
                    <option value="11">Banca Afirme</option>
                    <option value="12">American Express Bank (México)</option>
                    <option value="13">Investa Bank</option>
                    <option value="14">CiBanco</option>
                    <option value="15">Banco Azteca</option>
                    <option value="16">Banco Autofin México</option>
                    <option value="17">Banco Ahorro Famsa</option>
                    <option value="18">Banco Actinver</option>
                    <option value="19">Banco Compartamos</option>
                    <option value="20">Banco Multiva</option>
                    <option value="21">Bancoppel</option>
                    <option value="23">ConsuBanco</option>
                    <option value="24">Banco Wal-Mart de México</option>
                    <option value="25">Fundación Dondé Banco</option>
                    <option value="26">Banco Bancrea</option>
			  </select>			  
            </div>
            <div class="form-group col-md-3">
              <input type="text" class="form-control form-control-sm" id="NuevaCuenta"  name="NuevaCuenta" required placeholder="No. de Cuenta" data-toggle="tooltip" title="No. de Cuenta bancaria">
            </div>
            <div class="form-group col-md-5">
              <input type="text" class="form-control form-control-sm" id="NuevaClabe"  name="NuevaClabe" required placeholder="Cuenta CLABE" data-toggle="tooltip" title="Cuenta CLABE">
            </div>
          </div>
         
          <div class="form-row">
            <div class="form-group col-md-4">
			  <select class="form-control form-control-sm" name="NacimientoEstado" id="NacimientoEstado" data-toggle="tooltip" title="Estado de nacimiento">
				<option selected>Estado de Nacimiento</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $estado=ControladorTecnicos::ctrMostrarEstados($item, $valor);
                    foreach($estado as $key=>$value){
                        echo '<option value="'.$value["idestado"].'">'.$value["nombreestado"].'</option>';
                    }
                  ?>
			  </select>			  
            </div>
            <div class="form-group col-md-5">
			  <select class="form-control form-control-sm" name="NuevoAlmacen" id="NuevoAlmacen" data-toggle="tooltip" title="Almacen Asignado">
				<option selected>Almacen Asignado</option>}
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
            <div class="form-group col-md-3">
			  <select class="form-control form-control-sm" name="NuevoEstatus" id="NuevoEstatus" data-toggle="tooltip" title="Estatus">
				<option selected>Seleccione Estatus</option>
				<option value="1">Activado</option>
				<option value="0">Desactivado</option>
			  </select>			  
            </div>
          </div>                    
         
         </div>
        </div>
      
      </div>    <!-- fin Modal-body  -->

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal py-2">
       
        <button type="button" class="btn btn-primary btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Guardar</button>
      
      </div>
      <?php
		$crearTecnico=new ControladorTecnicos();
        $crearTecnico->ctrCrearTecnico();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>    <!-- fin del modal -->

<!--================================ -->
  <!-- === MODAL VER TECNICOS ==-->
 <!--================================ -->
   <div class="modal fade" id="modalVerTecnico" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
   
    <div class="modal-content">
    <form role="form" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal py-2">
          <h4 class="modal-title">Mostrar Datos de Técnico</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <!-- Modal body -->
      <div class="modal-body">
           
        <div class="box-body">
        </div>   

        <div class="card card-info">
         <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-9">
            <input type="text" class="form-control form-control-sm" id="VerNombre" name="VerNombre" placeholder="Nombre" data-toggle="tooltip" title="Nombre Completo" autofocus required>
          </div>

          <div class="form-group col-md-3">
              <input type="text" class="form-control form-control-sm" id="VerRfc" placeholder="Rfc" data-toggle="tooltip" title="RFC"  required>
          </div>
        </div>

         <div class="form-row">
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="VerCurp" placeholder="Curp" data-toggle="tooltip" title="CURP" required>
            </div>
            <div class="form-group col-md-6">
              <input type="text" class="form-control form-control-sm" id="VerDireccion"  placeholder="Dirección" required>
            </div>
            <div class="form-group col-md-2">
              <input type="number" class="form-control form-control-sm" id="VerCp" placeholder="C.P." data-toggle="tooltip" title="Cod. Postal">
            </div>
          </div>     
                   
          <div class="form-row">
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="VerCiudad" required placeholder="Ciudad" data-toggle="tooltip" title="Ciudad de Residencia">
            </div>
            <div class="form-group col-md-4">
			  <select class="form-control form-control-sm" name="NuevoEstado" id="VerEstado" data-toggle="tooltip" title="Estado de Residencia">
				<option selected>Seleccione Estado</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $estado=ControladorTecnicos::ctrMostrarEstados($item, $valor);
                    foreach($estado as $key=>$value){
                        echo '<option value="'.$value["idestado"].'">'.$value["nombreestado"].'</option>';
                    }
                  ?>
			  </select>			  
            </div>
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="VerTelefono" required placeholder="Teléfono(s)" data-toggle="tooltip" title="Telefonos">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <input type="email" class="form-control form-control-sm" id="VerEmail" name="NuevoEmail" required placeholder="Email" data-toggle="tooltip" title="Correo Electrónico">
            </div>
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="VerLicencia" required placeholder="Num. de Licencia" data-toggle="tooltip" title="Número de licencia de manejo">
            </div>
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="VerSeguro" required placeholder="Número de S.S." data-toggle="tooltip" title="Num. de Seguro Social">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="VerExpediente" required placeholder="Expediente" data-toggle="tooltip" title="No. de Expediente">
            </div>
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="VerUsuario" required placeholder="Usuario" data-toggle="tooltip" title="Usuario">
            </div>
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="VerContrasena" required placeholder="Contraseña" data-toggle="tooltip" title="Contraseña">
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group col-md-4">
			  <select class="form-control form-control-sm" id="VerBanco" data-toggle="tooltip" title="Institución Bancaria">
				    <option selected>Seleccione Banco</option>
                    <option value="1">Banco Nacional de México (Banamex)</option>
                    <option value="2">Banco Santander (México)</option>
                    <option value="3">HSBC México</option>
                    <option value="4">Scotiabank Inverlat</option>
                    <option value="5">BBVA Bancomer</option>
                    <option value="6">Banco Mercantil del Norte (Banorte)</option>
                    <option value="7">Banco Inbursa</option>
                    <option value="8">Banco Invex</option>
                    <option value="9">Banco del Bajio</option>
                    <option value="10">Bansi</option>
                    <option value="11">Banca Afirme</option>
                    <option value="12">American Express Bank (México)</option>
                    <option value="13">Investa Bank</option>
                    <option value="14">CiBanco</option>
                    <option value="15">Banco Azteca</option>
                    <option value="16">Banco Autofin México</option>
                    <option value="17">Banco Ahorro Famsa</option>
                    <option value="18">Banco Actinver</option>
                    <option value="19">Banco Compartamos</option>
                    <option value="20">Banco Multiva</option>
                    <option value="21">Bancoppel</option>
                    <option value="23">ConsuBanco</option>
                    <option value="24">Banco Wal-Mart de México</option>
                    <option value="25">Fundación Dondé Banco</option>
                    <option value="26">Banco Bancrea</option>
			  </select>			  
            </div>
            <div class="form-group col-md-3">
              <input type="text" class="form-control form-control-sm" id="VerCuenta"  required placeholder="No. de Cuenta" data-toggle="tooltip" title="No. de Cuenta bancaria">
            </div>
            <div class="form-group col-md-5">
              <input type="text" class="form-control form-control-sm" id="VerClabe" required placeholder="Cuenta CLABE" data-toggle="tooltip" title="Cuenta CLABE">
            </div>
          </div>
         
          <div class="form-row">
            <div class="form-group col-md-4">
			  <select class="form-control form-control-sm" id="VerNacimientoEstado" data-toggle="tooltip" title="Estado de nacimiento">
				<option selected>Estado de Nacimiento</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $estado=ControladorTecnicos::ctrMostrarEstados($item, $valor);
                    foreach($estado as $key=>$value){
                        echo '<option value="'.$value["idestado"].'">'.$value["nombreestado"].'</option>';
                    }
                  ?>
			  </select>			  
            </div>
            <div class="form-group col-md-5">
			  <select class="form-control form-control-sm" id="VerAlmacen" data-toggle="tooltip" title="Almacen Asignado">
				<option selected>Almacen Asignado</option>}
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
            <div class="form-group col-md-3">
			  <select class="form-control form-control-sm" id="VerEstatus" data-toggle="tooltip" title="Estatus">
				<option selected>Seleccione Estatus</option>
				<option value="1">Activado</option>
				<option value="0">Desactivado</option>
			  </select>			  
            </div>
          </div>                    
         
         </div>
        </div>
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal py-2">
       
        <button type="button" class="btn btn-primary btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
      
      </div>
      <?php
		//$crearTecnico=new ControladorTecnicos();
        //$crearTecnico->ctrCrearTecnico();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>    <!-- fin del modal VER TECNICOS-->

<!--================================ -->
<!--================================ -->
 <!-- === MODAL EDITAR TECNICOS ==-->
 <!--================================ -->
   <div class="modal fade" id="modalEditarTecnico" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
   
    <div class="modal-content">
    <form role="form" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal py-2">
          <h4 class="modal-title">Editar Técnico</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <!-- Modal body -->
      <div class="modal-body">
           
        <div class="box-body">
        </div>   

        <div class="card card-info">
         <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-9">
            <input type="text" class="form-control form-control-sm" id="EditarNombre" name="EditarNombre" placeholder="Nombre" data-toggle="tooltip" title="Nombre Completo" autofocus required>
			      <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
            <input type="hidden" id="idTecnico" name="idTecnico">
          </div>

          <div class="form-group col-md-3">
              <input type="text" class="form-control form-control-sm" id="EditarRfc" name="EditarRfc" placeholder="Rfc" data-toggle="tooltip" title="RFC"  required>
          </div>
        </div>

         <div class="form-row">
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="EditarCurp" name="EditarCurp" placeholder="Curp" data-toggle="tooltip" title="CURP" required>
            </div>
            <div class="form-group col-md-6">
              <input type="text" class="form-control form-control-sm" id="EditarDireccion" name="EditarDireccion" placeholder="Dirección" required>
            </div>
            <div class="form-group col-md-2">
              <input type="number" class="form-control form-control-sm" id="EditarCp" name="EditarCp" placeholder="C.P." data-toggle="tooltip" title="Cod. Postal">
            </div>
          </div>     
                   
          <div class="form-row">
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="EditarCiudad" name="EditarCiudad" required placeholder="Ciudad" data-toggle="tooltip" title="Ciudad de Residencia">
            </div>
            <div class="form-group col-md-4">
			  <select class="form-control form-control-sm" name="EditarEstado" id="EditarEstado" data-toggle="tooltip" title="Estado de Residencia">
				<option selected>Seleccione Estado</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $estado=ControladorTecnicos::ctrMostrarEstados($item, $valor);
                    foreach($estado as $key=>$value){
                        echo '<option value="'.$value["idestado"].'">'.$value["nombreestado"].'</option>';
                    }
                  ?>
			  </select>			  
            </div>
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="EditarTelefono" name="EditarTelefono" required placeholder="Teléfono(s)" data-toggle="tooltip" title="Telefonos">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <input type="email" class="form-control form-control-sm" id="EditarEmail" name="EditarEmail" required placeholder="Email" data-toggle="tooltip" title="Correo Electrónico">
            </div>
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="EditarLicencia"  name="EditarLicencia" required placeholder="Num. de Licencia" data-toggle="tooltip" title="Número de licencia de manejo">
            </div>
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="EditarSeguro"  name="EditarSeguro" required placeholder="Número de S.S." data-toggle="tooltip" title="Num. de Seguro Social">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="EditarExpediente" name="EditarExpediente" required placeholder="Expediente" data-toggle="tooltip" title="No. de Expediente">
            </div>
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="EditarUsuario"  name="EditarUsuario" required placeholder="Usuario" data-toggle="tooltip" title="Usuario">
            </div>
            <div class="form-group col-md-4">
              <input type="text" class="form-control form-control-sm" id="EditarContrasena"  name="EditarContrasena" required placeholder="Contraseña" data-toggle="tooltip" title="Contraseña">
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group col-md-4">
			  <select class="form-control form-control-sm" name="EditarBanco" id="EditarBanco" data-toggle="tooltip" title="Institución Bancaria">
				    <option selected>Seleccione Banco</option>
                    <option value="1">Banco Nacional de México (Banamex)</option>
                    <option value="2">Banco Santander (México)</option>
                    <option value="3">HSBC México</option>
                    <option value="4">Scotiabank Inverlat</option>
                    <option value="5">BBVA Bancomer</option>
                    <option value="6">Banco Mercantil del Norte (Banorte)</option>
                    <option value="7">Banco Inbursa</option>
                    <option value="8">Banco Invex</option>
                    <option value="9">Banco del Bajio</option>
                    <option value="10">Bansi</option>
                    <option value="11">Banca Afirme</option>
                    <option value="12">American Express Bank (México)</option>
                    <option value="13">Investa Bank</option>
                    <option value="14">CiBanco</option>
                    <option value="15">Banco Azteca</option>
                    <option value="16">Banco Autofin México</option>
                    <option value="17">Banco Ahorro Famsa</option>
                    <option value="18">Banco Actinver</option>
                    <option value="19">Banco Compartamos</option>
                    <option value="20">Banco Multiva</option>
                    <option value="21">Bancoppel</option>
                    <option value="23">ConsuBanco</option>
                    <option value="24">Banco Wal-Mart de México</option>
                    <option value="25">Fundación Dondé Banco</option>
                    <option value="26">Banco Bancrea</option>
			  </select>			  
            </div>
            <div class="form-group col-md-3">
              <input type="text" class="form-control form-control-sm" id="EditarCuenta"  name="EditarCuenta" required placeholder="No. de Cuenta" data-toggle="tooltip" title="No. de Cuenta bancaria">
            </div>
            <div class="form-group col-md-5">
              <input type="text" class="form-control form-control-sm" id="EditarClabe"  name="EditarClabe" required placeholder="Cuenta CLABE" data-toggle="tooltip" title="Cuenta CLABE">
            </div>
          </div>
         
          <div class="form-row">
            <div class="form-group col-md-4">
			  <select class="form-control form-control-sm" name="EditarNacimientoEstado" id="EditarNacimientoEstado" data-toggle="tooltip" title="Estado de nacimiento">
				<option selected>Estado de Nacimiento</option>
                  <?php
                    $item=null;
                    $valor=null;
                    $estado=ControladorTecnicos::ctrMostrarEstados($item, $valor);
                    foreach($estado as $key=>$value){
                        echo '<option value="'.$value["idestado"].'">'.$value["nombreestado"].'</option>';
                    }
                  ?>
			  </select>			  
            </div>
            <div class="form-group col-md-5">
			  <select  class="form-control form-control-sm" name="EditarAlmacen" id="EditarAlmacen" data-toggle="tooltip" title="Almacen Asignado">
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
            <div class="form-group col-md-3">
			  <select class="form-control form-control-sm" name="EditarEstatus" id="EditarEstatus" data-toggle="tooltip" title="Estatus">
				<option value="1">Activado</option>
				<option value="0">Desactivado</option>
			  </select>			  
            </div>
          </div>                    
         
         </div>
        </div>
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal py-2">
       
        <button type="button" class="btn btn-primary btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Guardar Cambios</button>
      
      </div>
      <?php
		$editarTecnico=new ControladorTecnicos();
        $editarTecnico->ctrEditarTecnico();
      ?>
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>    <!-- fin del modal -->

<!--================================ 
$_POST["NuevoNombre"],
$_POST["NuevoRfc"],
$_POST["NuevoCurp"],
$_POST["NuevaDireccion"],
$_POST["NuevoCp"],
$_POST["NuevaCiudad"],
$_POST["NuevoEstado"],
$_POST["NuevoTelefono"],
$_POST["NuevoEmail"],
$_POST["NuevaLicencia"],
$_POST["NuevoSeguro"],
$_POST["NuevoExpediente"],
$_POST["NuevoUsuario"],
$_POST["NuevaContrasena"],
$_POST["NuevoBanco"],
$_POST["NuevaCuenta"],
$_POST["NuevaClabe"],
$_POST["NacimientoEstado"],
$_POST["NuevoAlmacen"],
$_POST["NuevoEstatus"];

NuevoNombre,
NuevoRfc,
NuevoCurp,
NuevaDireccion,
NuevoCp,
NuevaCiudad,
NuevoEstado,
NuevoTelefono,
NuevoEmail,
NuevaLicencia,
NuevoSeguro,
NuevoExpediente,
NuevoUsuario,
NuevaContrasena,
NuevoBanco,
NuevaCuenta,
NuevaClabe,
NacimientoEstado,
NuevoAlmacen,
NuevoEstatus;
-->
