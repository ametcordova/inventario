<div class="card">
    <div class="card-header" id="headingFor">
        <h5 class="mb-0">
            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFor" aria-expanded="false" aria-controls="collapseFor">
                  <h5 class="text-warning">Configuración</h5>
            </button>
        </h5>
    </div>
        <div id="collapseFor" class="collapse" aria-labelledby="headingFor" data-parent="#accordion">
            <div class="card-body">
            <table class="table table-bordered table-sm">
                    <thead class="bg-info">
                        <tr class="text-center">
                        <th scope="col" class="bg-primary">Opción</th>
                        <th scope="col" class="bg-primary">Habilitar</th>
                        <th scope="col">Agregar</th>
                        <th scope="col">Editar</th>
                        <th scope="col">Eliminar</th>
                        <th scope="col">Activar</th>
                        </tr>
                    </thead>

                    <tbody id="checkeadosconfig">
                        <tr>
                            <th scope="row">Usuarios</th>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline habilitaPermisoUsuarios" type="checkbox" value="0"  id="usuarios">
                            </td>

                            <td class="text-center">
                               <input class="form-check-input checkbox-inline rolusuarios" type="checkbox" value="1" id="adiusua">
                            </td>

                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolusuarios" type="checkbox" value="1" id="ediusua">
                            </td>

                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolusuarios" type="checkbox" value="1" id="delusua">
                            </td>

                            <td class="text-center">
                                <input class="form-check-input checkbox-inline rolusuarios" type="checkbox" value="1" id="actusua">
                            </td>
                        </tr>     
                        
                        <tr>
                            <th scope="row">Permisos</th>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline habilitaPermisoPermisos" type="checkbox" value="1"  id="permisos">
                            </td>
                            <td colspan="7" class="text-center">
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Empresa</th>
                            <td class="text-center">
                                <input class="form-check-input checkbox-inline habilitaPermisoEmpresa" type="checkbox" value="1"  id="empresa">
                            </td>
                            <td colspan="7" class="text-center">
                            </td>
                        </tr>

                    </tbody>
            </table>    
                    <div class="text-center pt-2 pb-0">
                        <button class="btn btn-warning btn-sm" id="guardarPermisoConfig" type="button"><i class="fa fa-save"></i> Guardar</button>
                    </div>

            </div>
        </div>
</div>

<script defer src="vistas/js/configura.js?v=01092020"></script>