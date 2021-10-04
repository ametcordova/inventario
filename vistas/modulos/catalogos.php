<div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <h5 class="text-success">Catálogos</h5>
                        </button>
                    </h5>
                </div>
              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                <!-- <form role="form" id="formPermisoCat" method="post">-->
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

                    <tbody id="checkeadoscata">
                        
                        <tr>
                        <th scope="row">Productos</th>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline habilitaPermisoProd" type="checkbox" value="1" id="pproductos">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolproducto" type="checkbox" value="1" id="adippro">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolproducto" type="checkbox" value="1"  id="edippro">
                        </td>
                        <td class="text-center">
                            
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolproducto" type="checkbox" value="1" id="delppro">
                        </td>
                        <td class="text-center">
                            
                        </td>
                        <td class="text-center">
                            
                        </td>
                        <td class="text-center">
                            
                        </td>
                        </tr>

                        <tr>
                        <th scope="row">Proveedores</th>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline habilitaPermisoProveedor" type="checkbox" value="1" id="proveedores">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolproveedor" type="checkbox" value="1" id="adiprov">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolproveedor" type="checkbox" value="1" id="ediprov">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolproveedor" type="checkbox" value="1" id="vieprov">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolproveedor" type="checkbox" value="1" id="delprov">
                        </td>
                        <td class="text-center">

                        </td>
                        <td class="text-center">

                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolproveedor" type="checkbox" value="1" id="actprov">
                        </td>
                        </tr>     

                        <tr>
                        <th scope="row">Clientes</th>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline habilitaPermisoCliente" type="checkbox" value="1" id="pclientes">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolcliente" type="checkbox" value="1" id="adipcli">
                        </td>
                        <td class="text-center rolcliente">
                            <input class="form-check-input checkbox-inline rolcliente" type="checkbox" value="1" id="edipcli">
                        </td>
                        <td class="text-center rolcliente">
                            <input class="form-check-input checkbox-inline rolcliente" type="checkbox" value="1" id="viepcli">
                        </td>
                        <td class="text-center rolcliente">
                            <input class="form-check-input checkbox-inline rolcliente" type="checkbox" value="1" id="delpcli">
                        </td>
                        <td class="text-center rolcliente">
                            <input class="form-check-input checkbox-inline rolcliente" type="checkbox" value="1" id="pripcli">
                        </td>
                        <td class="text-center rolcliente">
                            <input class="form-check-input checkbox-inline rolcliente" type="checkbox" value="1" id="selpcli">
                        </td>
                        <td class="text-center rolcliente">
                            <input class="form-check-input checkbox-inline rolcliente" type="checkbox" value="1" id="actpcli">
                        </td>
                        </tr>                        

                        <tr>
                        <th scope="row">Técnicos</th>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline habilitaPermisoTecnicos" type="checkbox" value="1" id="ptecnicos">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline roltecnicos" type="checkbox" value="1" id="adiptec">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline roltecnicos" type="checkbox" value="1" id="ediptec">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline roltecnicos" type="checkbox" value="1" id="vieptec">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline roltecnicos" type="checkbox" value="1" id="delptec">
                        </td>
                        <td class="text-center">

                        </td>
                        <td class="text-center">

                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline roltecnicos" type="checkbox" value="1" id="actptec">
                        </td>
                        </tr>   

                        <tr >
                        <th scope="row">Categorias</th>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline habilitaPermisoCategoria" type="checkbox" value="1" id="pcategorias">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolcategoria" type="checkbox" value="1" id="adipcat">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolcategoria" type="checkbox" value="1"  id="edipcat">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolcategoria" type="checkbox" value="1"  id="viepcat">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolcategoria" type="checkbox" value="1" id="delpcat">
                        </td>
                        <td class="text-center">
                        </td>
                        <td class="text-center">
                        </td>
                        <td class="text-center">
                        </td>
                        </tr>
                        

                        <tr>
                        <th scope="row">U. de Medidas</th>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline habilitaPermisoMedida" type="checkbox" value="0" id="pmedidas">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolmedida" type="checkbox" value="1" id="adipmed">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolmedida" type="checkbox" value="1" id="edipmed">
                        </td>

                        <td class="text-center">
                        </td>

                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolmedida" type="checkbox" value="1" id="delpmed">
                        </td>
                        <td class="text-center">

                        </td>
                        <td class="text-center">
                        </td>

                        <td class="text-center">
                        </td>
                        </tr>                        

                        <tr>
                        <th scope="row">Crear Almacén</th>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline habilitaPermisoAlmacen" type="checkbox" value="0" id="palmacenes">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolalmacen" type="checkbox" value="1" id="adipalm">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolalmacen" type="checkbox" value="1" id="edipalm">
                        </td>

                        <td class="text-center">
                        </td>

                        <td class="text-center">
                            <input class="form-check-input checkbox-inline rolalmacen" type="checkbox" value="1" id="delpalm">
                        </td>
                        <td class="text-center">

                        </td>
                        <td class="text-center">
                        </td>

                        <td class="text-center">
                        </td>
                        </tr>  

                        <tr>
                        <th scope="row">Tipos de Mov.</th>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline habilitaPermisoTipMov" type="checkbox" value="0" id="ptiposmov">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline roltipomov" type="checkbox" value="1" id="aditipo">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline roltipomov" type="checkbox" value="1" id="editipo">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline roltipomov" type="checkbox" value="1" id="vietipo">
                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline roltipomov" type="checkbox" value="1" id="deltipo">
                        </td>
                        <td class="text-center">

                        </td>
                        <td class="text-center">

                        </td>
                        <td class="text-center">
                            <input class="form-check-input checkbox-inline roltipomov" type="checkbox" value="1" id="acttipo">
                        </td>
                        </tr>                                            

                    </tbody>

                </table> 
                
                   <div class="text-center pt-2 pb-0">
                    <button class="btn btn-success btn-sm" id="guardarPermisoCat"  type="button"><i class="fa fa-save"></i> Guardar</button>
                   </div>
                <!-- </form> -->
                </div>
              </div>
            </div>  <!--fin de card   -->
            <script defer src="vistas/js/catalogos.js?v=02092020"></script>