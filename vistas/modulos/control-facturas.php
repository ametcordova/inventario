<script>
  //evitar que se desconecte.
  document.addEventListener("DOMContentLoaded", () => {
    // Invocamos cada 5 minutos ;
    const milisegundos = 500 * 1000;
    setInterval(() => {
      //console.log("500 segundos.. refrescado")
      fetch("vistas/modulos/refrescar.php");
      // No esperamos la respuesta de la petición porque no nos importa
    }, milisegundos);
  });
</script>

<?php
    $fechaHoy=date("d/m/Y");
    $yearHoy=date("Y");
    $monthHoy=date("m");
    $tabla="usuarios";
    $module="pctfacts";
    $campo="administracion";
    $acceso=accesomodulo($tabla, $_SESSION['id'], $module, $campo);    
?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background-color:darkslategrey;">
    <!-- Content Header (Page header) -->
    <section class="content-header m-0 p-1">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h5 style="color:floralwhite;">Control Facturas:&nbsp; 
                <small><i class="fa fa-th"></i></small>
            </h5>
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
      <div class="card" >

        <div class="card-header border-success mb-2" >

          <div class="col-md-12 d-inline-block px-0" style="background-color:azure;">

            <div class="input-group col-md-3 col-md-auto d-inline-block my-0 nopadding" style="width:fit-content;">
              <?php if(getAccess($acceso, ACCESS_ADD)){?> 
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarFactura" title="Agrega nueva factura"><i class="fa fa-plus-circle"></i> Agregar Factura
                </button> 
                <button class="btn btn-danger btn-sm ml-1" id="btnregresar" onclick="regresar()" type="button" title="Regresar a Inicio"><i class="fa fa-backward"></i> a Inicio</button>
              <?php } ?>           
            </div>

              <?php if(getAccess($acceso, ACCESS_VIEW)){?>           
                <div class="col-md-4 d-sm-inline-block p-0" style="width:fit-content;">	
                    <input type="radio" name="radiofactura" value="todos" >
                      <label class="mr-2">Todos</label>
                    <input type="radio" class="ml-1" name="radiofactura" value="porpagar" checked>
                      <label class="mr-2">Por Pagar</label>
                    <input type="radio" class="ml-1" name="radiofactura" value="pagado">
                      <label class="mr-2">Pagados</label>
                    <input type="radio" class="ml-1" name="radiofactura" value="cancelado">
                      <label >Cancelados</label>
                </div>		

                  <div class="col-md-1 text-center mb-1 d-sm-inline-block">
                    <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            <input type="text" class="form-control form-control-sm" placeholder="" name="filterYear" id="filterYear" value="<?= $yearHoy?>" data-toggle="tooltip" title="Año" >
                    </div>
                  </div>

                  <div class="col-md-1 text-center d-sm-inline-block">
                    <div class="input-group-prepend ml-1">
                            <span class="input-group-text"  title="Selecciona mes de facturación"><i class="fa fa-calendar-o"></i></span>
                            <input type="text" class="form-control form-control-sm" placeholder="M. Ini" name="filterMonthStart" id="filterMonthStart" data-toggle="tooltip" title="Selecciona mes Inicial" >
                    </div>
                  </div>

                  <div class="col-md-1 text-center mt-1 d-sm-inline-block">
                    <div class="input-group-prepend">
                            <span class="input-group-text"  title="Selecciona mes de facturación"><i class="fa fa-calendar-o"></i></span>
                            <input type="text" class="form-control form-control-sm" placeholder="M. Fin" name="filterMonthEnd" id="filterMonthEnd" data-toggle="tooltip" title="Selecciona mes Final" >
                    </div>
                  </div>

                  <div class="col-md-1 d-sm-inline-block text-bold" style="font-size:.8em">
                      <input class="form-check-input checkbox-inline factpagadas" type="checkbox" id="factpagadas">
                      Solo Pagadas
                  </div>


                  <div class="col-md-1 text-center d-sm-inline-block" >
                      <button class="btn btn-success btn-sm" onclick="listarFacturas()" title="Listar facturas"><i class="fa fa-eye"></i> Listar</button>
                      <button class="btn btn-dark btn-sm" onclick="location.reload()" title="Reset filtros"><i class="fa fa-refresh"></i></button>
                  </div>

              <?php } ?>          

          </div>  <!-- col-md-12 -->
     
		
            <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Ocultar">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-tool" onclick="regresar()" data-toggle="tooltip" title="a Inicio">
              <i class="fa fa-times"></i></button>			  
            </div>
		  
		  
        </div>  <!-- fin del card-header -->
 
        <div class="card-body p-1">
        
        <div class="card" >
          
            <!-- <div class="card-header p-0">
              <div class="text-center col-md-12 d-none" style="font-family: 'Play', sans-serif; font-size:1.1em; font-weight:bold;" id="sumaseleccionados">Suma selección s/IVA:
                <label class="sumaseleccion text-info" ></label>
              </div>
            </div>  -->

            <div class="card-body table-responsive-sm p-1">

               <div class="text-center">
                 <a class="toggle-vis btn btn-sm btn-info" data-column="11">#RP</a>
               </div> 

              <table class="table table-bordered compact table-hover table-striped dt-responsive" cellspacing="0" id="TablaFacturas" width="100%">
                <thead class="thead-dark">
                <tr style="font-size:0.80em"> 
					<!-- <th style="width:2%;"><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>-->
                    <th translate="no" style="width:3%;">#Fact</th>
                    <th translate="no" style="width:15%;">Nombre</th>
                    <th translate="no" style="width:14%;">Tipo de Trab.</th>
                    <th translate="no" style="width:6%;"># Orden</th>
                    <th translate="no" style="width:7%;">F. Fact.</th>
                    <th translate="no" style="width:7%;">Subtotal</th>
                    <th translate="no" style="width:6%;">iva</th>
                    <th translate="no" style="width:6%;">iva Ret</th>
                    <th translate="no" style="width:7%;">Importe</th>
                    <th translate="no" style="width:7%;">F. Entregado</th>
                    <th translate="no" style="width:7%;">F.Pag/Can</th>
                    <th translate="no" style="width:3%;">#RP</th>
                    <th translate="no" style="width:5%;">Stat</th>
                    <th translate="no" style="width:7%;" class="text-center">Acción</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot class="thead-dark">
                <tr style="font-size:0.80em">
                    <th>No.</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th colspan="2" class="text-right">Suma selección</th>
                    <th colspan="2" class="sumaseleccion"></th>
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
  
 <!-- ====================================================
            MODAL AGREGAR Factura
 ============================= =======================-->
   <div class="modal fade" id="modalAgregarFactura" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form role="form" name="formularioAgregarFactura" id="formularioAgregarFactura" method="POST">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal p-2">
   
            <h4 class="modal-title">Agregar Factura</h4>
        
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
                      <span class="input-group-text"><i class="fa fa-building-o"></i></span>
              </div>
              <input type="text" class="form-control form-control-sm" placeholder="Nombre Cliente" name="nuevoCliente" id="nuevoCliente" value="" data-toggle="tooltip" data-placement="top" title="Nombre Cliente" tabindex="1">
            </div>

            <div class="form-row">
              <div class="input-group mb-3 col-md-6">
                <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-hashtag"></i></span>
                </div>
                <input type="number" class="form-control form-control-sm" placeholder="Número de Factura" name="nuevaFactura" id="nuevaFactura" value="" data-toggle="tooltip" title="Número de Factura" required tabindex="2" >
                <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
              </div>

              <div class="input-group mb-3 col-md-6">
                <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-sticky-note"></i></span>
                </div>
                <input type="text" class="form-control form-control-sm" placeholder="No. de orden" name="nuevaOrden" id="nuevaOrden" value="" title="No. de orden" tabindex="3">
              </div>
            </div>


          <div class="form-row">
            
          <div class="input-group mb-3 col-md-6">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-calendar"></i></span>
              </div>
              <input type="date" class="form-control form-control-sm" placeholder="Fecha Fact." name="nvaFechaFactura" id="nvaFechaFactura"  value="<?= $fechaHoy?>" data-toggle="tooltip" title="Fecha Factura"  required tabindex="4" >
            </div>

            <div class="input-group mb-3 col-md-6">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-calendar"></i></span>
              </div>
              <input type="date" class="form-control form-control-sm" placeholder="" name="nvaFechaEntregado" id="nvaFechaEntrega" value="<?= $fechaHoy?>" data-toggle="tooltip" title="Fecha Entregado" tabindex="5">
            </div>

          </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-align-justify"></i></span>
              </div>
				<textarea class="form-control form-control-sm" name="nvoTipoTrabajo" id="nvoTipoTrabajo" value="" onkeyUp="mayuscula(this);" placeholder="Descripción Factura"  data-toggle="tooltip" title="Tipo de Trabajo" tabindex="6" cols="80" rows="2"></textarea>
            </div>

        <div class="form-row">			
			
            <div class="input-group mb-3 col-md-6">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-plus"></i></span>
              </div>
              <input type="number" class="form-control form-control-sm" placeholder="Subtotal" name="nvoSubtotal" id="nvoSubtotal" value="" step="any" data-toggle="tooltip" data-placement="top" title="Subtotal" tabindex="7" required>
            </div>

            <div class="input-group mb-3 col-md-6">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-percent"></i></span>
              </div>
              <input type="number" class="form-control form-control-sm" placeholder="Iva" name="nvoIva" id="nvoIva" value="" step="any" data-toggle="tooltip" data-placement="top" title="Iva" tabindex="8" required>
            </div>

        </div>			

        <div class="form-row">			
			
            <div class="input-group mb-3 col-md-6">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-usd"></i></span>
              </div>
              <input type="number" class="form-control form-control-sm" placeholder="Retención" name="nvaRetencion" id="nvaRetencion" value="" step="any" data-toggle="tooltip" data-placement="top" title="Retención" tabindex="9" required>
            </div>

            <div class="input-group mb-3 col-md-6">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-money"></i></span>
              </div>
              <input type="number" class="form-control form-control-sm" placeholder="Importe Fact" name="nvoImporteFactura" id="nvoImporteFactura" step="any" data-toggle="tooltip" data-placement="top" title="Importe factura" tabindex="10" required>
            </div>
			
        </div>			


            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-file-text"></i></span>
              </div>
              <input type="text" class="form-control form-control-sm" placeholder="Observaciones" name="nvaObservacion" id="nvaObservacion" value="" onkeyUp="mayuscula(this);" placeholder="Observación" data-toggle="tooltip" title="Observación" tabindex="11">
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-unlock-alt"></i></span>
              </div>
                <select class="form-control form-control-sm" name="nvoStatusFactura" id="nvoStatusFactura" required tabindex="12" placeholder="" data-toggle="tooltip" title="Estatus" >
                <option value="" selected>Seleccione</option>
                <option value="1">Pagado</option>
                <option value="0">Sin pagar</option>
                </select>	
            </div>
			
            <div class="input-group mb-1">
                <label for="exampleInputFile">Subir PDF:&nbsp</label>
                <p class="help-block m-0 p-0">Peso máximo 2mb.</p>
                <div class="input-group">
                     <input type="file" class="nuevoPdf" id="nuevoPdf" name="nuevoPdf" accept="application/pdf">
                </div>

           </div>
                                                                
         </div>
        </div>
      
      </div>    <!-- fin del modal-body -->

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal p-2">
       
        <button type="button" class="btn btn-primary btn-sm float-left salirfrm" data-dismiss="modal" tabindex="12"><i class="fa fa-reply"></i> 
        Salir
        </button>
        <button type="submit" class="btn btn-success btn-sm enviarfrm" tabindex="13"><i class="fa fa-save"></i> Guardar</button>
        <div class="spin">
            <button type="button" class="btn btn-sm btn-warning"> Espere... <i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i></button>
        </div>

      </div>
      
     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  


<!-- =========== MODAL EDITAR Factura ============-->
  <div class="modal fade" id="modalEditarFactura" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
   
    <div class="modal-content">
    <form name="formularioEditFactura" id="formularioEditFactura" method="POST" enctype="multipart/form-data">
      <!-- Modal Header -->
      <div class="modal-header colorbackModal p-1">
   
            <h5 class="modal-title">Editar Factura</h5>
        
            <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      

      <!-- Modal body -->
      <div class="modal-body">
           
        <div class="box-body">
        </div>   

        <div class="card card-info mb-1 pb-1" style="background-color:honeydew;">

         <div class="card-body">

           <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-building-o"></i></span>
              </div>
              <input type="text" class="form-control form-control-sm" placeholder="Nombre Cliente" name="editaCliente" id="editaCliente" value="" data-toggle="tooltip" data-placement="top" title="Nombre Cliente" tabindex="2">
            </div>

            <div class="form-row">            
              <div class="input-group mb-3 col-md-6">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-hashtag"></i></span>
                </div>
                <input type="number" class="form-control form-control-sm" placeholder="Número de Factura" name="editaFactura" id="editaFactura" value="" required tabindex="1" data-toggle="tooltip" title="Número de Factura" readonly>
                <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
                <input type="hidden"  name="idregistro" value="">
              </div>

              <div class="input-group mb-3 col-md-6">
                <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-sticky-note"></i></span>
                </div>
                <input type="text" class="form-control form-control-sm" placeholder="No. de orden" name="editaOrden" id="editaOrden" value="" title="No. de orden" tabindex="3">
              </div>
            </div>


          <div class="form-row">
            
          <div class="input-group mb-3 col-md-6">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-calendar"></i></span>
              </div>
              <input type="date" class="form-control form-control-sm" placeholder="Fecha Factura Factura" name="editaFechaFactura" value="" data-toggle="tooltip" title="Fecha Factura"  required tabindex="4" >
            </div>

            <div class="input-group mb-3 col-md-6">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-calendar"></i></span>
              </div>
              <input type="date" class="form-control form-control-sm" placeholder="" name="editaFechaEntregado" value="" data-toggle="tooltip" title="Fecha Entregado" tabindex="5">
            </div>

          </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-align-justify"></i></span>
              </div>
			  <textarea class="form-control form-control-sm" name="editaTipoTrabajo" id="editaTipoTrabajo" onkeyUp="mayuscula(this);" placeholder="Descripción Factura"  data-toggle="tooltip" title="Tipo de Trabajo" tabindex="6" cols="80" rows="2"></textarea>
              <!-- <input type="text" class="form-control form-control-sm" name="editaTipoTrabajo" id="editaTipoTrabajo" value="" onkeyUp="mayuscula(this);" data-toggle="tooltip" title="Tipo de Trabajo" tabindex="5">-->
            </div>

          <div class="form-row">			
			
            <div class="input-group mb-3 col-md-6">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-plus"></i></span>
              </div>
              <input type="number" class="form-control form-control-sm" placeholder="Subtotal" name="editaSubtotal" id="editaSubtotal" value="" step="any" data-toggle="tooltip" data-placement="top" title="Subtotal" tabindex="7" required>
            </div>

            <div class="input-group mb-3 col-md-6">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-percent"></i></span>
              </div>
              <input type="number" class="form-control form-control-sm" placeholder="Iva" name="editaIva" id="editaIva" value="" step="any" data-toggle="tooltip" data-placement="top" title="Iva" tabindex="8" required>
            </div>

          </div>				

        <div class="form-row">			
          <div class="input-group mb-3 col-md-6">
                <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-usd"></i></span>
                </div>
                <input type="number" class="form-control form-control-sm" placeholder="Retención" name="editaRetencion" id="editaRetencion" value="" step="any" data-toggle="tooltip" data-placement="top" title="Retención" tabindex="9" required>
              </div>

              <div class="input-group mb-3 col-md-6">
                <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa fa-money"></i></span>
                </div>
                <input type="number" class="form-control form-control-sm" placeholder="Importe Factura" name="editaImporteFactura" id="editaImporteFactura" value="" step="any" data-toggle="tooltip" data-placement="top" title="Importe factura" tabindex="10" required>
          </div>
        </div>				

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-file-text"></i></span>
              </div>
			 <textarea class="form-control form-control-sm" name="editaObservacion" id="editaObservacion" onkeyUp="mayuscula(this);" placeholder="Observación" data-toggle="tooltip" title="Observación"  tabindex="11" rows="1"></textarea>
             <!-- <input type="text" class="form-control form-control-sm" placeholder="Observaciones" name="editaObservacion" id="editaObservacion" value="" onkeyUp="mayuscula(this);" placeholder="Observación" data-toggle="tooltip" title="Observación"  tabindex="7" >-->
            </div>

		    <div class="form-row">
		
            <div class="input-group mb-3 col-md-6">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-calendar-check-o"></i></span>
              </div>
              <input type="date" class="form-control form-control-sm" placeholder="Fecha pagado" name="editaFechaPagado" value="" data-toggle="tooltip" title="Fecha Pagado" tabindex="12">
            </div>

            <div class="input-group mb-3 col-md-6">
              <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-unlock-alt"></i></span>
              </div>
                <select class="form-control form-control-sm" name="editaStatusFactura" id="editaStatusFactura" required tabindex="13" placeholder="" data-toggle="tooltip" title="Estatus" >
                <option value="" selected>Seleccione</option>
                <option value="1">Pagado</option>
                <option value="0">Sin pagar</option>
                <option value="2">Cancelado</option>
                </select>	
            </div>
			
        </div>
		
            <div class="input-group mb-0">
                <label for="exampleInputFile">Subir PDF:&nbsp</label>
                <p class="help-block m-0 p-0">Peso máximo 2mb.</p>
                <div class="input-group">
                     <input type="file" class="nuevoPdf" id="editarPdf" name="editarPdf" accept="application/pdf">
                </div>

              <div class="input-group mt-2 mb-0" id="downfile">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-file-pdf-o"></i></span>
                </div>
                <input type="text" class="form-control form-control-sm" placeholder="Ningun archivo subido" name="actualPdf" id="actualPdf" value="" title="Archivo actual" readonly>
              </div>
            </div>
		   
         </div> <!-- fin card-body -->

        </div>  <!-- fin card -->
      
      </div>

      <!-- Modal footer -->
      <div class="modal-footer colorbackModal p-2">
       
        <button type="button" class="btn btn-sm btn-primary float-left salirfrm" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
        <button type="submit" class="btn btn-sm btn-success enviarfrm"><i class="fa fa-save"></i> Guardar Cambios</button>
        <div class="spin">
            <button type="button" class="btn btn-sm btn-warning"> Espere... <i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i></button>
        </div>
        <!--  <div hidden id="spin"></div> -->

      </div>

     </form>
    </div> <!-- fin del modal-content -->
  </div>
</div>  

<!--================================= MODAL PAGAR FACTURA =======================================-->
<!-- Modal -->
<!-- <div class="modal fade" id="modalEditarFactura" data-backdrop="static" data-keyboard="false" tabindex="-1"> -->
<div class="modal fade" id="modalPagarFactura" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
	 <form name="formularioPagoFactura" id="formularioPagoFactura" method="POST">
      <div class="modal-header colorbackModal p-2">
        <h5 class="modal-title" id="ModalCenterTitleFact"></h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Fecha Pagado:</label>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-calendar-check-o"></i></span>
              </div>
              
              <input type="date" class="form-control form-control-sm" placeholder="" name="fechaPagoFactura" data-toggle="tooltip" title="Fecha Pago Factura" required >
              <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
              <input type="hidden"  name="registroid" value="">
            </div>
          </div>
		
          <div class="form-group">
            <label for="recipient-name" class="col-form-label"> No. Complemento:</label>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-sticky-note"></i></span>
              </div>
                <input type="text" class="form-control form-control-sm" placeholder="No. de complemento de pago" name="complementoPagoFact" value="" data-toggle="tooltip" title="# complemento Pago Factura">
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
<!--================================= FIN MODAL PAGAR FACTURA =======================================-->


<!--================================= MODAL AGREGA GASTOS A FACTURA =======================================-->
<!-- === MODAL AGREGAR SERIES ==-->
<div class="modal fade" id="modalAgregarGastos" data-backdrop="static" data-keyboard="false" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header p-1 colorbackModal">
          <h5 class="modal-title" id="ModalCenterTitleGastos"></h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form role="form" method="POST" id="formAgregaGastos">
        <!-- Modal body -->
        <div class="modal-body">
          <div class="card card-info">
            <div class="card-body">
                <div class="form-row">
                     <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">

                     <div class="form-group col-md-1">
                        <label class="control-label" for=""><i class="fa fa-hashtag"></i>Fact.</label>
                        <input type="text" class="form-control form-control-sm" name="numfactura" id="numfactura" value="" placeholder="" tabindex="1" title="Numero de Factura" readonly>
                      </div>
                      <div class="form-group col-md-3">
                        <label class="control-label" for=""><i class="fa fa-calendar"></i> Fecha Gasto</label>
                        <input type="date" class="form-control form-control-sm" name="fechagasto" id="fechagasto" value="" placeholder="" tabindex="2" title="Numero de docto de entrada">
                      </div>
                      <div class="form-group col-md-2">
                        <label class="control-label" for=""><i class="fa fa-hashtag"></i> Viático.</label>
                        <input type="text" class="form-control form-control-sm" name="idviatico" id="idviatico" value="" placeholder="" tabindex="1" title="" >
                      </div>
                      <div class="form-group col-md-4">
                        <label class="control-label" for=""><i class="fa fa-file-text"></i> Concepto Gasto</label>
                        <input type="text" class="form-control form-control-sm" name="conceptoGasto" id="conceptoGasto" value="" placeholder="" tabindex="1" title="" >
                      </div>
                      <div class="form-group col-md-2">
                        <label class="control-label" for=""><i class="fa fa-dollar"></i> Importe</label>
                        <input type="text" class="form-control form-control-sm" name="importeGasto" id="importeGasto" value="" placeholder="" tabindex="1" title="" >
                      </div>

                </div>  <!-- FIN DEL FORM-ROW -->		      


            </div>  <!--Fin del card-body -->
          </div>   <!--Fin del card card-info -->
        </div> <!--fin del modal-body -->

        <!-- Modal footer -->
        <div class="modal-footer p-1 colorbackModal">
          <button type="button" class="btn btn-primary btn-sm float-left" data-dismiss="modal"><i class="fa fa-reply"></i> Salir</button>
          <button type="submit" class="btn btn-success btn-sm" id="btnGuardarGastos"><i class="fa fa-save"></i> Guardar</button>
        </div> <!-- fin Modal footer -->

      </form>  <!-- fin del form -->
    </div> <!-- fin del modal-content -->
  </div>   <!-- fin del modal-lg -->
</div>    <!-- fin del modal -->
<!--================================= FIN MODAL =======================================-->
<!-- Modal -->
<div class="modal fade" id="modal_fecha_pago" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
    <form name="formFechaPagoFactura" id="formFechaPagoFactura" method="POST">    

      <div class="modal-header p-1">
        <h6 class="modal-title" id="numerodefactura"></h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body pt-3 pb-1 bg-warning">

          <div class="form-group">
              <input type="date" class="form-control form-control-sm" name="fechaPagoFactura" value="" title="Fecha Pago Factura" required >
              <input type="hidden"  name="idDeUsuario" value="<?php echo $_SESSION['id'];?>">
              <input type="hidden"  name="registroid" value="">
          </div>

        </div>

        <div class="modal-footer p-1">
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
        </div>
        
      </form>  <!-- fin del form -->
    </div>
  </div>
</div>
<script defer src="vistas/js/control-facturas.js?v=13092022"></script>
<!--===========================================================================================-->

<!--================================= MODAL VER EXPEDIENTE DE FACTURA =======================================-->
<!-- Modal para visualizar imagen del producto -->
<!-- <div class="modal fade" id="modalVerFactura" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-xlg" role="document">
    <div class="modal-content">
	
      <div class="modal-header m-1 p-1 bg-warning">
        <h6 class="modal-title text-center" id="ModalCenterTitle"></h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	  
      <div class="modal-body text-center">
		  <div id="target">
			  <div id="cover" class="d-none text-center">
				<img src="vistas/img/ajax-loadering.gif" alt="">
			  </div>
			  <div id="seepdf">
				
			 </div>
		  </div>
	 </div>	
	
      <div class="modal-footer m-1 p-1 bg-warning">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
	  
    </div> fin modal content --->
	
  <!-- </div>  fin modal-dialog --->
  
<!-- </div>	fin modal - -->

