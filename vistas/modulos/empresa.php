<?php
if($_SESSION["perfil"] == "Administrador" ){
?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1>Tablero
                <small>Panel de Control</small>
            </h1>
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
		 
          <h3 class="card-title">Datos de la Empresa:&nbsp;
		   <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button> 
		  </h3>

          <div class="card-tools">
		  
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" onclick="regresar()" title="a Inicio">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
		<form>

		  <div class="form-row">
		   <div class="form-group col-md-6">
			<label for="inputAddress">Razón Social de la Empresa</label>
			<input type="text" class="form-control" id="inputAddress" placeholder="">
		   </div>				
			<div class="form-group col-md-2">
			  <label for="inputZip">Rfc</label>
			  <input type="text" class="form-control" id="inputZip">
			</div>			
			<div class="form-group col-md-4">
			  <label for="inputZip">Slogan</label>
			  <input type="text" class="form-control" id="inputZip">
			</div>			
		  </div>

		  <div class="form-row">
			<div class="form-group col-md-6">
			  <label for="inputCity">Dirección</label>
			  <input type="text" class="form-control" id="inputCity">
			</div>
			<div class="form-group col-md-2">
			  <label for="inputZip">C.P.</label>
			  <input type="text" class="form-control" id="inputZip">
			</div>
			<div class="form-group col-md-2">
			  <label for="inputState">Ciudad</label>
			   <input type="text" class="form-control" id="inputState">
			</div>
			<div class="form-group col-md-2">
			  <label for="inputCity">Estado</label>
			  <input type="text" class="form-control" id="inputCity">
			</div>
		  </div>

		 <div class="form-row">
			<div class="form-group col-md-2">
			  <label for="inputZip">Teléfono Empresa</label>
			  <input type="text" class="form-control" id="inputZip">
			</div>
			<div class="form-group col-md-3">
			  <label for="inputZip">Email Empresa</label>
			  <input type="email" class="form-control" id="inputZip">
			</div>
			<div class="form-group col-md-3">
			  <label for="inputCity">Contacto</label>
			  <input type="text" class="form-control" id="inputCity">
			</div>
			<div class="form-group col-md-2">
			  <label for="inputZip">Teléfono Contacto</label>
			  <input type="text" class="form-control" id="inputZip">
			</div>
			<div class="form-group col-md-2">
			  <label for="inputZip">Email Contacto</label>
			  <input type="email" class="form-control" id="inputZip">
			</div>
		  </div>

		 <div class="form-row">
			<div class="form-group col-md-2">
			  <label for="inputCity">No.Incial Ticket</label>
			  <input type="text" class="form-control" id="inputCity">
			</div>
			<div class="form-group col-md-2">
			  <label for="inputZip">No. Inicial Compras</label>
			  <input type="text" class="form-control" id="inputZip">
			</div>
			<div class="form-group col-md-2">
			  <label for="inputZip">% de IVA</label>
			  <input type="email" class="form-control" id="inputZip">
			</div>
			<div class="form-group col-md-2">
			  <label for="inputZip">% Margen Ganancia</label>
			  <input type="email" class="form-control" id="inputZip">
			</div>
			<div class="form-group col-md-4">
			  <label for="inputZip">Página Web</label>
			  <input type="text" class="form-control" id="inputZip">
			</div>
		  </div>
		<div class="form-group">
            <label for="exampleInputFile">Subir Imagen</label>
            <div class="input-group">
                <div class="custom-file">
                        <input type="file" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Seleccione Archivo</label>
                </div>
                <div class="input-group-append">
                        <span class="input-group-text" id="">Subir</span>
                </div>
            </div>
        </div>		  
		  
		  <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Guardar</button>
		</form>
		  
        </div>
        <!-- /.card-body 
        <div class="card-footer">
          Footer	
        </div>
        <!-- /fin de .card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
}else{
	include "inicio.php";
}	