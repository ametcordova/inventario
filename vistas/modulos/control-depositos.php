<style>
  .select2-results__options {
    font-size: 14px !important;
  }
#dt-crtl-depositos {
  overflow: auto;
}  
.boton2 {
  color:wheat;
  background-color: #008CBA;
  } /* Blue */
  .seleccionado{
    background-color: #008CBA;
  }
</style>
<script>
    document.addEventListener("DOMContentLoaded", ()=>{
      // Invocamos cada 5 minutos ;
      const milisegundos = 500*1000;
      setInterval(()=>{
        //console.log("500 segundos.. refrescado")
        fetch("vistas/modulos/refrescar.php");
        // No esperamos la respuesta de la petición porque no nos importa
      },milisegundos);
    });
  </script>
<?php
require_once 'controladores/control-depositos.controlador.php';
require_once 'modelos/control-depositos.modelo.php';
$tabla = "usuarios";
$usuario = $_SESSION['id'];
$module = "pdeposito";
$campo = "administracion";
$acceso = accesomodulo($tabla, $usuario, $module, $campo);
$fechaHoy = date("Y-m-d");
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="background-color:darkslategrey">
  <!-- Content Header (Page header) -->
  <section class="content-header m-0 ml-2 p-0">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h4 class="titleout">Control Depósitos</h4>
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

      <div class="card-header col-md-12">

        <div class="col-md-2 d-inline" >
          <?php if (getAccess($acceso, ACCESS_ADD)) { ?>
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalAgregarDeposito">
              <span class="fa fa-plus-circle"></span> Agregar Depósito
            </button>
          <?php } ?>
        </div>
        
        <div class="col-md-2 d-inline">
          <?php if (getAccess($acceso, ACCESS_ADD)) { ?>
            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalAgregarcuentahabiente" title="Agregar Cuentahabiente">
              <span class="fa fa-plus-circle"></span> Agregar Cuentah.
            </button>
          <?php } ?>
        </div>
        <div class="col-md-1 d-inline">  
          <button class="btn btn-danger btn-sm" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>
        </div>

        <div class="col-md-3 d-inline">
          <!-- Date range -->
          <?php if (getAccess($acceso, ACCESS_VIEW)) { ?>
            <button type="button" class="btn btn-dark btn-sm ml-1 mr-2 " id="daterange-btn-ctrldepositos">
              <span>
                <i class="fa fa-calendar"></i> Rango de fecha
              </span>
              <i class="fa fa-caret-down"></i>
            </button>

            <!-- <button class="btn btn-success btn-sm " onclick="dt_crtl_depositos()">
              <i class="fa fa-list"></i> Listar
            </button> -->
          <?php } ?>
        </div>

        <div class="card-tools">
        <button type="button" class="btn btn-tool" title="Refresh" onclick="location.reload()">
            <i class="fa fa-refresh"></i></button>
          <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div>
      </div>

      <div class="row">
        
      </div>

      <div class="card-body">
      
        <div class="card">
          <div id="ohsnap" style="z-index:100;"></div>
          <div class="card-body p-1">
            <!-- <table class="table table-bordered table-hover compact table-striped dt-responsive display nowrap" id="dt-crtl-depositos" cellspacing="0" width="100%"> -->
            <table class="table table-bordered table-hover compact table-striped" id="dt-crtl-depositos" cellspacing="0" style="width:100%">
              <thead class="thead-dark" >
                <tr style="font-size:0.80em">
                  <th translate="no" style="width:2em;">#</th>
                  <th translate="no" style="width:21em;">Nombre</th>
                  <th translate="no">Motivo</th>
                  <th translate="no" style="width:6em;">Importe</th>
                  <th translate="no" style="width:5em;">Comisión</th>
                  <th translate="no" style="width:12em;">Destino</th>
                  <th translate="no" style="width:14em;">Referencia</th>
                  <th translate="no" style="width:7em;">F. Movto</th>
                  <th translate="no" style="width:4em;">Suc.</th>
                  <th translate="no" style="width:5.5em;">Acción</th>
                </tr>
              </thead>
              <tbody style="font-size:0.90em">

              </tbody>

              <tfoot class="thead-dark">
                <tr style="font-size:0.80em">
                  <th colspan="2" style="width:25em;"></th>
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
          </div>  <!-- /.card-body  -->
        </div>   <!-- /.card -->
      </div>   <!-- /.card-body -->

    </div>   <!-- /.card -->

  </section>  <!-- /.content -->
</div> <!-- /.content-wrapper -->

<!-- ==============================================================================
            MODAL AGREGAR DEPOSITO
===============================================================================-->
 <div class="modal fade" id="modalAgregarDeposito" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
   
    <div class="modal-content ui-widget-content">
     <form role="form" name="" id="form-AgregaDeposito" method="POST">
        <!-- Modal Header -->
        <div class="modal-header colorbackModal p-1">
            <h3 class="modal-title">Agregar Depósito</h3>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
          
        <!-- Modal body -->
        <div class="modal-body">
            
          <div class="box-body">

          <div class="form-group p-0" id="form-benef">
            <label for="inputNombre">Nombre Beneficiario</label>
              <!--CONSULTA DE PRODUCTOS POR AJAX REMOTE DATA-->
                    <select class="form-control" name="nvoBeneficiario" id="nvoBeneficiario" style="width:100%;" tabindex="1" >
                    </select>
                    <input type="hidden" name="idDeUsuario" value="<?php echo $_SESSION['id']; ?>">
                    <input type="hidden" name="" value="<?php echo $_SESSION['id']; ?>">
                    <input type="hidden" name="identifica" id="identifica" value=0>
          </div>

          <div class="form-group p-0" id="edit-benef">
            <label for="inputBenef">Nombre Beneficiario</label>
            <input type="text" class="form-control" id="beneficiario" name="beneficiario" >
          </div>

          <div class="form-group p-0">
            <label for="inputNombre">Motivo depósito</label>
            <input type="text" class="form-control" id="nvoMotivoDeposito" name="nvoMotivoDeposito" placeholder="" tabindex="2" required>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4 pt-0">
              <label for="inputNombre">Importe</label>
              <input type="number" class="form-control" id="nvoImporte" name="nvoImporte" step="any" placeholder="$" tabindex="3" required>
            </div>

            <div class="form-group col-md-4 pt-0">
              <label for="inputNombre">Comisón</label>
              <input type="number" class="form-control" id="nvoComision" name="nvoComision" step="any" value=0 placeholder="$" tabindex="4" required>
            </div>

            <div class="form-group col-md-4 pt-0">
              <label for="inputNombre">Total</label>
              <input type="text" class="form-control" id="nvoTotal" name="nvoTotal" step="any"  placeholder="$" required readonly>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6 pt-0">
              <label for="inputNombre">Banco/Emp.</label>
              <input type="text" class="form-control" id="nvoBanco" name="nvoBanco" value="" placeholder="" tabindex="5" required readonly>
              <input type="hidden" id="idBanco" name="idBanco">
            </div>

            <div class="form-group col-md-6 pt-0">
              <label for="inputNombre">Cuenta/Referencia</label>
              <input type="text" class="form-control" id="nvoCuenta" name="nvoCuenta" placeholder="" tabindex="6" required readonly>
            </div>
          </div>

          <div class="form-row">

            <div class="form-group col-md-3 pt-0">
              <label for="inputNombre">Sucursal</label>
                <select class="form-control" name="nvoSucursal" id="nvoSucursal" required tabindex="7" title="Sucursal" >
                  <option value="VLH">VLH - VillaHermosa</option>
                  <option value="TGZ">TGZ - Tuxtla Gtz.</option>
                  <option value="FIP">FIP - Fipabide</option>
                  <option value="LEG">LEG - Personal</option>
                </select>	
            </div>

            <div class="form-group col-md-5 pt-0">
              <label for="inputNombre">Fecha Trans.</label>
              <input type="date" class="form-control" id="nvoFecha" name="nvoFecha" placeholder="" value="" tabindex="8" required>
            </div>

            <div class="form-group col-md-4 pt-0">
              <label for="inputNombre">Estatus</label>
                <select class="form-control" name="nvoEstatus" id="nvoEstatus" required tabindex="9" title="Estatus" >
                  <option value=1>Activo</option>
                  <option value=0>Desactivado</option>
                </select>	
            </div>
          </div>

        </div>   

        </div>    <!-- fin del modal-body -->

        <!-- Modal footer -->
        <div class="modal-footer colorbackModal p-1">
          <button type="button" class="btn btn-primary btn-sm float-left salirfrm" data-dismiss="modal" tabindex="10"><i class="fa fa-reply"></i> 
          Salir
          </button>
          <button type="submit" class="btn btn-success btn-sm enviarfrm" tabindex="11"><i class="fa fa-save"></i> Guardar</button>
        </div>
      
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  <!-- fin del modal -->
<!-- ==================================================================================== -->

<!-- ==============================================================================
            MODAL AGREGAR CUENTAHABIENTE
===============================================================================-->
<div class="modal fade" id="modalAgregarcuentahabiente" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
   
    <div class="modal-content ui-widget-content">
     <form role="form" name="" id="form-Agregarcuentahabiente" method="POST">
        <!-- Modal Header -->
        <div class="modal-header colorbackModal p-1">
            <h4 class="modal-title">Agregar Cuentahabiente</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        

        <!-- Modal body -->
        <div class="modal-body">
            
          <div class="box-body">

          <div class="form-group p-0">
            <label for="inputNombre">Nombre Cuentahabiente *</label>
                <input type="text" class="form-control" name="nvoCuentaHabiente" id="nvoCuentaHabiente" tabindex="1" required>
                <input type="hidden" name="idDeUsuario" value="<?php echo $_SESSION['id']; ?>">
          </div>

          <div class="form-group p-0">
            <label for="inputNombre">Motivo depósito *</label>
            <input type="text" class="form-control" id="nvoUsoDeposito" name="nvoUsoDeposito" placeholder="Motivo" tabindex="2" required>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12 pt-0">
              <label for="inputNombre">Banco/Emp. *</label>
              <select class="form-control" name="nvoDestinatario" id="nvoDestinatario" style="width: 100%;" tabindex="3" required>
                <option value="">Selecione</option>
                <?php
                  $item="estatus";
                  $valor=1;
                  $destinatario=ControladorCtrolDepositos::ctrMostrarDestinatarios($item, $valor);
                  foreach($destinatario as $key=>$value){
                    echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                  }
                  ?>				  
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6 pt-0">
              <label for="inputNombre">Número Tarjeta *</label>
              <input type="number" class="form-control" id="nvoNumeroTarjeta" name="nvoNumeroTarjeta" step="any" placeholder="" tabindex="4" required>
            </div>

            <div class="form-group col-md-6 pt-0">
              <label for="inputNombre">Cuenta Clabe</label>
              <input type="number" class="form-control" id="nvoCuentaClabe" name="nvoCuentaClabe" step="any" placeholder="" tabindex="5">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6 pt-0">
              <label for="inputNombre">Fecha Pago.</label>
              <input type="date" class="form-control" id="nvoFechaPago" name="nvoFechaPago" placeholder="" value="<?= $fechaHoy ?>" tabindex="6" required>
            </div>

            <div class="form-group col-md-6 pt-0">
              <label for="inputNombre">Estatus</label>
                <select class="form-control" name="nvoStatus" id="nvoStatus" required tabindex="7" title="Estatus" >
                  <option value="1">Activo</option>
                  <option value="0">Desactivado</option>
                </select>	
            </div>
          </div>


        </div>   

        </div>    <!-- fin del modal-body -->

        <!-- Modal footer -->
        <div class="modal-footer colorbackModal p-1">
          <button type="button" class="btn btn-primary btn-sm float-left" data-dismiss="modal" tabindex="9"><i class="fa fa-reply"></i> 
          Salir
          </button>
          <button type="submit" class="btn btn-success btn-sm" tabindex="10"><i class="fa fa-save"></i> Guardar</button>
        </div>
      
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  <!-- fin del modal -->
<!-- ==================================================================================== -->
<script defer src="vistas/js/control-depositos.js?v=03102022"></script>


