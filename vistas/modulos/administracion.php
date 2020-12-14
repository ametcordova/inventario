<div id="accordion" style="display:none;">
            <div class="card">
                <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        <h5 class="text-info">Administración</h5>
                    </button>
                </h5>
                </div>

                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <table class="table table-bordered table-sm">
                    <thead class="bg-info">
                        <tr class="text-center">
                        <th scope="col" class="bg-primary">Opción</th>
                        <th scope="col" class="bg-primary">Habilitar</th>
                        <th scope="col">Agregar</th>
                        <th scope="col">Editar</th>
                        <th scope="col">Visualizar</th>
                        <th scope="col">Eliminar</th>
                        <th scope="col">Imprimir</th>
                        <th scope="col">Capturar</th>
                        <th scope="col">Activar</th>
                        </tr>
                    </thead>

                    <tbody id="checkeadosadmin">
                        <tr>
                        <th scope="row">Salidas</th>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline habilitaPermisoSalidas" type="checkbox" value="1" id="psalidas">
                            </td>
                            <td  class="text-center">
                                <input class="form-check-input checkbox-inline rolSalidasAlm" type="checkbox" value="1" id="adipsal">
                            </td>
                            <td  class="text-center">
                                <input class="form-check-input checkbox-inline rolSalidasAlm" type="checkbox" value="1" id="edipsal">
                            </td>
                            <td  class="text-center">
                                <input class="form-check-input checkbox-inline rolSalidasAlm" type="checkbox" value="1" id="viepsal">
                            </td>
                            <td  class="text-center">
                                <input class="form-check-input checkbox-inline rolSalidasAlm" type="checkbox" value="1" id="delpsal">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolSalidasAlm" type="checkbox" value="1" id="pripsal">
                            </td>
                            <td colspan="2" class="text-center">
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">Entradas</th>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline habilitaPermisoEntradas" type="checkbox" value="1"  id="pentradas">
                            </td>
                            <td  class="text-center">
                                <input class="form-check-input checkbox-inline rolEntradasAlm" type="checkbox" value="1" id="adipent">
                            </td>
                            <td  class="text-center">
                                <input class="form-check-input checkbox-inline rolEntradasAlm" type="checkbox" value="1" id="edipent">
                            </td>
                            <td  class="text-center">
                                <input class="form-check-input checkbox-inline rolEntradasAlm" type="checkbox" value="1" id="viepent">
                            </td>
                            <td  class="text-center">
                                <input class="form-check-input checkbox-inline rolEntradasAlm" type="checkbox" value="1" id="delpent">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolEntradasAlm" type="checkbox" value="1" id="pripent">
                            </td>
                            <td colspan="2" class="text-center">
                            </td>
                        </tr>

                        <tr>
                        <th scope="row">Capturar Series</th>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline habilitaPermisoCapSeries" type="checkbox" value="1"  id="pcapseries">
                            </td>
                            <td  class="text-center">
                                <input class="form-check-input checkbox-inline rolcapseries" type="checkbox" value="1" id="adipcap">
                            </td>
                            <td  class="text-center">
                                <input class="form-check-input checkbox-inline rolcapseries" type="checkbox" value="1" id="edipcap">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolcapseries" type="checkbox" value="1" id="viepcap">
                            </td>
                            <td colspan="3" class="text-center">
                            </td>

                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolcapseries" type="checkbox" value="1" id="actpcap">
                            </td>
                        </tr>

                        <tr>

                        <th scope="row">Ajuste de Inventario</th>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline habilitaPermisoAjusteInv" type="checkbox" value="1"  id="ajusteinv">
                            </td>
                            <td  class="text-center">
                                <input class="form-check-input checkbox-inline rolajusteinv" type="checkbox" value="1" id="adiajus">
                            </td>
                            <td  class="text-center">
                                <input class="form-check-input checkbox-inline rolajusteinv" type="checkbox" value="1" id="ediajus">
                            </td>
                            <td></td>
                            <td  class="text-center">
                                <input class="form-check-input checkbox-inline rolajusteinv" type="checkbox" value="1" id="vieajus">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolajusteinv" type="checkbox" value="1" id="priajus">
                            </td>
                            <td colspan="3" class="text-center">
                            </td>

                        </tr>

                        <tr>
                        <th scope="row">Devolución al Almacén</th>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline habilitaPermisoDevAlmacen" type="checkbox" value="1"  id="pdevalm">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline roldevalm" type="checkbox" value="1" id="adipdev">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline roldevalm" type="checkbox" value="1" id="edipdev">
                            </td>
                            <td class="text-center">
                            <input class="form-check-input checkbox-inline roldevalm" type="checkbox" value="1" id="viepdev">
                            </td>
                            <td colspan="3" class="text-center">
                            </td>                        
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline roldevalm" type="checkbox" value="1" id="actpdev">
                            </td>
                        </tr>

                        <tr>
                        <th scope="row">Control de Facturas</th>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline habilitaPermisoCtrlFact" type="checkbox" value="1"  id="pctfacts">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolctrlfact" type="checkbox" value="1" id="adipctf">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolctrlfact" type="checkbox" value="1" id="edipctf">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolctrlfact" type="checkbox" value="1" id="viepctf">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolctrlfact" type="checkbox" value="1" id="delpctf">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolctrlfact" type="checkbox" value="1" id="pripctf">
                            </td>
                            <td class="text-center">    <!-- OPCION CAPTURAR -->
                                <input class="form-check-input checkbox-inline rolctrlfact" type="checkbox" value="1" id="selpctf">
                            </td>                   
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolctrlfact" type="checkbox" value="1" id="actpctf">
                            </td>                        
                        </tr>

                        <tr>
                            <th scope="row">Control de Viáticos</th>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline habilitaPermisoCtrlViaticos" type="checkbox" value="1"  id="pctviaticos">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolctrlviatico" type="checkbox" value="1" id="adipctv">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolctrlviatico" type="checkbox" value="1" id="edipctv">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolctrlviatico" type="checkbox" value="1" id="viepctv">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolctrlviatico" type="checkbox" value="1" id="delpctv">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolctrlviatico" type="checkbox" value="1" id="pripctv">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolctrlviatico" type="checkbox" value="1" id="selpctv">
                            </td>                   
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolctrlviatico" type="checkbox" value="1" id="actpctv">
                            </td>                        
                        </tr>

                        <tr>
                            <th scope="row">OS Villa</th>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline habilitaPermisoOsVila" type="checkbox" value="1"  id="posvilla">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolosvilla" type="checkbox" value="1" id="adiposvi">
                            </td>
                            <td class="text-center">
                                
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolosvilla" type="checkbox" value="1" id="vieposvi">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolosvilla" type="checkbox" value="1" id="delposvi">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolosvilla" type="checkbox" value="1" id="priposvi">
                            </td>
                            <td class="text-center">
                                
                            </td>                   
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolosvilla" type="checkbox" value="1" id="actposvi">
                            </td>                        
                        </tr>
                        
                        <tr>
                            <th scope="row">Respaldo BD</th>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline habilitaPermisoRespaldo" type="checkbox" value="1"  id="prespaldo">
                            </td>
                            <td colspan="7" class="text-center">
                            </td>
                        </tr>

                    </tbody>
                    </table>                    
                    <div class="text-center pt-2 pb-0">
                        <button class="btn btn-info btn-sm" id="guardarPermisoAdmin" type="button"><i class="fa fa-save"></i> Guardar</button>
                    </div>

                </div>
                </div>
            </div>
            <script defer src="vistas/js/administracion.js"></script>