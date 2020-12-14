 <div class="card">
     <div class="card-header" id="headingThree">
         <h5 class="mb-0">
             <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                 <h5>Reportes</h5>
             </button>
         </h5>
     </div>
     <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
        <div class="card-body">
            <table class="table table-bordered table-sm">
                 <thead class="bg-info">
                     <tr class="text-center">
                         <th scope="col" class="bg-primary">Opción</th>
                         <th scope="col" class="bg-primary">Habilitar</th>
                         <th scope="col">Visualizar</th>
                         <th scope="col">Imprimir</th>
                         <th scope="col">&nbsp &nbsp &nbsp   </th>
                         <th scope="col">&nbsp &nbsp &nbsp   </th>
                     </tr>
                 </thead>

                 <tbody id="checkeadosreportes">

                    <tr>
                         <th scope="row">Rep. de Salidas</th>
                         <td class="text-center">
                             <input class="form-check-input checkbox-inline habilitaPermisoRepSalidas" type="checkbox" value="0" id="rsalidas">
                         </td>

                         <td class="text-center">
                            <input class="form-check-input checkbox-inline rolrepsalidas" type="checkbox" value="1"  id="viersal">
                         </td>

                         <td class="text-center">
                            <input class="form-check-input checkbox-inline rolrepsalidas" type="checkbox" value="1" id="prirsal"> 
                         </td>

                         <td colspan="2" class="text-center">
                         </td>
                    </tr>


                     <tr>
                         <th scope="row">Rep. de Entradas</th>
                         <td class="text-center">
                             <input class="form-check-input checkbox-inline habilitaPermisoRepEntradas" type="checkbox" value="1" id="rentradas">
                         </td>

                         <td class="text-center">
                            <input class="form-check-input checkbox-inline rolrepentradas" type="checkbox" value="1"  id="vierent">
                         </td>

                         <td class="text-center">
                            <input class="form-check-input checkbox-inline rolrepentradas" type="checkbox" value="1" id="prirent"> 
                         </td>

                         <td colspan="2" class="text-center">
                         </td>
                     </tr>

                     <tr>
                         <th scope="row">Rep. de Inventario</th>
                         <td class="text-center">
                             <input class="form-check-input checkbox-inline habilitaPermisoRepInventarios" type="checkbox" value="0" id="rinventarios">
                         </td>

                         <td class="text-center">
                            <input class="form-check-input checkbox-inline rolrepinv" type="checkbox" value="1"  id="vierinv">
                         </td>

                         <td class="text-center">
                            <input class="form-check-input checkbox-inline rolrepinv" type="checkbox" value="1" id="pririnv"> 
                         </td>

                         <td colspan="2" class="text-center">
                         </td>
                     </tr>
              
                 </tbody>

             </table>

             <div class="text-center pt-2 pb-0">
                 <button class="btn btn-primary btn-sm" id="guardarPermisoRep" type="button"><i class="fa fa-save"></i> Guardar</button>
             </div>

        </div>      <!--fin de card-body   -->
     </div>
 </div> <!--fin de card   -->
 <script defer src="vistas/js/report.js?v=02092020"></script>