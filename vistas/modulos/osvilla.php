<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-0 p-0">
      <div class="container-fluid">
        <div class="row ">
          <div class="col-sm-6">
            <h4>Administrar OS Villa:&nbsp; 
                <small><i class="fa fa-th"></i></small>
            </h4>
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
        
         <div class="card-header my-0 ml-1 p-2">
           <button class="btn btn-primary btn-sm" data-toggle="modal" id="modalagrega" data-target="#modalAgregarOsvilla"><i class="fa fa-plus-circle"></i> Agregar OS
			</button>
			<button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>

                <!-- Date range -->
             <button type="button" class="btn btn-default btn-sm ml-3 mr-2 " id="daterange-btn2" data-toggle="tooltip" title="Rango de Fecha Asignadas">
                    <span>
                      <i class="fa fa-calendar"></i> Rango de fecha
                    </span>
                      <i class="fa fa-caret-down"></i>                     
            </button>

            <button class="btn btn-success btn-sm" onclick="listarOSVilla()">
					<i class="fa fa-eye"></i>  Mostrar
			</button>
			
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Ocultar">
              <i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-tool" onclick="regresar()" data-toggle="tooltip" title="a Inicio">
              <i class="fa fa-times"></i></button>			  
          </div>
      
         </div>
        
        <div class="card-body">
        <div class="card">
            <!-- <div class="card-header">
            </div> -->           <!-- /.card-header -->
              <div class="card-body">
              <!-- <table class="table table-bordered display compact nowrap striped table-hover dt-responsive" id="TablaOS" width="100%"> -->
              
              <table class="table table-bordered compact table-striped table-hover dt-responsive" id="TablaOS" width="100%">

                <thead class="thead-dark">
                <tr>
                    <th style="width:10px;">#</th>
                    <th style="width:80px;">OS</th>
                    <th style="width:100px;">Técnico</th>
                    <th style="width:70px;">F. Asig.</th>
                    <th style="width:70px;">F. Liquida</th>
                    <th style="width:100px;">Teléfono</th>
                    <th style="width:250px;">Cliente</th>
                    <th style="width:10px;">Status</th>
                    <th style="width:10px;">Acción</th>
                </tr>
                </thead>
                <tbody>
<!--				
                <tr class="table-success">
                 <td>125</td>
                 <td>035208019</td>
                 <td>PEÑA JIMENEZ LEIDY LAURA</td>
                 <td>01/01/2020</td>
                 <td>01/02/2020</td>
                 <td>9931870418</td>
                 <td>Carlos</td>
                 <td><button class="btn btn-success btn-sm" title="Visualizar">Liquidado</button></td>
                 <td class="">
                  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acción
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-success bg-light" data-toggle="modal" href="#modalLiquidarOsvilla" title="Generar Liquidación"><i class="fa fa-ticket"></i> &nbspGenerar Liquidación</a>
                    <a class="dropdown-item text-info bg-light" href="#" title="Ver"><i class="fa fa-eye"></i> &nbspVisualizar OS</a>
                    <a class="dropdown-item text-warning bg-light btnEditarOs" href="#" title="Editar"><i class="fa fa-pencil"></i> &nbspEditar OS</a>
                    <a class="dropdown-item text-primary bg-light" href="#" title="Generar OS en PDF "><i class="fa fa-file-pdf-o"></i> &nbspReporte OS en PDF</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger bg-light" href="#"  title="Eliminar OS"><i class="fa fa-eraser"></i> &nbspEliminar OS</a>
                  </div>
                 </td>
                </tr> 
                
                <tr class="table-warning">
                 <td>126</td>
                 <td>035202521</td>
                 <td>MARTINEZ JUAREZ RICARDO</td>
                 <td>01/01/2020</td>
                 <td>01/02/2020</td>
                 <td>9931870321</td>
                 <td>Noel</td>
                 <td><button class="btn btn-warning btn-sm" title="Visualizar">Pendiente</button></td>
				 <td class="">
                  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acción
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-success bg-light" href="#" title="Generar Liquidación"><i class="fa fa-ticket"></i> &nbspGenerar Liquidación</a>
                    <a class="dropdown-item text-info bg-light" href="#" title="Ver"><i class="fa fa-eye"></i> &nbspVisualizar OS</a>
                    <a class="dropdown-item text-warning bg-light" href="#" title="Editar"><i class="fa fa-pencil"></i> &nbspEditar OS</a>
                    <a class="dropdown-item text-primary bg-light" href="#" title="Generar OS en PDF "><i class="fa fa-file-pdf-o"></i> &nbspReporte OS en PDF</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger bg-light" href="#"  title="Eliminar OS"><i class="fa fa-eraser"></i> &nbspEliminar OS</a>
                  </div>
                 </td>
                </tr> 

                <tr class="table-danger">
                 <td>127</td>
                 <td>035203335</td>
                 <td>HERNANDEZ DIAZ JOSE</td>
                 <td>01/02/2020</td>
                 <td>02/01/2020</td>
                 <td>9931340352</td>
                 <td>Eduardo</td>
                 <td><button class="btn btn-danger btn-sm" title="Visualizar">Objetado</button></td>
				 <td class="">
                  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Acción
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item text-success bg-light" href="#" title="Generar Liquidación"><i class="fa fa-ticket"></i> &nbspGenerar Liquidación</a>
                    <a class="dropdown-item text-info bg-light" href="#" title="Ver"><i class="fa fa-eye"></i> &nbspVisualizar OS</a>
                    <a class="dropdown-item text-warning bg-light" href="#" title="Editar"><i class="fa fa-pencil"></i> &nbspEditar OS</a>
                    <a class="dropdown-item text-primary bg-light" href="#" title="Generar OS en PDF "><i class="fa fa-file-pdf-o"></i> &nbspReporte OS en PDF</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger bg-light" href="#"  title="Eliminar OS"><i class="fa fa-eraser"></i> &nbspEliminar OS</a>
                  </div>
                 </td>
                </tr> 
                 hasta aqui-->
                </tbody>
                <tfoot class="thead-dark">
                <tr>
                    <th style="width:10px;">#</th>
                    <th style="width:80px;">OS</th>
                    <th style="width:100px;">Técnico</th>
                    <th style="width:70px;">F. Asig.</th>
                    <th style="width:70px;">F. Liquida</th>
                    <th style="width:100px;">Teléfono</th>
                    <th style="width:250px;">Cliente</th>
                    <th style="width:10px;">Status</th>
                    <th style="width:10px;">Acción</th>
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
  
 <!-- ======================================== MODAL AGREGAR OS ======================================-->
   <div class="modal fade " id="modalAgregarOsvilla" role="dialog" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-xlg" role="document">
   
    <div class="modal-content">
    <form role="form" name="formularioAgregarOSVilla" id="formularioAgregarOSVilla" >
      <!-- Modal Header -->
      <div class="modal-header colorbackModal">
   
            <h4 class="modal-title">Agregar OS</h4>
        
            <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
     <!-- Modal body
        <div class="form-row">
         <div class="form-group col-md-3">
            <input type="text" class="form-control form-control-sm" id="" name="" placeholder="" data-toggle="tooltip" title="" required>
         </div>
        </div>      
	-->
      <div class="modal-body">
           
        <div class="box-body">
        </div>   

        <div class="card card-info">
         <div class="card-body">
         <label for="datoscliente">Datos Cliente:</label>
         
        <div class="form-row">
         <div class="form-group col-md-3">
            <input type="text" class="form-control form-control-sm" id="" name="nvaArea" value="" placeholder="Area" data-toggle="tooltip" title="Área" required>
			<input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
         </div>
         <div class="form-group col-md-2">
            <input type="text" class="form-control form-control-sm" id="" name="nvaOs" value="" placeholder="O. de S." data-toggle="tooltip" title="No. Orden de Servicio" required>
         </div>
         <div class="form-group col-md-2">
            <input type="text" class="form-control form-control-sm" id="" name="nvoTipoOs" value="" placeholder="Tipo OS" data-toggle="tooltip" title="Tipo Orden de Servicio" required>
         </div>
         <div class="form-group col-md-5">
            <input type="text" class="form-control form-control-sm" id="" name="nvoContratante" value="" placeholder="Nom. Contratante" data-toggle="tooltip" title="Nombre del Contratante" required>
         </div>
        </div>     
        
        <div class="form-row">
         <div class="form-group col-md-2">
            <input type="date" class="form-control form-control-sm" id="" name="nvaFechaCita" placeholder="Fecha Cita" data-toggle="tooltip" title="Fecha cita" required>
         </div>
         <div class="form-group col-md-5">
            <input type="text" class="form-control form-control-sm" id="" name="nvoDomicilio" value=""  placeholder="Domicilio" data-toggle="tooltip" title="Domicilio actual" required>
         </div>
         <div class="form-group col-md-2">
            <input type="text" class="form-control form-control-sm" id="" name="nvaCiudad" value="" placeholder="Ciudad" data-toggle="tooltip" title="Ciudad" required>
         </div>
         <div class="form-group col-md-3">
			  <select class="form-control form-control-sm" name="nvoEstado" id="" data-toggle="tooltip" title="Estado de Residencia">
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
        </div>      
        
        <div class="form-row">
         <div class="form-group col-md-2">
            <input type="text" class="form-control form-control-sm" id="" name="nvoFolio" value="" placeholder="Folio Pisaplex" data-toggle="tooltip" title="Folio Pisaplex" required>
         </div>
         <div class="form-group col-md-1">
            <input type="text" class="form-control form-control-sm" id="" name="nvaPrioridad" value="" placeholder="Prioridad" data-toggle="tooltip" title="Prioridad" required>
         </div>
         <div class="form-group col-md-3">
            <input type="text" class="form-control form-control-sm" id="" name="nvaZona" value="" placeholder="Zona" data-toggle="tooltip" title="Zona" required>
         </div>
         <div class="form-group col-md-3">
            <input type="tel" class="form-control form-control-sm" id="" name="nvoTelefono" value="" placeholder="Télefono" data-toggle="tooltip" title="Télefono" required>
         </div>
         <div class="form-group col-md-3">
            <input type="tel" class="form-control form-control-sm" id="" name="nvoTelContacto" value=""  placeholder="Tel. Contacto" data-toggle="tooltip" title="Tel. contacto">
         </div>
         
        </div>      
        
        <div class="form-row">
         <div class="form-group col-md-2">
            <input type="text" class="form-control form-control-sm" id="" name="nvoTipoCliente" value="" placeholder="Tipo  Cliente" data-toggle="tooltip" title="Tipo Cliente">
         </div>
         <div class="form-group col-md-4">
            <input type="tel" class="form-control form-control-sm" id="" name="nvoTelCelular" value=""  placeholder="Tel. Celular" data-toggle="tooltip" title="Tel. Celular">
         </div>
         
         <div class="form-group col-md-4">
            <input type="mail" class="form-control form-control-sm" id="" name="nvoEmail" value="" placeholder="email" data-toggle="tooltip" title="email">
         </div>
		 
         <div class="form-group col-md-2">
            <input type="date" class="form-control form-control-sm" id="" name="nvaFechaAsigna" placeholder="Fecha Asignación" data-toggle="tooltip" title="Fecha Asignación" required>
         </div>
		 
        </div>      

<div class="form-row">
 <div class="table-responsive-sm">
    <table class="table table-bordered table-sm striped table-hover dt-responsive" id="TablaOS" width="100%">
    <thead>
    <tr class="text-center" style="font-size:.9rem;">
        <th style="width:10px;">TARJ-LIN-1</th>
        <th style="width:10px;">TELEFONO</th>
        <th style="width:10px;">DISTRITO</th>
        <th style="width:10px;">TERMINAL</th>
        <th style="width:10px;">ST.</th>
        <th style="width:10px;">PAR</th>
        <th style="width:10px;">DISPOSIT.</th>
        <th style="width:10px;">LOCALIZA</th>
    </tr>
    </thead> 
    <tbody>
    <tr>
        <td><input type="text" name="tarjlin1a" class="form-control form-control-sm"> </td>
        <td><input type="text" name="telefono1" class="form-control form-control-sm"> </td>
        <td><input type="text" name="distrito1" class="form-control form-control-sm"> </td>
        <td><input type="text" name="terminal1" class="form-control form-control-sm"> </td>
        <td><input type="text" name="st1" class="form-control form-control-sm"> </td>
        <td><input type="text" name="par1" class="form-control form-control-sm"> </td>
        <td><input type="text" name="dispositivo1" class="form-control form-control-sm"> </td>
        <td><input type="text" name="localiza1" class="form-control form-control-sm"> </td>
    </tr>
    <tr>
        <td><input type="text" name="tarjlin2a" class="form-control form-control-sm"> </td>
        <td><input type="text" name="telefono2" class="form-control form-control-sm"> </td>
        <td><input type="text" name="distrito2" class="form-control form-control-sm"> </td>
        <td><input type="text" name="terminal2" class="form-control form-control-sm"> </td>
        <td><input type="text" name="st2" class="form-control form-control-sm"> </td>
        <td><input type="text" name="par2" class="form-control form-control-sm"> </td>
        <td><input type="text" name="dispositivo2" class="form-control form-control-sm"> </td>
        <td><input type="text" name="localiza2" class="form-control form-control-sm"> </td>
    </tr>
    
    </tbody>
    </table>
 </div>                                                          
</div>       
<!-- ----------------------------------------------------------------------- -->

<label for="infocontrato">Datos de contrato:</label>
<div class="form-row">
 <div class="table-responsive-sm">
    <table class="table table-bordered table-sm striped table-hover dt-responsive" id="TablaContrato" width="100%">
	
    <tr class="text-center" style="font-size:.75rem;">
        <th style="width:9px;">EQ. O SER.1</th>
		<td><input type="text" name="eqser11" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser12" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser13" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser14" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser15" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser16" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser17" class="form-control form-control-sm"> </td>
    </tr>
    <tr class="text-center" style="font-size:.75rem;">
        <th style="width:9px;">DESCRIPCION</th>
		<td><input type="text" name="descripcion11" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion12" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion13" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion14" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion15" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion16" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion17" class="form-control form-control-sm"> </td>
    </tr>
    <tr class="text-center" style="font-size:.75rem;">
        <th style="width:9px;">CANTIDAD</th>
		<td><input type="text" name="cantidad11" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad12" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad13" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad14" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad15" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad16" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad17" class="form-control form-control-sm"> </td>
    </tr>
	    <tr class="text-center" style="font-size:.75rem;">
        <th style="width:9px;">EQ. O SER.2</th>
		<td><input type="text" name="eqser21" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser22" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser23" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser24" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser25" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser26" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser27" class="form-control form-control-sm"> </td>
    </tr>
    <tr class="text-center" style="font-size:.75rem;">
        <th style="width:9px;">DESCRIPCION</th>
		<td><input type="text" name="descripcion21" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion22" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion23" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion24" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion25" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion26" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion27" class="form-control form-control-sm"> </td>
    </tr>
    <tr class="text-center" style="font-size:.75rem;">
        <th style="width:9px;">CANTIDAD</th>
		<td><input type="text" name="cantidad21" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad22" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad23" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad24" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad25" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad26" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad27" class="form-control form-control-sm"> </td>
    </tr>
	
    <tbody>
		<tr>
		</tr>
    </tbody>
    </table>
 </div>                                                          
</div>  
                                                   
<!-- ----------------------------------------------------------------------- -->
<label for="infoadsl">Información ADSL:</label>
<div class="form-row">
 <div class="table-responsive-sm">
    <table class="table table-bordered table-sm striped table-hover dt-responsive" id="TablaAdsl" width="100%">
    <thead>
    <tr class="text-center" style="font-size:.9rem;">
        <th style="width:10px;">GRUPO</th>
        <th style="width:10px;">DSLAM</th>
        <th style="width:10px;">CLASE</th>
        <th style="width:10px;">REM. ENTRADA</th>
        <th style="width:10px;">DISP. DIGITAL</th>
        <th style="width:10px;">REM. SALIDA</th>
    </tr>
    </thead> 
    <tbody>
    <tr>
        <td><input type="text" name="grupo" class="form-control form-control-sm"> </td>
        <td><input type="text" name="dslam" class="form-control form-control-sm"> </td>
        <td><input type="text" name="clase"class="form-control form-control-sm"> </td>
        <td><input type="text" name="rementrada" class="form-control form-control-sm"> </td>
        <td><input type="text" name="dispdigital" class="form-control form-control-sm"> </td>
        <td><input type="text" name="remsalida" class="form-control form-control-sm"> </td>
    </tr>
   
    </tbody>
    </table>
 </div>                                                          
</div>  

<label for="infoadsl">Información PRODUCTO:</label>
<div class="form-row">
 <div class="table-responsive-sm">
    <table class="table table-bordered striped table-hover dt-responsive table-sm" id="TablaProd" width="100%">
			<thead>
				<tr class="text-center" style="font-size:.9rem;">
					<th rowspan="2" style="align:center;">PRODUCTO</th>
					<th colspan="2">CONFIGURADA</th>
					<th colspan="2">CONFIGURADA</th>
					<th rowspan="2" style="align:center;">PERFIL CONF.</th>
				</tr>
				<tr class="text-center" style="font-size:.9rem;">
					<th>VELOCIDAD</th>
					<th>DESCRIPCION</th>
					<th>VELOCIDAD</th>
					<th>DESCRIPCION</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><input type="text" name="producto" value="" class="form-control form-control-sm"></th>
					<td><input type="text" name="confvelocidad" class="form-control form-control-sm"></td>
					<td><input type="text" name="confdescripcion" class="form-control form-control-sm"></td>
					<td><input type="text" name="contvelocidad" class="form-control form-control-sm"></td>
					<td><input type="text" name="contdescripcion" class="form-control form-control-sm"></td>
					<th><input type="text" name="perfilconf" class="form-control form-control-sm"></th>
				</tr>
			</tbody>
		</table>
</div>                                                          
</div>  
		

        
           <div class="form-row mt-1">
            <div class="form-group col-md-2">
                  <select class="form-control form-control-sm" name="nvoTecnico" id="nvoTecnico" style="width: 100%;" required>
                  <option value="">Selecione Técnico</option>
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
            <div class="form-group col-md-2">
			  <select class="form-control form-control-sm" name="nvoAlmacen" id="nvoAlmacen" data-toggle="tooltip" title="Almacen Asignado">
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
            <div class="form-group col-md-2">
			  <select class="form-control form-control-sm" name="nvoEstatus" id="nvoEstatus" data-toggle="tooltip" title="Estatus">
				<option selected>Seleccione Estatus</option>
				<option value="0">Pendiente</option>
				<option value="1">Liquidado</option>
				<option value="2">Objetado</option>
			  </select>			  
            </div>
			<div class="form-group col-md-6">
			   <textarea class="form-control form-control-sm" id="" name="nvaObservacion" value=""  placeholder="Observaciones" data-toggle="tooltip" title="Observaciones" rows="1"></textarea>
				<!-- <input type="text" class="form-control form-control-sm" id="" name="nvaObservacion" value=""  placeholder="Observaciones" data-toggle="tooltip" title="Observaciones">	-->
			</div>
          </div>                    
         
         </div>
        </div>
      
      </div>    <!-- fin Modal-body  -->
      <!-- Modal footer -->
      <div class="modal-footer colorbackModal">
       
        <button type="button" class="btn btn-primary btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Guardar</button>
      
  </div>
     </form>	<!-- fin del form -->
    </div> <!-- fin del modal-content -->
  </div>
</div>  
<!-- =================================================================================================== -->	  
<!-- ======================================== MODAL EDITAR OS ======================================-->
   <div class="modal fade " id="modalEditarOsvilla" role="dialog" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-xlg" role="document">
   
    <div class="modal-content">
    <form role="form" name="formularioEditarOSVilla" id="formularioEditarOSVilla" >
      <!-- Modal Header -->
      <div class="modal-header colorbackModal">
   
            <h4 class="modal-title">Editar OS</h4>
        
            <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
     <!-- Modal body
        <div class="form-row">
         <div class="form-group col-md-3">
            <input type="text" class="form-control form-control-sm" id="" name="" placeholder="" data-toggle="tooltip" title="" required>
         </div>
        </div>      
	-->
      <div class="modal-body">
           
        <div class="box-body">
        </div>   

        <div class="card card-info">
         <div class="card-body">
         <label for="datoscliente">Datos Cliente:</label>
         
        <div class="form-row">
         <div class="form-group col-md-3">
            <input type="text" class="form-control form-control-sm" id="editarArea" name="editarArea" value="" placeholder="Area" data-toggle="tooltip" title="Área" required>
			<input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
         </div>
         <div class="form-group col-md-2">
            <input type="text" class="form-control form-control-sm" id="editarOs" name="editarOs" placeholder="O. de S." data-toggle="tooltip" title="No. Orden de Servicio" readonly>
         </div>
         <div class="form-group col-md-2">
            <input type="text" class="form-control form-control-sm" id="editarTipoOs" name="editarTipoOs" value="" placeholder="Tipo OS" data-toggle="tooltip" title="Tipo Orden de Servicio" required>
         </div>
         <div class="form-group col-md-5">
            <input type="text" class="form-control form-control-sm" id="editarContratante" name="editarContratante" value="" placeholder="Nom. Contratante" data-toggle="tooltip" title="Nombre del Contratante" required>
         </div>
        </div>     
        
        <div class="form-row">
         <div class="form-group col-md-2">
            <input type="date" class="form-control form-control-sm" id="editarFechaCita" name="editarFechaCita" placeholder="Fecha Cita" data-toggle="tooltip" title="Fecha cita" required>
         </div>
         <div class="form-group col-md-5">
            <input type="text" class="form-control form-control-sm" id="editarDomicilio" name="editarDomicilio" value=""  placeholder="Domicilio" data-toggle="tooltip" title="Domicilio actual" required>
         </div>
         <div class="form-group col-md-2">
            <input type="text" class="form-control form-control-sm" id="editarCiudad" name="editarCiudad" value="" placeholder="Ciudad" data-toggle="tooltip" title="Ciudad" required>
         </div>
         <div class="form-group col-md-3">
			  <select class="form-control form-control-sm" id="editarEstado" name="editarEstado"  data-toggle="tooltip" title="Estado de Residencia">
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
        </div>      
        
        <div class="form-row">
         <div class="form-group col-md-2">
            <input type="text" class="form-control form-control-sm" id="editarFolio" name="editarFolio" placeholder="Folio Pisaplex" data-toggle="tooltip" title="Folio Pisaplex" required>
         </div>
         <div class="form-group col-md-1">
            <input type="text" class="form-control form-control-sm" id="editarPrioridad" name="editarPrioridad" value="" placeholder="Prioridad" data-toggle="tooltip" title="Prioridad" required>
         </div>
         <div class="form-group col-md-3">
            <input type="text" class="form-control form-control-sm" id="editarZona" name="editarZona" value="" placeholder="Zona" data-toggle="tooltip" title="Zona" required>
         </div>
         <div class="form-group col-md-3">
            <input type="tel" class="form-control form-control-sm" id="editarTelefono" name="editarTelefono" value="" placeholder="Télefono" data-toggle="tooltip" title="Télefono" required>
         </div>
         <div class="form-group col-md-3">
            <input type="tel" class="form-control form-control-sm" id="editarTelContacto" name="editarTelContacto" value=""  placeholder="Tel. Contacto" data-toggle="tooltip" title="Tel. contacto">
         </div>
         
        </div>      
        
        <div class="form-row">
         <div class="form-group col-md-2">
            <input type="text" class="form-control form-control-sm" id="editarTipoCliente" name="editarTipoCliente" value="" placeholder="Tipo  Cliente" data-toggle="tooltip" title="Tipo Cliente">
         </div>
         <div class="form-group col-md-4">
            <input type="tel" class="form-control form-control-sm" id="editarTelCelular" name="editarTelCelular" value=""  placeholder="Tel. Celular" data-toggle="tooltip" title="Tel. Celular">
         </div>
         
         <div class="form-group col-md-4">
            <input type="mail" class="form-control form-control-sm" id="editarEmail" name="editarEmail" value="" placeholder="email" data-toggle="tooltip" title="email">
         </div>
		 
         <div class="form-group col-md-2">
            <input type="date" class="form-control form-control-sm" id="editarFechaAsigna" name="editarFechaAsigna" placeholder="Fecha Asignación" data-toggle="tooltip" title="Fecha Asignación" required>
         </div>
		 
        </div>      

<div class="form-row">
 <div class="table-responsive-sm">
    <table class="table table-bordered table-sm striped table-hover dt-responsive" id="TablaOS" width="100%">
    <thead>
    <tr class="text-center" style="font-size:.9rem;">
        <th style="width:10px;">TARJ-LIN-1</th>
        <th style="width:10px;">TELEFONO</th>
        <th style="width:10px;">DISTRITO</th>
        <th style="width:10px;">TERMINAL</th>
        <th style="width:10px;">ST.</th>
        <th style="width:10px;">PAR</th>
        <th style="width:10px;">DISPOSIT.</th>
        <th style="width:10px;">LOCALIZA</th>
    </tr>
    </thead> 
    <tbody>
    <tr>
        <td><input type="text" id="tarjlin1a" name="tarjlin1a" class="form-control form-control-sm"> </td>
        <td><input type="text" id="telefono1" name="telefono1" class="form-control form-control-sm"> </td>
        <td><input type="text" id="distrito1" name="distrito1" class="form-control form-control-sm"> </td>
        <td><input type="text" id="terminal1" name="terminal1" class="form-control form-control-sm"> </td>
        <td><input type="text" id="st1" name="st1" class="form-control form-control-sm"> </td>
        <td><input type="text" id="par1" name="par1" class="form-control form-control-sm"> </td>
        <td><input type="text" id="dispositivo1" name="dispositivo1" class="form-control form-control-sm"> </td>
        <td><input type="text" id="localiza1" name="localiza1" class="form-control form-control-sm"> </td>
    </tr>
    <tr>
        <td><input type="text" id="tarjlin2a" name="tarjlin2a" class="form-control form-control-sm"> </td>
        <td><input type="text" id="telefono2" name="telefono2" class="form-control form-control-sm"> </td>
        <td><input type="text" id="distrito2" name="distrito2" class="form-control form-control-sm"> </td>
        <td><input type="text" id="terminal2" name="terminal2" class="form-control form-control-sm"> </td>
        <td><input type="text" id="st2" name="st2" class="form-control form-control-sm"> </td>
        <td><input type="text" id="par2" name="par2" class="form-control form-control-sm"> </td>
        <td><input type="text" id="dispositivo2" name="dispositivo2" class="form-control form-control-sm"> </td>
        <td><input type="text" id="localiza2" name="localiza2" class="form-control form-control-sm"> </td>
    </tr>
    
    </tbody>
    </table>
 </div>                                                          
</div>       
<!-- ----------------------------------------------------------------------- -->

<label for="infocontrato">Datos de contrato:</label>
<div class="form-row">
 <div class="table-responsive-sm">
    <table class="table table-bordered table-sm striped table-hover dt-responsive" id="TablaContrato" width="100%">
	
    <tr class="text-center" style="font-size:.75rem;">
        <th style="width:9px;">EQ. O SER.1</th>
		<td><input type="text" name="eqser11" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser12" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser13" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser14" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser15" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser16" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser17" class="form-control form-control-sm"> </td>
    </tr>
    <tr class="text-center" style="font-size:.75rem;">
        <th style="width:9px;">DESCRIPCION</th>
		<td><input type="text" name="descripcion11" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion12" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion13" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion14" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion15" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion16" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion17" class="form-control form-control-sm"> </td>
    </tr>
    <tr class="text-center" style="font-size:.75rem;">
        <th style="width:9px;">CANTIDAD</th>
		<td><input type="text" name="cantidad11" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad12" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad13" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad14" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad15" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad16" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad17" class="form-control form-control-sm"> </td>
    </tr>
	    <tr class="text-center" style="font-size:.75rem;">
        <th style="width:9px;">EQ. O SER.2</th>
		<td><input type="text" name="eqser21" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser22" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser23" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser24" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser25" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser26" class="form-control form-control-sm"> </td>
		<td><input type="text" name="eqser27" class="form-control form-control-sm"> </td>
    </tr>
    <tr class="text-center" style="font-size:.75rem;">
        <th style="width:9px;">DESCRIPCION</th>
		<td><input type="text" name="descripcion21" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion22" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion23" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion24" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion25" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion26" class="form-control form-control-sm"> </td>
		<td><input type="text" name="descripcion27" class="form-control form-control-sm"> </td>
    </tr>
    <tr class="text-center" style="font-size:.75rem;">
        <th style="width:9px;">CANTIDAD</th>
		<td><input type="text" name="cantidad21" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad22" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad23" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad24" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad25" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad26" class="form-control form-control-sm"> </td>
		<td><input type="text" name="cantidad27" class="form-control form-control-sm"> </td>
    </tr>
	
    <tbody>
		<tr>
		</tr>
    </tbody>
    </table>
 </div>                                                          
</div>  

                                                   
<!-- ----------------------------------------------------------------------- -->
<label for="infoadsl">Información ADSL:</label>
<div class="form-row">
 <div class="table-responsive-sm">
    <table class="table table-bordered table-sm striped table-hover dt-responsive" id="TablaAdsl" width="100%">
    <thead>
    <tr class="text-center" style="font-size:.9rem;">
        <th style="width:10px;">GRUPO</th>
        <th style="width:10px;">DSLAM</th>
        <th style="width:10px;">CLASE</th>
        <th style="width:10px;">REM. ENTRADA</th>
        <th style="width:10px;">DISP. DIGITAL</th>
        <th style="width:10px;">REM. SALIDA</th>
    </tr>
    </thead> 
    <tbody>
    <tr>
        <td><input type="text" id="grupo" name="grupo" class="form-control form-control-sm"> </td>
        <td><input type="text" id="dslam" name="dslam" class="form-control form-control-sm"> </td>
        <td><input type="text" id="clase" name="clase" class="form-control form-control-sm"> </td>
        <td><input type="text" id="rementrada" name="rementrada" class="form-control form-control-sm"> </td>
        <td><input type="text" id="dispdigital" name="dispdigital" class="form-control form-control-sm"> </td>
        <td><input type="text" id="remsalida" name="remsalida" class="form-control form-control-sm"> </td>
    </tr>
   
    </tbody>
    </table>
 </div>                                                          
</div>  

<label for="infoadsl">Información PRODUCTO:</label>
<div class="form-row">
 <div class="table-responsive-sm">
    <table class="table table-bordered striped table-hover dt-responsive table-sm" id="TablaProd" width="100%">
			<thead>
				<tr class="text-center" style="font-size:.9rem;">
					<th rowspan="2" style="align:center;">PRODUCTO</th>
					<th colspan="2">CONFIGURADA</th>
					<th colspan="2">CONFIGURADA</th>
					<th rowspan="2" style="align:center;">PERFIL CONF.</th>
				</tr>
				<tr class="text-center" style="font-size:.9rem;">
					<th>VELOCIDAD</th>
					<th>DESCRIPCION</th>
					<th>VELOCIDAD</th>
					<th>DESCRIPCION</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><input type="text" name="producto"class="form-control form-control-sm"></th>
					<td><input type="text" name="confvelocidad" class="form-control form-control-sm"></td>
					<td><input type="text" name="confdescripcion" class="form-control form-control-sm"></td>
					<td><input type="text" name="contvelocidad" class="form-control form-control-sm"></td>
					<td><input type="text" name="contdescripcion" class="form-control form-control-sm"></td>
					<th><input type="text" name="perfilconf" class="form-control form-control-sm"></th>
				</tr>
			</tbody>
		</table>
</div>                                                          
</div>  
		

        
           <div class="form-row mt-1">
            <div class="form-group col-md-2">
                  <select class="form-control form-control-sm" name="editarTecnico" id="editarTecnico" style="width: 100%;" required>
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
            <div class="form-group col-md-2">
			  <select class="form-control form-control-sm" name="editarAlmacen" id="editarAlmacen" data-toggle="tooltip" title="Almacen Asignado">
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
			  <select class="form-control form-control-sm" name="editarEstatus" id="editarEstatus" data-toggle="tooltip" title="Estatus">
				<option value="0">Pendiente</option>
				<option value="1">Liquidado</option>
				<option value="2">Objetado</option>
			  </select>			  
            </div>
			<div class="form-group col-md-6">
			    <textarea class="form-control form-control-sm" id="editarObservacion" name="editarObservacion" value="" placeholder="Observaciones" data-toggle="tooltip" title="Observaciones" rows="1"></textarea>
				<!-- <input type="text" class="form-control form-control-sm" id="editarObservacion" name="editarObservacion" value="" placeholder="Observaciones" data-toggle="tooltip" title="Observaciones">	-->
			</div>
          </div>                    
         
         </div>
        </div>
      
      </div>    <!-- fin Modal-body  -->
      <!-- Modal footer -->
      <div class="modal-footer colorbackModal">
       
        <button type="button" class="btn btn-primary btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Guardar</button>
      
  </div>
     </form>	<!-- fin del form -->
    </div> <!-- fin del modal-content -->
  </div>
</div>  

<!-- ====================================MODAL LIQUIDACION============================================================= -->	  
<!-- Modal -->
<div class="modal fade" id="modalLiquidarOsvilla" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Datos de Liquidación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Liquidacion</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="imagenes-tab" data-toggle="tab" href="#imagenes" role="tab" aria-controls="profile" aria-selected="false">Imagenes</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Otros</a>
  </li>
  
</ul>
<div class="tab-content" id="myTabContent">

  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"></br>
		
    <h4 class="text-center" id="ordenServicio"></h4>
  
	<form action="" id="OsVillaLiquidacion">
	   <div class="col-sm-12 ">
	   <hr class="my-3">
       
        <div class="form-row mt-1">
          
            <div class="col-sm-2 pb-2 ">
                <label for="exampleAccount">Localización</label>
                <input type="text" class="form-control form-control-sm" id="" placeholder="">
            </div>
            
            <div class="col-sm-2 pb-2 ">
                <label for="entrada">Rem. Entrada</label>
                <input type="text" class="form-control form-control-sm" id="" placeholder="">
            </div>
            
            <div class="col-sm-2 pb-2 ">
                <label for="">Rem. Salida</label>
                <input type="text" class="form-control form-control-sm" id="" placeholder="">
            </div>

           <div class="col-sm-2 pb-2">
            <label for="modem">&nbsp Modem &nbsp</label>
             <div class="form-check form-check-inline">
               <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" name="" id="" value="option1"> &nbspSí
              </label>
             </div>
            </div>
                        
           <div class="col-sm-2 pb-2">
            <label for="estatus">&nbsp E. Exitosa&nbsp</label>
             <div class="form-check form-check-inline">
               <label class="form-check-label"> &nbsp
                  <input class="form-check-input" type="checkbox" name="" id="" value="option2">&nbsp Sí
              </label>
             </div>
           </div>

           <div class="col-sm-2 pb-2">
            <label for="video"> Claro Video</label>
             <div class="form-check form-check-inline">
               <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" name="" id="" value="option3"> &nbsp Sí
              </label>
             </div>
           </div>
            
	   </div> 
	   
	   <div class="form-row mt-1">
           
            <div class="col-sm-2 pb-2 ">
                <label for="">Bajante</label>
                <input type="text" class="form-control form-control-sm" id="" placeholder="">
            </div>
            <div class="col-sm-2 pb-2 ">
                <label for="">Rosetas</label>
                <input type="text" class="form-control form-control-sm" id="" placeholder="">
            </div>
            <div class="col-sm-2 pb-2 ">
                <label for="">Ptes Central</label>
                <input type="number" class="form-control form-control-sm" id="" min="0" placeholder="">
            </div>
            <div class="col-sm-2 pb-2 ">
                <label for="">Ptes Distrito</label>
                <input type="number" class="form-control form-control-sm" id="" min="0" placeholder="">
            </div>
            <div class="col-sm-1 pb-2 ">
                <label for="">DITS</label>
                <input type="number" class="form-control form-control-sm" id="" min="0" placeholder="">
            </div>
            <div class="col-sm-3 pb-2 ">
                <label for="">Folio Liq.</label>
                <input type="text" class="form-control form-control-sm" id="" placeholder="">
            </div>
	   
	   </div> 

	   <div class="form-row mt-1">
           
            <div class="col-sm-2 pb-2 ">
                <label for="">Armella</label>
                <input type="number" class="form-control form-control-sm" id="" min="0" placeholder="">
            </div>
            <div class="col-sm-2 pb-2 ">
                <label for="">Argolla</label>
                <input type="number" class="form-control form-control-sm" id="" min="0" placeholder="">
            </div>
            <div class="col-sm-2 pb-2 ">
                <label for="">Marfil</label>
                <input type="number" class="form-control form-control-sm" id="" min="0" placeholder="">
            </div>
            <div class="col-sm-2 pb-2 ">
                <label for="">Cinchos</label>
                <input type="number" class="form-control form-control-sm" id="" min="0" placeholder="">
            </div>
            <div class="col-sm-2 pb-2 ">
                <label for="">Tensores</label>
                <input type="number" class="form-control form-control-sm" id="" min="0" placeholder="">
            </div>
            <div class="col-sm-2 pb-2 ">
                <label for="">Taquetes</label>
                <input type="text" class="form-control form-control-sm" id="" placeholder="">
            </div>
	   
	   </div> 

	   <div class="form-row mt-1">
           
            <div class="col-sm-3 pb-1 ">
                <label for="">Mang. Ran.</label>
                <input type="number" class="form-control form-control-sm" id="" min="0" placeholder="">
            </div>
            <div class="col-sm-3 pb-1 ">
                <label for="">Muela</label>
                <input type="number" class="form-control form-control-sm" id="" min="0" placeholder="">
            </div>
            <div class="col-sm-3 pb-1 ">
                <label for="">Ducto</label>
                <input type="number" class="form-control form-control-sm" id="" min="0" placeholder="">
            </div>
            <div class="col-sm-3 pb-1 ">
                <label for="">F. Liquida</label>
                <input type="date" class="form-control form-control-sm" id="" placeholder="">
            </div>
	   
	   </div> 
	   
	   	   <div class="form-row mt-1">
            <div class="col-sm-12 pb-1 ">
                <label for="">Otros</label>
                <input type="text" class="form-control form-control-sm" id="" placeholder="">
            </div>
		   </div>
	   	   	   	   
	   </div> 
	</form>				

    </div>

<!--===================================================================================== -->  
<div class="tab-pane fade" id="imagenes" role="tabpanel" aria-labelledby="imagenes-tab"> Subir Imagenes </br>
	<form action="upload/imagenes.php" class="dropzone" id="my-dropzone" method="post">
	<!--
		  <input type="text" name="directory" value="super">	
		  <input type="submit" value="enviar">
	-->
	</form>
		<p style="margin-left:2em" id="output"></p>
</div>
<!--===================================================================================== -->   

<!--===================================================================================== -->  
<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"> Otros </br>
<!--
<form>	  
    <div class="col-md-10 offset-md-1">
                    <span class="anchor" id="formComplex"></span>
                    <hr class="my-3">
                    <h3>Complex Form Example </h3>
                    
                    <div class="form-row mt-4">
                        <div class="col-sm-5 pb-3">
                            <label for="exampleAccount">Account #</label>
                            <input type="text" class="form-control form-control-sm" id="exampleAccount" placeholder="XXXXXXXXXXXXXXXX">
                        </div>
                        <div class="col-sm-3 pb-3">
                            <label for="exampleCtrl">Control #</label>
                            <input type="text" class="form-control form-control-sm" id="exampleCtrl" placeholder="0000">
                        </div>
                        <div class="col-sm-4 pb-3">
                            <label for="exampleAmount">Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend"></div>
                                <input type="text" class="form-control form-control-sm" id="exampleAmount" placeholder="Amount">
                            </div>
                        </div>
                        <div class="col-sm-6 pb-3">
                            <label for="exampleFirst">First Name</label>
                            <input type="text" class="form-control" id="exampleFirst">
                        </div>
                        <div class="col-sm-6 pb-3">
                            <label for="exampleLast">Last Name</label>
                            <input type="text" class="form-control" id="exampleLast">
                        </div>
                        <div class="col-sm-6 pb-3">
                            <label for="exampleCity">City</label>
                            <input type="text" class="form-control" id="exampleCity">
                        </div>
                        <div class="col-sm-3 pb-3">
                            <label for="exampleSt">State</label>
                            <select class="form-control" id="exampleSt">
                                <option>Pick a state</option>
                            </select>
                        </div>
                        <div class="col-sm-3 pb-3">
                            <label for="exampleZip">Postal Code</label>
                            <input type="text" class="form-control" id="exampleZip">
                        </div>
                        <div class="col-md-6 pb-3">
                            <label for="exampleAccount">Color</label>
                            <div class="form-group small">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> Blue
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Red
                                    </label>
                                </div>
                                <div class="form-check form-check-inline disabled">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3" disabled=""> Green
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option4"> Yellow
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option5"> Black
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option6"> Orange
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 pb-3">
                            <label for="exampleMessage">Message</label>
                            <textarea class="form-control" id="exampleMessage"></textarea>
                            <small class="text-info">
                              Add the packaging note here.
                            </small>
                        </div>
                        <div class="col-12">
                            <div class="form-row">
                                 <label class="col-md col-form-label"  for="name">Generated Id</label>
                                 <input type="text" class="form-control col-md-4" name="gid" id="gid" />
                                 <label class="col-md col-form-label"  for="name">Date Assigned</label>
                                 <input type="text" class="form-control col-md-4" name="da" id="da" />
                            </div>
                        </div>
                    </div>

                </div>	  
</form>		
-->
</div>
<!--===================================================================================== -->   

</div>        
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
<!-- =================================================================================================== -->	  
