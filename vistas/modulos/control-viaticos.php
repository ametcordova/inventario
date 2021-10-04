<script>
    document.addEventListener("DOMContentLoaded", ()=>{
      // Invocamos cada 5 minutos ;
      const milisegundos = 500*1000;
      setInterval(()=>{
      // No esperamos la respuesta de la petición porque no nos importa
      //console.log("500 segundos.. refrescado")
      fetch("vistas/modulos/refrescar.php");
      },milisegundos);
    });
  </script>
<?php
    date_default_timezone_set('America/Mexico_City');
    $fechaHoy=date("d/m/Y");
    $yearHoy=date("Y");
    $tabla="usuarios";
    $module="pctviaticos";
    $campo="administracion";
    $acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);    
?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header m-0 p-1">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h4>Control Viáticos:&nbsp; 
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

        <div class="card-header border-success mb-3 py-1" >
		 
          <div class="input-group mb-3 col-md-8" >

                <?php if(getAccess($acceso, ACCESS_ADD)){?> 
                  <button class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#modalAgregarViaticos"><i class="fa fa-plus-circle"></i> Agregar Viáticos</button> 
                <?php } ?>                             

              <button class="btn btn-danger btn-sm mr-2" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button>
            <?php if(getAccess($acceso, ACCESS_VIEW)){?>
              <div>	
                <input type="radio" name="radiofactura" value="todos" >
                  <label class="ml-1">Todos</label>
                <input type="radio" class="ml-1" name="radiofactura" value="porpagar" checked>
                  <label>Por comprobar</label>
                <input type="radio" class="ml-1" name="radiofactura" value="pagado">
                  <label>Comprobados</label>
              </div>		

              <div class="input-group-prepend ml-3">
                      <span class="input-group-text"><i class="fa fa-calendar"></i></span>
              </div>
                <input type="text" class="form-control form-control-sm" placeholder="" name="filterYearViaticos" id="filterYearViaticos" value="<?= $yearHoy?>" data-toggle="tooltip" title="Año" >
                <button class="btn btn-success btn-sm ml-2" onclick="listarViaticos()" ><i class="fa fa-eye"></i>
                  Mostrar
              </button>
            <?php } ?>
          </div>

		
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Ocultar">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" onclick="regresar()" data-toggle="tooltip" title="a Inicio">
              <i class="fa fa-times"></i></button>			  
          </div>
		  		  
        </div>  <!-- fin del card-header -->
 
        <div class="card-body p-1">
          <div class="card">
        <!--<div class="card-header"></div> -->

            <div class="card-body table-responsive-sm p-1">
             <!-- <table id="example" class="display" width="100%"></table> -->
              <table class="table table-bordered compact table-hover table-striped dt-responsive" cellspacing="0" id="TablaViaticos" width="100%">
                <thead class="thead-dark text-center">
                <tr style="font-size:0.80em" class="text-center"> 
					<!-- <th style="width:2%;"><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>-->
                    <th style="width:2%;">#</th>
                    <th>F. Disp.</th>
                    <th>Usuario</th>
                    <th>Descripción</th>
                    <th>Importe</th>
                    <th>Saldo</th>
                    <th style="width:9%;">Admin</th>
                    <th style="width:14%;">Acción</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot class="thead-dark">
                <tr style="font-size:0.80em">
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
  
 <!--=????????=================== MODAL AGREGAR VIATICO ==============================-->
  <div class="modal fade" id="modalAgregarViaticos" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form role="form" name="formularioAgregarViatico" id="formularioAgregarViatico" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal p-2">
            <h4 class="modal-title">Agregar Viáticos</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
           
        <div class="box-body">
        </div>   

        <div class="card card-info">
         <div class="card-body">

            <div class="input-group mb-3">
               <div class="input-group-prepend">
                 <span class="input-group-text"><i class="fa fa-hashtag"></i></span>
              </div>
              <input type="number" class="form-control form-control-sm disabled" placeholder="Número" name="nvoIdViatico" id="nvoIdViatico" value="" required tabindex="1" title="Número" disabled>
              <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-tty"></i></span>
              </div>
                  <select class="form-control form-control-sm" name="nvoComisionadoV" id="nvoComisionadoV" title="Seleccione Técnico" tabindex="2"  required>
                      <option value="" selected>Seleccione Técnico</option>
                      <?php
                        $item=null;
                        $valor=null;
                        $comisionado=ControladorUsuarios::ctrMostrarUsuarios($item, $valor);
                        foreach($comisionado as $key=>$value){
                              echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                        }
                      ?>				  
                  </select>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-list-ol"></i></span>
              </div>
                <select class="form-control form-control-sm" name="nvoConcepto" id="nvoConcepto" required tabindex="3" placeholder="" title="Concepto del gasto" >
                <option value="" selected>Seleccione Concepto</option>
                <option value="1">Gastos a comprobar</option>
                <option value="2">Otro</option>
                </select>	
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
              </div>
              <input type="text" class="form-control form-control-sm" placeholder="Descripción del gasto" name="nvadescripcion" id="nvadescripcion" value="" title="Descripción" tabindex="4">
            </div>

          <div class="form-row">
            
          <div class="input-group mb-3 col-md-12">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-calendar"></i></span>
              </div>
              <input type="date" class="form-control form-control-sm" placeholder="Fecha dispersión" name="nvaFecha" id="nvaFecha" value="<?= $fechaHoy?>" title="Fecha dispersión"  required tabindex="5" >
            </div>

            <!-- <div class="input-group mb-3 col-md-6">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-money"></i></span>
              </div>
              <input type="number" class="form-control form-control-sm" placeholder="Importe" name="nvoImporte" id="nvoImporte" value="" step="any" title="Importe a comprobar" tabindex="6" required>
            </div> -->

          </div>

          <!-- <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-cc-visa"></i></span>
              </div>
                <select class="form-control form-control-sm" name="nvoMedioDeposito" id="nvoMedioDeposito" required placeholder="Medio de deposito" title="Medio del deposito" tabindex="7">
                <option value="" selected>Seleccione Medio de deposito</option>
                <option value="1">Transferencia</option>
                <option value="2">Deposito banco</option>
                <option value="3">OXXO</option>
                <option value="4">Farm. Guadalajara</option>
                <option value="5">Farm. del Ahorro</option>
                <option value="6">Efectivo</option>
                <option value="7">Cheque</option>
                <option value="8">Mexpei</option>
                <option value="9">Otro</option>
                </select>	
            </div> -->

         </div>
        </div>
      
      </div>    <!-- fin del modal-body -->

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal p-2">
       
        <button type="button" class="btn btn-primary btn-sm float-left" data-dismiss="modal" tabindex="12"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-success btn-sm" tabindex="13"><i class="fa fa-save"></i> Guardar</button>
      
      </div>

     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  

<!--================================= MODAL AGREGAR VIATICO =======================================-->
<div class="modal fade" id="modalAgregaViatico" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
	 <form name="formularioAgregaViatico" id="formularioAgregaViatico" method="POST">
      <div class="modal-header colorbackModal p-2">
        <h5 class="modal-title" id="ModalCenterTitleFact"></h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		
      <div class="form-group">
			  <label for="recipient-name" class="col-form-label">Fecha:*</label>
			  <div class="input-group mb-3">
				  <div class="input-group-prepend">
						  <span class="input-group-text"><i class="fa fa-calendar-check-o"></i></span>
				  </div>
				  <input type="date" class="form-control form-control" placeholder="" name="fechagregaviatico" value="" tabindex="1" title="Fecha deposito" required >
				  <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
				  <input type="hidden"  name="registroid" value="">
        </div>
      </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-cc-visa"></i></span>
              </div>
                <select class="form-control form-control" name="mediodeposito" id="mediodeposito" required tabindex="2" placeholder="Medio de deposito" title="Medio del deposito" >
                <option value="" selected>Seleccione Medio de deposito</option>
                <option value="1">Transferencia</option>
                <option value="2">Deposito banco</option>
                <option value="3">OXXO</option>
                <option value="4">Farm. Guadalajara</option>
                <option value="5">Farm. del Ahorro</option>
                <option value="6">Efectivo</option>
                <option value="7">Cheque</option>
                <option value="8">Mexpei</option>
                <option value="9">Otro</option>
                </select>	
            </div>
            
            <label for="" class="col-form-label">Comentario:</label>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-commenting"></i></span>
              </div>
              <input type="text" class="form-control form-control" placeholder="comentario" name="nvocomentario" id="nvocomentario" value="" onkeyUp="mayuscula(this);" title="comentario adicional" tabindex="3">
            </div>


        <div class="form-group">
			  <label for="" class="col-form-label">Importe adicional: *</label>
			  <div class="input-group mb-3">
				  <div class="input-group-prepend">
						  <span class="input-group-text"><i class="fa fa-dollar"></i></span>
				  </div>
            <input type="number" class="form-control form-control" placeholder="capture importe" name="importeagregaviatico" value="" min=0 step="any"  title="importe" tabindex="4"  required >
          </div>
			  </div>
		
      </div>	<!-- fin modal-body-->
	  
      <div class="modal-footer colorbackModal p-2">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-reply"></i> Cerrar</button>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Guardar</button>
      </div>
	 </form>
    </div>
  </div>
</div>
<!--================================= FIN MODAL AGREGAR VIATICO =======================================*/-->


<!--================================= MODAL COMPROBACION GASTOS =======================================-->
<div class="modal fade" id="modalCheckup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
	 <form name="formularioCheckup" id="formularioCheckup" method="POST">
      <div class="modal-header colorbackModal p-2">
        <h5 class="modal-title" id="ModalCenterTitleCheck"></h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		
      <div class="form-row">

        <div class="col-md-6">
          <label for="recipient-name" class="col-form-label">Fecha gasto:</label>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-calendar-check-o"></i></span>
            </div>
            <input type="date" class="form-control form-control" placeholder="" name="fechagasto" value=""  title="Fecha" required >
            <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
            <input type="hidden"  name="registroid" value="">
          </div>
        </div>

          <div class="col-md-6">
            <label for="" class="col-form-label">Num. docto:</label>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-file-text"></i></span>
              </div>
                <input type="text" class="form-control form-control" placeholder="" name="numerodocto" value="" onkeyUp="mayuscula(this);"  title="Concepto" required >
            </div>
          </div>

      </div>

      <div class="form-group">
			  <label for="" class="col-form-label">Concepto gasto:</label>
			  <div class="input-group mb-3">
				  <div class="input-group-prepend">
						  <span class="input-group-text"><i class="fa fa-automobile"></i></span>
				  </div>
            <input type="text" class="form-control form-control" placeholder="" name="conceptogasto" value="" onkeyUp="mayuscula(this);"  title="Concepto" required >
          </div>
			  </div>

        <div class="form-group">
			  <label for="" class="col-form-label">Importe gasto:</label>
			  <div class="input-group mb-3">
				  <div class="input-group-prepend">
						  <span class="input-group-text"><i class="fa fa-dollar"></i></span>
				  </div>
            <input type="number" class="form-control form-control" placeholder="Capture gasto" name="importegasto" value="" min=0 step="any"  title="Importe gasto" required >
          </div>
			  </div>
		
      </div>	<!-- fin modal-body-->
	  
      <div class="modal-footer colorbackModal p-2">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-reply"></i> Cerrar</button>
        <button type="submit" id="submitGasto" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Guardar</button>
      </div>
	 </form>
    </div>
  </div>
</div>
<!--================================= FIN MODAL COMPROBACION DE GASTOS =======================================-->*/
<!--================================= MODAL  =======================================-->
<div class="modal fade" id="modalReporteViatico" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header colorbackModal p-2">
        <h5 class="modal-title" id="ModalCenterTitleReport"></h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" id="paraimprimir">

      <table style="width:100%" >
        <tr>
            <td style="width:120px"><img src="vistas/img/logo_nuno.png" width="80%"></td>
        
            <td style="background-color:white; width:180px">
              <div style="font-size:10px; text-align:right; line-height:15px;" class="text-center">
                      Av. Rio Coatan No.504, Col. 24 de Junio, Tuxtla Gutiérrez, Chiapas.
              </div>
            </td>
        
          <td style="background-color:white; width:180px">
              <div style="font-size:10px; text-align:right; line-height:15px;" class="text-center">
              Teléfono: Tel: (961)-1407119
              <br>
              brunonunosco1998@gmail.com
              </div>
          </td>
        
          <td style="background-color:white; width:150px; text-align:center; color:red" id="iddoc"></td>

        </tr>
        </table>
        
        <hr class="m-2">

        <div id="app" style="overflow-x:hidden; overflow-y: auto;">
        </div>
		
      </div>	<!-- fin modal-body-->
	  
      <div class="modal-footer colorbackModal p-2">
        <button type="button" class="btn btn-sm  btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
        <!-- <button type="button" class="btn btn-sm btn-primary" onclick="javascript:imprim1(paraimprimir);"><i class="fa fa-print"></i> imprimir</button> -->
      </div>
    </div>
  </div>
</div>  <!-- fin modal-->
<!--================================= FIN MODAL  =======================================-->
<div class="modal fade" id="DescModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                 <h3 class="modal-title">Job Requirements & Description</h3>

            </div>
            <div class="modal-body">
                 <h5 class="text-center"></h5>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default " data-dismiss="modal">Apply!</button>
                <button type="button" class="btn btn-primary">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!--================================= FIN MODAL  =======================================-->*/
<script>
function imprim1(paraimprimir){
var printContents = document.getElementById('paraimprimir').innerHTML;
   w = window.open();
   w.document.write(printContents);
   w.document.close(); // necessary for IE >= 10
    w.focus(); // necessary for IE >= 10
		w.print();
		w.close();
    return true;
  }
</script>

<script defer src="vistas/js/control-viaticos.js?v=31012021"></script> 
