<style>
.select2-results__options{
  font-size:13px !important;
}
.modal-body {
  max-height: calc(100vh - 212px);
  overflow-y: auto;
}        
.buttonstyle {
  background-color: lavenderblush;
  width: 20.6rem;
  height: 28rem;

}	
</style>
<script>    //evitar que se desconecte.
    document.addEventListener("DOMContentLoaded", ()=>{
      const milisegundos = 500*1000;      // Invocamos cada 5 minutos ;
      setInterval(()=>{
        fetch("vistas/modulos/refrescar.php");      // No esperamos la respuesta de la petición porque no nos importa
      },milisegundos);
    });
  </script>

<?php
date_default_timezone_set("America/Mexico_City");
error_reporting(E_ALL^E_NOTICE);
$fechaHoy=date("Y-m-d");
$tabla="usuarios";
$module="pcapquejas";
$campo="administracion";
$acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);
?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header p-1">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h4>Capturar Quejas:&nbsp; 
                <small><i class="fa fa-address-book"></i></small>
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
        
         <div class="card-header">

		<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarLORG"><i class="fa fa-plus-circle"></i> Agregar Queja </button>   
    
         <?php if(getAccess($acceso, ACCESS_ADD)){?>
          <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>          
        <?php } ?>
			
                <!-- Date range -->
          <?php if(getAccess($acceso, ACCESS_VIEW)){?>
                    <button type="button" class="btn btn-default btn-sm ml-3 mr-2 " id="daterange-btnOS">
                     <span>
                      <i class="fa fa-calendar"></i> Rango de fecha
                     </span>
                        <i class="fa fa-caret-down"></i>                     
                    </button>
                <button class="btn btn-success btn-sm" onclick="listarQuejas()"><i class="fa fa-eye"></i>  Mostrar</button>
          <?php } ?>
			  <!--<h2 class="card-title">Control de Usuarios</h2> -->
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Ocultar">
              <i class="fa fa-minus"></i></button>
              <button type="button" class="btn btn-tool" onclick="regresar()" title="a Inicio">
              <i class="fa fa-times"></i></button>			  
            </div>

         </div>

        <div class="card-body">

        <div class="card">
            <!--<div class="card-header">
            </div>-->
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered table-hover compact table-striped table-sm" id="DatatableQuejas" cellspacing="0" style="width:100%;">
                <thead class="thead-dark">
                <tr style="height:10px !important; font-size:0.75em !important;">
                    <th style="width:1em;">#</th>
                    <th style="width:3em;">Emp.</th>
                    <th>Tecnico</th>
                    <th>OS</th>
                    <th>Teléfono</th>
                    <th>#OCI</th>
                    <th>Motivo</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Stat</th>
                    <th>Accion</th>
                </tr>
                </thead>
                <tbody style="font-size:0.80em !important;">
                
                </tbody>
                <tfoot class="thead-dark">
                <tr style="height:10px !important; font-size:0.75em !important;">
                    <th style="width:1em;">#</th>
                    <th style="width:3em;">Emp.</th>
                    <th>Tecnico</th>
                    <th>OS</th>
                    <th>Teléfono</th>
                    <th>#OCI</th>
                    <th>Motivo</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Stat</th>
                    <th>Accion</th>
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
 
  <!-- ========================================================================================-->
 <!-- ========================== MODAL AGREGAR QUEJA ====================================-->
 <!-- ========================================================================================-->
 <div class="modal fade" id="modalAgregarLORG" data-backdrop="static" data-keyboard="false">

  <div class="modal-dialog modal-lg">
   
   <div class="modal-content ui-widget-content">

      <!-- Modal Header -->
      <div class="modal-header colorbackModal px-2 py-1">
          <h5 class="modal-title headertitle">Capturar datos de Técnico: &nbsp<?php echo $_SESSION['nombre'];?></h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form role="form" method="POST" id="formularioAgregaLORG">

        <!-- Modal body -->
        <div class="modal-body pb-1">
            
          <div class="card card-info">

            <div class="card-body">

                <div class="form-row pt-0 pb-0">
                <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
                <input type="hidden" name="bandera" id="bandera" value=0>
                <input type="hidden" name="ctrlid" id="ctrlid" value=0>
             
                  <div class="form-group col-md-3">
                    <label class="control-label"><i class="fa fa-calendar"></i> Fecha Capt:</label>
                    <input type="date" class="form-control form-control-sm flag" name="fechacapt" id="fechacapt" value="<?= $fechaHoy?>" placeholder="" tabindex="0" title="Fecha Instalación" required>
                  </div>

                  <div class="form-group col-md-3">
                      <label class="control-label"><i class="fa fa-sort-numeric-asc"></i> No. OS:</label>
                      <input type="number" class="form-control form-control-sm flag" name="numeroos" id="numeroos" value="" tabindex="1" title="Numero de Orden de Servicio" required>
                  </div>

                  <div class="form-group col-md-3">
                    <label class="control-label"><i class="fa fa-phone-square"></i> Teléfono</label>
                    <input type="number" class="form-control form-control-sm flag" name="numtelefono" id="numtelefono" value="" step="any" placeholder="" tabindex="2" title="Numero de Teléfono" required>
                  </div>

                  <div class="form-group col-md-3">
                    <label class="control-label"><i class="fa fa-map-marker"></i> Distrito:</label>
                    <input type="text" class="form-control form-control-sm flag" name="distritoos" id="distritoos" value="" placeholder="" tabindex="3" title="Distrito">
                  </div>


                </div>  <!-- FIN DEL FORM-ROW -->		 

                <div class="form-row pt-0 pb-0">
                    <div class="form-group col-md-12">
                        <label class="control-label"><i class="fa fa-check-square-o"></i> Nombre contratante:</label>
                        <input type="text" class="form-control form-control-sm flag" name="nombrecontrato" id="nombrecontrato" tabindex="4" title="Nombre cliente" pattern="^[A-Za-zÑñÁáÉéÍíÓóÚúÜü\s]+$" required>
                    </div>
                </div>  <!-- FIN DEL FORM-ROW -->

                <div class="form-row pt-0 pb-0">
                    <div class="form-group col-md-12">
                        <label class="control-label"><i class="fa fa-check-square-o"></i> Motivo:</label>
                        <textarea rows="2" class="form-control form-control-sm flag" name="motivo" id="motivo" tabindex="5" title=""></textarea>
                    </div>
                </div>  <!-- FIN DEL FORM-ROW -->

                <div class="form-row pt-0 pb-0">
                <div class="form-group col-md-4">
                        <label class="control-label"><i class="fa fa-road"></i> Folio OCI:</label>
                        <input type="text" class="form-control form-control-sm flag" name="foliooci" id="foliooci" value="" placeholder="" tabindex="6" title="Colonia" required>
                    </div>
                    <div class="form-group col-md-8">
                        <label class="control-label"><i class="fa fa-home"></i> Operador:</label>
                        <input type="text" class="form-control form-control-sm flag" name="operador" id="operador" value="" step="any" placeholder="" tabindex="7" title="Dirección de la Instalación">
                    </div>
                </div>

                <div class="form-row pt-0 pb-0">

                    <div class="form-group col-md-4">
                    <label class="control-label"><i class="fa fa-ship"></i> Inicio Llamada:</label>
                      <div class="input-group date" data-target-input="nearest">
                        <input type="text" class="form-control form-control-sm datetimepicker-input flag" name="datetimepicker1" id="datetimepicker1" data-target="#datetimepicker1" tabindex="8" />
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                        </div>
                      </div>
                    </div>


                    <div class="form-group col-md-4">
                    <label class="control-label"><i class="fa fa-ship"></i> Fin Llamada:</label>
                      <div class="input-group date" data-target-input="nearest">
                        <input type="text" class="form-control form-control-sm datetimepicker-input flag" name="datetimepicker3" id="datetimepicker3" data-target="#datetimepicker3" tabindex="9" />
                        <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="control-label"><i class="fa fa-openid"></i> Minutos:</label>
                        <input type="text" class="form-control form-control-sm text-center" name="totalmin" id="totalmin" value="" placeholder="" title="Numero Tipo"  required readonly>
                    </div>
                </div>
                <!-- style="background-color:blanchedalmond;" -->
                 
                <div class="form-row form-inline m-3" >

                <div class="form-group form-inline col-2">
                </div>
                <div class="form-group form-inline col-4">
                  <div class="form-check form-check-inline">
                    <i class="fa fa-volume-control-phone fa-lg mr-1 text-success"></i>
                    <input class="form-check-input" type="radio" name="medioqueja" id="llamarop" value="op1">
                    <label class="form-check-label" for="inlineRadio1"></label>
                  </div>
                </div>

                <div class="form-group form-inline col-2">
                </div>

                <div class="form-group form-inline col-4 float-left">
                  <div class="form-check form-check-inline">
                    <i class="fa fa-headphones fa-lg mr-1 text-info"></i>
                    <input class="form-check-input" type="radio" name="medioqueja" id="diademaop" value="op2">
                    <label class="form-check-label" for="inlineRadio2"></label>
                  </div>
                </div>

                </div>  <!-- FIN DEL FORM-ROW -->

                <div class="form-row pt-0 pb-0">
                    <div class="form-group col-md-12">
                        <label class="control-label"><i class="fa fa-check-square-o"></i> Observaciones:</label>
                        <textarea rows="2" class="form-control form-control-sm flag" name="altaobservaciones" id="altaobservaciones" value="" tabindex="10" title="Numero Tipo"></textarea>
                    </div>
                </div>  <!-- FIN DEL FORM-ROW -->

            </div>  <!--Fin del card-body -->
          </div>   <!--Fin del card card-info -->
        
        </div> <!--fin del modal-body -->

        <!-- Modal footer -->
        <div class="modal-footer p-1 colorbackModal">
          <button type="button" class="btn btn-primary btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
          <button type="submit" class="btn btn-success btn-sm" id="btnGuardarLORG" tabindex="11"><i class="fa fa-save"></i> Guardar</button>
        </div> <!-- fin Modal footer -->
        
    </form>  <!-- fin del form -->
   </div> <!-- fin del modal-content -->
  </div>   <!-- fin del modal-lg -->
</div>    <!-- fin del modal  <div class="form-row"></div>   -->

    
<script defer src="vistas/js/funciones.js?v=01072022"></script>
<script defer src="vistas/js/adminquejas.js?v=30032022"></script>
