
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Productos Financieros <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button>
						  <a href="../Reportes/rptarticulos.php" target="_blank"><button class="btn btn-info" id="btnreporte">Reporte</button></a>
						  <button class="btn btn-danger" id="btnregresar" onclick="regresar()" type="button"><i class="fa fa-arrow-circle-left"></i> Regresar</button></h1>
						  </h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Acciones</th>
                            <th>Clave</th>
                            <th>Nombre</th>
                            <th>Tasa</th>
                            <th>Mora</th>
                            <th>D.Gracia</th>
                            <th>Seguro</th>
							<th>Status</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Acciones</th>
                            <th>Clave</th>
                            <th>Nombre</th>
                            <th>Tasa</th>
                            <th>Mora</th>
                            <th>D.Gracia</th>
                            <th>Seguro</th>
							<th>Status</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="container" id="formularioregistros">
					 <div class="row"
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Clave(*):</label>
                            <input type="hidden" name="idproducto" id="idproducto">
                            <input type="text" class="form-control" name="clave" id="clave" maxlength="100" placeholder="Clave" font style="text-transform: uppercase" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Nombre(*):</label>
                            <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Nombre del Producto" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Reg.Condusef(*):</label>
                            <input type="text" class="form-control" name="regcondusef" id="regcondusef" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Tasa s/Iva(*):</label>
                            <input type="text" class="form-control" name="tasasin" id="tasasin" maxlength="6" placeholder="Tasa Mensual sin Iva">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Tasa Mora s/Iva(*):</label>
                            <input type="text" class="form-control" name="tasamora" id="tasamora" placeholder="Tasa Mora Mensual s/Iva">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>iva(*):</label>
                            <input type="number" class="form-control" name="iva" id="iva" placeholder="IVA">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Dias de Gracia:</label>
                            <input type="number" class="form-control" name="ddegracia" id="ddegracia" placeholder="">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Seguro:</label>
                            <input type="number" class="form-control" name="seguro" id="seguro" placeholder="">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Plazo Maximo:</label>
                            <input type="text" class="form-control" name="plazomax" id="plazomax" placeholder="Plazo Max. del Crédito en meses">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Dias de Corte:</label>
							<select class="form-control select-picker" name="ddecorte" id="ddecorte" required>
                              <option value=0>Segun Crédito</option>
                              <option value=15>Cada 15 días</option>
                              <option value=30>30 de Cada Mes</option>
                            </select>							
                          </div>

                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                          </div>
                        </form>
                    </div>
                    <!--Fin centro -->
					</div>
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
